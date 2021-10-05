<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kpi_so extends MX_Controller {
    
    private $prefix         = 'kpi_so';
    private $table_db       = 'm_kpi_so';
    private $title          = 'KPI - SO';
    private $url            = 'app/kpi_so';

    function __construct() {
        parent::__construct();
        $this->middleware('guest', 'forbidden');
    }

    public function index($id='')
    {
        csrf_init();
        $data['title']      = $this->title;
        $data['url']        = $this->url;
        $data['breadcrumb'] = [$this->title => $this->url];

        //cek id so
        if($id != ''){
            $arr = $this->m_global->getDataAll('m_so', null,  ['id'=>$id])[0];
            $data['id_bsc']         = $arr->id_bsc;
            $data['id_perspective'] = $arr->id_perspective;
            $data['id_so']          = $arr->id;
            $data['year']           = substr($arr->start_date,0,4);
        }else{
            $data['id_bsc']         = 1;
            $data['year']           = date('Y');
        }

        //bsc
        $data['bsc'] = $this->m_global->getDataAll('m_bsc', null,  ['is_active'=>'t'], 'id,name', null, "name ASC");
        
        //year
        $data['start_year'] = @$this->m_global->getDataAll('m_periode', null, ['is_active'=>'t'], 'MIN(start_year) AS year')[0]->year;
        $data['end_year'] = @$this->m_global->getDataAll('m_periode', null, ['is_active'=>'t'], 'MAX(end_year) AS year')[0]->year;

        $js['custom']       = ['table_kpi_so'];
        $this->template->display($this->url.'/v_'.$this->prefix, $data, $js);
    }

    public function table_kpi_so()
    {    
        // load model view 
        $this->load->model('app/m_kpi_so','m_kpi_so');

        //search default
        $where  = [];
        $whereE = " is_active = 't' ";

        //cek role PIC SO
        if(h_session('ROLE_ID') == '4'){ 
            $position_id = h_session('POSITION_ID');
            $where2 = " a.is_active = 't' ";
            $where2 .= " AND '".$position_id."'::text = ANY (string_to_array(a.pic_so,', ')::text[]) "; 
            $select2 = "STRING_AGG(a.id::character varying, ',') AS arr_id_so";
            $arr_id_so = $this->m_global->getDataAll('m_so a', null, $where2, $select2)[0]->arr_id_so;
            if($arr_id_so !=  ''){
                $whereE .= " AND a.id_so IN(".$arr_id_so.") "; 
            }
        }
        

        //cek role PIC KPI-SO
        if(h_session('ROLE_ID') == '8'){
            $nip = h_session('NIP');
            $whereE .= " AND '".$nip."'::text = ANY (string_to_array(a.user_pic_kpi_so,', ')::text[]) "; 
        }

        //cek role PIC KPI-SO Manager
        if(h_session('ROLE_ID') == '10'){
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
        if($id_bsc != ''){ $where['a.id_bsc'] = $id_bsc; }
        if($id_perspective != ''){ $where['a.id_perspective'] = $id_perspective; }
        if($id_so != ''){ $where['a.id_so'] = $id_so; }

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
                    $name = $this->m_kpi_so->select($row['name']); $name = str_replace(' AS '.$row['name'], ' ', $name );

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

        $select = [ 'id','name','code','is_active','created_date','created_by','updated_date','updated_by','status_kpi_so',
                    'polarisasi','name_pic_kpi_so','ukuran','frekuensi_pengukuran','name_polarisasi',
                    'name_so','name_perspective','name_bsc','name_status_kpi_so',
                    'start_date','end_date','description'
                ];
        $select = array_unique(array_merge($select, $search));
        $select = $this->m_kpi_so->select($select);

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
            if(in_array( h_session('ROLE_ID'), h_role_admin())){
                if($rows->status_kpi_so == '1'){
                    $btn_edit = '<button title="Edit" id="'.$id.'" class="btn btn-sm btn-primary btn_edit"><i class="fa fa-edit"></i></button>';
                    $btn_approval = '<button title="Approval" id="'.$id.'" val="2" class="btn btn-sm  btn-warning btn_view"><i class="fa fa-envelope-o"></i></button>';
                    $action =  @$btn_approval.@$btn_edit.@$btn_delete;
                }
                if($rows->status_kpi_so == '3'){
                    // $btn_copy   = '<button title="Copy" id="'.$id.'" class="btn btn-sm btn-info btn_copy"><i class="fa fa-copy"></i></button>';
                    $btn_copy   = '<button title="Copy" id="'.$id.'" class="btn btn-sm btn-info"><i class="fa fa-copy"></i></button>';
                    $btn_edit   = '<button title="Edit" id="'.$id.'" class="btn btn-sm btn-primary btn_edit"><i class="fa fa-edit"></i></button>';
                    $btn_kpi_so = '<a href="'.base_url('app/monev_kpi_so/index/').$id.'" title="KPI SO" class="btn btn-sm btn-warning"><i class="fa fa-arrow-right"></i></a>';
                    $action =  @$btn_edit.$btn_copy.@$btn_kpi_so;
                }
                if($rows->status_kpi_so == '4'){
                    $btn_approval = '<button title="Approval" id="'.$id.'" val="2" class="btn btn-sm  btn-warning btn_view"><i class="fa fa-envelope-o"></i></button>';
                    $btn_edit = '<button title="Edit" id="'.$id.'" class="btn btn-sm btn-primary btn_edit"><i class="fa fa-edit"></i></button>';
                    $action =  @$btn_approval.@$btn_edit.@$btn_delete;
                }
            }

            

            //isi table disini
            $isi['no']                  = $i;
            $isi['id']                  = $rows->id;
            $isi['code']                = $rows->code;
            $isi['is_active']           = ($rows->is_active == 't' ? 'Active' : 'Non Active');
            $isi['created_date']        = h_format_date($rows->created_date,'d F Y');
            $isi['created_by']          = $rows->created_by;
            $isi['updated_date']        = h_format_date($rows->updated_date,'d F Y');
            $isi['updated_by']          = $rows->updated_by;
            $isi['name_status_kpi_so']  = $rows->name_status_kpi_so;

            $isi['start_date']          = $rows->start_date;
            $isi['end_date']            = $rows->end_date;
            $isi['name']                = h_read_more($rows->name,40);
            $isi['name_polarisasi']     = $rows->name_polarisasi;
            $isi['pic_kpi_so']          = h_read_more($rows->name_pic_kpi_so,10);
            $isi['ukuran']              = $rows->ukuran;
            $isi['frekuensi_pengukuran']= $rows->frekuensi_pengukuran;

            $isi['id_so']               = $rows->name_so;
            $isi['id_perspective']      = $rows->name_perspective;
            $isi['id_bsc']              = $rows->name_bsc;
            $isi['description']         = h_read_more($rows->description,20);

            $isi['action']              = '<div style="width:125px;'.($iTotalRecords=='1'?'height:50px;':'').'">'.$action.'</div>';


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

    public function load_add() {
        csrf_init();
        $data['url'] = $this->url;

        //perspective
        $data['perspective'] = $this->m_global->getDataAll('m_perspective', null, ['is_active'=>'t'], '*', null, "name ASC");

        //bsc
        $data['bsc'] = $this->m_global->getDataAll('m_bsc', null,  ['is_active'=>'t'], '*', null, "name ASC");
        
        //polarisasi
        $data['polarisasi'] = $this->m_global->getDataAll('m_status', null, ['is_active'=>'t', 'type'=>'Polarisasi'], '*', null, '"order" ASC');

        //pic
        // $where = " '4' = ANY (string_to_array(role_id,',')) ";
        // $data['pic_kpi_so'] = $this->m_global->getDataAll('sys_user', null, ['is_active'=>'t'], '*', $where, "fullname ASC");

        $this->template->display_ajax($this->url.'/v_kpi_so_add', $data);
    }

    public function load_edit() {
        csrf_init();
        $data['url'] = $this->url;
        $id = @$this->input->post('id');
        $data['id'] = $id;

        //cek tipe view
        $type = @$this->input->post('type');
        $data['type'] = $type;
        $data['readonly'] = ($type == 'view' ? 'readonly="readonly"' : '');
        $data['disabled'] = ($type == 'view' ? 'disabled="disabled"' : '');

        //perspective
        $data['perspective'] = $this->m_global->getDataAll('m_perspective', null, ['is_active'=>'t'], '*', null, "name ASC");
        //bsc
        $data['bsc'] = $this->m_global->getDataAll('m_bsc', null,  ['is_active'=>'t'], '*', null, "name ASC");

        //polarisasi
        $data['arr_polarisasi'] = $this->m_global->getDataAll('m_status', null, ['is_active'=>'t', 'type'=>'Polarisasi'], '*', null, '"order" ASC');
        
        //get data
        $data['data'] = @$this->m_global->getDataAll('m_kpi_so', null, ['id'=>$id], '*')[0];

        //file so
        $data['html_list_file_kpi_so'] = $this->list_file_kpi_so(TRUE,$id);

        //start_year
        $data['polarisasi'] = $data['data']->polarisasi;

        $this->template->display_ajax($this->url.'/v_kpi_so_edit', $data);
    }

    public function load_copy() {
        csrf_init();
        $data['url'] = $this->url;
        $id = @$this->input->post('id');
        $data['id'] = $id;

        //perspective
        $data['perspective'] = $this->m_global->getDataAll('m_perspective', null, ['is_active'=>'t'], '*', null, "name ASC");
        //bsc
        $data['bsc'] = $this->m_global->getDataAll('m_bsc', null,  ['is_active'=>'t'], '*', null, "name ASC");

        //polarisasi
        $data['arr_polarisasi'] = $this->m_global->getDataAll('m_status', null, ['is_active'=>'t', 'type'=>'Polarisasi'], '*', null, '"order" ASC');

        //pic
        $where = " '4' = ANY (string_to_array(role_id,',')) ";
        $data['pic_kpi_so'] = $this->m_global->getDataAll('sys_user', null, ['is_active'=>'t'], '*', $where, "fullname ASC");
        
        //get data
        $data['data'] = @$this->m_global->getDataAll('m_kpi_so', null, ['id'=>$id], '*')[0];

        //start_year
        $data['polarisasi'] = $data['data']->polarisasi;
        $data['start_year'] = substr($data['data']->start_date,0,4);
        $data['end_year'] = substr($data['data']->end_date,0,4);

        $this->template->display_ajax($this->url.'/v_kpi_so_copy', $data);
    }

    public function load_table_target_kpi_so() {
        csrf_init();
        $data['url'] = $this->url;

        $id = @$this->input->post('id');
        $type = @$this->input->post('type');
        $polarisasi = @$this->input->post('polarisasi');
        $start_year = substr(@$this->input->post('start_date'),0,4) ;
        $end_year = substr(@$this->input->post('end_date'),0,4) ;
        $data['start_year'] = $start_year;
        $data['end_year'] = $end_year;
        $data['polarisasi'] = $polarisasi;
        $data['readonly'] = ($type == 'view' ? 'readonly="readonly"' : '');

        //cek id nya
        if($id != ''){
            //target year
            $select = "year,target,target_from,target_to";
            $where = " id_kpi_so='".$id."' AND  year >= ".$start_year." AND year <= ".$end_year;
            $order = ['year'=>'ASC'];
            $arr_year  = @$this->m_global->getDataAll('m_kpi_so_target_year', null, $where, $select,null,$order);
            $target = $target_from = $target_to = [];
            if($arr_year != ''){
                foreach($arr_year as $row){
                    $target[$row->year] = $row->target;
                    $target_from[$row->year] = $row->target_from;
                    $target_to[$row->year] = $row->target_to;
                }
            }
            $data['target'] = $target;
            $data['target_from'] = $target_from;
            $data['target_to'] = $target_to;
            // echo '<pre>';print_r($data['target']);exit;


            //target month
            $select = "year,month,target,target_from,target_to";
            $where = " id_kpi_so='".$id."' AND  year >= ".$start_year." AND year <= ".$end_year;
            $order = ['year'=>'ASC', 'month'=>'ASC'];
            $arr_month = @$this->m_global->getDataAll('m_kpi_so_target_month', null, $where, $select, null, $order);
            $target_month = $target_month_from = $target_month_to = [];
            if($arr_month != ''){
                foreach($arr_month as $row2){
                    $target_month[$row2->year][$row2->month] = $row2->target;
                    $target_month_from[$row2->year][$row2->month] = $row2->target_from;
                    $target_month_to[$row2->year][$row2->month] = $row2->target_to;
                }
            }
            $data['target_month'] = $target_month;
            $data['target_month_from'] = $target_month_from;
            $data['target_month_to'] = $target_month_to;
            // echo '<pre>';print_r($data['target_month']);exit;
        }

        $this->template->display_ajax($this->url.'/v_kpi_so_table_target', $data);
    }

    public function save_add() {

        //cek csrf token
        // $ex_csrf_token = @$this->input->post('ex_csrf_token');
        // $res = [];
        // if (csrf_get_token() != $ex_csrf_token){
        //     $res['status']  = 2;
        //     $res['message'] = $this->csrf_message;
        //     echo json_encode($res);
        // }else{

            // Set Rule Login Form
            $this->form_validation->set_rules('name', 'KPI-SO Title', 'trim|xss_clean|required');
            $this->form_validation->set_rules('code', 'Number/Prefix', 'trim|xss_clean|required');
            $this->form_validation->set_rules('ukuran', 'Ukuran', 'required');
            $this->form_validation->set_rules('polarisasi', 'Polarisasi', 'required');
            $this->form_validation->set_rules('frekuensi_pengukuran', 'Frekuensi Pengukuran', 'required');
            $this->form_validation->set_rules('pic_kpi_so', 'PIC KPI-SO', 'trim|xss_clean|required');
            $this->form_validation->set_rules('user_pic_manager', 'Assigned KPI-SO Manager', 'trim|xss_clean|required');
            $this->form_validation->set_rules('start_date', 'Start Date', 'trim|xss_clean|required');
            $this->form_validation->set_rules('end_date', 'End Date', 'trim|xss_clean|required');

            $this->form_validation->set_rules('id_bsc', 'BSC', 'trim|xss_clean|required');
            $this->form_validation->set_rules('id_perspective', 'Perspective', 'trim|xss_clean|required');
            $this->form_validation->set_rules('id_so', 'SO', 'trim|xss_clean|required');
            if ($this->form_validation->run($this)) {

                //insert data
                $polarisasi = @$this->input->post('polarisasi');
                $start_date = @$this->input->post('start_date');
                $end_date   = @$this->input->post('end_date');
                
                $data['name']           = @$this->input->post('name');
                $data['code']           = @$this->input->post('code');
                $data['polarisasi']     = $polarisasi;
                $data['pic_kpi_so']     = str_replace(',',', ',@$this->input->post('pic_kpi_so'));
                $data['user_pic_kpi_so']   = str_replace(',',', ',@$this->input->post('user_pic_kpi_so'));
                $data['user_pic_manager']  = str_replace(',',', ',@$this->input->post('user_pic_manager'));
                $data['ukuran']         = @$this->input->post('ukuran');
                $data['frekuensi_pengukuran'] = @$this->input->post('frekuensi_pengukuran');
                $data['start_date']     = $start_date.'-01';
                $data['end_date']       = $end_date.'-01';

                $data['id_bsc']         = @$this->input->post('id_bsc');
                $data['id_perspective'] = @$this->input->post('id_perspective');
                $data['id_periode']     = @$this->input->post('id_periode');
                $data['id_so']          = @$this->input->post('id_so');
                $data['status_kpi_so']  = 1;
                
                $data['description']    = @$this->input->post('description');
                $data['created_date']   = date("Y-m-d H:i:s");
                $data['created_by']     = h_session('USERNAME');
                $result = $this->m_global->insert('m_kpi_so', $data);

                //=================================================================================
                //insert target year dan target TW
                $start_year  = substr($start_date,0,4);
                $end_year  = substr($end_date,0,4);
                $id_kpi_so = $this->db->insert_id();
                if($id_kpi_so != ''){
                    
                    for($y=$start_year;$y<=$end_year;$y++){
                        $data2['id_kpi_so'] = $id_kpi_so;
                        $data2['year']      = $y;
                        $data2['status']    = 1;
                        //polarisasi stabilze
                        if($polarisasi == '10'){
                            $target_from = str_replace(',','',@$this->input->post('target_from_'.$y));
                            $target_to   = str_replace(',','',@$this->input->post('target_to_'.$y));
                            $data2['target_from'] = ($target_from == '' ? NULL : $target_from);
                            $data2['target_to']   = ($target_to == '' ? NULL : $target_to);
                            $data2['target']      = NULL;
                        }else{
                            $target = str_replace(',','',@$this->input->post('target_'.$y));
                            $data2['target_from'] = NULL;
                            $data2['target_to']   = NULL;
                            $data2['target']      = ($target == '' ? NULL : $target);
                        }
                        $result = $this->m_global->insert('m_kpi_so_target_year', $data2);

                        for($m=1;$m<=12;$m++){
                            if(in_array($m,array('3','6','9','12'))){
                                $data3['id_kpi_so'] = $id_kpi_so;
                                $data3['year']      = $y;
                                $data3['month']     = $m;
                                //polarisasi stabilze
                                if($polarisasi == '10'){
                                    $target_month_from = str_replace(',','',@$this->input->post('target_month_from_'.$y.'_'.$m));
                                    $target_month_to   = str_replace(',','',@$this->input->post('target_month_to_'.$y.'_'.$m));
                                    $data3['target_from'] = ($target_month_from == '' ? NULL : $target_month_from);
                                    $data3['target_to'] = ($target_month_to == '' ? NULL : $target_month_to);
                                    $data3['target'] = NULL;
                                }else{
                                    $target_month = str_replace(',','',@$this->input->post('target_month_'.$y.'_'.$m));
                                    $data3['target_from'] = NULL;
                                    $data3['target_to'] = NULL;
                                    $data3['target'] = ($target_month == '' ? NULL : $target_month);
                                }
                                $result = $this->m_global->insert('m_kpi_so_target_month', $data3);
                            }
                        }
                    }
                }

                //=================================================================================

                //use pic kpi-so dan manager
                $arr_user_pic_kpi_so  = explode(',',@$this->input->post('user_pic_kpi_so'));
                $arr_user_pic_manager = explode(',',@$this->input->post('user_pic_manager'));
                $arr_join = array_merge($arr_user_pic_kpi_so,$arr_user_pic_manager);
                foreach($arr_join as $nip){
                    //cek role
                    if(in_array($nip, $arr_user_pic_kpi_so)){ $role_id_pic = '8'; }else{ $role_id_pic = '10'; }
                    //mengambil data id_kpi_so lama
                    $id_kpi_so_old = @$this->m_global->getDataAll('m_user_kpi_so', null, ['nip' => $nip], 'id_kpi_so')[0]->id_kpi_so;
                    if($id_kpi_so_old == ''){
                        //insert data
                        $data = [];
                        $data['nip']            = $nip;
                        $data['role_id']        = $role_id_pic;
                        $data['id_kpi_so']      = $id_kpi_so;
                        $data['created_date']   = date("Y-m-d H:i:s");
                        $data['created_by']     = h_session('USERNAME');
                        $result = $this->m_global->insert('m_user_kpi_so', $data);
                    }else{
                        //update data user ic
                        $data = [];
                        $data['role_id']        = $role_id_pic;
                        $data['id_kpi_so']      = $id_kpi_so_old.', '.$id_kpi_so;
                        $data['updated_date']   = date("Y-m-d H:i:s");
                        $data['updated_by']     = h_session('USERNAME');
                        $result = $this->m_global->update('m_user_kpi_so', $data, ['nip' => $nip]);
                    }
                }
                
                //=================================================================================

                //membuat user login, role pic si
                $arr_user_pic_kpi_so = explode(',',@$this->input->post('user_pic_kpi_so'));
                $arr_user_pic_manager = explode(',',@$this->input->post('user_pic_manager'));
                $arr_join = array_merge($arr_user_pic_kpi_so,$arr_user_pic_manager);
                foreach($arr_join as $nip){
                    //cek role
                    if(in_array($nip, $arr_user_pic_kpi_so)){ $role_id_pic = '8'; }else{ $role_id_pic = '10'; }
                    //cek nip di table user
                    $arr_user = @$this->m_global->getDataAll('sys_user a', null, ['a.nip' => $nip],'id, role_id')[0];
                    $user_id = @$arr_user->id;
                    if($user_id == ''){
                        //insert user
                        $select = " \"NAMA\", \"NAMA\", \"CHILD_EMAIL\", \"NAME\", \"POSITION_ID\"";
                        $select .= ", (SELECT b.\"SINGKATAN_POSISI\" FROM \"ERP_STO_REAL\" b WHERE b.\"POSITION_ID\"= a.\"POSITION_ID\" LIMIT 1) AS \"SINGKATAN_POSISI\"";
                        $arr = @$this->m_global->getDataAll('DIRJAB_STO a', null, ['a.NIP' => $nip], $select)[0];
                        $data = [];
                        $data['username']   = @$nip;
                        $data['password']   = md5_mod('123');
                        $data['nip']        = @$nip;
                        $data['fullname']   = @$arr->NAMA;
                        $data['email']      = @$arr->CHILD_EMAIL;
                        $data['title']      = @$arr->NAME;
                        $data['singkatan_jabatan']  = @$arr->SINGKATAN_POSISI;
                        $data['position_id']  = @$arr->POSITION_ID;
                        $data['role_id']    = $role_id_pic;
                        $this->m_global->insert('sys_user', $data);
                    }else{
                        //update user
                        $data = [];
                        $user_role_id = @$arr_user->role_id;
                        if($user_role_id == ''){
                            $data['role_id'] = $role_id_pic;
                        }else{
                            $arr_user_role_id = explode(', ',$user_role_id);
                            if(!in_array($role_id_pic, $arr_user_role_id)){
                                $arr_user_role_id[] = $role_id_pic;
                            }
                            $data['role_id'] = join(', ', $arr_user_role_id);
                        }
                        $this->m_global->update('sys_user', $data, ['id' => $user_id]);
                    }
                }
                //==================================================================================


                // echo '<pre>';print_r($data);exit;
                // echo $this->db->last_query();exit;

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


    public function save_edit() {

        //cek csrf token
        // $ex_csrf_token = @$this->input->post('ex_csrf_token');
        // $res = [];
        // if (csrf_get_token() != $ex_csrf_token){
        //     $res['status']  = 2;
        //     $res['message'] = $this->csrf_message;
        //     echo json_encode($res);
        // }else{

            // Set Rule Login Form
             // Set Rule Login Form
             $this->form_validation->set_rules('name', 'KPI-SO Title', 'trim|xss_clean|required');
             $this->form_validation->set_rules('code', 'Number/Prefix', 'trim|xss_clean|required');
             $this->form_validation->set_rules('ukuran', 'Ukuran', 'required');
             $this->form_validation->set_rules('polarisasi', 'Polarisasi', 'required');
             $this->form_validation->set_rules('frekuensi_pengukuran', 'Frekuensi Pengukuran', 'required');
             $this->form_validation->set_rules('pic_kpi_so', 'PIC KPI-SO', 'trim|xss_clean|required');
             $this->form_validation->set_rules('user_pic_manager', 'Assigned KPI-SO Manager', 'trim|xss_clean|required');
             $this->form_validation->set_rules('start_date', 'Start Date', 'trim|xss_clean|required');
             $this->form_validation->set_rules('end_date', 'End Date', 'trim|xss_clean|required');
 
             $this->form_validation->set_rules('id_bsc', 'BSC', 'trim|xss_clean|required');
             $this->form_validation->set_rules('id_perspective', 'Perspective', 'trim|xss_clean|required');
             $this->form_validation->set_rules('id_so', 'SO', 'trim|xss_clean|required');
            // $this->form_validation->set_rules('description', 'Description', 'trim|xss_clean|required');
            if ($this->form_validation->run($this)) {

                // echo '<pre>';print_r(@$this->input->post());exit;

                //update data
                $id = $this->input->post('id');
                $id_kpi_so = $id;

                $polarisasi = @$this->input->post('polarisasi');
                $start_date = @$this->input->post('start_date');
                $end_date   = @$this->input->post('end_date');
                $start_date_old = @$this->input->post('start_date_old');
                $end_date_old   = @$this->input->post('end_date_old');

                $data['name']           = @$this->input->post('name');
                $data['code']           = @$this->input->post('code');
                $data['polarisasi']     = $polarisasi;
                $data['pic_kpi_so']     = str_replace(',',', ',@$this->input->post('pic_kpi_so'));
                $data['user_pic_kpi_so']   = str_replace(',',', ',@$this->input->post('user_pic_kpi_so'));
                $data['user_pic_manager']  = str_replace(',',', ',@$this->input->post('user_pic_manager'));
                $data['ukuran']         = @$this->input->post('ukuran');
                $data['frekuensi_pengukuran'] = @$this->input->post('frekuensi_pengukuran');
                $data['start_date']     = $start_date.'-01';
                $data['end_date']       = $end_date.'-01';

                $data['id_bsc']         = @$this->input->post('id_bsc');
                $data['id_perspective'] = @$this->input->post('id_perspective');
                $data['id_so']          = @$this->input->post('id_so');

                $data['description']    = @$this->input->post('description');
                $data['updated_date']   = date("Y-m-d H:i:s");
                $data['updated_by']     = h_session('USERNAME');
                $result = $this->m_global->update('m_kpi_so', $data, ['id' => $id]);


                //============================================================================================

                //cek tahun yang dirubah
                $start_year         = substr($start_date,0,4);
                $end_year           = substr($end_date,0,4);
                $start_year_old     = substr($start_date_old,0,4);
                $end_year_old       = substr($end_date_old,0,4);

                //cek periode tahun lama dan baru
                $arr_year_old = [];
                for($y=$start_year_old;$y<=$end_year_old;$y++){
                    $arr_year_old[] = $y;
                }
                $arr_year_new = [];
                for($y=$start_year;$y<=$end_year;$y++){
                    $arr_year_new[] = $y;
                }

                //cek tahun yang di insert
                $year_insert = [];
                foreach($arr_year_new as $val){
                    if(!in_array($val,$arr_year_old)){
                        $year_insert[] = $val;
                    }
                }
                
                //cek tahun yang di update dan delete
                $year_update = [];
                $year_delete = [];
                foreach($arr_year_old as $val){
                    if(in_array($val,$arr_year_new)){
                        $year_update[] = $val;
                    }else{
                        $year_delete[] = $val;
                    }
                }

                //delete target year dan month
                if(count($year_delete) > 0){
                    foreach($year_delete as $y){
                        $where = $data = [];
                        $where['id_kpi_so'] = $id;
                        $where['year'] = $y;
                        $data['is_active'] = 'f';
                        $result = $this->m_global->update('m_kpi_so_target_year', $data, $where);
                        $result = $this->m_global->update('m_kpi_so_target_month', $data, $where);

                    }
                }
                
                //update target year dan month
                if(count($year_update) > 0){
                    foreach($year_update as $y){
                        $data2 = $data_year = [];
                        $data2['id_kpi_so'] = $id_kpi_so;
                        $data2['year']      = $y;
                        //polarisasi stabilze
                        if($polarisasi == '10'){
                            $target_from = str_replace(',','',@$this->input->post('target_from_'.$y));
                            $target_to = str_replace(',','',@$this->input->post('target_to_'.$y));
                            $data_year['target_from'] = ($target_from == '' ? NULL : $target_from);
                            $data_year['target_to']   = ($target_to == '' ? NULL : $target_to);
                            $data_year['target']      = NULL;
                        }else{
                            $target = str_replace(',','',@$this->input->post('target_'.$y));
                            $data_year['target_from'] = NULL;
                            $data_year['target_to']   = NULL;
                            $data_year['target']      = ($target == '' ? NULL : $target);
                        }
                        $result = $this->m_global->update('m_kpi_so_target_year', $data_year, $data2);
                        // echo $this->db->last_query();'<br>';

                        for($m=1;$m<=12;$m++){
                            if(in_array($m,array('3','6','9','12'))){
                                $data3 = $data_month = [];
                                $data3['id_kpi_so'] = $id_kpi_so;
                                $data3['year']      = $y;
                                $data3['month']     = $m;
                                //polarisasi stabilze
                                if($polarisasi == '10'){
                                    $target_month_from = str_replace(',','',@$this->input->post('target_month_from_'.$y.'_'.$m));
                                    $target_month_to = str_replace(',','',@$this->input->post('target_month_to_'.$y.'_'.$m));
                                    $data_month['target_from'] = ($target_month_from == '' ? NULL : $target_month_from);
                                    $data_month['target_to'] = ($target_month_to == '' ? NULL : $target_month_to);
                                    $data_month['target'] = NULL;
                                }else{
                                    $target_month = str_replace(',','',@$this->input->post('target_month_'.$y.'_'.$m));
                                    $data_month['target_from'] = NULL;
                                    $data_month['target_to'] = NULL;
                                    $data_month['target'] = ($target_month == '' ? NULL : $target_month);
                                }
                                $result = $this->m_global->update('m_kpi_so_target_month', $data_month, $data3);
                                // echo $this->db->last_query();'<br>';
                            }
                        }
                    }
                }

                //insert target year dan month
                if(count($year_insert) > 0){
                    foreach($year_insert as $y){
                        $data2['id_kpi_so'] = $id;
                        $data2['year']      = $y;
                        $data2['status']    = 1;
                        //polarisasi stabilze
                        if($polarisasi == '10'){
                            $target_from = str_replace(',','',@$this->input->post('target_from_'.$y));
                            $target_to   = str_replace(',','',@$this->input->post('target_to_'.$y));
                            $data2['target_from'] = ($target_from == '' ? NULL : $target_from);
                            $data2['target_to']   = ($target_to == '' ? NULL : $target_to);
                            $data2['target']      = NULL;
                        }else{
                            $target = str_replace(',','',@$this->input->post('target_'.$y));
                            $data2['target_from'] = NULL;
                            $data2['target_to']   = NULL;
                            $data2['target']      = ($target == '' ? NULL : $target);
                        }
                        //cek insert / update
                        $where = [];
                        $where['id_kpi_so'] = $id;
                        $where['year'] = $y;
                        $cek = @$this->m_global->getDataAll('m_kpi_so_target_year', NULL,$where,"id")[0]->id;
                        if($cek == ''){
                            $result = $this->m_global->insert('m_kpi_so_target_year', $data2);
                        }else{
                            $data2['is_active']   = 't';
                            $result = $this->m_global->update('m_kpi_so_target_year', $data2, $where);
                        }


                        for($m=1;$m<=12;$m++){

                            if(in_array($m,array('3','6','9','12'))){
                                $data3['id_kpi_so'] = $id;
                                $data3['year']      = $y;
                                $data3['month']     = $m;
                                //polarisasi stabilze
                                if($polarisasi == '10'){
                                    $target_month_from = str_replace(',','',@$this->input->post('target_month_from_'.$y.'_'.$m));
                                    $target_month_to   = str_replace(',','',@$this->input->post('target_month_to_'.$y.'_'.$m));
                                    $data3['target_from'] = ($target_month_from == '' ? NULL : $target_month_from);
                                    $data3['target_to'] = ($target_month_to == '' ? NULL : $target_month_to);
                                    $data3['target'] = NULL;
                                }else{
                                    $target_month = str_replace(',','',@$this->input->post('target_month_'.$y.'_'.$m));
                                    $data3['target_from'] = NULL;
                                    $data3['target_to'] = NULL;
                                    $data3['target'] = ($target_month == '' ? NULL : $target_month);
                                }

                                //cek insert / update
                                $where = [];
                                $where['id_kpi_so'] = $id;
                                $where['year']      = $y;
                                $where['month']     = $m;
                                $cek = @$this->m_global->getDataAll('m_kpi_so_target_month', NULL,$where,"id")[0]->id;
                                if($cek == ''){
                                    $result = $this->m_global->insert('m_kpi_so_target_month', $data3);
                                }else{
                                    $data3['is_active'] = 't';
                                    $result = $this->m_global->update('m_kpi_so_target_month', $data3, $where);
                                }
                            }
                        }
                    }
                }


                //============================================================================================

                //delete user pic si dan ic yang lama
                $arr_user_pic_kpi_so_old = explode(',',@$this->input->post('user_pic_kpi_so_old'));
                $arr_user_pic_manager_old = explode(',',@$this->input->post('user_pic_manager_old'));
                $arr_join = array_merge($arr_user_pic_kpi_so_old,$arr_user_pic_manager_old);
                foreach($arr_join as $nip){
                    if($nip == ''){ continue; }
                    //cek role
                    if(in_array($nip, $arr_user_pic_kpi_so_old)){ $role_id_pic = '8'; }else{ $role_id_pic = '10'; }
                    //mengambil data id_kpi_so lama
                    $id_kpi_so_old = @$this->m_global->getDataAll('m_user_kpi_so', null, ['nip' => $nip], 'id_kpi_so')[0]->id_kpi_so;
                    $arr_id_kpi_so_old = explode(', ', $id_kpi_so_old);
                    if (($key = array_search($id_kpi_so, $arr_id_kpi_so_old)) !== false) {
                        unset($arr_id_kpi_so_old[$key]);
                    }
                    //cek array nya kosong ata
                    if(@$arr_id_kpi_so_old[0] == '' ){
                        //delete nip
                        $result = $this->m_global->delete('m_user_kpi_so', ['nip' => $nip]);
                    }else{
                        //update data user ic
                        $data = [];
                        $data['role_id']        = $role_id_pic;
                        $data['id_kpi_so']      = $id_kpi_so_old.', '.$id_kpi_so;
                        $data['updated_date']   = date("Y-m-d H:i:s");
                        $data['updated_by']     = h_session('USERNAME');
                        $result = $this->m_global->update('m_user_kpi_so', $data, ['nip' => $nip]);
                    }
                }

                //user pic si dan ic
                $arr_user_pic_kpi_so = explode(',',@$this->input->post('user_pic_kpi_so'));
                $arr_user_pic_manager = explode(',',@$this->input->post('user_pic_manager'));
                $arr_join = array_merge($arr_user_pic_kpi_so,$arr_user_pic_manager);
                foreach($arr_join as $nip){
                    if($nip == ''){ continue; }
                    //cek role
                    if(in_array($nip, $arr_user_pic_kpi_so)){ $role_id_pic = '8'; }else{ $role_id_pic = '10'; }
                    //mengambil data id_kpi_so lama
                    $id_kpi_so_old = @$this->m_global->getDataAll('m_user_kpi_so', null, ['nip' => $nip], 'id_kpi_so')[0]->id_kpi_so;
                    if($id_kpi_so_old == ''){
                        //insert data
                        $data = [];
                        $data['nip']            = $nip;
                        $data['role_id']        = $role_id_pic;
                        $data['id_kpi_so']      = $id_kpi_so;
                        $data['created_date']   = date("Y-m-d H:i:s");
                        $data['created_by']     = h_session('USERNAME');
                        $result = $this->m_global->insert('m_user_kpi_so', $data);
                    }else{
                        //update data user ic
                        $data = [];
                        $data['role_id']        = $role_id_pic;
                        $data['id_kpi_so']      = $id_kpi_so_old.', '.$id_kpi_so;
                        $data['updated_date']   = date("Y-m-d H:i:s");
                        $data['updated_by']     = h_session('USERNAME');
                        $result = $this->m_global->update('m_user_kpi_so', $data, ['nip' => $nip]);
                    }
                }


                //===============================================================================

                //delete role_id login, untuk role pic si dan pic ic
                $arr_user_pic_kpi_so = explode(',',@$this->input->post('user_pic_kpi_so_old'));
                $arr_user_pic_manager = explode(',',@$this->input->post('user_pic_manager_old'));
                $arr_join = array_merge($arr_user_pic_kpi_so,$arr_user_pic_manager);
                foreach($arr_join as $nip){
                    if($nip == ''){ continue; }
                    //cek role
                    if(in_array($nip, $arr_user_pic_kpi_so)){ $role_id_pic = '8'; }else{ $role_id_pic = '10'; }
                    //cek nip di table user
                    $arr_user = @$this->m_global->getDataAll('sys_user a', null, ['a.nip' => $nip],'id, role_id')[0];
                    $user_id = @$arr_user->id;
                    $user_role_id = @$arr_user->role_id;
                    //update user
                    $arr_user_role_id = explode(', ',$user_role_id);
                    if (($key = array_search($role_id_pic, $arr_user_role_id)) !== false) {
                        unset($arr_user_role_id[$key]);
                    }
                    if(@$arr_user_role_id[0] == ''){ 
                        $role_id_new = null;
                    }else{ 
                        $role_id_new = join(', ', $arr_user_role_id); 
                    }
                    $data = [];
                    $data['role_id'] = $role_id_new;
                    $this->m_global->update('sys_user', $data, ['id' => $user_id]);
                }


                //membuat user login, role pic si
                $arr_user_pic_kpi_so = explode(',',@$this->input->post('user_pic_kpi_so'));
                $arr_user_pic_manager = explode(',',@$this->input->post('user_pic_manager'));
                $arr_join = array_merge($arr_user_pic_kpi_so,$arr_user_pic_manager);
                foreach($arr_join as $nip){
                    if($nip == ''){ continue; }
                    //cek role
                    if(in_array($nip, $arr_user_pic_kpi_so)){ $role_id_pic = '8'; }else{ $role_id_pic = '10'; }
                    //cek nip di table user
                    $arr_user = @$this->m_global->getDataAll('sys_user a', null, ['a.nip' => $nip],'id, role_id')[0];
                    $user_id = @$arr_user->id;
                    if($user_id == ''){
                        //insert user
                        $select = " \"NAMA\", \"NAMA\", \"CHILD_EMAIL\", \"NAME\", \"POSITION_ID\"";
                        $select .= ", (SELECT b.\"SINGKATAN_POSISI\" FROM \"ERP_STO_REAL\" b WHERE b.\"POSITION_ID\"= a.\"POSITION_ID\" LIMIT 1) AS \"SINGKATAN_POSISI\"";
                        $arr = @$this->m_global->getDataAll('DIRJAB_STO a', null, ['a.NIP' => $nip], $select)[0];
                        $data = [];
                        $data['username']   = @$nip;
                        $data['password']   = md5_mod('123');
                        $data['nip']        = @$nip;
                        $data['fullname']   = @$arr->NAMA;
                        $data['email']      = @$arr->CHILD_EMAIL;
                        $data['title']      = @$arr->NAME;
                        $data['singkatan_jabatan']  = @$arr->SINGKATAN_POSISI;
                        $data['position_id']  = @$arr->POSITION_ID;
                        $data['role_id']    = $role_id_pic;
                        $this->m_global->insert('sys_user', $data);
                    }else{
                        //update user
                        $data = [];
                        $user_role_id = @$arr_user->role_id;
                        if($user_role_id == ''){
                            $data['role_id'] = $role_id_pic;
                        }else{
                            $arr_user_role_id = explode(', ',$user_role_id);
                            if(!in_array($role_id_pic, $arr_user_role_id)){
                                $arr_user_role_id[] = $role_id_pic;
                            }
                            $data['role_id'] = join(', ', $arr_user_role_id);
                        }
                        $this->m_global->update('sys_user', $data, ['id' => $user_id]);
                    }
                }

                //===============================================================================

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


    public function save_copy() {

        //cek csrf token
        // $ex_csrf_token = @$this->input->post('ex_csrf_token');
        // $res = [];
        // if (csrf_get_token() != $ex_csrf_token){
        //     $res['status']  = 2;
        //     $res['message'] = $this->csrf_message;
        //     echo json_encode($res);
        // }else{

            // Set Rule Login Form
            $this->form_validation->set_rules('name', 'KPI-SO Title', 'trim|xss_clean|required');
            $this->form_validation->set_rules('code', 'Number/Prefix', 'trim|xss_clean|required');
            $this->form_validation->set_rules('ukuran', 'Ukuran', 'required');
            $this->form_validation->set_rules('polarisasi', 'Polarisasi', 'required');
            $this->form_validation->set_rules('frekuensi_pengukuran', 'Frekuensi Pengukuran', 'required');
            $this->form_validation->set_rules('pic_kpi_so', 'PIC KPI-SO', 'trim|xss_clean|required');
            $this->form_validation->set_rules('start_date', 'Start Date', 'trim|xss_clean|required');
            $this->form_validation->set_rules('end_date', 'End Date', 'trim|xss_clean|required');

            $this->form_validation->set_rules('id_bsc', 'BSC', 'trim|xss_clean|required');
            $this->form_validation->set_rules('id_perspective', 'Perspective', 'trim|xss_clean|required');
            $this->form_validation->set_rules('id_so', 'SO', 'trim|xss_clean|required');
            if ($this->form_validation->run($this)) {

                //insert data
                $polarisasi = @$this->input->post('polarisasi');
                $start_date = @$this->input->post('start_date');
                $end_date   = @$this->input->post('end_date');
                
                $data['name']           = @$this->input->post('name');
                $data['code']           = @$this->input->post('code');
                $data['polarisasi']     = $polarisasi;
                $data['pic_kpi_so']     = str_replace(',',', ',@$this->input->post('pic_kpi_so'));
                $data['ukuran']         = @$this->input->post('ukuran');
                $data['frekuensi_pengukuran'] = @$this->input->post('frekuensi_pengukuran');
                $data['start_date']     = $start_date.'-01';
                $data['end_date']       = $end_date.'-01';

                $data['id_bsc']         = @$this->input->post('id_bsc');
                $data['id_perspective'] = @$this->input->post('id_perspective');
                $data['id_periode']     = @$this->input->post('id_periode');
                $data['id_so']          = @$this->input->post('id_so');
                
                $data['description']    = @$this->input->post('description');
                $data['created_date']   = date("Y-m-d H:i:s");
                $data['created_by']     = h_session('USERNAME');
                $result = $this->m_global->insert('m_kpi_so', $data);

                
                //insert target year dan target TW
                $start_year  = substr($start_date,0,4);
                $end_year  = substr($end_date,0,4);
                $id_kpi_so = $this->db->insert_id();
                if($id_kpi_so != ''){
                    
                    for($y=$start_year;$y<=$end_year;$y++){
                        $data2['id_kpi_so'] = $id_kpi_so;
                        $data2['year']      = $y;
                        $data2['status']    = 1;
                        //polarisasi stabilze
                        if($polarisasi == '10'){
                            $target_from = str_replace(',','',@$this->input->post('target_from_'.$y));
                            $target_to   = str_replace(',','',@$this->input->post('target_to_'.$y));
                            $data2['target_from'] = ($target_from == '' ? NULL : $target_from);
                            $data2['target_to']   = ($target_to == '' ? NULL : $target_to);
                            $data2['target']      = NULL;
                        }else{
                            $target = str_replace(',','',@$this->input->post('target_'.$y));
                            $data2['target_from'] = NULL;
                            $data2['target_to']   = NULL;
                            $data2['target']      = ($target == '' ? NULL : $target);
                        }
                        $result = $this->m_global->insert('m_kpi_so_target_year', $data2);

                        for($m=1;$m<=12;$m++){
                            if(in_array($m,array('3','6','9','12'))){
                                $data3['id_kpi_so'] = $id_kpi_so;
                                $data3['year']      = $y;
                                $data3['month']     = $m;
                                //polarisasi stabilze
                                if($polarisasi == '10'){
                                    $target_month_from = str_replace(',','',@$this->input->post('target_month_from_'.$y.'_'.$m));
                                    $target_month_to   = str_replace(',','',@$this->input->post('target_month_to_'.$y.'_'.$m));
                                    $data3['target_from'] = ($target_month_from == '' ? NULL : $target_month_from);
                                    $data3['target_to'] = ($target_month_to == '' ? NULL : $target_month_to);
                                    $data3['target'] = NULL;
                                }else{
                                    $target_month = str_replace(',','',@$this->input->post('target_month_'.$y.'_'.$m));
                                    $data3['target_from'] = NULL;
                                    $data3['target_to'] = NULL;
                                    $data3['target'] = ($target_month == '' ? NULL : $target_month);
                                }
                                $result = $this->m_global->insert('m_kpi_so_target_month', $data3);
                            }
                        }
                    }
                }

                //=================================================================================

                //use pic kpi-so dan manager
                $arr_user_pic_kpi_so = explode(',',@$this->input->post('user_pic_kpi_so'));
                $arr_user_pic_manager = explode(',',@$this->input->post('user_pic_manager'));
                $arr_join = array_merge($arr_user_pic_kpi_so,$arr_user_pic_manager);
                foreach($arr_join as $nip){
                    //cek role
                    if(in_array($nip, $arr_user_pic_kpi_so)){ $role_id_pic = '8'; }else{ $role_id_pic = '10'; }
                    //mengambil data id_kpi_so lama
                    $id_kpi_so_old = @$this->m_global->getDataAll('m_user_kpi_so', null, ['nip' => $nip], 'id_kpi_so')[0]->id_kpi_so;
                    if($id_kpi_so_old == ''){
                        //insert data
                        $data = [];
                        $data['nip']            = $nip;
                        $data['role_id']        = $role_id_pic;
                        $data['id_kpi_so']      = $id_kpi_so;
                        $data['created_date']   = date("Y-m-d H:i:s");
                        $data['created_by']     = h_session('USERNAME');
                        $result = $this->m_global->insert('m_user_kpi_so', $data);
                    }else{
                         //update data user ic
                        $data = [];
                        $data['role_id']        = $role_id_pic;
                        $data['id_kpi_so']      = $id_kpi_so_old.', '.$id_kpi_so;
                        $data['updated_date']   = date("Y-m-d H:i:s");
                        $data['updated_by']     = h_session('USERNAME');
                        $result = $this->m_global->update('m_user_kpi_so', $data, ['nip' => $nip]);
                    }
                }
                
                //=================================================================================

                //membuat user login, role pic si
                $arr_user_pic_kpi_so = explode(',',@$this->input->post('user_pic_kpi_so'));
                $arr_user_pic_manager = explode(',',@$this->input->post('user_pic_manager'));
                $arr_join = array_merge($arr_user_pic_kpi_so,$arr_user_pic_manager);
                foreach($arr_join as $nip){
                    //cek role
                    if(in_array($nip, $arr_user_pic_kpi_so)){ $role_id_pic = '8'; }else{ $role_id_pic = '10'; }
                    //cek nip di table user
                    $arr_user = @$this->m_global->getDataAll('sys_user a', null, ['a.nip' => $nip],'id, role_id')[0];
                    $user_id = @$arr_user->id;
                    if($user_id == ''){
                        //insert user
                        $select = " \"NAMA\", \"NAMA\", \"CHILD_EMAIL\", \"NAME\", \"POSITION_ID\"";
                        $select .= ", (SELECT b.\"SINGKATAN_POSISI\" FROM \"ERP_STO_REAL\" b WHERE b.\"POSITION_ID\"= a.\"POSITION_ID\" LIMIT 1) AS \"SINGKATAN_POSISI\"";
                        $arr = @$this->m_global->getDataAll('DIRJAB_STO a', null, ['a.NIP' => $nip], $select)[0];
                        $data = [];
                        $data['username']   = @$nip;
                        $data['password']   = md5_mod('123');
                        $data['nip']        = @$nip;
                        $data['fullname']   = @$arr->NAMA;
                        $data['email']      = @$arr->CHILD_EMAIL;
                        $data['title']      = @$arr->NAME;
                        $data['singkatan_jabatan']  = @$arr->SINGKATAN_POSISI;
                        $data['position_id']  = @$arr->POSITION_ID;
                        $data['role_id']    = $role_id_pic;
                        $this->m_global->insert('sys_user', $data);
                    }else{
                        //update user
                        $data = [];
                        $user_role_id = @$arr_user->role_id;
                        if($user_role_id == ''){
                            $data['role_id'] = $role_id_pic;
                        }else{
                            $arr_user_role_id = explode(', ',$user_role_id);
                            if(!in_array($role_id_pic, $arr_user_role_id)){
                                $arr_user_role_id[] = $role_id_pic;
                            }
                            $data['role_id'] = join(', ', $arr_user_role_id);
                        }
                        $this->m_global->update('sys_user', $data, ['id' => $user_id]);
                    }
                }
                //==================================================================================

                // echo '<pre>';print_r($data);exit;
                // echo $this->db->last_query();exit;

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


    public function delete_data() {
        //cek csrf token
        $ex_csrf_token = @$this->input->post('token');
        $res = [];
        if (csrf_get_token() != $ex_csrf_token){
            $res['status']  = 0;
            $res['message'] = $this->csrf_message;
            echo json_encode($res);
        }else{
            $id = $this->input->post('id');
            $val = $this->input->post('val');
            $data['is_active'] = $val;
            $res = $this->m_global->update('m_kpi_so', $data, ['id' => $id]);
            $res1 = @$this->m_global->update('m_kpi_so_target_month', $data, ['id_kpi_so' => $id]);
            $res2 = @$this->m_global->update('m_kpi_so_target_year', $data, ['id_kpi_so' => $id]);
            if($val == 't'){
                $res['message'] = 'Active Success!';
            }else{
                $res['message'] = 'Delete Success!';
            }
            
            echo json_encode($res);
        }
    }


    public function select_perspective()
    {
        if(isset($_REQUEST['q'])){
            $q = strtolower($_REQUEST['q']);
            $id_bsc = @$_REQUEST['id_bsc'];
            if(!empty($_REQUEST['q'])){
                $where = "LOWER(name) LIKE '%".$q."%'";
            }
            if(!empty($id_bsc)){
                $where = " id = '".$id_bsc."'";
            }
            $arr_perspective = @$this->m_global->getDataAll('m_bsc AS a', NULL,$where,"a.id_perspective")[0]->id_perspective;
            $arr_perspective = str_replace(' ','',$arr_perspective);
            $where = " id IN(".$arr_perspective.")";
            $arr = @$this->m_global->getDataAll('m_perspective AS a', NULL,$where,"a.id, a.name");
            // echo $this->db->last_query();exit;    
            $data = [];
            for ($i=0; $i < count($arr); $i++) {
                $data[$i] = ['id' => $arr[$i]->id,  'name' => $arr[$i]->name ];
            }
            echo json_encode(['item' => $data]);
        }else{
            $id = $_REQUEST['id'];
            $where = ['id' => $id];
            $arr = @$this->m_global->getDataAll('m_perspective AS a', NULL,$where,"a.id, a.name",NULL,NULL,0,10);
            $data[] = [ 'id' => @$arr[0]->id,  'name' => @$arr[0]->name ];
            echo json_encode($data);
        }
    }

    public function select_so()
    {
        if(isset($_REQUEST['q'])){
            $q = strtolower($_REQUEST['q']);
            $id_bsc = @$_REQUEST['id_bsc'];
            $id_periode = @$_REQUEST['id_periode'];
            $id_perspective = @$_REQUEST['id_perspective'];
            $where = "a.is_active = 't' AND a.status_so = '3'";
            if(!empty($_REQUEST['q'])){
                $where .= " AND LOWER(a.name) LIKE '%".$q."%'";
            }
            if(!empty($id_periode)){
                $where .= " AND a.id_periode = '".$id_periode."'";
            }
            if(!empty($id_bsc)){
                $where .= " AND a.id_bsc = '".$id_bsc."'";
            }
            if(!empty($id_perspective)){
                $where .= " AND a.id_perspective = '".$id_perspective."'";
            }

            //cek role PIC SO
            if(h_session('ROLE_ID') == '4'){ 
                $position_id = h_session('POSITION_ID');
                if($position_id != ''){
                    $where .= " AND ".$position_id."::text = ANY (string_to_array(a.pic_so,', ')::text[]) "; 
                }else{
                    $where .= " AND a.pic_so = '' "; 
                }
            }

            //cek role PIC KPI-SO
            if(h_session('ROLE_ID') == '8'){
                $nip = h_session('NIP');
                $where2 = " a.is_active = 't' ";
                $where2 .= " AND '".$nip."'::text = ANY (string_to_array(a.user_pic_kpi_so,', ')::text[]) "; 
                $select2 = "STRING_AGG(a.id_so::character varying, ',') AS arr_id_so";
                $arr_id_so = $this->m_global->getDataAll('m_kpi_so a', null, $where2, $select2)[0]->arr_id_so;
                if($arr_id_so !=  ''){
                    $where .= " AND a.id IN('".$arr_id_so."') "; 
                }
            }

            //cek role PIC KPI-SO Manager
            if(h_session('ROLE_ID') == '10'){
                $nip = h_session('NIP');
                $where2 = " a.is_active = 't' ";
                $where2 .= " AND '".$nip."'::text = ANY (string_to_array(a.user_pic_manager,', ')::text[]) "; 
                $select2 = "STRING_AGG(a.id_so::character varying, ',') AS arr_id_so";
                $arr_id_so = $this->m_global->getDataAll('m_kpi_so a', null, $where2, $select2)[0]->arr_id_so;
                if($arr_id_so !=  ''){
                    $where .= " AND a.id IN('".$arr_id_so."') "; 
                }
            }

            $arr = @$this->m_global->getDataAll('m_so AS a', NULL,$where,"a.id, a.code, a.name, a.pic_so, a.start_date, a.end_date", null, "a.code ASC");
            // echo $this->db->last_query();exit;    

            $data = [];
            for ($i=0; $i < count($arr); $i++) {
                $name = '<b>['.$arr[$i]->code.']</b> '.$arr[$i]->name;
                $data[$i] = ['id' => $arr[$i]->id,  'name' => $name,  
                                'pic_so' => $arr[$i]->pic_so, 'start_date' => substr($arr[$i]->start_date,0,7),'end_date' => substr($arr[$i]->end_date,0,7)
                            ];
            }
            echo json_encode(['item' => $data]);
        }else{
            $id = $_REQUEST['id'];
            $where = ['id' => $id];
            $arr = @$this->m_global->getDataAll('m_so AS a', NULL,$where,"a.id, a.code, a.name, a.pic_so, a.start_date, a.end_date",NULL,NULL,0,10);
            $name = '<b>['.$arr[0]->code.']</b> '.$arr[0]->name;
            $data[] = [ 'id' => @$arr[0]->id,  'name' => @$name,  
                        'pic_so' => $arr[0]->pic_so, 'start_date' => substr($arr[0]->start_date,0,7),'end_date' => substr($arr[0]->end_date,0,7)
                    ];
            echo json_encode($data);
        }
    }


    public function select_pic_kpi_so()
    {
        if(isset($_REQUEST['q'])){
            $q          = $_REQUEST['q'];
            $where      = " \"POSISI\" != '' ";
            if($q != ''){ $where .= ' AND (LOWER("POSISI") LIKE \'%'.strtolower($q).'%\' OR LOWER("SINGKATAN_POSISI") LIKE \'%'.strtolower($q).'%\')' ; }
            $select     = "\"POSISI\" AS \"JABATAN\", \"SINGKATAN_POSISI\", \"POSITION_ID\"";
            $select     .= ", (SELECT b.\"NAMA\" FROM \"DIRJAB_STO\" b 
                                WHERE b.\"POSITION_ID\"= a.\"POSITION_ID\" LIMIT 1
                            ) AS \"NAMA\"";
            $select     .= ", (SELECT b.\"NIP\" FROM \"DIRJAB_STO\" b 
                                WHERE b.\"POSITION_ID\"= a.\"POSITION_ID\" LIMIT 1
                            ) AS \"NIP\"";
            $arr = $this->m_global->getDataAll('ERP_STO_REAL a', NULL, $where, $select, NULL, '"SINGKATAN_POSISI" ASC',0,20);
            $data = [];
            for ($i=0; $i < count($arr); $i++) {
                if($arr[$i]->NIP != ''){
                    $name = '<b>'.$arr[$i]->SINGKATAN_POSISI.'</b> ["'.$arr[$i]->JABATAN.'"] [<b>"'.$arr[$i]->NAMA.'"</b>]';
                    $data[] = ['id' => $arr[$i]->POSITION_ID, 'name' => $name, 'user_pic_kpi_so'=> $arr[$i]->NIP ];
                }
            }
            echo json_encode(['item' => $data]);
        }else{
            $id         = str_replace(",", "','",  $_REQUEST['id']);
            $where      = "\"POSITION_ID\" IN ('".$id."')";
            $select     = "\"POSISI\" AS \"JABATAN\", \"SINGKATAN_POSISI\", \"POSITION_ID\"";
            $select     .= ", (SELECT b.\"NAMA\" FROM \"DIRJAB_STO\" b 
                                WHERE b.\"POSITION_ID\"= a.\"POSITION_ID\" LIMIT 1
                            ) AS \"NAMA\"";
            $select     .= ", (SELECT b.\"NIP\" FROM \"DIRJAB_STO\" b 
                                WHERE b.\"POSITION_ID\"= a.\"POSITION_ID\" LIMIT 1
                            ) AS \"NIP\"";
            $arr = $this->m_global->getDataAll('ERP_STO_REAL a', NULL, $where, $select);
            $data = [];
            for ($i=0; $i < count($arr); $i++) {
                $name = '<b>'.$arr[$i]->SINGKATAN_POSISI.'</b> ["'.$arr[$i]->JABATAN.'"] [<b>"'.$arr[$i]->NAMA.'"</b>]';
                $data[] = ['id' => $arr[$i]->POSITION_ID, 'name' => $name, 'user_pic_kpi_so'=> $arr[$i]->NIP];
            }
            echo json_encode($data);
        }
    }


    public function select_pic_manager()
    {
        if(isset($_REQUEST['q'])){
            $q          = $_REQUEST['q'];
            $where      = " 1=1 ";
            if($q != ''){ $where .= ' AND LOWER("NIP") LIKE \'%'.strtolower($q).'%\' OR LOWER("NAMA") LIKE \'%'.strtolower($q).'%\'' ; }
            $select     = "\"NIP\", \"NAMA\", \"CHILD_EMAIL\", \"POSITION_ID\"";
            $select     .= ", (SELECT b.\"POSISI\" FROM \"ERP_STO_REAL\" b 
                                WHERE b.\"POSITION_ID\"= a.\"POSITION_ID\" LIMIT 1
                            ) AS \"TITLE\"";
            $arr = $this->m_global->getDataAll('DIRJAB_STO a', NULL, $where, $select, NULL, '"NAMA" ASC',0,20);
            $data = [];
            for ($i=0; $i < count($arr); $i++) {
                $name = '["<b>'.$arr[$i]->NIP.'</b>"] [<b>"'.$arr[$i]->NAMA.'"</b>] ["'.$arr[$i]->TITLE.'"]';
                $data[$i] = ['id' => $arr[$i]->NIP, 'name' => $name];
            }
            echo json_encode(['item' => $data]);
        }else{
            $id         = str_replace(",", "','",  $_REQUEST['id']);
            $where      = "\"NIP\" IN ('".$id."')";
            $select     = "\"NIP\", \"NAMA\", \"NAME\" AS \"TITLE\", \"CHILD_EMAIL\", \"POSITION_ID\"";
            $select     .= ", (SELECT b.\"POSISI\" FROM \"ERP_STO_REAL\" b 
                                WHERE b.\"POSITION_ID\"= a.\"POSITION_ID\" LIMIT 1
                            ) AS \"TITLE\"";
            $arr = $this->m_global->getDataAll('DIRJAB_STO a', NULL, $where, $select, NULL, '"NAMA" ASC',0,20);
            $data = [];
            for ($i=0; $i < count($arr); $i++) {
                $name = '["<b>'.$arr[$i]->NIP.'</b>"] [<b>"'.$arr[$i]->NAMA.'"</b>] ["'.$arr[$i]->TITLE.'"]';
                $data[$i] = ['id' => $arr[$i]->NIP, 'name' => $name];
            }
            echo json_encode($data);
        }
    }


    public function export_excel()
    {
        //load model view
        $param = @$this->input->post('input_form_export');
        $arr = json_decode($param);
        // echo '<pre>';print_r($arr);exit;
        
        //load model
        $this->load->model('app/m_kpi_so','m_kpi_so');

        //search default
        $where  = [];
        $whereE = " is_active = 't' ";

        //filter global
        $id_periode = @$arr->global_id_periode;
        $id_bsc = @$arr->global_id_bsc;
        $id_perspective = @$arr->global_id_perspective;
        $id_so = @$arr->global_id_so;
        if($id_periode != ''){ $where['a.id_periode'] = $id_periode; }
        if($id_bsc != ''){ $where['a.id_bsc'] = $id_bsc; }
        if($id_perspective != ''){ $where['a.id_perspective'] = $id_perspective; }
        if($id_so != ''){ $where['a.id_so'] = $id_so; }

        //filtering
        $search = [];
        if(isset($_REQUEST['filter'])){
            foreach ($_REQUEST['filter'] as $row) {

                //default parameter
                if(@$row['name'] != ''){

                    $val= $row['value']; $tipe = $row['tipe']; $search[] = $row['name']; 
                    $name = @$row['name'];
                    $name = $this->m_kpi_so->select($row['name']); $name = str_replace(' AS '.$row['name'], ' ', $name );

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

        //table dan join
        $table = "m_kpi_so AS a";
        $join  = NULL;
        $order = "a.id_perspective ASC, code_so ASC, code ASC";

        //select 
        $select = "*";
        // $select_field = [ 'id','nip','nik','customer','no_hp' ];
        // $select = array_unique(array_merge($select_field, $search));
        $select = $this->m_kpi_so->select($select);

        //Ambil datanya
        $result = $this->m_global->getDataAll($table, $join, $where, $select, $whereE, $order);
        // echo $this->db->last_query();exit;
        // echo '<pre>';print_r($result);exit;
        // foreach($result as $row){
        //     echo $row->name_perspective.'<br>';
        // }exit;
        $data ['data'] = $result;

        //variabel tambahan
        $periode = @$this->m_global->getDataAll('m_periode', null, ['id'=> @$arr->global_id_periode], 'name')[0]->name;
        $bsc = @$this->m_global->getDataAll('m_bsc', null, ['id'=> @$arr->global_id_bsc], 'name')[0]->name;
        
        $data['periode'] = @$periode;
        $data['bsc'] = @$bsc;

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment;filename=Summary-KPI-SO-Periode.xls");
        header("Content-Transfer-Encoding: binary ");

        $this->template->display_ajax($this->url.'/v_excel_kpi_so_year',$data);


    }



    public function download_excel()
    {
        //load model view
        $param = @$this->input->post('input_form');
        $arr = json_decode($param);
        // echo '<pre>';print_r($param);exit;
        
        //load model
        $this->load->model('app/m_kpi_so','m_kpi_so');

        //search default
        $where  = [];
        $whereE = " a.is_active = 't' ";

        //filter global
        //bsc
        $id_bsc = @$arr->global_id_bsc;
        if($id_bsc != ''){ $whereE .= " AND a.id_bsc = $id_bsc"; }
        //year
        $year = @$arr->global_year;
        $date = $year.'-01-01';
        $whereE .= " AND a.\"start_date\" <= '".$date."' AND a.\"end_date\" >= '".$date."' ";
       
        //data kpi-so
        $table = "m_kpi_so AS a";
        $join  = NULL;
        $order = "a.id_perspective ASC, code_so ASC, code ASC";
        $select = ['id', 'name_kpi_so', 'code_kpi_so', 'id_so','name_so','code_so','polarisasi'];
        $select = $this->m_kpi_so->select($select);
        $arr_kpi_so = @$this->m_global->getDataAll($table, $join, $where, $select, $whereE, $order);
        // echo '<pre>';print_r($arr_kpi_so);exit;

        //target year
        $where = " 1=1 ";
        if($year != ''){
            $date = $year.'-01-01';
            $where .= " AND a.\"start_date\" <= '".$date."' AND a.\"end_date\" >= '".$date."' ";
        }
        if($id_bsc != ''){ 
            $where .= " AND x.\"id_bsc\" = '".$id_bsc."'";
        }
        $select = 'a.id, a.polarisasi, z.year, z.target, z.target_from, z.target_to';
        $join  = [  ['table' => 'm_kpi_so_target_year z', 'on' => 'a.id = z.id_kpi_so', 'join' => 'LEFT'],
                    ['table' => 'm_so x', 'on' => 'x.id = a.id_so', 'join' => 'LEFT'] ];
        $arr = @$this->m_global->getDataAll('m_kpi_so AS a', $join, $where, $select);
        $target_year = [];
        foreach($arr as $row){
            //target year
            if($row->polarisasi == '10'){
                $target = $row->target_from.' - '.$row->target_to;
            }else{
                $target = $row->target;
            }
            $target_year[$row->id][$row->year] = $target;
        }
        // echo '<pre>';print_r($target_year);exit;

        //Target Month
        
        $where = " 1=1 ";
        if($year != ''){
            $date = $year.'-01-01';
            $where .= " AND a.\"start_date\" <= '".$date."' AND a.\"end_date\" >= '".$date."' ";
        }
        if($id_bsc != ''){ 
            $where .= " AND x.\"id_bsc\" = '".$id_bsc."'";
        }
        $select = 'a.id, a.polarisasi, z.month, z.year, z.target, z.target_from, z.target_to';
        $join  = [  ['table' => 'm_kpi_so_target_month z', 'on' => 'a.id = z.id_kpi_so', 'join' => 'LEFT'],
                    ['table' => 'm_so x', 'on' => 'x.id = a.id_so', 'join' => 'LEFT']  ];
        $arr = @$this->m_global->getDataAll('m_kpi_so AS a', $join, $where, $select);
        $arr_month = $arr_so_color = [];
        foreach($arr as $row){
            //target month
            if($row->polarisasi == '10'){
                if($row->target_from != ''){
                    $target = $row->target_from.' - '.$row->target_to;
                }else{
                    $target = '';
                }
            }else{
                $target = $row->target;
            }
            $arr_month[$row->id][$row->year][$row->month] = $target;
        }
        // echo '<pre>';print_r($arr_month);exit;

        //param excel
        $template_name = 'target_kpi_so.xls';
        $title = 'Target KPI-SO';
        $filename   ='Target KPI-SO.xlsx';

        //load library
        $this->load->library("excel");
        include APPPATH.'/third_party/PHPExcel/Writer/Excel2007.php';
        $objReader = PHPExcel_IOFactory::createReader('Excel5');
        if(h_localhost()){
            $this->excel = $objReader->load(APPPATH."third_party\\template_excel\\".$template_name);
        }else{
            $this->excel = $objReader->load(APPPATH."third_party//template_excel//".$template_name);
        }

        //data tambahan
        $bsc_name = @$this->m_global->getDataAll('m_bsc', null, ['id'=> $id_bsc], 'name')[0]->name;

        //sheet name
        $this->excel->setActiveSheetIndex(0);
        $this->excel->setActiveSheetIndex(0)->setTitle("$year");

        //bsc
        $this->excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString('B')-1, 2, $bsc_name);
        
        //masukan data
        $baris = 5;
        foreach($arr_kpi_so as $row){
            //data excel
            $name_so = '('.$row->code_so.')'.$row->name_so;
            $t_year = @$target_year[$row->id][$year];
            
            $this->excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString('A')-1,$baris, $row->id);
            $this->excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString('B')-1,$baris, $name_so);
            $this->excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString('C')-1,$baris, $row->code_kpi_so);
            $this->excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString('D')-1,$baris, $row->name_kpi_so);
            $this->excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString('E')-1,$baris, $t_year);
            $a="F"; 
            for($m=1;$m<=12;$m++){ 
                $this->excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString($a)-1,$baris, @$arr_month[$row->id][$year][$m]);
                $a++;
            }
            $baris++;
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
        // echo '<pre>';print_r($_FILES);exit;
        $fileName = 'target_kpi_so_'.time().$_FILES['file']['name'];
        $folder = './public/files/temp/';
        $config['upload_path']   = $folder; //buat folder dengan nama assets di root folder
        $config['file_name']     = $fileName;
        $config['allowed_types'] = '*';
        $config['allowed_types'] = 'xls|xlsx|csv';
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

                    $year = $val_sheet;

                    //membaca data sheet 
                    $sheet              = $objPHPExcel->getSheet($key); 
                    $highestRow         = $sheet->getHighestRow(); 
                    $highestColumn      = $sheet->getHighestColumn();
                    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

                    //tampung data perbaris
                    for ($row = 5; $row <= $highestRow; $row++){ 
                        
                        //array excel
                        $arr_data =  $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE, FALSE)[0];
                        // echo '<pre>';print_r($arr_data);exit;

                        //where
                        $id_kpi_so = $arr_data[0];
                        $where = ['id_kpi_so'=>$id_kpi_so, 'year'=>$year];
                        //data
                        $data = [];
                        $target = $arr_data[4];
                        if(strpos($target, ' - ') !== false){
                            $pecah = explode(' - ',$target);
                            $target_from = $pecah[0];
                            $target_to = $pecah[1];
                            $data['target']      = NULL;
                            $data['target_from'] = str_replace(',','',$target_from);
                            $data['target_to']   = str_replace(',','',$target_to);
                        }else{
                            $data['target'] = str_replace(',','',$target);
                            $data['target_from'] = NULL;
                            $data['target_to']   = NULL;
                        }
                        $data['updated_date']    = date("Y-m-d H:i:s");
                        $data['updated_by']      = h_session('USERNAME');
                        //update table kpi_so_target_year
                        $result = $this->m_global->update('m_kpi_so_target_year', $data, $where);

                        //where
                        $id_kpi_so = $arr_data[0];
                        for ($tw = 1; $tw <= 4; $tw++){ 
                            //where
                            $where = ['id_kpi_so'=>$id_kpi_so, 'year'=>$year, 'month'=>$tw];
                            //data
                            $data = [];
                            $target = $arr_data[4+$tw];
                            if(strpos($target, ' - ') !== false){
                                $pecah = explode(' - ',$target);
                                $target_from = $pecah[0];
                                $target_to = $pecah[1];
                                $data['target']      = NULL;
                                $data['target_from'] = str_replace(',','',$target_from);
                                $data['target_to']   = str_replace(',','',$target_to);
                            }else{
                                $data['target']      = str_replace(',','',$target);
                                $data['target_from'] = NULL;
                                $data['target_to']   = NULL;
                            }
                            $data['updated_date']   = date("Y-m-d H:i:s");
                            $data['updated_by']     = h_session('USERNAME');
                            //update table kpi_so_target_month
                            $result = $this->m_global->update('m_kpi_so_target_month', $data, $where);
                        }
                    }
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


    public function change_status() {
        //cek csrf token
        // $ex_csrf_token = @$this->input->post('token');
        // $res = [];
        // if (csrf_get_token() != $ex_csrf_token){
        //     $res['status']  = 0;
        //     $res['message'] = $this->csrf_message;
        //     echo json_encode($res);
        // }else{

            //update data
            $id = $this->input->post('id');
            $val = $this->input->post('val');
            $data['status_kpi_so'] = $val;
            $res = $this->m_global->update('m_kpi_so', $data, ['id' => $id]);

            //result
            $res['message'] = 'Success!';
            echo json_encode($res);

        // }
    }



    // =========================== upload file ============================
    public function list_file_kpi_so($html=FALSE, $id='')
    {   
        if($html == FALSE){
            $id = $this->input->post('id');
        }
        $where = ['id_from'=>$id, 'type'=>'kpi so', 'is_active'=>'t'];
        $file_kpi_so = @$this->m_global->getDataAll('m_file', null, $where, '*');
        $data['file_kpi_so']  = $file_kpi_so;

        $data['url'] = $this->url;
        $isi = $this->template->display_ajax($this->url.'/v_kpi_so_list_file', $data, NULL, NULL, $html);
        if($html){return $isi;}else{echo $isi;}
    }
    
    public function load_popup_upload_kpi_so()
    {
        $id = $this->input->post('id');
        $data['id'] = $id;
        
        $data['html_list_file_upload_kpi_so'] = $this->list_file_upload_kpi_so(TRUE,$id);

        $data['url'] = $this->url;
        $this->template->display_ajax($this->url.'/v_kpi_so_upload_file', $data);
    }
    
    public function list_file_upload_kpi_so($html=FALSE, $id='')
    {   
        if($html == FALSE){
            $id = $this->input->post('id');
        }
        $where = ['id_from'=>$id, 'type'=>'kpi so', 'is_active'=>'t'];
        $file_kpi_so = @$this->m_global->getDataAll('m_file', null, $where, '*');
        $data['file_kpi_so']  = $file_kpi_so;

        $data['url'] = $this->url;
        $isi = $this->template->display_ajax($this->url.'/v_kpi_so_list_file_upload', $data, NULL, NULL, $html);
        if($html){return $isi;}else{echo $isi;}
    }

    public function upload_file_kpi_so(){
        $date_now                   = date('Y-m-d H:i:s');
        $file_name_origin           = h_replace_file_name($_FILES['userfile']['name']); 
        $type_file                  = h_file_type($_FILES['userfile']['name']);
        $id                         = $this->input->post('id');
        $tgl                        = date('Ymdhis');
        $random                     = rand(1,100);
        //upload
        $folder                     = './public/files/kpi_so/';
        $file_name                  = 'kpi_so_'.$id.'_'.$tgl.'_'.$random;
        $input_name                 = array_keys($_FILES)[0];
        $file_type                  = '*'; 
        $upload = h_upload($folder,$file_name,$input_name,$file_type);
        //insert data
        if($upload == TRUE){
            $data = array(
                'id_from'          => $id,
                'type'             => 'kpi so',
                'file_name'        => $file_name.'.'.$type_file,
                'created_by'       => h_session('USERNAME'),
                'created_date'     => date("Y-m-d H:i:s"),
            );
            $result = $this->db->insert('m_file', $data);
        }
    }

    public function delete_file_kpi_so()
    {
        //param
        $param = $this->input->post();
        $file_name = $param['file_name'];
        $id = $param['id'];
        //delete data
        $result = $this->m_global->update('m_file', ['is_active'=>'f'], ['id' => $id]);
        //delete file
        // unlink(FCPATH."public/files/so/".$file_name);
        //message
        $res['status']  = ($result['status'] ? '1':'0');
        $res['message'] = 'Successfully Save Data!';
        echo json_encode($res);
    }
    // =========================== END upload file ============================


}
