<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends MX_Controller {
    
    private $prefix         = 'menu';
    private $table_db       = 'sys_menu';
    private $title          = 'Menu';
    private $url            = 'admin/menu';

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

        $js['custom']       = ['table_menu'];
        $this->template->display($this->url.'/v_'.$this->prefix, $data, $js);
    }

    public function table_menu()
    {    
        // load model view 
        // $this->load->model('admin/m_menu','sys_menu');

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
                    // $name = $this->sys_menu->select($row['name']); $name = str_replace(' AS '.$row['name'], ' ', $name );

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
        $table = "sys_menu AS a";
        $join  = NULL;

        //select 
        $select = "*";
        // $select_field = [ 'id','nip','nik','customer','no_hp' ];
        // $select = array_unique(array_merge($select_field, $search));
        // $select = $this->sys_menu->select($select);

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
            $isi['action']              = @$btn_edit.@$btn_delete;

            $isi['name']                = $rows->name;
            $isi['parent']              = $rows->parent;
            $isi['order']               = $rows->order;
            $isi['icon']                = $rows->icon;
            $isi['folder']              = $rows->folder;
            $isi['controler']           = $rows->controler;
            $isi['link']                = $rows->link;
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
        $this->template->display_ajax($this->url.'/v_menu_add', $data);
    }

    public function load_edit() {
        csrf_init();
        $data['url'] = $this->url;
        
        //get data
        $id = $this->input->post('id');
        $data['data'] = $this->m_global->getDataAll('sys_menu', null, ['id'=>$id], '*')[0];
        $this->template->display_ajax($this->url.'/v_menu_edit', $data);
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
            $this->form_validation->set_rules('name', 'Menu', 'trim|xss_clean|required');
            if ($this->form_validation->run($this)) {

                //insert data
                $data['name']           = @$this->input->post('name');
                $data['icon']           = (@$this->input->post('icon') == '' ? NULL : $this->input->post('icon'));
                $data['order']          = (@$this->input->post('order') == '' ? NULL : $this->input->post('order'));
                $data['parent']         = (@$this->input->post('parent') == '' ? 0 : $this->input->post('parent'));
                $data['controler']      = (@$this->input->post('controler') == '' ? NULL : $this->input->post('controler'));
                $data['folder']         = (@$this->input->post('folder') == '' ? 'app' : $this->input->post('folder'));
                $data['link']           = (@$this->input->post('link') == '' ? NULL : $this->input->post('link'));
                $data['description']    = (@$this->input->post('description') == '' ? NULL : $this->input->post('description'));
                $data['status']         = 1;
                $data['created_date']   = date("Y-m-d H:i:s");
                $data['created_by']     = h_session('USERNAME');
                $result = $this->m_global->insert('sys_menu', $data);
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
            $this->form_validation->set_rules('name', 'Menu', 'trim|required');
            if ($this->form_validation->run($this)) {

                //update data
                $id = $this->input->post('id');
                //insert data
                $data['name']           = @$this->input->post('name');
                $data['icon']           = (@$this->input->post('icon') == '' ? NULL : $this->input->post('icon'));
                $data['order']          = (@$this->input->post('order') == '' ? NULL : $this->input->post('order'));
                $data['parent']         = (@$this->input->post('parent') == '' ? 0 : $this->input->post('parent'));
                $data['controler']      = (@$this->input->post('controler') == '' ? NULL : $this->input->post('controler'));
                $data['folder']         = (@$this->input->post('folder') == '' ? 'app' : $this->input->post('folder'));
                $data['link']           = (@$this->input->post('link') == '' ? NULL : $this->input->post('link'));
                $data['description']    = (@$this->input->post('description') == '' ? NULL : $this->input->post('description'));
                
                $data['updated_date']   = date("Y-m-d H:i:s");
                $data['updated_by']     = h_session('USERNAME');
                $result = $this->m_global->update('sys_menu', $data, ['id' => $id]);

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
            $res = $this->m_global->update('sys_menu', $data, ['id' => $id]);
            if($val == 't'){
                $res['message'] = 'Active Success!';
            }else{
                $res['message'] = 'Delete Success!';
            }
            
            echo json_encode($res);
        }
    }


    public function select_parent()
    {
        if(isset($_REQUEST['q'])){
            $q = strtolower($_REQUEST['q']);
            $id_bsc = @$_REQUEST['id_bsc'];

            $where = "parent = 0";
            if(!empty($_REQUEST['q'])){
                $where .= " AND LOWER(name) LIKE '%".$q."%'";
            }
            $arr = @$this->m_global->getDataAll('sys_menu AS a', NULL,$where,"a.id, a.name");
            // echo $this->db->last_query();exit;    
            $data = [];
            for ($i=0; $i < count($arr); $i++) {
                $data[$i] = ['id' => $arr[$i]->id, 'name' => $arr[$i]->name ];
            }
            echo json_encode(['item' => $data]);
        }else{
            $id = $_REQUEST['id'];
            $where = ['id' => $id];
            $arr = @$this->m_global->getDataAll('sys_menu AS a', NULL,$where,"a.id, a.name",NULL,NULL,0,10);
            $data[] = [ 'id' => @$arr[0]->id,  'name' => @$arr[0]->name ];
            echo json_encode($data);
        }
    }

    public function check_menu()
    {
        $where = NULL;
        $val = strtolower($_REQUEST['val']);
        if(!empty($_REQUEST['val'])){
            $where = "LOWER(name) LIKE '%".$val."%'";
        }
        $check = $this->m_global->countDataAll('sys_menu', NULL, $where);
        if($check > 0){ $status=0; }else{ $status=1; }
        echo json_encode(['status'=>$status]);
    }

}
