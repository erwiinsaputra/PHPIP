<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Strategic_theme extends MX_Controller {
    
    private $prefix         = 'strategic_theme';
    private $table_db       = 'm_strategic_theme';
    private $title          = 'Strategic Theme';
    private $url            = 'app/strategic_theme';

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

        $js['custom']       = ['table_strategic_theme'];
        $this->template->display($this->url.'/v_'.$this->prefix, $data, $js);
    }

    public function table_strategic_theme()
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

            //isi table disini
            $isi['no']                  = $i;
            $isi['id']                  = $rows->id;
            $isi['is_active']           = ($rows->is_active == 't' ? 'Active' : 'Non Active');
            $isi['created_date']        = h_format_date($rows->created_date,'d F Y');
            $isi['created_by']          = $rows->created_by;
            $isi['updated_date']        = h_format_date($rows->updated_date,'d F Y');
            $isi['updated_by']          = $rows->updated_by;
            $isi['action']              = @$btn_edit.@$btn_delete.@$btn_active.@$btn_delete_permanent;

            $isi['name']                = $rows->name;
            $isi['code']                = $rows->code;
            $isi['description']         = h_read_more($rows->description,20);
            $isi['order']               = $rows->order;
            $isi['icon']                = $icon;

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
        $this->template->display_ajax($this->url.'/v_strategic_theme_add', $data);
    }

    public function load_edit() {
        csrf_init();
        $data['url'] = $this->url;
        
        //get data
        $id = $this->input->post('id');
        $data['data'] = @$this->m_global->getDataAll('m_strategic_theme', null, ['id'=>$id], '*')[0];
        $this->template->display_ajax($this->url.'/v_strategic_theme_edit', $data);
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
            $this->form_validation->set_rules('name', 'Strategic_theme', 'trim|required');
            $this->form_validation->set_rules('code', 'Code', 'trim|required');
            $this->form_validation->set_rules('description', 'Description', 'trim|required');
            $this->form_validation->set_rules('order', 'Description', 'trim|required');
            if ($this->form_validation->run($this)) {

                //insert data
                $data['name']           = @$this->input->post('name');
                $data['code']           = @$this->input->post('code');
                $data['description']    = @$this->input->post('description');
                $data['order']          = @$this->input->post('order');
                $data['icon']           = @$this->input->post('icon');
                $data['created_date']   = date("Y-m-d H:i:s");
                $data['created_by']     = h_session('USERNAME');
                $result = $this->m_global->insert('m_strategic_theme', $data);

                //id data
                $id = $this->db->insert_id();
                
                //upload file
                $date_now                   = date('Y-m-d H:i:s');
                $type_file                  = h_file_type($_FILES['file_upload']['name']);
                //upload
                $folder                     = './public/files/icon_strategic_theme/';
                $file_name                  = 'icon_'.$id;
                $input_name                 = array_keys($_FILES)[0];
                // $file_type                  = 'png|PNG';
                $file_type                  = '*';
                $upload = h_upload($folder,$file_name,$input_name,$file_type);


                //update file
                $data2['icon'] = $file_name.'.png';
                $result = $this->m_global->update('m_strategic_theme', $data2, ['id' => $id]);

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
            $this->form_validation->set_rules('name', 'Strategic_theme', 'trim|required');
            $this->form_validation->set_rules('code', 'Code', 'trim|required');
            $this->form_validation->set_rules('description', 'Description', 'trim|required');
            $this->form_validation->set_rules('order', 'Description', 'trim|required');
            if ($this->form_validation->run($this)) {

                $id = $this->input->post('id');

                //delete file lama
                $file_old = @$this->input->post('file_old');
                unlink(FCPATH."public/files/icon_strategic_theme/". $file_old);
                
                //upload file baru
                $date_now                   = date('Y-m-d H:i:s');
                $type_file                  = h_file_type($_FILES['file_upload']['name']);
                //upload
                $folder                     = './public/files/icon_strategic_theme/';
                $file_name                  = 'icon_'.$id;
                $input_name                 = array_keys($_FILES)[0];
                // $file_type                  = 'png|PNG';
                $file_type                  = '*';
                $upload = h_upload($folder,$file_name,$input_name,$file_type);

                //update data
                $data['name']           = @$this->input->post('name');
                $data['code']           = @$this->input->post('code');
                $data['description']    = @$this->input->post('description');
                $data['order']          = @$this->input->post('order');
                $data['icon']           = $file_name.'.png';
                $data['updated_date']   = date("Y-m-d H:i:s");
                $data['updated_by']     = h_session('USERNAME');
                $result = $this->m_global->update('m_strategic_theme', $data, ['id' => $id]);

                
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
            $data['is_active'] =  @$this->input->post('val');
            $res = $this->m_global->update('m_strategic_theme', $data, ['id' => $id]);
            $res['message'] = 'Delete Success!';
            echo json_encode($res);
        }
    }


    public function select_strategic_result($id=NULL)
    {
        if(is_null($id)){
            $q          = $_GET['q'];
            $parent     = $this->m_global->getDataAll('m_strategic_result', NULL,['name LIKE' => '%'.$q.'%'],'id, name, code',NULL,NULL,0,10);
            $data       = [];
            for ($i=0; $i < count($parent); $i++) {
                $data[$i] = ['id' => $parent[$i]->id, 'name' => $parent[$i]->name, 'code' => $parent[$i]->code];
            }
            echo json_encode(['item' => $data]);
        }else{
            $id         = str_replace('-', ',', $id);
            $where_e    = " id IN (".$id.")";
            $parent     = $this->m_global->getDataAll('m_strategic_result', NULL, NULL, 'id, name, code', $where_e);
            $data       = [];
            for ($i=0; $i < count($parent); $i++) {
                $data[$i] = ['id' => $parent[$i]->id, 'name' => $parent[$i]->name, 'code' => $parent[$i]->code];
            }
            echo json_encode($data);
        }
    }

}
