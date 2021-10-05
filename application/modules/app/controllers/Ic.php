<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ic extends MX_Controller {
    
    private $prefix         = 'ic';
    private $table_db       = 'm_action_plan';
    private $title          = 'Initiative Charter (IC)';
    private $url            = 'app/ic';

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
            $arr = $this->m_global->getDataAll('m_si', null,  ['id'=>$id])[0];
            $data['id_bsc']         = $arr->id_bsc;
            $data['id_si']          = $arr->id;
            $data['year']           = substr($arr->start_date,0,4);
        }else{
            $data['id_bsc']         = 1;
            $data['id_si']          = '';
            $data['year']           = '';
        }

        //template_excel_ic
        $data['template_excel_ic'] = $this->m_global->getDataAll('m_template_excel_ic', null,  ['is_active'=>'t'], 'id,name,file_name', null, "id ASC");

        //bsc
        $data['bsc'] = $this->m_global->getDataAll('m_bsc', null,  ['is_active'=>'t'], 'id,name', null, "name ASC");

        //status
        $data['status_action_plan'] = $this->m_global->getDataAll('m_status', null, ['is_active'=>'t', 'type'=>'SO STATUS'], '*', null, '"order" ASC');

        //year
        $data['start_year'] = @$this->m_global->getDataAll('m_periode', null, ['is_active'=>'t'], 'MIN(start_year) AS year')[0]->year;
        $data['end_year'] = @$this->m_global->getDataAll('m_periode', null, ['is_active'=>'t'], 'MAX(end_year) AS year')[0]->year;

        $js['custom']       = ['table_action_plan'];
        $this->template->display($this->url.'/v_'.$this->prefix, $data, $js);
    }

    public function table_action_plan()
    {    
        // load model view 
        $this->load->model('app/m_ic','m_ic');

        //search default
        $where  = [];
        $whereE = " is_active = 't' AND parent= '0' ";

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
        $global_id_si = @$_REQUEST['global_id_si'];
        if($id_bsc != ''){ $where['a.id_bsc'] = $id_bsc; }
        if($id_si != ''){ $where['a.id_si'] = $id_si; }
        
        //cek year dan month
        $year = @$_REQUEST['global_year'];
        if($year != ''){
            $date_start = $year.'-01-01';
            $date_end = $year.'-12-01';
            $whereE .= " AND ((a.\"start_date\" >= '".$date_start."' AND a.\"start_date\" <= '".$date_end."') ";
            $whereE .= " OR (a.\"end_date\" >= '".$date_start."' AND a.\"end_date\" <= '".$date_end."') ";
            $whereE .= " OR (a.\"start_date\" <= '".$date_start."' AND a.\"end_date\" >= '".$date_end."')) ";
        }

        //cek editing role Admin, PIC SI, PIC IC
        $arr_id_si = [];
        $editing = 'no';
        if(in_array(h_session('ROLE_ID'), h_role_admin())){ 
            $editing = 'yes';
        }
        if( h_session('ROLE_ID') == '5'){ 
            $editing = 'yes';
        }
        if( h_session('ROLE_ID') == '9'){
            $editing = 'no';
            if($id_si != ''){
                //cek editing
                $user_id = h_session('USER_ID');
                $where2 = ['a.is_active'=>'t', 'a.id_bsc'=>$id_bsc, 'a.id_si'=>$id_si, 'a.request_by'=>$user_id, 'a.status_request'=>'3', 'a.status_finished'=>'0'];
                $cek_editing = @$this->m_global->getDataAll('m_request_ic a', null,  $where2, "a.id_si", null, "a.id_si ASC", null, null, "a.id_si")[0]->id_si;
                if($cek_editing == ''){
                    $editing = 'no';
                }else{
                    $editing = 'yes';
                }
            }

            //cek pic ic si
            $nip = h_session('NIP');
            $where2 = " nip = '".$nip."'";
            $id_si = @$this->m_global->getDataAll('m_user_ic_si AS a', null, $where2, 'id_si')[0]->id_si;
            if($id_si != ''){
                $whereE .= " AND a.id_si IN(".$id_si.")";
            }else{
                $whereE .= " AND a.id_si IS NULL";
            }
        }

        // ============= cek IC sesuai SI =================
        //cek role PIC SI
        if(h_session('ROLE_ID') == '5'){ 
            //status selain draft
            // $whereE .= " AND a.status_si != '1' " ;
            //ambil SI yang di definisikan 
            //select id si sesuai pic
            $position_id = h_session('POSITION_ID');
            $nip = h_session('NIP');
            $where2 = "a.nip = '".$nip."' AND a.is_active='t'";
            $id_si2 = @$this->m_global->getDataAll('m_user_ic_si AS a', null, $where2, 'a.id_si')[0]->id_si;
            //cek
            $where3 = "a.status_si = '3' AND a.is_active = 't'";
            if($id_si2 != ''){
                $where3 .= " AND (a.id IN(".$id_si2.") OR ".$position_id."::text = ANY (string_to_array(a.pic_si,', ')::text[]))";
            }else{
                $where3 .= " AND ".$position_id."::text = ANY (string_to_array(a.pic_si,', ')::text[]) "; 
            }
            $arr_si = @$this->m_global->getDataAll('m_si a', null, $where3, 'a.id');
            $arr_id_si = [];
            foreach($arr_si as $row){ $arr_id_si[] = $row->id; }
            if(count($arr_id_si) > 0 ){
                $arr_id_si = join(',',$arr_id_si);
                $whereE .= " AND a.id_si IN(".$arr_id_si.")";
            }else{
                $whereE .= " AND a.id_si is NULL ";
            }
        }
        //cek role PIC IC
        if(h_session('ROLE_ID') == '9'){
            //ambil SI yang di definisikan 
            $nip = h_session('NIP');
            $where2 = " a.nip = '".$nip."'";
            $id_si2 = @$this->m_global->getDataAll('m_user_ic_si AS a', null, $where2, 'a.id_si')[0]->id_si;
            if($id_si2 != ''){
                $whereE .= " AND a.id_si IN(".$id_si2.")";
            }else{
                $whereE .= " AND a.id_si is NULL ";
            }
        }
        // ========================================



        //filtering
        $search = [];
        if(isset($_REQUEST['filter'])){
            foreach ($_REQUEST['filter'] as $row) {

                //default parameter
                if(@$row['name'] != ''){

                    $val= $row['value']; $tipe = $row['tipe']; $search[] = $row['name']; 
                    $name = @$row['name'];
                    $name = $this->m_ic->select($row['name']); $name = str_replace(' AS '.$row['name'], ' ', $name );

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
            $order = ['code','ASC'];
        }
        // echo '<pre>';print_r($order);exit;

        //table dan join
        $table = "m_action_plan AS a";
        $join  = NULL;
        $select = [ 'id','name','code','is_active','created_date','created_by','updated_date','updated_by','status_action_plan',
                    'name_pic_action_plan','deliverable','weighting_factor','budget_currency','id_si',
                    'name_si','code_si','name_bsc','name_status_action_plan','name_pic_action_plan','name_pic_action_plan2',
                    'start_date','end_date'
                ];
        $select = array_unique(array_merge($select, $search));
        $select = $this->m_ic->select($select);

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
            $id_si = @$rows->id_si;
            $action = '';

            //button delete
            $btn_delete = '<button title="Delete" id="'.$id.'" val="f" class="btn btn-sm  btn-danger btn_delete"><i class="fa fa-remove"></i></button>';
            if($rows->is_active == 'f'){
                $btn_delete = '<button title="Active" id="'.$id.'" val="t" class="btn btn-sm  btn-danger btn_delete"><i class="fa fa-check"></i></button>';
            }

            //jika role admin
            if(in_array( h_session('ROLE_ID'), h_role_admin())){
                if($rows->status_action_plan == '1'){
                    $btn_sub_action_plan = '<button title="Add Sub Action Plan" id="'.$id.'" class="btn btn-sm btn-primary btn_sub_action_plan"><i class="fa fa-plus"></i> Sub</button>';
                    $btn_edit = '<button title="Edit" id="'.$id.'" class="btn btn-sm btn-primary btn_edit"><i class="fa fa-edit"></i></button>';
                    $btn_approval = '<button title="Approval" id="'.$id.'" val="2" class="btn btn-sm  btn-warning btn_view"><i class="fa fa-envelope-o"></i></button>';
                    $action =  @$btn_sub_action_plan.@$btn_edit.@$btn_copy.@$btn_approval.@$btn_delete;
                }
                if($rows->status_action_plan == '3'){
                    $btn_sub_action_plan = '<button title="Add Sub Action Plan" id="'.$id.'" class="btn btn-sm btn-primary btn_sub_action_plan"><i class="fa fa-plus"></i> Sub</button>';
                    $btn_copy   = '<button title="Copy" id="'.$id.'" class="btn btn-sm btn-info btn_copy"><i class="fa fa-copy"></i></button>';
                    $btn_edit   = '<button title="Edit" id="'.$id.'" class="btn btn-sm btn-primary btn_edit"><i class="fa fa-edit"></i></button>';
                    $btn_monev_si = '<a href="'.base_url('app/monev_si/index/').$id_si.'" title="Monev IC" class="btn btn-sm btn-warning"><i class="fa fa-arrow-right"></i></a>';
                    $action =  @$btn_sub_action_plan.@$btn_edit.@$btn_copy.@$btn_delete.@$btn_monev_si;
                }
                if($rows->status_action_plan == '4'){
                    $btn_edit = '<button title="Edit" id="'.$id.'" class="btn btn-sm btn-primary btn_edit"><i class="fa fa-edit"></i></button>';
                    $action = @$btn_edit.@$btn_delete;
                }
            }

            //jika role PIC SI
            if(h_session('ROLE_ID') == '5'){
                if($rows->status_action_plan == '1'){
                    $btn_sub_action_plan = '<button title="Add Sub Action Plan" id="'.$id.'" class="btn btn-sm btn-primary btn_sub_action_plan"><i class="fa fa-plus"></i> Sub</button>';
                    $btn_edit = '<button title="Edit" id="'.$id.'" class="btn btn-sm btn-primary btn_edit"><i class="fa fa-edit"></i></button>';
                    $btn_approval = '<button title="Approval" id="'.$id.'" val="2" class="btn btn-sm  btn-warning btn_view"><i class="fa fa-envelope-o"></i></button>';
                    $action =  @$btn_sub_action_plan.@$btn_edit.@$btn_copy.@$btn_approval.@$btn_delete;
                }
                if($rows->status_action_plan == '3'){
                    $btn_sub_action_plan = '<button title="Add Sub Action Plan" id="'.$id.'" class="btn btn-sm btn-primary btn_sub_action_plan"><i class="fa fa-plus"></i> Sub</button>';
                    $btn_copy   = '<button title="Copy" id="'.$id.'" class="btn btn-sm btn-info btn_copy"><i class="fa fa-copy"></i></button>';
                    $btn_edit   = '<button title="Edit" id="'.$id.'" class="btn btn-sm btn-primary btn_edit"><i class="fa fa-edit"></i></button>';
                    $btn_monev_si = '<a href="'.base_url('app/monev_si/index/').$id_si.'" title="Monev IC" class="btn btn-sm btn-warning"><i class="fa fa-arrow-right"></i></a>';
                    $action =  @$btn_sub_action_plan.@$btn_edit.@$btn_copy.@$btn_delete.@$btn_monev_si;
                }
                if($rows->status_action_plan == '4'){
                    $btn_approval = '<button title="Approval" id="'.$id.'" val="2" class="btn btn-sm  btn-warning btn_view"><i class="fa fa-envelope-o"></i></button>';
                    $btn_edit = '<button title="Edit" id="'.$id.'" class="btn btn-sm btn-primary btn_edit"><i class="fa fa-edit"></i></button>';
                    $action = @$btn_approval.@$btn_edit.@$btn_delete;
                }
            }


            //jika role PIC IC
            if(h_session('ROLE_ID') == '9'){
                if($rows->status_action_plan == '1'){
                    $btn_sub_action_plan = '<button title="Add Sub Action Plan" id="'.$id.'" class="btn btn-sm btn-primary btn_sub_action_plan"><i class="fa fa-plus"></i> Sub</button>';
                    $btn_edit = '<button title="Edit" id="'.$id.'" class="btn btn-sm btn-primary btn_edit"><i class="fa fa-edit"></i></button>';
                    $btn_approval = '<button title="Approval" id="'.$id.'" val="2" class="btn btn-sm  btn-warning btn_view"><i class="fa fa-envelope-o"></i></button>';
                    $action =  @$btn_sub_action_plan.@$btn_edit.@$btn_copy.@$btn_approval.@$btn_delete;
                }
                if($rows->status_action_plan == '3'){
                    $btn_sub_action_plan = '<button title="Add Sub Action Plan" id="'.$id.'" class="btn btn-sm btn-primary btn_sub_action_plan"><i class="fa fa-plus"></i> Sub</button>';
                    $btn_copy   = '<button title="Copy" id="'.$id.'" class="btn btn-sm btn-info btn_copy"><i class="fa fa-copy"></i></button>';
                    $btn_edit   = '<button title="Edit" id="'.$id.'" class="btn btn-sm btn-primary btn_edit"><i class="fa fa-edit"></i></button>';
                    $btn_monev_si = '<a href="'.base_url('app/monev_si/index/').$id.'" title="Monev IC" class="btn btn-sm btn-warning"><i class="fa fa-arrow-right"></i></a>';
                    $action =  @$btn_sub_action_plan.@$btn_edit.@$btn_copy.@$btn_delete.@$btn_monev_si;
                }
                if($rows->status_action_plan == '4'){
                    $btn_approval = '<button title="Approval" id="'.$id.'" val="2" class="btn btn-sm  btn-warning btn_view"><i class="fa fa-envelope-o"></i></button>';
                    $btn_edit = '<button title="Edit" id="'.$id.'" class="btn btn-sm btn-primary btn_edit"><i class="fa fa-edit"></i></button>';
                    $action = @$btn_approval.@$btn_edit.@$btn_delete;
                }
            }


            //cek editing
            if($editing == 'yes'){
                if(h_session('ROLE_ID') == '9'){
                    if($rows->id_si == $global_id_si){
                        $action = $action;
                    }else{
                        $action = "";
                    }
                }else{
                    $action = $action;
                }
            }else{
                $action = "";
            }


            //isi table disini
            $isi['no']                  = $i;
            $isi['id']                  = $rows->id;
            
            $isi['id_si']               = $rows->name_si;
            $isi['id_bsc']              = $rows->name_bsc;
            $isi['code']                = $rows->code;
            $isi['name']                = h_read_more($rows->name,40);
            $isi['pic_action_plan']     = h_read_more($rows->name_pic_action_plan,10).''.h_read_more($rows->name_pic_action_plan2,10);
            $isi['deliverable']         = h_read_more($rows->deliverable,20);
            $isi['weighting_factor']    = round($rows->weighting_factor, 2, PHP_ROUND_HALF_DOWN);
            $isi['budget_currency']     = $rows->budget_currency;
            $isi['status_action_plan']  = $rows->name_status_action_plan;
            $isi['start_date']          = $rows->start_date;
            $isi['end_date']            = $rows->end_date;

            $isi['is_active']           = ($rows->is_active == 't' ? 'Active' : 'Non Active');
            $isi['created_date']        = h_format_date($rows->created_date,'d F Y');
            $isi['created_by']          = $rows->created_by;
            $isi['updated_date']        = h_format_date($rows->updated_date,'d F Y');
            $isi['updated_by']          = $rows->updated_by;

            $isi['action']              = '<div style="width:220px;'.($iTotalRecords=='1'?'height:50px;':'').'">'.$action.'</div>';

            $param[] = $isi;
            $i++;
        }
        $records["data"]            = $param;
        $records["draw"]            = $sEcho;
        $records["recordsTotal"]    = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        $records["total_data"]      = $iTotalRecords;
        $records["editing"]         = $editing;

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

        //bsc
        $data['bsc'] = $this->m_global->getDataAll('m_bsc', null,  ['is_active'=>'t'], '*', null, "name ASC");
        //pic
        $where = " '4' = ANY (string_to_array(role_id,',')) ";
        $data['pic'] = $this->m_global->getDataAll('sys_user', null, ['is_active'=>'t'], '*', $where, "fullname ASC");
        
        //get data
        $data['data'] = $this->m_global->getDataAll('m_si', null, ['id'=>$id], '*')[0];

        //get data so direct 
        $where = ['a.is_active'=>'t', 'a.id_si'=>$id, 'a.direct'=>'1'];
        $select = 'b.code as code_kpi_so, b.name as name_kpi_so, c.name as name_so,  c.code as code_so';
        $join  = [  ['table' => 'm_kpi_so b', 'on' => 'a.id_kpi_so = b.id', 'join' => 'LEFT'],
                    ['table' => 'm_so c', 'on' => 'a.id_so = c.id', 'join' => 'LEFT'] ];
        $arr = $this->m_global->getDataAll('m_si_so a', $join,  $where, $select, null, "a.id_so ASC");
        $data['direct'] = $arr;

        //get data so indirect 
        $where = ['a.is_active'=>'t', 'a.id_si'=>$id, 'a.direct'=>'0'];
        $arr = $this->m_global->getDataAll('m_si_so a', $join,  $where, $select, null, "a.id_so ASC");
        $data['indirect'] = $arr;
        // echo '<pre>';print_r($data['direct']);exit;

        $this->template->display_ajax($this->url.'/v_ic_detail_si', $data);
    }


    public function load_add() {
        csrf_init();
        $data['url'] = $this->url;

        //bsc
        $data['bsc'] = $this->m_global->getDataAll('m_bsc', null,  ['is_active'=>'t'], '*', null, "name ASC");

        $this->template->display_ajax($this->url.'/v_ic_add', $data);
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
        $data['data'] = @$this->m_global->getDataAll('m_action_plan', null, ['id'=>$id], '*')[0];

        //total weighting 
        $id_si = @$data['data']->id_si;
        $data['total_weighting_factor_now'] = @$this->get_total_weighting_factor($id_si,'html');

        //file si
        $data['html_list_file_ic'] = $this->list_file_ic(TRUE,$id);

        $this->template->display_ajax($this->url.'/v_ic_edit', $data);
    }


    public function load_sub_action_plan() {
        csrf_init();
        $data['url'] = $this->url;
        $id = @$this->input->post('id');
        $view = @$this->input->post('view');
        $data['id'] = $id;
        $data['view'] = $view;

        //get data action plan
        $where = ['a.id'=>$id];
        $select = " a.*, (SELECT DISTINCT STRING_AGG ( b.\"SINGKATAN_POSISI\" :: CHARACTER VARYING, ',' ) FROM \"ERP_STO_REAL\" b  
                            WHERE b.\"POSITION_ID\" ::text = ANY (string_to_array(a.pic_action_plan,', ')::text[])
                        ) AS name_pic_action_plan,
                        (SELECT DISTINCT STRING_AGG ( b.\"singkatan_posisi\" :: CHARACTER VARYING, ',' ) FROM \"m_pic\" b  
                            WHERE b.\"position_id_new\" ::text = ANY (string_to_array(a.pic_action_plan:: CHARACTER VARYING,', ')::text[])
                        ) AS name_pic_action_plan2";
        $action_plan = @$this->m_global->getDataAll('m_action_plan a', null, $where, $select)[0];
        $data['action_plan'] = $action_plan;

        //get data tahun
        $data['start_year'] = substr($action_plan->start_date,0,4);
        $data['end_year']   = substr($action_plan->end_date,0,4);

        //get data sub action plan
        $where = ['a.parent'=>$id, 'is_active'=>'t'];
        $select = " a.*, (SELECT DISTINCT STRING_AGG ( b.\"SINGKATAN_POSISI\" :: CHARACTER VARYING, ',' ) FROM \"ERP_STO_REAL\" b  
                            WHERE b.\"POSITION_ID\" ::text = ANY (string_to_array(a.pic_action_plan,', ')::text[])
                        ) AS name_pic_action_plan,
                        (SELECT DISTINCT STRING_AGG ( b.\"singkatan_posisi\" :: CHARACTER VARYING, ',' ) FROM \"m_pic\" b  
                            WHERE b.\"position_id_new\" ::text = ANY (string_to_array(a.pic_action_plan:: CHARACTER VARYING,', ')::text[])
                        ) AS name_pic_action_plan2";
        $sub_action_plan = @$this->m_global->getDataAll('m_action_plan a', null, $where, $select,null,"a.code ASC");
        $data['sub_action_plan'] = $sub_action_plan;
        
        //get data budget
        $id_action_plan = $id.",";
        foreach($sub_action_plan as $row){
            $id_action_plan .= $row->id.",";
        }
        $id_action_plan = substr($id_action_plan,0,-1);
        $where = " id_action_plan IN ($id_action_plan)";
        $arr = @$this->m_global->getDataAll('m_action_plan_year', null, $where, '*');
        $budget = [];
        foreach($arr as $row){
            $budget[$row->id_action_plan][$row->year] = $row->budget; 
        }
        $data['budget'] = $budget;
        
        $this->template->display_ajax($this->url.'/v_ic_sub_action_plan', $data);
    }

    public function load_add_sub() {
        csrf_init();
        $data['url'] = $this->url;
        $id = @$this->input->post('id');
        $data['id'] = $id;

        //get data action plan
        $where = ['id'=>$id];
        $action_plan = @$this->m_global->getDataAll('m_action_plan', null, $where, '*')[0];
        $data['action_plan'] = $action_plan;

        //get weighting_factor
        $select2 = 'SUM("weighting_factor"::numeric) as total';
        $where2['is_active'] = 't';
        $where2['parent'] = $id;
        $total = @$this->m_global->getDataAll('m_action_plan', null, $where2, $select2, null, null, null, null, "weighting_factor")[0]->total;
        if($total == ''){ $total = 0; }
        $data['total_weighting_factor_now'] = $total;

        //get data tahun
        $data['start_year'] = substr($action_plan->start_date,0,4);
        $data['end_year']   = substr($action_plan->end_date,0,4);

        //get data budget
        $id_action_plan = $id;
        $where = " id_action_plan = '$id'";
        $arr = @$this->m_global->getDataAll('m_action_plan_year', null, $where, '*');
        $budget = [];
        foreach($arr as $row){
            $budget[$row->id_action_plan][$row->year] = $row->budget; 
        }
        $data['budget'] = $budget;

        //get nomor terakhir sub
        $where = ['parent'=>$id, 'is_active'=>'t'];
        $no_baru = @$this->m_global->getDataAll('m_action_plan', null, $where, 'code', null,'code DESC')[0]->code + 0.01;
        $data['no_baru'] = str_replace('.0','.',$no_baru);

        $this->template->display_ajax($this->url.'/v_ic_add_sub', $data);
    }


    public function load_edit_sub() {
        csrf_init();
        $data['url'] = $this->url;
        $id = @$this->input->post('id');
        $data['id'] = $id;

        //get data action plan
        $where = ['id'=>$id];
        $action_plan = @$this->m_global->getDataAll('m_action_plan', null, $where, '*')[0];
        $data['action_plan'] = $action_plan;

        //get weighting_factor
        $select2 = 'SUM("weighting_factor"::numeric) as total';
        $where2['is_active'] = 't';
        $where2['parent'] = $id;
        $total = @$this->m_global->getDataAll('m_action_plan', null, $where2, $select2, null, null, null, null, "weighting_factor")[0]->total;
        if($total == ''){ $total = 0; }
        $data['total_weighting_factor_now'] = $total;

        //get data tahun
        $data['start_year'] = substr($action_plan->start_date,0,4);
        $data['end_year']   = substr($action_plan->end_date,0,4);

        //get data budget
        $id_action_plan = $id;
        $where = " id_action_plan = '$id'";
        $arr = @$this->m_global->getDataAll('m_action_plan_year', null, $where, '*');
        $budget = [];
        foreach($arr as $row){
            $budget[$row->id_action_plan][$row->year] = $row->budget; 
        }
        $data['budget'] = $budget;

        $this->template->display_ajax($this->url.'/v_ic_edit_sub', $data);
    }

    public function load_copy() {
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
        $data['data'] = @$this->m_global->getDataAll('m_action_plan', null, ['id'=>$id], '*')[0];

        $this->template->display_ajax($this->url.'/v_ic_copy', $data);
    }

    public function load_table_budget_ic() {
        csrf_init();
        $data['url'] = $this->url;

        //param
        $id = @$this->input->post('id');
        $start_year = substr(@$this->input->post('start_date'),0,4) ;
        $end_year = substr(@$this->input->post('end_date'),0,4) ;
        $data['start_year'] = $start_year;
        $data['end_year'] = $end_year;

        //tipe view
        $type = @$this->input->post('type');
        $data['readonly'] = ($type == 'view' ? 'readonly="readonly"' : '');

        //cek id nya
        if($id != ''){
            //budget year
            $select = "year,budget";
            $where = " id_action_plan='".$id."' AND  year >= ".$start_year." AND year <= ".$end_year;
            $order = ['year'=>'ASC'];
            $arr_year  = @$this->m_global->getDataAll('m_action_plan_year', null, $where, $select,null,$order);
            $budget = [];
            if($arr_year != ''){
                foreach($arr_year as $row){
                    $budget[$row->year] = $row->budget;
                }
            }
            $data['budget'] = $budget;
            // echo '<pre>';print_r($data['budget']);exit;
        }

        $this->template->display_ajax($this->url.'/v_ic_table_budget', $data);
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

            $this->form_validation->set_rules('name', 'KPI-SO Title', 'trim|xss_clean|required');
            $this->form_validation->set_rules('code', 'Number/Prefix', 'trim|xss_clean|required');
            $this->form_validation->set_rules('pic_action_plan', 'PIC KPI-SO', 'trim|xss_clean|required');
            $this->form_validation->set_rules('deliverable', 'Deliveriable', 'required');
            $this->form_validation->set_rules('weighting_factor', 'Weighting Factor', 'required');
            $this->form_validation->set_rules('budget_currency', 'Budget Currency', 'trim|xss_clean|required');
            $this->form_validation->set_rules('start_date', 'Start Date', 'trim|xss_clean|required');
            $this->form_validation->set_rules('end_date', 'End Date', 'trim|xss_clean|required');
            
            if ($this->form_validation->run($this)) {

                //insert data
                $start_date = @$this->input->post('start_date');
                $end_date   = @$this->input->post('end_date');
                
                $data['name']           = @$this->input->post('name');
                $data['code']           = @$this->input->post('code');
                $data['pic_action_plan']= str_replace(',',', ',@$this->input->post('pic_action_plan'));
                $data['deliverable']    = @$this->input->post('deliverable');
                $data['weighting_factor']= str_replace(',','.',@$this->input->post('weighting_factor'));
                $data['budget_currency']= @$this->input->post('budget_currency');
                $data['start_date']     = $start_date.'-01';
                $data['end_date']       = $end_date.'-01';

                $data['id_bsc']         = @$this->input->post('id_bsc');
                $data['id_si']          = @$this->input->post('id_si');
                $data['status_action_plan'] = 1;
                
                $data['created_date']   = date("Y-m-d H:i:s");
                $data['created_by']     = h_session('USERNAME');

                //insert action plan
                $data['parent'] = 0;
                $result = $this->m_global->insert('m_action_plan', $data);
                $id_action_plan = $this->db->insert_id();
                
                //insert sub action plan
                $data['parent'] = $id_action_plan;
                $data['code']   = @$this->input->post('code').'.01';
                $result = $this->m_global->insert('m_action_plan', $data);
                $id_sub_action_plan = $this->db->insert_id();
                
                //insert budget year
                $start_year  = substr($start_date,0,4);
                $end_year    = substr($end_date,0,4);
                if($id_action_plan != ''){
                    for($y=$start_year;$y<=$end_year;$y++){
                        $data2 = [];
                        $data2['id_action_plan'] = $id_action_plan;
                        $data2['year']      = $y;
                        $data2['status']    = 3;
                        $data2['status_complete'] = 13;
                        $budget = str_replace(',','',@$this->input->post('budget_'.$y));
                        $data2['budget']    = ($budget == '' ? NULL : $budget);
                        $result = $this->m_global->insert('m_action_plan_year', $data2);
                        $data2['id_action_plan'] = $id_sub_action_plan;
                        $result = $this->m_global->insert('m_action_plan_year', $data2);
                    }
                }

                //insert action plan month
                if($id_action_plan != ''){
                    for($y=$start_year;$y<=$end_year;$y++){
                        for($m=1;$m<=12;$m++){
                            $data3 = [];
                            $data3['id_action_plan'] = $id_action_plan;
                            $data3['year']      = $y;
                            $data3['month']     = $m;
                            $data3['status']    = 3;
                            $data3['status_complete'] = 13;
                            $result = $this->m_global->insert('m_action_plan_month', $data3);
                            $data3['id_action_plan'] = $id_sub_action_plan;
                            $result = $this->m_global->insert('m_action_plan_month', $data3);
                        }
                    }
                }

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


    public function save_add_sub() {

        //cek csrf token
        // $ex_csrf_token = @$this->input->post('ex_csrf_token');
        // $res = [];
        // if (csrf_get_token() != $ex_csrf_token){
        //     $res['status']  = 2;
        //     $res['message'] = $this->csrf_message;
        //     echo json_encode($res);
        // }else{

            // Set Rule Login Form
            $this->form_validation->set_rules('start_date', 'Start Date', 'trim|xss_clean|required');
            $this->form_validation->set_rules('end_date', 'End Date', 'trim|xss_clean|required');
            $this->form_validation->set_rules('name', 'KPI-SO Title', 'trim|xss_clean|required');
            $this->form_validation->set_rules('code', 'Number/Prefix', 'trim|xss_clean|required');
            $this->form_validation->set_rules('pic_action_plan', 'PIC KPI-SO', 'trim|xss_clean|required');
            $this->form_validation->set_rules('deliverable', 'Deliveriable', 'required');
            $this->form_validation->set_rules('weighting_factor', 'Weighting Factor', 'required');
            
            if ($this->form_validation->run($this)) {

                // echo '<pre>';print_r(@$this->input->post());exit;

                //get data action plan
                $id = @$this->input->post('id');
                $id_action_plan = @$this->input->post('id');

                //get data
                $action_plan = @$this->m_global->getDataAll('m_action_plan', null, ['id'=>$id], '*')[0];

                //insert data
                // $start_date = $action_plan->start_date;
                // $end_date   = $action_plan->end_date;
                $start_date = @$this->input->post('start_date');
                $end_date   = @$this->input->post('end_date');
                $id_bsc     = $action_plan->id_bsc;
                $id_si      = $action_plan->id_si;

                //ubah code menjadi 1.01
                $code_sub =  @$this->input->post('code');
                if(strpos($code_sub,'.') !== false ) { 
                    $pecah = explode('.',$code_sub);
                    $code_new = $pecah[0].'.'.str_pad( $pecah[1],2,'0',STR_PAD_LEFT);
                }else{
                    $code_new = '0.'.$code_sub;
                }
                
                $data['name']           = @$this->input->post('name');
                $data['code']           = @$code_new;
                $data['pic_action_plan']= str_replace(',',', ',@$this->input->post('pic_action_plan'));
                $data['deliverable']    = @$this->input->post('deliverable');
                $data['weighting_factor']= str_replace(',','.',@$this->input->post('weighting_factor'));
                $data['budget_currency']= @$this->input->post('budget_currency');
                $data['start_date']     = $start_date;
                $data['end_date']       = $end_date;

                $data['id_bsc']         = $id_bsc;
                $data['id_si']          = $id_si;
                $data['status_action_plan'] = 3;
                
                $data['created_date']   = date("Y-m-d H:i:s");
                $data['created_by']     = h_session('USERNAME');

                //insert sub action plan
                $data['parent'] = $id_action_plan;
                $result = $this->m_global->insert('m_action_plan', $data);
                $id_sub_action_plan = $this->db->insert_id();
                
                //insert budget year
                $start_year  = substr($start_date,0,4);
                $end_year    = substr($end_date,0,4);
                if($id_sub_action_plan != ''){
                    for($y=$start_year;$y<=$end_year;$y++){
                        $data2 = [];
                        $data2['id_action_plan'] = $id_sub_action_plan;
                        $data2['year']      = $y;
                        $data2['status']    = 3;
                        $data2['status_complete'] = 13;
                        $budget = str_replace(',','',@$this->input->post('budget_'.$y));
                        $data2['budget']    = ($budget == '' ? NULL : $budget);
                        $result = $this->m_global->insert('m_action_plan_year', $data2);
                    }
                }

                //insert action plan month
                if($id_sub_action_plan != ''){
                    for($y=$start_year;$y<=$end_year;$y++){
                        for($m=1;$m<=12;$m++){
                            $data3 = [];
                            $data3['id_action_plan'] = $id_sub_action_plan;
                            $data3['year']      = $y;
                            $data3['month']     = $m;
                            $data3['status']    = 3;
                            $data3['status_complete'] = 13;
                            $result = $this->m_global->insert('m_action_plan_month', $data3);
                        }
                    }
                }

                // echo '<pre>';print_r($data);exit;
                // echo $this->db->last_query();exit;

                $res['id']  = $id_action_plan;
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

            $this->form_validation->set_rules('name', 'KPI-SO Title', 'trim|xss_clean|required');
            $this->form_validation->set_rules('code', 'Number/Prefix', 'trim|xss_clean|required');
            $this->form_validation->set_rules('pic_action_plan', 'PIC KPI-SO', 'trim|xss_clean|required');
            $this->form_validation->set_rules('deliverable', 'Deliveriable', 'required');
            $this->form_validation->set_rules('weighting_factor', 'Weighting Factor', 'required');
            $this->form_validation->set_rules('budget_currency', 'Budget Currency', 'trim|xss_clean|required');
            $this->form_validation->set_rules('start_date', 'Start Date', 'trim|xss_clean|required');
            $this->form_validation->set_rules('end_date', 'End Date', 'trim|xss_clean|required');

            if ($this->form_validation->run($this)) {

                // echo '<pre>';print_r(@$this->input->post());exit;

                //update data
                $id = $this->input->post('id');
                $id_action_plan = $id;

                $start_date = @$this->input->post('start_date');
                $end_date   = @$this->input->post('end_date');
                $start_date_old = @$this->input->post('start_date_old');
                $end_date_old   = @$this->input->post('end_date_old');

                $data['name']           = @$this->input->post('name');
                $data['code']           = @$this->input->post('code');
                $data['pic_action_plan']= str_replace(',',', ',@$this->input->post('pic_action_plan'));
                $data['deliverable']   = @$this->input->post('deliverable');
                $data['weighting_factor']= str_replace(',','.',@$this->input->post('weighting_factor'));
                $data['budget_currency']= @$this->input->post('budget_currency');
                $data['start_date']     = $start_date.'-01';
                $data['end_date']       = $end_date.'-01';

                $data['id_bsc']         = @$this->input->post('id_bsc');
                $data['id_si']          = @$this->input->post('id_si');
                $data['status_action_plan'] = @$this->input->post('status_action_plan');
                
                $data['updated_date']   = date("Y-m-d H:i:s");
                $data['updated_by']     = h_session('USERNAME');

                $result = $this->m_global->update('m_action_plan', $data, ['id' => $id]);


                //============================================================================================

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

                //delete budget year dan month
                if(count($year_delete) > 0){
                    foreach($year_delete as $y){
                        $where = $data = [];
                        $where['id_action_plan'] = $id;
                        $where['year'] = $y;
                        $data['is_active'] = 'f';
                        $result = $this->m_global->update('m_action_plan_year', $data, $where);
                        $result = $this->m_global->update('m_action_plan_month', $data, $where);

                    }
                }
                
                //update budget year dan month
                if(count($year_update) > 0){
                    foreach($year_update as $y){
                        $data2 = $data_year = [];
                        $data2['id_action_plan'] = $id_action_plan;
                        $data2['year']      = $y;
                        $budget = str_replace(',','',@$this->input->post('budget_'.$y));
                        $data_year['budget']      = ($budget == '' ? NULL : $budget);
                        $result = $this->m_global->update('m_action_plan_year', $data_year, $data2);
                        // echo $this->db->last_query();'<br>';

                        for($m=1;$m<=12;$m++){
                            $data3 = $data_month = [];
                            $data3['id_action_plan'] = $id_action_plan;
                            $data3['year']      = $y;
                            $data3['month']     = $m;
                            $data_month['updated_date']   = date("Y-m-d H:i:s");
                            $data_month['updated_by']     = h_session('USERNAME');
                            $result = $this->m_global->update('m_action_plan_month', $data_month, $data3);
                            // echo $this->db->last_query();'<br>';
                        }
                    }
                }

                //insert budget year dan month
                if(count($year_insert) > 0){
                    foreach($year_insert as $y){
                        $data2 = [];
                        $data2['id_action_plan'] = $id;
                        $data2['year']      = $y;
                        $data2['status']    = 3;
                        $budget = str_replace(',','',@$this->input->post('budget_'.$y));
                        $data2['budget']      = ($budget == '' ? NULL : $budget);
                        //cek insert / update
                        $where = [];
                        $where['id_action_plan'] = $id;
                        $where['year'] = $y;
                        $cek = @$this->m_global->getDataAll('m_action_plan_year', NULL,$where,"id")[0]->id;
                        if($cek == ''){
                            $result = $this->m_global->insert('m_action_plan_year', $data2);
                        }else{
                            $data2['is_active']   = 't';
                            $result = $this->m_global->update('m_action_plan_year', $data2, $where);
                        }

                        for($m=1;$m<=12;$m++){
                            $data3 = [];
                            $data3['id_action_plan'] = $id;
                            $data3['year']      = $y;
                            $data3['month']     = $m;
                            $data3['status']     = 3;
                            //cek insert / update
                            $where = [];
                            $where['id_action_plan'] = $id;
                            $where['year']      = $y;
                            $where['month']     = $m;
                            $cek = @$this->m_global->getDataAll('m_action_plan_month', NULL,$where,"id")[0]->id;
                            if($cek == ''){
                                $result = $this->m_global->insert('m_action_plan_month', $data3);
                            }else{
                                $data3['is_active'] = 't';
                                $result = $this->m_global->update('m_action_plan_month', $data3, $where);
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


    public function save_edit_sub() {

        //cek csrf token
        // $ex_csrf_token = @$this->input->post('ex_csrf_token');
        // $res = [];
        // if (csrf_get_token() != $ex_csrf_token){
        //     $res['status']  = 2;
        //     $res['message'] = $this->csrf_message;
        //     echo json_encode($res);
        // }else{

            // Set Rule Login Form
            $this->form_validation->set_rules('start_date', 'Start Date', 'trim|xss_clean|required');
            $this->form_validation->set_rules('end_date', 'End Date', 'trim|xss_clean|required');
            $this->form_validation->set_rules('name', 'KPI-SO Title', 'trim|xss_clean|required');
            $this->form_validation->set_rules('code', 'Number/Prefix', 'trim|xss_clean|required');
            $this->form_validation->set_rules('pic_action_plan', 'PIC KPI-SO', 'trim|xss_clean|required');
            $this->form_validation->set_rules('deliverable', 'Deliveriable', 'required');
            $this->form_validation->set_rules('weighting_factor', 'Weighting Factor', 'required');

            if ($this->form_validation->run($this)) {

                // echo '<pre>';print_r(@$this->input->post());exit;

                //get data action plan
                $id = @$this->input->post('id');
                $id_sub_action_plan = @$this->input->post('id');

                //get data
                $action_plan = @$this->m_global->getDataAll('m_action_plan', null, ['id'=>$id], '*')[0];

                //update data
                $id_action_plan = $action_plan->parent;
                // $start_date = $action_plan->start_date;
                // $end_date   = $action_plan->end_date;
                $start_date = @$this->input->post('start_date');
                $end_date   = @$this->input->post('end_date');
                $id_bsc     = $action_plan->id_bsc;
                $id_si      = $action_plan->id_si;

                //ubah code menjadi 1.01
                $code_sub =  @$this->input->post('code');
                if(strpos($code_sub,'.') !== false ) { 
                    $pecah = explode('.',$code_sub);
                    $code_new = $pecah[0].'.'.str_pad( $pecah[1],2,'0',STR_PAD_LEFT);
                }else{
                    $code_new = '0.'.$code_sub;
                }
                
                $data['name']           = @$this->input->post('name');
                $data['code']           = $code_new;
                $data['pic_action_plan']= str_replace(',',', ',@$this->input->post('pic_action_plan'));
                $data['deliverable']    = @$this->input->post('deliverable');
                $data['weighting_factor']= str_replace(',','.',@$this->input->post('weighting_factor'));
                $data['budget_currency']= @$this->input->post('budget_currency');
                $data['start_date']     = $start_date;
                $data['end_date']       = $end_date;

                $data['id_bsc']         = $id_bsc;
                $data['id_si']          = $id_si;
                $data['status_action_plan'] = 3;
                
                $data['updated_date']   = date("Y-m-d H:i:s");
                $data['updated_by']     = h_session('USERNAME');

                $result = $this->m_global->update('m_action_plan', $data, ['id' => $id]);


                //============================================================================================

                //cek tahun yang dirubah
                $start_year         = substr($start_date,0,4);
                $end_year           = substr($end_date,0,4);

                //update budget year
                $start_year  = substr($start_date,0,4);
                $end_year    = substr($end_date,0,4);
                if($id_sub_action_plan != ''){
                    for($y=$start_year;$y<=$end_year;$y++){
                        $data2 = $data2 = [];
                        $data2['id_action_plan'] = $id_sub_action_plan;
                        $data2['year']      = $y;
                        $data2['status']    = 3;
                        $budget = str_replace(',','',@$this->input->post('budget_'.$y));
                        $data3['budget']    = ($budget == '' ? NULL : $budget);
                        $result = $this->m_global->update('m_action_plan_year', $data3, $data2);
                    }
                }

                $res['id']      = $id_action_plan;
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
            $this->form_validation->set_rules('id_bsc', 'BSC', 'trim|xss_clean|required');
            $this->form_validation->set_rules('id_si', 'SI', 'trim|xss_clean|required');

            $this->form_validation->set_rules('name', 'KPI-SO Title', 'trim|xss_clean|required');
            $this->form_validation->set_rules('code', 'Number/Prefix', 'trim|xss_clean|required');
            $this->form_validation->set_rules('pic_action_plan', 'PIC KPI-SO', 'trim|xss_clean|required');
            $this->form_validation->set_rules('deliverable', 'Deliveriable', 'required');
            $this->form_validation->set_rules('weighting_factor', 'Weighting Factor', 'required');
            $this->form_validation->set_rules('budget_currency', 'Budget Currency', 'trim|xss_clean|required');
            $this->form_validation->set_rules('start_date', 'Start Date', 'trim|xss_clean|required');
            $this->form_validation->set_rules('end_date', 'End Date', 'trim|xss_clean|required');
            
            if ($this->form_validation->run($this)) {

                //insert data
                $id = @$this->input->post('id');
                $start_date = @$this->input->post('start_date');
                $end_date   = @$this->input->post('end_date');
                
                $data['name']           = @$this->input->post('name');
                $data['code']           = @$this->input->post('code');
                $data['pic_action_plan']= str_replace(',',', ',@$this->input->post('pic_action_plan'));
                $data['deliverable']   = @$this->input->post('deliverable');
                $data['weighting_factor']= str_replace(',','.',@$this->input->post('weighting_factor'));
                $data['budget_currency']= @$this->input->post('budget_currency');
                $data['start_date']     = $start_date.'-01';
                $data['end_date']       = $end_date.'-01';

                $data['id_bsc']         = @$this->input->post('id_bsc');
                $data['id_si']          = @$this->input->post('id_si');
                $data['status_action_plan'] = 1;
                
                $data['created_date']   = date("Y-m-d H:i:s");
                $data['created_by']     = h_session('USERNAME');

                //insert action plan
                $data['parent'] = 0;
                $result = $this->m_global->insert('m_action_plan', $data);
                $id_action_plan = $this->db->insert_id();
                
                //insert sub action plan
                $data['parent'] = $id_action_plan;
                $data['code']   = @$this->input->post('code').'.1';
                $result = $this->m_global->insert('m_action_plan', $data);
                $id_sub_action_plan = $this->db->insert_id();
                
                //insert budget year
                $start_year  = substr($start_date,0,4);
                $end_year    = substr($end_date,0,4);
                if($id_action_plan != ''){
                    for($y=$start_year;$y<=$end_year;$y++){
                        $data2 = [];
                        $data2['id_action_plan'] = $id_action_plan;
                        $data2['year']      = $y;
                        $data2['status']    = 3;
                        $data2['status_complete'] = 13;
                        $budget = str_replace(',','',@$this->input->post('budget_'.$y));
                        $data2['budget']    = ($budget == '' ? NULL : $budget);
                        $result = $this->m_global->insert('m_action_plan_year', $data2);
                        $data2['id_action_plan'] = $id_sub_action_plan;
                        $result = $this->m_global->insert('m_action_plan_year', $data2);
                    }
                }

                //insert action plan month
                if($id_action_plan != ''){
                    for($y=$start_year;$y<=$end_year;$y++){
                        for($m=1;$m<=12;$m++){
                            $data3 = [];
                            $data3['id_action_plan'] = $id_action_plan;
                            $data3['year']      = $y;
                            $data3['month']     = $m;
                            $data3['status']    = 3;
                            $data3['status_complete'] = 13;
                            $result = $this->m_global->insert('m_action_plan_month', $data3);
                            $data3['id_action_plan'] = $id_sub_action_plan;
                            $result = $this->m_global->insert('m_action_plan_month', $data3);
                        }
                    }
                }

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
            
            //delete parent action plan
            $res = $this->m_global->update('m_action_plan', $data, ['id' => $id]);
            $res1 = @$this->m_global->update('m_action_plan_month', $data, ['id_action_plan' => $id]);
            $res2 = @$this->m_global->update('m_action_plan_year', $data, ['id_action_plan' => $id]);

            //delete sub action plan
            $arr_sub_action_plan = @$this->m_global->getDataAll('m_action_plan', null, ['parent'=>$id], 'id');
            foreach($arr_sub_action_plan as $row){
                $id_sub = $row->id;
                $res = $this->m_global->update('m_action_plan', $data, ['id' => $id_sub]);
                $res1 = @$this->m_global->update('m_action_plan_month', $data, ['id_action_plan' => $id_sub]);
                $res2 = @$this->m_global->update('m_action_plan_year', $data, ['id_action_plan' => $id_sub]);
            }

            if($val == 't'){
                $res['message'] = 'Active Success!';
            }else{
                $res['message'] = 'Delete Success!';
            }
            
            echo json_encode($res);
        // }
    }


    public function delete_sub_action_plan() {
        //cek csrf token
        // $ex_csrf_token = @$this->input->post('token');
        // $res = [];
        // if (csrf_get_token() != $ex_csrf_token){
        //     $res['status']  = 0;
        //     $res['message'] = $this->csrf_message;
        //     echo json_encode($res);
        // }else{
            
            //get data
            $id = $this->input->post('id');
            $val = $this->input->post('val');
            $data['is_active'] = $val;
            $res = $this->m_global->update('m_action_plan', $data, ['id' => $id]);
            $res1 = @$this->m_global->update('m_action_plan_month', $data, ['id_action_plan' => $id]);
            $res2 = @$this->m_global->update('m_action_plan_year', $data, ['id_action_plan' => $id]);
            if($val == 't'){
                $res['message'] = 'Active Success!';
            }else{
                $res['message'] = 'Delete Success!';
            }

            //id action plan
            $id_action_plan = @$this->m_global->getDataAll('m_action_plan', null, ['id'=>$id], 'parent')[0]->parent;
            $res['id'] = $id_action_plan;
            
            echo json_encode($res);
        // }
    }

    public function backup_data() {
        //cek csrf token
        // $ex_csrf_token = @$this->input->post('token');
        // $res = [];
        // if (csrf_get_token() != $ex_csrf_token){
        //     $res['status']  = 0;
        //     $res['message'] = $this->csrf_message;
        //     echo json_encode($res);
        // }else{

            //drop table backup lama 
            $sql = '';
            $sql .= 'DROP TABLE "m_action_plan_backup";';
            $sql .= 'DROP TABLE "m_action_plan_month_backup";';
            $sql .= 'DROP TABLE "m_action_plan_year_backup";';

            // buat table backup baru 
            $sql .= 'CREATE TABLE "m_action_plan_backup" AS TABLE "m_action_plan";';
            $sql .= 'CREATE TABLE "m_action_plan_month_backup" AS TABLE "m_action_plan_month";';
            $sql .= 'CREATE TABLE "m_action_plan_year_backup" AS TABLE "m_action_plan_year";';
            $result = $this->db->query($sql);

            //autoincrement
            $arr_table = ['m_action_plan_backup','m_action_plan_month_backup','m_action_plan_year_backup'];
            foreach($arr_table as $table){
                $sql = "CREATE SEQUENCE ".$table."_id_seq OWNED BY ".$table.".id;";
                $sql .= "SELECT setval('".$table."_id_seq', coalesce(max(id), 0) + 1, false) FROM ".$table.";
                         ALTER TABLE ".$table." ALTER COLUMN id SET DEFAULT nextval('".$table."_id_seq'); ";
                $sql .= "ALTER TABLE ".$table." ALTER COLUMN is_active SET DEFAULT true";
                 $this->db->query($sql);
             }

            //result
            if($result){
                $res['status'] = '1';
                $res['message'] = 'Backup Data Success!';
            }else{
                $res['status'] = '0';
                $res['message'] = 'Backup Data Failed!';
            }
            echo json_encode($res);
        // }
    }


    public function restore_data() {
        //cek csrf token
        // $ex_csrf_token = @$this->input->post('token');
        // $res = [];
        // if (csrf_get_token() != $ex_csrf_token){
        //     $res['status']  = 0;
        //     $res['message'] = $this->csrf_message;
        //     echo json_encode($res);
        // }else{

            //drop table lama 
            $sql = '';
            $sql .= 'DROP TABLE "m_action_plan";';
            $sql .= 'DROP TABLE "m_action_plan_month";';
            $sql .= 'DROP TABLE "m_action_plan_year";';

            // buat table backup baru 
            $sql .= 'CREATE TABLE "m_action_plan" AS TABLE "m_action_plan_backup";';
            $sql .= 'CREATE TABLE "m_action_plan_month" AS TABLE "m_action_plan_month_backup";';
            $sql .= 'CREATE TABLE "m_action_plan_year" AS TABLE "m_action_plan_year_backup";';
            $result = $this->db->query($sql);

            //autoincrement
            $arr_table = ['m_action_plan','m_action_plan_month','m_action_plan_year'];
            foreach($arr_table as $table){
                $sql = "CREATE SEQUENCE ".$table."_id_seq OWNED BY ".$table.".id;";
                $sql .= "SELECT setval('".$table."_id_seq', coalesce(max(id), 0) + 1, false) FROM ".$table.";
                        ALTER TABLE ".$table." ALTER COLUMN id SET DEFAULT nextval('".$table."_id_seq'); ";
                $sql .= "ALTER TABLE ".$table." ALTER COLUMN is_active SET DEFAULT true";
                $this->db->query($sql);
            }

            //result
            if($result){
                $res['status'] = '1';
                $res['message'] = 'Restore Data Success!';
            }else{
                $res['status'] = '0';
                $res['message'] = 'Restore Data Failed!';
            }
            echo json_encode($res);
        // }
    }

    public function get_total_weighting_factor($id_si='',$tipe='json') {

        //cek tipe
        if($tipe == 'json'){
            $id_si = @$this->input->post('id_si');
        }else{
            $id_si = $id_si;
        }

        //get data
        $select = 'SUM("weighting_factor"::numeric) as total';
        $where['is_active'] = 't';
        $where['parent'] = '0';
        $where['id_si'] = $id_si;
        $total = @$this->m_global->getDataAll('m_action_plan', null, $where, $select, null, null, null, null, "id_si")[0]->total;
        if($total == ''){ $total = 0; }

        //result
        if($tipe == 'json'){
            $res['val'] = round( $total, 2, PHP_ROUND_HALF_DOWN);
            echo json_encode($res);exit;
        }else{
            $total = round( $total, 2, PHP_ROUND_HALF_DOWN);
            return $total;
        }
    }

    public function get_total_sub_weighting_factor() {
        //get data
        $id_sub_si = @$this->input->post('id_sub_si');
        $select = 'SUM("weighting_factor"::numeric) as total';
        $where['is_active'] = 't';
        $where['parent'] = $id_sub_si;
        $total = @$this->m_global->getDataAll('m_action_plan', null, $where, $select, null, null, null, null, "id_si")[0]->total;
        if($total == ''){ $total = 0; }
        $res['val'] = round( $total, 2, PHP_ROUND_HALF_DOWN);
        echo json_encode($res);exit;
    }


    public function select_si()
    {
        if(isset($_REQUEST['q'])){
            $q = strtolower($_REQUEST['q']);
            $id_bsc = @$_REQUEST['id_bsc'];
            $where = "a.is_active = 't' AND a.status_si = '3'";
            if(!empty($_REQUEST['q'])){
                $where .= " AND ( LOWER(a.name) LIKE '%".$q."%' OR a.code::TEXT LIKE '%".$q."%' )";
            }
            if(!empty($id_bsc)){
                $where .= " AND a.id_bsc = '".$id_bsc."'";
            }
            
            //cek role pic si/ pic ic
            $role_id = h_session('ROLE_ID');
            if($role_id == '5' || $role_id == '9'){
                $nip = h_session('NIP');
                $where2 = "a.nip = '".$nip."' AND a.role_id='".$role_id."' AND a.is_active='t'";
                $arr_id_si = @$this->m_global->getDataAll('m_user_ic_si AS a', null, $where2, 'a.id_si')[0]->id_si;
                if($arr_id_si != ''){
                    $where .= " AND a.id IN(".$arr_id_si.")";
                }else{
                    $where .= " AND a.id is null";
                }
            }

            //select data
            $select = "a.id, a.code, a.name, a.pic_si, a.start_date, a.end_date";
            $arr = @$this->m_global->getDataAll('m_si AS a', NULL, $where, $select, null, "a.code ASC");
            $data = [];
            for ($i=0; $i < count($arr); $i++) {
                $name = '<b>['.$arr[$i]->code.']</b> '.$arr[$i]->name;
                $data[$i] = ['id' => $arr[$i]->id,  'name' => $name,  
                                'pic_si' => $arr[$i]->pic_si, 
                                'start_date' => substr($arr[$i]->start_date,0,7),
                                'end_date' => substr($arr[$i]->end_date,0,7)
                            ];
            }
            echo json_encode(['item' => $data]);
        }else{
            $id = $_REQUEST['id'];
            $where = ['id' => $id];
            $select = "a.id, a.code, a.name, a.pic_si, a.start_date, a.end_date";
            $arr = @$this->m_global->getDataAll('m_si AS a', NULL,$where, $select,NULL,"a.code ASC");
            $name = '<b>['.$arr[0]->code.']</b> '.$arr[0]->name;
            $data[] = [ 'id' => @$arr[0]->id,  'name' => @$name,  
                        'pic_si' => $arr[0]->pic_si, 
                        'start_date' => substr($arr[0]->start_date,0,7),
                        'end_date' => substr($arr[0]->end_date,0,7)
                    ];
            echo json_encode($data);
        }
    }


    public function select_pic_action_plan()
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
            $where      = " position_id_new IN ('".$id."') ";
            $select     = 'posisi, singkatan_posisi, position_id, position_id_new';
            $parent     = $this->m_global->getDataAll('m_pic', NULL, $where, $select, NULL, 'singkatan_posisi ASC',0,20);
            for ($a=0; $a < count($parent); $a++) {
                $name = '<b>'.$parent[$a]->singkatan_posisi.'</b> ["'.$parent[$a]->posisi.'"]';
                $data[] = ['id' => $parent[$a]->position_id_new, 'name' => $name];
            }
            echo json_encode($data);

        }
    }

    public function convert_pic_action_plan($pic_name = '')
    {
        //cek pic kosong
        if($pic_name != ''){

            $arr_pic = [];

            //cek pic lebih dari 1
            $pic_name = str_replace('  ',' ',$pic_name);
            $pic_name = str_replace('  ',' ',$pic_name);
            $pic_name = str_replace('  ',' ',$pic_name);
            $pic_name = str_replace(' . ','. ',$pic_name);
            $pic_name = str_replace(' , ',', ',$pic_name);
            $pic_name = str_replace('.  ','. ',$pic_name);
            $pic_name = str_replace(',  ',', ',$pic_name);
            $cek = explode('. ',$pic_name);
            $cek2 = explode(', ',$pic_name);
            if(@$cek[1] != ''){
                foreach($cek as $val){
                    $arr_pic[] = $this->get_id_pic_action_plan($val);
                }
            }elseif(@$cek2[1] != ''){
                foreach($cek2 as $val){
                    $arr_pic[] = $this->get_id_pic_action_plan($val);
                }
            }else{
                $arr_pic[] = $this->get_id_pic_action_plan($pic_name);
            }

            //cek pic lebih dari 1
            // $cek = explode('/ ',$pic_name);
            // if(count($cek) > 1){
            //     $arr_pic = [];
            //     foreach($cek as $val){
            //         $arr_pic[] = $this->get_id_pic_action_plan($val);
            //     }
            // }else{
            //     $arr_pic[] = $this->get_id_pic_action_plan(@$cek[0]);
            // }

            $id_pic = join(', ',$arr_pic);
            //cek koma di depan
            $cek_koma_di_depan = substr($id_pic,0,2);
            if($cek_koma_di_depan == ', '){
                $id_pic = substr($id_pic,2);
            }

        }else{
            $id_pic = '';
        }
        return $id_pic;
    }


    public function convert_id_position_to_name($id_position = '')
    {
        //cek pic kosong
        if($id_position != ''){
            //cek pic lebih dari 1
            $cek = explode(', ',$id_position);
            if(count($cek) > 1){
                $arr_id_position = join(', ',$cek);
            }else{
                $arr_id_position = $cek[0];
            }
            
            //cek di table ERP_STO_REAL
            $temp = [];
            $where         = "\"POSITION_ID\" IN(".$arr_id_position.")";
            $select        = '"SINGKATAN_POSISI"';
            $arr_singkatan_posisi   = @$this->m_global->getDataAll('ERP_STO_REAL', NULL, $where, $select);
            foreach($arr_singkatan_posisi as $row){
                $temp[] = $row->SINGKATAN_POSISI;
            }

            //cek di table m_pic
            $where         = "position_id_new IN(".$arr_id_position.")";
            $select        = 'singkatan_posisi';
            $arr_singkatan_posisi   = @$this->m_global->getDataAll('m_pic', NULL, $where, $select);
            foreach($arr_singkatan_posisi as $row){
                $temp[] = $row->singkatan_posisi;
            }
            $singkatan_posisi = join(', ',$temp);

        }else{
            $singkatan_posisi = '';
        }
        return $singkatan_posisi;
    }

    public function get_id_pic_action_plan($pic_name = '')
    {
        //dari table ERP_STO_REAL
        $position_id_new = '';
        $where         = "\"SINGKATAN_POSISI\" = '".$pic_name."'";
        $select        = '"POSITION_ID"';
        $position_id_erp = @$this->m_global->getDataAll('ERP_STO_REAL', NULL, $where, $select)[0]->POSITION_ID;
        if($position_id_erp == ''){
            //dari table m_pic
            $where         = " singkatan_posisi = '".$pic_name."' ";
            $select        = 'position_id_new';
            $position_id_new   = @$this->m_global->getDataAll('m_pic', NULL, $where, $select)[0]->position_id_new;
            if($position_id_new == ''){
                $position_id_max = @$this->m_global->getDataAll('m_pic', NULL, NULL, "MAX(position_id_new) AS position_id_new")[0]->position_id_new;
                if($position_id_max == ''){ 
                    $position_id_new = '999999000000';
                }else{
                    $position_id_new = 1 + $position_id_max;
                }
                $data_pic = [];
                $data_pic['position_id_new'] = $position_id_new;
                $data_pic['posisi'] = $pic_name;
                $data_pic['singkatan_posisi'] = $pic_name;
                $this->m_global->insert('m_pic', $data_pic);
            }
        }else{
            $position_id_new = $position_id_erp;
        }
        return $position_id_new;
    }


    public function change_status() {
        // //cek csrf token
        // $ex_csrf_token = @$this->input->post('token');
        // $res = [];
        // if (csrf_get_token() != $ex_csrf_token){
        //     $res['status']  = 0;
        //     $res['message'] = $this->csrf_message;
        //     echo json_encode($res);
        // }else{
            $id = $this->input->post('id');
            $val = $this->input->post('val');
            $data['status_action_plan'] = $val;
            $res = $this->m_global->update('m_action_plan', $data, ['id' => $id]);
            $res['message'] = 'Success!';
            echo json_encode($res);
        // }
    }



    // =========================== upload file ============================
    public function list_file_ic($html=FALSE, $id='')
    {   
        if($html == FALSE){
            $id = $this->input->post('id');
        }
        $where = ['id_from'=>$id, 'type'=>'kpi so', 'is_active'=>'t'];
        $file_ic = @$this->m_global->getDataAll('m_file', null, $where, '*');
        $data['file_ic']  = $file_ic;

        $data['url'] = $this->url;
        $isi = $this->template->display_ajax($this->url.'/v_ic_list_file', $data, NULL, NULL, $html);
        if($html){return $isi;}else{echo $isi;}
    }
    
    public function load_popup_upload_ic()
    {
        $id = $this->input->post('id');
        $data['id'] = $id;
        
        $data['html_list_file_upload_ic'] = $this->list_file_upload_ic(TRUE,$id);

        $data['url'] = $this->url;
        $this->template->display_ajax($this->url.'/v_ic_upload_file', $data);
    }
    
    public function list_file_upload_ic($html=FALSE, $id='')
    {   
        if($html == FALSE){
            $id = $this->input->post('id');
        }
        $where = ['id_from'=>$id, 'type'=>'kpi so', 'is_active'=>'t'];
        $file_ic = @$this->m_global->getDataAll('m_file', null, $where, '*');
        $data['file_ic']  = $file_ic;

        $data['url'] = $this->url;
        $isi = $this->template->display_ajax($this->url.'/v_ic_list_file_upload', $data, NULL, NULL, $html);
        if($html){return $isi;}else{echo $isi;}
    }

    public function upload_file_ic(){
        $date_now                   = date('Y-m-d H:i:s');
        $file_name_origin           = h_replace_file_name($_FILES['userfile']['name']); 
        $type_file                  = h_file_type($_FILES['userfile']['name']);
        $id                         = $this->input->post('id');
        $tgl                        = date('Ymdhis');
        $random                     = rand(1,100);
        //upload
        $folder                     = './public/files/ic/';
        $file_name                  = 'ic_'.$id.'_'.$tgl.'_'.$random;
        $input_name                 = array_keys($_FILES)[0];
        $file_type                  = '*'; 
        $upload = h_upload($folder,$file_name,$input_name,$file_type);
        //insert data
        if($upload == TRUE){
            $data = array(
                'id_from'          => $id,
                'type'             => 'kpi so',
                'file_name'        => $file_name.'.'.$type_file,
                'created_by'       => h_session('USERNAME'),
                'created_date'     => date("Y-m-d H:i:s"),
            );
            $result = $this->db->insert('m_file', $data);
        }
    }

    public function delete_file_ic()
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



    public function download_excel(){

        //load model view
        $param = @$this->input->post('input_form');
        $arr = json_decode($param);
        // echo '<pre>';print_r($param);exit;
        
        //load model
        $this->load->model('app/m_ic','m_ic');

        //search default
        $where  = [];
        $whereE = " a.is_active = 't' ";

        //filter global
        $id_bsc = @$arr->global_id_bsc;
        $id_si = @$arr->global_id_si;
        $year = @$arr->global_year;
       
        //Action Plan
        $order = "a.id_si ASC, a.code ASC";
        $select = 'a.id, a.id_si, a.id_bsc, a.code AS code_action_plan, a.name AS name_action_plan, 
                    a.pic_action_plan, a.deliverable, a.weighting_factor, 
                    a.status_action_plan, a.start_date, a.end_date, a.budget_currency,
                    x.name AS name_si, x.code AS code_si';
        $join  = [  ['table' => 'm_si x', 'on' => 'x.id = a.id_si', 'join' => 'LEFT']  ];
        $where['a.is_active'] = 't';
        if($id_bsc != ''){ $where['a.id_bsc'] = $id_bsc; }
        if($id_si != ''){ $where['a.id_si'] = $id_si; }
        //cek year
        if($year != ''){
            $date_start = $year.'-01-01';
            $date_end = $year.'-12-31';
            $whereE .= " AND ((a.\"start_date\" >= '".$date_start."' AND a.\"start_date\" <= '".$date_end."') ";
            $whereE .= " OR (a.\"end_date\" >= '".$date_start."' AND a.\"end_date\" <= '".$date_end."') ";
            $whereE .= " OR (a.\"start_date\" <= '".$date_start."' AND a.\"end_date\" >= '".$date_end."')) ";
        }
        $arr = @$this->m_global->getDataAll('m_action_plan AS a', $join, $where, $select,null,$order);
        // echo $this->db->last_query();exit;
        // echo '<pre>';print_r($arr);exit;

        //tampung data
        $arr_action_plan = $arr_si = $arr_code_si = $arr_name_si = [];
        $arr_id_action_plan = [];
        foreach($arr as $row){
            //Action Plan year
            $arr_si[$row->id_si] = $row->id_si;
            $arr_code_si[$row->id_si] = $row->code_si;
            $arr_name_si[$row->id_si] = $row->name_si;
            $arr_id_action_plan[$row->id_si][$row->id] = $row->id;
            $arr_action_plan[$row->id]['code_action_plan']  = str_replace('.0','.',$row->code_action_plan);
            $arr_action_plan[$row->id]['name_action_plan']  = $row->name_action_plan;
            $arr_action_plan[$row->id]['pic_action_plan']   = $this->convert_id_position_to_name($row->pic_action_plan);
            $arr_action_plan[$row->id]['deliverable']       = $row->deliverable;
            $arr_action_plan[$row->id]['weighting_factor']  = $row->weighting_factor;
            $arr_action_plan[$row->id]['status_action_plan']= $row->status_action_plan;
            $arr_action_plan[$row->id]['start_date']        = $row->start_date;
            $arr_action_plan[$row->id]['end_date']          = $row->end_date;
            $arr_action_plan[$row->id]['budget_currency']   = $row->budget_currency;
        }
        // echo '<pre>';print_r($arr_action_plan);exit;


        //budget
        $select = 'a.id, a.id_si, z.year, z.budget';
        $join  = [  ['table' => 'm_action_plan a', 'on' => 'a.id = z.id_action_plan', 'join' => 'LEFT'] ];
        $where['a.is_active'] = 't';
        if($id_bsc != ''){ $where['a.id_bsc'] = $id_bsc; }
        if($id_si != ''){ $where['a.id_si'] = $id_si; }
        if($year != ''){ $where['z.year'] = $year; }
        $arr = @$this->m_global->getDataAll('m_action_plan_year AS z', $join, $where, $select);
        // echo $this->db->last_query();exit;
        // echo '<pre>';print_r($arr);exit;

        //tampung data
        $arr_budget = $arr_year = [];
        foreach($arr as $row){
            $arr_budget[$row->id][$row->year] = $row->budget;
            $arr_year[$row->id_si][$row->year] = $row->year;
        }
        // echo '<pre>';print_r($arr_budget);exit;
        
        //param excel
        $template_name  = 'template_default_action_plan.xls';
        $title          = 'Action Plan'.$year;
        $filename       = 'Data Action Plan'.$year.'.xlsx';

        //load library
        $this->load->library("excel");
        include APPPATH.'/third_party/PHPExcel/Writer/Excel2007.php';
        $objReader = PHPExcel_IOFactory::createReader('Excel5');
        if(h_localhost()){
            $this->excel = $objReader->load(APPPATH."third_party\\template_excel\\".$template_name);
        }else{
            $this->excel = $objReader->load(APPPATH."third_party//template_excel//".$template_name);
        }

        //data tambahan
        $bsc_name = @$this->m_global->getDataAll('m_bsc', null, ['id'=> $id_bsc], 'name')[0]->name;
        $bsc = $bsc_name;


        //create sheet si jika SI lebih dari 1
        if(count($arr_si) > 0){
            $i = -1;
            foreach($arr_si as $id_si) { 
                $i++;
                $code_si = 'SI '.$arr_code_si[$id_si];
                if($i == 0){
                    $this->excel->setActiveSheetIndex(0)->setTitle("$code_si");
                }else{
                    $tempSheet = $this->excel->getSheet(0)->copy();
                    $tempSheet->setTitle("$code_si");
                    $this->excel->addSheet($tempSheet);
                    unset($tempSheet);
                }
            }
        }else{
            echo 'SI Tidak ditemukan';exit;
        }

        //sheet si
        $i = -1;
        foreach($arr_si as $id_si)
        {
            $i++;

            //bsc dan si name
            $this->excel->setActiveSheetIndex($i)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString('B')-1, 2, $bsc);
            $this->excel->setActiveSheetIndex($i)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString('D')-1, 2, $arr_name_si[$id_si]);
            
            //year budget start end
            $year_start_budget = min($arr_year[$id_si]);
            $year_end_budget =  max($arr_year[$id_si]);
            
            //style budget text
            $z="I"; 
            for($y = $year_start_budget; $y <= $year_end_budget; $y++){
                $text_budget_year = "Budget ".$y;
                $this->excel->setActiveSheetIndex($i)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString($z)-1, 3, $text_budget_year);
                $new_z = $z;
                $z++;
            }
            //style budget color
            $this->excel->getActiveSheet()->getStyle('I3:'.($new_z).'3')->applyFromArray( 
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'FF0000')
                    )
                )
            );

            //masukan data
            $baris = 4;
            $a = -1;
            foreach($arr_id_action_plan[$id_si] as $id_action_plan){
                $a++;

                //data excel
                $code_action_plan       = $arr_action_plan[$id_action_plan]['code_action_plan'];   
                $name_action_plan       = $arr_action_plan[$id_action_plan]['name_action_plan'];   
                $pic_action_plan        = $arr_action_plan[$id_action_plan]['pic_action_plan'];    
                $deliverable            = $arr_action_plan[$id_action_plan]['deliverable'];        
                $status_action_plan     = $arr_action_plan[$id_action_plan]['status_action_plan']; 
                $start_date             = date_format(new DateTime($arr_action_plan[$id_action_plan]['start_date']), "d/m/Y");         
                $end_date               = date_format(new DateTime($arr_action_plan[$id_action_plan]['end_date']), "d/m/Y");          
                $weighting_factor       = $arr_action_plan[$id_action_plan]['weighting_factor'];
                $budget_currency        = $arr_action_plan[$id_action_plan]['budget_currency'];

                $this->excel->setActiveSheetIndex($i)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString('A')-1,$baris, $code_action_plan);
                $this->excel->setActiveSheetIndex($i)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString('B')-1,$baris, $name_action_plan);
                $this->excel->setActiveSheetIndex($i)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString('C')-1,$baris, $pic_action_plan);
                $this->excel->setActiveSheetIndex($i)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString('D')-1,$baris, $deliverable);
                $this->excel->setActiveSheetIndex($i)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString('E')-1,$baris, $start_date);
                $this->excel->setActiveSheetIndex($i)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString('F')-1,$baris, $end_date);
                $this->excel->setActiveSheetIndex($i)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString('G')-1,$baris, $weighting_factor);
                $this->excel->setActiveSheetIndex($i)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString('H')-1,$baris, $budget_currency); 
                
                //budget
                $z="I"; 
                for($y = $year_start_budget; $y <= $year_end_budget; $y++){
                    $budget = @$arr_budget[$id_action_plan][$y];
                    $this->excel->setActiveSheetIndex($i)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString($z)-1, $baris, $budget); 
                    $z++;
                }

                //bug format code 1.10 
                if(strpos($code_action_plan,'.') !== false ) { 
                    $pecah = explode('.',@$code_action_plan);
                    if(@$pecah[1] >= 10){
                        $this->excel->getActiveSheet()->getStyle('A'.$baris)->getNumberFormat()->setFormatCode('0.00');
                    }
                }else{
                    $this->excel->getActiveSheet()->getStyle('A'.$baris.':M'.$baris)->applyFromArray( 
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'EEECE1')
                            )
                        )
                    );
                }
                $baris++;
            }
        }

        $this->excel->getProperties()->setCreator("SIMO")
                                    ->setLastModifiedBy("SIMO")
                                    ->setTitle($title)
                                    ->setSubject($title)
                                    ->setDescription($title)
                                    ->setKeywords($title)
                                    ->setCategory($title);

        $data_excel =  $this->excel;
        $objWriter  = PHPExcel_IOFactory::createWriter($data_excel,'Excel2007');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: must-revalidate');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public'); 

        ob_end_clean();
        $objWriter->save("php://output");

    }


    public function upload_excel(){

        //setting file
        // echo '<pre>';print_r($format_template);exit;
        // echo '<pre>';print_r($_FILES);exit;
        $fileName = 'template_action_plan'.time().$_FILES['file']['name'];
        $folder = './public/files/temp/';
        $config['upload_path']   = $folder; //buat folder dengan nama assets di root folder
        $config['file_name']     = $fileName;
        $config['allowed_types'] = '*';
        $config['allowed_types'] = 'xls|xlsx';
        $config['max_size']      = 100000; //100mb

        $this->load->library('upload');
        $this->upload->initialize($config);

        //cek upload file
        if ( ! $this->upload->do_upload('file') ){
            //error upload
            $res['status']   = '0';
            $res['message']  = 'Invalid file uploaded!';
            // $a = $this->upload->display_errors();
            // echo $a;exit;
        }else{

            //upload data
            $file_data = $this->upload->data();
            $folder_file =  $folder.$file_data['file_name'];

            //cek jika ada error, error jangan diproses
            try {
                //load library excel
                $this->load->library("PHPExcel");
                $inputFileType   = PHPExcel_IOFactory::identify($folder_file);
                $objReader       = PHPExcel_IOFactory::createReader($inputFileType);
                // $objReader->setReadDataOnly(true);
                $objPHPExcel     = $objReader->load($folder_file);
                $worksheet_names = $objReader->listWorksheetNames($folder_file);

                foreach ($worksheet_names as $key => $val_sheet) {
                    
                    //cek nama si
                    if(strpos($val_sheet,'SI') === false ) { continue; }
                    $si_code = str_replace(',','.',str_replace('SI','',str_replace(' ','',$val_sheet)));
                    $id_bsc = $this->input->post('id_bsc');
                    $where = ['code'=>$si_code, 'id_bsc'=>$id_bsc];
                    $id_si = @$this->m_global->getDataAll('m_si', NULL,$where,"id")[0]->id;
                    if($id_si == ''){ continue; }

                    //membaca data sheet 
                    $sheet              = $objPHPExcel->getSheet($key); 
                    $highestRow         = $sheet->getHighestRow(); 
                    $highestColumn      = $sheet->getHighestColumn();
                    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

                    //kolom field
                    $arr_data_col =  $sheet->rangeToArray('A3:' . $highestColumn .'3', NULL, TRUE, FALSE, FALSE)[0];
                    // echo '<pre>';print_r($arr_data_col);exit;


                    //====================================== Format Default ==================================================
                    
                    //cek template format
                    $format_template = $this->input->post('format_template');
                    if($format_template == 'template_ic_default'){
                        
                        //tampung data perbaris
                        $arr_data = [];
                        for($row = 4; $row <= $highestRow; $row++){
                            //array excel
                            $arr_row =  $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE, FALSE)[0];
                            $arr_row_after =  $sheet->rangeToArray('A' . ($row+1) . ':' . $highestColumn . ($row+1), NULL, TRUE, FALSE, FALSE)[0];
                            // echo '<pre>';print_r($arr_row);exit;
                            // echo '<pre>';print_r($arr_row_after);exit;

                            //cek jika datanya kosong
                            if(@$arr_row[0] == ''){ continue;}

                            //cek parent
                            if(strpos($arr_row[0],'.') !== false ){  $parent = 'no'; }else{ $parent = 'yes'; }
                            //cek parent after
                            if(strpos($arr_row_after[0],'.') !== false ){ $parent_after = 'no'; }else{ $parent_after = 'yes'; }

                            //bug jika code 1.10 menjadi 1.1
                            if(@$parent == 'no' && @$code_action_plan_sub != '' && @$code_action_plan_sub != 9){
                                $temp_code = @$code_action_plan_sub+1;
                                $code_action_plan_new = @$code_action_plan_parent.'.'.str_pad($temp_code,2,'0',STR_PAD_LEFT);
                            }elseif(@$parent == 'no' && @$code_action_plan_sub != '' && @$code_action_plan_sub == 9){
                                $code_action_plan_new = @$code_action_plan_parent.'.10';
                            }else{
                                if(strpos($arr_row[0],'.' ) !== false ) {
                                    $pecah = explode('.',@$arr_row[0]);
                                    $code_action_plan_new = $pecah[0].'.01';
                                }else{
                                    $code_action_plan_new = $arr_row[0];
                                }
                            }

                            //tampung datanya
                            $arr_temp = [];
                            $arr_temp['code_action_plan']   = $code_action_plan_new;
                            $arr_temp['name_action_plan']   = $arr_row[1];
                            $arr_temp['pic_action_plan']    = $this->convert_pic_action_plan($arr_row[2]);
                            $arr_temp['deliverable']        = $arr_row[3];
                            $arr_temp['start_date']         = h_date_excel(@$arr_row[4]);
                            $arr_temp['end_date']           = h_date_excel(@$arr_row[5]);
                            $arr_temp['weighting_factor']   = round(str_replace(',','.',$arr_row[6]), 2, PHP_ROUND_HALF_DOWN);
                            $arr_temp['budget_currency']    = $arr_row[7];
                            // echo '<pre>';print_r($arr_temp);

                            //budget
                            $start_year = str_replace(' ','',str_replace('budget','',strtolower(@$arr_data_col[8])));
                            $end_year = $start_year;
                            for($a = 8; $a<= count($arr_data_col);$a++){
                                $cek = @$arr_data_col[$a];
                                if($cek == ''){
                                    $end_year = str_replace(' ','',str_replace('budget','',strtolower(@$arr_data_col[$a-1])));
                                    break;
                                }
                            }
                            $arr_budget = []; $i = 8;
                            for($y = $start_year; $y<=$end_year;$y++){ 
                                $arr_budget[$y] = @$arr_row[$i];
                                $i++;
                            }
                            $arr_temp['arr_budget'] = $arr_budget;

                            //cek parent, parent_after, maka buat sub menu manual/ duplicate data parent dengan nomornya dibedakan
                            if($parent == 'yes' && $parent_after == 'yes'){
                                //insert parent
                                $arr_temp['parent'] = 'yes';
                                $arr_data[] = $arr_temp;
                                //insert sub parent
                                $arr_temp['parent'] = 'no';
                                $arr_temp['code_action_plan'] = $arr_row[0].'.01';
                                $arr_data[] = $arr_temp;
                            }elseif($parent == 'yes' && $parent_after == 'no'){
                                $arr_temp['parent'] = 'yes';
                                $arr_data[] = $arr_temp;
                            }elseif($parent == 'no' && $parent_after == 'yes'){
                                $arr_temp['parent'] = 'no';
                                $arr_data[] = $arr_temp;
                            }elseif($parent == 'no' && $parent_after == 'no'){
                                $arr_temp['parent'] = 'no';
                                $arr_data[] = $arr_temp;
                            }

                            //bug untuk code 1.10
                            $pecah = explode('.',@$arr_row[0]);
                            if(@$code_action_plan_sub == '9'){
                                $code_action_plan_parent = @$pecah[0];
                                $code_action_plan_sub = 10;
                            }else{
                                $code_action_plan_parent = @$pecah[0];
                                $code_action_plan_sub = @$pecah[1];
                            }
                            

                        }
                    }
                    // echo '<pre>';print_r($arr_data);exit;


                    //================================== Format JVC =====================================================
                    $format_template = $this->input->post('format_template');
                    if($format_template == 'template_ic_JVC'){
                        
                        //copy sub parent
                        $arr_row_sub = $arr_row_parent = [];
                        for($row = 4; $row <= $highestRow; $row++){
                            $arr_row_copy =  $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE, FALSE)[0];
                            if(strpos($arr_row_copy[0],'.') !== false ){  
                                $arr_row_sub[] = $arr_row_copy; 
                            }else{
                                $arr_row_parent[] = $arr_row_copy;
                            }
                        }
                        //gabungkan parent dan sub
                        $arr_new = [];
                        foreach($arr_row_parent as $key => $row){ 
                            $arr_new[] = $row;
                            $no_parent = $row[0];
                            foreach($arr_row_sub as $key2 => $row2){
                                $pecah = explode('.',$row2[0]);
                                $no_sub = $pecah[1];
                                $row2[0] = $no_parent.'.'.$no_sub;
                                $arr_new[] = $row2;
                            }
                        }
                        // echo '<pre>';print_r($arr_new);exit;

                        //tampung data perbaris
                        foreach($arr_new as $arr_row){ 

                            //cek jika datanya kosong
                            if(@$arr_row[0] == ''){ continue;}
                            
                            //cek parent
                            if(strpos($arr_row[0],'.') !== false ){  $parent = 'no'; }else{ $parent = 'yes'; }

                            //bug jika code 1.10 menjadi 1.1
                            if(@$parent == 'no' && @$code_action_plan_sub != '' && @$code_action_plan_sub != 9){
                                $temp_code = @$code_action_plan_sub+1;
                                $code_action_plan_new = @$code_action_plan_parent.'.'.str_pad($temp_code,2,'0',STR_PAD_LEFT);
                            }elseif(@$parent == 'no' && @$code_action_plan_sub != '' && @$code_action_plan_sub == 9){
                                $code_action_plan_new = @$code_action_plan_parent.'.10';
                            }else{
                                if(strpos($arr_row[0],'.' ) !== false ) {
                                    $pecah = explode('.',@$arr_row[0]);
                                    $code_action_plan_new = $pecah[0].'.01';
                                }else{
                                    $code_action_plan_new = $arr_row[0];
                                }
                            }

                            //tampung datanya
                            $arr_temp = [];
                            $arr_temp['parent']             = $parent;
                            $arr_temp['code_action_plan']   = $code_action_plan_new;
                            $arr_temp['name_action_plan']   = $arr_row[1];
                            $arr_temp['pic_action_plan']    = $this->convert_pic_action_plan($arr_row[2]);
                            $arr_temp['deliverable']        = $arr_row[3];
                            $start_date                     = ($arr_row[4] - 25569) * 86400 ;
                            $arr_temp['start_date']         = gmdate("Y-m-d", $start_date);
                            $end_date                       = ($arr_row[5] - 25569) * 86400 ;
                            $arr_temp['end_date']           = gmdate("Y-m-d", $end_date);
                            $arr_temp['weighting_factor']   = round(str_replace(',','.',$arr_row[6]), 2, PHP_ROUND_HALF_DOWN);
                            $arr_temp['budget_currency']    = $arr_row[7];
                            
                            //budget
                            $start_year = str_replace(' ','',str_replace('budget','',strtolower(@$arr_data_col[8])));
                            $end_year = $start_year;
                            for($a = 8; $a<= count($arr_data_col);$a++){
                                $cek = @$arr_data_col[$a];
                                if($cek == ''){
                                    $end_year = str_replace(' ','',str_replace('budget','',strtolower(@$arr_data_col[$a-1])));
                                    break;
                                }
                            }
                            $arr_budget = []; $i = 8;
                            for($y = $start_year; $y<=$end_year;$y++){ 
                                $arr_budget[$y] = $arr_row[$i];
                                $i++;
                            }
                            $arr_temp['arr_budget'] = $arr_budget;

                            //bug untuk code 1.10
                            $pecah = explode('.',@$arr_row[0]);
                            if(@$code_action_plan_sub == '9'){
                                $code_action_plan_parent = @$pecah[0];
                                $code_action_plan_sub = 10;
                            }else{
                                $code_action_plan_parent = @$pecah[0];
                                $code_action_plan_sub = @$pecah[1];
                            }
                           
                            //tampung datanya
                            $arr_data[] = $arr_temp;
                        }
                    }
                    // echo '<pre>';print_r($arr_data);exit;
                    //===================================================================
                    

                    //=============== masukan data ke database =========================
                    foreach($arr_data as $row){ 

                        //data excel
                        $code_action_plan   = $row['code_action_plan'];
                        $name_action_plan   = $row['name_action_plan'];
                        $pic_action_plan    = $row['pic_action_plan'];
                        $deliverable        = $row['deliverable'];
                        $start_date         = $row['start_date'];
                        $end_date           = $row['end_date'];
                        $weighting_factor   = $row['weighting_factor'];
                        $budget_currency    = $row['budget_currency'];
                        $arr_budget         = $row['arr_budget'];
                        $parent             = ($row['parent'] == 'yes' ? 0 : @$id_parent);

                        //cek data apakah sudah ada
                        $where = [];
                        $where['id_bsc']  = $id_bsc;
                        $where['id_si']   = $id_si;
                        $where['name']    = $name_action_plan;
                        $where['code']    = $code_action_plan;
                        $select = "id, start_date, end_date";
                        $arr_action_plan = @$this->m_global->getDataAll('m_action_plan', NULL,$where,$select)[0];
                        $id_action_plan = @$arr_action_plan->id;
                        // echo $this->db->last_query();exit;
                        // echo '<pre>';print_r($id_action_plan);exit;

                        //cek sudah ada
                        if($id_action_plan == ''){ 
                            //insert
                            $data = [];
                            $data['id_bsc']         = $id_bsc;
                            $data['id_si']          = $id_si;
                            $data['name']           = $name_action_plan;
                            $data['code']           = $code_action_plan;
                            $data['pic_action_plan']= $pic_action_plan;
                            $data['deliverable']    = (string)$deliverable;
                            $data['weighting_factor']= (string)$weighting_factor;
                            $data['budget_currency'] = (string)$budget_currency;
                            $data['start_date']     = $start_date;
                            $data['end_date']       = $end_date;

                            $data['parent']         = $parent;
                            $data['status_action_plan'] = 3;
                            $data['created_date']   = date("Y-m-d H:i:s");
                            $data['created_by']     = h_session('USERNAME');

                            $result = $this->m_global->insert('m_action_plan', $data);
                            $id_action_plan = $this->db->insert_id();
                            if($row['parent'] == 'yes'){
                                $id_parent = $id_action_plan;
                            }
                            $tipe = 'insert';
                        }else{
                            //update
                            $data = [];
                            $data['id_bsc']         = $id_bsc;
                            $data['id_si']          = $id_si;
                            $data['name']           = $name_action_plan;
                            $data['code']           = $code_action_plan;
                            $data['pic_action_plan']= $pic_action_plan;
                            $data['deliverable']    = (string)$deliverable;
                            $data['weighting_factor']= (string)$weighting_factor;
                            $data['budget_currency'] = (string)$budget_currency;
                            $data['start_date']     = $start_date;
                            $data['end_date']       = $end_date;
                            
                            $data['parent']         = $parent;
                            $data['updated_date']   = date("Y-m-d H:i:s");
                            $data['updated_by']     = h_session('USERNAME');

                            $result = $this->m_global->update('m_action_plan', $data, ['id' => $id_action_plan]);
                            if($row['parent'] == 'yes'){
                                $id_parent = $id_action_plan;
                            }
                            $tipe = 'update';
                        }

                        //============================================================================================

                        //jika tipe insert
                        if($tipe == 'insert'){
                            for($y = $start_year; $y <= $end_year;$y++){ 
                                $data2 = [];
                                $data2['id_action_plan']    = $id_action_plan;
                                $data2['year']              = (int)$y;
                                $data2['budget']            = h_format_angka_excel(@$arr_budget[$y]);
                                $data2['status']            = 3;
                                $data2['status_complete']   = 13;
                                $result = $this->m_global->insert('m_action_plan_year', $data2);
                                for($m = '1'; $m<=12;$m++){ 
                                    $data3 = [];
                                    $data3['id_action_plan']    = $id_action_plan;
                                    $data3['year']              = (int)$y;
                                    $data3['month']             = (int)$m;
                                    $data3['status']            = 3;
                                    $data3['status_complete']   = 13;
                                    $result = $this->m_global->insert('m_action_plan_month', $data3);
                                }
                            }
                        }

                        //jika tipe update
                        if($tipe == 'update'){

                            //cek perubahan periode
                            $start_year_old = substr(@$arr_action_plan->start_date,0,4);
                            $end_year_old   = substr(@$arr_action_plan->end_date,0,4);
                            $start_year     = $start_year;
                            $end_year       = $end_year;

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
                            // echo '<pre>';print_r($year_delete);exit;
                            // echo '<pre>';print_r($year_update);exit;
                            // echo '<pre>';print_r($year_insert);exit;

                            //delete budget year dan month
                            if(count($year_delete) > 0){
                                foreach($year_delete as $y){
                                    $where = $data = [];
                                    $where['id_action_plan']    = $id_action_plan;
                                    $where['year']              = (int)$y;
                                    $cek = @$this->m_global->getDataAll('m_action_plan_year', NULL,$where,"id")[0]->id;
                                    if($cek != ''){
                                        $data = [];
                                        $data['is_active'] = 'f';
                                        $result = @$this->m_global->update('m_action_plan_year', $data, $where);
                                        $result = @$this->m_global->update('m_action_plan_month', $data, $where);
                                    }
                                }
                            }
                            
                            //update budget year dan month
                            if(count($year_update) > 0){
                                foreach($year_update as $y){
                                    $data2 = $data_year = [];
                                    $data2['id_action_plan']    = $id_action_plan;
                                    $data2['year']              = (int)$y;
                                    $budget = h_format_angka_excel(@$arr_budget[$y]);
                                    $data_year['budget']        = ($budget == '' ? NULL : $budget);
                                    $result = @$this->m_global->update('m_action_plan_year', $data_year, $data2);
                                    // echo $this->db->last_query();'<br>';

                                    for($m=1;$m<=12;$m++){
                                        $data3 = $data_month = [];
                                        $data3['id_action_plan'] = $id_action_plan;
                                        $data3['year']           = (int)$y;
                                        $data3['month']          = (int)$m;
                                        $data_month['updated_date']   = date("Y-m-d H:i:s");
                                        $data_month['updated_by']     = h_session('USERNAME');
                                        $result = @$this->m_global->update('m_action_plan_month', $data_month, $data3);
                                        // echo $this->db->last_query();'<br>';
                                    }
                                }
                            }

                            //insert budget year dan month
                            if(count($year_insert) > 0){
                                foreach($year_insert as $y){
                                    $data2 = [];
                                    $data2['id_action_plan']    = $id_action_plan;
                                    $data2['year']              = (int)$y;
                                    $budget = @$arr_budget[$y];
                                    $data2['budget']            = ($budget == '' ? NULL : $budget);
                                    //cek insert / update
                                    $where = [];
                                    $where['id_action_plan'] = $id_action_plan;
                                    $where['year'] = (int)$y;
                                    $cek = @$this->m_global->getDataAll('m_action_plan_year', NULL,$where,"id")[0]->id;
                                    if($cek == ''){
                                        $data2['status']          = 3;
                                        $data2['status_complete'] = 13;
                                        $result = @$this->m_global->insert('m_action_plan_year', $data2);
                                    }else{
                                        $data2['is_active']   = 't';
                                        $result = @$this->m_global->update('m_action_plan_year', $data2, $where);
                                    }

                                    //bulan
                                    for($m=1;$m<=12;$m++){
                                        $data3 = [];
                                        $data3['id_action_plan']    = $id_action_plan;
                                        $data3['year']              = (int)$y;
                                        $data3['month']             = (int)$m;
                                        //cek insert / update
                                        $where = [];
                                        $where['id_action_plan']    = $id_action_plan;
                                        $where['year']              = (int)$y;
                                        $where['month']             = (int)$m;
                                        $cek = @$this->m_global->getDataAll('m_action_plan_month', NULL,$where,"id")[0]->id;
                                        if($cek == ''){
                                            $data3['status'] = 3;
                                            $data3['status_complete'] = 13;
                                            $result = @$this->m_global->insert('m_action_plan_month', $data3);
                                        }else{
                                            $data3['is_active'] = 't';
                                            $result = @$this->m_global->update('m_action_plan_month', $data3, $where);
                                        }

                                    }
                                }
                            }
                        }
                        //============================================================================================
                    }

                }

                if (@$result['status'] == TRUE || @$result == TRUE){
                    $res['status']   = '1';
                    $res['message']  = 'Successfully imported file!</br>';
                }else{
                    $res['status']   = '0';
                    $res['message']  = 'Failed !';
                }
                unlink($folder_file);
            }
            //catch exception
            catch(Exception $e) {
                // echo 'Message: ' .$e->getMessage();
                unlink($folder_file);
            }
            echo json_encode($res);

        }
    }

}
