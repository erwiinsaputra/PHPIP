<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends MX_Controller {
    private $prefix         = 'menu';
    private $table_db       = 'sys_menu';
    private $title          = 'Menu';
    private $logTable       = '';
    private $url            = 'admin/menu/';
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
        $js['custom']       = ['table-ajax'];

        $data['index_action']   = $this->_index_action();
        $data['table_action']   = $this->_table_action();

        $this->template->display('admin/'.$this->prefix.'/'.$this->prefix, $data, $js);
    }

    public function get_table()
    {
        $this->table_db = 'v_menu';

        if(@$_REQUEST['customActionType'] == 'group_action'){
            $aChk = [1, 99, 98];
            if(in_array(@$_REQUEST['customActionName'], $aChk)){
                $this->change_status($_REQUEST['customActionName'], ['ID_MENU IN ' => "(".implode(",", $_REQUEST['id']).")"], count($_REQUEST['id']) );
            }
        }

        $aCari = [
            'menu'          => 'MENU',
            'parent'        => 'MENU2',
            'module'        => 'MODULE',
            'controller'    => 'CONTROLLER',
            'method'        => 'METHOD',
        ];

        $where  = null;
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
                $where[$this->table_db.'.IS_ACTIVE']  = $dataStatus;
            }
        }

        $join = null;
        

        $keys = array_keys($aCari);
        @$order = 'WEIGHT ASC';

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
            $this->table_db.'.ID_MENU',
            $this->table_db.'.IS_ACTIVE'
        ];

        $select = implode(',', array_merge($tmpSelect, $aCari));
        $result = $this->m_global->getDataAll($this->table_db, $join, $where, $select, $whereE, $order, $iDisplayStart, $iDisplayLength);
        $i = 1 + $iDisplayStart;
        foreach ($result as $rows) {
            $records["data"][] = [
                '<input type="checkbox" name="id[]" value="'.$rows->ID_MENU.'">',
                '<span '.($rows->IS_ACTIVE == 99 ? 'class="strike"' : '').'>'.$i.'</span>',

                $rows->MENU,
                $rows->MENU2,
                $rows->MODULE,
                $rows->CONTROLLER,
                $rows->METHOD,

                $this->_records_action($rows->ID_MENU, $rows->IS_ACTIVE)
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
        $data['breadcrumb'] = ['Master' => TRUE, $this->title => $this->setting['url'], 'Tambah' => $this->setting['url'].$this->setting['method']];
        $js['custom']       = ['form-validation'];

        $this->template->display('admin/'.$this->prefix.'/'.$this->prefix.'_add', $data, $js);
    }

    public function show_edit($id)
    {
        echo "masih belum";exit;
        csrf_init();
        $record             = $this->m_global->getDataAll($this->table_db, NULL, [$this->table_db.'.ID_MENU' => $id], '*')[0];
        $data['item']       = $record;

        $data['id']         = $id;
        $data['setting']    = $this->setting;
        $data['breadcrumb'] = ['Master' => TRUE, $this->title => $this->setting['url'], 'Ubah' => TRUE, $record->MENU => $this->setting['url'].$this->setting['method'].'/'.$id];
        $js['custom']       = ['form-validation'];
        $this->template->display('admin/'.$this->prefix.'/'.$this->prefix.'_edit', $data, $js);
    }

    public function add()
    {
        $post = $this->input->post();
        $input['ex_csrf_token'] = @$this->input->post('ex_csrf_token');

        $res    = [];
        if (csrf_get_token() != $input['ex_csrf_token']){
            $res['status']  = 2;
            $res['message'] = $this->csrf_message;

            echo json_encode($res);
        }else{
            $this->form_validation->set_rules('menu',           'menu',             'required');
            $this->form_validation->set_rules('icon',           'icon',             'required');
            if($post['jenis_parent'] == 'YES'){
                if($post['jenis_module'] == 'YES'){
                    $this->form_validation->set_rules('module',      'module',          'required');
                }else{
                    $this->form_validation->set_rules('controler',    'controler',      'required');
                }
            }else{
                $this->form_validation->set_rules('parent',      'parent',          'required');
                $this->form_validation->set_rules('controler',    'controler',      'required');
            }
            
            
            if ($this->form_validation->run($this))
            {

                $data = [];
                $data['menu']   = $post['menu'];
                $data['icon']   = $post['icon'];
                if($post['jenis_parent'] == 'YES'){
                    if($post['jenis_module'] == 'YES'){
                        $data['parent']     = $post['module'];
                    }else{
                        $data['parent']     = $post['controler'];
                    }
                }else{
                    $data['parent']     = $post['parent'];
                    $data['controler']  = $post['controler'];
                }
                $data['weight']   = ''; //masih belum
                $data['method']   = 'index/'.$post['controler'];

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
            $this->form_validation->set_rules('deskripsi',      'Handphone',        'required|trim|xss_clean');
            $this->form_validation->set_rules('investor',       'Investor',         'trim|xss_clean');
            $this->form_validation->set_rules('bkpm',           'BKPM',             'trim|xss_clean');

            if ($this->form_validation->run($this))
            {
                $this->db->trans_start();

                $data               = [
                    'ROLE'          => $this->input->post('name'),
                    'DESCRIPTION'   => $this->input->post('deskripsi'),
                    'IS_INVESTOR'   => ($this->input->post('investor') == 1 ? 1 : 0),
                    'IS_BKPM'       => ($this->input->post('bkpm') == 1 ? 1 : 0),
                ];

                $result = $this->m_global->update($this->table_db, $data, ['ID_MENU' => $id]);
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
        $data['IS_ACTIVE']  = $status;
        if($status == '98'){
            $this->m_global->delete($this->table_db, NULL, $where);
        }else{
            $this->m_global->update($this->table_db, $data, NULL, $where);
        }

    }

    public function change_status_by($id, $status, $stat = FALSE)
    {
        if($stat){
            $result = $this->m_global->delete($this->table_db, ['ID_MENU' => $id]);
            if($result){
                $data['status'] = 1;
            }else{
                $data['status'] = 0;
            }

            echo json_encode($data);

        }else{
            $result = $this->m_global->update($this->table_db, ['IS_ACTIVE' => $status], ['ID_MENU' => $id]);
            if($result){
                $data['status'] = 1;
            }else{
                $data['status'] = 0;
            }

            echo json_encode($data);
        }
    }

    

    private function _index_action()
    {
        $role   = $this->getUserRole();

        $html   = '';
//        if($role == 'super_admin'){
            $html .= '<select class="bs-select form-control input-small-x input-sm input-inline" data-style="btn-danger" name="custom_status">
                        <option value="all">Semua</option>
                        <option value="1">Aktif</option>
                        <option value="99">Non-Aktif</option>
                    </select>
                    <a href="'.site_url($this->setting['url'].'show_add').'" class="ajaxify btn green-meadow tooltips" data-original-title="Tambah Data '.$this->setting['pagetitle'].'" data-placement="top" data-container="body"><i class="fa fa-plus"></i></a>
                    <a href="javascript:;" onclick="reloadTable()" class="btn purple-plum tooltips" data-original-title="Reload" data-placement="top" data-container="body"><i class="fa fa-refresh"></i></a>
                    <a href="javascript:;" class="btn btn-sm yellow-crusta btn_show_filter tooltips" data-original-title="Cari" data-placement="top" data-container="body"><i class="fa fa-search"></i></a>
                ';
//        }

        return $html;
    }

    private function _table_action()
    {
        $role   = $this->getUserRole();

        $html   = '';
//        if($role == 'super_admin'){
            $html .= '
                <select class="table-group-action-input form-control input-inline input-small input-sm">
                    <option value="">Pilih</option>
                    <option value="99">Hapus</option>
                    <option value="1">Aktif</option>
                    <option value="98">Hapus Permanen</option>
                </select>
                <button data-original-title="Submit" class="tooltips btn btn-sm yellow table-group-action-submit"><i class="fa fa-check"></i></button>';
//        }

        return $html;
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


    
    public function get_parent($id=NULL)
    {
        if(is_null($id)){
            $q          = $_GET['q'];
            $parent     = $this->m_global->getDataAll($this->table_db, NULL,['CONTROLLER'=> 'true', 'MODULE !=' => '', 'MENU LIKE' => '%'.$q.'%'],'ID_MENU, MENU',NULL,NULL,0,10);
            $data = [];
            for ($i=0; $i < count($parent); $i++) {
                $data[$i] = ['id' => $parent[$i]->ID_MENU, 'name' => $parent[$i]->MENU];
            }
            echo json_encode(['item' => $data]);
        }else{

            $parent     = $this->m_global->getDataAll($this->table_db, NULL, ['ID_MENU' => $id]);
            $tmp        = [
                            ['id' => $parent[0]->ID_MENU, 'name' => $parent[0]->MENU]
                        ];
            echo json_encode($tmp);
        }
    }
}
