<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monev_kpi_so extends MX_Controller {
    
    private $prefix         = 'monev_kpi_so';
    private $table_db       = 'm_kpi_so';
    private $title          = 'MONITORING KPI - SO';
    private $url            = 'app/monev_kpi_so';

    function __construct() {
        parent::__construct();
        $this->middleware('guest', 'forbidden');
    }

    public function index($id_kpi_so='',$year='',$month='')
    {
        csrf_init();
        $data['title']      = $this->title;
        $data['url']        = $this->url;
        $data['breadcrumb'] = [$this->title => $this->url];

        $arr = [];
        if($id_kpi_so != ''){
            $arr = @$this->m_global->getDataAll('m_kpi_so', null,  ['id'=>$id_kpi_so])[0];
        }
        if(@$arr->id != ''){
            $data['id_bsc']         = $arr->id_bsc;
            $data['id_perspective'] = $arr->id_perspective;
            $data['id_so']          = $arr->id_so;
            $data['id_kpi_so']      = $arr->id;
            $data['year']           = $year;
            $data['month']          = $month;
        }else{
            $data['id_bsc']         = 1;
            $data['year']           = date('Y');
        }

        //bsc
        $data['bsc'] = $this->m_global->getDataAll('m_bsc', null,  ['is_active'=>'t'], '*', null, "name ASC");
        //periode
        $data['periode'] = $this->m_global->getDataAll('m_periode', null, ['is_active'=>'t'], '*', null, "start_year ASC");
        
        //periode
        $data['periode'] = $this->m_global->getDataAll('m_periode', null, ['is_active'=>'t'], '*', null, "start_year ASC");
        $data['start_year'] = '';
        $data['end_year'] = '';
        $i=0;
        foreach($data['periode'] as $row){
            $i++;
            if($i == 1){
                $data['start_year'] = $row->start_year;
            }
            if($i == count($data['periode'])){
                $data['end_year'] = $row->end_year;
            }
        }
        $js['custom'] = ['table_monev_kpi_so'];
        $this->template->display($this->url.'/v_'.$this->prefix, $data, $js);
    }

    public function table_monev_kpi_so()
    {    
        // load model view 
        $this->load->model('app/m_monev_kpi_so','m_monev_kpi_so');

        //search default
        $where  = [];
        $whereE = " is_active = 't' AND status_kpi_so = '3' ";


        //cek role pic kpi-so
        if(h_session('ROLE_ID') == '8'){ 
            $nip = h_session('NIP');
            $whereE .= " AND '".$nip."'::text = ANY (string_to_array(a.user_pic_kpi_so,', ')::text[]) "; 
        }

        //cek role pic kpi-so manager
        if(h_session('ROLE_ID') == '10'){ 
            $nip = h_session('NIP');
            $whereE .= " AND '".$nip."'::text = ANY (string_to_array(a.user_pic_manager,', ')::text[]) "; 
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
        $id_perspective = @$_REQUEST['global_id_perspective'];
        $id_so = @$_REQUEST['global_id_so'];
        $id_kpi_so = @$_REQUEST['global_id_kpi_so'];

        if($id_bsc != ''){ $where['a.id_bsc'] = $id_bsc; }
        if($id_perspective != ''){ $where['a.id_perspective'] = $id_perspective; }
        if($id_so != ''){ $where['a.id_so'] = $id_so; }
        if($id_kpi_so != ''){ $where['a.id'] = $id_kpi_so; }

        //cek year dan month
        $year = @$_REQUEST['global_year'];
        $month = @$_REQUEST['global_month'];
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

        //filtering
        $search = [];
        if(isset($_REQUEST['filter'])){
            foreach ($_REQUEST['filter'] as $row) {

                //default parameter
                if(@$row['name'] != ''){

                    $val= $row['value']; $tipe = $row['tipe']; $search[] = $row['name']; 
                    $name = @$row['name'];
                    $name = $this->m_monev_kpi_so->select($row['name']); $name = str_replace(' AS '.$row['name'], ' ', $name );

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
        $table = "m_kpi_so AS a";
        $join  = NULL;

        //select 
        $select_field = [ 'id','code','is_active','created_date','created_by','updated_date','updated_by',
                            'id_so','id_perspective','id_bsc','id_periode','description','code_so',
                            'name_perspective','name_periode','name_bsc','name_so','name_kpi_so','name_pic_kpi_so','name_polarisasi',
                            'polarisasi','ukuran','frekuensi_pengukuran',
                            'arr_target','arr_target_from','arr_target_to'
                        ];
        $select = array_unique(array_merge($select_field, $search));
        $select = $this->m_monev_kpi_so->select($select);

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
            $btn_add_progress = '<button title="Add Progress" id="'.$id.'" class="btn btn-sm  btn-primary btn_add_progress"><i class="fa fa-plus"></i> Add Progress</button>';
            $action = $btn_add_progress;

            //isi table disini
            $isi['no']                  = $i;
            $isi['id']                  = $rows->id;
            $isi['code']                = $rows->code;
            $isi['is_active']           = ($rows->is_active == 't' ? 'Active' : 'Non Active');
            $isi['created_date']        = h_format_date($rows->created_date,'d F Y');
            $isi['created_by']          = $rows->created_by;
            $isi['updated_date']        = h_format_date($rows->updated_date,'d F Y');
            $isi['updated_by']          = $rows->updated_by;

            $isi['code_so']             = $rows->code_so;
            $isi['name_so']             = h_read_more($rows->name_so,30);
            $isi['name_kpi_so']         = h_read_more($rows->name_kpi_so,60);
            $isi['pic_kpi_so']          = str_replace(', ','<br>',$rows->name_pic_kpi_so );
            $isi['name_polarisasi']     = $rows->name_polarisasi;
            $isi['ukuran']              = $rows->ukuran;
            $isi['frekuensi_pengukuran']= $rows->frekuensi_pengukuran;

            //target month
            if($rows->polarisasi == '10'){
                if($rows->arr_target_from == ''){
                    for($a=1;$a<=5;$a++){
                        $isi['target_'.$a] = '0';
                    }
                }else{
                    $arr_target_from = explode(', ',$rows->arr_target_from);
                    $arr_target_to = explode(', ',$rows->arr_target_to);
                    $b=0; foreach($arr_target_from as $val){ $b++;
                        $isi['target_'.$b] = $val.' - '.@$arr_target_to[$b-1];
                    }
                }
            }else{
                if($rows->arr_target == ''){
                    for($a=1;$a<=5;$a++){
                        $isi['target_'.$a] = '0';
                    }
                }else{
                    $arr_target = explode(', ',$rows->arr_target);
                    $b=0; foreach($arr_target as $val){ $b++;
                        $isi['target_'.$b] = $val;
                    }
                }
            }

            $isi['id_so']               = h_read_more($rows->name_so,30);
            $isi['id_perspective']      = $rows->name_perspective;
            $isi['id_bsc']              = $rows->name_bsc;
            $isi['id_periode']          = $rows->name_periode;
            $isi['description']         = h_read_more($rows->description,20);

            $isi['action']              = '<div style="width:125px;'.($iTotalRecords=='1'?'height:50px;':'').'">'.$action.'</div>';

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


    public function load_add() {

        $this->load->model('app/m_monev_kpi_so','m_monev_kpi_so');

        $data['url'] = $this->url;
        
        //get data
        $id = $this->input->post('id');
        $view = @$this->input->post('view');
        $year = $this->input->post('year');
        $month = $this->input->post('month');
        
        $data['view'] = $view;
        $data['year']   = ($year == '' ? date('Y') : $year);
        $data['month']  = ($month == '' ? '' : $month);

        $select = ['id','code_so','name_so','code','name_kpi_so','name_pic_kpi_so','ukuran','frekuensi_pengukuran',
                    'arr_target','id_periode','polarisasi','start_date','end_date'
                ];
        $select = $this->m_monev_kpi_so->select($select);
        $arr = @$this->m_global->getDataAll('m_kpi_so AS a', null, ['id'=>$id], $select)[0];
        $data['data'] = $arr;
        //start_year
        $data['start_year'] = substr($arr->start_date,0,4);
        $data['end_year']   = substr($arr->end_date,0,4);

        //polarisasi
        $polarisasi = @$arr->polarisasi;
        $polarisasi_name = @$this->m_global->getDataAll('m_status', null, ['id'=>$polarisasi])[0]->name;
        $data['polarisasi_name'] = $polarisasi_name;

        $this->template->display_ajax($this->url.'/v_monev_kpi_so_add', $data);
    }
    

    public function load_edit_month() {

        $this->load->model('app/m_monev_kpi_so','m_monev_kpi_so');

        $data['url'] = $this->url;

        //param
        $id = @$this->input->post('id');
        $data['id'] = $id;
        
        //data month
        $where = ['id' => $id];
        $data['data'] = @$this->m_global->getDataAll('m_kpi_so_target_month', null, $where, '*')[0];
        
        //polarisasi
        $id_kpi_so = $data['data']->id_kpi_so;
        $year = $data['data']->year;
        $where = ['id' => $id_kpi_so];
        $polarisasi = @$this->m_global->getDataAll('m_kpi_so', null, $where, 'polarisasi')[0]->polarisasi;
        $data['polarisasi'] = $polarisasi;

        //target_year
        $where = ['id_kpi_so' => $id_kpi_so, 'year' => $year];
        $data['target_year'] = @$this->m_global->getDataAll('m_kpi_so_target_year', null, $where, 'target')[0]->target;
        if($polarisasi == '10'){
            $arr = @$this->m_global->getDataAll('m_kpi_so_target_year', null, $where, 'target_from, target_to')[0];
            $target_year = @$arr->target_from.' - '.@$arr->target_to;
        }else{
            $target_year = @$this->m_global->getDataAll('m_kpi_so_target_year', null, $where, 'target')[0]->target;
        }
        $data['target_year'] = $target_year;

        $this->template->display_ajax($this->url.'/v_monev_kpi_so_edit', $data);
    }


    public function load_table_month() {

        $this->load->model('app/m_monev_kpi_so','m_monev_kpi_so');

        $data['url'] = $this->url;

        //parameter
        $id = $this->input->post('id');
        $view = @$this->input->post('view');
        $year = $this->input->post('year');
        $month = $this->input->post('month');

        $data['id'] = $id;
        $data['view'] = $view;
        $data['year'] = $year;
        $data['month'] = $month;
        
        //get data
        $select = ['id','code_so','name_so','name_kpi_so','name_pic_kpi_so','ukuran','polarisasi','arr_target'];
        $select = $this->m_monev_kpi_so->select($select);
        $arr_kpi_so = @$this->m_global->getDataAll('m_kpi_so AS a', null, ['id'=>$id], $select)[0];
        $data['data'] = $arr_kpi_so;
        $polarisasi = $arr_kpi_so->polarisasi;

        //target year
        $where = ['id_kpi_so'=>$id, 'year'=>$year, 'is_active'=>'t'];
        $arr  = @$this->m_global->getDataAll('m_kpi_so_target_year', null, $where, 'id, target')[0];
        $data['target_year'] = $arr->target;
        $data['id_target_year'] = $arr->id;

        //target month
        $where = ['a.id_kpi_so'=>$id,'a.is_active'=>'t'];
        $order = ['a.year'=>'ASC', 'a.month'=>'ASC'];
        $select = ['id','year','month','target','target_from','target_to','pencapaian','realisasi','prognosa','prognosa_pencapaian',
                    'recommendations','quick_win','penyebab1','penyebab2','color_name','color_name2','status'];
        $select = $this->m_monev_kpi_so->select_target_month($select);
        $arr_month = @$this->m_global->getDataAll('m_kpi_so_target_month a', null, $where, $select, null, $order);
        $id_month =  $target_month = $status_month =  $pencapaian =  $realisasi =   $prognosa = $prognosa_pencapaian = $recommendations =  $quick_win = $penyebab1 = $penyebab2 = $color_name = [];
        if($arr_month != ''){
            foreach($arr_month as $row2){
                $id_month[$row2->year][$row2->month] = $row2->id;
                if($polarisasi == '10'){
                    $target_month[$row2->year][$row2->month]  = $row2->target_from.' - '.$row2->target_to;
                }else{
                    $target_month[$row2->year][$row2->month]  = $row2->target;
                }
                $pencapaian[$row2->year][$row2->month]       = $row2->pencapaian;
                $realisasi[$row2->year][$row2->month]        = $row2->realisasi;
                $prognosa[$row2->year][$row2->month]         = $row2->prognosa;
                $prognosa_pencapaian[$row2->year][$row2->month] = $row2->prognosa_pencapaian;
                $recommendations[$row2->year][$row2->month]  = $row2->recommendations;
                $quick_win[$row2->year][$row2->month]        = $row2->quick_win;
                $penyebab1[$row2->year][$row2->month]        = $row2->penyebab1;
                $penyebab2[$row2->year][$row2->month]        = $row2->penyebab2;
                $color_name[$row2->year][$row2->month]       = $row2->color_name;
                $color_name2[$row2->year][$row2->month]      = $row2->color_name2;
                $color_name2[$row2->year][$row2->month]      = $row2->color_name2;
                $status_month[$row2->year][$row2->month]     = $row2->status;
            }
        }
        $data['id_month']           = $id_month;
        $data['target_month']       = $target_month;
        $data['pencapaian']         = $pencapaian;
        $data['realisasi']          = $realisasi;
        $data['prognosa']           = $prognosa;
        $data['prognosa_pencapaian']= $prognosa_pencapaian;
        $data['recommendations']    = $recommendations;
        $data['quick_win']          = $quick_win;
        $data['penyebab1']          = $penyebab1;
        $data['penyebab2']          = $penyebab2;
        $data['color_name']         = $color_name;
        $data['color_name2']        = $color_name2;
        $data['status_month']       = $status_month;
        // echo '<pre>';print_r($data['target_month']);exit;

        $this->template->display_ajax($this->url.'/v_monev_kpi_so_table_month', $data);
    }


    public function save_edit_month() {

        //cek csrf token
        $ex_csrf_token = @$this->input->post('ex_csrf_token');
        $res = [];
        if (csrf_get_token() != $ex_csrf_token){
            $res['status']  = 2;
            $res['message'] = $this->csrf_message;
            echo json_encode($res);
        }else{

            // Set Rule Login Form
            $this->form_validation->set_rules('id', 'ID', 'trim|xss_clean|required');
            // $this->form_validation->set_rules('realisasi', 'Realisasi', 'trim|xss_clean|required');
            // $this->form_validation->set_rules('recommendations', 'Quick Win', 'trim|xss_clean|required');
            // $this->form_validation->set_rules('prognosa', 'Prognosa', 'trim|xss_clean|required');
            // $this->form_validation->set_rules('quick_win', 'Quick Win', 'trim|xss_clean|required');
            
            if ($this->form_validation->run($this)) {

                // echo '<pre>';print_r(@$this->input->post());exit;

                //realisasi
                $data = []; 
                $id = $this->input->post('id');
                $realisasi = h_number(@$this->input->post('realisasi'));
                $pencapaian = h_number(@$this->input->post('pencapaian'));
                if($realisasi != ''){
                    $data['realisasi']   = @$realisasi;
                    $data['pencapaian']  = @$pencapaian;
                    $where_color = "a.\"nilai_awal\"::NUMERIC <= '".floor($pencapaian)."'::NUMERIC AND a.\"nilai_akhir\"::NUMERIC >= '".floor($pencapaian)."'::NUMERIC";
                    $data['color'] = @$this->m_global->getDataAll('m_color AS a', NULL,$where_color,"id")[0]->id;
                }else{
                    $data['realisasi'] = NULL;
                    $data['pencapaian'] = NULL;
                    $data['color'] = NULL;
                }
                
                $data['recommendations']    = h_text_utf8(@$this->input->post('recommendations'));
                $data['penyebab1']          = h_text_utf8(@$this->input->post('penyebab1'));

                //prognosa
                $prognosa = h_number(@$this->input->post('prognosa'));
                $prognosa_pencapaian = h_number(@$this->input->post('prognosa_pencapaian'));
                if($prognosa != ''){
                    $data['prognosa'] = $prognosa;
                    $data['prognosa_pencapaian'] = $prognosa_pencapaian;
                    $where_color = "a.\"nilai_awal\"::NUMERIC <= '".floor($prognosa_pencapaian)."'::NUMERIC AND a.\"nilai_akhir\"::NUMERIC >= '".floor($prognosa_pencapaian)."'::NUMERIC";
                    $data['prognosa_color'] = @$this->m_global->getDataAll('m_color AS a', NULL,$where_color,"id")[0]->id;
                }else{
                    $data['prognosa'] = NULL;
                    $data['prognosa_pencapaian'] = NULL;
                    $data['prognosa_color'] = NULL;
                }
                $data['quick_win'] = h_text_utf8(@$this->input->post('quick_win'));
                $data['penyebab2'] = h_text_utf8(@$this->input->post('penyebab2'));

                //update data
                $data['updated_date']       = date("Y-m-d H:i:s");
                $data['updated_by']         = h_session('USERNAME');
                $result = $this->m_global->update('m_kpi_so_target_month', $data, ['id' => $id]);

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
            $res = $this->m_global->update('m_monev_kpi_so', $data, ['id' => $id]);
            $res1 = @$this->m_global->update('m_monev_kpi_so_target_month', $data, ['id_monev_kpi_so' => $id]);
            $res2 = @$this->m_global->update('m_monev_kpi_so_target_year', $data, ['id_monev_kpi_so' => $id]);
            if($val == 't'){
                $res['message'] = 'Active Success!';
            }else{
                $res['message'] = 'Delete Success!';
            }
            
            echo json_encode($res);
        }
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

    public function select_so()
    {
        if(isset($_REQUEST['q'])){
            $q = strtolower($_REQUEST['q']);
            $id_bsc = @$_REQUEST['id_bsc'];
            $id_perspective = @$_REQUEST['id_perspective'];
            $where = "is_active = 't' AND status_so = '3'";
            if(!empty($_REQUEST['q'])){
                $where .= " AND LOWER(name) LIKE '%".$q."%'";
            }
            if(!empty($id_bsc)){
                $where .= " AND id_bsc = '".$id_bsc."'";
            }
            if(!empty($id_perspective)){
                $where .= " AND id_perspective = '".$id_perspective."'";
            }

            //cek role PIC SO
            if(h_session('ROLE_ID') == '4'){ 
                $position_id = h_session('POSITION_ID');
                if($position_id != ''){
                    $where .= " AND ".$position_id."::text = ANY (string_to_array(a.pic_so,', ')::text[]) "; 
                }else{
                    $where .= " AND a.pic_so = '' "; 
                }
            }

            //cek role PIC KPI-SO
            if(h_session('ROLE_ID') == '8'){
                $nip = h_session('NIP');
                $where2 = " a.is_active = 't' ";
                $where2 .= " AND '".$nip."'::text = ANY (string_to_array(a.user_pic_kpi_so,', ')::text[]) "; 
                $select2 = "STRING_AGG(a.id_so::character varying, ',') AS arr_id_so";
                $arr_id_so = $this->m_global->getDataAll('m_kpi_so a', null, $where2, $select2)[0]->arr_id_so;
                if($arr_id_so !=  ''){
                    $where .= " AND a.id IN(".$arr_id_so.") "; 
                }
            }

            //cek role PIC KPI-SO Manager
            if(h_session('ROLE_ID') == '10'){
                $nip = h_session('NIP');
                $where2 = " a.is_active = 't' ";
                $where2 .= " AND '".$nip."'::text = ANY (string_to_array(a.user_pic_manager,', ')::text[]) "; 
                $select2 = "STRING_AGG(a.id_so::character varying, ',') AS arr_id_so";
                $arr_id_so = $this->m_global->getDataAll('m_kpi_so a', null, $where2, $select2)[0]->arr_id_so;
                if($arr_id_so !=  ''){
                    $where .= " AND a.id IN(".$arr_id_so.") "; 
                }
            }


            $arr = @$this->m_global->getDataAll('m_so AS a', NULL,$where,"a.id, a.code, a.name, a.pic_so", null, "a.code ASC");
            // echo $this->db->last_query();exit;    
            $data = [];
            for ($i=0; $i < count($arr); $i++) {
                $name = '<b>['.$arr[$i]->code.']</b> '.$arr[$i]->name;
                $data[$i] = ['id' => $arr[$i]->id,  'name' => $name,  'pic_so' => $arr[$i]->pic_so];
            }
            echo json_encode(['item' => $data]);
        }else{
            $id = $_REQUEST['id'];
            $where = ['id' => $id];
            $arr = @$this->m_global->getDataAll('m_so AS a', NULL,$where,"a.id, a.code, a.name, a.pic_so",NULL,NULL,0,10);
            $name = '<b>['.$arr[0]->code.']</b> '.$arr[0]->name;
            $data[] = [ 'id' => @$arr[0]->id,  'name' => @$name,  'pic_so' => @$arr[0]->pic_so ];
            echo json_encode($data);
        }
    }


    public function select_pic_kpi_so ()
    {
        if(isset($_REQUEST['q'])){
            $q          = $_REQUEST['q'];
            $pic_so     = $_REQUEST['pic_so'];
            $where['ATASAN_NIP'] = $pic_so;
            if($q != ''){ $where = ['NAMA LIKE' => '%'.$q.'%']; }
            $parent     = $this->m_global->getDataAll('DIRJAB_STO', NULL, $where, 'NIP, NAMA, NAME',NULL,NULL,0,10);
            $data       = [];
            for ($i=0; $i < count($parent); $i++) {
                $name = '<b>'.$parent[$i]->NAMA.'</b> ["'.$parent[$i]->NAME.'"]';
                $data[$i] = ['id' => $parent[$i]->NIP, 'name' => $name];
            }
            echo json_encode(['item' => $data]);
        }else{
            $id         = str_replace(",", "','",  $_REQUEST['id']);
            $where      = "NIP IN ('".$id."')";
            $parent     = $this->m_global->getDataAll('DIRJAB_STO', NULL, $where, 'NIP, NAMA, NAME',NULL,NULL,0,10);
            $data       = [];
            for ($i=0; $i < count($parent); $i++) {
                $name = '<b>'.$parent[$i]->NAMA.'</b> ["'.$parent[$i]->NAME.'"]';
                $data[$i] = ['id' => $parent[$i]->NIP, 'name' => $name];
            }
            echo json_encode($data);
        }
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

            //update data
            $id_kpi_so  = $this->input->post('id_kpi_so');
            $title      = $this->input->post('title');
            $year       = $this->input->post('year');
            $month      = $this->input->post('month');
            $val        = $this->input->post('val');
            $keterangan = @$this->input->post('keterangan');
            $status_new = $val;

            $data = [];
            $data['status']         = $val;
            $data['updated_date']   = date("Y-m-d H:i:s");
            $data['updated_by']     = h_session('USER_ID');
            if($status_new == '2'){
                $data['request_approval_by']    = h_session('USER_ID');
                $data['request_approval_date']  = date("Y-m-d H:i:s");
            }
            if($status_new == '3' || $status_new == '4'){
                $data['request_approval_keterangan'] = $keterangan;
            }
            $where = ['id_kpi_so' => $id_kpi_so, 'year' => $year, 'month' => $month, 'is_active' => 't'];
            $result = $this->m_global->update('m_kpi_so_target_month', $data, $where);
            
            //untuk bulanan tidak ada approval by, hanya statusnya saja
            $data = [];
            $data['status']         = $val;
            $data['updated_date']   = date("Y-m-d H:i:s");
            $data['updated_by']     = h_session('USER_ID');
            $where = ['id_kpi_so' => $id_kpi_so, 'year' => $year, 'is_active' => 't'];
            $result = $this->m_global->update('m_kpi_so_target_year', $data, $where);

            
            //============================== Kirim Notif Email ====================================

            //get data kpi_so
            $select = "name, code";
            $arr = @$this->m_global->getDataAll('m_kpi_so AS a', null, ['id' => $id_kpi_so], $select)[0];
            $kpi_so_name = $arr->name;
            $kpi_so_code = $arr->code;

            //cek status
            if($status_new == '1' || $status_new == '2'){

                //selec pic kpi_so dari master user ic kpi_so
                $arr_user = [];
                $where      = " a.is_active='t' AND a.role_id='8' ";
                $where      .= " AND ('".$id_kpi_so."' = ANY (string_to_array(a.id_kpi_so,', ')))";
                $arr        = @$this->m_global->getDataAll('m_user_kpi_so AS a', null, $where, 'a.nip');
                foreach($arr as $row){ $arr_user[$row->nip] = $row->nip;}

                //kirim ke user pic kpi_so
                $arr_nip    = join("','",$arr_user);
                $where      = "a.nip IN('".$arr_nip."') AND a.is_active='t'";
                $arr        = @$this->m_global->getDataAll('sys_user AS a', null, $where, 'a.nip, a.fullname, a.email, a.title, a.role_id');
                // echo '<pre>';print_r($arr);exit;
                
                foreach($arr as $row){
                    $data = [];
                    $data['nip']        = $row->nip;
                    $data['fullname']   = $row->fullname;
                    $data['email']      = $row->email;
                    $data['title']      = $row->title;
                    $data['status']     = $status_new;
                    $data['kpi_so_name']    = $kpi_so_name;
                    $data['kpi_so_code']    = $kpi_so_code;
                    $data['request_by']   = h_session('NAME');
                    $data['request_date'] = date("Y-m-d H:i:s");
                    
                    $token = h_insert_token('request_approval_monev_kpi_so',$row->nip, '30');
                    $pecah = explode(', ',$row->role_id);
                    if(in_array('5',$pecah)) { $role_id = '8';  }else{ $role_id = '10';}
                    $link = site_url().'login/redirect_page/request_approval_monev_kpi_so/'.$token.'/'.$row->nip.'/'.$role_id.'/'.$id_kpi_so.'/'.$year.'/'.$month;
                    $data['link'] = $link;

                    $to         = h_email_to($row->email);
                    $from       = 'noreply@indonesiapower.co.id';
                    $title      = "Request Approval Monitoring & Evaluation KPI-SO";
                    $subject    = "Request Approval Monitoring & Evaluation KPI-SO";
                    $data['subject'] = $subject;
                    
                    //untuk cek html
                    // $this->load->view($this->url.'/v_monev_kpi_so_email_request_approval', $data);

                    //kirim email html
                    $html = $this->load->view($this->url.'/v_monev_kpi_so_email_request_approval', $data, TRUE);
                    h_kirim_email_ip($from, $to, null, null, $title, $subject, $html);


                    //============================ notif inbox ====================================
                    if($status_new == '2'){
                        //insert to inbox
                        $data = [];
                        $data['element']     = "KPI-SO - ".$kpi_so_code;
                        $data['type_inbox']  = "KPI-SO";
                        $data['description'] = "Request Approval Monitoring & Evaluation KPI-SO, <br>".
                                                "Untuk KPI-SO: (".$kpi_so_code.") ".h_text_br($kpi_so_name,40)."<br>".
                                                "Bulan: ".h_month_name($month)." ".$year;
                        $data['param_id']       = $id_kpi_so;
                        $data['review_status']  = 18;
                        $data['request_by']     = h_session('USER_ID');
                        $data['request_date']   = date('Y-m-d H:i:s');
                        $data['nip']            = $row->nip;
                        $data['role_id']        = $role_id;
                        $data['redirect_page']  = $link;
                        $result = $this->m_global->insert('m_inbox', $data);
                    }
                    if($status_new == '1'){
                        //delete inbox
                        $data = [];
                        $data['element']        = "KPI-SO - ".$kpi_so_code;
                        $data['type_inbox']     = "KPI-SO";
                        $data['param_id']       = $id_kpi_so;
                        $data['review_status']  = 18;
                        $data['request_by']     = h_session('USER_ID');
                        $data['nip']            = $row->nip;
                        $data['role_id']        = $role_id;
                        $result = $this->m_global->delete('m_inbox', $data);
                    }
                    //=============================================================================
                }
            }

            if($status_new == '3' || $status_new == '4'){

                //kirim email
                $arr_user = [];
                $where  = "a.is_active='t' AND id_kpi_so='".$id_kpi_so."' AND month='".$month."' AND year='".$year."'";
                $request_from = @$this->m_global->getDataAll('m_kpi_so_target_month AS a', null, $where, 'a.request_approval_by')[0]->request_approval_by;
                if($request_from == ''){ $request_from = '1';}

                //kirim ke user pic ic
                $where      = "a.is_active='t' AND id='".$request_from."'";
                $arr        = @$this->m_global->getDataAll('sys_user AS a', null, $where, 'a.nip, a.fullname, a.email, a.title, a.role_id');
                foreach($arr as $row){

                    $data = [];
                    $data['nip']        = $row->nip;
                    $data['fullname']   = $row->fullname;
                    $data['email']      = $row->email;
                    $data['title']      = $row->title;
                    $data['status']     = $status_new;
                    $data['kpi_so_name']    = $kpi_so_name;
                    $data['kpi_so_code']    = $kpi_so_code;
                    $data['request_by']   = h_session('NAME');
                    $data['request_date'] = date("Y-m-d H:i:s");
                    $data['keterangan'] = $row->keterangan;

                    $token = h_insert_token('request_approval_monev_kpi_so',$row->nip, '30');
                    $pecah = explode(', ',$row->role_id);
                    if(in_array('5',$pecah)) { $role_id = '8';  }else{ $role_id = '10';}
                    $link = site_url().'login/redirect_page/request_approval_monev_kpi_so/'.$token.'/'.$row->nip.'/'.$role_id.'/'.$id_kpi_so.'/'.$year.'/'.$month;
                    $data['link'] = $link;

                    $to         = h_email_to($row->email);
                    $from       = 'noreply@indonesiapower.co.id';
                    $title      = "Request Approval KPI-SO";
                    $subject    = 'Request Approval KPI-SO';
                    $data['subject'] = $subject;
                    
                    //untuk cek html
                    // $this->load->view($this->url.'/v_monev_kpi_so_email_request_approval', $data);

                    //kirim email html
                    $html = $this->load->view($this->url.'/v_monev_kpi_so_email_request_approval', $data, TRUE);
                    try {
                        error_reporting(0);
                        h_kirim_email_ip($from, $to, null, null, $title, $subject, $html);
                    } catch (Exception $e) {
                        //kosong
                    }

                    //============================ notif inbox ====================================
                    //keterangan status
                    if($status_new == '3'){ 
                        $isi = 'Telah <span class="label btn_keterangan_approval" keterangan="'.$keterangan.'" style="cursor:pointer;color:#fff;background-color:#5cb85c;">DISETUJUI</span>';
                    }else{
                        $isi = 'Telah <span class="label label-danger btn_keterangan_approval" keterangan="'.$keterangan.'" style="cursor:pointer;">DITOLAK</span>';
                    }
                    //insert to inbox
                    $data = [];
                    $data['element']     = "KPI-SO - ".$kpi_so_code;
                    $data['type_inbox']  = "KPI-SO";
                    $data['description'] = "Request Approval Monitoring & Evaluation KPI-SO, <br>".
                                            "".$isi."<br>".
                                            "Untuk KPI-SO: (".$kpi_so_code.") ".h_text_br($kpi_so_name,40)."<br>".
                                            "Bulan: ".h_month_name($month)." ".$year;
                    $data['param_id']       = $id_kpi_so;
                    $data['review_status']  = 18;
                    $data['request_by']     = h_session('USER_ID');
                    $data['request_date']   = date('Y-m-d H:i:s');
                    $data['nip']            = $row->nip;
                    $data['role_id']        = $role_id;
                    $data['redirect_page']  = $link;
                    $result = $this->m_global->insert('m_inbox', $data);
                    //=============================================================================

                }

            }
            
            //result
            if($result['status'] == '1'){
                $res['status']  = ($result['status'] ? '1':'0');
                $res['message'] = $title.' Success!';
            }else{
                $res['status']  = ($result['status'] ? '1':'0');
                $res['message'] = $title.' Failed!';
            }
            echo json_encode($res);
        // }
    }


    public function download_excel(){

        //load model view
        $param = @$this->input->post('input_form');
        $arr = json_decode($param);
        // echo '<pre>';print_r($param);exit;
        
        //load model
        $this->load->model('app/m_monev_kpi_so','m_monev_kpi_so');

        //search default
        $where  = [];
        $whereE = " a.is_active = 't' ";

        //filter global
        $id_bsc = @$arr->global_id_bsc;
        $year = @$arr->global_year;
        $month = @$arr->global_month;
       
        //data kpi-so
        $table = "m_kpi_so AS a";
        $join  = NULL;
        $order = "a.id_perspective ASC, code_so ASC, code ASC";
        $select = ['id', 'name_kpi_so', 'code_kpi_so', 'id_so','name_so','code_so','polarisasi'];
        $select = $this->m_monev_kpi_so->select_download_excel($select);
        if($id_bsc != ''){ $where['a.id_bsc'] = $id_bsc; }
        $arr_kpi_so = @$this->m_global->getDataAll($table, $join, $where, $select, $whereE, $order);
        // echo '<pre>';print_r($arr_kpi_so);exit;

        //Target month
        $select = 'a.id, a.polarisasi, z.month, z.year, z.realisasi, z.prognosa, z.penyebab1 , z.penyebab2, z.recommendations, z.quick_win';
        $join  = [  ['table' => 'm_kpi_so_target_month z', 'on' => 'a.id = z.id_kpi_so', 'join' => 'LEFT'],
                    ['table' => 'm_so x', 'on' => 'x.id = a.id_so', 'join' => 'LEFT']  ];
        if($id_bsc != ''){ $where['x.id_bsc'] = $id_bsc; }
        if($year != ''){ $where['z.year'] = $year; }
        if($month != ''){ $where['z.month'] = $month; }
        $arr = @$this->m_global->getDataAll('m_kpi_so AS a', $join, $where, $select);
        $arr_month = [];
        foreach($arr as $row){
            //target month
            $arr_month[$row->id][$row->year][$row->month]['realisasi'] = $row->realisasi;
            $arr_month[$row->id][$row->year][$row->month]['penyebab1'] = $row->penyebab1;
            $arr_month[$row->id][$row->year][$row->month]['recommendations'] = $row->recommendations;
            $arr_month[$row->id][$row->year][$row->month]['prognosa'] = $row->prognosa;
            $arr_month[$row->id][$row->year][$row->month]['penyebab2'] = $row->penyebab2;
            $arr_month[$row->id][$row->year][$row->month]['quick_win'] = $row->quick_win;
        }
        // echo '<pre>';print_r($arr_month);exit;

        //cek filter bulan
        if($month == ''){
            $template_name  = 'pencapaian_kpi_so_year.xls';
            $title          = 'Pencapaian KPI-SO '.$year;
            $filename       = 'Pencapaian KPI-SO'.$year.'.xlsx';
            $start_m = 1;
            $end_m = 12;
        }else{
            $template_name  = 'pencapaian_kpi_so_month.xls';
            $title          = 'Pencapaian KPI-SO '.h_month_name($month);
            $filename       = 'Pencapaian KPI-SO '.h_month_name($month).'.xlsx';
            $start_m = $month;
            $end_m = $month;
        }

        //param excel
        $title          = 'Pencapaian KPI-SO';
        $filename       = 'Pencapaian KPI-SO.xlsx';

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
        $bsc = $bsc_name.'('.$id_bsc.')';

        //sheet year
        $i = -1;
        for($m = $start_m; $m <= $end_m; $m++){
            $i++;
            
            //sheet name
            $this->excel->setActiveSheetIndex($i)->setTitle("$m");

            //bsc
            $this->excel->setActiveSheetIndex($i)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString('B')-1, 2, $bsc);
            $this->excel->setActiveSheetIndex($i)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString('D')-1, 2, $year);
            
            //masukan data
            $baris = 5;
            foreach($arr_kpi_so as $row){
                //data excel
                $name_so = '('.$row->code_so.')'.$row->name_so;
                $realisasi      = @$arr_month[$row->id][$year][$m]['realisasi'];
                $prognosa       = @$arr_month[$row->id][$year][$m]['prognosa'];
                $penyebab1      = @$arr_month[$row->id][$year][$m]['penyebab1'];
                $penyebab2      = @$arr_month[$row->id][$year][$m]['penyebab2'];
                $recommendations= @$arr_month[$row->id][$year][$m]['recomm$recommendations'];
                $quick_win      = @$arr_month[$row->id][$year][$m]['recomm$quick_win'];
                $this->excel->setActiveSheetIndex($i)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString('A')-1,$baris, $row->id);
                $this->excel->setActiveSheetIndex($i)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString('B')-1,$baris, $name_so);
                $this->excel->setActiveSheetIndex($i)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString('C')-1,$baris, $row->code_kpi_so);
                $this->excel->setActiveSheetIndex($i)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString('D')-1,$baris, $row->name_kpi_so);
                $this->excel->setActiveSheetIndex($i)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString('E')-1,$baris, $realisasi);
                $this->excel->setActiveSheetIndex($i)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString('F')-1,$baris, $prognosa);
                $this->excel->setActiveSheetIndex($i)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString('G')-1,$baris, $penyebab1);
                $this->excel->setActiveSheetIndex($i)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString('H')-1,$baris, $recommendations); 
                $this->excel->setActiveSheetIndex($i)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString('I')-1,$baris, $penyebab2); 
                $this->excel->setActiveSheetIndex($i)->setCellValueByColumnAndRow(PHPExcel_Cell::columnIndexFromString('J')-1,$baris, $quick_win); 
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
        // echo '<pre>';print_r($_FILES);exit;
        $fileName = 'pencapaian_kpi_so_'.time().$_FILES['file']['name'];
        $folder = './public/files/temp/';
        $config['upload_path']   = $folder; //buat folder dengan nama assets di root folder
        $config['file_name']     = $fileName;
        $config['allowed_types'] = '*';
        $config['allowed_types'] = 'xls|xlsx|csv';
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
                    
                    // $pecah = explode('-',$val_sheet);
                    // $m = @$pecah[1];
                    $m = $val_sheet;

                    //membaca data sheet 
                    $sheet              = $objPHPExcel->getSheet($key); 
                    $highestRow         = $sheet->getHighestRow(); 
                    $highestColumn      = $sheet->getHighestColumn();
                    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

                    //bsc
                    $bsc = $sheet->rangeToArray('B2', NULL, TRUE, FALSE, FALSE)[0][0];
                    $pecah = explode('(',$bsc);
                    $id_bsc = substr($pecah[1],0,-1);

                    //year
                    $year = $sheet->rangeToArray('D2', NULL, TRUE, FALSE, FALSE)[0][0];

                    //target month
                    $select = 'z.id_kpi_so, z.month, z.year, z.target, a.polarisasi';
                    $join  = [  ['table' => 'm_kpi_so_target_month z', 'on' => 'a.id = z.id_kpi_so', 'join' => 'LEFT'] ];
                    $where = ['a.id_bsc'=>$id_bsc, 'z.year'=>$year, 'z.month'=>$m];
                    $arr = @$this->m_global->getDataAll('m_kpi_so AS a', $join, $where, $select);
                    // echo '<pre>';print_r($arr);exit;

                    $target_month = [];
                    foreach($arr as $row){
                        //target month
                        $target_month[$row->id_kpi_so][$row->year][$row->month]['polarisasi'] = $row->polarisasi;
                        $target_month[$row->id_kpi_so][$row->year][$row->month]['target'] = $row->target;
                    }


                    //tampung data perbaris
                    for ($row = 5; $row <= $highestRow; $row++){ 
                        
                        //array excel
                        $arr_data =  $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE, FALSE)[0];
                        // echo '<pre>';print_r($arr_data);exit;

                        //where
                        $id_kpi_so = $arr_data[0];
                        $where = ['id_kpi_so'=>$id_kpi_so, 'year'=>$year, 'month'=>$m];

                        //realisasi
                        $realisasi      = $arr_data[4];
                        $target1        = $target_month[$id_kpi_so][$year][$m]['target'];
                        $polarisasi1    = $target_month[$id_kpi_so][$year][$m]['polarisasi'];
                        $pencapaian1    = h_pencapaian($realisasi, $target1, $polarisasi1);
                        $color1         = h_color_pencapaian($pencapaian1);
                        $penyebab1      = $arr_data[6];
                        $recommendations = $arr_data[7];
                        $data['realisasi']          = str_replace(',','',$realisasi);
                        $data['pencapaian']         = $pencapaian1;
                        $data['color']              = $color1;
                        $data['penyebab1']          = $penyebab1;
                        $data['recommendations']    = $recommendations;

                        //prognosa
                        $prognosa       = $arr_data[5];
                        $target2        = $target_month[$id_kpi_so][$year][$m]['target'];
                        $polarisasi2    = $target_month[$id_kpi_so][$year][$m]['polarisasi'];
                        $pencapaian2    = h_pencapaian($prognosa, $target2, $polarisasi2);
                        $color2         = h_color_pencapaian($pencapaian2);
                        $penyebab2      = $arr_data[8];
                        $quick_win      = $arr_data[8];
                        $data['prognosa']           = str_replace(',','',$prognosa);
                        $data['prognosa_pencapaian']= $pencapaian1;
                        $data['prognosa_color']     = $color2;
                        $data['penyebab2']          = $penyebab2;
                        $data['quick_win']          = $quick_win;
                        
                        //data tambahan
                        $data['updated_date']       = date("Y-m-d H:i:s");
                        $data['updated_by']         = h_session('USERNAME');

                        //update table kpi_so_target_month
                        $result = $this->m_global->update('m_kpi_so_target_month', $data, $where);
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
