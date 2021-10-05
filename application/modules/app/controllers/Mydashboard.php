<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mydashboard extends MY_Controller {
    
    private $prefix         = 'mydashboard';
    private $table_db       = '';
    private $title          = 'Mydashboard';
    private $folder         = 'app';
    private $url            = 'app/mydashboard';

    function __construct() {
        parent::__construct();
        $this->middleware('guest', 'forbidden');
    }

    public function index()
    {
        $data['url']        = $this->url;
        $data['breadcrumb'] = ["Home" => $this->url];

        $data['html_load_inbox'] = $this->load_inbox(true);

        $this->template->display($this->url.'/v_mydashboard', $data);

    }

    public function load_element($html=FALSE)
    {
        $data['url'] = $this->url;

        //param
        $param = @$this->input->post();
        // echo '<pre>';print_r($param);exit;

        //default filter year month
        if(h_triwulan_now() == '1'){
            $year_now = date('Y')-1;
            $month_now = 12;
        }else{
            $year_now = date('Y');
            $month_now = (h_triwulan_now()-1) * 3;
        }

        //filter global
        $year = (@$param['global_year'] == '' ? $year_now : @$param['global_year']);
        $month = (@$param['global_month'] == '' ? $month_now : @$param['global_month']);
        $id_bsc = (@$param['global_id_bsc'] == '' ? '1' : @$param['global_id_bsc']);
        $id_periode = (@$param['global_id_periode'] == '' ? '1' : @$param['global_id_periode']);
        $data['year']       = @$year;
        $data['month']      = @$month;
        $data['id_bsc']     = @$id_bsc;
        $data['id_periode'] = @$id_periode;

        //bsc
        $data['bsc'] = $this->m_global->getDataAll('m_bsc', null,  ['is_active'=>'t'], '*', null, "name ASC");
        //periode
        $arr = $this->m_global->getDataAll('m_periode', null, ['is_active'=>'t'], '*', null, "start_year ASC");
        $data['periode'] = $arr;

        //cek role
        $where = " is_active = 't' AND id='".h_session('USER_ID')."'";
        $role_id = @$this->m_global->getDataAll('sys_user', null,  $where, 'role_id')[0]->role_id;
        $tab_st = $tab_sr = $tab_so = $tab_kpi_so = $tab_si = $tab_action_plan = $tab_am = '';
        if($role_id != ''){
            $arr_role_id = explode(', ',$role_id);
            foreach($arr_role_id as $val){
                if($val == '1' || $val == '3' || $val == '7'){ 
                    $tab_st = $tab_sr = $tab_so = $tab_kpi_so = $tab_si = $tab_action_plan = $tab_am = 'active'; 
                    break; 
                }
                if($val == '4'){ $tab_so = 'active'; $tab_kpi_so = 'active'; }
                if($val == '5'){ $tab_si = 'active'; $tab_action_plan = 'active'; }
                if($val == '6'){ $tab_am = 'active'; }
                if($val == '8'){ $tab_so = 'active'; $tab_kpi_so = 'active'; }
                if($val == '9'){ $tab_si = 'active'; $tab_action_plan = 'active'; }
                if($val == '10'){ $tab_kpi_so = 'active'; }
            }
        }

        $data['tab_st'] = $tab_st;
        $data['tab_sr'] = $tab_sr;
        $data['tab_so'] = $tab_so;
        $data['tab_kpi_so'] = $tab_kpi_so;
        $data['tab_si'] = $tab_si;
        $data['tab_action_plan'] = $tab_action_plan;
        $data['tab_am'] = $tab_am;


        // //load table
        // $data['html_table_st_mydashboard']           = $this->load_table_st_mydashboard(true);
        // $data['html_table_sr_mydashboard']           = $this->load_table_sr_mydashboard(true);
        // $data['html_table_so_mydashboard']            = $this->load_table_so_mydashboard(true);
        // $data['html_table_kpi_so_mydashboard']       = $this->load_table_kpi_so_mydashboard(true);
        // $data['html_table_si_mydashboard']           = $this->load_table_si_mydashboard(true);
        // $data['html_table_action_plan_mydashboard']  = $this->load_table_action_plan_mydashboard(true);

        if($html){
            return $this->template->display_ajax("app/mydashboard/v_mydashboard_element", $data, null, null, TRUE);
        }else{
            $this->template->display_ajax("app/mydashboard/v_mydashboard_element", $data);
        }
    }


    public function load_inbox($html=FALSE)
    {
        //param
        $data['url'] = $this->url;
        $js['custom']       = ['table_inbox'];
        $param = @$this->input->post();
        // echo '<pre>';print_r($param);exit;

        if($html){
            return $this->template->display_ajax("app/mydashboard/v_mydashboard_inbox", $data, $js, null, TRUE);
        }else{
            $this->template->display_ajax("app/mydashboard/v_mydashboard_inbox", $data, $js);
        }
    }



    public function table_inbox()
    {    
        // load model view 
        $this->load->model('app/m_mydashboard','m_mydashboard');

        //search default
        $where  = [];
        $whereE = " 1=1 ";

        //tampilkan data inbox yang new
        $role_id = h_session('ROLE_ID');
        if(!in_array( $role_id, h_role_admin())){
            $whereE .= " AND nip = '".h_session('NIP')."' ";
        }

        //global filter
        $review_status = @$_REQUEST['global_review_status'];
        if($review_status != ''){ $where['a.review_status'] = $review_status; }

        //filtering
        $search = [];
        if(isset($_REQUEST['filter'])){
            foreach ($_REQUEST['filter'] as $row) {

                //default parameter
                if(@$row['name'] != ''){

                    $val= $row['value']; $tipe = $row['tipe']; $search[] = $row['name']; 
                    $name = @$row['name'];
                    $name = $this->m_mydashboard->select($row['name']); $name = str_replace(' AS '.$row['name'], ' ', $name );

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
            $order = ['id','DESC'];
        }
        // echo '<pre>';print_r($order);exit;

        
        //table dan join
        $table = "m_inbox AS a";
        $join  = NULL;

        //select 
        $select = "*";
        // $select = [ 'id','code','name','name_bsc','name_pic_si','name_status_si',
        //                     'is_active','created_date','created_by','updated_date','updated_by',
        //                     'start_date','end_date','background_goal','objective_key_result','cek_objective_key_result'
        //                 ];
        // $select = array_unique(array_merge($select, $search));
        $select = $this->m_mydashboard->select($select);

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
            $action = '';
            $btn_done = $btn_review = '';

            //btn
            // $btn_view = '<button title="View" id="'.$id.'" class="btn btn-sm btn-warning btn_view"><i class="fa fa-list"></i></button>';
            $btn_review = '<a target="_blank" href="'.$rows->redirect_page.'" id="'.$id.'" val="19" title="Review" class="btn btn-sm btn_review btn-primary"><i class="fa fa-arrow-right"></i></a>';
            if($rows->review_status != '20'){
                $btn_done = '<button title="Done" id="'.$id.'" val="20" class="btn btn-sm  btn-success btn_done"><i class="fa fa-check"></i></button>';
            }
            if($rows->review_status == '20'){
                $btn_review = '<a target="_blank" href="'.$rows->redirect_page.'" title="Review" class="btn btn-sm  btn-warning"><i class="fa fa-list"></i></a>';
            }
            $action = @$btn_review.@$btn_done;

            //isi table disini
            $isi['no']                  = $i;
            $isi['id']                  = $rows->id;
            $isi['element']             = $rows->element;
            // $isi['description']         = h_read_more_nobr($rows->description,50);
            $isi['description']         = $rows->description;
            $isi['name_request_by']     = h_read_more($rows->name_request_by,15);
            $isi['name_request_to']     = h_read_more($rows->name_request_to,15);
            $isi['request_date']        = h_format_date($rows->request_date,'d F Y');
            $isi['review_date']         = h_format_date($rows->review_date,'d F Y');
            $isi['name_review_status']  = $rows->name_review_status;

            $isi['action']              = '<div style="width:85px;'.($iTotalRecords=='1'?'height:10px;':'').'">'.$action.'</div>';

            $param[] = $isi;
            $i++;
        }
        $records["data"]            = $param;
        $records["draw"]            = $sEcho;
        $records["recordsTotal"]    = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        //total review status
        $where = null;
        if(!in_array( $role_id, h_role_admin())){
            $where = ['nip'=>h_session('NIP')];
        }
        $arr = $this->m_global->getDataAll('m_inbox', null, $where, 'count(*) AS total, review_status', null, null, null, null, 'review_status');
        $tot_new = $tot_review = $tot_done = 0;
        foreach($arr as $row){
            if($row->review_status == '18'){ $tot_new = $row->total; }
            if($row->review_status == '19'){ $tot_review = $row->total; }
            if($row->review_status == '20'){ $tot_done = $row->total; }
        }
        $records["tot_new"]     = $tot_new;
        $records["tot_review"]  = $tot_review;
        $records["tot_done"]    = $tot_done;
        $records["tot_all"]     = $tot_new+$tot_review+$tot_done;

        echo json_encode($records);
    }

    public function load_table_st_mydashboard($html=FALSE)
    {
        //param
        $data['url'] = $this->url;
        $js['custom']  = ['table_st_mydashboard'];
        $param = @$this->input->post();
        // echo '<pre>';print_r($param);exit;

        //filter global
        $data['year']       = @$param['global_year'];
        $data['month']      = @$param['global_month'];
        $data['id_bsc']     = @$param['global_id_bsc'];
        $data['id_periode'] = @$param['global_id_periode'];
        $res['table']       = @$param['global_table'];
        
        if($html){
            return $this->template->display_ajax("app/mydashboard/v_mydashboard_table_st", $data, $js, null, TRUE);
        }else{
            $res['html'] = $this->template->display_ajax("app/mydashboard/v_mydashboard_table_st", $data, $js, null, TRUE);
            echo json_encode($res);
        }
    }


    public function load_table_sr_mydashboard($html=FALSE)
    {
        //param
        $data['url'] = $this->url;
        $js['custom']  = ['table_sr_mydashboard'];
        $param = @$this->input->post();
        // echo '<pre>';print_r($param);exit;
        
        //filter global
        $data['year']       = @$param['global_year'];
        $data['month']      = @$param['global_month'];
        $data['id_bsc']     = @$param['global_id_bsc'];
        $data['id_periode'] = @$param['global_id_periode'];
        $res['table']       = @$param['global_table'];

        if($html){
            return $this->template->display_ajax("app/mydashboard/v_mydashboard_table_sr", $data, $js, null, TRUE);
        }else{
            $res['html'] = $this->template->display_ajax("app/mydashboard/v_mydashboard_table_sr", $data, $js, null, TRUE);
            echo json_encode($res);
        }
    }


    public function load_table_so_mydashboard($html=FALSE)
    {
        //param
        $data['url'] = $this->url;
        $js['custom']  = ['table_so_mydashboard'];
        $param = @$this->input->post();
        // echo '<pre>';print_r($param);exit;

        //filter global
        $data['year']       = @$param['global_year'];
        $data['month']      = @$param['global_month'];
        $data['id_bsc']     = @$param['global_id_bsc'];
        $data['id_periode'] = @$param['global_id_periode'];
        $res['table']       = @$param['global_table'];
        
        if($html){
            return $this->template->display_ajax("app/mydashboard/v_mydashboard_table_so", $data, $js, null, TRUE);
        }else{
            $res['html'] = $this->template->display_ajax("app/mydashboard/v_mydashboard_table_so", $data, $js, null, TRUE);
            echo json_encode($res);
        }
    }

    public function load_table_kpi_so_mydashboard($html=FALSE)
    {
        //param
        $data['url'] = $this->url;
        $js['custom']  = ['table_kpi_so_mydashboard'];
        $param = @$this->input->post();
        // echo '<pre>';print_r($param);exit;

        //filter global
        $data['year']       = @$param['global_year'];
        $data['month']      = @$param['global_month'];
        $data['id_bsc']     = @$param['global_id_bsc'];
        $data['id_periode'] = @$param['global_id_periode'];
        $res['table']       = @$param['global_table'];
        
        if($html){
            return $this->template->display_ajax("app/mydashboard/v_mydashboard_table_kpi_so", $data, $js, null, TRUE);
        }else{
            $res['html'] = $this->template->display_ajax("app/mydashboard/v_mydashboard_table_kpi_so", $data, $js, null, TRUE);
            echo json_encode($res);
        }
    }


    public function load_table_si_mydashboard($html=FALSE)
    {
        //param
        $data['url'] = $this->url;
        $js['custom']  = ['table_si_mydashboard'];
        $param = @$this->input->post();
        // echo '<pre>';print_r($param);exit;

        //filter global
        $data['year']       = @$param['global_year'];
        $data['month']      = @$param['global_month'];
        $data['id_bsc']     = @$param['global_id_bsc'];
        $data['id_periode'] = @$param['global_id_periode'];
        $res['table']       = @$param['global_table'];
        
        if($html){
            return $this->template->display_ajax("app/mydashboard/v_mydashboard_table_si", $data, $js, null, TRUE);
        }else{
            $res['html'] = $this->template->display_ajax("app/mydashboard/v_mydashboard_table_si", $data, $js, null, TRUE);
            echo json_encode($res);
        }
    }

    public function load_table_action_plan_mydashboard($html=FALSE)
    {
        //param
        $data['url'] = $this->url;
        $js['custom']  = ['table_action_plan_mydashboard'];
        $param = @$this->input->post();
        // echo '<pre>';print_r($param);exit;

        //filter global
        $data['year']       = @$param['global_year'];
        $data['month']      = @$param['global_month'];
        $data['id_bsc']     = @$param['global_id_bsc'];
        $data['id_periode'] = @$param['global_id_periode'];
        $res['table']       = @$param['global_table'];
        
        if($html){
            return $this->template->display_ajax("app/mydashboard/v_mydashboard_table_action_plan", $data, $js, null, TRUE);
        }else{
            $res['html'] = $this->template->display_ajax("app/mydashboard/v_mydashboard_table_action_plan", $data, $js, null, TRUE);
            echo json_encode($res);
        }
    }

    public function load_table_am_mydashboard($html=FALSE)
    {
        //param
        $data['url'] = $this->url;
        $js['custom']  = ['table_am_mydashboard'];
        $param = @$this->input->post();
        // echo '<pre>';print_r($param);exit;

        //filter global
        $data['year']       = @$param['global_year'];
        $data['month']      = @$param['global_month'];
        $data['id_bsc']     = @$param['global_id_bsc'];
        $data['id_periode'] = @$param['global_id_periode'];
        $res['table']       = @$param['global_table'];
        
        if($html){
            return $this->template->display_ajax("app/mydashboard/v_mydashboard_table_am", $data, $js, null, TRUE);
        }else{
            // $res['html'] = $this->template->display_ajax("app/mydashboard/v_mydashboard_table_am", $data, $js, null, TRUE);
            $res['html'] = '';
            echo json_encode($res);
        }
    }


    public function table_st_mydashboard()
    {    
        // load model view 
        // $this->load->model('global/m_strategic_theme','m_strategic_theme');

        //search default
        $where  = [];
        $whereE = " is_active = 't' ";

        //cek is active
        if(isset($_REQUEST['filter'])){
            foreach ($_REQUEST['filter'] as $row) {
                if(@$row['name'] == 'is_active'){ $whereE = " 0=0 "; }
            }
        }

        //filtering global
        // echo '<pre>';print_r($_REQUEST['filter']);exit;
        // $nip  = @$_REQUEST['dsar_nip'];
        // $date = @$_REQUEST['dsar_date'];
        // $where['a.date_sales'] = date_format(new DateTime($date), "Y-m-d") ; 
        // if($nip != ''){ $where['a.nip'] = $nip; }

        //filtering
        $search = [];
        if(isset($_REQUEST['filter'])){
            foreach ($_REQUEST['filter'] as $row) {

                //default parameter
                if(@$row['name'] != ''){

                    $val= $row['value']; $tipe = $row['tipe']; $search[] = $row['name']; 
                    $name = @$row['name'];
                    // $name = $this->m_strategic_theme->select($row['name']); $name = str_replace(' AS '.$row['name'], ' ', $name );

                    //cek tipe
                    if($val != ''){
                        if($tipe == '1'){
                            $where[' lower('.$name.') LIKE '] = '%'.strtolower($val).'%';
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
            $tipe  = @$_REQUEST['order'][0]['dir'];
            $order = [ $kolom , $tipe];
        }else{
            $order = ['order','ASC'];
        }
        // echo '<pre>';print_r($order);exit;

        
        //table dan join
        $table = "m_strategic_theme AS a";
        $join  = NULL;

        //select 
        $select = "*";
        // $select_field = [ 'id','nip','nik','customer','no_hp' ];
        // $select = array_unique(array_merge($select_field, $search));
        // $select = $this->m_dsar->select($select);

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

            //button
            $btn_edit       = '<button title="Edit" id="'.$id.'" class="btn btn-sm btn-primary btn_edit"><i class="fa fa-edit"></i></button>';
            $btn_delete     = '<button title="Delete" id="'.$id.'" val="f" class="btn btn-sm  btn-danger btn_delete"><i class="fa fa-remove"></i></button>';
            if($rows->is_active == 'f'){
                $btn_delete = '<button title="Active" id="'.$id.'" val="t" class="btn btn-sm  btn-danger btn_delete"><i class="fa fa-check"></i></button>';
            }
            //file
            if($rows->icon == ''){
                $icon = 'No Image';
            }else{
                $icon = '<a target="_blank" href="'.base_url('public/files/icon_strategic_theme/').$rows->icon.'">
                        <img src="'.base_url('public/files/icon_strategic_theme/').$rows->icon.'" width="40em" height="40em"/>
                </a>';
            }

            // $action = @$btn_edit.@$btn_delete.@$btn_active.@$btn_delete_permanent;
            $action = "";

            //isi table disini
            $isi['no']                  = $i;
            $isi['name']                = $rows->name;
            $isi['code']                = $rows->code;
            $isi['description']         = h_read_more($rows->description,20);

            $param[] = $isi;
            $i++;
        }
        $records["data"]            = $param;
        $records["draw"]            = $sEcho;
        $records["recordsTotal"]    = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        echo json_encode($records);
    }

    public function table_sr_mydashboard()
    {    
        // load model view 
        $this->load->model('app/m_strategic_result','m_strategic_result');

        //search default
        $where  = [];
        $whereE = " is_active = 't' ";

        //cek is active
        if(isset($_REQUEST['filter'])){
            foreach ($_REQUEST['filter'] as $row) {
                if(@$row['name'] == 'is_active'){ $whereE = " 0=0 "; }
            }
        }

        //cek multiple role
        $arr_role_id = h_session('ARR_ROLE_ID');
        if(in_array('4',$arr_role_id)){
            $role_id = '4';
        }elseif(in_array('8',$arr_role_id)){
            $role_id = '8';
        }else{
            $role_id = h_session('ROLE_ID');
        }

       //filtering global
        // echo '<pre>';print_r($_REQUEST);exit;
        $id_periode = @$_REQUEST['global_id_periode'];
        $id_bsc = @$_REQUEST['global_id_bsc'];
        $id_strategic_theme = @$_REQUEST['global_id_strategic_theme'];
        if($id_periode != ''){ $where['a.id_periode'] = $id_periode; }
        if($id_bsc != ''){ $where['a.id_bsc'] = $id_bsc; }
        if($id_strategic_theme != ''){ $where['a.id_strategic_theme'] = $id_strategic_theme; }

        //filtering
        $search = [];
        if(isset($_REQUEST['filter'])){
            foreach ($_REQUEST['filter'] as $row) {

                //default parameter
                if(@$row['name'] != ''){

                    $val= $row['value']; $tipe = $row['tipe']; $search[] = $row['name']; 
                    // $name = @$row['name'];
                    $name = $this->m_strategic_result->select($row['name']); $name = str_replace(' AS '.$row['name'], ' ', $name );

                    //cek tipe
                    if($val != ''){
                        if($tipe == '1'){
                            $where[' lower('.$name.') LIKE '] = '%'.strtolower($val).'%';
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
            $order = ['id','DESC'];
        }
        // echo '<pre>';print_r($order);exit;

        
        //table dan join
        $table = "m_strategic_result AS a";
        $join  = NULL;

        //select 
        //select 
        $select = [ 'id','code_strategic_result','name_strategic_result','name_strategic_theme','name_periode','name_bsc',
                        'name_pic_sr','status_sr','name_status_sr','description','target','name_polarisasi',
                        'is_active','created_date','created_by','updated_date','updated_by'
                    ];
        $select = array_unique(array_merge($select, $search));
        $select = $this->m_strategic_result->select($select);

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
        // echo '<pre>';print_r($result);exit;

        $param=[];
        foreach ($result as $rows) {
            $id = @$rows->id;
            $action = '';

            //button delete
            $btn_delete = '<button title="Delete" id="'.$id.'" val="f" class="btn btn-sm  btn-danger btn_delete"><i class="fa fa-remove"></i></button>';
            if($rows->is_active == 'f'){
                $btn_delete = '<button title="Active" id="'.$id.'" val="t" class="btn btn-sm  btn-danger btn_delete"><i class="fa fa-check"></i></button>';
            }

            //jika role admin
            if(in_array( $role_id, h_role_admin())){
                if($rows->status_sr == '1'){
                    $btn_edit = '<button title="Edit" id="'.$id.'" class="btn btn-sm btn-primary btn_edit"><i class="fa fa-edit"></i></button>';
                    $btn_approval = '<button title="Approval" id="'.$id.'" val="2" class="btn btn-sm  btn-warning btn_view"><i class="fa fa-envelope-o"></i></button>';
                    $action =  @$btn_approval.@$btn_edit.@$btn_delete;
                }
                if($rows->status_sr == '3'){
                    $btn_copy   = '<button title="Copy" id="'.$id.'" class="btn btn-sm btn-info btn_copy"><i class="fa fa-copy"></i></button>';
                    $btn_edit   = '<button title="Edit" id="'.$id.'" class="btn btn-sm btn-primary btn_edit"><i class="fa fa-edit"></i></button>';
                    $btn_monev_sr = '<a href="'.base_url('app/monev_sr/index/').$id.'" title="Monitoring Strategic Result" class="btn btn-sm btn-warning"><i class="fa fa-arrow-right"></i></a>';
                    $action =  @$btn_edit.$btn_copy.@$btn_monev_sr;
                }
                if($rows->status_sr == '4'){
                    $btn_approval = '<button title="Approval" id="'.$id.'" val="2" class="btn btn-sm  btn-warning btn_view"><i class="fa fa-envelope-o"></i></button>';
                    $btn_edit = '<button title="Edit" id="'.$id.'" class="btn btn-sm btn-primary btn_edit"><i class="fa fa-edit"></i></button>';
                    $action =  @$btn_approval.@$btn_edit.@$btn_delete;
                }
            }

            $action = "";

            //isi table disini
            $isi['no']                  = $i;
            $isi['id']                  = $rows->id;
            $isi['is_active']           = ($rows->is_active == 't' ? 'Active' : 'Non Active');
            $isi['created_date']        = h_format_date($rows->created_date,'d F Y');
            $isi['created_by']          = $rows->created_by;
            $isi['updated_date']        = h_format_date($rows->updated_date,'d F Y');
            $isi['updated_by']          = $rows->updated_by;
            $isi['action']              = @$action;

            $isi['name']                = h_read_more($rows->name,20);
            $isi['code']                = $rows->code;
            $isi['description']         = h_read_more($rows->description,20);

            $isi['indikator']           = h_read_more($rows->indikator,20);
            $isi['polarisasi']          = $rows->name_polarisasi;
            $isi['ukuran']              = $rows->ukuran;
            $isi['target']              = $rows->target;

            $isi['name_periode']            = h_read_more($rows->name_periode,20);
            $isi['name_bsc']                = h_read_more($rows->name_bsc,20);
            $isi['name_strategic_theme']    = h_read_more($rows->name_strategic_theme,20);
            $isi['name_pic_sr']             = h_read_more($rows->name_pic_sr,20);
            $isi['name_status_sr']          = $rows->name_status_sr;

            $param[] = $isi;
            $i++;
        }
        $records["data"]            = $param;
        $records["draw"]            = $sEcho;
        $records["recordsTotal"]    = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        echo json_encode($records);
    }

    public function table_so_mydashboard()
    {    
        // load model view 
        $this->load->model('app/m_so','m_so');

        //search default
        $where  = [];
        $whereE = " a.\"is_active\" = 't' ";

        //cek multiple role
        $arr_role_id = h_session('ARR_ROLE_ID');
        if(in_array('4',$arr_role_id)){
            $role_id = '4';
        }elseif(in_array('8',$arr_role_id)){
            $role_id = '8';
        }else{
            $role_id = h_session('ROLE_ID');
        }

        //cek role PIC SO
        if($role_id == '4'){ 
            $position_id = h_session('POSITION_ID');
            if($position_id != ''){
                $whereE .= " AND ".$position_id."::text = ANY (string_to_array(a.pic_so,', ')::text[]) "; 
            }else{
                $whereE .= " AND a.pic_so = '' "; 
            }
        }

        //cek multiple role
        $arr_role_id = h_session('ARR_ROLE_ID');
        if(in_array('8',$arr_role_id)){
            $role_id = '8';
        }elseif(in_array('8',$arr_role_id)){
            $role_id = '8';
        }else{
            $role_id = h_session('ROLE_ID');
        }

        //cek role PIC KPI-SO
        if($role_id == '8'){
            $nip = h_session('NIP');
            $where2 = " a.is_active = 't' ";
            $where2 .= " AND '".$nip."'::text = ANY (string_to_array(a.user_pic_kpi_so,', ')::text[]) "; 
            $select2 = "STRING_AGG(a.id_so::character varying, ',') AS arr_id_so";
            $arr_id_so = $this->m_global->getDataAll('m_kpi_so a', null, $where2, $select2)[0]->arr_id_so;
            if($arr_id_so !=  ''){
                $whereE .= " AND a.id IN(".$arr_id_so.") "; 
            }
        }

        //cek role PIC KPI-SO Manager
        if($role_id == '10'){
            $nip = h_session('NIP');
            $where2 = " a.is_active = 't' ";
            $where2 .= " AND '".$nip."'::text = ANY (string_to_array(a.user_pic_manager,', ')::text[]) "; 
            $select2 = "STRING_AGG(a.id_so::character varying, ',') AS arr_id_so";
            $arr_id_so = $this->m_global->getDataAll('m_kpi_so a', null, $where2, $select2)[0]->arr_id_so;
            if($arr_id_so !=  ''){
                $whereE .= " AND a.id IN(".$arr_id_so.") "; 
            }
        }

        
        //cek is active
        if(isset($_REQUEST['filter'])){
            foreach ($_REQUEST['filter'] as $row) {
                if(@$row['name'] == 'is_active'){ $whereE = " 0=0 "; }
            }
        }

        //cek role id, jika pic so
        if($role_id == '4'){
            $where['a.status_so !='] = '1';
        }

        //filtering global
        // echo '<pre>';print_r($_REQUEST);exit;
        $id_bsc = @$_REQUEST['global_id_bsc'];
        $id_perspective = @$_REQUEST['global_id_perspective'];
        if($id_bsc != ''){ $where['a.id_bsc'] = $id_bsc; }
        if($id_perspective != ''){ $where['a.id_perspective'] = $id_perspective; }

        //cek year dan month
        $year = @$_REQUEST['global_year'];
        $month = @$_REQUEST['global_month'];
        if($month != ''){ 
            $month = str_pad($month,2,'0',STR_PAD_LEFT);
            $date_start = $year.'-'.$month.'-01';
            $date_end = $year.'-'.$month.'-01';
        }else{
            $date_start = $year.'-01-01';
            $date_end = $year.'-12-01';
        }
        $whereE .= " AND ((a.\"start_date\" >= '".$date_start."' AND a.\"start_date\" <= '".$date_end."') ";
        $whereE .= " OR (a.\"end_date\" >= '".$date_start."' AND a.\"end_date\" <= '".$date_end."') ";
        $whereE .= " OR (a.\"start_date\" <= '".$date_start."' AND a.\"end_date\" >= '".$date_end."')) ";


        //filtering
        $search = [];
        if(isset($_REQUEST['filter'])){
            foreach ($_REQUEST['filter'] as $row) {

                //default parameter
                if(@$row['name'] != ''){

                    $val= $row['value']; $tipe = $row['tipe']; $search[] = $row['name']; 
                    $name = @$row['name'];
                    $name = $this->m_so->select($row['name']); $name = str_replace(' AS '.$row['name'], ' ', $name );

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
            $order = ['id','DESC'];
        }
        // echo '<pre>';print_r($order);exit;

        
        //table dan join
        $table = "m_so AS a";
        $join  = NULL;

        //select 
        $select = [ 'id','code','name','name_perspective','name_bsc','name_pic_so','status_so','name_status_so',
                            'is_active','created_date','created_by','updated_date','updated_by',
                            'start_date','end_date','description'
                        ];
        $select = array_unique(array_merge($select, $search));
        $select = $this->m_so->select($select);

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
            $action = '';

            //button delete
            $btn_delete = '<button title="Delete" id="'.$id.'" val="f" class="btn btn-sm  btn-danger btn_delete"><i class="fa fa-remove"></i></button>';
            if($rows->is_active == 'f'){
                $btn_delete = '<button title="Active" id="'.$id.'" val="t" class="btn btn-sm  btn-danger btn_delete"><i class="fa fa-check"></i></button>';
            }

            //jika role admin
            if(in_array( $role_id, h_role_admin())){
                if($rows->status_so == '1'){
                    $btn_edit = '<button title="Edit" id="'.$id.'" class="btn btn-sm btn-primary btn_edit"><i class="fa fa-edit"></i></button>';
                    $btn_approval = '<button title="Approval" id="'.$id.'" val="2" class="btn btn-sm  btn-warning btn_view"><i class="fa fa-envelope-o"></i></button>';
                    $action =  @$btn_approval.@$btn_edit.@$btn_delete;
                }
                if($rows->status_so == '3'){
                    $btn_copy   = '<button title="Copy" id="'.$id.'" class="btn btn-sm btn-info btn_copy"><i class="fa fa-copy"></i></button>';
                    $btn_edit   = '<button title="Edit" id="'.$id.'" class="btn btn-sm btn-primary btn_edit"><i class="fa fa-edit"></i></button>';
                    $btn_kpi_so = '<a href="'.base_url('app/kpi_so/index/').$id.'" title="KPI SO" class="btn btn-sm btn-warning"><i class="fa fa-arrow-right"></i></a>';
                    $action =  @$btn_edit.$btn_copy.@$btn_kpi_so;
                }
                if($rows->status_so == '4'){
                    $btn_approval = '<button title="Approval" id="'.$id.'" val="2" class="btn btn-sm  btn-warning btn_view"><i class="fa fa-envelope-o"></i></button>';
                    $btn_edit = '<button title="Edit" id="'.$id.'" class="btn btn-sm btn-primary btn_edit"><i class="fa fa-edit"></i></button>';
                    $action =  @$btn_approval.@$btn_edit.@$btn_delete;
                }
            }

            $btn_review_so = '<a href="javascript:;" id="'.$id.'" title="View" class="btn btn-sm btn-warning btn_review_so"> View</a>';
            @$action = $btn_review_so;

            //isi table disini
            $isi['no']                  = $i;
            $isi['id']                  = $rows->id;
            $isi['is_active']           = ($rows->is_active == 't' ? 'Active' : 'Non Active');
            $isi['created_date']        = h_format_date($rows->created_date,'d F Y');
            $isi['created_by']          = $rows->created_by;
            $isi['updated_date']        = h_format_date($rows->updated_date,'d F Y');
            $isi['updated_by']          = $rows->updated_by;

            $isi['code']                = $rows->code;
            $isi['name']                = h_read_more($rows->name,30);
            $isi['id_perspective']      = h_read_more($rows->name_perspective,20);
            $isi['id_bsc']              = $rows->name_bsc;
            $isi['start_date']          = $rows->start_date;
            $isi['end_date']            = $rows->end_date;
            $isi['name_pic_so']         = h_read_more($rows->name_pic_so,20);
            $isi['status_so']           = $rows->name_status_so;
            $isi['description']         = h_read_more($rows->description,20);

            $isi['action']              = '<div style="width:60px;'.($iTotalRecords=='1'?'height:50px;':'').'">'.$action.'</div>';

            $param[] = $isi;
            $i++;
        }
        $records["data"]            = $param;
        $records["draw"]            = $sEcho;
        $records["recordsTotal"]    = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        echo json_encode($records);
    }

    public function table_kpi_so_mydashboard()
    {    
        // load model view 
        $this->load->model('app/m_monev_kpi_so','m_monev_kpi_so');

        //search default
        $where  = [];
        $whereE = " is_active = 't' AND status_kpi_so = '3' ";

        //cek multiple role
        $arr_role_id = h_session('ARR_ROLE_ID');
        if(in_array('8',$arr_role_id)){
            $role_id = '8';
        }elseif(in_array('10',$arr_role_id)){
            $role_id = '10';
        }else{
            $role_id = h_session('ROLE_ID');
        }

        //cek role pic kpi-so
        if ($role_id == '8'){ 
            $nip = h_session('NIP');
            $whereE .= " AND '".$nip."'::text = ANY (string_to_array(a.user_pic_kpi_so,', ')::text[]) "; 
        }

        //cek role pic kpi-so manager
        if ($role_id == '10'){ 
            $nip = h_session('NIP');
            $whereE .= " AND '".$nip."'::text = ANY (string_to_array(a.user_pic_manager,', ')::text[]) "; 
        }


        //cek is active
        if(isset($_REQUEST['filter'])){
            foreach ($_REQUEST['filter'] as $row) {
                if(@$row['name'] == 'is_active'){ $whereE = " 0=0 "; }
            }
        }

        //filtering global
        // echo '<pre>';print_r($_REQUEST);exit;
        $id_bsc = @$_REQUEST['global_id_bsc'];
        $id_perspective = @$_REQUEST['global_id_perspective'];
        $id_so = @$_REQUEST['global_id_so'];
        $id_kpi_so = @$_REQUEST['global_id_kpi_so'];

        if($id_bsc != ''){ $where['a.id_bsc'] = $id_bsc; }
        if($id_perspective != ''){ $where['a.id_perspective'] = $id_perspective; }
        if($id_so != ''){ $where['a.id_so'] = $id_so; }
        if($id_kpi_so != ''){ $where['a.id'] = $id_kpi_so; }

        //cek year dan month
        $year = @$_REQUEST['global_year'];
        $month = @$_REQUEST['global_month'];
        if($month != ''){ 
            $month = str_pad($month,2,'0',STR_PAD_LEFT);
            $date_start = $year.'-'.$month.'-01';
            $date_end = $year.'-'.$month.'-01';
        }else{
            $date_start = $year.'-01-01';
            $date_end = $year.'-12-01';
        }
        $whereE .= " AND ((a.\"start_date\" >= '".$date_start."' AND a.\"start_date\" <= '".$date_end."') ";
        $whereE .= " OR (a.\"end_date\" >= '".$date_start."' AND a.\"end_date\" <= '".$date_end."') ";
        $whereE .= " OR (a.\"start_date\" <= '".$date_start."' AND a.\"end_date\" >= '".$date_end."')) ";

        //filtering
        $search = [];
        if(isset($_REQUEST['filter'])){
            foreach ($_REQUEST['filter'] as $row) {

                //default parameter
                if(@$row['name'] != ''){

                    $val= $row['value']; $tipe = $row['tipe']; $search[] = $row['name']; 
                    $name = @$row['name'];
                    $name = $this->m_monev_kpi_so->select($row['name']); $name = str_replace(' AS '.$row['name'], ' ', $name );

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
            $order = ['id','DESC'];
        }
        // echo '<pre>';print_r($order);exit;

        
        //table dan join
        $table = "m_kpi_so AS a";
        $join  = NULL;

        //select 
        $select_field = [ 'id','code','is_active','created_date','created_by','updated_date','updated_by',
                            'id_so','id_perspective','id_bsc','id_periode','description','code_so',
                            'name_perspective','name_periode','name_bsc','name_so','name_kpi_so','name_pic_kpi_so','name_polarisasi',
                            'polarisasi','ukuran','frekuensi_pengukuran',
                            'arr_target','arr_target_from','arr_target_to'
                        ];
        $select = array_unique(array_merge($select_field, $search));
        $select = $this->m_monev_kpi_so->select($select);

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

            //button
            $btn_add_progress = '<button title="view" id="'.$id.'" class="btn btn-sm  btn-warning btn_add_progress">View</button>';
            $action = $btn_add_progress;

            //isi table disini
            $isi['no']                  = $i;
            $isi['id']                  = $rows->id;
            $isi['code']                = $rows->code;
            $isi['is_active']           = ($rows->is_active == 't' ? 'Active' : 'Non Active');
            $isi['created_date']        = h_format_date($rows->created_date,'d F Y');
            $isi['created_by']          = $rows->created_by;
            $isi['updated_date']        = h_format_date($rows->updated_date,'d F Y');
            $isi['updated_by']          = $rows->updated_by;

            $isi['code_so']             = $rows->code_so;
            $isi['name_so']             = h_read_more($rows->name_so,30);
            $isi['name_kpi_so']         = h_read_more($rows->name_kpi_so,60);
            $isi['pic_kpi_so']          = str_replace(', ','<br>',$rows->name_pic_kpi_so );
            $isi['name_polarisasi']     = $rows->name_polarisasi;
            $isi['ukuran']              = $rows->ukuran;
            $isi['frekuensi_pengukuran']= $rows->frekuensi_pengukuran;

            //target month
            if($rows->polarisasi == '10'){
                if($rows->arr_target_from == ''){
                    for($a=1;$a<=5;$a++){
                        $isi['target_'.$a] = '0';
                    }
                }else{
                    $arr_target_from = explode(', ',$rows->arr_target_from);
                    $arr_target_to = explode(', ',$rows->arr_target_to);
                    $b=0; foreach($arr_target_from as $val){ $b++;
                        $isi['target_'.$b] = $val.' - '.@$arr_target_to[$b-1];
                    }
                }
            }else{
                if($rows->arr_target == ''){
                    for($a=1;$a<=5;$a++){
                        $isi['target_'.$a] = '0';
                    }
                }else{
                    $arr_target = explode(', ',$rows->arr_target);
                    $b=0; foreach($arr_target as $val){ $b++;
                        $isi['target_'.$b] = $val;
                    }
                }
            }

            $isi['id_so']               = h_read_more($rows->name_so,30);
            $isi['id_perspective']      = $rows->name_perspective;
            $isi['id_bsc']              = $rows->name_bsc;
            $isi['id_periode']          = $rows->name_periode;
            $isi['description']         = h_read_more($rows->description,20);

            $isi['action']              = '<div style="width:80px;'.($iTotalRecords=='1'?'height:50px;':'').'">'.$action.'</div>';

            $param[] = $isi;
            $i++;
        }
        $records["data"]            = $param;
        $records["draw"]            = $sEcho;
        $records["recordsTotal"]    = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        // echo '<pre>';print_r($param);exit;

        echo json_encode($records);
    }

    public function table_si_mydashboard()
    {    
        // load model view 
        $this->load->model('app/m_review_si','m_review_si');

        //search default
        $where  = [];
        $whereE = " a.is_active = 't'";

        //cek multiple role
        $arr_role_id = h_session('ARR_ROLE_ID');
        if(in_array('5',$arr_role_id)){
            $role_id = '5';
        }elseif(in_array('9',$arr_role_id)){
            $role_id = '9';
        }else{
            $role_id = h_session('ROLE_ID');
        }

        //cek role PIC SO, PICKPI-SO
        if($role_id == '4' || $role_id == '8'){ 
            $position_id = h_session('POSITION_ID');
            $whereE .= " AND ".$position_id."::text = ANY (string_to_array(a.pic_si,', ')::text[]) "; 
        }

        //cek is active
        if(isset($_REQUEST['filter'])){
            foreach ($_REQUEST['filter'] as $row) {
                if(@$row['name'] == 'is_active'){ $whereE = " 0=0 "; }
            }
        }


        // ============= cek IC sesuai SI =================
        //cek role PIC SI
        if($role_id == '5'){ 
            //status selain draft
            // $whereE .= " AND a.status_si != '1' " ;
            //ambil SI yang di definisikan 
            //select id si sesuai pic
            $position_id = h_session('POSITION_ID');
            $nip = h_session('NIP');
            $where2 = "a.nip = '".$nip."' AND a.is_active='t'";
            $id_si2 = @$this->m_global->getDataAll('m_user_ic_si AS a', null, $where2, 'a.id_si')[0]->id_si;
            //cek
            $where3 = "a.status_si = '3' AND a.is_active = 't'";
            if($id_si2 != ''){
                $where3 .= " AND (a.id IN(".$id_si2.") OR ".$position_id."::text = ANY (string_to_array(a.pic_si,', ')::text[]))";
            }else{
                $where3 .= " AND ".$position_id."::text = ANY (string_to_array(a.pic_si,', ')::text[]) "; 
            }
            $arr_si = @$this->m_global->getDataAll('m_si a', null, $where3, 'a.id');
            $arr_id_si = [];
            foreach($arr_si as $row){ $arr_id_si[] = $row->id; }
            if(count($arr_id_si) > 0 ){
                $arr_id_si = join(',',$arr_id_si);
                $whereE .= " AND a.id_si IN(".$arr_id_si.")";
            }
        }
        //cek role PIC IC
        if($role_id == '9'){
            //ambil SI yang di definisikan 
            $nip = h_session('NIP');
            $where2 = " a.nip = '".$nip."'";
            $id_si2 = @$this->m_global->getDataAll('m_user_ic_si AS a', null, $where2, 'a.id_si')[0]->id_si;
            if($id_si2 != ''){
                $whereE .= " AND a.id_si IN(".$id_si2.")";
            }
        }
        // ========================================



        //filtering global
        // echo '<pre>';print_r($_REQUEST);exit;
        $id_bsc = @$_REQUEST['global_id_bsc'];
        $status_complete = @$_REQUEST['global_status_complete'];
        $year = @$_REQUEST['global_year'];
        $month = @$_REQUEST['global_month'];
        if($id_bsc != ''){ 
            $whereE .=  " AND (SELECT b.id_bsc FROM m_si b WHERE b.id = a.id_si) = '$id_bsc'";
        }
        if($status_complete != ''){ 
            $whereE .=  " AND a.status_complete = '$status_complete'";
        }
        if($year != ''){ 
            $whereE .=  " AND a.year = '$year'";
        }
        if($month != ''){ 
            $whereE .=  " AND a.month = '$month'";
        }

        //filtering
        $search = [];
        if(isset($_REQUEST['filter'])){
            foreach ($_REQUEST['filter'] as $row) {

                //default parameter
                if(@$row['name'] != ''){

                    $val= $row['value']; $tipe = $row['tipe']; $search[] = $row['name']; 
                    $name = @$row['name'];
                    $name = $this->m_review_si->select($row['name']); $name = str_replace(' AS '.$row['name'], ' ', $name );

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
        $table = "m_monev_si_month AS a";
        $join  = NULL;

        //select 
        $select = "*";
        // $select_field = [ 'id','nip','nik','customer','no_hp' ];
        // $select = array_unique(array_merge($select_field, $search));
        $select = $this->m_review_si->select($select, $year, $month, $status_complete);

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
            $id_si = @$rows->id_si;
            
            $btn_ic = '<a href="javascript:;" id="'.$id.'" id_si="'.$id_si.'" title="IC" title_popup="'.$rows->name.'" class="btn btn-sm btn-primary btn_ic"> IC</a>';
            $btn_issue = '<a href="javascript:;" id="'.$id.'" id_si="'.$id_si.'" title="Issue" class="btn btn-sm btn-danger btn_issue" style="border-radius:0px;"> Issue</a>';
            @$action = $btn_ic.$btn_issue;

            //isi table disini
            $isi['no']                  = $i;
            $isi['id']                  = $rows->id;

            $isi['year']                = @$rows->year;
            $isi['month']               = @$rows->month;
            $isi['code']                = $rows->code;
            $isi['name']                = h_read_more($rows->name,50);
            $isi['id_bsc']              = $rows->name_bsc;
            $isi['name_pic_si']         = $rows->name_pic_si;
            $isi['status_complete']     = $rows->name_status_complete;
            $isi['color']               = '<div style="'.$rows->name_color.'">'.$rows->code_color.'</div>';
            $isi['overall_complete']    = $rows->overall_complete;
            $isi['complete_on_year']    = $rows->complete_on_year;

            $isi['action']              = '<div style="width:100px;'.($iTotalRecords=='1'?'height:50px;':'').'">'.$action.'</div>';


            $param[] = $isi;
            $i++;
        }
        $records["data"]            = $param;
        $records["draw"]            = $sEcho;
        $records["recordsTotal"]    = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        //reload grafik
        $arr_total  = @$this->m_global->getDataAll($table, $join, $where,"a.color, COUNT(a.is_active) total", $whereE, null,null,null,'a.color');
        // echo $this->db->last_query();exit;    
        $total = 0 ;
        foreach($arr_total as $row){ $total = @$total + (int)$row->total; }
        $arr_color = [0,0,0,0];
        foreach($arr_total as $row){
            if($row->color == '4'){ $arr_color[0] = (int)$row->total; }
            if($row->color == '5'){ $arr_color[1] = (int)$row->total; }
            if($row->color == '6'){ $arr_color[2] = (int)$row->total; }
            if($row->color == '7'){ $arr_color[3] = (int)$row->total; }
        }
        $records["data_grafik"] = $arr_color;
        // echo '<pre>';print_r($arr_color);exit;

        echo json_encode($records);
    }

    public function table_action_plan_mydashboard()
    {    
        // load model view 
        $this->load->model('app/m_ic','m_ic');

        //search default
        $where  = [];
        $whereE = " is_active = 't' AND parent= '0' ";

        //cek is active
        if(isset($_REQUEST['filter'])){
            foreach ($_REQUEST['filter'] as $row) {
                if(@$row['name'] == 'is_active'){ $whereE = " 0=0 "; }
            }
        }

        //filtering global
        // echo '<pre>';print_r($_REQUEST);exit;
        $id_bsc = @$_REQUEST['global_id_bsc'];
        $id_si = @$_REQUEST['global_id_si'];
        if($id_bsc != ''){ $where['a.id_bsc'] = $id_bsc; }
        if($id_si != ''){ $where['a.id_si'] = $id_si; }
        
        //cek year dan month
        $year = @$_REQUEST['global_year'];
        if($year != ''){
            $date_start = $year.'-01-01';
            $date_end = $year.'-12-01';
            $whereE .= " AND ((a.\"start_date\" >= '".$date_start."' AND a.\"start_date\" <= '".$date_end."') ";
            $whereE .= " OR (a.\"end_date\" >= '".$date_start."' AND a.\"end_date\" <= '".$date_end."') ";
            $whereE .= " OR (a.\"start_date\" <= '".$date_start."' AND a.\"end_date\" >= '".$date_end."')) ";
        }


        //cek multiple role
        $arr_role_id = h_session('ARR_ROLE_ID');
        if(in_array('5',$arr_role_id)){
            $role_id = '5';
        }elseif(in_array('9',$arr_role_id)){
            $role_id = '9';
        }else{
            $role_id = h_session('ROLE_ID');
        }


        //cek editing role Admin, PIC SI, PIC IC
        $arr_id_si = [];
        $editing = 'yes';
        if(in_array($role_id, h_role_admin())){ 
            $editing = 'yes';
        }
        if( $role_id == '5'){ 
            $editing = 'yes';
        }
        if( $role_id == '9'){
            //cek editing
            $user_id = h_session('USER_ID');
            $where2 = ['a.is_active'=>'t', 'a.id_bsc'=>$id_bsc, 'a.request_by'=>$user_id, 'a.status_request'=>'3', 'a.status_finished'=>'0'];
            $arr = @$this->m_global->getDataAll('m_request_ic a', null,  $where2, "a.id_si", null, "a.id_si ASC", null, null, "a.id_si");
            $arr_id_si = [];
            foreach($arr as $row){ $arr_id_si[] = $row->id_si; }
            if(count($arr_id_si) > 0){
                $editing = 'yes';
            }else{
                $editing = 'no';
            }
            //cek pic ic si
            $nip = h_session('NIP');
            $where2 = " nip = '".$nip."'";
            $id_si = @$this->m_global->getDataAll('m_user_ic_si AS a', null, $where2, 'id_si')[0]->id_si;
            if($id_si != ''){
                $whereE .= " AND a.id_si IN(".$id_si.")";
            }else{
                $whereE .= " AND a.id_si IS NULL";
            }
        }



        // ============= cek IC sesuai SI =================
        //cek role PIC SI
        if($role_id == '5'){ 
            //status selain draft
            // $whereE .= " AND a.status_si != '1' " ;
            //ambil SI yang di definisikan 
            //select id si sesuai pic
            $position_id = h_session('POSITION_ID');
            $nip = h_session('NIP');
            $where2 = "a.nip = '".$nip."' AND a.is_active='t'";
            $id_si2 = @$this->m_global->getDataAll('m_user_ic_si AS a', null, $where2, 'a.id_si')[0]->id_si;
            //cek
            $where3 = "a.status_si = '3' AND a.is_active = 't'";
            if($id_si2 != ''){
                $where3 .= " AND (a.id IN(".$id_si2.") OR ".$position_id."::text = ANY (string_to_array(a.pic_si,', ')::text[]))";
            }else{
                $where3 .= " AND ".$position_id."::text = ANY (string_to_array(a.pic_si,', ')::text[]) "; 
            }
            $arr_si = @$this->m_global->getDataAll('m_si a', null, $where3, 'a.id');
            $arr_id_si = [];
            foreach($arr_si as $row){ $arr_id_si[] = $row->id; }
            if(count($arr_id_si) > 0 ){
                $arr_id_si = join(',',$arr_id_si);
                $whereE .= " AND a.id_si IN(".$arr_id_si.")";
            }else{
                $whereE .= " AND a.id_si is NULL ";
            }
        }
        //cek role PIC IC
        if($role_id == '9'){
            //ambil SI yang di definisikan 
            $nip = h_session('NIP');
            $where2 = " a.nip = '".$nip."'";
            $id_si2 = @$this->m_global->getDataAll('m_user_ic_si AS a', null, $where2, 'a.id_si')[0]->id_si;
            if($id_si2 != ''){
                $whereE .= " AND a.id_si IN(".$id_si2.")";
            }else{
                $whereE .= " AND a.id_si is NULL ";
            }
        }
        // ========================================



        //filtering
        $search = [];
        if(isset($_REQUEST['filter'])){
            foreach ($_REQUEST['filter'] as $row) {

                //default parameter
                if(@$row['name'] != ''){

                    $val= $row['value']; $tipe = $row['tipe']; $search[] = $row['name']; 
                    $name = @$row['name'];
                    $name = $this->m_ic->select($row['name']); $name = str_replace(' AS '.$row['name'], ' ', $name );

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
        $table = "m_action_plan AS a";
        $join  = NULL;
        $select = [ 'id','name','code','is_active','created_date','created_by','updated_date','updated_by','status_action_plan',
                    'name_pic_action_plan','deliverable','weighting_factor','budget_currency','id_si',
                    'name_si','code_si','name_bsc','name_status_action_plan','name_pic_action_plan','name_pic_action_plan2',
                    'start_date','end_date'
                ];
        $select = array_unique(array_merge($select, $search));
        $select = $this->m_ic->select($select);

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
            $id_si = @$rows->id_si;
            $action = '';

            //button delete
            $btn_delete = '<button title="Delete" id="'.$id.'" val="f" class="btn btn-sm  btn-danger btn_delete"><i class="fa fa-remove"></i></button>';
            if($rows->is_active == 'f'){
                $btn_delete = '<button title="Active" id="'.$id.'" val="t" class="btn btn-sm  btn-danger btn_delete"><i class="fa fa-check"></i></button>';
            }

            //jika role admin
            if(in_array( $role_id, h_role_admin())){
                if($rows->status_action_plan == '1'){
                    $btn_sub_action_plan = '<button title="Add Sub Action Plan" id="'.$id.'" class="btn btn-sm btn-primary btn_sub_action_plan"><i class="fa fa-plus"></i> Sub</button>';
                    $btn_edit = '<button title="Edit" id="'.$id.'" class="btn btn-sm btn-primary btn_edit"><i class="fa fa-edit"></i></button>';
                    $btn_approval = '<button title="Approval" id="'.$id.'" val="2" class="btn btn-sm  btn-warning btn_view"><i class="fa fa-envelope-o"></i></button>';
                    $action =  @$btn_sub_action_plan.@$btn_edit.@$btn_copy.@$btn_approval.@$btn_delete;
                }
                if($rows->status_action_plan == '3'){
                    $btn_sub_action_plan = '<button title="Add Sub Action Plan" id="'.$id.'" class="btn btn-sm btn-primary btn_sub_action_plan"><i class="fa fa-plus"></i> Sub</button>';
                    $btn_copy   = '<button title="Copy" id="'.$id.'" class="btn btn-sm btn-info btn_copy"><i class="fa fa-copy"></i></button>';
                    $btn_edit   = '<button title="Edit" id="'.$id.'" class="btn btn-sm btn-primary btn_edit"><i class="fa fa-edit"></i></button>';
                    $btn_monev_si = '<a href="'.base_url('app/monev_si/index/').$id_si.'" title="Monev IC" class="btn btn-sm btn-warning"><i class="fa fa-arrow-right"></i></a>';
                    $action =  @$btn_sub_action_plan.@$btn_edit.@$btn_copy.@$btn_delete.@$btn_monev_si;
                }
                if($rows->status_action_plan == '4'){
                    $btn_edit = '<button title="Edit" id="'.$id.'" class="btn btn-sm btn-primary btn_edit"><i class="fa fa-edit"></i></button>';
                    $action = @$btn_edit.@$btn_delete;
                }
            }

            //jika role PIC SI
            if($role_id == '5'){
                if($rows->status_action_plan == '1'){
                    $btn_sub_action_plan = '<button title="Add Sub Action Plan" id="'.$id.'" class="btn btn-sm btn-primary btn_sub_action_plan"><i class="fa fa-plus"></i> Sub</button>';
                    $btn_edit = '<button title="Edit" id="'.$id.'" class="btn btn-sm btn-primary btn_edit"><i class="fa fa-edit"></i></button>';
                    $btn_approval = '<button title="Approval" id="'.$id.'" val="2" class="btn btn-sm  btn-warning btn_view"><i class="fa fa-envelope-o"></i></button>';
                    $action =  @$btn_sub_action_plan.@$btn_edit.@$btn_copy.@$btn_approval.@$btn_delete;
                }
                if($rows->status_action_plan == '3'){
                    $btn_sub_action_plan = '<button title="Add Sub Action Plan" id="'.$id.'" class="btn btn-sm btn-primary btn_sub_action_plan"><i class="fa fa-plus"></i> Sub</button>';
                    $btn_copy   = '<button title="Copy" id="'.$id.'" class="btn btn-sm btn-info btn_copy"><i class="fa fa-copy"></i></button>';
                    $btn_edit   = '<button title="Edit" id="'.$id.'" class="btn btn-sm btn-primary btn_edit"><i class="fa fa-edit"></i></button>';
                    $btn_monev_si = '<a href="'.base_url('app/monev_si/index/').$id_si.'" title="Monev IC" class="btn btn-sm btn-warning"><i class="fa fa-arrow-right"></i></a>';
                    $action =  @$btn_sub_action_plan.@$btn_edit.@$btn_copy.@$btn_delete.@$btn_monev_si;
                }
                if($rows->status_action_plan == '4'){
                    $btn_approval = '<button title="Approval" id="'.$id.'" val="2" class="btn btn-sm  btn-warning btn_view"><i class="fa fa-envelope-o"></i></button>';
                    $btn_edit = '<button title="Edit" id="'.$id.'" class="btn btn-sm btn-primary btn_edit"><i class="fa fa-edit"></i></button>';
                    $action = @$btn_approval.@$btn_edit.@$btn_delete;
                }
            }


            //jika role PIC IC
            if($role_id == '9'){
                if($rows->status_action_plan == '1'){
                    $btn_sub_action_plan = '<button title="Add Sub Action Plan" id="'.$id.'" class="btn btn-sm btn-primary btn_sub_action_plan"><i class="fa fa-plus"></i> Sub</button>';
                    $btn_edit = '<button title="Edit" id="'.$id.'" class="btn btn-sm btn-primary btn_edit"><i class="fa fa-edit"></i></button>';
                    $btn_approval = '<button title="Approval" id="'.$id.'" val="2" class="btn btn-sm  btn-warning btn_view"><i class="fa fa-envelope-o"></i></button>';
                    $action =  @$btn_sub_action_plan.@$btn_edit.@$btn_copy.@$btn_approval.@$btn_delete;
                }
                if($rows->status_action_plan == '3'){
                    $btn_sub_action_plan = '<button title="Add Sub Action Plan" id="'.$id.'" class="btn btn-sm btn-primary btn_sub_action_plan"><i class="fa fa-plus"></i> Sub</button>';
                    $btn_copy   = '<button title="Copy" id="'.$id.'" class="btn btn-sm btn-info btn_copy"><i class="fa fa-copy"></i></button>';
                    $btn_edit   = '<button title="Edit" id="'.$id.'" class="btn btn-sm btn-primary btn_edit"><i class="fa fa-edit"></i></button>';
                    $btn_monev_si = '<a href="'.base_url('app/monev_si/index/').$id.'" title="Monev IC" class="btn btn-sm btn-warning"><i class="fa fa-arrow-right"></i></a>';
                    $action =  @$btn_sub_action_plan.@$btn_edit.@$btn_copy.@$btn_delete.@$btn_monev_si;
                }
                if($rows->status_action_plan == '4'){
                    $btn_approval = '<button title="Approval" id="'.$id.'" val="2" class="btn btn-sm  btn-warning btn_view"><i class="fa fa-envelope-o"></i></button>';
                    $btn_edit = '<button title="Edit" id="'.$id.'" class="btn btn-sm btn-primary btn_edit"><i class="fa fa-edit"></i></button>';
                    $action = @$btn_approval.@$btn_edit.@$btn_delete;
                }
            }


            //cek id si yang diedit
            if($editing == 'yes'){
                if($role_id == '9'){
                    if(in_array($rows->id_si, $arr_id_si)){
                        $action = $action;
                    }else{
                        $action = "";
                    }
                }else{
                    $action = $action;
                }
            }else{
                $action = "";
            }


            $btn_sub_action_plan = '<button title="View Sub" id="'.$id.'" class="btn btn-sm btn-warning btn_sub_action_plan">View Sub</button>';
            $action = @$btn_sub_action_plan;


            //isi table disini
            $isi['no']                  = $i;
            $isi['id']                  = $rows->id;
            
            $isi['id_si']               = $rows->name_si;
            $isi['id_bsc']              = $rows->name_bsc;
            $isi['code']                = $rows->code;
            $isi['name']                = h_read_more($rows->name,40);
            $isi['pic_action_plan']     = h_read_more($rows->name_pic_action_plan,10).''.h_read_more($rows->name_pic_action_plan2,10);
            $isi['deliverable']         = h_read_more($rows->deliverable,20);
            $isi['weighting_factor']    = round($rows->weighting_factor, 2, PHP_ROUND_HALF_DOWN);
            $isi['budget_currency']     = $rows->budget_currency;
            $isi['status_action_plan']  = $rows->name_status_action_plan;
            $isi['start_date']          = $rows->start_date;
            $isi['end_date']            = $rows->end_date;

            $isi['is_active']           = ($rows->is_active == 't' ? 'Active' : 'Non Active');
            $isi['created_date']        = h_format_date($rows->created_date,'d F Y');
            $isi['created_by']          = $rows->created_by;
            $isi['updated_date']        = h_format_date($rows->updated_date,'d F Y');
            $isi['updated_by']          = $rows->updated_by;

            $isi['action']              = '<div style="width:95px;'.($iTotalRecords=='1'?'height:50px;':'').'">'.$action.'</div>';

            $param[] = $isi;
            $i++;
        }
        $records["data"]            = $param;
        $records["draw"]            = $sEcho;
        $records["recordsTotal"]    = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        $records["total_data"]      = $iTotalRecords;


        // echo '<pre>';print_r($param);exit;

        echo json_encode($records);
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
            $id = $this->input->post('id');
            $val = $this->input->post('val');
            $data['review_status'] = $val;
            $data['review_date'] = date("Y-m-d H:i:s");
            $res = $this->m_global->update('m_inbox', $data, ['id' => $id]);
            $res['message'] = 'Success!';
            echo json_encode($res);
        // }
    }

}