<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MX_Controller {
    
    private $prefix         = 'user';
    private $table_db       = 'sys_user';
    private $title          = 'User';
    private $url            = 'admin/user';

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

        $js['custom']       = ['table_user'];
        $this->template->display($this->url.'/v_'.$this->prefix, $data, $js);
    }

    public function table_user()
    {    
        // load model view 
        $this->load->model('admin/m_user','m_user');

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
                    $name = $this->m_user->select($row['name']); $name = str_replace(' AS '.$row['name'], ' ', $name );

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
        $table = "sys_user AS a";
        $join  = NULL;

        //select 
        $select = "*";
        // $select_field = [ 'id','nip','nik','customer','no_hp' ];
        // $select = array_unique(array_merge($select_field, $search));
        $select = $this->m_user->select($select);

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
            // $btn_delete     = '<button title="Delete" id="'.$id.'" val="f" class="btn btn-sm  btn-danger btn_delete"><i class="fa fa-times"></i></button>';
            // if($rows->is_active == 'f'){
            //     $btn_delete = '<button title="Active" id="'.$id.'" val="t" class="btn btn-sm  btn-danger btn_delete"><i class="fa fa-check"></i></button>';
            // }

            //isi table disini
            $isi['no']                  = $i;
            $isi['id']                  = $rows->id;
            $isi['is_active']           = ($rows->is_active == 't' ? 'Active' : 'Non Active');
            $isi['created_date']        = h_format_date($rows->created_date,'d F Y');
            $isi['created_by']          = $rows->created_by;
            $isi['updated_date']        = h_format_date($rows->updated_date,'d F Y');
            $isi['updated_by']          = $rows->updated_by;
            $isi['action']              = @$btn_edit.@$btn_delete;

            // $isi['role_id']             = $rows->role_id;
            $isi['role_name']           = h_read_more($rows->role_name);
            $isi['fullname']            = $rows->fullname;
            $isi['status']              = ($rows->status == '1' ? 'Active' : 'Disabled');
            $isi['username']            = $rows->username;
            $isi['password']            = $rows->password;
            $isi['fullname']            = $rows->fullname;
            $isi['email']               = $rows->email;
            $isi['contact']             = $rows->contact;
            $isi['photo']               = $rows->photo;
            $isi['nip']                 = $rows->nip;
            $isi['title']               = $rows->title;
            $isi['department']          = $rows->department;
            $isi['company']             = $rows->company;
            $isi['office']              = $rows->office;

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
        $this->template->display_ajax($this->url.'/v_user_add', $data);
    }

    public function load_edit() {
        csrf_init();
        $data['url'] = $this->url;
        
        //get data
        $id = $this->input->post('id');
        $select = "id, nip, fullname, role_id, status";
        $data['data'] = $this->m_global->getDataAll('sys_user', null, ['id'=>$id], '*')[0];
        $this->template->display_ajax($this->url.'/v_user_edit', $data);
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
            $this->form_validation->set_rules('nip', 'Nama User', 'trim|xss_clean|required');
            $this->form_validation->set_rules('role_id', 'Code', 'trim|required');
            if ($this->form_validation->run($this)) {
                
                //role
                $role_id = str_replace(',',', ',@$this->input->post('role_id'));
                if($role_id == ''){ $role_id = null; }

                //nip
                $nip = @$this->input->post('nip');
                $select = "NAMA AS fullname, NAME AS title, CHILD_EMAIL AS email, POSITION_ID AS position_id";
                $arr = $this->m_global->getDataAll('DIRJAB_STO', null, ['NIP'=>$nip], $select)[0];

                //insert data
                $data['role_id']        = $role_id;
                $data['username']       = $arr->email;
                $data['password']       = md5_mod('123');
                $data['nip']            = $nip;
                $data['fullname']       = $arr->fullname;
                $data['title']          = $arr->title;
                $data['email']          = $arr->email;
                $data['position_id']    = $arr->position_id;
                $data['status']         = @$this->input->post('status');
                $data['created_date']   = date("Y-m-d H:i:s");
                $data['created_by']     = h_session('USERNAME');

                $result = $this->m_global->insert('sys_user', $data);
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
            // $this->form_validation->set_rules('role_id', 'Role', 'trim|required');
            if ($this->form_validation->run($this)) {

                //update data
                $id = $this->input->post('id');
                $role_id = str_replace(',',', ',@$this->input->post('role_id'));
                if($role_id == ''){
                    $role_id = null;
                }
                $data['role_id']        = $role_id;
                $data['status']         = @$this->input->post('status');
                $data['updated_date']   = date("Y-m-d H:i:s");
                $data['updated_by']     = h_session('USERNAME');
                $result = $this->m_global->update('sys_user', $data, ['id' => $id]);

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
            $res = $this->m_global->update('sys_user', $data, ['id' => $id]);
            if($val == 't'){
                $res['message'] = 'Active Success!';
            }else{
                $res['message'] = 'Delete Success!';
            }
            
            echo json_encode($res);
        }
    }

    public function reset_password() {
        //cek csrf token
        $ex_csrf_token = @$this->input->post('token');
        $res = [];
        if (csrf_get_token() != $ex_csrf_token){
            $res['status']  = 0;
            $res['message'] = $this->csrf_message;
            echo json_encode($res);
        }else{
            $id = $this->input->post('id');
            $data['password'] = '6aeb0b405e630f3a870679de85fdb10a';
            $res = $this->m_global->update('sys_user', $data, ['id' => $id]);
            $res['message'] = 'Reset Password Success!';
            echo json_encode($res);
        }
    }
    

    public function select_role()
    {

        if(isset($_REQUEST['q'])){
            $q          = $_REQUEST['q'];
            $where      = " name != '' ";
            if($q != ''){ $where .= " AND LOWER(name) LIKE '%".strtolower($q)."%'"; }
            $select     = 'name, id';
            $parent     = $this->m_global->getDataAll('sys_role', NULL, $where, $select, NULL, 'name ASC');
            $data       = [];
            for ($i=0; $i < count($parent); $i++) {
                $data[$i] = ['id' => $parent[$i]->id, 'name' => $parent[$i]->name];
            }
            echo json_encode(['item' => $data]);
        }else{
            $id         = str_replace(",", "','",  $_REQUEST['id']);
            $where      = "id IN ('".$id."')";
            $select     = 'name, id';
            $parent     = $this->m_global->getDataAll('sys_role', NULL, $where, $select, NULL, 'name ASC');
            $data       = [];
            for ($i=0; $i < count($parent); $i++) {
                $data[$i] = ['id' => $parent[$i]->id, 'name' => $parent[$i]->name];
            }
            echo json_encode($data);
        }
    }

    public function select_user()
    {

        if(isset($_REQUEST['q'])){
            $q          = $_REQUEST['q'];
            $where      = " 1=1 ";
            if($q != ''){ $where .= ' AND LOWER("NIP") LIKE \'%'.strtolower($q).'%\' OR LOWER("NAMA") LIKE \'%'.strtolower($q).'%\'' ; }
            $select     = '"NIP", "NAMA"';
            $arr        = $this->m_global->getDataAll('DIRJAB_STO', NULL, $where, $select, NULL, '"NIP" ASC',0,20);
            $data       = [];
            for ($i=0; $i < count($arr); $i++) {
                $name = '[<b>'.$arr[$i]->NIP.'</b>] '.$arr[$i]->NAMA.'';
                $data[$i] = ['id' => $arr[$i]->NIP, 'name' => $name];
            }
            echo json_encode(['item' => $data]);
        }else{
            $where      = "\"NIP\" = '".$_REQUEST['id']."'";
            $select     = '"NIP", "NAMA"';
            $arr        = $this->m_global->getDataAll('DIRJAB_STO', NULL, $where, $select);
            $data       = [];
            for ($i=0; $i < count($arr); $i++) {
                $name = '[<b>'.$arr[$i]->NIP.'</b>] '.$arr[$i]->NAMA.'';
                $data[$i] = ['id' => $arr[$i]->NIP, 'name' => $name];
            }
            echo json_encode($data);
        }
    }

    public function cek_user()
    {
        $where = " nip = '".$_POST['nip']."'";
        $cek   = @$this->m_global->getDataAll('sys_user', NULL, $where, 'id')[0]->id;
        if($cek == ''){ $res['cek'] = 0; }else{  $res['cek'] = 1; }
        echo json_encode($res);
    }


}
