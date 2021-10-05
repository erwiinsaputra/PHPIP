<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bsc extends MX_Controller {
    
    private $prefix         = 'bsc';
    private $table_db       = 'm_bsc';
    private $title          = 'Balanced Scorecard (BSC)';
    private $folder         = 'app';
    private $url            = 'app/bsc';

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

        $js['custom']       = ['table_bsc'];
        $this->template->display($this->url.'/v_'.$this->prefix, $data, $js);
    }

    public function table_bsc()
    {    
        // load model view 
        $this->load->model($this->folder.'/'.$this->table_db,$this->table_db);

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
                    // $name = @$row['name'];
                    $name = $this->m_bsc->select($row['name']); $name = str_replace(' AS '.$row['name'], ' ', $name );

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
        $table = $this->table_db." AS a";
        $join  = NULL;

        //select 
        $select = "*";
        // $select_field = [ 'id','nip','nik','customer','no_hp' ];
        // $select = array_unique(array_merge($select_field, $search));
        $select = $this->m_bsc->select($select);

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
            $btn_edit   = '<button title="Edit" id="'.$id.'" class="btn btn-sm btn-primary btn_edit"><i class="fa fa-edit"></i></button>';
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
            $isi['action']              = @$btn_edit.@$btn_delete.@$btn_active.@$btn_delete_permanent;

            $isi['name']                = $rows->name;
            $isi['name_workunit']       = $rows->name_workunit;
            $isi['name_bsc_type']       = $rows->name_bsc_type;
            $isi['name_perspective']     = '- '.str_replace(', ','<br>- ',$rows->name_perspective);
            $isi['code']                = $rows->code;
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

        //workunit
        $data['workunit'] = $this->m_global->getDataAll('m_workunit', null,  ['is_active'=>'t'], '*', null, "name ASC");

        //BSC Type
        $data['bsc_type'] = $this->m_global->getDataAll('m_bsc_type', null,  ['is_active'=>'t'], '*',null, "name ASC");

        $this->template->display_ajax($this->url.'/v_bsc_add', $data);
    }

    public function load_edit() {
        csrf_init();
        $data['url'] = $this->url;
        
        //workunit
        $data['workunit'] = $this->m_global->getDataAll('m_workunit', null,  ['is_active'=>'t'], '*',null, "name ASC");

        //BSC Type
        $data['bsc_type'] = $this->m_global->getDataAll('m_bsc_type', null,  ['is_active'=>'t'], '*',null, "name ASC");

        //get data
        $id = $this->input->post('id');
        $data['data'] = $this->m_global->getDataAll('m_bsc', null, ['id'=>$id], '*')[0];
        $this->template->display_ajax($this->url.'/v_bsc_edit', $data);
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
            $this->form_validation->set_rules('name', 'Bsc', 'trim|required');
            $this->form_validation->set_rules('id_workunit', 'Work Unit', 'trim|required');
            $this->form_validation->set_rules('id_bsc_type', 'Type', 'trim|required');
            $this->form_validation->set_rules('id_perspective', 'Perspective', 'trim|xss_clean|required');
            // $this->form_validation->set_rules('code', 'Code', 'trim|required');
            // $this->form_validation->set_rules('description', 'Description', 'trim|required');
            if ($this->form_validation->run($this)) {

                //insert data
                $data['name']           = @$this->input->post('name');
                $data['id_workunit']    = @$this->input->post('id_workunit');
                $data['id_bsc_type']    = @$this->input->post('id_bsc_type');
                $data['id_perspective'] = str_replace(',',', ',@$this->input->post('id_perspective'));
                $data['code']           = @$this->input->post('code');
                $data['description']    = @$this->input->post('description');
                $data['created_date']   = date("Y-m-d H:i:s");
                $data['created_by']     = h_session('USERNAME');
                $result = $this->m_global->insert('m_bsc', $data);

                //insert ke template strategy map
                $id_bsc = $this->db->insert_id();
                $periode = $this->m_global->getDataAll('m_periode', null,  ['is_active'=>'t'], 'id',null, "id ASC");
                foreach($periode as $row){
                    $data = [];
                    $data['id_bsc']         = $id_bsc;
                    $data['id_periode']     = $row->id;
                    $data['created_date']   = date("Y-m-d H:i:s");
                    $data['created_by']     = h_session('USERNAME');
                    $result = $this->m_global->insert('m_template_strategy_map', $data);
                }
                
                //result
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
            $this->form_validation->set_rules('name', 'Bsc', 'trim|required');
            $this->form_validation->set_rules('id_workunit', 'Work Unit', 'trim|required');
            $this->form_validation->set_rules('id_bsc_type', 'Type', 'trim|required');
            $this->form_validation->set_rules('id_perspective', 'Perspective', 'trim|xss_clean|required');
            // $this->form_validation->set_rules('code', 'Code', 'trim|required');
            // $this->form_validation->set_rules('description', 'Description', 'trim|required');
            if ($this->form_validation->run($this)) {

                //update data
                $id = $this->input->post('id');
                $data['name']           = @$this->input->post('name');
                $data['id_workunit']    = @$this->input->post('id_workunit');
                $data['id_bsc_type']    = @$this->input->post('id_bsc_type');
                $data['id_perspective'] = str_replace(',',', ',@$this->input->post('id_perspective'));
                $data['code']           = @$this->input->post('code');
                $data['description']    = @$this->input->post('description');
                $data['updated_date']   = date("Y-m-d H:i:s");
                $data['updated_by']     = h_session('USERNAME');
                $result = $this->m_global->update('m_bsc', $data, ['id' => $id]);

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
            $data['is_active'] = $this->input->post('val');
            $res = $this->m_global->update('m_bsc', $data, ['id' => $id]);
            $res = $this->m_global->update('m_template_strategy_map', $data, ['id_bsc' => $id]);
            $res['message'] = 'Delete Success!';
            echo json_encode($res);
        }
    }

    public function select_perspective($id=NULL)
    {
        if(is_null($id)){
            $q          = $_GET['q'];
            $parent     = $this->m_global->getDataAll('m_perspective', NULL,['name LIKE' => '%'.$q.'%'],'id, name',NULL,NULL,0,10);
            $data       = [];
            for ($i=0; $i < count($parent); $i++) {
                $data[$i] = ['id' => $parent[$i]->id, 'name' => $parent[$i]->name];
            }
            echo json_encode(['item' => $data]);
        }else{
            $id         = str_replace('-', ',', $id);
            $where_e    = " id IN (".$id.")";
            $parent     = $this->m_global->getDataAll('m_perspective', NULL, NULL, '*', $where_e);
            $data       = [];
            for ($i=0; $i < count($parent); $i++) {
                $data[$i] = ['id' => $parent[$i]->id, 'name' => $parent[$i]->name];
            }
            echo json_encode($data);
        }
    }

}
