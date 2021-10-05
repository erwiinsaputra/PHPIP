<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class So extends MX_Controller {
    
    private $prefix         = 'so';
    private $table_db       = 'm_so';
    private $title          = 'Strategic Objectives (SO)';
    private $url            = 'app/so';

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
        //periode
        $data['periode'] = $this->m_global->getDataAll('m_periode', null, ['is_active'=>'t'], '*', null, "start_year ASC");
        //status
        $data['status_so'] = $this->m_global->getDataAll('m_status', null, ['is_active'=>'t', 'type'=>'SO STATUS'], '*', null, '"order" ASC');
        
        //year
        $data['start_year'] = @$this->m_global->getDataAll('m_periode', null, ['is_active'=>'t'], 'MIN(start_year) AS year')[0]->year;
        $data['end_year'] = @$this->m_global->getDataAll('m_periode', null, ['is_active'=>'t'], 'MAX(end_year) AS year')[0]->year;

        $js['custom']       = ['table_so'];
        $this->template->display($this->url.'/v_'.$this->prefix, $data, $js);
    }

    public function table_so()
    {    
        // load model view 
        $this->load->model('app/m_so','m_so');

        //search default
        $where  = [];
        $whereE = " a.\"is_active\" = 't' ";

        //cek role PIC SO
        if(h_session('ROLE_ID') == '4'){ 
            $position_id = h_session('POSITION_ID');
            if($position_id != ''){
                $whereE .= " AND ".$position_id."::text = ANY (string_to_array(a.pic_so,', ')::text[]) "; 
            }else{
                $whereE .= " AND a.pic_so = '' "; 
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
                $whereE .= " AND a.id IN(".$arr_id_so.") "; 
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
        if(h_session('ROLE_ID') == '4'){
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
        $select = [ 'id','code','name','name_perspective','name_bsc','name_pic_so','name_status_so',
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
            if(in_array( h_session('ROLE_ID'), h_role_admin())){
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
        csrf_init();
        $data['url'] = $this->url;

        //perspective
        $data['perspective'] = $this->m_global->getDataAll('m_perspective', null, ['is_active'=>'t'], '*', null, "name ASC");
        //bsc
        $data['bsc'] = $this->m_global->getDataAll('m_bsc', null,  ['is_active'=>'t'], '*', null, "name ASC");
        //periode
        $data['periode'] = $this->m_global->getDataAll('m_periode', null, ['is_active'=>'t'], '*', null, "start_year ASC");

        $this->template->display_ajax($this->url.'/v_so_add', $data);
    }

    public function load_edit() {
        csrf_init();
        $data['url'] = $this->url;

        //param
        $id = $this->input->post('id');
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
        //pic
        $where = " '4' = ANY (string_to_array(role_id,',')) ";
        $data['pic'] = $this->m_global->getDataAll('sys_user', null, ['is_active'=>'t'], '*', $where, "fullname ASC");

        //file
        $data['html_list_file_so'] = $this->list_file_so(TRUE,$id);
        
        //get data
        $data['data'] = $this->m_global->getDataAll('m_so', null, ['id'=>$id], '*')[0];
        $this->template->display_ajax($this->url.'/v_so_edit', $data);
    }

    public function load_copy() {
        csrf_init();
        $data['url'] = $this->url;

        //perspective
        $data['perspective'] = $this->m_global->getDataAll('m_perspective', null, ['is_active'=>'t'], '*', null, "name ASC");
        //bsc
        $data['bsc'] = $this->m_global->getDataAll('m_bsc', null,  ['is_active'=>'t'], '*', null, "name ASC");
        //pic
        $where = " '4' = ANY (string_to_array(role_id,',')) ";
        $data['pic'] = $this->m_global->getDataAll('sys_user', null, ['is_active'=>'t'], '*', $where, "fullname ASC");
        
        //get data
        $id = $this->input->post('id');
        $data['data'] = $this->m_global->getDataAll('m_so', null, ['id'=>$id], '*')[0];
        $this->template->display_ajax($this->url.'/v_so_copy', $data);
    }


    public function save_add() {

        //cek csrf token
        $ex_csrf_token = @$this->input->post('ex_csrf_token');
        $res = [];
        if (csrf_get_token() != $ex_csrf_token){
            $res['status']  = 2;
            $res['message'] = $this->csrf_message;
            echo json_encode($res);
        }else{

            // Set Rule Login Form
            $this->form_validation->set_rules('start_date', 'Periode', 'trim|xss_clean|required');
            $this->form_validation->set_rules('end_date', 'Periode', 'trim|xss_clean|required');
            $this->form_validation->set_rules('name', 'SO Title', 'trim|xss_clean|required');
            $this->form_validation->set_rules('code', 'SO Number', 'trim|xss_clean|required');
            $this->form_validation->set_rules('id_bsc', 'BSC', 'trim|xss_clean|required');
            $this->form_validation->set_rules('id_perspective', 'Perspective', 'trim|xss_clean|required');
            $this->form_validation->set_rules('pic_so', 'PIC', 'trim|xss_clean|required');
            // $this->form_validation->set_rules('description', 'Description', 'trim|xss_clean|required');
            if ($this->form_validation->run($this)) {

                //insert data
                $id_bsc =  @$this->input->post('id_bsc');
                $data['start_date']     = @$this->input->post('start_date').'-01';
                $data['end_date']       = @$this->input->post('end_date').'-01';
                $data['name']           = @$this->input->post('name');
                $data['code']           = @$this->input->post('code');
                $data['id_bsc']         = $id_bsc;
                $data['id_perspective'] = @$this->input->post('id_perspective');
                $data['pic_so']         = str_replace(',',', ',@$this->input->post('pic_so'));
                if($id_bsc != '1'){
                    $data['parent_so']  = @$this->input->post('parent_so');
                }
                $data['description']    = @$this->input->post('description');
                $data['created_date']   = date("Y-m-d H:i:s");
                $data['status_so']      = 1;
                $data['created_by']     = h_session('USERNAME');
                $result = $this->m_global->insert('m_so', $data);
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
        }
    }


    public function save_edit() {

        //cek csrf token
        $ex_csrf_token = @$this->input->post('ex_csrf_token');
        $res = [];
        if (csrf_get_token() != $ex_csrf_token){
            $res['status']  = 2;
            $res['message'] = $this->csrf_message;
            echo json_encode($res);
        }else{

            // Set Rule Login Form
            $this->form_validation->set_rules('start_date', 'Periode', 'trim|xss_clean|required');
            $this->form_validation->set_rules('end_date', 'Periode', 'trim|xss_clean|required');
            $this->form_validation->set_rules('name', 'SO Title', 'trim|xss_clean|required');
            $this->form_validation->set_rules('code', 'SO Number', 'trim|xss_clean|required');
            $this->form_validation->set_rules('id_bsc', 'BSC', 'trim|xss_clean|required');
            $this->form_validation->set_rules('id_perspective', 'Perspective', 'trim|xss_clean|required');
            $this->form_validation->set_rules('pic_so', 'PIC', 'trim|xss_clean|required');
            // $this->form_validation->set_rules('description', 'Description', 'trim|xss_clean|required');
            if ($this->form_validation->run($this)) {

                //update data
                $id = $this->input->post('id');
                $id_bsc =  @$this->input->post('id_bsc');
                $data['start_date']     = @$this->input->post('start_date').'-01';
                $data['end_date']       = @$this->input->post('end_date').'-01';
                $data['name']           = @$this->input->post('name');
                $data['code']           = @$this->input->post('code');
                $data['id_bsc']         = $id_bsc;
                $data['id_perspective'] = @$this->input->post('id_perspective');
                $data['pic_so']         = str_replace(',',', ',@$this->input->post('pic_so'));
                if($id_bsc != '1'){
                    $data['parent_so']  = @$this->input->post('parent_so');
                }
                $data['description']    = @$this->input->post('description');
                $data['updated_date']   = date("Y-m-d H:i:s");
                $data['updated_by']     = h_session('USERNAME');
                $result = $this->m_global->update('m_so', $data, ['id' => $id]);

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
        }
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
            $res = $this->m_global->update('m_so', $data, ['id' => $id]);
            if($val == 't'){
                $res['message'] = 'Active Success!';
            }else{
                $res['message'] = 'Delete Success!';
            }
            
            echo json_encode($res);
        }
    }

    public function change_status() {
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
            $data['status_so'] = $val;
            $res = $this->m_global->update('m_so', $data, ['id' => $id]);
            $res['message'] = 'Success!';
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


    public function select_pic_so()
    {

        if(isset($_REQUEST['q'])){
            $q          = $_REQUEST['q'];
            $where      = " \"POSISI\" != '' ";
            if($q != ''){ $where .= ' AND LOWER("POSISI") LIKE \'%'.strtolower($q).'%\' OR LOWER("SINGKATAN_POSISI") LIKE \'%'.strtolower($q).'%\'' ; }
            $select     = '"POSISI" AS "JABATAN", "SINGKATAN_POSISI", "POSITION_ID"';
            $parent     = $this->m_global->getDataAll('ERP_STO_REAL', NULL, $where, $select, NULL, '"SINGKATAN_POSISI" ASC',0,20);
            $data       = [];
            for ($i=0; $i < count($parent); $i++) {
                $name = '<b>'.$parent[$i]->SINGKATAN_POSISI.'</b> ["'.$parent[$i]->JABATAN.'"]';
                $data[$i] = ['id' => $parent[$i]->POSITION_ID, 'name' => $name];
            }
            echo json_encode(['item' => $data]);
        }else{
            $id         = str_replace(",", "','",  $_REQUEST['id']);
            $where      = "\"POSITION_ID\" IN ('".$id."')";
            $select     = '"POSISI" AS "JABATAN", "SINGKATAN_POSISI", "POSITION_ID"';
            $parent     = $this->m_global->getDataAll('ERP_STO_REAL', NULL, $where, $select);
            $data       = [];
            for ($i=0; $i < count($parent); $i++) {
                $name = '<b>'.$parent[$i]->SINGKATAN_POSISI.'</b> ["'.$parent[$i]->JABATAN.'"]';
                $data[$i] = ['id' => $parent[$i]->POSITION_ID, 'name' => $name];
            }
            echo json_encode($data);
        }
    }

    public function select_parent_so()
    {
        if(isset($_REQUEST['q'])){
            $q = strtolower($_REQUEST['q']);
            $id_perspective = @$_REQUEST['id_perspective'];
            $where = "is_active = 't'";
            $where .= " AND id_bsc = '1'";
            if(!empty($_REQUEST['q'])){
                $where .= " AND LOWER(name) LIKE '%".$q."%'";
            }
            if(!empty($id_perspective)){
                $where .= " AND id_perspective = '".$id_perspective."'";
            }
            $arr = @$this->m_global->getDataAll('m_so AS a', NULL,$where,"a.id, a.code, a.name, a.pic_so, a.id_perspective",NULL,"code ASC");
            // echo $this->db->last_query();exit;    
            $data = [];
            for ($i=0; $i < count($arr); $i++) {
                $name = '<b>['.$arr[$i]->code.']</b> '.$arr[$i]->name;
                $data[$i] = ['id' => $arr[$i]->id, 'name' => $name,  
                                'code' => $arr[$i]->code, 'name_so' => $arr[$i]->name,  
                                'pic_so' => $arr[$i]->pic_so, 'id_perspective' => $arr[$i]->id_perspective
                            ];
            }
            echo json_encode(['item' => $data]);
        }else{
            $id = $_REQUEST['id'];
            $id  = str_replace(",", "','", $id);
            $where = " a.id IN ('".$id."')";
            $arr = @$this->m_global->getDataAll('m_so AS a', NULL,$where,"a.id, a.code, a.name, a.pic_so, a.id_perspective");
            for ($i=0; $i < count($arr); $i++) {
                $name = '<b>['.$arr[$i]->code.']</b> '.$arr[$i]->name;
                $data[$i] = ['id' => $arr[$i]->id, 'name' => $name,  
                                'code' => $arr[$i]->code, 'name_so' => $arr[$i]->name,  
                                'pic_so' => $arr[$i]->pic_so, 'id_perspective' => $arr[$i]->id_perspective
                            ];
            }
            echo json_encode($data);
        }
    }


    // =========================== upload file ============================
    public function list_file_so($html=FALSE, $id='')
    {   
        if($html == FALSE){
            $id = $this->input->post('id');
        }
        $where = ['id_from'=>$id, 'type'=>'so', 'is_active'=>'t'];
        $file_so = @$this->m_global->getDataAll('m_file', null, $where, '*');
        $data['file_so']  = $file_so;

        $data['url'] = $this->url;
        $isi = $this->template->display_ajax($this->url.'/v_so_list_file', $data, NULL, NULL, $html);
        if($html){return $isi;}else{echo $isi;}
    }
    
    public function load_popup_upload_so()
    {
        $id = $this->input->post('id');
        $data['id'] = $id;
        
        $data['html_list_file_upload_so'] = $this->list_file_upload_so(TRUE,$id);

        $data['url'] = $this->url;
        $this->template->display_ajax($this->url.'/v_so_upload_file', $data);
    }
    
    public function list_file_upload_so($html=FALSE, $id='')
    {   
        if($html == FALSE){
            $id = $this->input->post('id');
        }
        $where = ['id_from'=>$id, 'type'=>'so', 'is_active'=>'t'];
        $file_so = @$this->m_global->getDataAll('m_file', null, $where, '*');
        $data['file_so']  = $file_so;

        $data['url'] = $this->url;
        $isi = $this->template->display_ajax($this->url.'/v_so_list_file_upload', $data, NULL, NULL, $html);
        if($html){return $isi;}else{echo $isi;}
    }

    public function upload_file_so(){
        $date_now                   = date('Y-m-d H:i:s');
        $file_name_origin           = h_replace_file_name($_FILES['userfile']['name']); 
        $type_file                  = h_file_type($_FILES['userfile']['name']);
        $id                         = $this->input->post('id');
        $tgl                        = date('Ymdhis');
        $random                     = rand(1,100);
        //upload
        $folder                     = './public/files/so/';
        $file_name                  = 'so_'.$id.'_'.$tgl.'_'.$random;
        $input_name                 = array_keys($_FILES)[0];
        $file_type                  = '*'; 
        $upload = h_upload($folder,$file_name,$input_name,$file_type);
        //insert data
        if($upload == TRUE){
            $data = array(
                'id_from'            => $id,
                'type'             => 'so',
                'file_name'        => $file_name.'.'.$type_file,
                'created_by'       => h_session('USERNAME'),
                'created_date'     => date("Y-m-d H:i:s"),
            );
            $result = $this->db->insert('m_file', $data);
        }
    }

    public function delete_file_so()
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
