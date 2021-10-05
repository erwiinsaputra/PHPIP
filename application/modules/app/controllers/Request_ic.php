<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Request_ic extends MX_Controller {
    
    private $prefix         = 'request_ic';
    private $table_db       = 'm_request_ic';
    private $title          = 'Request IC';
    private $url            = 'app/request_ic';

    function __construct() {
        parent::__construct();
        $this->middleware('guest', 'forbidden');
    }

    public function index($id='')
    {
        csrf_init();
        $data['title']      = $this->title;
        $data['url']        = $this->url;
        $data['breadcrumb'] = [$this->title => $this->url];

        //cek id so
        if($id != ''){
            $arr = $this->m_global->getDataAll('m_request_ic', null,  ['id'=>$id])[0];
            $data['id_bsc']         = $arr->id_bsc;
            $data['id_si']          = $arr->id_si;
            $data['id']             = $arr->id;
        }else{
            $data['id_bsc']         = 1;
        }

        //bsc
        $data['bsc'] = $this->m_global->getDataAll('m_bsc', null,  ['is_active'=>'t'], 'id,name', null, "name ASC");

        //status
        $data['status_request'] = $this->m_global->getDataAll('m_status', null, ['is_active'=>'t', 'type'=>'SO STATUS'], '*', null, '"order" ASC');

        //year
        $data['start_year'] = @$this->m_global->getDataAll('m_periode', null, ['is_active'=>'t'], 'MIN(start_year) AS year')[0]->year;
        $data['end_year'] = @$this->m_global->getDataAll('m_periode', null, ['is_active'=>'t'], 'MAX(end_year) AS year')[0]->year;

        $js['custom']       = ['table_request_ic'];
        $this->template->display($this->url.'/v_'.$this->prefix, $data, $js);
    }

    public function table_request_ic()
    {    
        // load model view 
        $this->load->model('app/m_request_ic','m_request_ic');

        //search default
        $where  = [];
        $whereE = " a.is_active = 't'";

        //cek role Admin, PIC SI, PIC IC
        if(in_array(h_session('ROLE_ID'), h_role_admin())){ 
            $whereE .= ""; 
        }elseif( h_session('ROLE_ID') == '5'){ 
            $position_id = h_session('POSITION_ID');
            $whereE .= " AND a.status_request != '1'";
            $whereE .= " AND ".$position_id."::text = ANY (string_to_array(a.id_pic:: TEXT,', ')::text[]) "; 
        }elseif( h_session('ROLE_ID') == '9'){
            $user_id = h_session('USER_ID');
            $whereE .= " AND a.request_by =  '".$user_id."' "; 
        }else{
            $whereE .= " a.id = '' "; 
        }

        //cek is active
        if(isset($_REQUEST['filter'])){
            foreach ($_REQUEST['filter'] as $row) {
                if(@$row['name'] == 'is_active'){ $whereE = " 0=0 "; }
            }
        }

        //filtering global
        // echo '<pre>';print_r($_REQUEST);exit;
        $id_bsc = @$_REQUEST['global_id_bsc'];
        $id_si = @$_REQUEST['global_id_si'];
        $id = @$_REQUEST['global_id'];
        if($id_bsc != ''){ $where['a.id_bsc'] = $id_bsc; }
        if($id_si != ''){ $where['a.id_si'] = $id_si; }
        if($id != ''){ $where['a.id'] = $id; }

        //filtering
        $search = [];
        if(isset($_REQUEST['filter'])){
            foreach ($_REQUEST['filter'] as $row) {

                //default parameter
                if(@$row['name'] != ''){

                    $val= $row['value']; $tipe = $row['tipe']; $search[] = $row['name']; 
                    $name = @$row['name'];
                    $name = $this->m_request_ic->select($row['name']); $name = str_replace(' AS '.$row['name'], ' ', $name );

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
        $table = "m_request_ic AS a";
        $join  = NULL;
        $select = [ 'id','id_bsc','id_si',
                    'code_si','name_si','name_bsc','name_pic','name_pic2','name_status_request','name_request_by',
                    'request_by','keterangan','status_request','status_finished','keterangan_approval','status_send_to_admin',
                    'request_date','approve_date','finished_date',
                    'is_active','created_date','created_by','updated_date','updated_by','status_request'
                ];
        $select = array_unique(array_merge($select, $search));
        $select = $this->m_request_ic->select($select);

        //pagging
        $iTotalRecords  = @$this->m_global->countDataAll($table, $join, $where, $whereE);
        $iDisplayLength = intval($_REQUEST['length']);
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
        $iDisplayStart  = intval($_REQUEST['start']);
        $sEcho          = intval($_REQUEST['draw']);
        $end            = $iDisplayStart + $iDisplayLength;
        $i              = 1 + $iDisplayStart;
        $end            = $end > $iTotalRecords ? $iTotalRecords : $end;

        //Ambil datanya
        $result = @$this->m_global->getDataAll($table, $join, $where, $select, $whereE, $order, $iDisplayStart, $iDisplayLength);
        // echo $this->db->last_query();exit;

        $param=[];
        foreach ($result as $rows) {
            $id = @$rows->id;
            $id_si = @$rows->id_si;

            //button
            $action = $btn_edit = $btn_view =  $btn_delete = '';
            $btn_send_approval = $btn_reject_send = $btn_approval = $btn_reject_approval = '';
            $btn_send_to_admin = $btn_done_request_admin =  '';

            //button delete
            $btn_delete = '<button title="Delete" id="'.$id.'" val="f" class="btn btn-sm  btn-danger btn_delete"><i class="fa fa-remove"></i></button>';
            if($rows->is_active == 'f'){
                $btn_delete = '<button title="Active" id="'.$id.'" val="t" class="btn btn-sm  btn-danger btn_delete"><i class="fa fa-check"></i></button>';
            }
            //button view
            $btn_view = '<button title="View" id="'.$id.'" class="btn btn-sm btn-warning btn_view"><i class="fa fa-list"></i></button>';

            //jika role admin
            if(in_array( h_session('ROLE_ID'), h_role_admin())){
                if($rows->status_request == '1'){
                    $btn_send_approval = '<button title="Send Approval" id="'.$id.'" id_si="'.$id_si.'" class="btn btn-sm  btn-info btn_send_approval"><i class="fa fa-send-o"></i></button>';
                    $btn_edit = '<button title="Edit" id="'.$id.'" class="btn btn-sm btn-primary btn_edit"><i class="fa fa-edit"></i></button>';
                    $action = @$btn_send_approval.@$btn_edit.@$btn_delete;
                }
                if($rows->status_request == '2'){
                    $btn_approval = '<button title="Approval" id="'.$id.'" class="btn btn-sm btn-primary btn_approval"><i class="fa fa-check"></i></button>';
                    $btn_reject_send = '<button title="Reject Send" id_si="'.$id_si.'" id="'.$id.'" class="btn btn-sm  btn-info btn_reject_send"><i class="fa fa-reply"></i></button>';
                    $action =  @$btn_approval.@$btn_reject_send.@$btn_view;
                }
                if($rows->status_request == '3'){
                    $btn_reject_approval = '<button title="Reject Approval" id="'.$id.'" class="btn btn-sm  btn-info btn_reject_approval"><i class="fa fa-reply"></i></button>';
                    if($rows->status_send_to_admin == ''){
                        $btn_send_to_admin = '<button title="Request Assist Admin" id_si="'.$id_si.'" id="'.$id.'" val="2" class="btn btn-sm  btn-primary change_status_send_to_admin"><i class="fa fa-send-o"></i></button>';
                    }
                    if($rows->status_send_to_admin == '2'){
                        $btn_done_request_admin = '<button title="Done Request Assist Admin" id="'.$id.'" class="btn btn-sm btn-primary btn_done_request_admin"><i class="fa fa-check"></i></button>';
                    }
                    $btn_finished = '<button title="Finished Request IC" id="'.$id.'" class="btn btn-sm  btn-success btn_finished"><i class="fa fa-check"></i></button>';
                    $action = @$btn_send_to_admin.@$btn_done_request_admin.@$btn_finished.@$btn_reject_approval;
                }
                if($rows->status_request == '4'){
                    $action = @$btn_view;
                }
            }

            //role PIC SI
            if(h_session('ROLE_ID') == '5'){
                if($rows->status_request == '1'){
                    $action =  @$btn_view;
                }
                if($rows->status_request == '2'){
                    $btn_approval = '<button title="Approval" id="'.$id.'" class="btn btn-sm  btn-primary btn_approval"><i class="fa fa-check"></i></button>';
                    $action =  @$btn_approval;
                }
                if($rows->status_request == '3'){
                    $btn_reject_approval = '<button title="Reject Approval" id="'.$id.'" class="btn btn-sm  btn-info btn_approval"><i class="fa fa-reply"></i></button>';
                    $action = @$btn_reject_approval.@$btn_view;
                }
                if($rows->status_request == '4'){
                    $btn_reject_approval = '<button title="Reject Approval" id="'.$id.'" class="btn btn-sm  btn-info btn_approval"><i class="fa fa-reply"></i></button>';
                    $action = @$btn_reject_approval.@$btn_view;
                }
            }

            //role PIC IC
            if(h_session('ROLE_ID') == '9'){
                if($rows->status_request == '1'){
                    $btn_send_approval = '<button title="Send Approval" id="'.$id.'" id_si="'.$id_si.'" class="btn btn-sm  btn-info btn_send_approval"><i class="fa fa-send-o"></i></button>';
                    $btn_edit = '<button title="Edit" id="'.$id.'" class="btn btn-sm btn-primary btn_edit"><i class="fa fa-edit"></i></button>';
                    $action =  @$btn_send_approval.@$btn_edit.@$btn_delete;
                }
                if($rows->status_request == '2'){
                    $btn_reject_send = '<button title="Reject Send" id_si="'.$id_si.'" id="'.$id.'" class="btn btn-sm  btn-info btn_reject_send"><i class="fa fa-reply"></i></button>';
                    $action =  @$btn_reject_send.@$btn_view;
                }
                if($rows->status_request == '3'){
                    if($rows->status_finished == '0'){
                        if($rows->status_send_to_admin == ''){
                            $btn_send_to_admin = '<button title="Request Assist Admin" id="'.$id.'" id_si="'.@$id_si.'" val="2" class="btn btn-sm  btn-primary btn_send_to_admin"><i class="fa fa-send-o"></i></button>';
                        }
                        $btn_go_to_master = '<button title="Go To Master IC" id="'.@$rows->id_si.'" class="btn btn-sm  btn-primary btn_go_to_master"><i class="fa fa-arrow-right"></i></button>';
                        $btn_finished = '<button title="Finished" id="'.$id.'" class="btn btn-sm  btn-success btn_finished"><i class="fa fa-check"></i></button>';
                        $action =  @$btn_send_to_admin.@$btn_go_to_master.@$btn_finished.@$btn_view;
                    }else{
                        $action =  @$btn_view;
                    }
                }
                if($rows->status_request == '4'){
                    $action =  @$btn_view;
                }
            }

            //status send admin
            if($rows->status_send_to_admin == '1'){
                $status_send_to_admin = 'No';
            }elseif($rows->status_send_to_admin == '2'){
                $status_send_to_admin = 'Yes';
            }elseif($rows->status_send_to_admin == '3'){
                $status_send_to_admin = 'Done';
            }else{
                $status_send_to_admin = 'No';
            }

            //isi table disini
            $isi['no']                  = $i;
            $isi['id']                  = $rows->id;
            
            $isi['id_si']               = h_read_more($rows->name_si,30);
            $isi['id_bsc']              = h_read_more($rows->name_bsc,30);
            $isi['id_pic']              = h_read_more($rows->name_pic,10).''.h_read_more($rows->name_pic2,10);

            $isi['name_request_by']     = h_read_more($rows->name_request_by,30);
            $isi['keterangan']          = h_read_more($rows->keterangan,30);
            $isi['keterangan_approval'] = h_read_more($rows->keterangan_approval,30);
            $isi['status_request']      = $rows->name_status_request;
            $isi['status_finished']     = ($rows->status_finished == '0' ? 'No' : 'Finished');
            $isi['status_send_to_admin']   = $status_send_to_admin;
            $isi['request_date']        = $rows->request_date;
            $isi['finished_date']       = $rows->finished_date;
            $isi['approve_date']        = $rows->approve_date;

            $file_request = '<a href="javascript:;" id="'.$id.'" class="btn_view_file_request"><i class="fa fa-folder-open-o" style="font-size:2em;margin-top:8px;"></i> </a>';
            $isi['file_request']        = $file_request;

            $isi['is_active']           = ($rows->is_active == 't' ? 'Active' : 'Non Active');
            $isi['created_date']        = h_format_date($rows->created_date,'d F Y');
            $isi['created_by']          = $rows->created_by;
            $isi['updated_date']        = h_format_date($rows->updated_date,'d F Y');
            $isi['updated_by']          = $rows->updated_by;
            
            $isi['action']              = '<div style="width:140px;'.($iTotalRecords=='1'?'height:50px;':'').'">'.$action.'</div>';

            $param[] = $isi;
            $i++;
        }
        $records["data"]            = $param;
        $records["draw"]            = $sEcho;
        $records["recordsTotal"]    = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        // echo '<pre>';print_r($param);exit;

        echo json_encode($records);
    }


    public function load_detail_si() {
        csrf_init();
        $data['url'] = $this->url;

        //param
        $id = $this->input->post('id');
        $data['id'] = $id;

        //cek tipe view
        $type = 'view';
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
        
        //get data
        $data['data'] = $this->m_global->getDataAll('m_si', null, ['id'=>$id], '*')[0];

        //get data so direct 
        $where = ['a.is_active'=>'t', 'a.id_si'=>$id, 'a.tipe'=>'1'];
        $select = 'b.code as code_kpi_so, b.name as name_kpi_so, c.name as name_so,  c.code as code_so';
        $join  = [  ['table' => 'm_kpi_so b', 'on' => 'a.id_kpi_so = b.id', 'join' => 'LEFT'],
                    ['table' => 'm_so c', 'on' => 'a.id_so = c.id', 'join' => 'LEFT'] ];
        $arr = $this->m_global->getDataAll('m_si_so a', $join,  $where, $select, null, "a.id_so ASC");
        $data['direct'] = $arr;

        //get data so indirect 
        $where = ['a.is_active'=>'t', 'a.id_si'=>$id, 'a.tipe'=>'0'];
        $arr = $this->m_global->getDataAll('m_si_so a', $join,  $where, $select, null, "a.id_so ASC");
        $data['indirect'] = $arr;
        // echo '<pre>';print_r($data['direct']);exit;

        $this->template->display_ajax($this->url.'/v_request_ic_detail_si', $data);
    }


    public function load_add() {
        csrf_init();
        $data['url'] = $this->url;

        //bsc
        $data['bsc'] = $this->m_global->getDataAll('m_bsc', null,  ['is_active'=>'t'], '*', null, "name ASC");

        $this->template->display_ajax($this->url.'/v_request_ic_add', $data);
    }

    public function load_edit() {
        csrf_init();
        $data['url'] = $this->url;
        $id = @$this->input->post('id');
        $data['id'] = $id;

        //cek tipe view
        $type = @$this->input->post('type');
        $data['type'] = $type;
        $data['readonly'] = ($type == 'view' ? 'readonly="readonly"' : '');
        $data['disabled'] = ($type == 'view' ? 'disabled="disabled"' : '');

        //bsc
        $data['bsc'] = $this->m_global->getDataAll('m_bsc', null,  ['is_active'=>'t'], '*', null, "name ASC");

        //get data
        $data['data'] = @$this->m_global->getDataAll('m_request_ic', null, ['id'=>$id], '*')[0];

        //file request ic
        $data['html_list_file_request_ic'] = $this->list_file_request_ic(TRUE,$id);

        $this->template->display_ajax($this->url.'/v_request_ic_edit', $data);
    }

    public function load_view() {
        csrf_init();
        $data['url'] = $this->url;
        $id = @$this->input->post('id');
        $data['id'] = $id;

        //cek tipe view
        $type = @$this->input->post('type');
        $data['type'] = $type;
        $data['readonly'] = ($type == 'view' ? 'readonly="readonly"' : '');
        $data['disabled'] = ($type == 'view' ? 'disabled="disabled"' : '');

        //bsc
        $data['bsc'] = $this->m_global->getDataAll('m_bsc', null,  ['is_active'=>'t'], '*', null, "name ASC");

        //get data
        $data['data'] = @$this->m_global->getDataAll('m_request_ic', null, ['id'=>$id], '*')[0];

        //file request ic
        $data['html_list_file_request_ic'] = $this->list_file_request_ic(TRUE,$id);

        $this->template->display_ajax($this->url.'/v_request_ic_view', $data);
    }

    public function load_send_to_admin() {
        csrf_init();
        $data['url'] = $this->url;
        $id = @$this->input->post('id');
        $data['id'] = $id;

        //cek tipe view
        $type = @$this->input->post('type');
        $data['type'] = $type;
        $data['readonly'] = ($type == 'view' ? 'readonly="readonly"' : '');
        $data['disabled'] = ($type == 'view' ? 'disabled="disabled"' : '');

        //bsc
        $data['bsc'] = $this->m_global->getDataAll('m_bsc', null,  ['is_active'=>'t'], '*', null, "name ASC");

        //get data
        $data['data'] = @$this->m_global->getDataAll('m_request_ic', null, ['id'=>$id], '*')[0];

        //file request ic
        $data['html_list_file_request_ic'] = $this->list_file_request_ic(TRUE,$id);

        $this->template->display_ajax($this->url.'/v_request_ic_send_to_admin', $data);
    }

    public function load_done_request_admin() {
        csrf_init();
        $data['url'] = $this->url;
        $id = @$this->input->post('id');
        $data['id'] = $id;

        //cek tipe view
        $type = @$this->input->post('type');
        $data['type'] = $type;
        $data['readonly'] = ($type == 'view' ? 'readonly="readonly"' : '');
        $data['disabled'] = ($type == 'view' ? 'disabled="disabled"' : '');

        //bsc
        $data['bsc'] = $this->m_global->getDataAll('m_bsc', null,  ['is_active'=>'t'], '*', null, "name ASC");

        //get data
        $data['data'] = @$this->m_global->getDataAll('m_request_ic', null, ['id'=>$id], '*')[0];

        //file request ic
        $data['html_list_file_request_ic'] = $this->list_file_request_ic(TRUE,$id);

        $this->template->display_ajax($this->url.'/v_request_ic_done_request_admin', $data);
    }


    public function load_approval() {
        csrf_init();
        $data['url'] = $this->url;
        $id = @$this->input->post('id');
        $data['id'] = $id;

        //bsc
        $data['bsc'] = $this->m_global->getDataAll('m_bsc', null,  ['is_active'=>'t'], '*', null, "name ASC");

        //get data
        $data['data'] = @$this->m_global->getDataAll('m_request_ic', null, ['id'=>$id], '*')[0];

        //file request ic
        $data['html_list_file_request_ic'] = $this->list_file_request_ic(TRUE,$id);

        $this->template->display_ajax($this->url.'/v_request_ic_approval', $data);
    }

    public function load_view_file_request() {
        csrf_init();
        $data['url'] = $this->url;
        $id = @$this->input->post('id');
        $data['id'] = $id;

        //file request ic
        $data['html_list_file_request_ic'] = $this->list_file_request_ic(TRUE,$id);

        $this->template->display_ajax($this->url.'/v_request_ic_view_file_request', $data);
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
            $this->form_validation->set_rules('id_bsc', 'BSC', 'trim|xss_clean|required');
            $this->form_validation->set_rules('id_si', 'SI', 'trim|xss_clean|required');
            $this->form_validation->set_rules('keterangan', 'Keterangan', 'required');
            
            if ($this->form_validation->run($this)) {

                //insert data
                $data['id_bsc']         = @$this->input->post('id_bsc');
                $data['id_si']          = @$this->input->post('id_si');
                $data['id_pic']         = @$this->input->post('pic_si');
                $data['keterangan']     = @$this->input->post('keterangan');
                $data['status_request'] = 1;
                $data['request_by']     = h_session('USER_ID');
                $data['request_date']   = date("Y-m-d H:i:s");
                $data['created_date']   = date("Y-m-d H:i:s");
                $data['created_by']     = h_session('USERNAME');

                //insert m_request_ic
                $result = $this->m_global->insert('m_request_ic', $data);
                $id_request_ic = $this->db->insert_id();
                
                //update id_from di m_file
                $data2['id_from'] = $id_request_ic;
                $where2['id_from'] = @$this->input->post('id_from');
                $result = $this->m_global->update('m_file', $data2, $where2);
                
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
            $this->form_validation->set_rules('id_bsc', 'BSC', 'trim|xss_clean|required');
            $this->form_validation->set_rules('id_si', 'SI', 'trim|xss_clean|required');
            $this->form_validation->set_rules('keterangan', 'Keterangan', 'required');

            if ($this->form_validation->run($this)) {

                // echo '<pre>';print_r(@$this->input->post());exit;

                //update data
                $id = $this->input->post('id');
                $id_request_ic = $id;

                $data['id_bsc']         = @$this->input->post('id_bsc');
                $data['id_si']          = @$this->input->post('id_si');
                $data['id_pic']         = @$this->input->post('pic_si');
                $data['keterangan']     = @$this->input->post('keterangan');
                $data['request_by']     = h_session('USER_ID');
                $data['request_date']   = date("Y-m-d H:i:s");
                $data['updated_date']   = date("Y-m-d H:i:s");
                $data['updated_by']     = h_session('USERNAME');

                $result = $this->m_global->update('m_request_ic', $data, ['id' => $id]);

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

    public function send_approval() {
        //cek csrf token
        // $ex_csrf_token = @$this->input->post('token');
        // $res = [];
        // if (csrf_get_token() != $ex_csrf_token){
        //     $res['status']  = 0;
        //     $res['message'] = $this->csrf_message;
        //     echo json_encode($res);
        // }else{

            //param
            $id = $this->input->post('id');
            $id_si = $this->input->post('id_si');
            $status_new = '2';

            //update data
            $data['status_request'] = $status_new;
            $res = $this->m_global->update('m_request_ic', $data, ['id' => $id]);

            //======================== kirim notif email =======================================
            //get data si
            $select = "name, code";
            $arr = @$this->m_global->getDataAll('m_si AS a', null, ['id' => $id_si], $select)[0];
            $si_name = $arr->name;
            $si_code = $arr->code;

            //selec pic IC
            $role_id  = 5;
            $arr_user = [];
            $where      = " a.is_active='t' AND a.role_id='".$role_id."' ";
            $where      .= " AND ('".$id_si."' = ANY (string_to_array(a.id_si,', ')))";
            $arr        = @$this->m_global->getDataAll('m_user_ic_si AS a', null, $where, 'a.nip');
            foreach($arr as $row){ $arr_user[$row->nip] = $row->nip;}

            //kirim email ke user pic IC
            $arr_nip    = join("','",$arr_user);
            $where      = "a.nip IN('".$arr_nip."') AND a.is_active='t'";
            $arr        = @$this->m_global->getDataAll('sys_user AS a', null, $where, 'a.nip, a.fullname, a.email, a.title, a.role_id');
            // echo '<pre>';print_r($arr);exit;
            
            foreach($arr as $row){
                $data = [];
                $data['nip']            = $row->nip;
                $data['fullname']       = $row->fullname;
                $data['email']          = $row->email;
                $data['title']          = $row->title;
                $data['status']         = $status_new;
                $data['si_name']        = $si_name;
                $data['si_code']        = $si_code;
                $data['request_by']     = h_session('NAME');
                $data['request_date']   = date("Y-m-d H:i:s");

                $token = h_insert_token('request_approval_request_ic',$row->nip, '30');
                $link = site_url().'login/redirect_page/request_approval_request_ic/'.$token.'/'.$row->nip.'/'.$role_id.'/'.$id;
                $data['link'] = $link;

                $to         = h_email_to($row->email);
                $from       = 'noreply@indonesiapower.co.id';
                $title      = "Request Approval IC";
                $subject    = 'Request Approval IC';
                $data['subject'] = $subject;
                
                //untuk cek html
                // $this->load->view($this->url.'/v_request_ic_email_request_approval', $data);

                //kirim email html
                $html = $this->load->view($this->url.'/v_request_ic_email_request_approval', $data, TRUE);
                h_kirim_email_ip($from, $to, null, null, $title, $subject, $html);


                //============================ notif inbox ====================================
                //insert to inbox
                $data = [];
                $data['element']        = "SI - ".$si_code;
                $data['type_inbox']     = "SI";
                $data['description']    = "Request Approval Initiative Charter(IC), <br>
                                            Untuk SI: (".$si_code.") ".h_text_br($si_name,40);
                $data['param_id']       = $id;
                $data['review_status']  = 18;
                $data['request_by']     = h_session('USER_ID');
                $data['request_date']   = date('Y-m-d H:i:s');
                $data['nip']            = $row->nip;
                $data['role_id']        = $role_id;
                $data['redirect_page']  = $link;
                $result = $this->m_global->insert('m_inbox', $data);
                //============================================================================

            }

            //result
            $res['message'] = 'Send Approval Success!';
            echo json_encode($res);

        // }


        
    }

    public function reject_send() {
        //cek csrf token
        // $ex_csrf_token = @$this->input->post('token');
        // $res = [];
        // if (csrf_get_token() != $ex_csrf_token){
        //     $res['status']  = 0;
        //     $res['message'] = $this->csrf_message;
        //     echo json_encode($res);
        // }else{

            //update
            $id = $this->input->post('id');
            $id_si = $this->input->post('id_si');
            $status_new = '1';
            $data['status_request'] = $status_new;
            $res = $this->m_global->update('m_request_ic', $data, ['id' => $id]);

            //======================== kirim notif email =======================================
            //get data si
            $select = "name, code";
            $arr = @$this->m_global->getDataAll('m_si AS a', null, ['id' => $id_si], $select)[0];
            $si_name = $arr->name;
            $si_code = $arr->code;

            //selec pic si dari master user ic si
            $role_id  = 5;
            $arr_user = [];
            $where      = " a.is_active='t' AND a.role_id='".$role_id."' ";
            $where      .= " AND ('".$id_si."' = ANY (string_to_array(a.id_si,', ')))";
            $arr        = @$this->m_global->getDataAll('m_user_ic_si AS a', null, $where, 'a.nip');
            foreach($arr as $row){ $arr_user[$row->nip] = $row->nip;}

            //kirim ke user pic si
            $arr_nip    = join("','",$arr_user);
            $where      = "a.nip IN('".$arr_nip."') AND a.is_active='t'";
            $arr        = @$this->m_global->getDataAll('sys_user AS a', null, $where, 'a.nip, a.fullname, a.email, a.title, a.role_id');
            // echo '<pre>';print_r($arr);exit;
            
            foreach($arr as $row){
                $data = [];
                $data['nip']            = $row->nip;
                $data['fullname']       = $row->fullname;
                $data['email']          = $row->email;
                $data['title']          = $row->title;
                $data['status']         = $status_new;
                $data['si_name']        = $si_name;
                $data['si_code']        = $si_code;
                $data['request_by']     = h_session('NAME');
                $data['request_date']   = date('Y-m-d H:i:s');

                $token = h_insert_token('request_approval_request_ic',$row->nip, '30');
                $link = site_url().'login/redirect_page/request_approval_request_ic/'.$token.'/'.$row->nip.'/'.$role_id.'/'.$id;
                $data['link'] = $link;

                $to         = h_email_to($row->email);
                $from       = 'noreply@indonesiapower.co.id';
                $title      = "Request Approval IC";
                $subject    = 'Request Approval IC';
                $data['subject'] = $subject;
                
                //untuk cek html
                // $this->load->view($this->url.'/v_request_ic_email_request_approval', $data);

                //kirim email html
                $html = $this->load->view($this->url.'/v_request_ic_email_request_approval', $data, TRUE);
                h_kirim_email_ip($from, $to, null, null, $title, $subject, $html);

                //============================ notif inbox ====================================
                //delete inbox
                $data = [];
                $data['element']        = "SI - ".$si_code;
                $data['type_inbox']     = "SI";
                $data['param_id']       = $id;
                $data['review_status']  = 18;
                $data['request_by']     = h_session('USER_ID');
                $data['nip']            = $row->nip;
                $data['role_id']        = $role_id;
                $result = $this->m_global->delete('m_inbox', $data);
                //============================================================================

            }


            //result
            $res['message'] = 'Send Approval Success!';
            echo json_encode($res);
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
            $res = $this->m_global->update('m_request_ic', $data, ['id' => $id]);
            if($val == 't'){
                $res['message'] = 'Active Success!';
            }else{
                $res['message'] = 'Delete Success!';
            }
            
            echo json_encode($res);
        // }
    }

    public function select_si()
    {
        if(isset($_REQUEST['q'])){
            $q = strtolower($_REQUEST['q']);
            $id_bsc = @$_REQUEST['id_bsc'];
            $where = "is_active = 't' AND status_si = '3'";
            if(!empty($_REQUEST['q'])){
                $where .= " AND LOWER(name) LIKE '%".$q."%'";
            }
            if(!empty($id_bsc)){
                $where .= " AND id_bsc = '".$id_bsc."'";
            }
            //cek pic ic si
            if(h_session('ROLE_ID') == '9' || h_session('ROLE_ID') == '5'){
                $nip = h_session('NIP');
                $where2 = " nip = '".$nip."'";
                $id_si = @$this->m_global->getDataAll('m_user_ic_si AS a', null, $where2, 'id_si')[0]->id_si;
                if($id_si != ''){
                    $where .= " AND id IN(".$id_si.")";
                }else{
                    //kosong
                    $data = [];
                    echo json_encode(['item' => $data]);exit;
                }
            }
            
            //select data
            $select = "a.id, a.code, a.name, a.pic_si";
            $arr = @$this->m_global->getDataAll('m_si AS a', null, $where, $select, null, "a.code ASC");
            
            $data = [];
            for ($i=0; $i < count($arr); $i++) {
                $name = '<b>['.$arr[$i]->code.']</b> '.$arr[$i]->name;
                $data[$i] = ['id' => $arr[$i]->id,  'name' => $name ];
            }
            echo json_encode(['item' => $data]);
        }else{
            $id = $_REQUEST['id'];
            $where = ['id' => $id];
            $select = "a.id, a.code, a.name, a.pic_si";
            $arr = @$this->m_global->getDataAll('m_si AS a', NULL,$where, $select, NULL,NULL, 0,10);
            $name = '<b>['.$arr[0]->code.']</b> '.$arr[0]->name;
            $data[] = [ 'id' => @$arr[0]->id,  'name' => @$name ];
            echo json_encode($data);
        }
    }

    public function select_si_pic()
    {
        if(isset($_REQUEST['q'])){
            $q = strtolower($_REQUEST['q']);
            $id_bsc = @$_REQUEST['id_bsc'];
            $where = "is_active = 't' AND status_si = '3'";
            if(!empty($_REQUEST['q'])){
                $where .= " AND LOWER(name) LIKE '%".$q."%'";
            }
            if(!empty($id_bsc)){
                $where .= " AND id_bsc = '".$id_bsc."'";
            }

            //cek pic ic si
            if(h_session('ROLE_ID') == '9' || h_session('ROLE_ID') == '5'){
                $nip = h_session('NIP');
                $where2 = " nip = '".$nip."'";
                $id_si = @$this->m_global->getDataAll('m_user_ic_si AS a', null, $where2, 'id_si')[0]->id_si;
                if($id_si != ''){
                    $where .= " AND a.id IN(".$id_si.")";
                }else{
                    //kosong
                    $data = [];
                    echo json_encode(['item' => $data]);exit;
                }
            }
            //select data
            $select = "a.id, a.code, a.name, a.pic_si, a.start_date, a.end_date, ";
            $select .= "(SELECT DISTINCT STRING_AGG ( b.\"SINGKATAN_POSISI\" :: CHARACTER VARYING, ',' )
                            FROM \"ERP_STO_REAL\" b  
                            WHERE b.\"POSITION_ID\" ::text = ANY (string_to_array(a.pic_si:: CHARACTER VARYING,', ')::text[])
                        ) AS pic_si_name, ";
            $select .= "(SELECT DISTINCT STRING_AGG ( b.\"singkatan_posisi\" :: CHARACTER VARYING, ',' )
                            FROM \"m_pic\" b  
                            WHERE b.\"position_id_new\" ::text = ANY (string_to_array(a.pic_si:: CHARACTER VARYING,', ')::text[])
                        ) AS pic_si_name2";
            $arr = @$this->m_global->getDataAll('m_si AS a', null, $where, $select, null, "a.code ASC");
            $data = [];
            for ($i=0; $i < count($arr); $i++) {
                $name = '<b>['.$arr[$i]->code.']</b> '.$arr[$i]->name;
                $pic_si_name = $arr[$i]->pic_si_name.''.$arr[$i]->pic_si_name2;
                $data[$i] = ['id' => $arr[$i]->id,  'name' => $name,  
                                'pic_si' => $arr[$i]->pic_si, 'pic_si_name' => $pic_si_name
                            ];
            }
            echo json_encode(['item' => $data]);
        }else{
            $id = $_REQUEST['id'];
            $where = ['id' => $id];
            $select = "a.id, a.code, a.name, a.pic_si, a.start_date, a.end_date, ";
            $select .= "(SELECT DISTINCT STRING_AGG ( b.\"SINGKATAN_POSISI\" :: CHARACTER VARYING, ',' )
                            FROM \"ERP_STO_REAL\" b  
                            WHERE b.\"POSITION_ID\" ::text = ANY (string_to_array(a.pic_si:: CHARACTER VARYING,', ')::text[])
                        ) AS pic_si_name, ";
            $select .= "(SELECT DISTINCT STRING_AGG ( b.\"singkatan_posisi\" :: CHARACTER VARYING, ',' )
                            FROM \"m_pic\" b  
                            WHERE b.\"position_id_new\" ::text = ANY (string_to_array(a.pic_si:: CHARACTER VARYING,', ')::text[])
                        ) AS pic_si_name2";
            $arr = @$this->m_global->getDataAll('m_si AS a', NULL,$where, $select, NULL,NULL, 0,10);
            $name = '<b>['.$arr[0]->code.']</b> '.$arr[0]->name;
            $pic_si_name = $arr[0]->pic_si_name.''.$arr[0]->pic_si_name2;
            $data[] = [ 'id' => @$arr[0]->id,  'name' => @$name,  
                        'pic_si' => $arr[0]->pic_si, 'pic_si_name' => $pic_si_name
                    ];
            echo json_encode($data);
        }
    }


    public function select_pic_request_ic()
    {

        if(isset($_REQUEST['q'])){

            //dari table ERP_STO_REAL
            $q          = $_REQUEST['q'];
            $where      = " \"POSISI\" != '' ";
            if($q != ''){ $where .= ' AND LOWER("POSISI") LIKE \'%'.strtolower($q).'%\' OR LOWER("SINGKATAN_POSISI") LIKE \'%'.strtolower($q).'%\'' ; }
            $select     = '"POSISI" AS "JABATAN", "SINGKATAN_POSISI", "POSITION_ID"';
            $parent     = $this->m_global->getDataAll('ERP_STO_REAL', NULL, $where, $select, NULL, '"SINGKATAN_POSISI" ASC',0,20);
            $data       = [];
            for ($i=0; $i < count($parent); $i++) {
                $name = '<b>'.$parent[$i]->SINGKATAN_POSISI.'</b> ["'.$parent[$i]->JABATAN.'"]';
                $data[] = ['id' => $parent[$i]->POSITION_ID, 'name' => $name];
            }

            //dari table m_pic
            $q          = $_REQUEST['q'];
            $where      = " posisi != '' ";
            if($q != ''){ $where .= ' AND LOWER(posisi) LIKE \'%'.strtolower($q).'%\' OR LOWER(singkatan_posisi) LIKE \'%'.strtolower($q).'%\'' ; }
            $select     = 'posisi, singkatan_posisi, position_id, position_id_new';
            $parent     = $this->m_global->getDataAll('m_pic', NULL, $where, $select, NULL, 'singkatan_posisi ASC',0,20);
            for ($a=0; $a < count($parent); $a++) {
                $name = '<b>'.$parent[$a]->singkatan_posisi.'</b> ["'.$parent[$a]->posisi.'"]';
                $data[] = ['id' => $parent[$a]->position_id_new, 'name' => $name];
            }
            echo json_encode(['item' => $data]);

        }else{

            //dari table ERP_STO_REAL
            $id         = str_replace(",", "','",  $_REQUEST['id']);
            $where      = "\"POSITION_ID\" IN ('".$id."')";
            $select     = '"POSISI" AS "JABATAN", "SINGKATAN_POSISI", "POSITION_ID"';
            $parent     = $this->m_global->getDataAll('ERP_STO_REAL', NULL, $where, $select);
            $data       = [];
            for ($i=0; $i < count($parent); $i++) {
                $name = '<b>'.$parent[$i]->SINGKATAN_POSISI.'</b> ["'.$parent[$i]->JABATAN.'"]';
                $data[] = ['id' => $parent[$i]->POSITION_ID, 'name' => $name];
            }

            //dari table m_pic
            $id         = str_replace(",", "','",  $_REQUEST['id']);
            $where      = " posisi != '".$id."' ";
            $select     = 'posisi, singkatan_posisi, position_id, position_id_new';
            $parent     = $this->m_global->getDataAll('m_pic', NULL, $where, $select, NULL, 'singkatan_posisi ASC',0,20);
            for ($a=0; $a < count($parent); $a++) {
                $name = '<b>'.$parent[$a]->singkatan_posisi.'</b> ["'.$parent[$a]->posisi.'"]';
                $data[] = ['id' => $parent[$a]->position_id_new, 'name' => $name];
            }
            echo json_encode($data);

        }
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

            //update
            $id = $this->input->post('id');
            $val = $this->input->post('val');
            $id_si = $this->input->post('id_si');
            $keterangan_approval = @$this->input->post('keterangan_approval');
            $status_new = $val;
            $data['status_request'] = $val;
            $data['keterangan_approval'] = $keterangan_approval;
            $res = $this->m_global->update('m_request_ic', $data, ['id' => $id]);

            //============================ kirim notif email ==================================================
            //get data si
            $select = "name, code";
            $arr = @$this->m_global->getDataAll('m_si AS a', null, ['id' => $id_si], $select)[0];
            $si_name = $arr->name;
            $si_code = $arr->code;

            //kirim email
            $role_id  = 9;
            $where      = "a.is_active='t' AND a.id='".$id."'";
            $request_from = @$this->m_global->getDataAll('m_request_ic AS a', null, $where, 'a.request_by')[0]->request_by;

            //kirim ke user pic si
            $where      = "a.is_active='t' AND id='".$request_from."'";
            $arr        = @$this->m_global->getDataAll('sys_user AS a', null, $where, 'a.nip, a.fullname, a.email, a.title, a.role_id');
            foreach($arr as $row){

                $data = [];
                $data['nip']        = $row->nip;
                $data['fullname']   = $row->fullname;
                $data['email']      = $row->email;
                $data['title']      = $row->title;
                $data['status']     = $status_new;
                $data['si_name']    = $si_name;
                $data['si_code']    = $si_code;
                $data['request_by']   = h_session('NAME');
                $data['request_date'] = date("Y-m-d H:i:s");

                $token = h_insert_token('request_approval_monev_si',$row->nip, '30');
                $link = site_url().'login/redirect_page/request_approval_request_ic/'.$token.'/'.$row->nip.'/'.$role_id.'/'.$id;
                $data['link'] = $link;

                $to         = h_email_to($row->email);
                $from       = 'noreply@indonesiapower.co.id';
                $title      = "Request Approval IC";
                $subject    = 'Request Approval IC';
                $data['subject'] = $subject;
                
                //untuk cek html
                // $this->load->view($this->url.'/v_request_ic_email_request_approval', $data);

                //kirim email html
                $html = $this->load->view($this->url.'/v_request_ic_email_request_approval', $data, TRUE);
                h_kirim_email_ip($from, $to, null, null, $title, $subject, $html);


                //============================ notif inbox ====================================
                //keterangan
                $isi = '';
                if($status_new == '3'){ 
                    $isi = 'Telah <span class="label btn_keterangan_approval" keterangan="'.$keterangan_approval.'" style="cursor:pointer;color:#fff;background-color:#5cb85c;">DISETUJUI</span>';
                }else{
                    $isi = 'Telah <span class="label label-danger btn_keterangan_approval" keterangan="'.$keterangan_approval.'" style="cursor:pointer;">DITOLAK</span>';
                }
                //insert to inbox
                $data = [];
                $data['element']     = "SI - ".$si_code;
                $data['type_inbox']  = "SI";
                $data['description'] = "Request Approval Initiative Charter(IC), <br>
                                        ".$isi.",<br>
                                        Untuk SI: (".$si_code.") ".h_text_br($si_name,40);
                $data['param_id']       = $id;
                $data['review_status']  = 18;
                $data['request_by']     = h_session('USER_ID');
                $data['request_date']   = date('Y-m-d H:i:s');
                $data['nip']            = $row->nip;
                $data['role_id']        = $role_id;
                $data['redirect_page']  = $link;
                $result = $this->m_global->insert('m_inbox', $data);
                //=========================================================================

            }

            //result
            $res['message'] = 'Success!';
            echo json_encode($res);
            
        // }
    }


    public function finished_request() {
        //cek csrf token
        // $ex_csrf_token = @$this->input->post('token');
        // $res = [];
        // if (csrf_get_token() != $ex_csrf_token){
        //     $res['status']  = 0;
        //     $res['message'] = $this->csrf_message;
        //     echo json_encode($res);
        // }else{
            $id = $this->input->post('id');
            $data['status_finished'] = 1;
            $res = $this->m_global->update('m_request_ic', $data, ['id' => $id]);
            $res['message'] = 'Success!';
            echo json_encode($res);
        // }
    }


    public function change_status_send_to_admin() {
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
            $id_si = @$this->input->post('id_si');
            $keterangan = $this->input->post('keterangan');
            $status_new = $val;
            $data['status_send_to_admin'] = $val;
            $data['keterangan_send_to_admin'] = $keterangan;
            $res = $this->m_global->update('m_request_ic', $data, ['id' => $id]);


            //=========================== kirim notif email ==================================
            //get data si
            $select = "name, code";
            $arr = @$this->m_global->getDataAll('m_si AS a', null, ['id' => $id_si], $select)[0];
            $si_name = $arr->name;
            $si_code = $arr->code;

            //kirim email
            $role_id = 1;
            $nip = 1;
            $data = [];
            $data['nip']        = $nip;
            $data['fullname']   = "ADMIN";
            $data['email']      = "adminsimo@gmail.com";
            $data['title']      =  h_email_admin();
            $data['status']     = $status_new;
            $data['si_name']    = $si_name;
            $data['si_code']    = $si_code;
            $data['request_by']   = h_session('NAME');
            $data['request_date'] = date("Y-m-d H:i:s");

            $token = h_insert_token('request_approval_request_ic','1', '30');
            $link = site_url().'login/redirect_page/request_ic_send_to_admin/'.$token.'/'.$nip.'/'.$role_id.'/'.$id;
            $data['link'] = $link;

            $to         = h_email_admin();
            $from       = 'noreply@indonesiapower.co.id';
            $title      = "Request Assist Admin";
            $subject    = "Request Assist Admin";
            $data['subject'] = $subject;
            
            //untuk cek html
            // $this->load->view($this->url.'/v_request_ic_email_send_to_admin', $data);

            //kirim email html
            $html = $this->load->view($this->url.'/v_request_ic_email_send_to_admin', $data, TRUE);
            h_kirim_email_ip($from, $to, null, null, $title, $subject, $html);
            //==================================================================================


            //===========================  notif inbox ========================================
            //insert to inbox
            $data = [];
            $data['element']     = "SI - ".$si_code;
            $data['type_inbox']  = "Request Asisst Admin";
            $data['description'] = "Request Asisst Admin, <br>Pengisian Data Initiative Charter(IC), <br>
                                    Untuk SI: (".$si_code.') '.h_text_br($si_name,40);
            $data['param_id']       = $id;
            $data['review_status']  = 18;
            $data['request_by']     = h_session('USER_ID');
            $data['request_date']   = date('Y-m-d H:i:s');
            $data['nip']            = $nip;
            $data['role_id']        = $role_id;
            $data['redirect_page']  = $link;
            $result = $this->m_global->insert('m_inbox', $data);
            //==================================================================================


            //result
            $res['message'] = 'Success!';
            echo json_encode($res);
        // }
    }

    public function change_status_done_request_admin() {
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
            $id_si = $this->input->post('id_si');
            $val = $this->input->post('val');
            $keterangan = $this->input->post('keterangan');
            $status_new = $val;
            $data['status_send_to_admin'] = $val;
            $data['keterangan_done_request_admin'] = $keterangan;
            $res = $this->m_global->update('m_request_ic', $data, ['id' => $id]);

            //=========================== kirim notif email ==================================
            //get data si
            $select = "name, code";
            $arr = @$this->m_global->getDataAll('m_si AS a', null, ['id' => $id_si], $select)[0];
            $si_name = $arr->name;
            $si_code = $arr->code;

            //kirim email
            $role_id    = 9;
            $where      = "a.is_active='t' AND a.id='".$id."'";
            $request_from = @$this->m_global->getDataAll('m_request_ic AS a', null, $where, 'a.request_by')[0]->request_by;

            //kirim ke user pic si
            $where      = "a.is_active='t' AND id='".$request_from."'";
            $arr        = @$this->m_global->getDataAll('sys_user AS a', null, $where, 'a.nip, a.fullname, a.email, a.title, a.role_id');
            foreach($arr as $row){

                $data = [];
                $data['nip']        = $row->nip;
                $data['fullname']   = $row->fullname;
                $data['email']      = $row->email;
                $data['title']      = $row->title;
                $data['status']     = $status_new;
                $data['si_name']    = $si_name;
                $data['si_code']    = $si_code;
                $data['request_by']   = h_session('NAME');
                $data['request_date'] = date("Y-m-d H:i:s");

                $token = h_insert_token('request_approval_monev_si',$row->nip, '30');
                $link = site_url().'login/redirect_page/request_ic_done_request_admin/'.$token.'/'.$row->nip.'/'.$role_id.'/'.$id;
                $data['link'] = $link;
                
                $to         = h_email_to($row->email);
                $from       = 'noreply@indonesiapower.co.id';
                $title      = "Request Assist Admin";
                $subject    = "Request Assist Admin";
                $data['subject'] = $subject;
                
                //untuk cek html
                // $this->load->view($this->url.'/v_request_ic_email_done_request_admin', $data);

                //kirim email html
                $html = $this->load->view($this->url.'/v_request_ic_email_done_request_admin', $data, TRUE);
                h_kirim_email_ip($from, $to, null, null, $title, $subject, $html);


                //===========================  notif inbox ========================================
                //insert to inbox
                $data = [];
                $data['element']     = "SI - ".$si_code;
                $data['type_inbox']  = "Done Request Asisst Admin";
                $data['description'] = "Done Request Asisst Admin, <br>Pengisian Data Initiative Charter(IC), <br>
                                        Untuk SI: (".$si_code.') '.h_text_br($si_name,40);
                $data['param_id']       = $id;
                $data['review_status']  = 18;
                $data['request_by']     = h_session('USER_ID');
                $data['request_date']   = date('Y-m-d H:i:s');
                $data['nip']            = $row->nip;
                $data['role_id']        = $role_id;
                $data['redirect_page']  = $link;
                $result = $this->m_global->insert('m_inbox', $data);
                //==================================================================================

            }


            //result
            $res['message'] = 'Success!';
            echo json_encode($res);
        // }
    }
    

    // =========================== upload file ============================
    public function list_file_request_ic($html=FALSE, $id='')
    {   
        if($html == FALSE){
            $id = $this->input->post('id');
        }
        $where = ['id_from'=>$id, 'type'=>'request_ic', 'is_active'=>'t'];
        $file_request_ic = @$this->m_global->getDataAll('m_file', null, $where, '*');
        $data['file_request_ic']  = $file_request_ic;

        $data['url'] = $this->url;
        $isi = $this->template->display_ajax($this->url.'/v_request_ic_list_file', $data, NULL, NULL, $html);
        if($html){return $isi;}else{echo $isi;}
    }
    
    public function load_popup_upload_request_ic()
    {
        $id = $this->input->post('id');
        $data['id'] = $id;
        
        $data['html_list_file_upload_request_ic'] = @$this->list_file_upload_request_ic(TRUE,$id);

        $data['url'] = $this->url;
        $this->template->display_ajax($this->url.'/v_request_ic_upload_file', $data);
    }
    
    public function list_file_upload_request_ic($html=FALSE, $id='')
    {   
        if($html == FALSE){
            $id = $this->input->post('id');
        }
        $where = ['id_from'=>$id, 'type'=>'request_ic', 'is_active'=>'t'];
        $file_request_ic = @$this->m_global->getDataAll('m_file', null, $where, '*');
        $data['file_request_ic']  = $file_request_ic;

        $data['url'] = $this->url;
        $isi = $this->template->display_ajax($this->url.'/v_request_ic_list_file_upload', $data, NULL, NULL, $html);
        if($html){return $isi;}else{echo $isi;}
    }

    public function upload_file_request_ic(){
        $date_now                   = date('Y-m-d H:i:s');
        $file_name_origin           = h_replace_file_name($_FILES['userfile']['name']); 
        $type_file                  = h_file_type($_FILES['userfile']['name']);
        $id                         = $this->input->post('id');
        $tgl                        = date('Ymdhis');
        $random                     = rand(1,100);
        //upload
        $folder                     = './public/files/request_ic/';
        $file_name                  = 'request_ic_'.$id.'_'.$tgl.'_'.$random;
        $input_name                 = array_keys($_FILES)[0];
        $file_type                  = '*'; 
        $upload = h_upload($folder,$file_name,$input_name,$file_type);
        //insert data
        if($upload == TRUE){
            $data = array(
                'id_from'          => $id,
                'type'             => 'request_ic',
                'file_name'        => $file_name.'.'.$type_file,
                'created_by'       => h_session('USERNAME'),
                'created_date'     => date("Y-m-d H:i:s"),
            );
            $result = $this->db->insert('m_file', $data);
        }
    }

    public function delete_file_request_ic()
    {
        //param
        $param = $this->input->post();
        $file_name = $param['file_name'];
        $id = $param['id'];
        //delete data
        $result = $this->m_global->update('m_file', ['is_active'=>'f'], ['id' => $id]);
        //delete file
        // unlink(FCPATH."public/files/so/".$file_name);
        //message
        $res['status']  = ($result['status'] ? '1':'0');
        $res['message'] = 'Successfully Save Data!';
        echo json_encode($res);
    }
    // =========================== END upload file ============================


}
