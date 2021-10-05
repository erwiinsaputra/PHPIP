<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Review_si extends MX_Controller {
    
    private $prefix         = 'review_si';
    private $table_db       = 'm_so';
    private $title          = 'Strategic Initiative (SI) Review';
    private $url            = 'app/review_si';

    function __construct() {
        parent::__construct();
        $this->middleware('guest', 'forbidden');
    }

    public function index()
    {
        csrf_init();
        $data['title']      = $this->title;
        $data['url']        = $this->url;
        $data['breadcrumb'] = [$this->title => $this->url];

        //bsc
        $data['bsc'] = $this->m_global->getDataAll('m_bsc', null,  ['is_active'=>'t'], '*', null, "name ASC");
        
        //status
        $data['status_complete']  = $this->m_global->getDataAll('m_status', null, ['is_active'=>'t', 'type'=>'Monev Action Plan'], '*', null, '"order" ASC');

        //year
        $data['start_year'] = @$this->m_global->getDataAll('m_periode', null, ['is_active'=>'t'], 'MIN(start_year) AS year')[0]->year;
        $data['end_year']   = @$this->m_global->getDataAll('m_periode', null, ['is_active'=>'t'], 'MAX(end_year) AS year')[0]->year;

        $js['custom']       = ['table_review_si'];
        $this->template->display($this->url.'/v_'.$this->prefix, $data, $js);
    }

    public function table_review_si()
    {    
        // load model view 
        $this->load->model('app/m_review_si','m_review_si');

        //search default
        $where  = [];
        $whereE = " a.is_active = 't'";

        //cek role PIC SO, PICKPI-SO
        if(h_session('ROLE_ID') == '4' || h_session('ROLE_ID') == '8'){ 
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
        if(h_session('ROLE_ID') == '5'){ 
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
        if(h_session('ROLE_ID') == '9'){
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
            $isi['action']              = @$action;

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



    public function load_ic() {
        csrf_init();
        $data['url'] = $this->url;

        $this->load->model('app/m_monev_si','m_monev_si');

        $id_si = @$this->input->post('id_si');
        $year = @$this->input->post('year');
        $data['year'] = $year;
        $data['id_si'] = $id_si;

        //get data action plan
        $whereE = " a.id_si = $id_si AND is_active = 't'";
        $date_start = $year.'-01-01';
        $date_end = $year.'-12-01';
        // $whereE .= " AND ((a.\"start_date\" >= '".$date_start."' AND a.\"start_date\" <= '".$date_end."') ";
        // $whereE .= " OR (a.\"end_date\" >= '".$date_start."' AND a.\"end_date\" <= '".$date_end."') ";
        // $whereE .= " OR (a.\"start_date\" <= '".$date_start."' AND a.\"end_date\" >= '".$date_end."')) ";
        $select = [ 'id','name','code','is_active','created_date','created_by','updated_date','updated_by','status_action_plan',
            'name_pic_action_plan','deliverable','weighting_factor','budget_currency','id_si',
            'name_si','code_si','name_bsc','name_status_action_plan','name_pic_action_plan','name_pic_action_plan2',
            'start_date','end_date','parent','budget_year'
        ];
        $select = $this->m_monev_si->select_action_plan($select, $year);
        $order = "a.code ASC";
        $arr_action_plan = @$this->m_global->getDataAll('m_action_plan AS a', null, $whereE, $select, null, $order);
        // echo $this->db->last_query();exit;
        // echo '<pre>';print_r($arr_action_plan);exit;
        $data['arr_action_plan'] = $arr_action_plan;


        // //get data tahun
        // $data['start_year'] = substr(@$action_plan->start_date,0,4);
        // $data['end_year']   = substr(@$action_plan->end_date,0,4);

        //get data action plan
        // $where = ['a.id_si'=>$id_si];
        // $select = " a.*, (SELECT DISTINCT STRING_AGG ( b.\"SINGKATAN_POSISI\" :: CHARACTER VARYING, ',' ) FROM \"ERP_STO_REAL\" b  
        //                     WHERE b.\"POSITION_ID\" ::text = ANY (string_to_array(a.pic_action_plan,', ')::text[])
        //                 ) AS name_pic_action_plan";
        // $action_plan = @$this->m_global->getDataAll('m_action_plan a', null, $where, $select)[0];
        // $data['action_plan'] = $action_plan;

        // //get data tahun
        // $data['start_year'] = substr(@$action_plan->start_date,0,4);
        // $data['end_year']   = substr(@$action_plan->end_date,0,4);

        // //get data sub action plan
        // $where = ['a.parent'=>$id_si, 'is_active'=>'t'];
        // $select = " a.*, (SELECT DISTINCT STRING_AGG ( b.\"SINGKATAN_POSISI\" :: CHARACTER VARYING, ',' ) FROM \"ERP_STO_REAL\" b  
        //                     WHERE b.\"POSITION_ID\" ::text = ANY (string_to_array(a.pic_action_plan,', ')::text[])
        //                 ) AS name_pic_action_plan";
        // $sub_action_plan = @$this->m_global->getDataAll('m_action_plan a', null, $where, $select);
        // $data['sub_action_plan'] = $sub_action_plan;

        $this->template->display_ajax($this->url.'/v_review_si_table_ic', $data);
    }


    public function load_issue() {
        csrf_init();
        $data['url'] = $this->url;
        $id_si = @$this->input->post('id_si');
        $year = @$this->input->post('year');
        $data['id_si'] = $id_si;
        $data['year'] = $year;

        $where = ['id_si'=>$id_si, 'is_active'=>'t'];
        $arr = @$this->m_global->getDataAll('m_action_plan', null, $where, 'id');
        foreach($arr as $row){ $arr_id[] = $row->id;}
        if(@$arr_id != ''){
            $arr_id = join(',',$arr_id);
            $where = "id_action_plan IN($arr_id) AND year = $year AND is_active = 't' ";
            $select = "a.*, 
                        (SELECT concat('(',b.code,') ', b.name) FROM m_action_plan b WHERE b.id = a.id_action_plan) AS name_action_plan,
                        (SELECT b.\"SINGKATAN_POSISI\" FROM \"ERP_STO_REAL\" b WHERE b.\"POSITION_ID\"::TEXT = a.\"executor\"::TEXT) AS \"name_executor\"
                      ";
            $arr_issue = @$this->m_global->getDataAll('m_issue a', null, $where, $select);
        }else{
            $arr_issue = [];
        }
        $data['arr_issue'] = $arr_issue;

        $this->template->display_ajax($this->url.'/v_review_si_table_issue', $data);
    }

}
