<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Strategic_result extends MX_Controller {
    
    private $prefix         = 'strategic_result';
    private $table_db       = 'm_strategic_result';
    private $title          = 'Strategic Result';
    private $url            = 'app/strategic_result';

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

        //periode
        $data['periode'] = $this->m_global->getDataAll('m_periode', null, ['is_active'=>'t'], '*', null, "start_year ASC");
        //bsc
        $data['bsc'] = $this->m_global->getDataAll('m_bsc', null,  ['is_active'=>'t'], '*', null, "name ASC");
        //strategic_theme
        $data['strategic_theme'] = $this->m_global->getDataAll('m_strategic_theme', null,  ['is_active'=>'t'], '*', null, "name ASC");
        //status
        $data['status_sr'] = $this->m_global->getDataAll('m_status', null, ['is_active'=>'t', 'type'=>'SO STATUS'], '*', null, '"order" ASC');

        $js['custom']       = ['table_strategic_result'];
        $this->template->display($this->url.'/v_'.$this->prefix, $data, $js);
    }

    public function table_strategic_result()
    {    
        // load model view 
        $this->load->model('app/m_strategic_result','m_strategic_result');

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
        // echo '<pre>';print_r($_REQUEST);exit;
        $id_periode = @$_REQUEST['global_id_periode'];
        $id_bsc = @$_REQUEST['global_id_bsc'];
        $id_strategic_theme = @$_REQUEST['global_id_strategic_theme'];
        if($id_periode != ''){ $where['a.id_periode'] = $id_periode; }
        if($id_bsc != ''){ $where['a.id_bsc'] = $id_bsc; }
        if($id_strategic_theme != ''){ $where['a.id_strategic_theme'] = $id_strategic_theme; }

        //filtering
        $search = [];
        if(isset($_REQUEST['filter'])){
            foreach ($_REQUEST['filter'] as $row) {

                //default parameter
                if(@$row['name'] != ''){

                    $val= $row['value']; $tipe = $row['tipe']; $search[] = $row['name']; 
                    // $name = @$row['name'];
                    $name = $this->m_strategic_result->select($row['name']); $name = str_replace(' AS '.$row['name'], ' ', $name );

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
        $table = "m_strategic_result AS a";
        $join  = NULL;

        //select 
        //select 
        $select = [ 'id','code_strategic_result','name_strategic_result','name_strategic_theme','name_periode','name_bsc',
                        'name_pic_sr','status_sr','name_status_sr','description','target','name_polarisasi',
                        'is_active','created_date','created_by','updated_date','updated_by'
                    ];
        $select = array_unique(array_merge($select, $search));
        $select = $this->m_strategic_result->select($select);

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
        // echo '<pre>';print_r($result);exit;

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
                if($rows->status_sr == '1'){
                    $btn_edit = '<button title="Edit" id="'.$id.'" class="btn btn-sm btn-primary btn_edit"><i class="fa fa-edit"></i></button>';
                    $btn_approval = '<button title="Approval" id="'.$id.'" val="2" class="btn btn-sm  btn-warning btn_view"><i class="fa fa-envelope-o"></i></button>';
                    $action =  @$btn_approval.@$btn_edit.@$btn_delete;
                }
                if($rows->status_sr == '3'){
                    $btn_copy   = '<button title="Copy" id="'.$id.'" class="btn btn-sm btn-info btn_copy"><i class="fa fa-copy"></i></button>';
                    $btn_edit   = '<button title="Edit" id="'.$id.'" class="btn btn-sm btn-primary btn_edit"><i class="fa fa-edit"></i></button>';
                    $btn_monev_sr = '<a href="'.base_url('app/monev_sr/index/').$id.'" title="Monitoring Strategic Result" class="btn btn-sm btn-warning"><i class="fa fa-arrow-right"></i></a>';
                    $action =  @$btn_edit.$btn_copy.@$btn_monev_sr;
                }
                if($rows->status_sr == '4'){
                    $btn_approval = '<button title="Approval" id="'.$id.'" val="2" class="btn btn-sm  btn-warning btn_view"><i class="fa fa-envelope-o"></i></button>';
                    $btn_edit = '<button title="Edit" id="'.$id.'" class="btn btn-sm btn-primary btn_edit"><i class="fa fa-edit"></i></button>';
                    $action =  @$btn_approval.@$btn_edit.@$btn_delete;
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
            $isi['action']              = @$action;

            $isi['name']                = h_read_more($rows->name,20);
            $isi['code']                = $rows->code;
            $isi['description']         = h_read_more($rows->description,20);

            $isi['indikator']           = h_read_more($rows->indikator,20);
            $isi['polarisasi']          = $rows->name_polarisasi;
            $isi['ukuran']              = $rows->ukuran;
            $isi['target']              = $rows->target;

            $isi['name_periode']            = h_read_more($rows->name_periode,20);
            $isi['name_bsc']                = h_read_more($rows->name_bsc,20);
            $isi['name_strategic_theme']    = h_read_more($rows->name_strategic_theme,20);
            $isi['name_pic_sr']             = h_read_more($rows->name_pic_sr,20);
            $isi['name_status_sr']          = $rows->name_status_sr;

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

        //periode
        $data['periode'] = $this->m_global->getDataAll('m_periode', null, ['is_active'=>'t'], '*', null, "start_year ASC");
        //bsc
        $data['bsc'] = $this->m_global->getDataAll('m_bsc', null,  ['is_active'=>'t'], '*', null, "name ASC");
        //strategic_theme
        $data['strategic_theme'] = $this->m_global->getDataAll('m_strategic_theme', null, ['is_active'=>'t'], '*', null, "name ASC");

        //polarisasi
        $data['polarisasi'] = $this->m_global->getDataAll('m_status', null, ['is_active'=>'t', 'type'=>'Polarisasi'], '*', null, '"order" ASC');

        $this->template->display_ajax($this->url.'/v_strategic_result_add', $data);
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

        //periode
        $data['periode'] = $this->m_global->getDataAll('m_periode', null, ['is_active'=>'t'], '*', null, "start_year ASC");
        //bsc
        $data['bsc'] = $this->m_global->getDataAll('m_bsc', null,  ['is_active'=>'t'], '*', null, "name ASC");
        //strategic_theme
        $data['strategic_theme'] = $this->m_global->getDataAll('m_strategic_theme', null, ['is_active'=>'t'], '*', null, "name ASC");
        //polarisasi
        $data['polarisasi'] = $this->m_global->getDataAll('m_status', null, ['is_active'=>'t', 'type'=>'Polarisasi'], '*', null, '"order" ASC');

        //pic
        $where = " '4' = ANY (string_to_array(role_id,',')) ";
        $data['pic'] = $this->m_global->getDataAll('sys_user', null, ['is_active'=>'t'], '*', $where, "fullname ASC");

        //file
        $data['html_list_file_sr'] = $this->list_file_sr(TRUE,$id);
        
        //get data
        $id = $this->input->post('id');
        $data['data'] = $this->m_global->getDataAll('m_strategic_result', null, ['id'=>$id], '*')[0];
        $this->template->display_ajax($this->url.'/v_strategic_result_edit', $data);
    }

    public function load_copy() {
        csrf_init();
        $data['url'] = $this->url;

        //periode
        $data['periode'] = $this->m_global->getDataAll('m_periode', null, ['is_active'=>'t'], '*', null, "start_year ASC");
        //bsc
        $data['bsc'] = $this->m_global->getDataAll('m_bsc', null,  ['is_active'=>'t'], '*', null, "name ASC");
        //strategic_theme
        $data['strategic_theme'] = $this->m_global->getDataAll('m_strategic_theme', null, ['is_active'=>'t'], '*', null, "name ASC");
        //polarisasi
        $data['polarisasi'] = $this->m_global->getDataAll('m_status', null, ['is_active'=>'t', 'type'=>'Polarisasi'], '*', null, '"order" ASC');

        //pic
        $where = " '4' = ANY (string_to_array(role_id,',')) ";
        $data['pic'] = $this->m_global->getDataAll('sys_user', null, ['is_active'=>'t'], '*', $where, "fullname ASC");

        //get data
        $id = $this->input->post('id');
        $data['data'] = @$this->m_global->getDataAll('m_strategic_result', null, ['id'=>$id], '*')[0];
        $this->template->display_ajax($this->url.'/v_strategic_result_copy', $data);
    }


    public function save_add() {

        // //cek csrf token
        // $ex_csrf_token = @$this->input->post('ex_csrf_token');
        // $res = [];
        // if (csrf_get_token() != $ex_csrf_token){
        //     $res['status']  = 2;
        //     $res['message'] = $this->csrf_message;
        //     echo json_encode($res);
        // }else{

            // Set Rule Login Form
            $this->form_validation->set_rules('id_periode', 'periode', 'trim|required');
            $this->form_validation->set_rules('id_bsc', 'BSC', 'trim|required');
            $this->form_validation->set_rules('id_strategic_theme', 'Strategic Theme', 'trim|required');
            $this->form_validation->set_rules('pic_sr', 'PIC', 'trim|required');
            $this->form_validation->set_rules('code', 'Code', 'trim|required');
            $this->form_validation->set_rules('name', 'Strategic Result', 'trim|required');
            $this->form_validation->set_rules('indikator', 'Indikator', 'trim|required');
            $this->form_validation->set_rules('polarisasi', 'Polarisasi', 'trim|required');
            $this->form_validation->set_rules('ukuran', 'Ukuran', 'trim|required');
            $polarisasi = @$this->input->post('polarisasi');
            if($polarisasi == '10'){
                $this->form_validation->set_rules('target_from', 'Target From', 'trim|required');
                $this->form_validation->set_rules('target_to', 'Target To', 'trim|required');
            }else{
                $this->form_validation->set_rules('target', 'Target', 'trim|required');
            }

            //cek validasi
            if ($this->form_validation->run($this)) {

                //insert data
                $id_periode = @$this->input->post('id_periode');
                $data['id_periode']         = @$this->input->post('id_periode');
                $data['id_strategic_theme'] = @$this->input->post('id_strategic_theme');
                $data['id_bsc']             = @$this->input->post('id_bsc');
                $data['pic_sr']             = @$this->input->post('pic_sr');
                $data['indikator']          = @$this->input->post('indikator');
                $data['polarisasi']         = @$this->input->post('polarisasi');
                $data['ukuran']             = @$this->input->post('ukuran');
                //cek polarisasi
                $polarisasi = @$this->input->post('polarisasi');
                if($polarisasi == '10' ){
                    $data['target_from'] = @$this->input->post('target_from');
                    $data['target_to']   = @$this->input->post('target_to');
                }else{
                    $data['target'] = @$this->input->post('target');
                }
                $data['name']               = @$this->input->post('name');
                $data['code']               = @$this->input->post('code');
                $data['description']        = @$this->input->post('description');
                $data['created_date']       = date("Y-m-d H:i:s");
                $data['created_by']         = h_session('USERNAME');
                $result = $this->m_global->insert('m_strategic_result', $data);

                //periode dan target
                $id_periode =  @$this->input->post('id_periode');
                $arr = @$this->m_global->getDataAll('m_periode', null, ['id'=>$id_periode], 'start_year,end_year')[0];
                $start_year  = @$arr->start_year;
                $end_year    = @$arr->end_year;

                //insert target year dan target TW
                $id_sr = $this->db->insert_id();
                if($id_sr != ''){
                    
                    for($y=$start_year;$y<=$end_year;$y++){
                        $data2['id_sr'] = $id_sr;
                        $data2['year']      = $y;
                        $data2['status']    = 1;
                        //polarisasi stabilze
                        if($polarisasi == '10'){
                            $target_from = str_replace(',','',@$this->input->post('target_from_'.$y));
                            $target_to   = str_replace(',','',@$this->input->post('target_to_'.$y));
                            $data2['target_from'] = ($target_from == '' ? NULL : $target_from);
                            $data2['target_to']   = ($target_to == '' ? NULL : $target_to);
                            $data2['target']      = NULL;
                        }else{
                            $target = str_replace(',','',@$this->input->post('target_'.$y));
                            $data2['target_from'] = NULL;
                            $data2['target_to']   = NULL;
                            $data2['target']      = ($target == '' ? NULL : $target);
                        }
                        $result = $this->m_global->insert('m_sr_target_year', $data2);

                        for($m=1;$m<=12;$m++){
                            if($m == 6 || $m == 12){
                                $data3['id_sr']     = $id_sr;
                                $data3['year']      = $y;
                                $data3['month']     = $m;
                                //polarisasi stabilze
                                if($polarisasi == '10'){
                                    $target_month_from = str_replace(',','',@$this->input->post('target_month_from_'.$y.'_'.$m));
                                    $target_month_to   = str_replace(',','',@$this->input->post('target_month_to_'.$y.'_'.$m));
                                    $data3['target_from'] = ($target_month_from == '' ? NULL : $target_month_from);
                                    $data3['target_to'] = ($target_month_to == '' ? NULL : $target_month_to);
                                    $data3['target'] = NULL;
                                }else{
                                    $target_month = str_replace(',','',@$this->input->post('target_month_'.$y.'_'.$m));
                                    $data3['target_from'] = NULL;
                                    $data3['target_to'] = NULL;
                                    $data3['target'] = ($target_month == '' ? NULL : $target_month);
                                }
                                $result = $this->m_global->insert('m_sr_target_month', $data3);
                            }
                        }
                    }
                }


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

        // //cek csrf token
        // $ex_csrf_token = @$this->input->post('ex_csrf_token');
        // $res = [];
        // if (csrf_get_token() != $ex_csrf_token){
        //     $res['status']  = 2;
        //     $res['message'] = $this->csrf_message;
        //     echo json_encode($res);
        // }else{

            // Set Rule Login Form
            $this->form_validation->set_rules('id_periode', 'periode', 'trim|required');
            $this->form_validation->set_rules('id_bsc', 'BSC', 'trim|required');
            $this->form_validation->set_rules('id_strategic_theme', 'Strategic Theme', 'trim|required');
            $this->form_validation->set_rules('pic_sr', 'PIC', 'trim|required');
            $this->form_validation->set_rules('code', 'Code', 'trim|required');
            $this->form_validation->set_rules('name', 'Strategic Result', 'trim|required');
            $this->form_validation->set_rules('indikator', 'Indikator', 'trim|required');
            $this->form_validation->set_rules('polarisasi', 'Polarisasi', 'trim|required');
            $this->form_validation->set_rules('ukuran', 'Ukuran', 'trim|required');

            if ($this->form_validation->run($this)) {

                //update data
                $id = $this->input->post('id');
                $id_sr = $id;
                $polarisasi     = @$this->input->post('polarisasi');
                
                $data['id_periode']         = @$this->input->post('id_periode');
                $data['id_strategic_theme'] = @$this->input->post('id_strategic_theme');
                $data['id_bsc']             = @$this->input->post('id_bsc');
                $data['pic_sr']             = @$this->input->post('pic_sr');
                
                $data['indikator']          = @$this->input->post('indikator');
                $data['polarisasi']         = @$this->input->post('polarisasi');
                $data['ukuran']             = @$this->input->post('ukuran');
                //polarisasi stabilze
                if($polarisasi == '10'){
                    $target_from = str_replace(',','',@$this->input->post('target_from'));
                    $target_to = str_replace(',','',@$this->input->post('target_to'));
                    $data['target_from'] = ($target_from == '' ? NULL : $target_from);
                    $data['target_to']   = ($target_to == '' ? NULL : $target_to);
                    $data['target']      = NULL;
                }else{
                    $target = str_replace(',','',@$this->input->post('target'));
                    $data['target_from'] = NULL;
                    $data['target_to']   = NULL;
                    $data['target']      = ($target == '' ? NULL : $target);
                }

                $data['name']               = @$this->input->post('name');
                $data['code']               = @$this->input->post('code');
                $data['description']        = @$this->input->post('description');
                $data['updated_date']       = date("Y-m-d H:i:s");
                $data['updated_by']         = h_session('USERNAME');
                $result = $this->m_global->update('m_strategic_result', $data, ['id' => $id]);

                // echo '<pre>';print_r($this->input->post());exit;
                //====================================== TARGET ======================================================

                //start year new and old
                $polarisasi     = @$this->input->post('polarisasi');
                $id_periode     = @$this->input->post('id_periode');
                $arr = @$this->m_global->getDataAll('m_periode', null, ['is_active'=>'t', 'id'=>$id_periode], 'start_year, end_year')[0];
                $start_year         = $arr->start_year;
                $end_year           = $arr->end_year;

                //update target year dan month
                for($y=$start_year;$y<=$end_year;$y++){
                    $data2 = $data_year = [];
                    $data2['id_sr'] = $id_sr;
                    $data2['year']      = $y;
                    //polarisasi stabilze
                    if($polarisasi == '10'){
                        $target_from = str_replace(',','',@$this->input->post('target_from_'.$y));
                        $target_to = str_replace(',','',@$this->input->post('target_to_'.$y));
                        $data_year['target_from'] = ($target_from == '' ? NULL : $target_from);
                        $data_year['target_to']   = ($target_to == '' ? NULL : $target_to);
                        $data_year['target']      = NULL;
                    }else{
                        $target = str_replace(',','',@$this->input->post('target_'.$y));
                        $data_year['target_from'] = NULL;
                        $data_year['target_to']   = NULL;
                        $data_year['target']      = ($target == '' ? NULL : $target);
                    }
                    $result = $this->m_global->update('m_sr_target_year', $data_year, $data2);
                    // echo $this->db->last_query();'<br>';

                    for($m=1;$m<=12;$m++){
                        if($m == 6 || $m == 12){
                            $data3 = $data_month = [];
                            $data3['id_sr'] = $id_sr;
                            $data3['year']      = $y;
                            $data3['month']     = $m;
                            //polarisasi stabilze
                            if($polarisasi == '10'){
                                $target_month_from = str_replace(',','',@$this->input->post('target_month_from_'.$y.'_'.$m));
                                $target_month_to = str_replace(',','',@$this->input->post('target_month_to_'.$y.'_'.$m));
                                $data_month['target_from'] = ($target_month_from == '' ? NULL : $target_month_from);
                                $data_month['target_to'] = ($target_month_to == '' ? NULL : $target_month_to);
                                $data_month['target'] = NULL;
                            }else{
                                $target_month = str_replace(',','',@$this->input->post('target_month_'.$y.'_'.$m));
                                $data_month['target_from'] = NULL;
                                $data_month['target_to'] = NULL;
                                $data_month['target'] = ($target_month == '' ? NULL : $target_month);
                            }
                            $result = $this->m_global->update('m_sr_target_month', $data_month, $data3);
                            // echo $this->db->last_query();'<br>';
                        }
                    }
                }

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
        $ex_csrf_token = @$this->input->post('token');
        $res = [];
        if (csrf_get_token() != $ex_csrf_token){
            $res['status']  = 0;
            $res['message'] = $this->csrf_message;
            echo json_encode($res);
        }else{
            $id = $this->input->post('id');
            $data['is_active'] = $this->input->post('val');
            $res = $this->m_global->update('m_strategic_result', $data, ['id' => $id]);
            $res['message'] = 'Delete Success!';
            echo json_encode($res);
        }
    }


    public function change_status() {
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
            $data['status_sr'] = $val;
            $res = $this->m_global->update('m_strategic_result', $data, ['id' => $id]);
            $res['message'] = 'Success!';
            echo json_encode($res);
        }
    }


    public function select_strategic_theme()
    {
        if(isset($_REQUEST['q'])){
            $q = strtolower($_REQUEST['q']);
            if(!empty($_REQUEST['q'])){
                $where = "LOWER(name) LIKE '%".$q."%'";
            }
            $arr = @$this->m_global->getDataAll('m_strategic_theme AS a', NULL,$where,"a.id, a.name",NULL,NULL,0,10);
            // echo $this->db->last_query();exit;    
            $data = [];
            for ($i=0; $i < count($arr); $i++) {
                $data[$i] = ['id' => $arr[$i]->id,  'name' => $arr[$i]->name ];
            }
            echo json_encode(['item' => $data]);
        }else{
            $where = ['id' => $id];
            $arr = @$this->m_global->getDataAll('m_strategic_theme AS a', NULL,$where,"a.id, a.name",NULL,NULL,0,10);
            $data[] = [ 'id' => @$arr[0]->id,  'name' => @$arr[0]->name ];
            echo json_encode($data);
        }
    }


    public function select_pic_sr()
    {

        if(isset($_REQUEST['q'])){
            $q          = $_REQUEST['q'];
            $where      = " \"POSISI\" != '' ";
            if($q != ''){ $where .= ' AND LOWER("POSISI") LIKE \'%'.strtolower($q).'%\' OR LOWER("SINGKATAN_POSISI") LIKE \'%'.strtolower($q).'%\'' ; }
            $select     = '"POSISI" AS "JABATAN", "SINGKATAN_POSISI", "POSITION_ID"';
            $parent     = $this->m_global->getDataAll('ERP_STO_REAL', NULL, $where, $select, NULL, '"SINGKATAN_POSISI" ASC',0,20);
            $data       = [];
            for ($i=0; $i < count($parent); $i++) {
                $name = '<b>'.$parent[$i]->SINGKATAN_POSISI.'</b> ["'.$parent[$i]->JABATAN.'"]';
                $data[$i] = ['id' => $parent[$i]->POSITION_ID, 'name' => $name];
            }
            echo json_encode(['item' => $data]);
        }else{
            $id         = str_replace(",", "','",  $_REQUEST['id']);
            $where      = "\"POSITION_ID\" IN ('".$id."')";
            $select     = '"POSISI" AS "JABATAN", "SINGKATAN_POSISI", "POSITION_ID"';
            $parent     = $this->m_global->getDataAll('ERP_STO_REAL', NULL, $where, $select);
            $data       = [];
            for ($i=0; $i < count($parent); $i++) {
                $name = '<b>'.$parent[$i]->SINGKATAN_POSISI.'</b> ["'.$parent[$i]->JABATAN.'"]';
                $data[$i] = ['id' => $parent[$i]->POSITION_ID, 'name' => $name];
            }
            echo json_encode($data);
        }
    }




    // =========================== upload file ============================
    public function list_file_sr($html=FALSE, $id='')
    {   
        if($html == FALSE){
            $id = $this->input->post('id');
        }
        $where = ['id_from'=>$id, 'type'=>'so', 'is_active'=>'t'];
        $file_sr = @$this->m_global->getDataAll('m_file', null, $where, '*');
        $data['file_sr']  = $file_sr;

        $data['url'] = $this->url;
        $isi = $this->template->display_ajax($this->url.'/v_strategic_result_list_file', $data, NULL, NULL, $html);
        if($html){return $isi;}else{echo $isi;}
    }
    
    public function load_popup_upload_sr()
    {
        $id = $this->input->post('id');
        $data['id'] = $id;
        
        $data['html_list_file_upload_sr'] = $this->list_file_upload_sr(TRUE,$id);

        $data['url'] = $this->url;
        $this->template->display_ajax($this->url.'/v_strategic_result_upload_file', $data);
    }
    
    public function list_file_upload_sr($html=FALSE, $id='')
    {   
        if($html == FALSE){
            $id = $this->input->post('id');
        }
        $where = ['id_from'=>$id, 'type'=>'so', 'is_active'=>'t'];
        $file_sr = @$this->m_global->getDataAll('m_file', null, $where, '*');
        $data['file_sr']  = $file_sr;

        $data['url'] = $this->url;
        $isi = $this->template->display_ajax($this->url.'/v_strategic_result_list_file_upload', $data, NULL, NULL, $html);
        if($html){return $isi;}else{echo $isi;}
    }

    public function upload_file_sr(){
        $date_now                   = date('Y-m-d H:i:s');
        $file_name_origin           = h_replace_file_name($_FILES['userfile']['name']); 
        $type_file                  = h_file_type($_FILES['userfile']['name']);
        $id                         = $this->input->post('id');
        $tgl                        = date('Ymdhis');
        $random                     = rand(1,100);
        //upload
        $folder                     = './public/files/so/';
        $file_name                  = 'so_'.$id.'_'.$tgl.'_'.$random;
        $input_name                 = array_keys($_FILES)[0];
        $file_type                  = '*'; 
        $upload = h_upload($folder,$file_name,$input_name,$file_type);
        //insert data
        if($upload == TRUE){
            $data = array(
                'id_from'            => $id,
                'type'             => 'so',
                'file_name'        => $file_name.'.'.$type_file,
                'created_by'       => h_session('USERNAME'),
                'created_date'     => date("Y-m-d H:i:s"),
            );
            $result = $this->db->insert('m_file', $data);
        }
    }

    public function delete_file_sr()
    {
        //param
        $param = $this->input->post();
        $file_name = $param['file_name'];
        $id = $param['id'];
        //delete data
        $result = $this->m_global->update('m_file', ['is_active'=>'f'], ['id' => $id]);
        //message
        $res['status']  = ($result['status'] ? '1':'0');
        $res['message'] = 'Successfully Save Data!';
        echo json_encode($res);
    }
    // =========================== END upload file ============================

    public function load_table_target_sr() {
        csrf_init();
        $data['url'] = $this->url;

        $id = @$this->input->post('id');
        $type = @$this->input->post('type');
        $polarisasi = @$this->input->post('polarisasi');
        $start_year = @$this->input->post('start_year');
        $end_year = @$this->input->post('end_year');
        $data['start_year'] = $start_year;
        $data['end_year'] = $end_year;
        $data['polarisasi'] = $polarisasi;
        $data['readonly'] = ($type == 'view' ? 'readonly="readonly"' : '');
        // echo '<pre>';print_r($id);exit;

        //cek id nya
        if($id != ''){
            //target year
            $select = "year,target,target_from,target_to";
            $where = " id_sr='".$id."' AND  year >= ".$start_year." AND year <= ".$end_year;
            $order = ['year'=>'ASC'];
            $arr_year  = @$this->m_global->getDataAll('m_sr_target_year', null, $where, $select,null,$order);
            $target = $target_from = $target_to = [];
            if($arr_year != ''){
                foreach($arr_year as $row){
                    $target[$row->year] = $row->target;
                    $target_from[$row->year] = $row->target_from;
                    $target_to[$row->year] = $row->target_to;
                }
            }
            $data['target'] = $target;
            $data['target_from'] = $target_from;
            $data['target_to'] = $target_to;
            // echo '<pre>';print_r($data['target']);exit;


            //target month
            $select = "year,month,target,target_from,target_to";
            $where = " id_sr='".$id."' AND  year >= ".$start_year." AND year <= ".$end_year;
            $order = ['year'=>'ASC', 'month'=>'ASC'];
            $arr_month = @$this->m_global->getDataAll('m_sr_target_month', null, $where, $select, null, $order);
            $target_month = $target_month_from = $target_month_to = [];
            if($arr_month != ''){
                foreach($arr_month as $row2){
                    $target_month[$row2->year][$row2->month] = $row2->target;
                    $target_month_from[$row2->year][$row2->month] = $row2->target_from;
                    $target_month_to[$row2->year][$row2->month] = $row2->target_to;
                }
            }
            $data['target_month']       = $target_month;
            $data['target_month_from']  = $target_month_from;
            $data['target_month_to']    = $target_month_to;
            // echo '<pre>';print_r($data['target_month']);exit;
        }

        $this->template->display_ajax($this->url.'/v_sr_table_target_year', $data);
    }

}
