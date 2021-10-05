<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monev_si extends MX_Controller {
    
    private $prefix         = 'monev_si';
    private $table_db       = 'm_action_plan';
    private $title          = 'SI MONITORING & EVALUATION';
    private $url            = 'app/monev_si';

    function __construct() {
        parent::__construct();
        $this->middleware('guest', 'forbidden');
    }

    public function index($id_si='', $year='', $month='')
    {
        csrf_init();
        $data['title']      = $this->title;
        $data['url']        = $this->url;
        $data['breadcrumb'] = [$this->title => $this->url];

        //cek id_si
        $arr = [];
        if($id_si != ''){
            $arr = @$this->m_global->getDataAll('m_si', null,  ['id'=>$id_si])[0];
            $data['id_bsc']         = $arr->id_bsc;
            $data['id_si']          = $id_si;
            $data['year']           = $year;
            $data['month']          = $month;
        }else{
            $data['id_bsc']         = 1;
            $data['year']           = date('Y');
        }
        
        //bsc
        $data['bsc'] = $this->m_global->getDataAll('m_bsc', null,  ['is_active'=>'t'], '*', null, "name ASC");
        
        //periode
        $data['periode'] = $this->m_global->getDataAll('m_periode', null, ['is_active'=>'t'], '*', null, "start_year ASC");
        $data['start_year'] = '';
        $data['end_year'] = '';
        $i=0;
        foreach($data['periode'] as $row){
            $i++;
            if($i == 1){
                $data['start_year'] = $row->start_year;
            }
            if($i == count($data['periode'])){
                $data['end_year'] = $row->end_year;
            }
        }
        $js['custom'] = ['table_monev_si'];
        $this->template->display($this->url.'/v_'.$this->prefix, $data, $js);
    }



    public function table_monev_si()
    {    
        // load model view 
        $this->load->model('app/m_monev_si','m_monev_si');

        //search default
        $where  = [];
        $whereE = " a.\"is_active\" = 't' ";

        //cek is active
        if(isset($_REQUEST['filter'])){
            foreach ($_REQUEST['filter'] as $row) {
                if(@$row['name'] == 'is_active'){ $whereE = " 0=0 "; }
            }
        }
        
        // ============= cek role =================
        //cek role PIC SI
        if(h_session('ROLE_ID') == '5'){ 
            //status selain draft
            $whereE .= " AND a.status_si != '1' " ;
            //ambil SI yang di definisikan 
            $nip = h_session('NIP');
            $where2 = " a.nip = '".$nip."' AND a.role_id='5' AND a.is_active='t' ";
            $id_si = @$this->m_global->getDataAll('m_user_ic_si AS a', null, $where2, 'a.id_si')[0]->id_si;
            if($id_si != ''){
                $whereE .= " AND a.id IN(".$id_si.")";
            }else{
                $whereE .= " AND a.id is null "; 
            }
        }
        //cek role PIC IC
        if(h_session('ROLE_ID') == '9'){
            //ambil SI yang di definisikan 
            $nip = h_session('NIP');
            $where2 = " a.nip = '".$nip."' AND a.role_id='9' AND a.is_active='t' ";
            $id_si = @$this->m_global->getDataAll('m_user_ic_si AS a', null, $where2, 'a.id_si')[0]->id_si;
            if($id_si != ''){
                $whereE .= " AND a.id IN(".$id_si.")";
            }else{
                $whereE .= " AND a.id is null";
            }
        }
        // ========================================

        //filtering global
        // echo '<pre>';print_r($_REQUEST);exit;
        $id_bsc = @$_REQUEST['global_id_bsc'];
        $id_si = @$_REQUEST['global_id_si'];
        if($id_bsc != ''){ $where['a.id_bsc'] = $id_bsc; }
        if($id_si != ''){ $where['a.id'] = $id_si; }

        //cek year dan month
        $year = @$_REQUEST['global_year'];
        if($year != ''){
            $date_start = $year.'-01-01';
            $date_end = $year.'-12-01';
            $whereE .= " AND ((a.\"start_date\" >= '".$date_start."' AND a.\"start_date\" <= '".$date_end."') ";
            $whereE .= " OR (a.\"end_date\" >= '".$date_start."' AND a.\"end_date\" <= '".$date_end."') ";
            $whereE .= " OR (a.\"start_date\" <= '".$date_start."' AND a.\"end_date\" >= '".$date_end."')) ";
        }


        //filtering
        $search = [];
        if(isset($_REQUEST['filter'])){
            foreach ($_REQUEST['filter'] as $row) {

                //default parameter
                if(@$row['name'] != ''){

                    $val= $row['value']; $tipe = $row['tipe']; $search[] = $row['name']; 
                    $name = @$row['name'];
                    $name = $this->m_monev_si->select($row['name']); $name = str_replace(' AS '.$row['name'], ' ', $name );

                    //cek tipe
                    if($val != ''){
                        if($tipe == '1'){
                            $where[' LOWER('.$name.') LIKE '] = '%'.strtolower($val).'%';
                        }
                        if($tipe == '2'){
                            $where[$name.' ='] = $val;
                        }
                        if($tipe == '3'){
                            $where[$name.' >= '] = str_replace(",","",$val);
                        }
                        if($tipe == '4'){
                            $where[$name.' <= '] = str_replace(",","",$val);
                        }
                        if($tipe == '5'){
                            if($whereE==NULL){$and='';}else{$and=' AND ';}
                            $whereE .= $and." FIND_IN_SET( '".$val."', ".$name." )";
                        }
                        if($tipe == '6'){
                            if(is_array($val)){
                                $val = array_values($val); $val = implode(",", $val);
                            }
                            if($val != ''){
                                if(strpos( $val, ',' ) !== false ) { 
                                    $val = explode(',', $val); $val = implode("','", $val);
                                }
                                if($whereE==NULL){$and='';}else{$and=' AND ';}
                                $whereE .= $and.$name." IN('".$val."')";
                            }
                        }
                    }
                }
            }
        }
        // echo '<pre>';print_r($where);
        // echo '<pre>';print_r($whereE);exit;

        //order
        if($_REQUEST['columns']){ foreach ($_REQUEST['columns'] as $row) { $columns[] = $row['data']; } }      
        if(isset($_REQUEST['order'])){
            $kolom = @$columns[(@$_REQUEST['order'][0]['column'])];
            $kolom = str_replace('_id', '_name', $kolom);
            $tipe  = $_REQUEST['order'][0]['dir'];
            $order = [ $kolom , $tipe];
        }else{
            $order = ['code','ASC'];
        }
        // echo '<pre>';print_r($order);exit;

        
        //table dan join
        $table = "m_si AS a";
        $join  = NULL;

        //select 
        $select = [ 'id','code','name','name_bsc','name_pic_si','name_status_si',
                            'is_active','created_date','created_by','updated_date','updated_by',
                            'start_date','end_date','background_goal','objective_key_result','cek_objective_key_result',
                            'total_status_request'
                        ];
        $select = array_unique(array_merge($select, $search));
        $select = $this->m_monev_si->select($select);

        //pagging
        $iTotalRecords  = $this->m_global->countDataAll($table, $join, $where, $whereE);
        $iDisplayLength = intval($_REQUEST['length']);
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
        $iDisplayStart  = intval($_REQUEST['start']);
        $sEcho          = intval($_REQUEST['draw']);
        $end            = $iDisplayStart + $iDisplayLength;
        $i              = 1 + $iDisplayStart;
        $end            = $end > $iTotalRecords ? $iTotalRecords : $end;

        //Ambil datanya
        $result = $this->m_global->getDataAll($table, $join, $where, $select, $whereE, $order, $iDisplayStart, $iDisplayLength);
        // echo $this->db->last_query();exit;

        $param=[];
        foreach ($result as $rows) {
            $id = @$rows->id;
            //action 
            $action = '';
            
            //cek txt progress
            if(in_array( h_session('ROLE_ID'), h_role_admin())){ $txt_btn_progress = 'Add Progress'; }
            if(h_session('ROLE_ID') == '5'){ $txt_btn_progress = 'View Progress'; }
            if(h_session('ROLE_ID') == '9'){ $txt_btn_progress = 'Add Progress'; }

            //cek role
            $btn_add_progress = '<button title_popup="('.$rows->code.') '.$rows->name.'" title="'.$txt_btn_progress.'" id="'.$id.'" class="btn btn-sm  btn-primary btn_add_progress"><i class="fa fa-plus"></i> '.$txt_btn_progress.'</button>';
            if(h_session('ROLE_ID') == '5' && $rows->total_status_request > 0){
                $btn_add_progress = '<button title_popup="('.$rows->code.') '.$rows->name.'" title="'.$txt_btn_progress.'" id="'.$id.'" class="btn btn-sm  btn-primary btn_add_progress"><i class="fa fa-plus"></i> '.$txt_btn_progress.'&nbsp;
                                        <span class="badge badge-warning" style="background:#dfba49;color:white;">'.$rows->total_status_request.'</span>
                                    </button>';
            }
            $action = $btn_add_progress;

            //isi table disini
            $isi['no']                  = $i;
            $isi['id']                  = $rows->id;
            $isi['is_active']           = ($rows->is_active == 't' ? 'Active' : 'Non Active');
            $isi['created_date']        = h_format_date($rows->created_date,'d F Y');
            $isi['created_by']          = $rows->created_by;
            $isi['updated_date']        = h_format_date($rows->updated_date,'d F Y');
            $isi['updated_by']          = $rows->updated_by;

            $isi['code']                = str_replace(',','.',$rows->code);
            $isi['name']                = h_read_more($rows->name,30);
            $isi['id_bsc']              = $rows->name_bsc;
            $isi['start_date']          = $rows->start_date;
            $isi['end_date']            = $rows->end_date;
            $isi['name_pic_si']         = h_read_more($rows->name_pic_si,20);
            $isi['status_si']           = $rows->name_status_si;
            $isi['background_goal']     = h_read_more($rows->background_goal,20);
            $isi['objective_key_result'] = h_read_more($rows->objective_key_result,20);
            $isi['cek_objective_key_result'] = ($rows->cek_objective_key_result == '1' ? 'checked': 'No Check');

            $isi['action']              = '<div style="width:125px;'.($iTotalRecords=='1'?'height:50px;':'').'">'.$action.'</div>';

            $param[] = $isi;
            $i++;
        }
        $records["data"]            = $param;
        $records["draw"]            = $sEcho;
        $records["recordsTotal"]    = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        echo json_encode($records);
    }

    public function load_add() {

        $this->load->model('app/m_monev_si','m_monev_si');
        $data['url'] = $this->url;

        //param
        $tipe   = $this->input->post('tipe');
        $id_si  = $this->input->post('id_si');
        $year   = (@$this->input->post('year') == '' ? date('Y') : @$this->input->post('year'));
        $month  = (@$this->input->post('month') == '' ? (int)date('m') : @$this->input->post('month'));
        $data['tipe']   = $tipe;
        $data['id_si']  = $id_si;
        $data['year']   = $year;
        $data['month']  = $month;
        
        //get data
        $data['data'] = @$this->m_global->getDataAll('m_si', null, ['id'=>$id_si], '*')[0];

        //start_year
        $start_year = substr(@$data['data']->start_date,0,4);
        $end_year   = substr(@$data['data']->end_date,0,4);
        $data['start_year'] = $start_year;
        $data['end_year']   = $end_year;
        if($start_year == $end_year){$year = $start_year;}
        $data['year'] = $year;

        //cek notif request user PIC SI
        $arr_tot_notif = $arr_notif_month = [];
        $role = $this->session->userdata('USER')['ROLE_ID'];
        if($role == '5'){ 
            $arr_year = $this->m_global->getDataAll('m_monev_si_month', null, ['id_si'=>$id_si, 'status'=>'2', 'is_active'=>'t'], 'year, month');
            foreach($arr_year as $row){
                $arr_notif_tot[$row->year] = @$arr_tot_notif[$row->year] + 1;
                $arr_notif_month[$row->year][] = h_month_name($row->month);
            }
        }
        $data['arr_notif_tot'] = @$arr_notif_tot;
        $data['arr_notif_month'] = $arr_notif_month;

        //kalo dipake malah jadi lama
        // $data['html_load_table_action_plan'] = $this->load_table_action_plan(TRUE, $id_si, $year, $month);

        $this->template->display_ajax($this->url.'/v_monev_si_add', $data);
    }

    public function load_table_action_plan($html=FALSE, $id_si='', $year='', $month='', $tipe='') {

        // echo '<pre>';print_r($this->input->post());exit;
        $this->load->model('app/m_monev_si','m_monev_si');

        $data['url'] = $this->url;

        //cek tipe html
        if($html == FALSE){
            //parameter
            $tipe   = @$this->input->post('tipe');
            $id_si  = $this->input->post('id');
            $year   = @$this->input->post('year');
            $month  = @$this->input->post('month');
        }

        $data['tipe']   = $tipe;
        $data['id_si']  = $id_si;
        $data['year']   = $year;
        $data['month']  = $month;

        //get data si month overall_complete & complete on year
        $select = "color, status_complete, status, keterangan, month, overall_complete, complete_on_year, request_approval_keterangan";
        $where = ['id_si'=>$id_si, 'year'=>$year, 'is_active'=>'1'];
        $arr = @$this->m_global->getDataAll('m_monev_si_month', null, $where, $select);
        $arr_overall_complete = $arr_complete_on_year = $arr_color = $arr_status_complete = $arr_status_approval = $arr_keterangan = [];
        foreach($arr as $row){
            //target overall_complete
            $arr_overall_complete[$row->month]  = $row->overall_complete;
            $arr_complete_on_year[$row->month]  = $row->complete_on_year;
            $arr_color[$row->month]             = $row->color;
            $arr_status_complete[$row->month]   = $row->status_complete;
            $arr_status_approval[$row->month]   = ($row->status == '' ? 1 : $row->status);
            $arr_keterangan[$row->month]        = $row->keterangan;
            $arr_keterangan_approval[$row->month] = $row->request_approval_keterangan;
        }
        $data['arr_overall_complete']   = $arr_overall_complete;
        $data['arr_complete_on_year']   = $arr_complete_on_year;
        $data['arr_color']              = $arr_color;
        $data['arr_keterangan']         = $arr_keterangan;
        $data['arr_status_complete']    = $arr_status_complete;
        $data['arr_status_approval']    = $arr_status_approval;
        $data['arr_keterangan_approval']= $arr_keterangan_approval;

        //get data action plan
        // $whereE = " a.id_si = $id_si AND is_active = 't' AND status_action_plan = '3' ";
        $whereE = " a.id_si = $id_si AND is_active = 't'";
        // $date_start = $year.'-01-01';
        // $date_end = $year.'-12-01';
        // $whereE .= " AND ((a.\"start_date\" >= '".$date_start."' AND a.\"start_date\" <= '".$date_end."') ";
        // $whereE .= " OR (a.\"end_date\" >= '".$date_start."' AND a.\"end_date\" <= '".$date_end."') ";
        // $whereE .= " OR (a.\"start_date\" <= '".$date_start."' AND a.\"end_date\" >= '".$date_end."')) ";
        $select = [ 'id','name','code','is_active','created_date','created_by','updated_date','updated_by','status_action_plan',
            'name_pic_action_plan','deliverable','weighting_factor','budget_currency','id_si',
            'name_si','code_si','name_bsc','name_status_action_plan','name_pic_action_plan','name_pic_action_plan2',
            'start_date','end_date','parent'
        ];
        $select = $this->m_monev_si->select_action_plan($select, $year);
        $order = "a.code ASC";
        $arr_action_plan = @$this->m_global->getDataAll('m_action_plan AS a', null, $whereE, $select, null, $order);
        // echo $this->db->last_query();exit;
        // echo '<pre>';print_r($arr_action_plan);exit;
        $data['arr_action_plan'] = $arr_action_plan;


        //get data action_plan year
        $select = "z.id_action_plan, a.id_si, z.year, z.status_complete";
        $join  = [  ['table' => 'm_action_plan_year z', 'on' => 'a.id = z.id_action_plan  AND z.status = 3', 'join' => 'LEFT'],           
                    ['table' => 'm_si x', 'on' => 'x.id = a.id_si', 'join' => 'LEFT'],
                ];
        // $where = ['a.id_si'=>$id_si, 'z.year'=>$year, 'a.is_active'=>'1', 'a.status_action_plan'=>'3'];
        $where = ['a.id_si'=>$id_si, 'z.year'=>$year, 'a.is_active'=>'1'];
        // $where = ['a.id_si'=>$id_si, 'a.is_active'=>'1'];
        $arr = @$this->m_global->getDataAll('m_action_plan AS a', $join, $where, $select);
        // echo $this->db->last_query();exit;
        // echo '<pre>';print_r($arr);exit;
        $arr_year = [];
        foreach($arr as $row){
            //target year
            $arr_year[$row->id_action_plan][$row->year]['status_complete']  = $row->status_complete;
        }
        $data['arr_year'] = $arr_year;
        // echo '<pre>';print_r($arr_year);exit;

        
        //get data action_plan month
        $select = "z.id_action_plan, a.id_si, z.month, z.pencapaian";
        $join  = [  ['table' => 'm_action_plan_month z', 'on' => 'a.id = z.id_action_plan  AND z.status = 3', 'join' => 'LEFT'],           
                    ['table' => 'm_si x', 'on' => 'x.id = a.id_si', 'join' => 'LEFT'],
                ];
        // $where = ['a.id_si'=>$id_si, 'z.year'=>$year, 'a.is_active'=>'1', 'a.status_action_plan'=>'3'];
        $where = ['a.id_si'=>$id_si, 'z.year'=>$year, 'a.is_active'=>'1'];
        // $where = ['a.id_si'=>$id_si, 'a.is_active'=>'1'];
        $arr = @$this->m_global->getDataAll('m_action_plan AS a', $join, $where, $select);
        // echo $this->db->last_query();exit;
        // echo '<pre>';print_r($arr);exit;
        $arr_month = [];
        foreach($arr as $row){
            //target month
            $arr_month[$row->id_action_plan][$row->month]['pencapaian'] = $row->pencapaian;
        }
        $data['arr_month'] = $arr_month;
        // echo '<pre>';print_r($arr_month);exit;
        
        //status
        $data['status_complete'] = $this->m_global->getDataAll('m_status', null, ['is_active'=>'t', 'type'=>'Monev Action Plan'], '*', null, '"order" ASC');

        $isi = $this->template->display_ajax($this->url.'/v_monev_si_table_action_plan', $data, NULL, NULL, $html);
        if($html){return $isi;}else{echo $isi;}
    }


    public function load_issue() {
        csrf_init();
        $data['url'] = $this->url;
        $tipe = @$this->input->post('tipe');
        $id_si = @$this->input->post('id_si');
        $id_action_plan = @$this->input->post('id_action_plan');
        $year = @$this->input->post('year');

        $data['tipe'] = $tipe;
        $data['id_si'] = $id_si;
        $data['id_action_plan'] = $id_action_plan;
        $data['year'] = $year;
        
        $data['html_load_table_issue'] = $this->load_table_issue(TRUE, $id_si, $id_action_plan, $year, $tipe);

        $this->template->display_ajax($this->url.'/v_monev_si_issue', $data);
    }

    public function load_add_issue() {
        csrf_init();
        $data['url'] = $this->url;
        $id_action_plan = @$this->input->post('id_action_plan');
        $id_si = @$this->input->post('id_si');
        $year = @$this->input->post('year');
        $data['id_si'] = $id_si;
        $data['id_action_plan'] = $id_action_plan;
        $data['year'] = $year;
        $this->template->display_ajax($this->url.'/v_monev_si_add_issue', $data);
    }

    public function load_edit_issue() {
        csrf_init();
        $data['url'] = $this->url;
        $id = @$this->input->post('id');
        $id_action_plan = @$this->input->post('id_action_plan');
        $id_si = @$this->input->post('id_si');
        $year = @$this->input->post('year');
        $data['id']             = $id;
        $data['id_si']          = $id_si;
        $data['id_action_plan'] = $id_action_plan;
        $data['year']           = $year;

        //get data
        $data['data'] = @$this->m_global->getDataAll('m_issue', null, ['id'=>$id], '*')[0];

        $this->template->display_ajax($this->url.'/v_monev_si_edit_issue', $data);
    }
    
    public function delete_data() {
        //cek csrf token
        // $ex_csrf_token = @$this->input->post('token');
        // $res = [];
        // if (csrf_get_token() != $ex_csrf_token){
        //     $res['status']  = 0;
        //     $res['message'] = $this->csrf_message;
        //     echo json_encode($res);
        // }else{
            $id = $this->input->post('id');
            $val = $this->input->post('val');
            $data['is_active'] = $val;
            $res = $this->m_global->update('m_monev_si', $data, ['id' => $id]);
            $res1 = @$this->m_global->update('m_monev_si_target_month', $data, ['id_monev_si' => $id]);
            $res2 = @$this->m_global->update('m_monev_si_target_year', $data, ['id_monev_si' => $id]);
            if($val == 't'){
                $res['message'] = 'Active Success!';
            }else{
                $res['message'] = 'Delete Success!';
            }
            
            echo json_encode($res);
        // }
    }

    public function delete_issue() {
        //cek csrf token
        // $ex_csrf_token = @$this->input->post('token');
        // $res = [];
        // if (csrf_get_token() != $ex_csrf_token){
        //     $res['status']  = 0;
        //     $res['message'] = $this->csrf_message;
        //     echo json_encode($res);
        // }else{
            $id = $this->input->post('id');
            $data['is_active'] = 'f';
            $res = $this->m_global->update('m_issue', $data, ['id' => $id]);
            $res['message'] = 'Delete Success!';
            echo json_encode($res);
        // }
    }

    public function update_calculation() {
        //cek csrf token
        // $ex_csrf_token = @$this->input->post('token');
        // $res = [];
        // if (csrf_get_token() != $ex_csrf_token){
        //     $res['status']  = 0;
        //     $res['message'] = $this->csrf_message;
        //     echo json_encode($res);
        // }else{

            // echo '<pre>';print_r( $this->input->post());exit;

            //tampung data
            $id_si              = $this->input->post('id_si');
            $year               = $this->input->post('year');
            $arr_id             = explode('^',$this->input->post('id_action_plan'));
            $arr_month          = explode('^',$this->input->post('month'));
            $arr_pencapaian     = explode('^',$this->input->post('pencapaian'));

            //cek data perubahan
            if(@$arr_id[0] != ''){
                //update pencapaian
                foreach($arr_id as $key => $val){
                    $id = $arr_id[$key];
                    $month = $arr_month[$key];
                    $data = $where = [];
                    $data['pencapaian'] = (@$arr_pencapaian[$key] == '' ? 0 : $arr_pencapaian[$key]);
                    $where = ['id_action_plan' => $id, 'year' => $year, 'month' => $month, 'is_active'=> 't'];
                    $res = $this->m_global->update('m_action_plan_month', $data, $where);
                }
            }

            //update status complete year
            $arr_id_status      = explode('^',$this->input->post('id_action_plan_status'));
            $arr_status_year    = explode('^',$this->input->post('status_year'));
            
            //cek data perubahan
            if(@$arr_id_status[0] != ''){
                //update status complete year
                foreach($arr_id_status as $key => $val){

                    //update status year sub 
                    $id = $arr_id_status[$key];
                    $status_complete = $arr_status_year[$key];
                    $data = $where = [];
                    $data  = ['status_complete'=> $status_complete] ;
                    $where = ['id_action_plan' => $id, 'year' => $year, 'is_active'=> 't'];
                    $res = $this->m_global->update('m_action_plan_year', $data, $where);

                    //update complete jika status progress
                    if($status_complete == '11'){
                        $where = "id_action_plan = '$id' AND year > '$year' AND (pencapaian = '' OR pencapaian = '0' OR pencapaian is NULL) AND is_active = 't'";
                        $select = "id_action_plan, year";
                        $arr = @$this->m_global->getDataAll('m_action_plan_month', null, $where, $select);
                        foreach($arr as $row){
                            $data = $where = [];
                            $where = ['id_action_plan' => $row->id_action_plan, 'year' => $row->year, 'is_active'=> 't'];
                            $data = ['pencapaian' => '100'];
                            $res = $this->m_global->update('m_action_plan_month', $data, $where);
                            $data = [];
                            $data = ['status_complete' => '11'];
                            $res = $this->m_global->update('m_action_plan_year', $data, $where);
                        }
                    }else{
                        $where = "id_action_plan = '$id' AND year > '$year' AND pencapaian = '100' AND is_active = 't'";
                        $select = "id_action_plan, year";
                        $arr = @$this->m_global->getDataAll('m_action_plan_month', null, $where, $select);
                         foreach($arr as $row){
                            $data = $where = [];
                            $where = ['id_action_plan' => $row->id_action_plan, 'year' => $row->year, 'is_active'=> 't'];
                            $data = ['pencapaian' => '0'];
                            $res = $this->m_global->update('m_action_plan_month', $data, $where);
                            $data = [];
                            $data = ['status_complete' => $status_complete];
                            $res = $this->m_global->update('m_action_plan_year', $data, $where);
                         }
                    }
                }

                //update status year parent
                $arr_temp = join(',',$arr_id_status);
                $where = "id IN($arr_temp)";
                $arr_parent = @$this->m_global->getDataAll('m_action_plan', null, $where, 'parent', null,null,null,null,'parent');
                foreach($arr_parent as $row){ 
                    $where = [];
                    $id_parent = $row->parent;
                    $where['a.parent'] = $id_parent;
                    $select = " (SELECT b.status_complete FROM m_action_plan_year b WHERE b.id_action_plan = a.id AND b.year = $year) AS status_complete";
                   
                    $arr_status_year = @$this->m_global->getDataAll('m_action_plan a', null, $where, $select);
                    $arr_status_sub = [];
                    foreach($arr_status_year as $row){
                        $arr_status_sub[] = ($row->status_complete == '' ? '13' : $row->status_complete);
                    }

                    //cek status
                    if(in_array('13',$arr_status_sub)){ $cek1 = 'ada'; }else{ $cek1 = ''; }
                    if(in_array('12',$arr_status_sub)){ $cek2 = 'ada'; }else{ $cek2 = ''; }
                    if(in_array('11',$arr_status_sub)){ $cek3 = 'ada'; }else{ $cek3 = ''; }
                    if($cek1 == 'ada' && $cek2 == '' && $cek3 == ''){
                        $status_parent = '13';
                    }elseif($cek1 == '' && $cek2 == '' && $cek3 == 'ada'){
                        $status_parent = '11';
                    }else{
                        $status_parent = '12';
                    }

                    //update status complete parent
                    $data = $where = [];
                    $data['status_complete'] = $status_parent;
                    $where = ['id_action_plan' => $id_parent, 'year' => $year, 'is_active'=> 't'];
                    $res = $this->m_global->update('m_action_plan_year', $data, $where);

                }
            }

            //update overall complete
            $this->update_overall_complete($id_si,$year);

            //result
            $res['status'] = 1;
            $res['message'] = 'Update Success!';
            echo json_encode($res);

        // }
    }


    public function update_overall_complete($id_si='', $year='') {

        //get weigthin factor dan statusnya bukan "not started yet"
        $select = " a.id, a.id_si, a.parent, a.weighting_factor,
                    (SELECT b.status_complete FROM m_action_plan_year b 
                        WHERE b.id_action_plan = a.id  AND b.year = '$year'
                    ) AS status_complete";
        $where = "id_si = '$id_si' AND is_active = 't'";
        $arr = @$this->m_global->getDataAll('m_action_plan a', null, $where, $select);
        $arr_weighting_factor_all = $arr_weighting_factor_sub = $arr_id_action_plan_parent =  $arr_id_action_plan_sub = $arr_id_sub = [];
        foreach($arr as $row){
            //cek status selain "not started yet"
            if($row->parent == '0'){
                //tampung data perhitungan
                $arr_id_action_plan_parent[$row->id] = $row->id;
                $arr_weighting_factor_all[$row->id] = $row->weighting_factor;
            }else{
                //tampung data perhitungan
                $arr_id_sub[$row->id] = $row->id;
                $arr_id_action_plan_sub[$row->parent][$row->id] = $row->id;
                $arr_status_complete_sub[$row->parent][$row->id] = $row->status_complete;
                $arr_weighting_factor_sub[$row->id] = $row->weighting_factor;
                $arr_weighting_factor_sub_all[$row->parent] = @$arr_weighting_factor_sub_all[$row->parent] + $row->weighting_factor;
                //cek status selain "not started yet" untuk weighting figter complete on year
                if($row->status_complete != '13' && $row->status_complete != ''){
                    $arr_weighting_factor_sub_all_year[$row->parent] = @$arr_weighting_factor_sub_all_year[$row->parent] + $row->weighting_factor;
                }
            }
        }
        
        //cek arr_id_action_plan_parent
        if(count(@$arr_id_sub) == 0){ return ''; }
        
        //tampung data bulan
        $arr_id = join(',',$arr_id_sub);
        $where  = "id_action_plan IN($arr_id) AND year = '$year' AND is_active = 't'";
        $select = "id_action_plan, month, pencapaian";
        $arr = @$this->m_global->getDataAll('m_action_plan_month', null, $where, $select);
        $arr_pencapaian_sub = [];
        foreach($arr as $row){
            $arr_pencapaian_sub[$row->id_action_plan][$row->month] = ($row->pencapaian==''?0:$row->pencapaian);
        }

        //perhitungan
        $arr_overall_complete_all = $arr_overall_complete_month = $arr_pencapaian_month = [];
        foreach($arr_id_action_plan_parent as $parent){
            $wf_all = $arr_weighting_factor_all[$parent];
            //tampung data pencapaian dan overall_complete
            foreach($arr_id_action_plan_sub[$parent] as $id){
                //cek status selain "not started yet" untuk weighting figter complete on year
                if($arr_status_complete_sub != '13'){
                    $wf_sub = $arr_weighting_factor_sub[$id];
                    for ($m=1; $m <= 12; $m++) { 
                        $arr_pencapaian_month[$parent][$m] = (@$arr_pencapaian_sub[$id][$m] * $wf_sub) + @$arr_pencapaian_month[$parent][$m];
                        $arr_overall_complete_month[$parent][$m] = ($wf_sub * (@$arr_pencapaian_sub[$id][$m]/100)) + @$arr_overall_complete_month[$parent][$m];
                    }
                }
            }

            //overall_complete_all
            for ($m=1; $m <= 12; $m++) { 
                $arr_overall_complete_all[$m] = @$arr_overall_complete_all[$m] + $arr_overall_complete_month[$parent][$m];
            }

            //update pencapaian parent
            for ($m=1; $m <= 12; $m++) { 
                $pencapaian_parent_month = ($arr_pencapaian_month[$parent][$m]/$wf_all);
                $data = ['pencapaian'=> $pencapaian_parent_month];
                $where = ['id_action_plan' => $parent, 'year' => $year, 'month' => $m, 'is_active' => 't'];
                $result = $this->m_global->update('m_action_plan_month', $data, $where);
            }
        }

        //update overall complete
        for ($m=1; $m <= 12; $m++) { 
            $overall_complete_all = $arr_overall_complete_all[$m];
            $overall_complete_all = round($overall_complete_all,2);
            $data = ['overall_complete'=> $overall_complete_all];
            $where = ['id_si' => $id_si, 'year' => $year, 'month' => $m, 'is_active' => 't'];
            $result = $this->m_global->update('m_monev_si_month', $data, $where);
        }

        //perhitungan complete on year
        $arr_complete_on_year = $arr_complete_on_year_all = [];
        foreach($arr_id_action_plan_parent as $parent){
            for ($m=1; $m <= 12; $m++) { 
                $wf_all                     = $arr_weighting_factor_all[$parent];
                $wf_sub_all                 = (@$arr_weighting_factor_sub_all_year[$parent] == '' ? 0 : @$arr_weighting_factor_sub_all_year[$parent]);
                $overall_complete_month     = @$arr_overall_complete_month[$parent][$m];
                if($wf_sub_all == 0){
                    $arr_complete_on_year[$parent][$m] = 0;
                }else{
                    $arr_complete_on_year[$parent][$m] = (($wf_all/$wf_sub_all) * @$overall_complete_month);
                }
            }
            for ($m=1; $m <= 12; $m++) {
                $arr_complete_on_year_all[$m] = @$arr_complete_on_year_all[$m] + $arr_complete_on_year[$parent][$m];
            }
        }
       
        //cek perhitungan complete of year
        // echo '<table border="1" class="table">';
        // foreach($arr_complete_on_year as $val){
        //     echo "<tr>";
        //     foreach($val as $val2){
        //         echo '<td style="'.($val2=='0'?'':'background:lightgrey;').'">'.$val2.'</td>';
        //     }
        //     echo "</tr>";
        // }
        // echo "</table>";
        // echo '<pre>';print_r($arr_complete_on_year);exit;


        //get data Weihting Factor Status year parent
        $where = "a.id_si = '$id_si' AND b.year = '$year' AND b.status_complete IN(11,12) AND a.parent='0' AND a.is_active = 't' ";
        $select = "a.weighting_factor";
        $join  = [  ['table' => 'm_action_plan_year b', 'on' => 'b.id_action_plan = a.id', 'join' => 'INNER']  ];
        $arr = @$this->m_global->getDataAll('m_action_plan a', $join, $where, $select);
        // echo $this->db->last_query();exit;
        $tot_wf_status = 0;
        foreach($arr as $row){
            $tot_wf_status = @$tot_wf_status + $row->weighting_factor;
        }
        // echo '<pre>';print_r($tot_wf_status);exit;
        
        //update complete on year
        for ($m=1; $m <= 12; $m++) { 
            $complete_on_year_all = $arr_complete_on_year_all[$m] * (100/$tot_wf_status);
            $complete_on_year_all = round($complete_on_year_all,2);
            $data = ['complete_on_year'=> $complete_on_year_all];
            //monev si month
            $where = ['id_si' => $id_si, 'year' => $year, 'month' => $m, 'is_active' => 't'];
            $result = $this->m_global->update('m_monev_si_month', $data, $where);
        }
    }


    public function update_si_month(){
        //cek csrf token
        // $ex_csrf_token = @$this->input->post('token');
        // $res = [];
        // if (csrf_get_token() != $ex_csrf_token){
        //     $res['status']  = 0;
        //     $res['message'] = $this->csrf_message;
        //     echo json_encode($res);
        // }else{
            $id_si              = $this->input->post('id_si');
            $year               = $this->input->post('year');
            $month              = $this->input->post('month');
            $status_complete    = $this->input->post('status_complete');
            $color              = $this->input->post('color');
            $keterangan         = $this->input->post('keterangan');
            $data['status_complete']    = $status_complete;
            $data['color']              = $color;
            $data['keterangan']         = $keterangan;
            $where = ['id_si' => $id_si, 'year' => $year, 'month' => $month, 'is_active' => 't'];
            $res = $this->m_global->update('m_monev_si_month', $data, $where);

            $res['message'] = 'Update Success!';
            echo json_encode($res);
        // }
    }


    public function get_data_si_month(){
        //cek csrf token
        // $ex_csrf_token = @$this->input->post('token');
        // $res = [];
        // if (csrf_get_token() != $ex_csrf_token){
        //     $res['status']  = 0;
        //     $res['message'] = $this->csrf_message;
        //     echo json_encode($res);
        // }else{
            $id_si = $this->input->post('id_si');
            $year  = $this->input->post('year');
            $month = $this->input->post('month');
            //get data si month
            $where = "a.id_si = '$id_si' AND a.year = '$year' AND a.month = '$month'  AND a.is_active = 't' ";
            $select = "a.color, a.keterangan, a.status_complete";
            $arr = @$this->m_global->getDataAll('m_monev_si_month a', null, $where, $select)[0];
            // echo $this->db->last_query();exit;
            // echo '<pre>';print_r($arr_issue);exit;
            $data['color']           = $arr->color;
            $data['keterangan']      = $arr->keterangan;
            $data['status_complete'] = $arr->status_complete;
            $data['month_text']      = h_month_name($month);
            $data['month']           = $month;
            echo json_encode($data);
        // }
    }

    public function change_status_month() {
        //cek csrf token
        // $ex_csrf_token = @$this->input->post('token');
        // $res = [];
        // if (csrf_get_token() != $ex_csrf_token){
        //     $res['status']  = 0;
        //     $res['message'] = $this->csrf_message;
        //     echo json_encode($res);
        // }else{

            //get data status month
            $id_si      = $this->input->post('id_si');
            $year       = $this->input->post('year');
            $month      = $this->input->post('month');
            $tipe       = $this->input->post('tipe');
            $val        = $this->input->post('val');
            $keterangan = $this->input->post('keterangan');
            $data2['id_si']                 = $id_si;
            $data2['year']                  = $year;
            $data2['month']                 = $month;
            $data2['status_approval_month'] = $val;
            $data2['tipe']                  = $tipe;
            $data2['keterangan']            = $keterangan;
            $this->template->display_ajax($this->url.'/v_monev_si_approval', $data2);

        // }
    }


    public function change_status() {
        //cek csrf token
        // $ex_csrf_token = @$this->input->post('token');
        // $res = [];
        // if (csrf_get_token() != $ex_csrf_token){
        //     $res['status']  = 0;
        //     $res['message'] = $this->csrf_message;
        //     echo json_encode($res);
        // }else{
            
            $id_si      = $this->input->post('id_si');
            $year       = $this->input->post('year');
            $month      = $this->input->post('month');
            $title      = $this->input->post('title');
            $val        = $this->input->post('val');
            $tipe       = @$this->input->post('tipe');
            $keterangan = @$this->input->post('keterangan');
            $status_new = $val;
            $request_by = h_session('USER_ID');
            $request_date = date("Y-m-d H:i:s");
            $data = [];
            $data['status']                 = $val;
            $data['updated_by']             = $request_by;
            $data['updated_date']           = $request_date;
            if($status_new == '2'){
                $data['request_approval_by']    = h_session('USER_ID');
                $data['request_approval_date']  = date("Y-m-d H:i:s");
            }
            if($status_new == '3' || $status_new == '4'){
                $data['request_approval_keterangan'] = $keterangan;
            }
            $where = ['id_si' => $id_si, 'month' => $month, 'year' => $year];
            $result = @$this->m_global->update('m_monev_si_month', $data, $where);
           
            //get data status month
            $data2 = [];
            $data2['id_si']           = $id_si;
            $data2['year']            = $year;
            $data2['month']           = $month;
            $data2['tipe']            = $tipe;
            $data2['keterangan']      = $keterangan;
            $data2['status_approval_month'] = $val;
            $this->template->display_ajax($this->url.'/v_monev_si_approval', $data2);

            //============================== Kirim Notif Email ====================================

            //get data si
            $select = "name, code";
            $arr = @$this->m_global->getDataAll('m_si AS a', null, ['id' => $id_si], $select)[0];
            $si_name = $arr->name;
            $si_code = $arr->code;

            //cek status
            if($status_new == '1' || $status_new == '2'){

                //selec pic si dari master user ic si
                $arr_user = [];
                $where      = " a.is_active='t' AND a.role_id='5' ";
                $where      .= " AND ('".$id_si."' = ANY (string_to_array(a.id_si,', ')))";
                $arr        = @$this->m_global->getDataAll('m_user_ic_si AS a', null, $where, 'a.nip');
                foreach($arr as $row){ $arr_user[$row->nip] = $row->nip;}

                //kirim ke user pic si
                $arr_nip    = join("','",$arr_user);
                $where      = "a.nip IN('".$arr_nip."') AND a.is_active='t'";
                $arr        = @$this->m_global->getDataAll('sys_user AS a', null, $where, 'a.nip, a.fullname, a.email, a.title, a.role_id');
                // echo '<pre>';print_r($arr);exit;
                
                foreach($arr as $row){
                    $data = [];
                    $data['nip']        = $row->nip;
                    $data['fullname']   = $row->fullname;
                    $data['email']      = $row->email;
                    $data['title']      = $row->title;
                    $data['status']     = $status_new;
                    $data['si_name']    = $si_name;
                    $data['si_code']    = $si_code;
                    $data['request_by']   = h_session('NAME');
                    $data['request_date'] = date("Y-m-d H:i:s");
                    
                    $token = h_insert_token('request_approval_monev_si',$row->nip, '30');
                    $pecah = explode(', ',$row->role_id);
                    if(in_array('5',$pecah)) { $role_id = '5';  }else{ $role_id = '9';}
                    $link = site_url().'login/redirect_page/request_approval_monev_si/'.$token.'/'.$row->nip.'/'.$role_id.'/'.$id_si.'/'.$year.'/'.$month;
                    $data['link'] = $link;

                    $to         = h_email_to($row->email);
                    $from       = 'noreply@indonesiapower.co.id';
                    $title      = "Request Approval Monitoring & Evaluation SI";
                    $subject    = "Request Approval Monitoring & Evaluation SI";
                    $data['subject'] = $subject;
                    
                    //untuk cek html
                    // $this->load->view($this->url.'/v_monev_si_email_request_approval', $data);

                    //kirim email html
                    $html = $this->load->view($this->url.'/v_monev_si_email_request_approval', $data, TRUE);
                    h_kirim_email_ip($from, $to, null, null, $title, $subject, $html);


                    //============================ notif inbox ====================================
                    if($status_new == '2'){
                        //insert to inbox
                        $data = [];
                        $data['element']     = "SI - ".$si_code;
                        $data['type_inbox']  = "SI";
                        $data['description'] = "Request Approval Monitoring & Evaluation Strategic Initiative(SI), <br>
                                                Untuk SI: (".$si_code.") ".h_text_br($si_name,40);
                        $data['param_id']       = $id_si;
                        $data['review_status']  = 18;
                        $data['request_by']     = h_session('USER_ID');
                        $data['request_date']   = date('Y-m-d H:i:s');
                        $data['nip']            = $row->nip;
                        $data['role_id']        = $role_id;
                        $data['redirect_page']  = $link;
                        $result = $this->m_global->insert('m_inbox', $data);
                    }
                    if($status_new == '1'){
                        //delete inbox
                        $data = [];
                        $data['element']        = "SI - ".$si_code;
                        $data['type_inbox']     = "SI";
                        $data['param_id']       = $id_si;
                        $data['review_status']  = 18;
                        $data['request_by']     = h_session('USER_ID');
                        $data['nip']            = $row->nip;
                        $data['role_id']        = $role_id;
                        $result = $this->m_global->delete('m_inbox', $data);
                    }
                    //=============================================================================
                }
            }

            if($status_new == '3' || $status_new == '4'){

                //kirim email
                $arr_user = [];
                $where  = "a.is_active='t' AND id_si='".$id_si."' AND month='".$month."' AND year='".$year."'";
                $request_from = @$this->m_global->getDataAll('m_monev_si_month AS a', null, $where, 'a.request_approval_by')[0]->request_approval_by;
                if($request_from == ''){ $request_from = '1';}

                //kirim ke user pic ic
                $where      = "a.is_active='t' AND id='".$request_from."'";
                $arr        = @$this->m_global->getDataAll('sys_user AS a', null, $where, 'a.nip, a.fullname, a.email, a.title, a.role_id');
                foreach($arr as $row){

                    $data = [];
                    $data['nip']        = $row->nip;
                    $data['fullname']   = $row->fullname;
                    $data['email']      = $row->email;
                    $data['title']      = $row->title;
                    $data['status']     = $status_new;
                    $data['si_name']    = $si_name;
                    $data['si_code']    = $si_code;
                    $data['request_by']   = h_session('NAME');
                    $data['request_date'] = date("Y-m-d H:i:s");

                    $token = h_insert_token('request_approval_monev_si',$row->nip, '30');
                    $pecah = explode(', ',$row->role_id);
                    if(in_array('5',$pecah)) { $role_id = '5';  }else{ $role_id = '9';}
                    $link = site_url().'login/redirect_page/request_approval_monev_si/'.$token.'/'.$row->nip.'/'.$role_id.'/'.$id_si.'/'.$year.'/'.$month;
                    $data['link'] = $link;

                    $to         = h_email_to($row->email);
                    $from       = 'noreply@indonesiapower.co.id';
                    $title      = "Request Approval SI";
                    $subject    = 'Request Approval SI';
                    $data['subject'] = $subject;
                    
                    //untuk cek html
                    // $this->load->view($this->url.'/v_monev_si_email_request_approval', $data);

                    //kirim email html
                    $html = $this->load->view($this->url.'/v_monev_si_email_request_approval', $data, TRUE);
                    h_kirim_email_ip($from, $to, null, null, $title, $subject, $html);

                    //============================ notif inbox ====================================
                    //keterangan status
                    if($status_new == '3'){ 
                        $isi = 'Telah <span class="label btn_keterangan_approval" keterangan="'.$keterangan.'" style="cursor:pointer;color:#fff;background-color:#5cb85c;">DISETUJUI</span>';
                    }else{
                        $isi = 'Telah <span class="label label-danger btn_keterangan_approval" keterangan="'.$keterangan.'" style="cursor:pointer;">DITOLAK</span>';
                    }
                    //insert to inbox
                    $data = [];
                    $data['element']     = "SI - ".$si_code;
                    $data['type_inbox']  = "SI";
                    $data['description'] = "Request Approval Monitoring & Evaluation Strategic Initiative(SI), <br>
                                            ".$isi.",<br>
                                            Untuk SI: (".$si_code.") ".h_text_br($si_name,40);
                    $data['param_id']       = $id_si;
                    $data['review_status']  = 18;
                    $data['request_by']     = h_session('USER_ID');
                    $data['request_date']   = date('Y-m-d H:i:s');
                    $data['nip']            = $row->nip;
                    $data['role_id']        = $role_id;
                    $data['redirect_page']  = $link;
                    $result = $this->m_global->insert('m_inbox', $data);
                    //=============================================================================

                }

            }


        // }

    }



    public function load_table_issue($html=FALSE, $id_si='', $id_action_plan='', $year='', $tipe='')
    {   
        //cek id_si
        if($html == FALSE){
            $tipe  = $this->input->post('tipe');
            $id_si = $this->input->post('id_si');
            $year  = $this->input->post('year');
        }
        $data['tipe']   = $tipe;
        $data['id_si']  = $id_si;
        $data['year']   = $year;

        if($id_action_plan == ''){
            $where = "a.id_si = '$id_si' AND a.year = '$year' AND a.is_active = 't' ";
        }else{
            $where = "a.id_action_plan = '$id_action_plan' AND a.year = '$year' AND a.is_active = 't' ";
        }
        $select = "a.*, 
                    (SELECT concat('(',b.code,') ', b.name) FROM m_action_plan b WHERE b.id = a.id_action_plan) AS name_action_plan,
                    (SELECT b.\"SINGKATAN_POSISI\" FROM \"ERP_STO_REAL\" b WHERE b.\"POSITION_ID\"::TEXT = a.\"executor\"::TEXT) AS \"name_executor\"
                    ";
        $arr_issue = @$this->m_global->getDataAll('m_issue a', null, $where, $select, null, 'a.id DESC');
        // echo $this->db->last_query();exit;
        // echo '<pre>';print_r($arr_issue);exit;
        $data['arr_issue'] = $arr_issue;

        $isi = $this->template->display_ajax($this->url.'/v_monev_si_table_issue', $data, NULL, NULL, $html);
        if($html){return $isi;}else{echo $isi;}
        
    }

    public function save_add_issue() {

        //cek csrf token
        // $ex_csrf_token = @$this->input->post('ex_csrf_token');
        // $res = [];
        // if (csrf_get_token() != $ex_csrf_token){
        //     $res['status']  = 2;
        //     $res['message'] = $this->csrf_message;
        //     echo json_encode($res);
        // }else{

            // Set Rule Login Form
            $this->form_validation->set_rules('issue', 'Issue', 'trim|xss_clean|required');
            $this->form_validation->set_rules('category', 'Category', 'trim|xss_clean|required');
            $this->form_validation->set_rules('followup', 'Follow Up', 'trim|xss_clean|required');
            $this->form_validation->set_rules('executor', 'Executor', 'trim|xss_clean|required');
            $this->form_validation->set_rules('email', 'Email', 'trim|xss_clean|required');
            $this->form_validation->set_rules('no_hp', 'No HP', 'required');
            $this->form_validation->set_rules('due_date', 'Due Date', 'required');
            $this->form_validation->set_rules('status_issue', 'Status Issue', 'trim|xss_clean|required');
            
            if ($this->form_validation->run($this)) {

                //insert data
                $data['id_si']          = @$this->input->post('id_si');
                $data['id_action_plan'] = @$this->input->post('id_action_plan');
                $data['year']           = @$this->input->post('year');
                $data['issue']          = @$this->input->post('issue');
                $data['category']       = @$this->input->post('category');
                $data['followup']       = @$this->input->post('followup');
                $data['executor']       = @$this->input->post('executor');
                $data['email']          = @$this->input->post('email');
                $data['no_hp']          = @$this->input->post('no_hp');
                $data['due_date']       = @$this->input->post('due_date').' 00:00:00';
                $data['status_issue']   = @$this->input->post('status_issue');

                $data['date_issue']     = date("Y-m-d H:i:s");
                $data['created_date']   = date("Y-m-d H:i:s");
                $data['created_by']     = h_session('USERNAME');

                $result = $this->m_global->insert('m_issue', $data);

                $res['status']  = ($result['status'] ? '1':'0');
                $res['message'] = 'Successfully Save Data!';
                echo json_encode($res);

            }else{
                //error form validasi 
                $res['status'] = 3;
                $str = ['<p>', '</p>']; $str_replace= ['<li>', '</li>'];
                $res['message'] = str_replace($str, $str_replace, validation_errors());
                echo json_encode($res);
            }
        // }
    }


    public function save_edit_issue() {

        //cek csrf token
        // $ex_csrf_token = @$this->input->post('ex_csrf_token');
        // $res = [];
        // if (csrf_get_token() != $ex_csrf_token){
        //     $res['status']  = 2;
        //     $res['message'] = $this->csrf_message;
        //     echo json_encode($res);
        // }else{

            // Set Rule Login Form
            $this->form_validation->set_rules('issue', 'Issue', 'trim|xss_clean|required');
            $this->form_validation->set_rules('category', 'Category', 'trim|xss_clean|required');
            $this->form_validation->set_rules('followup', 'Follow Up', 'trim|xss_clean|required');
            $this->form_validation->set_rules('executor', 'Executor', 'trim|xss_clean|required');
            $this->form_validation->set_rules('email', 'Email', 'trim|xss_clean|required');
            $this->form_validation->set_rules('no_hp', 'No HP', 'required');
            $this->form_validation->set_rules('due_date', 'Due Date', 'required');
            $this->form_validation->set_rules('status_issue', 'Status Issue', 'trim|xss_clean|required');
            
            if ($this->form_validation->run($this)) {

                //update data
                $id = @$this->input->post('id');
                $data['id_si']          = @$this->input->post('id_si');
                $data['id_action_plan'] = @$this->input->post('id_action_plan');
                $data['year']           = @$this->input->post('year');
                $data['issue']          = @$this->input->post('issue');
                $data['category']       = @$this->input->post('category');
                $data['followup']       = @$this->input->post('followup');
                $data['executor']       = @$this->input->post('executor');
                $data['email']          = @$this->input->post('email');
                $data['no_hp']          = @$this->input->post('no_hp');
                $data['due_date']       = @$this->input->post('due_date').' 00:00:00';
                $data['status_issue']   = @$this->input->post('status_issue');

                $data['updated_date']   = date("Y-m-d H:i:s");
                $data['updated_by']     = h_session('USERNAME');

                $result = $this->m_global->update('m_issue', $data, ['id'=>$id]);

                $res['status']  = ($result['status'] ? '1':'0');
                $res['message'] = 'Successfully Save Data!';
                echo json_encode($res);

            }else{
                //error form validasi 
                $res['status'] = 3;
                $str = ['<p>', '</p>']; $str_replace= ['<li>', '</li>'];
                $res['message'] = str_replace($str, $str_replace, validation_errors());
                echo json_encode($res);
            }
        // }
    }



    public function select_executor()
    {

        if(isset($_REQUEST['q'])){
            $q          = $_REQUEST['q'];
            $where      = " \"POSISI\" != '' ";
            if($q != ''){ $where .= ' AND LOWER("POSISI") LIKE \'%'.strtolower($q).'%\' OR LOWER("SINGKATAN_POSISI") LIKE \'%'.strtolower($q).'%\'' ; }
            $select     = '"POSISI" AS "JABATAN", "SINGKATAN_POSISI", "POSITION_ID", (SELECT b."CHILD_EMAIL" FROM "DIRJAB_STO" as b WHERE a."POSITION_ID" = b."POSITION_ID" LIMIT 1) AS "EMAIL"';
            $parent     = @$this->m_global->getDataAll('ERP_STO_REAL AS a', NULL, $where, $select, NULL, '"SINGKATAN_POSISI" ASC',0,20);
            $data       = [];
            for ($i=0; $i < count($parent); $i++) {
                $name = '<b>'.$parent[$i]->SINGKATAN_POSISI.'</b> ["'.$parent[$i]->JABATAN.'"]';
                $data[$i] = ['id' => $parent[$i]->POSITION_ID, 'name' => $name, 'email' => $parent[$i]->EMAIL, 'no_hp' => '', 'tipe' => 'add'];
            }
            if(count($data) < 1){
                $data[$i] = ['id' => $q, 'name' => $q, 'email' => '', 'no_hp' => '', 'tipe' => 'add'];
            }
            echo json_encode(['item' => $data]);
        }else{
            //cek id nya integer
            if((int)$_REQUEST['id'] > 0){
                $id = str_replace(",", "','",  $_REQUEST['id']);
                $where      = "\"POSITION_ID\" IN ('".$id."')";
                $select     = '"POSISI" AS "JABATAN", "SINGKATAN_POSISI", "POSITION_ID", (SELECT b."CHILD_EMAIL" FROM "DIRJAB_STO" as b WHERE a."POSITION_ID" = b."POSITION_ID" LIMIT 1) AS "EMAIL"';
                $parent     = @$this->m_global->getDataAll('ERP_STO_REAL AS a', NULL, $where, $select);
                $data       = [];
                for ($i=0; $i < count($parent); $i++) {
                    $name = '<b>'.$parent[$i]->SINGKATAN_POSISI.'</b> ["'.$parent[$i]->JABATAN.'"]';
                    $data[$i] = ['id' => $parent[$i]->POSITION_ID, 'name' => $name, 'email' => $parent[$i]->EMAIL, 'no_hp' => '', 'tipe' => 'edit'];
                }
                echo json_encode($data);
            }else{
                $data[0] = ['id' => $_REQUEST['id'], 'name' => $_REQUEST['id'], 'email' => '', 'no_hp' => '', 'tipe' => 'edit'];
                echo json_encode($data);exit;
            }
        }
    }


    public function select_action_plan()
    {
        if(isset($_REQUEST['q'])){
            $q = strtolower($_REQUEST['q']);
            $id_si = @$_REQUEST['id_si'];
            $where = " is_active='t' AND id_si='$id_si' AND parent != 0";
            if(!empty($_REQUEST['q'])){
                $where .= " AND LOWER(name) LIKE '%".$q."%'";
            }
            $arr = @$this->m_global->getDataAll('m_action_plan AS a', NULL,$where,"a.id, a.code, a.name",NULL,'a.code');
            // echo $this->db->last_query();exit;    
            $data = [];
            for ($i=0; $i < count($arr); $i++) {
                $code = str_replace('.0','.',$arr[$i]->code);
                $name = "(".$code.") ".$arr[$i]->name;
                $data[$i] = ['id' => $arr[$i]->id,  'name' => $name ];
            }
            echo json_encode(['item' => $data]);
        }else{
            $id = $_REQUEST['id'];
            $where = ['id' => $id];
            $arr = @$this->m_global->getDataAll('m_action_plan AS a', NULL,$where,"a.id, a.code, a.name");
            $code = str_replace('.0','.',@$arr[0]->code);
            $name = "(".$code.") ".@$arr[0]->name;
            $data[] = [ 'id' => @$arr[0]->id,  'name' => $name ];
            echo json_encode($data);
        }
    }

    public function download_excel(){

        //load model view
        $param = @$this->input->post('input_form');
        $arr = json_decode($param);
        // echo '<pre>';print_r($param);exit;
        
        //load model
        $this->load->model('app/m_ic','m_ic');

        //search default
        $where  = [];
        $whereE = " a.is_active = 't' ";

        //filter global
        $id_bsc     = @$arr->global_id_bsc;
        $year       = @$this->input->post('year');
        // $periode    = @$this->input->post('periode');
       
        //Get Data Action Plan All Data sesuai periode 
        $order = "a.id_si ASC, a.code ASC";
        $select = 'a.id, a.id_si, a.id_bsc, a.code AS code_action_plan, a.name AS name_action_plan, 
                    a.pic_action_plan, a.deliverable, a.weighting_factor, 
                    a.status_action_plan, a.start_date, a.end_date,
                    x.name AS name_si, x.code AS code_si';
        $join  = [  ['table' => 'm_si x', 'on' => 'x.id = a.id_si', 'join' => 'LEFT']  ];
        $where['a.is_active'] = 't';
        $where['a.id_bsc'] = $id_bsc;

        //cek role pic ic si
        if(h_session('ROLE_ID') == '9' || h_session('ROLE_ID') == '5'){
            $nip = h_session('NIP');
            $where2 = " nip = '".$nip."'";
            $arr_id_si = @$this->m_global->getDataAll('m_user_ic_si AS a', null, $where2, 'id_si')[0]->id_si;
            if($arr_id_si != ''){
                $whereE .= " AND a.id_si IN(".$arr_id_si.")";
            }
        }

        //periode star_year and end_year
        // $arr = @$this->m_global->getDataAll('m_periode', null, ['id'=> $periode], 'start_year,end_year')[0];
        // $date_start = $arr->start_year.'-01-01';
        // $date_end = $arr->end_year.'-12-31';
        // $whereE .= " AND ((a.\"start_date\" >= '".$date_start."' AND a.\"start_date\" <= '".$date_end."') ";
        // $whereE .= " OR (a.\"end_date\" >= '".$date_start."' AND a.\"end_date\" <= '".$date_end."') ";
        // $whereE .= " OR (a.\"start_date\" <= '".$date_start."' AND a.\"end_date\" >= '".$date_end."')) ";
        $arr = @$this->m_global->getDataAll('m_action_plan AS a', $join, $where, $select, $whereE, $order);
        // echo $this->db->last_query();exit;
        // echo '<pre>';print_r($arr);exit;


        //tampung data
        $arr_action_plan = $arr_si = $arr_code_si = $arr_name_si = $arr_pencapaian = [];
        $arr_id_action_plan = [];
        foreach($arr as $row){
            //Action Plan month
            $arr_si[$row->id_si] = $row->id_si;
            $arr_code_si[$row->id_si] = $row->code_si;
            $arr_name_si[$row->id_si] = $row->name_si;
            $arr_id_action_plan[$row->id_si][$row->id] = $row->id;
            $arr_action_plan[$row->id]['code_action_plan']  = str_replace('.0','.',$row->code_action_plan);
            $arr_action_plan[$row->id]['name_action_plan']  = $row->name_action_plan;
            $arr_action_plan[$row->id]['pic_action_plan']   = $this->convert_id_position_to_name($row->pic_action_plan);
            $arr_action_plan[$row->id]['deliverable']       = $row->deliverable;
            $arr_action_plan[$row->id]['weighting_factor']  = $row->weighting_factor;
            $arr_action_plan[$row->id]['status_action_plan']= $row->status_action_plan;
            $arr_action_plan[$row->id]['start_date']        = $row->start_date;
            $arr_action_plan[$row->id]['end_date']          = $row->end_date;
        }
        // echo '<pre>';print_r($arr_action_plan);exit;


        //Get Data Action Plan sesuai tahun Year yang dipilih
        $select = 'a.id, a.id_si';
        $whereE = " a.is_active ='t' ";
        $where['a.id_bsc'] = $id_bsc;
        //cek role pic ic si
        if(h_session('ROLE_ID') == '9' || h_session('ROLE_ID') == '5'){
            $nip = h_session('NIP');
            $where2 = " nip = '".$nip."'";
            $arr_id_si = @$this->m_global->getDataAll('m_user_ic_si AS a', null, $where2, 'id_si')[0]->id_si;
            if($arr_id_si != ''){
                $whereE .= " AND a.id_si IN(".$arr_id_si.")";
            }
        }
        //cek year
        $date_start = $year.'-01-01';
        $date_end   = $year.'-12-31';
        $whereE .= " AND ((a.\"start_date\" >= '".$date_start."' AND a.\"start_date\" <= '".$date_end."') ";
        $whereE .= " OR (a.\"end_date\" >= '".$date_start."' AND a.\"end_date\" <= '".$date_end."') ";
        $whereE .= " OR (a.\"start_date\" <= '".$date_start."' AND a.\"end_date\" >= '".$date_end."')) ";
        $arr = @$this->m_global->getDataAll('m_action_plan AS a', null, $where, $select, $whereE);
        //tampung data
        $arr_id_action_plan_year = [];
        foreach($arr as $row){
             //Action Plan month
             $arr_id_action_plan_year[$row->id_si][$row->id] = $row->id;
        }
        // echo '<pre>';print_r($arr_id_action_plan_year);exit;
        

        //Action Plan Month
        $select = 'a.id, z.month, z.pencapaian';
        $join  = [  ['table' => 'm_action_plan a', 'on' => 'a.id = z.id_action_plan', 'join' => 'LEFT'] ];
        $where['a.is_active'] = 't';
        if($id_bsc != ''){ $where['a.id_bsc'] = $id_bsc; }
        if($year != ''){ $where['z.year'] = $year; }
        $arr = @$this->m_global->getDataAll('m_action_plan_month AS z', $join, $where, $select);
        //tampung data
        $arr_pencapaian = [];
        foreach($arr as $row){
            //Action Plan month
            $arr_pencapaian[$row->id][$row->month] = $row->pencapaian;
        }
        // echo '<pre>';print_r($arr_pencapaian);exit;


        //Action Plan Year
        $select = 'a.id, b.name AS name_status_complete';
        $join  = [  ['table' => 'm_action_plan a', 'on' => 'a.id = z.id_action_plan', 'join' => 'LEFT'],
                    ['table' => 'm_status b', 'on' => 'b.id = z.status_complete', 'join' => 'INNER']
                ];
        $where['a.is_active'] = 't';
        if($id_bsc != ''){ $where['a.id_bsc'] = $id_bsc; }
        if($year != ''){ $where['z.year'] = $year; }
        $arr = @$this->m_global->getDataAll('m_action_plan_year AS z', $join, $where, $select);
         //tampung data
         $arr_status_complete_year = [];
         foreach($arr as $row){
             //Action Plan month
             $arr_status_complete_year[$row->id] = $row->name_status_complete;
         }
        //  echo '<pre>';print_r($arr_status_complete_year);exit;

        
        //param excel
        $template_name  = 'template_monev_si.xls';
        $title          = 'Data Monev '.$year;
        $filename       = 'Data Monev SI - '.$year.'.xlsx';

        //load library
        $this->load->library("excel");
        include APPPATH.'/third_party/PHPExcel/Writer/Excel2007.php';
        $objReader = PHPExcel_IOFactory::createReader('Excel5');
        if(h_localhost()){
            $this->excel = $objReader->load(APPPATH."third_party\\template_excel\\".$template_name);
        }else{
            $this->excel = $objReader->load(APPPATH."third_party//template_excel//".$template_name);
        }

        //create sheet si jika SI lebih dari 1
        if(count($arr_si) > 0){
            $i = -1;
            foreach($arr_si as $id_si) { 
                $i++;
                $code_si = 'SI '.$arr_code_si[$id_si];
                if($i == 0){
                    $this->excel->setActiveSheetIndex(0)->setTitle("$code_si");
                }else{
                    $tempSheet = $this->excel->getSheet(0)->copy();
                    $tempSheet->setTitle("$code_si");
                    $this->excel->addSheet($tempSheet);
                    unset($tempSheet);
                }
            }
        }else{
            echo 'SI Tidak ditemukan';exit;
        }

        //data status si, color si, performace analysis
        $status_si = $performance_analysis = $color_si = [];
        foreach($arr_si as $id_si){
            $select = "a.color, a.keterangan, (SELECT b.name FROM m_status b WHERE b.type='Monev Action Plan' AND b.id = a.status_complete ) AS status_si";
            $where = ['a.id_si'=>$id_si, 'a.year'=>$year];
            $arr = @$this->m_global->getDataAll('m_monev_si_year a', NULL, $where, $select)[0];
            $status_si[$id_si]              = (@$arr->status_si == '' ? 'Not Yet Started' : @$arr->status_si);
            $performance_analysis[$id_si]   = @$arr->keterangan;
            $color_si[$id_si]               = (@$arr->color == '' ? 'RED' : @$arr->color);
        }
        // echo '<pre>';print_r($status_si);exit;
        
        //sheet si
        $i = -1;
        foreach($arr_si as $id_si)
        {
            $i++;

            //year, status si, color, performace analysis
            $this->excel->setActiveSheetIndex($i)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString('C')-1, 1, $year);
            // $this->excel->setActiveSheetIndex($i)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString('C')-1, 2, @$status_si[$id_si]);
            // $this->excel->setActiveSheetIndex($i)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString('E')-1, 1, @$color_si[$id_si]);
            // $this->excel->setActiveSheetIndex($i)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString('E')-1, 2, @$performance_analysis[$id_si]);
            
            //masukan data
            $baris = 4;
            $a = -1;
            foreach($arr_id_action_plan[$id_si] as $id_action_plan){
                $a++;

                //data excel
                $code_action_plan       = $arr_action_plan[$id_action_plan]['code_action_plan'];   
                $name_action_plan       = $arr_action_plan[$id_action_plan]['name_action_plan'];   
                $pic_action_plan        = $arr_action_plan[$id_action_plan]['pic_action_plan'];    
                $deliverable            = $arr_action_plan[$id_action_plan]['deliverable'];        
                $status_action_plan     = $arr_action_plan[$id_action_plan]['status_action_plan']; 
                $start_date             = date_format(new DateTime($arr_action_plan[$id_action_plan]['start_date']), "d/m/Y");         
                $end_date               = date_format(new DateTime($arr_action_plan[$id_action_plan]['end_date']), "d/m/Y");          
                $weighting_factor       = $arr_action_plan[$id_action_plan]['weighting_factor'];

                $this->excel->setActiveSheetIndex($i)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString('A')-1,$baris, $id_action_plan);
                $this->excel->setActiveSheetIndex($i)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString('B')-1,$baris, $code_action_plan);
                $this->excel->setActiveSheetIndex($i)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString('C')-1,$baris, $name_action_plan);
                $this->excel->setActiveSheetIndex($i)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString('D')-1,$baris, $pic_action_plan);
                $this->excel->setActiveSheetIndex($i)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString('E')-1,$baris, $deliverable);
                $this->excel->setActiveSheetIndex($i)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString('F')-1,$baris, $start_date);
                $this->excel->setActiveSheetIndex($i)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString('G')-1,$baris, $end_date);
                $this->excel->setActiveSheetIndex($i)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString('H')-1,$baris, $weighting_factor);
                
                //warna untuk year select
                if(@$arr_id_action_plan[$id_si][$id_action_plan] == @$arr_id_action_plan_year[$id_si][$id_action_plan]){
                    $this->excel->getActiveSheet()->getStyle('I'.$baris.':T'.$baris)->applyFromArray( 
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'FFFF00')
                            ),
                            'borders' => array(
                                'allborders' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                                    'color' => array('rgb' => '000000')
                                )
                            )
                        )
                    );
                }else{
                    $this->excel->getActiveSheet()->getStyle('I'.$baris.':T'.$baris)->applyFromArray( 
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'D3D3D3')
                            ),
                            'borders' => array(
                                'allborders' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                                    'color' => array('rgb' => '000000')
                                )
                            )
                        )
                    );
                }
                


                //pencapaian
                $z="H"; 
                for($m = 1; $m <= 12; $m++){
                    $pencapaian = @$arr_pencapaian[$id_action_plan][$m];
                    $this->excel->setActiveSheetIndex($i)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString($z)-1, $baris, $pencapaian); 
                    $z++;
                }

                //status complete year
                $status_complete_year = @$arr_status_complete_year[$id_action_plan];
                $this->excel->setActiveSheetIndex($i)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString($z), $baris, $status_complete_year); 


                //bug format code 1.10 
                if(strpos($code_action_plan,'.') !== false ) { 
                    $pecah = explode('.',@$code_action_plan);
                    if(@$pecah[1] >= 10){
                        $this->excel->getActiveSheet()->getStyle('A'.$baris)->getNumberFormat()->setFormatCode('0.00');
                    }
                }else{
                    $this->excel->getActiveSheet()->getStyle('A'.$baris.':U'.$baris)->applyFromArray( 
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'A9D08E')
                            )
                        )
                    );
                }
                $baris++;
            }
        }

        $this->excel->getProperties()->setCreator("SIMO")
                                    ->setLastModifiedBy("SIMO")
                                    ->setTitle($title)
                                    ->setSubject($title)
                                    ->setDescription($title)
                                    ->setKeywords($title)
                                    ->setCategory($title);

        $data_excel =  $this->excel;
        $objWriter  = PHPExcel_IOFactory::createWriter($data_excel,'Excel2007');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: must-revalidate');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public'); 

        ob_end_clean();
        $objWriter->save("php://output");

    }


    public function upload_excel(){

        //setting file
        // echo '<pre>';print_r($format_template);exit;
        // echo '<pre>';print_r($_FILES);exit;
        $fileName = 'template_action_plan'.time().$_FILES['file']['name'];
        $folder = './public/files/temp/';
        $config['upload_path']   = $folder; //buat folder dengan nama assets di root folder
        $config['file_name']     = $fileName;
        $config['allowed_types'] = '*';
        $config['allowed_types'] = 'xls|xlsx';
        $config['max_size']      = 100000; //100mb

        $this->load->library('upload');
        $this->upload->initialize($config);

        //cek upload file
        if ( ! $this->upload->do_upload('file') ){
            //error upload
            $res['status']   = '0';
            $res['message']  = 'Invalid file uploaded!';
            // $a = $this->upload->display_errors();
            // echo $a;exit;
        }else{

            //upload data
            $file_data = $this->upload->data();
            $folder_file =  $folder.$file_data['file_name'];

            //cek jika ada error, error jangan diproses
            try {
                //load library excel
                $this->load->library("PHPExcel");
                $inputFileType   = PHPExcel_IOFactory::identify($folder_file);
                $objReader       = PHPExcel_IOFactory::createReader($inputFileType);
                // $objReader->setReadDataOnly(true);
                $objPHPExcel     = $objReader->load($folder_file);
                $worksheet_names = $objReader->listWorksheetNames($folder_file);

                foreach ($worksheet_names as $key => $val_sheet) {
                    
                    //cek nama si
                    if(strpos($val_sheet,'SI') === false ) { continue; }
                    $si_code = str_replace(',','.',str_replace('SI','',str_replace(' ','',$val_sheet)));
                    $id_bsc = $this->input->post('id_bsc');
                    $where = ['code'=>$si_code, 'id_bsc'=>$id_bsc];
                    $id_si = @$this->m_global->getDataAll('m_si', NULL,$where,"id")[0]->id;
                    if($id_si == ''){ continue; }

                    //membaca data sheet 
                    $sheet              = $objPHPExcel->getSheet($key); 
                    $highestRow         = $sheet->getHighestRow(); 
                    $highestColumn      = $sheet->getHighestColumn();
                    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

                    //year, status, color, keterangan
                    $arr_row1 =  $sheet->rangeToArray('A1:E1', NULL, TRUE, FALSE, FALSE)[0];
                    $arr_row2 =  $sheet->rangeToArray('A2:E2', NULL, TRUE, FALSE, FALSE)[0];
                    $year               = (@$this->input->post('year') == '' ? $arr_row1[2] : @$this->input->post('year'));
                    $color              = $arr_row1[4];
                    $status_complete    = strtolower($arr_row2[2]);
                    $keterangan         = $arr_row2[4];

                    //cek tahun yang diupload apakah sama
                    if(@$this->input->post('year') != $arr_row1[2]){
                        $res['status']   = '0';
                        $res['message']  = 'Tahun Upload Tidak Sesuai dengan Excel !';
                        unlink($folder_file);
                        echo json_encode($res);exit;
                    }

                    //array status complete
                    $arr = @$this->m_global->getDataAll('m_status', NULL,['type'=>'Monev Action Plan'],'id,name');
                    foreach($arr as $row){ $arr_status[strtolower($row->name)] = $row->id; }
                    

                    //update status, color, keterangan
                    $Where = $data = [];
                    $where = ['id_bsc' => $id_bsc, 'id_si' => $id_si, 'is_active' => 't'];
                    $data['color']              = $color;
                    $data['status_complete']    = @$arr_status[$status_complete];
                    $data['keterangan']         = $keterangan;
                    $result = @$this->m_global->update('m_monev_si_year', $data, $where);
                    
                    
                    //kolom field
                    $arr_data_col =  $sheet->rangeToArray('A3:' . $highestColumn .'3', NULL, TRUE, FALSE, FALSE)[0];
                    // echo '<pre>';print_r($arr_data_col);exit;
                    

                    //====================================== Update Data ==================================================
                    $arr_data = [];
                    for($row = 4; $row <= $highestRow; $row++){
                        //array excel
                        $arr_row =  $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE, FALSE)[0];
                        // echo '<pre>';print_r($arr_row);exit;

                        //cek jika datanya kosong
                        if(@$arr_row[0] == ''){ continue;}

                        //update data action plan year
                        $id_action_plan = $arr_row[0];
                        $status_complete = strtolower($arr_row[20]);
                        $where = ['id_action_plan' => $id_action_plan, 'year' => $year, 'is_active' => 't'];
                        $data  = ['status_complete' => @$arr_status[$status_complete] ];
                        $result = @$this->m_global->update('m_action_plan_year', $data, $where);

                        //update data action plan month
                        for($m=1;$m<=12;$m++){
                            $id_action_plan = $arr_row[0];
                            $pencapaian     = $arr_row[7+$m];
                            $where = ['id_action_plan' => $id_action_plan, 'year' => $year, 'month' => $m, 'is_active' => 't'];
                            $data = ['pencapaian' => round(str_replace(',','.',$pencapaian), 2, PHP_ROUND_HALF_DOWN)];
                            $result = @$this->m_global->update('m_action_plan_month', $data, $where);
                        }

                    }
                    //============================================================================================

                }

                if (@$result['status'] == TRUE || @$result == TRUE){
                    $res['status']   = '1';
                    $res['message']  = 'Successfully imported file!</br>';
                }else{
                    $res['status']   = '0';
                    $res['message']  = 'Failed !';
                }
                unlink($folder_file);
            }
            //catch exception
            catch(Exception $e) {
                // echo 'Message: ' .$e->getMessage();
                unlink($folder_file);
            }
            echo json_encode($res);

        }
    }


    public function convert_id_position_to_name($id_position = '')
    {
        //cek pic kosong
        if($id_position != ''){
            //cek pic lebih dari 1
            $cek = explode(', ',$id_position);
            if(count($cek) > 1){
                $arr_id_position = join(', ',$cek);
            }else{
                $arr_id_position = $cek[0];
            }
            
            //cek di table ERP_STO_REAL
            $temp = [];
            $where         = "\"POSITION_ID\" IN(".$arr_id_position.")";
            $select        = '"SINGKATAN_POSISI"';
            $arr_singkatan_posisi   = @$this->m_global->getDataAll('ERP_STO_REAL', NULL, $where, $select);
            foreach($arr_singkatan_posisi as $row){
                $temp[] = $row->SINGKATAN_POSISI;
            }

            //cek di table m_pic
            $where         = "position_id_new IN(".$arr_id_position.")";
            $select        = 'singkatan_posisi';
            $arr_singkatan_posisi   = @$this->m_global->getDataAll('m_pic', NULL, $where, $select);
            foreach($arr_singkatan_posisi as $row){
                $temp[] = $row->singkatan_posisi;
            }
            $singkatan_posisi = join(', ',$temp);

        }else{
            $singkatan_posisi = '';
        }
        return $singkatan_posisi;
    }


    public function select_si()
    {
        if(isset($_REQUEST['q'])){
            $q = strtolower($_REQUEST['q']);
            $id_bsc = @$_REQUEST['id_bsc'];
            $where = "a.is_active = 't' AND a.status_si = '3'";
            if(!empty($_REQUEST['q'])){
                $where .= " AND ( LOWER(a.name) LIKE '%".$q."%' OR a.code::TEXT LIKE '%".$q."%' )";
            }
            if(!empty($id_bsc)){
                $where .= " AND a.id_bsc = '".$id_bsc."'";
            }
            
            //cek role pic si/ pic ic
            $role_id = h_session('ROLE_ID');
            if($role_id == '5' || $role_id == '9'){
                $nip = h_session('NIP');
                $where2 = "a.nip = '".$nip."' AND a.role_id='".$role_id."' AND a.is_active='t'";
                $arr_id_si = @$this->m_global->getDataAll('m_user_ic_si AS a', null, $where2, 'a.id_si')[0]->id_si;
                if($arr_id_si != ''){
                    $where .= " AND a.id IN(".$arr_id_si.")";
                }else{
                    $where .= " AND a.id is null";
                }
            }

            //select data
            $select = "a.id, a.code, a.name, a.pic_si, a.start_date, a.end_date";
            $arr = @$this->m_global->getDataAll('m_si AS a', NULL, $where, $select, null, "a.code ASC");
            $data = [];
            for ($i=0; $i < count($arr); $i++) {
                $name = '<b>['.$arr[$i]->code.']</b> '.$arr[$i]->name;
                $data[$i] = ['id' => $arr[$i]->id,  'name' => $name,  
                                'pic_si' => $arr[$i]->pic_si, 
                                'start_date' => substr($arr[$i]->start_date,0,7),
                                'end_date' => substr($arr[$i]->end_date,0,7)
                            ];
            }
            echo json_encode(['item' => $data]);
        }else{
            $id = $_REQUEST['id'];
            $where = ['id' => $id];
            $select = "a.id, a.code, a.name, a.pic_si, a.start_date, a.end_date";
            $arr = @$this->m_global->getDataAll('m_si AS a', NULL,$where, $select,NULL,"a.code ASC");
            $name = '<b>['.$arr[0]->code.']</b> '.$arr[0]->name;
            $data[] = [ 'id' => @$arr[0]->id,  'name' => @$name,  
                        'pic_si' => $arr[0]->pic_si, 
                        'start_date' => substr($arr[0]->start_date,0,7),
                        'end_date' => substr($arr[0]->end_date,0,7)
                    ];
            echo json_encode($data);
        }
    }


  
}
