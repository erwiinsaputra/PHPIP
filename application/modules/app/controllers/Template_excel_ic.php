<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Template_excel_ic extends MX_Controller {
    
    private $prefix         = 'template_excel_ic';
    private $table_db       = 'm_template_excel_ic';
    private $title          = 'Template Strategy Map';
    private $url            = 'app/template_excel_ic';

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

        $js['custom']       = ['table_template_excel_ic'];
        $this->template->display($this->url.'/v_'.$this->prefix, $data, $js);
    }

    public function table_template_excel_ic()
    {    
        // load model view 
        $this->load->model('app/m_template_excel_ic','m_template_excel_ic');

        //search default
        $where  = [];
        $whereE = " is_active = 't' ";

        //cek is active
        if(isset($_REQUEST['filter'])){
            foreach ($_REQUEST['filter'] as $row) {
                if(@$row['name'] == 'is_active'){ $whereE = " 0=0 "; }
            }
        }


        //filtering
        $search = [];
        if(isset($_REQUEST['filter'])){
            foreach ($_REQUEST['filter'] as $row) {

                //default parameter
                if(@$row['name'] != ''){

                    $val= $row['value']; $tipe = $row['tipe']; $search[] = $row['name']; 
                    $name = $this->m_template_excel_ic->select($row['name']); $name = str_replace(' AS '.$row['name'], ' ', $name );

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
            $order = ['id','ASC'];
        }
        // echo '<pre>';print_r($order);exit;

        
        //table dan join
        $table = "m_template_excel_ic AS a";
        $join = NULL;
        $select = "*";
        // $select_field = [ 'id','nip','nik','customer','no_hp' ];
        // $select = array_unique(array_merge($select_field, $search));
        $select = $this->m_template_excel_ic->select($select);

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
            $btn_delete     = '<button title="Delete" id="'.$id.'" val="f" class="btn btn-sm  btn-danger btn_delete"><i class="fa fa-remove"></i></button>';
            if($rows->is_active == 'f'){
                $btn_delete = '<button title="Active" id="'.$id.'" val="t" class="btn btn-sm  btn-danger btn_delete"><i class="fa fa-check"></i></button>';
            }
            $btn_edit       = '<button title="Edit" id="'.$id.'" class="btn btn-sm btn-primary btn_edit"><i class="fa fa-edit"></i></button>';
            if($rows->file_name == ''){
                $file_name = 'No Image';
            }else{
                $file_name = '<a target="_blank" href="'.base_url('public/files/template_excel_ic/').$rows->file_name.'"><i class="fa fa-file-image-o" style="font-size:2em;margin-top:8px;"></i></a>';
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
            $isi['file_name']           = $file_name;

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

        $this->template->display_ajax($this->url.'/v_template_excel_ic_add', $data);
    }

    public function load_edit() {
        csrf_init();
        $data['url'] = $this->url;
        
        //get data
        $id = $this->input->post('id');
        $data['data'] = $this->m_global->getDataAll('m_template_excel_ic', null, ['id'=>$id], '*')[0];
        
        $this->template->display_ajax($this->url.'/v_template_excel_ic_edit', $data);
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
            $this->form_validation->set_rules('name', 'Name', 'trim|xss_clean|required');
            if ($this->form_validation->run($this)) {

                //param
                $name = @$this->input->post('name');

                //upload file
                $date_now                   = date('Y-m-d H:i:s');
                $type_file                  = h_file_type($_FILES['file_upload']['name']);
                //upload
                $folder                     = './public/files/template_excel_ic/';
                $file_name                  = str_replace(' ','_',strtolower($name));
                $input_name                 = array_keys($_FILES)[0];
                $file_type                  = 'xls|xlsx';
                $upload = h_upload($folder,$file_name,$input_name,$file_type);

                //create data
                if($upload == TRUE){
                    //insert data
                    $data['name']           = $name;
                    $data['file_name']      = $file_name.'.'.$type_file;
                    $data['created_date']   = date("Y-m-d H:i:s");
                    $data['created_by']     = h_session('USERNAME');

                    $result = $this->m_global->insert('m_template_excel_ic', $data);
                    // echo '<pre>';print_r($data);exit;
                    // echo $this->db->last_query();exit;
                    $res['status']  = ($result['status'] ? '1':'0');
                    $res['message'] = 'Successfully Save Data!';
                }else{
                    $res['status']  = 0;
                    $res['message'] = 'Error Upload Data!';
                }
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
            $this->form_validation->set_rules('name', 'Name', 'trim|xss_clean|required');
            if ($this->form_validation->run($this)) {

                //param
                $id = @$this->input->post('id');
                $name = @$this->input->post('name');

                //upload file
                $date_now                   = date('Y-m-d H:i:s');
                $type_file                  = h_file_type($_FILES['file_upload']['name']);
                //upload
                $folder                     = './public/files/template_excel_ic/';
                $file_name                  = str_replace(' ','_',strtolower($name));
                $input_name                 = array_keys($_FILES)[0];
                $file_type                  = 'xls|xlsx'; 
                $upload = h_upload($folder,$file_name,$input_name,$file_type);

                //update data
                if($upload == TRUE){
                    $data = array(
                        'name'             => $name,
                        'file_name'        => $file_name.'.'.$type_file,
                        'updated_by'       => h_session('USERNAME'),
                        'updated_date'     => date("Y-m-d H:i:s")
                    );
                    $result = $this->db->update('m_template_excel_ic', $data, ['id' => $id]);
                    $res['status']  = ($result ? '1':'0');
                    $res['message'] = 'Successfully Save Data!';
                }else{
                    $res['status']  = 0;
                    $res['message'] = 'Error Upload Data!';
                }
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
            $res = $this->m_global->update('m_template_excel_ic', $data, ['id' => $id]);
            if($val == 't'){
                $res['message'] = 'Active Success!';
            }else{
                $res['message'] = 'Delete Success!';
            }
            
            echo json_encode($res);
        }
    }

}
