<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MX_Controller {
    private $prefix         = 'user';
    private $table_db       = 'sys_user';
    private $title          = 'User';
    private $logTable       = '';
    private $url            = 'admin/user/';
    private $setting;

    function __construct() {
        parent::__construct();
        $this->middleware('guest', 'forbidden');
        $this->setting  = [
            'instance'  => $this->prefix,
            'url'       => $this->url,
            'method'    => $this->router->method,
            'title'     => $this->title,
            'pagetitle' => $this->title
        ];
    }

    public function index()
    {
        $data['setting']     = $this->setting;
        $data['breadcrumb'] = ['Master' => TRUE, $this->title => $this->setting['url']];
        $js['custom']       = ['table_user'];

        $this->template->display('admin/'.$this->prefix.'/'.$this->prefix, $data, $js);
    }

    public function show_add()
    {
        csrf_init();

        $data['setting']    = $this->setting;
        $data['breadcrumb'] = ['Master' => TRUE, $this->title => $this->setting['url'], 'Tambah' => $this->setting['url'].$this->setting['method']];
        $js['custom']       = ['form-validation'];

        $this->template->display('admin/'.$this->prefix.'/'.$this->prefix.'_add', $data, $js);
    }

    public function show_edit($id)
    {
        csrf_init();

        $record             = $this->m_global->getDataAll($this->table_db, NULL, [$this->table_db.'.USER_ID' => $id], '*')[0];
        $data['data']       = $record;

        $data['id']         = $id;
        $data['setting']    = $this->setting;
        $data['breadcrumb'] = ['Master' => TRUE, $this->title => $this->setting['url'], 'Ubah' => TRUE, $record->USER_NAME => $this->setting['url'].$this->setting['method'].'/'.$id];
        $js['custom']       = ['form-validation'];

        $data['role']       = $this->m_global->getDataAll('m_role', NULL, ['ROLE_STATUS' => '1'], 'ROLE_ID as ID, ROLE_NAME');
        $this->template->display('admin/'.$this->prefix.'/'.$this->prefix.'_edit', $data, $js);
    }

    public function add()
    {
        $input['ex_csrf_token'] = @$this->input->post('ex_csrf_token');

        $res    = [];
        if (csrf_get_token() != $input['ex_csrf_token']){
            $res['status']  = 2;
            $res['message'] = $this->csrf_message;

            echo json_encode($res);
        }else{
            $this->form_validation->set_rules('USER_USERNAME',            'USER_USERNAME',             'required|trim|xss_clean');
            $this->form_validation->set_rules('USER_ROLE_ID',       'USER_ROLE_ID',         'required|trim|xss_clean|is_unique['.$this->table_db.'.USER_NAME]');
            $this->form_validation->set_rules('USER_REGION_ID',     'USER_REGION_ID',         'required|trim|xss_clean');
            $this->form_validation->set_rules('USER_NAME',      'USER_NAME',             'required|trim|xss_clean');
            $this->form_validation->set_rules('USER_CUS_ID',        'USER_CUS_ID',            'required|trim|xss_clean|is_unique['.$this->table_db.'.USER_CUS_ID]');
         

            if ($this->form_validation->run($this))
            {
                $this->db->trans_start();
                $data               = [
                    'USER_USERNAME'      => $this->input->post('USER_USERNAME'),
                    'USER_ROLE_ID'  => $this->input->post('USER_ROLE_ID'),
                    'USER_REGION_ID'  => $this->input->post('USER_REGION_ID'),
                    'USER_NAME'   => $this->input->post('USER_NAME'),
                    'USER_CUS_ID'     => $this->input->post('USER_CUS_ID'),
                    'USER_USERNAME'     => $this->input->post('USER_USERNAME'),
                    'USER_IS_ACTIVE' => '1',
                ];


                //upload file
                $upload = false;
                if(!empty($_FILES['gambar'])){
                    $folder = "./public/files/userimage";
                    if( is_dir($folder) === false ){
                        mkdir($folder, 0777, true);
                        chmod($folder, 0777);
                    }
                    $config                     = [];
                    $config['upload_path']      = $folder;
                    $config['allowed_types']    = 'gif|jpg|png';
                    $config['max_size']         = '2048';
                    $config['file_name']        = strtolower(create_slug(time().'-'.$data['USERNAME']));

                    $this->load->library('upload', $config);

                    if (!$this->upload->do_upload('gambar')){
                        $res['status']  = 0;
                        $res['message'] = 'File harus gambar dan size maksimal adalah '.$config['max_size'].'Kb';
                        echo json_encode($res);
                        die();
                    }else{
                        $uploadData         = $this->upload->data();
                        $upload = true;
                        $data['USER_PHOTO']  = strtolower($config['file_name'].$uploadData['file_ext']);
                    }
                }

                $result = $this->m_global->insert($this->table_db, $data);
                if ($result['status'])
                {   
                    $res['status'] = 1;
                    $res['message'] = 'Berhasil menambahkan User dengan Nama <strong>'.$this->input->post('name').'</strong>';
                    if($send == false){
                        $res['message'] .= '</br> Gagal mengirimkan Email!';
                    }
                    echo json_encode($res);
                } else
                {
                    if($upload) {
                        unlink($uploadData['full_path']);
                    }
                    $res['status'] = 0;
                    $res['message'] = 'Gagal Menambahkan User dengan Nama <strong>'.$this->input->post('name').'</strong>';
                    echo json_encode($res);
                }

                $this->db->trans_complete();
            }else{
                $res['status'] = 3;
                $str                = ['<p>', '</p>'];
                $str_replace        = ['<li>', '</li>'];
                $res['message']    = str_replace($str, $str_replace, validation_errors());
                echo json_encode($res);
            }
        }
    }

   /* public function edit($id)
    {
        $input['ex_csrf_token'] = @$this->input->post('ex_csrf_token');

        $res    = [];
        if (csrf_get_token() != $input['ex_csrf_token']){
            $res['status']  = 2;
            $res['message'] = $this->csrf_message;

            echo json_encode($res);
        }else{
            $this->form_validation->set_rules('name',           'Nama',             'required|trim|xss_clean');
            $this->form_validation->set_rules('username',       'Username',         'required|trim|xss_clean');
            $this->form_validation->set_rules('role',           'Role',             'required|trim|xss_clean');

            if ($this->form_validation->run($this))
            {
                $this->db->trans_start();

                $data               = [
                    'USER_NAME'      => $this->input->post('name'),
                    'USER_NAME'  => $this->input->post('username'),
                    'USER_ROLE_ID'   => $this->input->post('role'),
                ];


                $result = $this->m_global->update($this->table_db, $data, ['USER_USERNAME' => $id]);
                if ($result)
                {
                    if(!empty($_FILES['gambar'])){
                        $config                     = [];
                        $config['upload_path']      = './public/files/userimage/';
                        $config['allowed_types']    = 'gif|jpg|png';
                        $config['max_size']         = '2048';
                        $config['file_name']        = strtolower(create_slug(time().'-'.$data['USERNAME']));

                        $this->load->library('upload', $config);

                        if (!$this->upload->do_upload('gambar')){
                            $res['status'] = 0;
                            $res['message'] = 'File harus gambar dan size maksimal adalah '.$config['max_size'].'Kb';
                            echo json_encode($res);
                            die();
                        }else{
                            $uploadData     = $this->upload->data();
                            $image          = strtolower($config['file_name'].$uploadData['file_ext']);

                            $this->m_global->update($this->table_db, ['USER_PHOTO' => $image], ['USER_USERNAME' => $id]);
                            if($record->USER_PHOTO != '' && file_exists('./public/files/userimage/'.$record->USER_PHOTO)){
                                unlink('./public/files/userimage/'.$record->USER_PHOTO);
                            }
                        }
                    }

                    $res['status'] = 1;
                    $res['message'] = 'Berhasil Mengubah User dengan Nama <strong>'.$this->input->post('name').'</strong>';
                    echo json_encode($res);
                } else
                {
                    $res['status'] = 0;
                    $res['message'] = 'Gagal Mengubah User dengan Nama <strong>'.$this->input->post('name').'</strong>';
                    echo json_encode($res);
                }

                $this->db->trans_complete();
            }else{
                $res['status'] = 3;
                $str                = ['<p>', '</p>'];
                $str_replace        = ['<li>', '</li>'];
                $res['message']    = str_replace($str, $str_replace, validation_errors());
                echo json_encode($res);
            }
        }
    }*/

    public function edit($id)
    {
        /*$input['ex_csrf_token'] = @$this->input->post('ex_csrf_token');

        $res    = [];
        if (csrf_get_token() != $input['ex_csrf_token']){
            $res['status']  = 2;
            $res['message'] = $this->csrf_message;

            echo json_encode($res);
        }else{
            
            $this->form_validation->set_rules('apu_name', 'Nama', 'required|trim|xss_clean');

            if ($this->form_validation->run($this))
            {*/
                // echo '<pre>';print_r($this->input->post());exit;
                $data = [
                    'USER_INITIAL' => @$this->input->post('USER_INITIAL'),
                    'USER_ROLE_ID' => $this->input->post('USER_ROLE_ID'),
                    'USER_CUS_COMPANY' => @$this->input->post('USER_CUS_COMPANY'),
                    'USER_USERNAME' => @$this->input->post('USER_USERNAME'),
                    'USER_IS_ACTIVE' => @$this->input->post('USER_IS_ACTIVE'),
                ];

                $result = $this->m_global->update($this->table_db, $data, ['USER_ID' => $id]);

                if ($result)
                {
                    $res['status'] = 1;
                    $res['message'] = 'successfully changed data';
                    echo json_encode($res);
                } else
                {
                    $res['status'] = 0;
                    $res['message'] = 'Failed Edit Data !';
                    echo json_encode($res);
                }

            /*}else{
                $res['status'] = 3;
                $str                = ['<p>', '</p>'];
                $str_replace        = ['<li>', '</li>'];
                $res['message']    = str_replace($str, $str_replace, validation_errors());
                echo json_encode($res);
            }
        }*/
    }

    public function change_status($status, $where)
    {
        $data['USER_IS_ACTIVE']  = $status;
        if($status == '98'){
            $user = $this->m_global->getDataAll($this->table_db, NULL, NULL, 'USER_PHOTO as PHOTO', $where);

            $result = $this->m_global->delete($this->table_db, NULL, $where);
            if($result){
                foreach($user as $row){
                    $image = file_exists('./public/files/userimage/' . $row->PHOTO);

                    if(!empty($image) && $row->PHOTO != ''){
                        unlink('./public/files/userimage/' . $user->PHOTO);
                    }
                }
            }
        }else{
            $this->m_global->update($this->table_db, $data, NULL, $where);
        }

    }


    public function table_user()
    {
        //parameter yg diperlukan
        $table      = "m_user";
        //$addSelect  = [ 'tpm_id','cus_id']; // tambahan field yg diselect 
        $addSelect  = [ '*'];
        $search     = [
                        'USER_USERNAME' => 'USER_USERNAME',
                        'USER_NAME' => 'USER_NAME',
                        'USER_INITIAL' => 'USER_INITIAL',
                        'USER_TITLE' => 'USER_TITLE',
                        'USER_EMAIL' => 'USER_EMAIL',
                        'USER_ROLE_ID' => 'USER_ROLE_ID',
                        'USER_CUS_COMPANY' => 'USER_CUS_COMPANY',
                        'USER_UNIT' => 'USER_UNIT',
                        'USER_IS_ACTIVE' => 'USER_IS_ACTIVE',
                        'USER_CREATED_DATE' => 'USER_CREATED_DATE',
                        'USER_UPDATED_DATE' => 'USER_UPDATED_DATE',
                        'USER_CUS_ID' => 'USER_CUS_ID',/*
                        'USER_TIPE' => 'USER_TIPE',*/
                      ];

        //seting pencarian data
        $where  = [];  $whereE = NULL;
        foreach ($search as $key => $value) {
            if(isset($_REQUEST[$key]) && $_REQUEST[$key] != ''){
                // if ($value == 'tpm_year') {
                //     $where[$value] = $_REQUEST[$key];
                // }
                // else{
                    $where[$value.' LIKE '] = '%'.$_REQUEST[$key].'%';
                // }
            }
        }

        //select penampilan data pada prospect_table_tpm
        if(isset($_REQUEST['data-status'])){
            $dataStatus = $_REQUEST['data-status'];
            if($dataStatus != 'all'){

                $where['USER_IS_ACTIVE']  = $dataStatus;
            }
        }

        //order
        $keys = array_keys($search);
        if(isset($_REQUEST['order'])){
            $order = [ $search[$keys[($_REQUEST['order'][0]['column'])]] , $_REQUEST['order'][0]['dir']];
        }else{
            $order = ['USER_CREATED_DATE','desc'];
        }

        //default datable setting
        $keys           = array_keys($search);
        $join           = NULL;
        $iTotalRecords  = $this->m_global->countDataAll($table, $join, $where, $whereE);
        $iDisplayLength = intval($_REQUEST['length']);
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
        $iDisplayStart  = intval($_REQUEST['start']);
        $sEcho          = intval($_REQUEST['draw']);
        $end            = $iDisplayStart + $iDisplayLength;
        $end            = $end > $iTotalRecords ? $iTotalRecords : $end;
        $select         = implode(',', array_merge($addSelect, $search));
        $result         = $this->m_global->getDataAll($table, $join, $where, $select, $whereE, $order, $iDisplayStart, $iDisplayLength);

         // echo $this->db->last_query();exit;    
        $isi            = [];
        $i              = 1 + $iDisplayStart;

        
        //tampilkan datanya disini
        foreach ($result as $rows) {
            
            //button action
            $btn_edit   = '<a data-original-title="Edit Data" href="'.site_url( $this->url.'show_edit/'. $rows->USER_ID ) . '" class="btn btn-sm blue-madison ajaxify tooltips"><i class="fa fa-edit"></i></a>';
            $btn_del    = '<a data-original-title="Delete Data" href="'.site_url($this->url. 'delete/' . $rows->USER_ID .'/true') . '" class="btn btn-sm red-sunglo tooltips" onClick="return f_status(2, this, event)"><i class="fa fa-times"></i></a>';
            
            //data
            $isi[] = [
                        $i,
                        $rows->USER_USERNAME,
                        $rows->USER_NAME,
                        $rows->USER_INITIAL,
                        $rows->USER_TITLE,
                        $rows->USER_EMAIL,
                        $rows->USER_ROLE_ID,
                        $rows->USER_CUS_COMPANY,
                        $rows->USER_UNIT,
                        $rows->USER_IS_ACTIVE,
                        $rows->USER_CREATED_DATE,
                        $rows->USER_UPDATED_DATE,
                        $rows->USER_CUS_ID,/*
                        $rows->USER_TIPE,*/
                        $btn_edit.'&nbsp;'.$btn_del
                    ];
            $i++;
        }
        $records["data"]            = $isi;
        $records["draw"]            = $sEcho;
        $records["recordsTotal"]    = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
    }


    public function checkUsername($id = null)
    {
        $username = $this->input->post('value', true);

        $where = [
            'USER_NAME'  => $username
        ];
        if(!is_null($id)){
            $where['USER_USERNAME !='] = $id;
        }

        $check = $this->m_global->countDataAll('m_user', NULL, $where);
        if($check > 0){
            $res['status'] = 0;
        }else{
            $res['status'] = 1;
        }

        echo json_encode($res);
    }

    public function checkEmail($id = null)
    {
        $this->load->helper('email');
        $email = $this->input->post('value', true);

        $where = ['USER_EMAIL' => $email];
        if(!is_null($id)){
            $where['USER_USERNAME !='] = $id;
        }

        $check = $this->m_global->countDataAll('m_user', NULL, $where);
        if($check == 0 AND filter_var($email, FILTER_VALIDATE_EMAIL)){
            $res['status'] = 1;
        }else{
            $res['status'] = 0;
        }

        echo json_encode($res);
    }


    public function get_role($id=NULL)
    {
        if(is_null($id)){
            $q          = $_GET['q'];
            $parent     = $this->m_global->getDataAll('m_role', NULL,['ROLE_NAME LIKE' => '%'.$q.'%'],'ROLE_ID, ROLE_NAME',NULL,NULL,0,10);
            $data       = [];
            for ($i=0; $i < count($parent); $i++) {
                $data[$i] = ['id' => $parent[$i]->ROLE_ID, 'name' => $parent[$i]->ROLE_NAME];
            }
            echo json_encode(['item' => $data]);
        }else{
            $parent     = $this->m_global->getDataAll('m_role', NULL, ['ROLE_ID' => $id], 'ROLE_ID as id, ROLE_NAME as name')[0];
            echo json_encode($parent);
        }
    }

    public function select_region($id=NULL)
    {
        if(is_null($id)){
            $q          = $_GET['q'];
            $parent     = $this->m_global->getDataAll('m_region', NULL,['reg_name LIKE' => '%'.$q.'%'], 'reg_id, reg_name',NULL,NULL,0,10);
            $data = [];
            for ($i=0; $i < count($parent); $i++) {
                $data[$i] = ['id' => $parent[$i]->reg_id, 'name' => $parent[$i]->reg_name];
            }
            echo json_encode(['item' => $data]);
        }else{
            $parent     = $this->m_global->getDataAll('m_region', NULL, ['reg_id' => $id]);
            $tmp        = [
                            ['id' => $parent[0]->reg_id, 'name' => $parent[0]->reg_name]
                        ];
            echo json_encode($tmp);
        }
    }

    public function select_customer($id=NULL)
    {
        if(is_null($id)){
            $q          = $_GET['q'];
            $parent     = $this->m_global->getDataAll('m_customer', NULL,['cus_name LIKE' => '%'.$q.'%'], 'cus_id, cus_name',NULL,NULL,0,10);
            $data = [];
            for ($i=0; $i < count($parent); $i++) {
                $data[$i] = ['id' => $parent[$i]->cus_id, 'name' => $parent[$i]->cus_name];
            }
            echo json_encode(['item' => $data]);
        }else{
            $parent     = $this->m_global->getDataAll('m_customer', NULL, ['cus_id' => $id]);
            $tmp        = [
                            ['id' => $parent[0]->cus_id, 'name' => $parent[0]->cus_name]
                        ];
            echo json_encode($tmp);
        }
    }

}
