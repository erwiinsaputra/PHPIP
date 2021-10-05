<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Review_sr extends MX_Controller {
    
    private $prefix         = 'review_sr';
    private $table_db       = 'm_strategic_result';
    private $title          = 'Review Strategic Result';
    private $url            = 'app/review_sr';

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
       //status
       $data['status_sr'] = $this->m_global->getDataAll('m_status', null, ['is_active'=>'t', 'type'=>'SO STATUS'], '*', null, '"order" ASC');

        $js['custom']       = ['table_review_sr'];
        $this->template->display($this->url.'/v_'.$this->prefix, $data, $js);
    }

    public function table_review_sr()
    {    
        // load model view 
        $this->load->model('app/m_review_sr','m_review_sr');

        //search default
        $where  = [];
        $whereE = " is_active = 't' AND status_sr = '3'";

        //cek role PIC SO, PICKPI-SO
        if(h_session('ROLE_ID') == '4' || h_session('ROLE_ID') == '8'){ 
            $position_id = h_session('POSITION_ID');
            $whereE .= " AND ".$position_id."::text = ANY (string_to_array(a.pic_sr,', ')::text[]) "; 
        }

        //cek is active
        if(isset($_REQUEST['filter'])){
            foreach ($_REQUEST['filter'] as $row) {
                if(@$row['name'] == 'is_active'){ $whereE = " 0=0 "; }
            }
        }

        //cek role id, jika pic sr
        if(h_session('ROLE_ID') == '4'){
            $where['a.status_sr !='] = '1';
        }

        //filtering global
        // echo '<pre>';print_r($_REQUEST);exit;
        $id_periode = @$_REQUEST['global_id_periode'];
        $id_bsc = @$_REQUEST['global_id_bsc'];
        if($id_periode != ''){ $where['a.id_periode'] = $id_periode; }
        if($id_bsc != ''){ $where['a.id_bsc'] = $id_bsc; }


        //filtering
        $search = [];
        if(isset($_REQUEST['filter'])){
            foreach ($_REQUEST['filter'] as $row) {

                //default parameter
                if(@$row['name'] != ''){

                    $val= $row['value']; $tipe = $row['tipe']; $search[] = $row['name']; 
                    $name = @$row['name'];
                    $name = $this->m_review_sr->select($row['name']); $name = str_replace(' AS '.$row['name'], ' ', $name );

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
        $table = "m_strategic_result AS a";
        $join  = NULL;

        //select 
        $select = "*";
        // $select_field = [ 'id','nip','nik','customer','no_hp' ];
        // $select = array_unique(array_merge($select_field, $search));
        $select = $this->m_review_sr->select($select);

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
            
            $btn_review_sr = '<a href="javascript:;" id="'.$id.'" title="Review Strategic Result" class="btn btn-sm btn-primary btn_review_sr"><i class="fa fa-table"></i> Review Strategic Result</a>';
            @$action = $btn_review_sr;

            //isi table disini
            $isi['no']                  = $i;
            $isi['id']                  = $rows->id;
            $isi['is_active']           = ($rows->is_active == 't' ? 'Active' : 'Non Active');
            $isi['created_date']        = h_format_date($rows->created_date,'d F Y');
            $isi['created_by']          = $rows->created_by;
            $isi['updated_date']        = h_format_date($rows->updated_date,'d F Y');
            $isi['updated_by']          = $rows->updated_by;
            $isi['action']              = @$action;

            $isi['code']                = $rows->code;
            $isi['name']                = h_read_more($rows->name,100);
            $isi['id_bsc']              = $rows->name_bsc;
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

    public function load_review_sr() {

        $this->load->model('app/m_review_sr','m_review_sr');

        $data['url'] = $this->url;
        
       //get data
       $id = $this->input->post('id');
       $year = @$this->input->post('year');
       $month = @$this->input->post('month');

       $arr = @$this->m_global->getDataAll('m_strategic_result AS a', null, ['id'=>$id], 'name, code, start_date, end_date')[0];
       $sr_title = ''.$arr->code.'. '.$arr->name;
       $data['sr_title']    = $sr_title;
       $data['id_sr']       = $id;

       //start_year
       $data['start_year']  = substr($arr->start_date,0,4);
       $data['end_year']    = substr($arr->end_date,0,4);
       $data['year']        = ($year == '' ? date('Y') : $year);
       $data['month']       = ($month == '' ? date('m') : $month);

       $this->template->display_ajax($this->url.'/v_review_sr_add', $data);
    }


    public function load_table_kpi_sr() {

        // echo '<pre>';print_r($this->input->post());exit;
        $this->load->model('app/m_review_sr','m_review_sr');

        $data['url'] = $this->url;

        //parameter
        $id_sr = $this->input->post('id_sr');
        $year = $this->input->post('year');
        $month = @$this->input->post('month');

        $data['id_sr'] = $id_sr;
        $data['year'] = $year;
        $data['month'] = $month;

        //where
        $whereE = " a.id_sr = $id_sr AND is_active = 't' AND status_kpi_sr = '3' ";
        if($month != ''){ 
            $month = str_pad($month,2,'0',STR_PAD_LEFT);
            $date_start = $year.'-'.$month.'-01';
            $date_end = $year.'-'.$month.'-01';
        }else{
            $date_start = $year.'-01-01';
            $date_end = $year.'-12-01';
        }
        $whereE .= " AND ((a.\"start_date\" >= '".$date_start."' AND a.\"start_date\" <= '".$date_end."') ";
        $whereE .= " OR (a.\"end_date\" >= '".$date_start."' AND a.\"end_date\" <= '".$date_end."') ";
        $whereE .= " OR (a.\"start_date\" <= '".$date_start."' AND a.\"end_date\" >= '".$date_end."')) ";

        //get data kpi_sr
        $select = ['id','code_kpi_sr','name_kpi_sr','name_pic_kpi_sr','ukuran','polarisasi','name_polarisasi',
                    'arr_target_from','arr_target_to','arr_target'
                ];
        $select = $this->m_review_sr->select_kpi_sr($select, $year);
        $order = "a.code ASC";
        $arr_kpi_sr = @$this->m_global->getDataAll('m_kpi_sr AS a', null, $whereE, $select, null, $order);
        $data['arr_kpi_sr'] = $arr_kpi_sr;
        // echo '<pre>';print_r($arr_kpi_sr);exit;

        //get data kpi_sr
        $select = 'a.id, a.polarisasi, a.id_sr,
                    z.month, z.target, z.target_from, z.target_to, 
                    z.realisasi, z.pencapaian, z.prognosa, z.prognosa_pencapaian, z.color AS color_id,
                    y.color, w.color as prognosa_color';
        $join  = [  ['table' => 'm_kpi_sr_target_month z', 'on' => 'a.id = z.id_kpi_sr  AND z.status = 3', 'join' => 'LEFT'],
                    ['table' => 'm_color y', 'on' => 'y.id = z.color', 'join' => 'LEFT'],
                    ['table' => 'm_color w', 'on' => 'w.id = z.prognosa_color', 'join' => 'LEFT'],
                    ['table' => 'm_strategic_result x', 'on' => 'x.id = a.id_sr', 'join' => 'LEFT'],
                ];
        $where = ['a.id_sr'=>$id_sr, 'z.year'=>$year, 'a.is_active'=>'1', 'a.status_kpi_sr'=>'3'];
        $arr = @$this->m_global->getDataAll('m_kpi_sr AS a', $join, $where, $select);
        $arr_month = [];
        foreach($arr as $row){
            //target month
            if($row->polarisasi == '10'){
                $target = $row->target_from.' - '.$row->target_to;
            }else{
                $target = $row->target;
            }
            $arr_month[$row->id][$row->month]['target']       = $target;
            $arr_month[$row->id][$row->month]['realisasi']    = $row->realisasi;
            $arr_month[$row->id][$row->month]['pencapaian']   = $row->pencapaian;
            $arr_month[$row->id][$row->month]['color']        = $row->color;
            $arr_month[$row->id][$row->month]['prognosa']     = $row->prognosa;
            $arr_month[$row->id][$row->month]['prognosa_color'] = $row->prognosa_color;
            $arr_month[$row->id][$row->month]['prognosa_pencapaian'] = $row->prognosa_pencapaian;
        }
        $data['month'] = $arr_month;

        // echo '<pre>';print_r($arr_month);exit;

        $this->template->display_ajax($this->url.'/v_review_sr_table_kpi_sr', $data);
    }


    public function load_detail_month() {

        $this->load->model('app/m_monev_kpi_sr','m_monev_kpi_sr');

        $data['url'] = $this->url;

        //data month
        $id_kpi_sr = @$this->input->post('id_kpi_sr');
        $year = @$this->input->post('year');
        $month = @$this->input->post('month');
        $where = ['id_kpi_sr' => $id_kpi_sr, 'year' => $year, 'month' => $month];
        $data['data'] = @$this->m_global->getDataAll('m_kpi_sr_target_month', null, $where, '*')[0];
        // echo $this->db->last_query();exit;

        $this->template->display_ajax($this->url.'/v_review_sr_detail_month', $data);
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
            $this->form_validation->set_rules('name', 'SO Title', 'trim|xss_clean|required');
            $this->form_validation->set_rules('code', 'SO Number', 'trim|xss_clean|required');
            $this->form_validation->set_rules('id_bsc', 'BSC', 'trim|xss_clean|required');
            $this->form_validation->set_rules('id_strategic_theme', 'Perspective', 'trim|xss_clean|required');
            $this->form_validation->set_rules('id_periode', 'Periode', 'trim|xss_clean|required');
            $this->form_validation->set_rules('pic_sr', 'PIC', 'trim|xss_clean|required');
            // $this->form_validation->set_rules('description', 'Description', 'trim|xss_clean|required');
            if ($this->form_validation->run($this)) {

                //insert data
                $data['name']           = @$this->input->post('name');
                $data['code']           = @$this->input->post('code');
                $data['id_bsc']         = @$this->input->post('id_bsc');
                $data['id_strategic_theme'] = @$this->input->post('id_strategic_theme');
                $data['id_periode']     = @$this->input->post('id_periode');
                $data['pic_sr']      = @$this->input->post('pic_sr');
                $data['description']    = @$this->input->post('description');
                $data['created_date']   = date("Y-m-d H:i:s");
                $data['status_sr']      = 1;
                $data['created_by']     = h_session('USERNAME');
                $result = $this->m_global->insert('m_strategic_result', $data);
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
            $this->form_validation->set_rules('name', 'SO Title', 'trim|xss_clean|required');
            $this->form_validation->set_rules('code', 'SO Number', 'trim|xss_clean|required');
            $this->form_validation->set_rules('id_bsc', 'BSC', 'trim|xss_clean|required');
            $this->form_validation->set_rules('id_strategic_theme', 'Perspective', 'trim|xss_clean|required');
            $this->form_validation->set_rules('id_periode', 'Periode', 'trim|xss_clean|required');
            $this->form_validation->set_rules('pic_sr', 'PIC', 'trim|xss_clean|required');
            // $this->form_validation->set_rules('description', 'Description', 'trim|xss_clean|required');
            if ($this->form_validation->run($this)) {

                //update data
                $id = $this->input->post('id');
                $data['name']           = @$this->input->post('name');
                $data['code']           = @$this->input->post('code');
                $data['id_bsc']         = @$this->input->post('id_bsc');
                $data['id_strategic_theme'] = @$this->input->post('id_strategic_theme');
                $data['id_periode']     = @$this->input->post('id_periode');
                $data['pic_sr']         = @$this->input->post('pic_sr');
                $data['description']    = @$this->input->post('description');
                $data['updated_date']   = date("Y-m-d H:i:s");
                $data['updated_by']     = h_session('USERNAME');
                $result = $this->m_global->update('m_strategic_result', $data, ['id' => $id]);

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
            $val = $this->input->post('val');
            $data['is_active'] = $val;
            $res = $this->m_global->update('m_strategic_result', $data, ['id' => $id]);
            if($val == 't'){
                $res['message'] = 'Active Success!';
            }else{
                $res['message'] = 'Delete Success!';
            }
            
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
            if($val == 't'){
                $res['message'] = 'Active Success!';
            }else{
                $res['message'] = 'Delete Success!';
            }
            
            echo json_encode($res);
        }
    }


    public function select_strategic_theme()
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
            $arr_strategic_theme = @$this->m_global->getDataAll('m_bsc AS a', NULL,$where,"a.id_strategic_theme")[0]->id_strategic_theme;
            $arr_strategic_theme = str_replace(' ','',$arr_strategic_theme);
            $where = " id IN(".$arr_strategic_theme.")";
            $arr = @$this->m_global->getDataAll('m_strategic_theme AS a', NULL,$where,"a.id, a.name");
            // echo $this->db->last_query();exit;    
            $data = [];
            for ($i=0; $i < count($arr); $i++) {
                $data[$i] = ['id' => $arr[$i]->id,  'name' => $arr[$i]->name ];
            }
            echo json_encode(['item' => $data]);
        }else{
            $id = $_REQUEST['id'];
            $where = ['id' => $id];
            $arr = @$this->m_global->getDataAll('m_strategic_theme AS a', NULL,$where,"a.id, a.name",NULL,NULL,0,10);
            $data[] = [ 'id' => @$arr[0]->id,  'name' => @$arr[0]->name ];
            echo json_encode($data);
        }
    }

}
