<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role extends MX_Controller {
    private $prefix         = 'role';
    private $table_db       = 'sys_role';
    private $title          = 'Role';
    private $logTable       = '';
    private $url            = 'admin/role/';
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
        $data['setting']    = $this->setting;
        $data['url']        = $this->url;
        $data['breadcrumb'] = ['Admin' => TRUE, $this->title => $this->setting['url']];
        $js['custom']       = ['table-ajax'];

        $this->template->display('admin/'.$this->prefix.'/'.$this->prefix, $data, $js);
    }

    public function get_table()
    {
        if(@$_REQUEST['customActionType'] == 'group_action'){
            $aChk = [1, 99, 98];
            if(in_array(@$_REQUEST['customActionName'], $aChk)){
                $this->change_status($_REQUEST['customActionName'], ['id IN ' => "(".implode(",", $_REQUEST['id']).")"], count($_REQUEST['id']) );
            }
        }

        $aCari = [
            'role'      => 'ROLE_NAME',
        ];

        $where  = [];
        $whereE = NULL;
        foreach ($aCari as $key => $value) {
            if(isset($_REQUEST[$key]) && $_REQUEST[$key] != '')
            {
                $where[$value.' LIKE '] = '%'.$_REQUEST[$key].'%';
            }
        }

        if(isset($_REQUEST['data-status'])){
            $dataStatus = $_REQUEST['data-status'];
            if($dataStatus != 'all'){
                $where[$this->table_db.'.ROLE_STATUS']  = $dataStatus;
            }
        }

        $join   = NULL;

        $keys = array_keys($aCari);
        @$order = [$aCari[$keys[($_REQUEST['order'][0]['column']-2)]], $_REQUEST['order'][0]['dir']];

        $iTotalRecords  = $this->m_global->countDataAll($this->table_db, $join, $where, $whereE);
        $iDisplayLength = intval($_REQUEST['length']);
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
        $iDisplayStart  = intval($_REQUEST['start']);
        $sEcho = intval($_REQUEST['draw']);

        $records = [];
        $records["data"] = [];

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        $tmpSelect = [
            $this->table_db.'.id',
            $this->table_db.'.ROLE_STATUS'
        ];

        $select = implode(',', array_merge($tmpSelect, $aCari));
        $result = $this->m_global->getDataAll($this->table_db, $join, $where, $select, $whereE, $order, $iDisplayStart, $iDisplayLength);
        $i = 1 + $iDisplayStart;
        foreach ($result as $rows) {
            $records["data"][] = [
                '<span '.($rows->ROLE_STATUS == 99 ? 'class="strike"' : '').'>'.$i.'</span>',

                $rows->ROLE_NAME,

                $this->_records_action($rows->id, $rows->ROLE_STATUS)
            ];
            $i++;
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        echo json_encode($records);
    }



    public function show_add()
    {
        csrf_init();

        $data['setting']    = $this->setting;
        $data['breadcrumb'] = ['Admin' => TRUE, $this->title => $this->setting['url'], 'Tambah' => $this->setting['url'].$this->setting['method']];
        $js['custom']       = ['form-validation'];

        //menu
        $tmp  = $this->m_global->getDataAll('m_menu', NULL, ['MENU_STATUS' => '1', 'MENU_PARENT' => '0'], 'MENU_ID, MENU_NAME, MENU_STATUS', null, ['MENU_ORDER', 'asc']);
        foreach($tmp as $row){
            $menu[] = get_menu_rekursif($row, $row->MENU_ID, null);
        }
        $data['data_menu'] = $menu;

        $this->template->display('admin/'.$this->prefix.'/'.$this->prefix.'_add', $data, $js);
    }

    public function show_edit($id)
    {
        csrf_init();

        $record             = $this->m_global->getDataAll($this->table_db, NULL, [$this->table_db.'.id' => $id], 'ROLE_NAME, ROLE_MENU, id')[0];
        $data['data']       = $record;
        $data['arr_menu']   = explode(',', $record->ROLE_MENU);

        $data['id']         = $id;
        $data['setting']    = $this->setting;
        $data['breadcrumb'] = ['Admin' => TRUE, $this->title => $this->setting['url'], 'Ubah' => TRUE, $record->ROLE_NAME => $this->setting['url'].$this->setting['method'].'/'.$id];
        $js['custom']       = ['form-validation'];

        //menu
        $tmp  = $this->m_global->getDataAll('m_menu', NULL, ['MENU_STATUS' => '1', 'MENU_PARENT' => '0'], 'MENU_ID, MENU_NAME', null, ['MENU_ORDER', 'asc']);
        foreach($tmp as $row){
            $menu[] = get_menu_rekursif($row, $row->MENU_ID, null);
        }
        $data['data_menu'] = $menu;

        

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

            $this->form_validation->set_rules('name',           'Nama',             'required|trim|xss_clean');

            if ($this->form_validation->run($this))
            {
                $this->db->trans_start();

                $data               = [
                    'ROLE_NAME'          => $this->input->post('name'),
                    'ROLE_MENU'          => implode(',', $this->input->post('chk')),
                    'ROLE_STATUS'        => '1',
                    'ROLE_CREATED_BY'    => @$this->session->userdata('USER')['USER_ID'],
                ];

                $result = $this->m_global->insert($this->table_db, $data);

                if ($result['status'])
                {
                    $res['status'] = 1;
                    $res['message'] = 'Berhasil menambahkan Role dengan Nama <strong>'.$this->input->post('name').'</strong>';
                    echo json_encode($res);
                }
                else
                {
                    $res['status'] = 0;
                    $res['message'] = 'Gagal Menambahkan Role dengan Nama <strong>'.$this->input->post('name').'</strong>';
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

    public function edit($id)
    {
        $input['ex_csrf_token'] = @$this->input->post('ex_csrf_token');

        $res    = [];
        if (csrf_get_token() != $input['ex_csrf_token']){
            $res['status']  = 2;
            $res['message'] = $this->csrf_message;

            echo json_encode($res);
        }else{
            
            $this->form_validation->set_rules('name',           'Nama',             'required|trim|xss_clean');

            if ($this->form_validation->run($this))
            {
                $this->db->trans_start();

                //validasi cek jika kosong
                $chk = @$this->input->post('chk');
                if($chk == ''){ 
                    $chk = 0;
                }else{
                    $chk = implode(',', $this->input->post('chk'));
                }

                $data               = [
                    'ROLE_NAME'          => $this->input->post('name'),
                    'ROLE_MENU'          => $chk,
                ];

                $result = $this->m_global->update($this->table_db, $data, ['id' => $id]);
                if ($result)
                {
                    $res['status'] = 1;
                    $res['message'] = 'Berhasil Mengubah Role dengan Nama <strong>'.$this->input->post('name').'</strong>';
                    echo json_encode($res);
                } else
                {
                    $res['status'] = 0;
                    $res['message'] = 'Gagal Mengubah Role dengan Nama <strong>'.$this->input->post('name').'</strong>';
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

    public function change_status($status, $where)
    {
        $data['ROLE_STATUS']  = $status;
        if($status == '98'){
            $this->m_global->delete($this->table_db, NULL, $where);
        }else{
            $this->m_global->update($this->table_db, $data, NULL, $where);
        }

    }

    public function change_status_by($id, $status, $stat = FALSE)
    {
        if($stat){
            $result = $this->m_global->delete($this->table_db, ['id' => $id]);
            if($result){
                $data['status'] = 1;
            }else{
                $data['status'] = 0;
            }

            echo json_encode($data);

        }else{
            $result = $this->m_global->update($this->table_db, ['ROLE_STATUS' => $status], ['id' => $id]);
            if($result){
                $data['status'] = 1;
            }else{
                $data['status'] = 0;
            }

            echo json_encode($data);
        }
    }

    


    private function _records_action($id, $status)
    {
        $role   = $this->getUserRole();

        $html   = '';
        $html .=
            ($status == 99 ? '<a data-original-title="Restore Data ' . $this->setting['title'] . '" href="' . site_url($this->setting['url']  . 'change_status_by/' . $id . '/1') . '" class="tooltips btn btn-sm red-sunglo" onClick="return f_status(1, this, event)"><i title="Aktif" class="fa fa-trash-o"></i></a>' : '') .
            '<a data-original-title="Edit Data ' . $this->setting['title'] . '" href="' . site_url($this->setting['url']  . 'show_edit/' . $id) . '" class="btn btn-sm blue-madison ajaxify tooltips"><i class="fa fa-edit"></i></a>
            <a data-original-title="'.($status == 99 ? 'Delete Permanently Data ' : 'Deactive Data '). $this->setting['title'] . '" href="' . site_url($this->setting['url']  . 'change_status_by/' . $id . '/99/' . ($status == 99 ? '/true' : '')) . '" class="btn btn-sm red-sunglo tooltips" onClick="return f_status(2, this, event)"><i class="fa fa-times"></i></a>';

        return $html;
    }
}
