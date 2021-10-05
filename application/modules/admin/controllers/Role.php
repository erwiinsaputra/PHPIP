<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role extends MX_Controller {
    
    private $prefix         = 'role';
    private $table_db       = 'sys_role';
    private $title          = 'Role';
    private $url            = 'admin/role';

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

        $js['custom']       = ['table_role'];
        $this->template->display($this->url.'/v_'.$this->prefix, $data, $js);
    }

    public function table_role()
    {    
        // load model view 
        // $this->load->model('global/sys_role','sys_role');

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
                    // $name = $this->sys_role->select($row['name']); $name = str_replace(' AS '.$row['name'], ' ', $name );

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
        $table = "sys_role AS a";
        $join  = NULL;

        //select 
        $select = "*";
        // $select_field = [ 'id','nip','nik','customer','no_hp' ];
        // $select = array_unique(array_merge($select_field, $search));
        // $select = $this->sys_role->select($select);

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
            $btn_delete     = '<button title="Delete" id="'.$id.'" val="f" class="btn btn-sm  btn-danger btn_delete"><i class="fa fa-remove"></i></button>';
            if($rows->is_active == 'f'){
                $btn_delete = '<button title="Active" id="'.$id.'" val="t" class="btn btn-sm  btn-danger btn_delete"><i class="fa fa-check"></i></button>';
            }

            //isi table disini
            $isi['no']                  = $i;
            $isi['id']                  = $rows->id;
            $isi['is_active']           = ($rows->is_active == 't' ? 'Active' : 'Non Active');
            $isi['created_date']        = h_format_date($rows->created_date,'d F Y');
            $isi['created_by']          = $rows->created_by;
            $isi['updated_date']        = h_format_date($rows->updated_date,'d F Y');
            $isi['updated_by']          = $rows->updated_by;
            $isi['action']              = @$btn_edit;

            $isi['name']                = $rows->name;
            $isi['menu']                = h_read_more($rows->menu,20);
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

    public function load_add() {
        csrf_init();
        $data['url'] = $this->url;

        $data['list_menu'] = $this->m_global->getDataAll('sys_menu', null, NULL, '*',NULL,'name ASC');

        $this->template->display_ajax($this->url.'/v_role_add', $data);
    }

    public function load_edit() {
        csrf_init();
        $data['url'] = $this->url;
        
        //get data
        $id = $this->input->post('id');
        $arr = $this->m_global->getDataAll('sys_role', null, ['id'=>$id], '*')[0];
        $data['data'] = $arr;

        //list menu
        $where = "id NOT IN(".$arr->menu.")";
        $data['list_menu'] = $this->m_global->getDataAll('sys_menu', null, $where, '*',null,'name ASC');

        //menu selected
        $menu_selected = $arr->menu;
        $menu_json = $arr->menu_json;
        $data['menu_selected'] = $this->convert_menu_selected($menu_selected, $menu_json);
        // echo '<pre>';print_r($data['menu_selected']);exit;

        $this->template->display_ajax($this->url.'/v_role_edit', $data);
    }

    public function save_add() {

        // echo '<pre>';print_r($this->input->post());exit;

        //cek csrf token
        $ex_csrf_token = @$this->input->post('ex_csrf_token');
        $res = [];
        if (csrf_get_token() != $ex_csrf_token){
            $res['status']  = 2;
            $res['message'] = $this->csrf_message;
            echo json_encode($res);
        }else{

            // Set Rule Login Form
            $this->form_validation->set_rules('name', 'Role', 'trim|xss_clean|required');
            $this->form_validation->set_rules('menu_json', 'Menu', 'trim|required');
            $this->form_validation->set_rules('description', 'Description', 'trim|required');
            if ($this->form_validation->run($this)) {

                //insert data
                $menu_json = @$this->input->post('menu_json');
                $data['name']           = @$this->input->post('name');
                $data['menu']           = $this->convert_menu_to_array($menu_json);
                $data['menu_json']      = $menu_json;
                $data['description']    = @$this->input->post('description');
                $data['created_date']   = date("Y-m-d H:i:s");
                $data['created_by']     = h_session('USERNAME');
                $result = $this->m_global->insert('sys_role', $data);
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
            $this->form_validation->set_rules('name', 'Role', 'trim|xss_clean|required');
            $this->form_validation->set_rules('menu_json', 'Menu', 'trim|required');
            $this->form_validation->set_rules('description', 'Description', 'trim|required');
            if ($this->form_validation->run($this)) {
                //update data
                $id = $this->input->post('id');
                $menu_json = @$this->input->post('menu_json');
                $data['name']           = @$this->input->post('name');
                $data['menu']           = $this->convert_menu_to_array($menu_json);
                $data['menu_json']      = $menu_json;
                $data['description']    = @$this->input->post('description');
                $data['updated_date']   = date("Y-m-d H:i:s");
                $data['updated_by']     = h_session('USERNAME');
                $result = $this->m_global->update('sys_role', $data, ['id' => $id]);

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
            $res = $this->m_global->update('sys_role', $data, ['id' => $id]);
            if($val == 't'){
                $res['message'] = 'Active Success!';
            }else{
                $res['message'] = 'Delete Success!';
            }
            echo json_encode($res);
        }
    }

    public function convert_menu_to_array($menu_json=[]) {

        $menu_json = json_decode($menu_json);
        $arr = [];
        foreach ($menu_json as $row) {
            $arr[] = $row->id;
            //sub menu
            if(@$row->children != ''){
                foreach(@$row->children as $sub){
                    $arr[] = $sub->id;
                    //sub menu 2
                    if(@$sub->children != ''){
                        foreach(@$sub->children as $sub2){
                            $arr[] = $sub2->id;
                        }
                    }
                }
            }
        }
        sort($arr);
        $arr_menu = join(', ', $arr);
        return $arr_menu;
    }

    public function convert_menu_selected($menu_selected=[], $menu_json=[]) {

        //menu
        $where = "id IN(".$menu_selected.")";
        $menu_selected = $this->m_global->getDataAll('sys_menu', null, $where, '*',null,'name ASC');
        $menu = [];
        foreach($menu_selected as $row){
            $menu[$row->id]['id']        = $row->id;
            $menu[$row->id]['name']      = $row->name;
            $menu[$row->id]['icon']      = $row->icon;
            $menu[$row->id]['controler'] = $row->controler;
            $menu[$row->id]['folder']    = $row->folder;
            $menu[$row->id]['sub']       = [];
        }

        //menu urutan
        $menu_json = json_decode($menu_json);
        $arr = [];
        $a = -1;
        foreach ($menu_json as $row) {
            $a++;
            $arr[$a] = @$menu[$row->id];

            //sub menu
            if(@$row->children != ''){
                $arr_sub = []; 
                $b = -1;
                foreach(@$row->children as $sub){
                    $b++;
                    $arr_sub[$b] = $menu[$sub->id];

                    //sub menu 2
                    if(@$sub->children != ''){
                        $arr_sub2 = [];
                        $c = -1;
                        foreach(@$sub->children as $sub2){
                            $c++;
                            $arr_sub2[$c] = $menu[$sub2->id];
                            
                            //sub menu 3
                            if(@$sub2->children != ''){
                                $arr_sub2 = [];
                                $d = -1;
                                foreach(@$sub2->children as $sub3){
                                    $d++;
                                    $arr_sub3[$d] = $menu[$sub3->id];
                                }
                                $arr_sub2[$c]['sub'] = $arr_sub3;
                            }
                        }
                        $arr_sub[$b]['sub'] = $arr_sub2;
                    }
                }
                $arr[$a]['sub'] = $arr_sub;
            }
        }
        return $arr;
    }

}
