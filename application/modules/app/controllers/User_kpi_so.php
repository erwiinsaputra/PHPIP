<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_kpi_so extends MX_Controller {
    
    private $prefix         = 'user_kpi_so';
    private $table_db       = 'm_user_kpi_so';
    private $title          = 'User KPI-SO';
    private $url            = 'app/user_kpi_so';

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

        $js['custom']       = ['table_user_kpi_so'];
        $this->template->display($this->url.'/v_'.$this->prefix, $data, $js);
    }

    public function table_user_kpi_so()
    {    
        // load model view 
        $this->load->model('app/m_user_kpi_so','m_user_kpi_so');

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
                    $name = $this->m_user_kpi_so->select($row['name']); $name = str_replace(' AS '.$row['name'], ' ', $name );

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
        // echo '<pre>';print_r($columns);exit;
        // echo '<pre>';print_r(@$_REQUEST['order']);exit;


        //order
        if($_REQUEST['columns']){ foreach ($_REQUEST['columns'] as $row) { $columns[] = $row['data']; } }      
        if(isset($_REQUEST['order'])){
            $kolom = @$columns[(@$_REQUEST['order'][0]['column'])];
            $kolom = str_replace('_id', '_name', $kolom);
            $tipe  = @$_REQUEST['order'][0]['dir'];
            $order = [ $kolom , $tipe];
        }else{
            $order = ['id','DESC'];
        }
        // echo '<pre>';print_r($order);exit;

        
        //table dan join
        $table = "m_user_kpi_so AS a";
        $join  = NULL;

        //select 
        $select = "*";
        // $select_field = [ 'id','nip','nik','customer','no_hp' ];
        // $select = array_unique(array_merge($select_field, $search));
        $select = $this->m_user_kpi_so->select($select);

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
            $btn_edit       = '<button title="Edit" id="'.$id.'" class="btn btn-sm btn-primary btn_edit"><i class="fa fa-edit"></i></button>';
            $btn_delete     = '<button title="Delete" id="'.$id.'" val="f" class="btn btn-sm  btn-danger btn_delete"><i class="fa fa-times"></i></button>';
            if($rows->is_active == 'f'){
                $btn_delete = '<button title="Active" id="'.$id.'" val="t" class="btn btn-sm  btn-danger btn_delete"><i class="fa fa-check"></i></button>';
            }

            //isi table disini
            $code_name_kpi_so = '';
            $pecah = explode(', ',$rows->code_kpi_so);
            $pecah2 = explode(', ',$rows->name_kpi_so);
            foreach($pecah as $key=>$val){
                $code_name_kpi_so .= h_read_more($pecah[$key].'&nbsp;'.$pecah2[$key],30);
            }

            // $action = @$btn_edit.@$btn_delete;
            $action = '';

            $isi['no']                  = $i;
            $isi['id']                  = $rows->id;
            $isi['is_active']           = ($rows->is_active == 't' ? 'Active' : 'Non Active');
            $isi['created_date']        = h_format_date($rows->created_date,'d F Y');
            $isi['created_by']          = $rows->created_by;
            $isi['updated_date']        = h_format_date($rows->updated_date,'d F Y');
            $isi['updated_by']          = $rows->updated_by;
            $isi['action']              = $action;

            $isi['nip']                 = $rows->nip;
            $isi['fullname']            = $rows->fullname;
            $isi['role_name']           = str_replace(', ','<br>',$rows->role_name);
            $isi['name_kpi_so']         = $code_name_kpi_so;
            $isi['name_position']       = $rows->name_position;

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
        $this->template->display_ajax($this->url.'/v_user_kpi_so_add', $data);
    }

    public function load_edit() {
        csrf_init();
        $data['url'] = $this->url;
        
        //get data
        $id = $this->input->post('id');
        $data['id'] = $id;
        $data['data'] = $this->m_global->getDataAll('m_user_kpi_so', null, ['id'=>$id], '*')[0];
        $this->template->display_ajax($this->url.'/v_user_kpi_so_edit', $data);
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
            $this->form_validation->set_rules('nip', 'NIP / Nama', 'trim|xss_clean|required');
            $this->form_validation->set_rules('id_kpi_so', 'SI Title', 'trim|required');
            if ($this->form_validation->run($this)) {
                
                //insert data
                $data['nip']            = @$this->input->post('nip');
                $data['id_kpi_so']          = str_replace(',',', ',@$this->input->post('id_kpi_so'));
                $data['created_date']   = date("Y-m-d H:i:s");
                $data['created_by']     = h_session('USERNAME');

                $result = $this->m_global->insert('m_user_kpi_so', $data);
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
            $this->form_validation->set_rules('id', 'id', 'trim|required');
            $this->form_validation->set_rules('nip', 'NIP / Nama', 'trim|xss_clean|required');
            $this->form_validation->set_rules('id_kpi_so', 'SI Title', 'trim|required');
            if ($this->form_validation->run($this)) {

                //update data
                $id = $this->input->post('id');
                $data['nip']            = @$this->input->post('nip');
                $data['id_kpi_so']          = str_replace(',',', ',@$this->input->post('id_kpi_so'));
                $data['updated_date']   = date("Y-m-d H:i:s");
                $data['updated_by']     = h_session('USERNAME');
                $result = $this->m_global->update('m_user_kpi_so', $data, ['id' => $id]);

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
            $res = $this->m_global->update('m_user_kpi_so', $data, ['id' => $id]);
            if($val == 't'){
                $res['message'] = 'Active Success!';
            }else{
                $res['message'] = 'Delete Success!';
            }
            
            echo json_encode($res);
        }
    }

    public function select_kpi_so()
    {

        if(isset($_REQUEST['q'])){
            $q          = $_REQUEST['q'];
            $where      = " name != '' AND is_active='t' ";
            if($q != ''){ $where .= " AND LOWER(name) LIKE '%".strtolower($q)."%'"; }
            $select     = 'id, name, code';
            $parent     = $this->m_global->getDataAll('m_kpi_so', NULL, $where, $select, NULL, 'code ASC');
            $data       = [];
            for ($i=0; $i < count($parent); $i++) {
                $name = '('.$parent[$i]->code.') '.$parent[$i]->name;
                $data[$i] = ['id' => $parent[$i]->id, 'name' => $name];
            }
            echo json_encode(['item' => $data]);
        }else{
            $id         = str_replace(",", "','",  $_REQUEST['id']);
            $where      = "id IN ('".$id."')";
            $select     = 'id, name, code';
            $parent     = $this->m_global->getDataAll('m_kpi_so', NULL, $where, $select, NULL, 'code ASC');
            $data       = [];
            for ($i=0; $i < count($parent); $i++) {
                $name = '('.$parent[$i]->code.') '.$parent[$i]->name;
                $data[$i] = ['id' => $parent[$i]->id, 'name' => $name];
            }
            echo json_encode($data);
        }
    }

    public function select_nip()
    {

        if(isset($_REQUEST['q'])){
            $q          = $_REQUEST['q'];
            $where      = "1=1 AND a.nip != '' AND a.id NOT IN(".h_user_tambahan().")";
            $where      .= " AND ( '8' = ANY (string_to_array(a.role_id,', ')) 
                                    OR '10' = ANY (string_to_array(a.role_id,', '))
                                )";
            if($q != ''){ $where .= ' AND LOWER(a.nip) LIKE \'%'.strtolower($q).'%\' OR LOWER(a.fullname) LIKE \'%'.strtolower($q).'%\'' ; }
            $select     = "a.nip, a.fullname, 
                            (SELECT DISTINCT STRING_AGG ( b.\"name\" :: CHARACTER VARYING, ',' )
                                FROM \"sys_role\" b 
                                WHERE b.\"id\" IN(8,10) 
                                AND b.\"id\" ::text = ANY (string_to_array(a.role_id,', ')::text[])
                            ) AS role_name";
            $arr        = $this->m_global->getDataAll('sys_user a', NULL, $where, $select, NULL, 'a.nip ASC',0,30);
            $data       = [];
            for ($i=0; $i < count($arr); $i++) {
                $name = '[<b>'.$arr[$i]->nip.'</b>] '.$arr[$i]->fullname.'';
                $data[$i] = ['id' => $arr[$i]->nip, 'name' => $name, 'role_name' => $arr[$i]->role_name];
            }
            echo json_encode(['item' => $data]);
        }else{
            $where      = "a.nip = '".$_REQUEST['id']."'";
            $select     = "a.nip, a.fullname, 
                            (SELECT DISTINCT STRING_AGG ( b.\"name\" :: CHARACTER VARYING, ',' )
                                FROM \"sys_role\" b 
                                WHERE b.\"id\" IN(8,10) 
                                AND b.\"id\" ::text = ANY (string_to_array(a.role_id,', ')::text[])
                            ) AS role_name";
            $arr        = $this->m_global->getDataAll('sys_user a', NULL, $where, $select);
            $data       = [];
            for ($i=0; $i < count($arr); $i++) {
                $name = '[<b>'.$arr[$i]->nip.'</b>] '.$arr[$i]->fullname.'';
                $data[$i] = ['id' => $arr[$i]->nip, 'name' => $name, 'role_name' => $arr[$i]->role_name];
            }
            echo json_encode($data);
        }
    }

    public function cek_user_kpi_so()
    {
        $where = " nip = '".$_POST['nip']."'";
        $cek   = @$this->m_global->getDataAll('m_user_kpi_so', NULL, $where, 'id')[0]->id;
        if($cek == ''){ $res['cek'] = 0; }else{  $res['cek'] = 1; }
        echo json_encode($res);
    }


}
