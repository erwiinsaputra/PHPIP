<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Si extends MX_Controller {
    
    private $prefix         = 'si';
    private $table_db       = 'm_si';
    private $title          = 'Strategic Initiative (SI)';
    private $url            = 'app/si';

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
        //status
        $data['status_si'] = $this->m_global->getDataAll('m_status', null, ['is_active'=>'t', 'type'=>'SI STATUS'], '*', null, '"order" ASC');

        //year
        $data['start_year'] = @$this->m_global->getDataAll('m_periode', null, ['is_active'=>'t'], 'MIN(start_year) AS year')[0]->year;
        $data['end_year'] = @$this->m_global->getDataAll('m_periode', null, ['is_active'=>'t'], 'MAX(end_year) AS year')[0]->year;

        $js['custom']       = ['table_si'];

        $this->template->display($this->url.'/v_'.$this->prefix, $data, $js);
        
    }

    public function table_si()
    {    
        // load model view 
        $this->load->model('app/m_si','m_si');

        //search default
        $where  = [];
        $whereE = " a.\"is_active\" = 't' ";

        //cek is active
        if(isset($_REQUEST['filter'])){
            foreach ($_REQUEST['filter'] as $row) {
                if(@$row['name'] == 'is_active'){ $whereE = " 0=0 "; }
            }
        }

        // ============= cek role =================
        //cek role PIC SI
        if(h_session('ROLE_ID') == '5'){ 
            //status selain draft
            $whereE .= " AND a.status_si != '1' " ;
            //ambil SI yang di definisikan 
            $position_id = h_session('POSITION_ID');
            $nip = h_session('NIP');
            $where2 = " a.nip = '".$nip."'";
            $id_si = @$this->m_global->getDataAll('m_user_ic_si AS a', null, $where2, 'a.id_si')[0]->id_si;
            if($id_si != ''){
                $whereE .= " AND (a.id IN(".$id_si.") OR ".$position_id."::text = ANY (string_to_array(a.pic_si,', ')::text[]))";
            }else{
                $whereE .= " AND ".$position_id."::text = ANY (string_to_array(a.pic_si,', ')::text[]) "; 
            }
        }
        //cek role PIC IC
        if(h_session('ROLE_ID') == '9'){
            //ambil SI yang di definisikan 
            $nip = h_session('NIP');
            $where2 = " a.nip = '".$nip."'";
            $id_si = @$this->m_global->getDataAll('m_user_ic_si AS a', null, $where2, 'a.id_si')[0]->id_si;
            if($id_si != ''){
                $whereE .= " AND a.id IN(".$id_si.")";
            }
        }
        // ========================================

        
        //filtering global
        // echo '<pre>';print_r($_REQUEST);exit;
        $id_bsc = @$_REQUEST['global_id_bsc'];
        if($id_bsc != ''){ $where['a.id_bsc'] = $id_bsc; }

        //cek year dan month
        $year = @$_REQUEST['global_year'];
        if($year != ''){
            $date_start = $year.'-01-01';
            $date_end = $year.'-12-01';
            $whereE .= " AND ((a.\"start_date\" >= '".$date_start."' AND a.\"start_date\" <= '".$date_end."') ";
            $whereE .= " OR (a.\"end_date\" >= '".$date_start."' AND a.\"end_date\" <= '".$date_end."') ";
            $whereE .= " OR (a.\"start_date\" <= '".$date_start."' AND a.\"end_date\" >= '".$date_end."')) ";
        }


        //filtering
        $search = [];
        if(isset($_REQUEST['filter'])){
            foreach ($_REQUEST['filter'] as $row) {

                //default parameter
                if(@$row['name'] != ''){

                    $val= $row['value']; $tipe = $row['tipe']; $search[] = $row['name']; 
                    $name = @$row['name'];
                    $name = $this->m_si->select($row['name']); $name = str_replace(' AS '.$row['name'], ' ', $name );

                    //cek tipe
                    if($val != ''){
                        if($tipe == '1'){
                            $where[' LOWER('.$name.') LIKE '] = '%'.strtolower($val).'%';
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
        $table = "m_si AS a";
        $join  = NULL;

        //select 
        $select = [ 'id','code','name','name_bsc','name_pic_si','name_status_si',
                            'is_active','created_date','created_by','updated_date','updated_by',
                            'start_date','end_date','background_goal','objective_key_result','cek_objective_key_result'
                        ];
        $select = array_unique(array_merge($select, $search));
        $select = $this->m_si->select($select);

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
            $action = '';

            //button delete
            $btn_delete = '<button title="Delete" id="'.$id.'" val="f" class="btn btn-sm  btn-danger btn_delete"><i class="fa fa-remove"></i></button>';
            if($rows->is_active == 'f'){
                $btn_delete = '<button title="Active" id="'.$id.'" val="t" class="btn btn-sm  btn-danger btn_delete"><i class="fa fa-check"></i></button>';
            }

            //jika role admin
            if(in_array( h_session('ROLE_ID'), h_role_admin())){
                if($rows->status_si == '1'){
                    $btn_edit = '<button title="Edit" id="'.$id.'" class="btn btn-sm btn-primary btn_edit"><i class="fa fa-edit"></i></button>';
                    $btn_approval = '<button title="Approval" id="'.$id.'" val="2" class="btn btn-sm  btn-warning btn_view"><i class="fa fa-envelope-o"></i></button>';
                    $action =  @$btn_approval.@$btn_edit.@$btn_delete;
                }
                if($rows->status_si == '3'){
                    $btn_copy   = '<button title="Copy" id="'.$id.'" class="btn btn-sm btn-info btn_copy"><i class="fa fa-copy"></i></button>';
                    $btn_edit   = '<button title="Edit" id="'.$id.'" class="btn btn-sm btn-primary btn_edit"><i class="fa fa-edit"></i></button>';
                    $btn_ic = '<a href="'.base_url('app/ic/index/').$id.'" title="Initiative Charter(IC)" class="btn btn-sm btn-warning"><i class="fa fa-arrow-right"></i></a>';
                    $action =  @$btn_edit.@$btn_copy.@$btn_ic;
                }
                if($rows->status_si == '4'){
                    $btn_approval = '<button title="Approval" id="'.$id.'" val="2" class="btn btn-sm  btn-warning btn_view"><i class="fa fa-envelope-o"></i></button>';
                    $btn_edit = '<button title="Edit" id="'.$id.'" class="btn btn-sm btn-primary btn_edit"><i class="fa fa-edit"></i></button>';
                    $action =  @$btn_approval.@$btn_edit;
                }
            }

            //isi table disini
            $isi['no']                  = $i;
            $isi['id']                  = $rows->id;
            $isi['is_active']           = ($rows->is_active == 't' ? 'Active' : 'Non Active');
            $isi['created_date']        = h_format_date($rows->created_date,'d F Y');
            $isi['created_by']          = $rows->created_by;
            $isi['updated_date']        = h_format_date($rows->updated_date,'d F Y');
            $isi['updated_by']          = $rows->updated_by;

            $isi['code']                = str_replace(',','.',$rows->code);
            $isi['name']                = h_read_more($rows->name,30);
            $isi['id_bsc']              = $rows->name_bsc;
            $isi['start_date']          = $rows->start_date;
            $isi['end_date']            = $rows->end_date;
            $isi['name_pic_si']         = h_read_more($rows->name_pic_si,20);
            $isi['status_si']           = $rows->name_status_si;
            $isi['background_goal']     = h_read_more($rows->background_goal,20);
            $isi['objective_key_result'] = h_read_more($rows->objective_key_result,20);
            $isi['cek_objective_key_result'] = ($rows->cek_objective_key_result == '1' ? 'checked': 'No Check');

            $isi['action']              = '<div style="width:125px;'.($iTotalRecords=='1'?'height:50px;':'').'">'.$action.'</div>';

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

        //perspective
        $data['perspective'] = $this->m_global->getDataAll('m_perspective', null, ['is_active'=>'t'], '*', null, "name ASC");
        //bsc
        $data['bsc'] = $this->m_global->getDataAll('m_bsc', null,  ['is_active'=>'t'], '*', null, "name ASC");
        //periode
        $data['periode'] = $this->m_global->getDataAll('m_periode', null, ['is_active'=>'t'], '*', null, "start_year ASC");

        $this->template->display_ajax($this->url.'/v_si_add', $data);
    }

    public function load_edit() {
        csrf_init();
        $data['url'] = $this->url;

        //param
        $id = $this->input->post('id');
        $data['id'] = $id;

        //cek tipe view
        $type = @$this->input->post('type');
        $data['type'] = $type;
        $data['readonly'] = ($type == 'view' ? 'readonly="readonly"' : '');
        $data['disabled'] = ($type == 'view' ? 'disabled="disabled"' : '');

        //perspective
        $data['perspective'] = $this->m_global->getDataAll('m_perspective', null, ['is_active'=>'t'], '*', null, "name ASC");
        //bsc
        $data['bsc'] = $this->m_global->getDataAll('m_bsc', null,  ['is_active'=>'t'], '*', null, "name ASC");
        //pic
        $where = " '4' = ANY (string_to_array(role_id,',')) ";
        $data['pic'] = $this->m_global->getDataAll('sys_user', null, ['is_active'=>'t'], '*', $where, "fullname ASC");

        //file
        $data['html_list_file_si'] = $this->list_file_si(TRUE,$id);
        
        //get data
        $data['data'] = $this->m_global->getDataAll('m_si', null, ['id'=>$id], '*')[0];

        //get data so direct indirect
        $where = ['is_active'=>'t', 'id_si'=>$id, 'direct'=>'1'];
        $arr = $this->m_global->getDataAll('m_si_so', null,  $where, 'id_so, id_kpi_so', null, "id_so ASC");
        $data['direct'] = json_encode($arr);
        $where = ['is_active'=>'t', 'id_si'=>$id, 'direct'=>'0'];
        $arr = $this->m_global->getDataAll('m_si_so', null,  $where, 'id_so, id_kpi_so', null, "id_so ASC");
        $data['indirect'] = json_encode($arr);
        // echo '<pre>';print_r($data['indirect']);exit;

        $this->template->display_ajax($this->url.'/v_si_edit', $data);
    }

    public function load_copy() {
        csrf_init();
        $data['url'] = $this->url;

        //perspective
        $data['perspective'] = $this->m_global->getDataAll('m_perspective', null, ['is_active'=>'t'], '*', null, "name ASC");
        //bsc
        $data['bsc'] = $this->m_global->getDataAll('m_bsc', null,  ['is_active'=>'t'], '*', null, "name ASC");
        //pic
        $where = " '4' = ANY (string_to_array(role_id,',')) ";
        $data['pic'] = $this->m_global->getDataAll('sys_user', null, ['is_active'=>'t'], '*', $where, "fullname ASC");
        
        //get data
        $id = $this->input->post('id');
        $data['data'] = $this->m_global->getDataAll('m_si', null, ['id'=>$id], '*')[0];
        $this->template->display_ajax($this->url.'/v_si_copy', $data);
    }


    public function save_add() {

        //cek csrf token
        // $ex_csrf_token = @$this->input->post('ex_csrf_token');
        // $res = [];
        // if (csrf_get_token() != $ex_csrf_token){
        //     $res['status']  = 2;
        //     $res['message'] = $this->csrf_message;
        //     echo json_encode($res);
        // }else{

            // Set Rule Login Form
            $this->form_validation->set_rules('start_date', 'Periode', 'trim|xss_clean|required');
            $this->form_validation->set_rules('end_date', 'Periode', 'trim|xss_clean|required');
            $this->form_validation->set_rules('name', 'SI Title', 'trim|xss_clean|required');
            $this->form_validation->set_rules('code', 'SI Number', 'trim|xss_clean|required');
            $this->form_validation->set_rules('id_bsc', 'BSC', 'trim|xss_clean|required');
            $this->form_validation->set_rules('pic_si', 'PIC SI', 'trim|xss_clean|required');
            $this->form_validation->set_rules('user_pic_ic', 'PIC IC', 'trim|xss_clean|required');
            $this->form_validation->set_rules('background_goal', 'Background Goal', 'trim|xss_clean|required');
            if ($this->form_validation->run($this)) {

                $param = $this->input->post();
                
                //insert data
                $id_bsc =  @$this->input->post('id_bsc');
                $data['start_date']     = @$this->input->post('start_date').'-01';
                $data['end_date']       = @$this->input->post('end_date').'-01';
                $data['name']           = @$this->input->post('name');
                $data['code']           = @$this->input->post('code');
                $data['id_bsc']         = $id_bsc;
                $data['pic_si']         = str_replace(',',', ',@$this->input->post('pic_si'));
                $data['user_pic_si']    = str_replace(',',', ',@$this->input->post('user_pic_si'));
                $data['user_pic_ic']    = str_replace(',',', ',@$this->input->post('user_pic_ic'));
                if($id_bsc != '1'){
                    $data['parent_si']  = @$this->input->post('parent_si');
                }
                $data['background_goal']            = @$this->input->post('background_goal');
                $data['objective_key_result']       = @$this->input->post('objective_key_result');
                $data['cek_objective_key_result']   = (@$this->input->post('cek_objective_key_result') == '' ? '0' : '1');
                $data['created_date']   = date("Y-m-d H:i:s");
                $data['status_si']      = 1;
                $data['created_by']     = h_session('USERNAME');
                $result = $this->m_global->insert('m_si', $data);
                $id_si = $this->db->insert_id();

                // echo '<pre>';print_r($data);exit;
                // echo $this->db->last_query();exit;

                //==================================================================================

                //insert dierect 
                if(is_array(@$param['so_direct'])){
                    for ($i=0; $i < count($param['so_direct']);$i++) {
                        //cek data kosong
                        if(@$param['so_direct'][$i] != '' && @$param['kpi_so_direct'][$i] != ''){
                            //insert si_so
                            $data = [];
                            $data['id_si']  = $id_si;
                            $data['id_so']  = $param['so_direct'][$i];
                            $data['id_kpi_so']  = $param['kpi_so_direct'][$i];
                            $data['direct']   = 1;
                            $this->m_global->insert('m_si_so', $data);
                        }
                    }
                }

                //insert Indierect 
                if(is_array(@$param['so_indirect'])){
                    for ($i=0; $i < count($param['so_indirect']);$i++) {
                        //cek data kosong
                        if(@$param['so_indirect'][$i] != '' && @$param['kpi_so_indirect'][$i] != ''){
                            //insert si_so
                            $data = [];
                            $data['id_si']  = $id_si;
                            $data['id_so']  = $param['so_indirect'][$i];
                            $data['id_kpi_so']  = $param['kpi_so_indirect'][$i];
                            $data['direct']   = 0;
                            $this->m_global->insert('m_si_so', $data);
                           
                        }
                    }
                }


                //==================================================================================

                //insert table m_monev_si_year and m_monev_si_month
                $start_year = substr(@$this->input->post('start_date'),0,4);
                $end_year = substr(@$this->input->post('end_date'),0,4);
                for($y=$start_year;$y<=$end_year;$y++){
                    // //insert year
                    $data2 = [];
                    $data2['id_si']     = $id_si;
                    $data2['year']      = (int)$y;
                    $data2['status']    = 1;
                    $result = $this->m_global->insert('m_monev_si_year', $data2);
                    //insert month
                    for($m=1;$m<=12;$m++){
                        $data3 = [];
                        $data3['id_si']     = $id_si;
                        $data3['year']      = (int)$y;
                        $data3['month']     = (int)$m;
                        $data3['status']    = 1;
                        $result = $this->m_global->insert('m_monev_si_month', $data3);
                    }
                }


                //==================================================================================

                //use pic si dan ic
                $arr_user_pic_si = explode(',',@$this->input->post('user_pic_si'));
                $arr_user_pic_ic = explode(',',@$this->input->post('user_pic_ic'));
                $arr_join = array_merge($arr_user_pic_si,$arr_user_pic_ic);
                foreach($arr_join as $nip){
                    if($nip == ''){ continue; }
                    //cek role
                    if(in_array($nip, $arr_user_pic_si)){ $role_id_pic = '5'; }else{ $role_id_pic = '9'; }
                    //mengambil data id_si lama
                    $id_si_old = @$this->m_global->getDataAll('m_user_ic_si', null, ['nip' => $nip], 'id_si')[0]->id_si;
                    if($id_si_old == ''){
                        //insert data
                        $data = [];
                        $data['nip']            = $nip;
                        $data['role_id']        = $role_id_pic;
                        $data['id_si']          = $id_si;
                        $data['created_date']   = date("Y-m-d H:i:s");
                        $data['created_by']     = h_session('USERNAME');
                        $result = $this->m_global->insert('m_user_ic_si', $data);
                    }else{
                         //update data user ic
                        $data = [];
                        $data['role_id']        = $role_id_pic;
                        $data['id_si']          = $id_si_old.', '.$id_si;
                        $data['updated_date']   = date("Y-m-d H:i:s");
                        $data['updated_by']     = h_session('USERNAME');
                        $result = $this->m_global->update('m_user_ic_si', $data, ['nip' => $nip]);
                    }
                }

                //==================================================================================

                //membuat user login, role pic si
                $arr_user_pic_si = explode(',',@$this->input->post('user_pic_si'));
                $arr_user_pic_ic = explode(',',@$this->input->post('user_pic_ic'));
                $arr_join = array_merge($arr_user_pic_si,$arr_user_pic_ic);
                foreach($arr_join as $nip){
                    if($nip == ''){ continue; }
                    //cek role
                    if(in_array($nip, $arr_user_pic_si)){ $role_id_pic = '5'; }else{ $role_id_pic = '9'; }
                    //cek nip di table user
                    $arr_user = @$this->m_global->getDataAll('sys_user a', null, ['a.nip' => $nip],'id, role_id')[0];
                    $user_id = @$arr_user->id;
                    if($user_id == ''){
                        //insert user
                        $select = " \"NAMA\", \"NAMA\", \"CHILD_EMAIL\", \"NAME\", \"POSITION_ID\"";
                        $select .= ", (SELECT b.\"SINGKATAN_POSISI\" FROM \"ERP_STO_REAL\" b WHERE b.\"POSITION_ID\"= a.\"POSITION_ID\" LIMIT 1) AS \"SINGKATAN_POSISI\"";
                        $arr = @$this->m_global->getDataAll('DIRJAB_STO a', null, ['a.NIP' => $nip], $select)[0];
                        $data = [];
                        $data['username']   = @$nip;
                        $data['password']   = md5_mod('123');
                        $data['nip']        = @$nip;
                        $data['fullname']   = @$arr->NAMA;
                        $data['email']      = @$arr->CHILD_EMAIL;
                        $data['title']      = @$arr->NAME;
                        $data['singkatan_jabatan']  = @$arr->SINGKATAN_POSISI;
                        $data['position_id']  = @$arr->POSITION_ID;
                        $data['role_id']    = $role_id_pic;
                        $this->m_global->insert('sys_user', $data);
                    }else{
                        //update user
                        $data = [];
                        $user_role_id = @$arr_user->role_id;
                        if($user_role_id == ''){
                            $data['role_id'] = $role_id_pic;
                        }else{
                            $arr_user_role_id = explode(', ',$user_role_id);
                            if(!in_array($role_id_pic, $arr_user_role_id)){
                                $arr_user_role_id[] = $role_id_pic;
                            }
                            $data['role_id'] = join(', ', $arr_user_role_id);
                        }
                        $this->m_global->update('sys_user', $data, ['id' => $user_id]);
                    }
                }
                //==================================================================================

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
        // }
    }


    public function save_edit() {

        //cek csrf token
        // $ex_csrf_token = @$this->input->post('ex_csrf_token');
        // $res = [];
        // if (csrf_get_token() != $ex_csrf_token){
        //     $res['status']  = 2;
        //     $res['message'] = $this->csrf_message;
        //     echo json_encode($res);
        // }else{

            // Set Rule Login Form
            $this->form_validation->set_rules('start_date', 'Periode', 'trim|xss_clean|required');
            $this->form_validation->set_rules('end_date', 'Periode', 'trim|xss_clean|required');
            $this->form_validation->set_rules('name', 'SI Title', 'trim|xss_clean|required');
            $this->form_validation->set_rules('code', 'SI Number', 'trim|xss_clean|required');
            $this->form_validation->set_rules('id_bsc', 'BSC', 'trim|xss_clean|required');
            $this->form_validation->set_rules('pic_si', 'PIC', 'trim|xss_clean|required');
            $this->form_validation->set_rules('user_pic_ic', 'PIC IC', 'trim|xss_clean|required');
            $this->form_validation->set_rules('background_goal', 'Background Goal', 'trim|xss_clean|required');
            if ($this->form_validation->run($this)) {

                //update data
                $param = $this->input->post();
                $id_si = $this->input->post('id');
                $id_bsc =  @$this->input->post('id_bsc');

                $data['start_date']     = @$this->input->post('start_date').'-01';
                $data['end_date']       = @$this->input->post('end_date').'-01';
                $data['name']           = @$this->input->post('name');
                $data['code']           = @$this->input->post('code');
                $data['id_bsc']         = $id_bsc;
                $data['pic_si']         = str_replace(',',', ',@$this->input->post('pic_si'));
                $data['user_pic_si']    = str_replace(',',', ',@$this->input->post('user_pic_si'));
                $data['user_pic_ic']    = str_replace(',',', ',@$this->input->post('user_pic_ic'));
                if($id_bsc != '1'){
                    $data['parent_si']  = @$this->input->post('parent_si');
                }
                $data['background_goal']            = @$this->input->post('background_goal');
                $data['objective_key_result']       = @$this->input->post('objective_key_result');
                $data['cek_objective_key_result']   = (@$this->input->post('cek_objective_key_result') == '' ? '0' : '1');
                $data['updated_date']   = date("Y-m-d H:i:s");
                $data['updated_by']     = h_session('USERNAME');
                $result = $this->m_global->update('m_si', $data, ['id' => $id_si]);


                //============================================================================================

                //delete dierect, Indierect
                $data = [];
                $data['id_si']  = $id_si;
                $this->m_global->delete('m_si_so', $data);

                //insert dierect 
                if(is_array(@$param['so_direct'])){
                    for ($i=0; $i < count($param['so_direct']);$i++) {
                        //insert si_so
                        $data = [];
                        $data['id_si']  = $id_si;
                        $data['id_so']  = $param['so_direct'][$i];
                        $data['id_kpi_so']  = $param['kpi_so_direct'][$i];
                        $data['direct']   = 1;
                        $this->m_global->insert('m_si_so', $data);
                    }
                }
                
                //insert Indierect 
                if(is_array(@$param['so_indirect'])){
                    for ($i=0; $i < count(@$param['so_indirect']);$i++) {
                        //insert si_so
                        $data = [];
                        $data['id_si']  = $id_si;
                        $data['id_so']  = @$param['so_indirect'][$i];
                        $data['id_kpi_so']  = @$param['kpi_so_indirect'][$i];
                        $data['direct']   = 0;
                        $this->m_global->insert('m_si_so', $data);
                        
                    }
                }

                //============================================================================================

                //update periode tahun 
                $start_date         = @$this->input->post('start_date').'-01';
                $end_date           = @$this->input->post('end_date').'-01';
                $start_date_old     = @$this->input->post('start_date_old');
                $end_date_old       = @$this->input->post('end_date_old');

                //cek tahun yang dirubah
                $start_year         = substr($start_date,0,4);
                $end_year           = substr($end_date,0,4);
                $start_year_old     = substr($start_date_old,0,4);
                $end_year_old       = substr($end_date_old,0,4);

                //cek periode tahun lama dan baru
                $arr_year_old = [];
                for($y=$start_year_old;$y<=$end_year_old;$y++){
                    $arr_year_old[] = $y;
                }
                $arr_year_new = [];
                for($y=$start_year;$y<=$end_year;$y++){
                    $arr_year_new[] = $y;
                }

                //cek tahun yang di insert
                $year_insert = [];
                foreach($arr_year_new as $val){
                    if(!in_array($val,$arr_year_old)){
                        $year_insert[] = $val;
                    }
                }
                
                //cek tahun yang di update dan delete
                $year_update = [];
                $year_delete = [];
                foreach($arr_year_old as $val){
                    if(in_array($val,$arr_year_new)){
                        $year_update[] = $val;
                    }else{
                        $year_delete[] = $val;
                    }
                }

                //delete target year dan month
                if(count($year_delete) > 0){
                    foreach($year_delete as $y){
                        $where = $data = [];
                        $where['id_si']     = $id_si;
                        $where['year']      = $y;
                        $data['is_active']  = 'f';
                        $result = $this->m_global->update('m_monev_si_year', $data, $where);
                        $result = $this->m_global->update('m_monev_si_month', $data, $where);
                    }
                }

                //insert target year dan month
                if(count($year_insert) > 0){
                    foreach($year_insert as $y){
                        //insert year
                        $data2 = [];
                        $data2['id_si']     = $id_si;
                        $data2['year']      = $y;
                        $data2['status']    = 1;
                        $result = $this->m_global->insert('m_monev_si_year', $data2);
                        //insert month
                        for($m=1;$m<=12;$m++){
                            $data3 = [];
                            $data3['id_si']     = $id_si;
                            $data3['year']      = $y;
                            $data3['month']     = $m;
                            $data3['status']    = 1;
                            $result = $this->m_global->insert('m_monev_si_month', $data3);
                        }
                    }
                }

                
                //============================================================================================

                //delete user pic si dan ic yang lama
                $arr_user_pic_si_old = explode(',',@$this->input->post('user_pic_si_old'));
                $arr_user_pic_ic_old = explode(',',@$this->input->post('user_pic_ic_old'));
                $arr_join = array_merge($arr_user_pic_si_old,$arr_user_pic_ic_old);
                foreach($arr_join as $nip){
                    if($nip == ''){ continue; }
                    //cek role
                    if(in_array($nip, $arr_user_pic_si_old)){ $role_id_pic = '5'; }else{ $role_id_pic = '9'; }
                    //mengambil data id_si lama
                    $id_si_old = @$this->m_global->getDataAll('m_user_ic_si', null, ['nip' => $nip], 'id_si')[0]->id_si;
                    $arr_id_si_old = explode(', ', $id_si_old);
                    if (($key = array_search($id_si, $arr_id_si_old)) !== false) {
                        unset($arr_id_si_old[$key]);
                    }
                    //cek array nya kosong ata
                    if(@$arr_id_si_old[0] == '' ){
                        //delete nip
                        $result = $this->m_global->delete('m_user_ic_si', ['nip' => $nip]);
                    }else{
                        //update data user ic
                        $data = [];
                        $data['id_si']          = $id_si_old.', '.$id_si;
                        $data['role_id']        = $role_id_pic;
                        $data['updated_date']   = date("Y-m-d H:i:s");
                        $data['updated_by']     = h_session('USERNAME');
                        $result = $this->m_global->update('m_user_ic_si', $data, ['nip' => $nip]);
                    }
                }

                //user pic si dan ic
                $arr_user_pic_si = explode(',',@$this->input->post('user_pic_si'));
                $arr_user_pic_ic = explode(',',@$this->input->post('user_pic_ic'));
                $arr_join = array_merge($arr_user_pic_si,$arr_user_pic_ic);
                foreach($arr_join as $nip){
                    if($nip == ''){ continue; }
                    //cek role
                    if(in_array($nip, $arr_user_pic_si)){ $role_id_pic = '5'; }else{ $role_id_pic = '9'; }
                    //mengambil data id_si lama
                    $id_si_old = @$this->m_global->getDataAll('m_user_ic_si', null, ['nip' => $nip], 'id_si')[0]->id_si;
                    if($id_si_old == ''){
                        //insert data
                        $data = [];
                        $data['nip']            = $nip;
                        $data['role_id']        = $role_id_pic;
                        $data['id_si']          = $id_si;
                        $data['created_date']   = date("Y-m-d H:i:s");
                        $data['created_by']     = h_session('USERNAME');
                        $result = $this->m_global->insert('m_user_ic_si', $data);
                    }else{
                        //update data user ic
                        $data = [];
                        $data['role_id']        = $role_id_pic;
                        $data['id_si']          = $id_si_old.', '.$id_si;
                        $data['updated_date']   = date("Y-m-d H:i:s");
                        $data['updated_by']     = h_session('USERNAME');
                        $result = $this->m_global->update('m_user_ic_si', $data, ['nip' => $nip]);
                    }
                }


                //===============================================================================

                //delete role_id login, untuk role pic si dan pic ic
                $arr_user_pic_si = explode(',',@$this->input->post('user_pic_si_old'));
                $arr_user_pic_ic = explode(',',@$this->input->post('user_pic_ic_old'));
                $arr_join = array_merge($arr_user_pic_si,$arr_user_pic_ic);
                foreach($arr_join as $nip){
                    if($nip == ''){ continue; }
                    //cek role
                    if(in_array($nip, $arr_user_pic_si)){ $role_id_pic = '5'; }else{ $role_id_pic = '9'; }
                    //cek nip di table user
                    $arr_user = @$this->m_global->getDataAll('sys_user a', null, ['a.nip' => $nip],'id, role_id')[0];
                    $user_id = @$arr_user->id;
                    $user_role_id = @$arr_user->role_id;
                    //update user
                    $arr_user_role_id = explode(', ',$user_role_id);
                    if (($key = array_search($role_id_pic, $arr_user_role_id)) !== false) {
                        unset($arr_user_role_id[$key]);
                    }
                    if(@$arr_user_role_id[0] == ''){ 
                        $role_id_new = null;
                    }else{ 
                        $role_id_new = join(', ', $arr_user_role_id); 
                    }
                    $data = [];
                    $data['role_id'] = $role_id_new;
                    $this->m_global->update('sys_user', $data, ['id' => $user_id]);
                }


                //membuat user login, role pic si
                $arr_user_pic_si = explode(',',@$this->input->post('user_pic_si'));
                $arr_user_pic_ic = explode(',',@$this->input->post('user_pic_ic'));
                $arr_join = array_merge($arr_user_pic_si,$arr_user_pic_ic);
                foreach($arr_join as $nip){
                    if($nip == ''){ continue; }
                    //cek role
                    if(in_array($nip, $arr_user_pic_si)){ $role_id_pic = '5'; }else{ $role_id_pic = '9'; }
                    //cek nip di table user
                    $arr_user = @$this->m_global->getDataAll('sys_user a', null, ['a.nip' => $nip],'id, role_id')[0];
                    $user_id = @$arr_user->id;
                    if($user_id == ''){
                        //insert user
                        $select = " \"NAMA\", \"NAMA\", \"CHILD_EMAIL\", \"NAME\", \"POSITION_ID\"";
                        $select .= ", (SELECT b.\"SINGKATAN_POSISI\" FROM \"ERP_STO_REAL\" b WHERE b.\"POSITION_ID\"= a.\"POSITION_ID\" LIMIT 1) AS \"SINGKATAN_POSISI\"";
                        $arr = @$this->m_global->getDataAll('DIRJAB_STO a', null, ['a.NIP' => $nip], $select)[0];
                        $data = [];
                        $data['username']   = @$nip;
                        $data['password']   = md5_mod('123');
                        $data['nip']        = @$nip;
                        $data['fullname']   = @$arr->NAMA;
                        $data['email']      = @$arr->CHILD_EMAIL;
                        $data['title']      = @$arr->NAME;
                        $data['singkatan_jabatan']  = @$arr->SINGKATAN_POSISI;
                        $data['position_id']  = @$arr->POSITION_ID;
                        $data['role_id']    = $role_id_pic;
                        $this->m_global->insert('sys_user', $data);
                    }else{
                        //update user
                        $data = [];
                        $user_role_id = @$arr_user->role_id;
                        if($user_role_id == ''){
                            $data['role_id'] = $role_id_pic;
                        }else{
                            $arr_user_role_id = explode(', ',$user_role_id);
                            if(!in_array($role_id_pic, $arr_user_role_id)){
                                $arr_user_role_id[] = $role_id_pic;
                            }
                            $data['role_id'] = join(', ', $arr_user_role_id);
                        }
                        $this->m_global->update('sys_user', $data, ['id' => $user_id]);
                    }
                }

                //===============================================================================
                

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
        // }
    }

    public function save_copy() {

        //cek csrf token
        // $ex_csrf_token = @$this->input->post('ex_csrf_token');
        // $res = [];
        // if (csrf_get_token() != $ex_csrf_token){
        //     $res['status']  = 2;
        //     $res['message'] = $this->csrf_message;
        //     echo json_encode($res);
        // }else{

            // Set Rule Login Form
            // $this->form_validation->set_rules('copy_type', 'COPY Type', 'trim|xss_clean|required');
            $this->form_validation->set_rules('start_date', 'Periode', 'trim|xss_clean|required');
            $this->form_validation->set_rules('end_date', 'Periode', 'trim|xss_clean|required');
            $this->form_validation->set_rules('name', 'SI Title', 'trim|xss_clean|required');
            $this->form_validation->set_rules('code', 'SI Number', 'trim|xss_clean|required');
            $this->form_validation->set_rules('id_bsc', 'BSC', 'trim|xss_clean|required');
            if ($this->form_validation->run($this)) {

                //parameter
                $param = $this->input->post();
                $id_si_old = @$this->input->post('id');
                // $copy_type = @$this->input->post('copy_type');

                //get data si yang dicopy
                $where = ['id'=> $id_si_old, 'is_active'=>'t'];
                $arr = @$this->m_global->getDataAll('m_si', null, $where, '*',null,null,null,null,null,2)[0];
                //ubah data copy sesuai yang diganti
                unset($arr['id']);
                $arr['start_date']  = @$this->input->post('start_date').'-01';
                $arr['end_date']    = @$this->input->post('end_date').'-01';
                $arr['id_bsc']      = @$this->input->post('id_bsc');
                $arr['name']        = @$this->input->post('name');
                $arr['code']        = @$this->input->post('code');
                $arr['status_si']   = 1;
                $arr['created_date']   = date("Y-m-d H:i:s");
                $arr['created_by']     = h_session('USERNAME');
                //insert data
                $result = $this->m_global->insert('m_si', $arr);
                $id_si = $this->db->insert_id();

                // echo '<pre>';print_r($arr);exit;
                // echo $this->db->last_query();exit;

                //==================================================================================

                //copy direct indirect
                $where = ['id_si'=> $id_si_old, 'is_active'=>'t'];
                $arr = @$this->m_global->getDataAll('m_si_so', null, $where, '*',null,null,null,null,null,2);
                foreach($arr as $key=>$val){
                    unset($arr[$key]['id']);
                    $arr[$key]['id_si'] = $id_si;
                }
                $res = @$this->m_global->insertBatch('m_si_so', $arr);

                //==================================================================================

                //insert table m_monev_si_year and m_monev_si_month
                $start_year = substr(@$this->input->post('start_date'),0,4);
                $end_year = substr(@$this->input->post('end_date'),0,4);
                for($y=$start_year;$y<=$end_year;$y++){
                    // //insert year
                    $data2 = [];
                    $data2['id_si']     = $id_si;
                    $data2['year']      = (int)$y;
                    $data2['status']    = 1;
                    $result = $this->m_global->insert('m_monev_si_year', $data2);
                    //insert month
                    for($m=1;$m<=12;$m++){
                        $data3 = [];
                        $data3['id_si']     = $id_si;
                        $data3['year']      = (int)$y;
                        $data3['month']     = (int)$m;
                        $data3['status']    = 1;
                        $result = $this->m_global->insert('m_monev_si_month', $data3);
                    }
                }
                
                //==================================================================================

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
        // }
    }

    public function delete_data() {
        //cek csrf token
        // $ex_csrf_token = @$this->input->post('token');
        // $res = [];
        // if (csrf_get_token() != $ex_csrf_token){
        //     $res['status']  = 0;
        //     $res['message'] = $this->csrf_message;
        //     echo json_encode($res);
        // }else{
            $id = $this->input->post('id');
            $val = $this->input->post('val');
            $data['is_active'] = $val;
            $res = $this->m_global->update('m_si', $data, ['id' => $id]);
            if($val == 't'){
                $res['message'] = 'Active Success!';
            }else{
                $res['message'] = 'Delete Success!';
            }
            
            echo json_encode($res);
        // }
    }

    public function change_status() {
        //cek csrf token
        // $ex_csrf_token = @$this->input->post('token');
        // $res = [];
        // if (csrf_get_token() != $ex_csrf_token){
        //     $res['status']  = 0;
        //     $res['message'] = $this->csrf_message;
        //     echo json_encode($res);
        // }else{
            
            //update data
            $id = $this->input->post('id');
            $val = $this->input->post('val');
            $status_si = $val;
            $data['status_si'] = $status_si;
            $res = $this->m_global->update('m_si', $data, ['id' => $id]);
            
            //result
            $res['message'] = 'Success!';
            echo json_encode($res);
            
        // }

    }


    public function change_status_approval() {
        //cek csrf token
        // $ex_csrf_token = @$this->input->post('token');
        // $res = [];
        // if (csrf_get_token() != $ex_csrf_token){
        //     $res['status']  = 0;
        //     $res['message'] = $this->csrf_message;
        //     echo json_encode($res);
        // }else{
            
            //update data
            $id = $this->input->post('id');
            $val = $this->input->post('val');
            $id_si = $id;
            $status_si = $val;
            $data['status_si'] = $status_si;
            $res = $this->m_global->update('m_si', $data, ['id' => $id]);
            
            //==========================================================================
            //kirim notif email, ke user PIC IC
            $select = " user_pic_si, user_pic_ic, name, code";
            $arr = @$this->m_global->getDataAll('m_si AS a', null, ['id' => $id], $select)[0];
            $si_name = $arr->name;
            $si_code = $arr->code;
            $user_pic_si = str_replace(", ", "','", $arr->user_pic_si);
            $user_pic_ic = str_replace(", ", "','", $arr->user_pic_ic);

            //user pic si email
            $where = "nip IN('".$user_pic_si."') AND is_active='t'";
            $arr = @$this->m_global->getDataAll('sys_user', null, $where, 'email, fullname');
            $arr_name_pic_si = [];
            foreach($arr as $row){ 
                $arr_name_pic_si[] = $row->fullname;
            }

            //user pic ic email
            $role_id = 9;
            $where = "nip IN('".$user_pic_ic."') AND is_active='t'";
            $arr = @$this->m_global->getDataAll('sys_user', null, $where, 'nip, fullname, title, email');
            foreach($arr as $row){
                $data = [];
                $data['nip']        = $row->nip;
                $data['fullname']   = $row->fullname;
                $data['email']      = $row->email;
                $data['title']      = $row->title;
                $data['si_name']    = $si_name;
                $data['status_si']  = $status_si;
                $data['pic_si_name']   = join(', ',$arr_name_pic_si);
                
                //token dan link
                $token = h_insert_token('notif_input_data_ic',$row->nip, '30');
                $link = site_url().'login/redirect_page/notif_input_data_ic/'.$token.'/'.$row->nip.'/'.$role_id.'/'.$id_si;
                $data['link'] = $link;

                $to         = h_email_to($row->email);
                $from       = 'noreply@indonesiapower.co.id';
                $title      = "Pengisian Data Initiative Charter(IC)";
                $subject    = "Pengisian Data Initiative Charter(IC)";
                $data['subject'] = $subject;
                
                //untuk cek html
                // $this->load->view($this->url.'/v_si_email_input_data_ic', $data);

                //kirim email html
                $html = $this->load->view($this->url.'/v_si_email_input_data_ic', $data, TRUE);
                h_kirim_email_ip($from, $to, null, null, $title, $subject, $html);

                //============================ notif inbox ====================================
                //insert to inbox
                $data = [];
                $data['element']     = "SI - ".$si_code;
                $data['type_inbox']  = "SI";
                $data['description'] = "Pengisian Data Initiative Charter(IC), <br>
                                        Untuk SI: (".$si_code.') '.h_text_br($si_name,40);
                $data['param_id']       = $id_si;
                $data['review_status']  = 18;
                $data['request_by']     = 1;
                $data['request_date']   = date('Y-m-d H:i:s');
                $data['nip']            = $row->nip;
                $data['role_id']        = $role_id;
                $data['redirect_page']  = $link;
                $result = $this->m_global->insert('m_inbox', $data);
                //============================================================================

                
            }

            //result
            $res['message'] = 'Success!';
            echo json_encode($res);
            
        // }

    }


    public function select_perspective()
    {
        if(isset($_REQUEST['q'])){
            $q = strtolower($_REQUEST['q']);
            $id_bsc = @$_REQUEST['id_bsc'];
            if(!empty($_REQUEST['q'])){
                $where = "LOWER(name) LIKE '%".$q."%'";
            }
            if(!empty($id_bsc)){
                $where = " id = '".$id_bsc."'";
            }
            $arr_perspective = @$this->m_global->getDataAll('m_bsc AS a', NULL,$where,"a.id_perspective")[0]->id_perspective;
            $arr_perspective = str_replace(' ','',$arr_perspective);
            $where = " id IN(".$arr_perspective.")";
            $arr = @$this->m_global->getDataAll('m_perspective AS a', NULL,$where,"a.id, a.name");
            // echo $this->db->last_query();exit;    
            $data = [];
            for ($i=0; $i < count($arr); $i++) {
                $data[$i] = ['id' => $arr[$i]->id,  'name' => $arr[$i]->name ];
            }
            echo json_encode(['item' => $data]);
        }else{
            $id = $_REQUEST['id'];
            $where = ['id' => $id];
            $arr = @$this->m_global->getDataAll('m_perspective AS a', NULL,$where,"a.id, a.name",NULL,NULL,0,10);
            $data[] = [ 'id' => @$arr[0]->id,  'name' => @$arr[0]->name ];
            echo json_encode($data);
        }
    }


    public function select_pic_si()
    {
        if(isset($_REQUEST['q'])){
            $q          = $_REQUEST['q'];
            $where      = " \"POSISI\" != '' ";
            if($q != ''){ $where .= ' AND LOWER("POSISI") LIKE \'%'.strtolower($q).'%\' OR LOWER("SINGKATAN_POSISI") LIKE \'%'.strtolower($q).'%\'' ; }
            $select     = "\"POSISI\" AS \"JABATAN\", \"SINGKATAN_POSISI\", \"POSITION_ID\"";
            $select     .= ", (SELECT b.\"NAMA\" FROM \"DIRJAB_STO\" b 
                                WHERE b.\"POSITION_ID\"= a.\"POSITION_ID\" LIMIT 1
                            ) AS \"NAMA\"";
            $select     .= ", (SELECT b.\"NIP\" FROM \"DIRJAB_STO\" b 
                                WHERE b.\"POSITION_ID\"= a.\"POSITION_ID\" LIMIT 1
                            ) AS \"NIP\"";
            $arr = $this->m_global->getDataAll('ERP_STO_REAL a', NULL, $where, $select, NULL, '"SINGKATAN_POSISI" ASC',0,20);
            $data = [];
            for ($i=0; $i < count($arr); $i++) {
                if($arr[$i]->NIP != ''){
                    $name = '<b>'.$arr[$i]->SINGKATAN_POSISI.'</b> ["'.$arr[$i]->JABATAN.'"] [<b>"'.$arr[$i]->NAMA.'"</b>]';
                    $data[] = ['id' => $arr[$i]->POSITION_ID, 'name' => $name, 'user_pic_si'=> $arr[$i]->NIP ];
                }
            }
            echo json_encode(['item' => $data]);
        }else{
            $id         = str_replace(",", "','",  $_REQUEST['id']);
            $where      = "\"POSITION_ID\" IN ('".$id."')";
            $select     = "\"POSISI\" AS \"JABATAN\", \"SINGKATAN_POSISI\", \"POSITION_ID\"";
            $select     .= ", (SELECT b.\"NAMA\" FROM \"DIRJAB_STO\" b 
                                WHERE b.\"POSITION_ID\"= a.\"POSITION_ID\" LIMIT 1
                            ) AS \"NAMA\"";
            $select     .= ", (SELECT b.\"NIP\" FROM \"DIRJAB_STO\" b 
                                WHERE b.\"POSITION_ID\"= a.\"POSITION_ID\" LIMIT 1
                            ) AS \"NIP\"";
            $arr = $this->m_global->getDataAll('ERP_STO_REAL a', NULL, $where, $select);
            $data = [];
            for ($i=0; $i < count($arr); $i++) {
                $name = '<b>'.$arr[$i]->SINGKATAN_POSISI.'</b> ["'.$arr[$i]->JABATAN.'"] [<b>"'.$arr[$i]->NAMA.'"</b>]';
                $data[] = ['id' => $arr[$i]->POSITION_ID, 'name' => $name, 'user_pic_si'=> $arr[$i]->NIP];
            }
            echo json_encode($data);
        }
    }

    public function select_pic_ic()
    {
        if(isset($_REQUEST['q'])){
            $q          = $_REQUEST['q'];
            $where      = " 1=1 ";
            if($q != ''){ $where .= ' AND LOWER("NIP") LIKE \'%'.strtolower($q).'%\' OR LOWER("NAMA") LIKE \'%'.strtolower($q).'%\'' ; }
            $select     = "\"NIP\", \"NAMA\", \"CHILD_EMAIL\", \"POSITION_ID\"";
            $select     .= ", (SELECT b.\"POSISI\" FROM \"ERP_STO_REAL\" b 
                                WHERE b.\"POSITION_ID\"= a.\"POSITION_ID\" LIMIT 1
                            ) AS \"TITLE\"";
            $arr = $this->m_global->getDataAll('DIRJAB_STO a', NULL, $where, $select, NULL, '"NAMA" ASC',0,20);
            $data = [];
            for ($i=0; $i < count($arr); $i++) {
                $name = '["<b>'.$arr[$i]->NIP.'</b>"] [<b>"'.$arr[$i]->NAMA.'"</b>] ["'.$arr[$i]->TITLE.'"]';
                $data[$i] = ['id' => $arr[$i]->NIP, 'name' => $name];
            }
            echo json_encode(['item' => $data]);
        }else{
            $id         = str_replace(",", "','",  $_REQUEST['id']);
            $where      = "\"NIP\" IN ('".$id."')";
            $select     = "\"NIP\", \"NAMA\", \"NAME\" AS \"TITLE\", \"CHILD_EMAIL\", \"POSITION_ID\"";
            $select     .= ", (SELECT b.\"POSISI\" FROM \"ERP_STO_REAL\" b 
                                WHERE b.\"POSITION_ID\"= a.\"POSITION_ID\" LIMIT 1
                            ) AS \"TITLE\"";
            $arr = $this->m_global->getDataAll('DIRJAB_STO a', NULL, $where, $select, NULL, '"NAMA" ASC',0,20);
            $data = [];
            for ($i=0; $i < count($arr); $i++) {
                $name = '["<b>'.$arr[$i]->NIP.'</b>"] [<b>"'.$arr[$i]->NAMA.'"</b>] ["'.$arr[$i]->TITLE.'"]';
                $data[$i] = ['id' => $arr[$i]->NIP, 'name' => $name];
            }
            echo json_encode($data);
        }
    }

    // public function select_pic_ic_2()
    // {
    //     if(isset($_REQUEST['q'])){
    //         $q          = $_REQUEST['q'];
    //         $where      = "1=1 AND a.nip != '' AND a.id NOT IN(".h_user_tambahan().")";
    //         $where      .= " AND ( '9' = ANY (string_to_array(a.role_id,', '))
    //                             )";
    //         if($q != ''){ $where .= ' AND LOWER(a.nip) LIKE \'%'.strtolower($q).'%\' OR LOWER(a.fullname) LIKE \'%'.strtolower($q).'%\'' ; }
    //         $select     = "a.nip, a.fullname, 
    //                         (SELECT DISTINCT STRING_AGG ( b.\"name\" :: CHARACTER VARYING, ',' )
    //                             FROM \"sys_role\" b 
    //                             WHERE b.\"id\" IN(9) 
    //                             AND b.\"id\" ::text = ANY (string_to_array(a.role_id,', ')::text[])
    //                         ) AS role_name";
    //         $arr        = $this->m_global->getDataAll('sys_user a', NULL, $where, $select, NULL, 'a.nip ASC',0,30);
    //         $data       = [];
    //         for ($i=0; $i < count($arr); $i++) {
    //             $name = '[<b>'.$arr[$i]->nip.'</b>] '.$arr[$i]->fullname.'';
    //             $data[$i] = ['id' => $arr[$i]->nip, 'name' => $name, 'role_name' => $arr[$i]->role_name];
    //         }
    //         echo json_encode(['item' => $data]);
    //     }else{
    //         $where      = "a.nip = '".$_REQUEST['id']."'";
    //         $select     = "a.nip, a.fullname, 
    //                         (SELECT DISTINCT STRING_AGG ( b.\"name\" :: CHARACTER VARYING, ',' )
    //                             FROM \"sys_role\" b 
    //                             WHERE b.\"id\" IN(9) 
    //                             AND b.\"id\" ::text = ANY (string_to_array(a.role_id,', ')::text[])
    //                         ) AS role_name";
    //         $arr        = $this->m_global->getDataAll('sys_user a', NULL, $where, $select);
    //         $data       = [];
    //         for ($i=0; $i < count($arr); $i++) {
    //             $name = '[<b>'.$arr[$i]->nip.'</b>] '.$arr[$i]->fullname.'';
    //             $data[$i] = ['id' => $arr[$i]->nip, 'name' => $name, 'role_name' => $arr[$i]->role_name];
    //         }
    //         echo json_encode($data);
    //     }
    // }

    public function select_parent_si()
    {
        if(isset($_REQUEST['q'])){
            $q = strtolower($_REQUEST['q']);
            $id_perspective = @$_REQUEST['id_perspective'];
            $where = "is_active = 't'";
            $where .= " AND id_bsc = '1'";
            if(!empty($_REQUEST['q'])){
                $where .= " AND LOWER(name) LIKE '%".$q."%'";
            }
            if(!empty($id_perspective)){
                $where .= " AND id_perspective = '".$id_perspective."'";
            }
            $arr = @$this->m_global->getDataAll('m_si AS a', NULL,$where,"a.id, a.code, a.name, a.pic_si, a.id_perspective",NULL,"code ASC");
            // echo $this->db->last_query();exit;    
            $data = [];
            for ($i=0; $i < count($arr); $i++) {
                $name = '<b>['.$arr[$i]->code.']</b> '.$arr[$i]->name;
                $data[$i] = ['id' => $arr[$i]->id, 'name' => $name,  
                                'code' => $arr[$i]->code, 'name_si' => $arr[$i]->name,  
                                'pic_si' => $arr[$i]->pic_si, 'id_perspective' => $arr[$i]->id_perspective
                            ];
            }
            echo json_encode(['item' => $data]);
        }else{
            $id = $_REQUEST['id'];
            $id  = str_replace(",", "','", $id);
            $where = " a.id IN ('".$id."')";
            $arr = @$this->m_global->getDataAll('m_si AS a', NULL,$where,"a.id, a.code, a.name, a.pic_si, a.id_perspective");
            for ($i=0; $i < count($arr); $i++) {
                $name = '<b>['.$arr[$i]->code.']</b> '.$arr[$i]->name;
                $data[$i] = ['id' => $arr[$i]->id, 'name' => $name,  
                                'code' => $arr[$i]->code, 'name_si' => $arr[$i]->name,  
                                'pic_si' => $arr[$i]->pic_si, 'id_perspective' => $arr[$i]->id_perspective
                            ];
            }
            echo json_encode($data);
        }
    }


    // =========================== upload file ============================
    public function list_file_si($html=FALSE, $id='')
    {   
        if($html == FALSE){
            $id = $this->input->post('id');
        }
        $where = ['id_from'=>$id, 'type'=>'si', 'is_active'=>'t'];
        $file_si = @$this->m_global->getDataAll('m_file', null, $where, '*');
        $data['file_si']  = $file_si;

        $data['url'] = $this->url;
        $isi = $this->template->display_ajax($this->url.'/v_si_list_file', $data, NULL, NULL, $html);
        if($html){return $isi;}else{echo $isi;}
    }
    
    public function load_popup_upload_si()
    {
        $id = $this->input->post('id');
        $data['id'] = $id;
        
        $data['html_list_file_upload_si'] = $this->list_file_upload_si(TRUE,$id);

        $data['url'] = $this->url;
        $this->template->display_ajax($this->url.'/v_si_upload_file', $data);
    }
    
    public function list_file_upload_si($html=FALSE, $id='')
    {   
        if($html == FALSE){
            $id = $this->input->post('id');
        }
        $where = ['id_from'=>$id, 'type'=>'si', 'is_active'=>'t'];
        $file_si = @$this->m_global->getDataAll('m_file', null, $where, '*');
        $data['file_si']  = $file_si;

        $data['url'] = $this->url;
        $isi = $this->template->display_ajax($this->url.'/v_si_list_file_upload', $data, NULL, NULL, $html);
        if($html){return $isi;}else{echo $isi;}
    }

    public function upload_file_si(){
        $date_now                   = date('Y-m-d H:i:s');
        $file_name_origin           = h_replace_file_name($_FILES['userfile']['name']); 
        $type_file                  = h_file_type($_FILES['userfile']['name']);
        $id                         = $this->input->post('id');
        $tgl                        = date('Ymdhis');
        $random                     = rand(1,100);
        //upload
        $folder                     = './public/files/si/';
        $file_name                  = 'si_'.$id.'_'.$tgl.'_'.$random;
        $input_name                 = array_keys($_FILES)[0];
        $file_type                  = '*'; 
        $upload = h_upload($folder,$file_name,$input_name,$file_type);
        //insert data
        if($upload == TRUE){
            $data = array(
                'id_from'            => $id,
                'type'             => 'si',
                'file_name'        => $file_name.'.'.$type_file,
                'created_by'       => h_session('USERNAME'),
                'created_date'     => date("Y-m-d H:i:s"),
            );
            $result = $this->db->insert('m_file', $data);
        }
    }

    public function delete_file_si()
    {
        //param
        $param = $this->input->post();
        $file_name = $param['file_name'];
        $id = $param['id'];
        //delete data
        $result = $this->m_global->update('m_file', ['is_active'=>'f'], ['id' => $id]);
        //delete file
        // unlink(FCPATH."public/files/si/".$file_name);
        //message
        $res['status']  = ($result['status'] ? '1':'0');
        $res['message'] = 'Successfully Save Data!';
        echo json_encode($res);
    }
    // =========================== END upload file ============================


    public function load_initiative_mapping($html=FALSE)
    {
        //url
        $data['url']  = $this->url;
        //load model
        $this->load->model('app/m_si','m_si');
        //param
        $id_bsc = $this->input->post('id_bsc');
        $year = $this->input->post('year');
        if($year == ''){ $year = date('Y'); }

        //cek year dan month
        $date_start = $year.'-01-01';
        $date_end = $year.'-12-01';
        $whereE = " 1=1 ";
        // $whereE .= " AND ((a.\"start_date\" >= '".$date_start."' AND a.\"start_date\" <= '".$date_end."') ";
        // $whereE .= " OR (a.\"end_date\" >= '".$date_start."' AND a.\"end_date\" <= '".$date_end."') ";
        // $whereE .= " OR (a.\"start_date\" <= '".$date_start."' AND a.\"end_date\" >= '".$date_end."')) ";

        //data si
        $where = ['a.id_bsc'=>$id_bsc];
        $arr_si = $this->m_global->getDataAll('m_si a', null, $where, 'a.id, a.name, a.code', $whereE,'a.code ASC');
        $data['arr_si'] = $arr_si;
        $temp = [];
        foreach($arr_si as $row){
            $temp[] = $row->id;
        }
        $arr_id_si = implode(',',$temp);

        //data so dan kpi-so
        if($arr_id_si != ''){
            $where = " a.id_si IN(".$arr_id_si.")";
        }else{
            $where = "a.id_si = 0";
        }
        
        $join  = [  ['table' => 'm_so c', 'on' => 'a.id_so = c.id', 'join' => 'LEFT'],
                    ['table' => 'm_kpi_so b', 'on' => 'a.id_kpi_so = b.id', 'join' => 'LEFT']
        ];
        $select = 'a.id_so, a.id_kpi_so, b.code AS code_kpi_so, b.name AS name_kpi_so, c.code AS code_so, c.name AS name_so';
        $group_by = 'a.id_kpi_so, a.id_so, b.code, b.name, c.code, c.name';
        $arr_si_so = @$this->m_global->getDataAll('m_si_so a', $join, $where, $select,null,'c.code ASC, b.code ASC');

        //data so dan kpi-so
        // $where = ['a.id_bsc'=>$id_bsc];
        // $join  = [  ['table' => 'm_so b', 'on' => 'a.id_so = b.id', 'join' => 'LEFT'] ];
        // $select = 'a.id_so, a.id as id_kpi_so, a.code AS code_kpi_so, a.name AS name_kpi_so, b.code AS code_so, b.name AS name_so';
        // $arr_si_so = $this->m_global->getDataAll('m_kpi_so a', $join, $where, $select,null,'a.code ASC, b.code ASC');

        //olah data so dan kpi-so
        $arr_id_so = $arr_name_so = $arr_code_so = [];
        $arr_id_kpi_so = $arr_name_kpi_so = $arr_code_kpi_so = [];
        $arr_jum_kpi_so = [];
        foreach($arr_si_so as $row){
            $id_so = $row->id_so;
            $id_kpi_so = $row->id_kpi_so;
            //so
            $arr_id_so[$id_so] = $row->id_so;
            $arr_name_so[$id_so] = $row->name_so;
            $arr_code_so[$id_so] = $row->code_so;
            //kpi
            $arr_id_kpi_so[$id_so][$id_kpi_so] = $row->id_kpi_so;
            $arr_name_kpi_so[$id_so][$id_kpi_so] = $row->name_kpi_so;
            $arr_code_kpi_so[$id_so][$id_kpi_so] = $row->code_kpi_so;
            //jumlah kpi
            $arr_jum_kpi_so[$id_so][$id_kpi_so] = $row->id_kpi_so;
        }
        
        $data['arr_jum_kpi_so'] = $arr_jum_kpi_so;
        $data['arr_id_kpi_so'] = $arr_id_kpi_so;
        $data['arr_name_kpi_so'] = $arr_name_kpi_so;
        $data['arr_code_kpi_so'] = $arr_code_kpi_so;
        $data['arr_id_so'] = array_keys($arr_id_so);
        $data['arr_name_so'] = $arr_name_so;
        $data['arr_code_so'] = $arr_code_so;
        // echo '<pre>';print_r($data['arr_id_kpi_so']);exit;

        //data so
        $join  = [      ['table' => 'm_si a', 'on' => 'a.id = b.id_si', 'join' => 'LEFT'], 
                        ['table' => 'm_so c', 'on' => 'b.id_so = c.id', 'join' => 'LEFT'],
                        ['table' => 'm_kpi_so d', 'on' => 'b.id_kpi_so = d.id', 'join' => 'LEFT']
                 ];
        $where = ['a.id_bsc'=>$id_bsc];
        $select = 'a.code AS code_si, b.*, d.code AS code_kpi_so, d.name AS name_kpi_so, c.code AS code_so, c.name AS name_so';
        $arr_si_so = $this->m_global->getDataAll('m_si_so b', $join, $where, $select, $whereE);
        // echo $this->db->last_query();exit;
        $arr_direct = [];
        foreach($arr_si_so as $row){
            $id_si = $row->id_si;
            $id_so = $row->id_so;
            $id_kpi_so = $row->id_kpi_so;
            $arr_direct[$id_si][$id_kpi_so] = $row->direct;
        }
        $data['arr_direct'] = $arr_direct;
        // echo '<pre>';print_r($data['arr_direct']);exit;

        //get data so direct indirect
        // $where = ['is_active'=>'t', 'id_si'=>$id, 'direct'=>'1'];
        // $arr = $this->m_global->getDataAll('m_si_so', null,  $where, 'id_kpi_so', null, "id_so ASC");
        // $data['direct'] = json_encode($arr);
        // $where = ['is_active'=>'t', 'id_si'=>$id, 'direct'=>'0'];
        // $arr = $this->m_global->getDataAll('m_si_so', null,  $where, 'id_kpi_so', null, "id_so ASC");
        // $data['indirect'] = json_encode($arr);

        $this->template->display_ajax($this->url.'/v_si_initiative_mapping', $data);

    }

    public function select_so()
    {
        if(isset($_REQUEST['q'])){
            $q = strtolower($_REQUEST['q']);
            $no = @$_REQUEST['no'];
            $id_bsc = @$_REQUEST['id_bsc'];
            $id_kpi_so = @$_REQUEST['id_kpi_so'];
            $where = "a.is_active = 't' AND a.status_so = '3'";
            if(!empty($_REQUEST['q'])){
                $where .= " AND LOWER(name) LIKE '%".$q."%'";
            }
            if(!empty($id_bsc)){
                $where .= " AND a.id_bsc = '".$id_bsc."'";
            }
            // if(!empty($id_kpi_so)){
            //     $where .= " AND b.id = '".$id_kpi_so."'";
            // }
            $join  = [['table' => 'm_kpi_so b', 'on' => 'a.id = b.id_so', 'join' => 'LEFT']];

            $arr = @$this->m_global->getDataAll('m_so AS a', $join,$where,"a.id, a.code, a.name", null, "a.code ASC", null, null, "a.id");
            // echo $this->db->last_query();exit;    
            $data = [];
            for ($i=0; $i < count($arr); $i++) {
                $name = '<b>['.$arr[$i]->code.']</b> '.$arr[$i]->name;
                $data[$i] = ['id' => $arr[$i]->id,  'name' => $name, 'id_kpi_so' => $id_kpi_so, 'no' => $no ];
            }
            echo json_encode(['item' => $data]);
        }else{
            $id = $_REQUEST['id'];
            $no = @$_REQUEST['no'];
            $id_kpi_so = @$_REQUEST['id_kpi_so'];
            $where = ['a.id' => $id];
            $join  = [['table' => 'm_kpi_so b', 'on' => 'a.id = b.id_so', 'join' => 'LEFT']];
            $arr = @$this->m_global->getDataAll('m_so AS a', $join,$where,"a.id, a.code, a.name", null, "a.code ASC", 0,1, "a.id");
            $name = '<b>['.$arr[0]->code.']</b> '.$arr[0]->name;
            $data[] = [ 'id' => @$arr[0]->id,  'name' => @$name, 'id_kpi_so' => $id_kpi_so, 'no' => $no ];
            echo json_encode($data);
        }
    }


    public function select_kpi_so()
    {
        if(isset($_REQUEST['q'])){
            $q = strtolower($_REQUEST['q']);
            $no = @$_REQUEST['no'];
            $id_so = @$_REQUEST['id_so'];
            $where = "is_active = 't' AND status_kpi_so = '3'";
            if(!empty($_REQUEST['q'])){
                $where .= " AND LOWER(name) LIKE '%".$q."%'";
            }
            if(!empty($id_so)){
                $where .= " AND id_so = '".$id_so."'";
            }
            $arr = @$this->m_global->getDataAll('m_kpi_so AS a', NULL,$where,"a.id, a.code, a.name, a.id_so", null, "a.code ASC");
            // echo $this->db->last_query();exit;    
            $data = [];
            for ($i=0; $i < count($arr); $i++) {
                $name = '<b>['.$arr[$i]->code.']</b> '.$arr[$i]->name;
                $data[$i] = ['id' => $arr[$i]->id,  'name' => $name, 'id_so' => $arr[$i]->id_so, 'no' => $no ];
            }
            echo json_encode(['item' => $data]);
        }else{
            $id = $_REQUEST['id'];
            $no = @$_REQUEST['no'];
            $where = ['id' => $id];
            $arr = @$this->m_global->getDataAll('m_kpi_so AS a', NULL,$where,"a.id, a.code, a.name, a.id_so",NULL,NULL,0,1);
            $name = '<b>['.$arr[0]->code.']</b> '.$arr[0]->name;
            $data[] = [ 'id' => @$arr[0]->id,  'name' => @$name, 'id_so' => $arr[0]->id_so, 'no' => $no ];
            echo json_encode($data);
        }
    }


}
