<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Review_sr extends MX_Controller {
    
    private $prefix         = 'review_sr';
    private $table_db       = 'm_strategic_result';
    private $title          = 'Strategic Result - Monitoring & Evaluation';
    private $url            = 'app/review_sr';

    function __construct() {
        parent::__construct();
        $this->middleware('guest', 'forbidden');
    }

    public function index($id_sr='')
    {
        csrf_init();
        $data['title']      = $this->title;
        $data['url']        = $this->url;
        $data['breadcrumb'] = [$this->title => $this->url];

        //cek id sr
        if($id_sr != ''){
            $arr = @$this->m_global->getDataAll('m_strategic_result', null,  ['id'=>$id_sr])[0];
            $data['id_periode']         = $arr->id_periode;
            $data['id_bsc']             = $arr->id_bsc;
            $data['id_strategic_theme'] = $arr->id_strategic_theme;
            $data['id_sr']              = $arr->id;
        }else{
            $year_now = date('Y');
            $where = " start_year <= ".$year_now." AND end_year >= ".$year_now." AND is_active = 't' ";
            $id_periode = @$this->m_global->getDataAll('m_periode', null, $where, 'id', null, "start_year ASC")[0]->id;
            $data['id_periode']         = $id_periode;
            $data['id_bsc']             = 1;
            $data['id_strategic_theme'] = '';
            $data['id_sr'] = '';
        }

        //periode
        $data['periode'] = $this->m_global->getDataAll('m_periode', null, ['is_active'=>'t'], '*', null, "start_year ASC");
        //bsc
        $data['bsc'] = $this->m_global->getDataAll('m_bsc', null,  ['is_active'=>'t'], '*', null, "name ASC");
        //strategic_theme
        $data['strategic_theme'] = $this->m_global->getDataAll('m_strategic_theme', null,  ['is_active'=>'t'], '*', null, "name ASC");
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
                    $name = $this->m_review_sr->select($row['name']); $name = str_replace(' AS '.$row['name'], ' ', $name );

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
            $order = ['code','DESC'];
        }
        // echo '<pre>';print_r($order);exit;

        
        //table dan join
        $table = "m_strategic_result AS a";
        $join  = NULL;

        //select 
        //select 
        $select = [ 'id','code_review_sr','name_review_sr','name_strategic_theme','name_periode','name_bsc',
                        'pic_sr','name_pic_sr','status_sr','name_status_sr','description',
                        'is_active','created_date','created_by','updated_date','updated_by'
                    ];
        $select = array_unique(array_merge($select, $search));
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
        // echo '<pre>';print_r($result);exit;

        $param=[];
        foreach ($result as $rows) {
            $id = @$rows->id;

            //button
            $btn_add_progress = '<button title="Add Progress" id="'.$id.'" class="btn btn-sm  btn-primary btn_add_progress"><i class="fa fa-plus"></i> Add Progress</button>';

            //isi table disini
            $isi['no']                  = $i;
            $isi['id']                  = $rows->id;
            $isi['is_active']           = ($rows->is_active == 't' ? 'Active' : 'Non Active');
            $isi['created_date']        = h_format_date($rows->created_date,'d F Y');
            $isi['created_by']          = $rows->created_by;
            $isi['updated_date']        = h_format_date($rows->updated_date,'d F Y');
            $isi['updated_by']          = $rows->updated_by;
            $isi['action']              = @$btn_add_progress;

            $isi['name']                = h_read_more($rows->name,20);
            $isi['code']                = $rows->code;
            $isi['description']         = h_read_more($rows->description,20);

            $isi['indikator']           = h_read_more($rows->indikator,20);

            $name_polarisasi = ''; 
            if(@$rows->polarisasi == '10' ){ 
              $name_polarisasi = '<img src="'.img_url('arrow/right.png').'" width="30em;"> <img src="'.img_url('arrow/left.png').'" width="30em;" style="margin-left:-1.5em;">'; 
            }elseif(@$rows->polarisasi == '8'){ 
              $name_polarisasi = '<img src="'.img_url('arrow/up.png').'" width="20em;">'; 
            }elseif(@$rows->polarisasi == '9'){ 
              $name_polarisasi = '<img src="'.img_url('arrow/down.png').'" width="20em;">'; 
            }

            $isi['polarisasi']          = $name_polarisasi;
            $isi['ukuran']              = $rows->ukuran;
            $isi['target']              = $rows->target;
            $isi['pic_sr']              = $rows->name_pic_sr;

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
        $this->load->model('app/m_review_sr','m_review_sr');

        $data['url'] = $this->url;
        
        //get data
        $id = $this->input->post('id');
        $select = ['id','name','code_so','name_so','code','name_sr','name_pic_sr','ukuran','target',
                    'arr_target','id_periode','name_periode','polarisasi','name_strategic_theme',
                    'start_year','end_year'
                ];
        $select = $this->m_review_sr->select($select);
        $arr = @$this->m_global->getDataAll('m_strategic_result AS a', null, ['id'=>$id], $select)[0];
        $data['data'] = $arr;

        //start_year
        $data['start_year'] = $arr->start_year;
        $data['end_year']   = $arr->end_year;

        //polarisasi
        $polarisasi = @$data['data']->polarisasi;
        $polarisasi_name = @$this->m_global->getDataAll('m_status', null, ['id'=>$polarisasi])[0]->name;
        $data['polarisasi_name'] = $polarisasi_name;

        $this->template->display_ajax($this->url.'/v_review_sr_add', $data);

    }

    public function load_table_month() {

        $this->load->model('app/m_review_sr','m_review_sr');

        $data['url'] = $this->url;

        //parameter
        $id = $this->input->post('id');
        $year = $this->input->post('year');

        $data['id'] = $id;
        $data['year'] = $year;
        
        //get data
        $select = ['id','code','name_strategic_theme','name_pic_sr','ukuran','polarisasi','target'];
        $select = $this->m_review_sr->select($select);
        $arr_sr = @$this->m_global->getDataAll('m_strategic_result AS a', null, ['id'=>$id], $select)[0];
        $data['data'] = $arr_sr;
        $polarisasi = $arr_sr->polarisasi;

        //target year
        $where = ['id_sr'=>$id, 'year'=>$year, 'is_active'=>'t'];
        $select = ['id','target','status'];
        $select = $this->m_review_sr->select($select);
        $arr = @$this->m_global->getDataAll('m_sr_target_year AS a', null, $where, $select)[0];
        $data['target_year'] = @$arr->target;
        $data['status_year'] = @$arr->status;
        $data['id_target_year'] = @$arr->id;

        //target month
        $where = ['a.id_sr'=>$id,'a.is_active'=>'t'];
        $order = ['a.year'=>'ASC', 'a.month'=>'ASC'];
        $select = ['id','year','month','target','target_from','target_to','pencapaian','realisasi',
                    'recommendations','quick_win','keterangan','color_name'];
        $select = $this->m_review_sr->select_target_month($select);
        $arr_month = @$this->m_global->getDataAll('m_sr_target_month a', null, $where, $select, null, $order);
        $id_month =  $target_month =  $pencapaian =  $realisasi =  $recommendations =  $keterangan = $color_name = [];
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
                $recommendations[$row2->year][$row2->month]  = $row2->recommendations;
                $keterangan[$row2->year][$row2->month]        = $row2->keterangan;
                $color_name[$row2->year][$row2->month]       = $row2->color_name;
            }
        }
        $data['id_month']           = $id_month;
        $data['target_month']       = $target_month;
        $data['pencapaian']         = $pencapaian;
        $data['realisasi']          = $realisasi;
        $data['recommendations']    = $recommendations;
        $data['keterangan']         = $keterangan;
        $data['color_name']         = $color_name;
        // echo '<pre>';print_r($data['target_month']);exit;

        $this->template->display_ajax($this->url.'/v_review_sr_table_month', $data);
    }


    public function load_edit_month() {

        $this->load->model('app/m_review_sr','m_review_sr');

        $data['url'] = $this->url;

        //param
        $id = @$this->input->post('id');
        $data['id'] = $id;
        
        //data month
        $where = ['id' => $id];
        $data['data'] = @$this->m_global->getDataAll('m_sr_target_month', null, $where, '*')[0];
        
        //polarisasi
        $id_sr = $data['data']->id_sr;
        $year = $data['data']->year;
        $where = ['id' => $id_sr];
        $polarisasi = @$this->m_global->getDataAll('m_strategic_result', null, $where, 'polarisasi')[0]->polarisasi;
        $data['polarisasi'] = $polarisasi;

        //target_year
        $where = ['id_sr' => $id_sr, 'year' => $year];
        $data['target_year'] = @$this->m_global->getDataAll('m_sr_target_year', null, $where, 'target')[0]->target;
        if($polarisasi == '10'){
            $arr = @$this->m_global->getDataAll('m_sr_target_year', null, $where, 'target_from, target_to')[0];
            $target_year = @$arr->target_from.' - '.@$arr->target_to;
        }else{
            $target_year = @$this->m_global->getDataAll('m_sr_target_year', null, $where, 'target')[0]->target;
        }
        $data['target_year'] = $target_year;

        $this->template->display_ajax($this->url.'/v_review_sr_edit', $data);
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
            $id_sr      = $this->input->post('id_sr');
            $year       = $this->input->post('year');
            $val        = $this->input->post('val');
            $data['status']         = $val;
            $data['updated_date']   = date("Y-m-d H:i:s");
            $data['updated_by']     = h_session('USERNAME');
            $result = $this->m_global->update('m_sr_target_year', $data, ['id_sr' => $id_sr, 'year' => $year]);
            $result = $this->m_global->update('m_sr_target_month', $data, ['id_sr' => $id_sr, 'year' => $year]);
            //result
            $res['status']  = ($result['status'] ? '1':'0');
            if($val == 't'){
                $res['message'] = 'Active Success!';
            }else{
                $res['message'] = 'Delete Success!';
            }
            echo json_encode($res);
        }
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
                $data['keterangan']          = h_text_utf8(@$this->input->post('keterangan'));

                //update data
                $data['updated_date']       = date("Y-m-d H:i:s");
                $data['updated_by']         = h_session('USERNAME');
                $result = $this->m_global->update('m_sr_target_month', $data, ['id' => $id]);

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


}
