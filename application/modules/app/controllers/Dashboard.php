<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {
    
    private $prefix         = 'dashboard';
    private $table_db       = '';
    private $title          = 'Dashboard';
    private $folder         = 'app';
    private $url            = 'app/dashboard';

    function __construct() {
        parent::__construct();
        $this->middleware('guest', 'forbidden');
    }

    public function index()
    {
        $data['url']        = $this->url;
        $data['breadcrumb'] = ["Home" => $this->url];

        
        //bsc
        $data['bsc'] = $this->m_global->getDataAll('m_bsc', null,  ['is_active'=>'t'], '*', null, "name ASC");
        //periode
        $arr = $this->m_global->getDataAll('m_periode', null, ['is_active'=>'t'], '*', null, "start_year ASC");
        $data['periode'] = $arr;
        // $this->load_table_view(TRUE);
        $data['html_map_view'] = $this->load_map_view(TRUE);
        // $data['html_table_view'] = $this->load_table_view(TRUE);
        // $data['html_graphical_analysis'] = $this->load_graphical_analysis(TRUE);

        // $this->template->display("app/dashboard/v_dashboard", $data);
        // $this->template->display("app/dashboard/v_dashboard_2", $data);
        // $this->template->display("app/dashboard/v_dashboard_3", $data);
        $this->template->display("app/dashboard/v_dashboard", $data);
    }

    public function load_map_view($html=FALSE)
    {
        $data['url'] = $this->url;

        //param
        $param = @$this->input->post();
        // echo '<pre>';print_r($param);exit;
        
        //load model
        $this->load->model('app/m_dashboard','m_dashboard');
        

        //search default
        $where  = [];
        $whereE = " is_active = 't' AND a.status_kpi_so = '3'";

        //default filter year month
        if(h_triwulan_now() == '1'){
            $year_now = date('Y')-1;
            $month_now = 12;
        }else{
            $year_now = date('Y');
            $month_now = (h_triwulan_now()-1) * 3;
        }

        //filter global
        $year = (@$param['global_year'] == '' ? $year_now : @$param['global_year']);
        $month = (@$param['global_month'] == '' ? $month_now : @$param['global_month']);
        $id_bsc = (@$param['global_id_bsc'] == '' ? '1' : @$param['global_id_bsc']);
        $id_periode = (@$param['global_id_periode'] == '' ? '1' : @$param['global_id_periode']);

        //Ambil datanya month
        $select = 'a.id_so, z.month, z.color AS color_id';
        $join  = [  ['table' => 'm_kpi_so_target_month z', 'on' => 'a.id = z.id_kpi_so AND z.status=3', 'join' => 'LEFT'],
                    ['table' => 'm_so x', 'on' => 'x.id = a.id_so', 'join' => 'LEFT']
                ];
        if($id_bsc != ''){ $where['x.id_bsc'] = $id_bsc; }
        if($year != ''){ $where['z.year'] = $year; }
        if($month != ''){ $where['z.month'] = $month; }
        $where['a.status_kpi_so'] = 3;
        $arr = @$this->m_global->getDataAll('m_kpi_so AS a', $join, $where, $select);
        // echo '<pre>';print_r($arr);exit;
        // echo $this->db->last_query();exit;
        $arr_month = $arr_so_color = [];
        foreach($arr as $row){
            $arr_so_color[$row->id_so][$row->month][] = $row->color_id;
        }
        // echo '<pre>';print_r($arr_so_color);exit;

        //so warna
        $so_color = [];
        foreach($arr_so_color as $so=>$val){
            foreach($val as $m=>$color){
                $jum_data = 0;
                $jum_color = 0;
                // echo '<pre>';print_r($color);
                foreach($color as $val){
                    if($val != ''){
                        $jum_data++;
                    }
                    if($val == '3'){
                        $jum_color++; 
                    }
                }
                //cek jumlah data dan color
                if($jum_data == 0 && $jum_color == 0){
                    $nilai_so = '';
                }else{
                    $nilai_so = h_pembagian($jum_color,$jum_data)*100;
                }
                // echo '<pre>';print_r($so.'/'.$m.'/'.$jum_color.'/'.$jum_data.'/'.$nilai_so);

                //warna so
                if($nilai_so == '' && $nilai_so != '0'){ $warna = 'N'; }
                elseif($nilai_so >= 0 && $nilai_so <=33){ $warna = 'R'; }
                elseif($nilai_so > 33 && $nilai_so < 100){ $warna = 'Y'; }
                elseif($nilai_so >= 100){ $warna = 'G'; }
                else{ $warna = ''; }

                $so_color[$so][$m] = $warna;
            }
        }
        // exit;
        // echo '<pre>';print_r($so_color);exit;
        
        //tampilkan so jika, tahunan atau month
        foreach($so_color as $so=>$val){
            if(@$month != ''){
                $so_color_new[$so] = @$so_color[$so][$month];
            }else{
                // if($year == date('Y')){
                //     $active = date('m')-1;
                //     $so_color_new[$so] = @$so_color[$so][$active];
                // }else{
                    $so_color_new[$so] = @$so_color[$so][12];
                // }
            }
        }

        $data['so_color'] = json_encode(@$so_color_new,TRUE);
        // echo '<pre>';print_r($so_color_new);exit;

        $data['year']       = @$year;
        $data['month']      = @$month;
        $data['id_bsc']     = @$id_bsc;
        $data['id_periode'] = @$id_periode;

        //strategic theme
        $data['strategic_theme'] = $this->m_global->getDataAll('m_strategic_theme', null,  ['is_active'=>'t'], '*', null, ['order','ASC']);
        $total= count($data['strategic_theme']);
        if($total > 4){ $total = 4;}
        if($total <= 2){ $total = 3;}

        if($html){
            return $this->template->display_ajax("app/dashboard/v_map_view_".$total, $data, null, null, TRUE);
        }else{
            $this->template->display_ajax("app/dashboard/v_map_view_".$total, $data);
        }
    }

    public function load_table_view($html=FALSE)
    {
        //url
        $data['url']  = $this->url;

        //param
        $param = @$this->input->post();
        // echo '<pre>';print_r($param);exit;
        
        //load model
        $this->load->model('app/m_dashboard','m_dashboard');

         //default filter year month
         if(h_triwulan_now() == '1'){
            $year_now = date('Y')-1;
            $month_now = 12;
        }else{
            $year_now = date('Y');
            $month_now = (h_triwulan_now()-1) * 3;
        }

        //filter global
        $year = (@$param['global_year'] == '' ? $year_now : @$param['global_year']);
        $month = (@$param['global_month'] == '' ? $month_now : @$param['global_month']);

        //filter global
        $id_periode = (@$param['global_id_periode'] == '' ? '1' : @$param['global_id_periode']);
        $id_bsc = (@$param['global_id_bsc'] == '' ? '1' : @$param['global_id_bsc']);

        //where
        $whereE = " is_active = 't' AND a.status_kpi_so = '3' ";
        $id_bsc = (@$param['global_id_bsc'] == '' ? '1' : @$param['global_id_bsc']);
        if($id_bsc != ''){ $whereE .= " AND a.id_bsc = $id_bsc"; }
        if($month != ''){ 
            $month_pad = str_pad($month,2,'0',STR_PAD_LEFT);
            $date_start = $year.'-'.$month_pad.'-01';
            $date_end = $year.'-'.$month_pad.'-01';
        }else{
            $date_start = $year.'-01-01';
            $date_end = $year.'-12-01';
        }
        $whereE .= " AND ((a.\"start_date\" >= '".$date_start."' AND a.\"start_date\" <= '".$date_end."') ";
        $whereE .= " OR (a.\"end_date\" >= '".$date_start."' AND a.\"end_date\" <= '".$date_end."') ";
        $whereE .= " OR (a.\"start_date\" <= '".$date_start."' AND a.\"end_date\" >= '".$date_end."')) ";

        //table dan join
        $table = "m_kpi_so AS a";
        $join  = NULL;
        $order = "a.id_perspective ASC, code_so ASC, code ASC";

        //Ambil datanya kpi-so
        $select = ['id','name_kpi_so','code_kpi_so',
                    'polarisasi','pic_kpi_so','ukuran','frekuensi_pengukuran',
                    'id_so', 
                    'name_pic_kpi_so','name_perspective','name_bsc','name_so','code_so','name_polarisasi'
                 ];
        $select = $this->m_dashboard->select($select, $year);
        $result = $this->m_global->getDataAll($table, $join, $whereE, $select, null, $order);
        // echo $this->db->last_query();exit;
        // echo '<pre>';print_r($result);exit;
        $data ['data'] = $result;

        //target year
        $where = " is_active = 't'  AND status=3 AND year='".$year."'"; 
        $arr = @$this->m_global->getDataAll('m_kpi_so_target_year', null, $where, 'id_kpi_so, year, target, target_from, target_to');
        $arr_target = $arr_target_from = $arr_target_to = [];
        foreach($arr as $row){
            $arr_target[$row->id_kpi_so][$row->year] = $row->target;
            $arr_target_from[$row->id_kpi_so][$row->year] = $row->target_from;
            $arr_target_to[$row->id_kpi_so][$row->year] = $row->target_to;
        }
        $data['arr_target'] = $arr_target;
        $data['arr_target_from'] = $arr_target_from;
        $data['arr_target_to'] = $arr_target_to;


        //Ambil datanya month
        $select = 'a.id, a.polarisasi, a.id_so,
                    z.month, z.target, z.target_from, z.target_to, 
                    z.realisasi, z.pencapaian, z.prognosa, z.prognosa_pencapaian, z.color AS color_id,
                    y.color, w.color as prognosa_color';
        $join  = [  ['table' => 'm_kpi_so_target_month z', 'on' => 'a.id = z.id_kpi_so AND z.status = 3', 'join' => 'LEFT'],
                    ['table' => 'm_color y', 'on' => 'y.id = z.color', 'join' => 'LEFT'],
                    ['table' => 'm_color w', 'on' => 'w.id = z.prognosa_color', 'join' => 'LEFT'],
                    ['table' => 'm_so x', 'on' => 'x.id = a.id_so', 'join' => 'LEFT'],
                ];
        $where = [];
        if($id_bsc != ''){ $where['x.id_bsc'] = $id_bsc; }
        if($year != ''){ $where['z.year'] = $year; }
        if($month != ''){ $where['z.month'] = $month; }
        $arr = @$this->m_global->getDataAll('m_kpi_so AS a', $join, $where, $select);
        // echo $this->db->last_query();exit;
        // echo '<pre>';print_r($arr);exit;
        $arr_month = $arr_so_color = [];
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
            $arr_month[$row->id][$row->month]['prognosa_pencapaian'] = $row->prognosa_pencapaian;
            $arr_month[$row->id][$row->month]['prognosa_color']      = $row->prognosa_color;

            //so warna
            $arr_so_color[$row->id_so][$row->month][] = $row->color_id;

        }
        $data['arr_month'] = $arr_month;
        // echo '<pre>';print_r($data['month']);exit;
        // echo '<pre>';print_r($arr_so_color);exit;


        //so warna
        $so_color = [];
        foreach($arr_so_color as $so=>$val){
            foreach($val as $m=>$color){
                $jum_data = 0;
                $jum_color = 0;
                foreach($color as $val){
                    if($val != ''){
                        $jum_data++; 
                    }
                    if($val == '3'){
                        $jum_color++; 
                    }
                }
                //cek jumlah data dan color
                if($jum_data == 0 && $jum_color == 0){
                    $nilai_so = '';
                }else{
                    $nilai_so = h_pembagian($jum_color,$jum_data)*100;
                }
                // echo '<pre>';print_r($so.'/'.$m.'/'.$jum_color.'/'.$jum_data.'/'.$nilai_so);
                
                //warna so
                if($nilai_so == '' && $nilai_so != '0'){ $warna = 'background:lightgrey;color:black;'; }
                elseif($nilai_so >= 0 && $nilai_so <=33 ){ $warna = 'background:red;color:white;'; }
                elseif($nilai_so > 33 && $nilai_so < 100){ $warna = 'background:yellow;color:black;'; }
                elseif($nilai_so >= 100 ){ $warna = 'background:green;color:white;'; }
                else{ $warna = ''; }
                $so_color[$so][$m] = $warna;
            }
        }

        //tampilkan so jika, tahunan atau month
        foreach($so_color as $so=>$val){
            if(@$month != ''){
                $so_color_new[$so] = @$so_color[$so][$month];
            }else{
                // if($year == date('Y')){
                //     $active = date('m')-1;
                //     $so_color_new[$so] = @$so_color[$so][$active];
                // }else{
                    $so_color_new[$so] = @$so_color[$so][12];
                // }
            }
        }
        $data['so_color'] = @$so_color_new;
        // echo '<pre>';print_r($so_color_new);exit;

        //variabel tambahan
        $bsc = @$this->m_global->getDataAll('m_bsc', null, ['id'=> $global_id_bsc], 'name')[0]->name;
        
        $data['bsc'] = @$bsc;
        $data['year'] = @$year;
        $data['month'] = @$month;

        if($html){
            return $this->template->display_ajax("app/dashboard/v_table_view", $data, null, null, TRUE);
        }else{
            $this->template->display_ajax("app/dashboard/v_table_view", $data);
        }
    }

    public function load_graphical_analysis($html=FALSE)
    {
        //url
        $data['url']  = $this->url;

        //param
        $param = @$this->input->post();
        // echo '<pre>';print_r($param);exit;
        
        //load model
        $this->load->model('app/m_dashboard','m_dashboard');

        //filter global
        $id_periode = (@$param['global_id_periode'] == '' ? '1' : @$param['global_id_periode']);
        $id_bsc = (@$param['global_id_bsc'] == '' ? '1' : @$param['global_id_bsc']);
      
        //where
        $whereE = " is_active = 't' AND a.status_kpi_so = '3'";
        if($id_bsc != ''){ $whereE .= " AND a.id_bsc = $id_bsc"; }
        if($id_periode != ''){ 
            $arr = @$this->m_global->getDataAll('m_periode', null, ['id'=> $id_periode], 'start_year,end_year')[0];
            $start_year = $arr->start_year;
            $end_year = $arr->end_year;
            $whereE .= " AND (
                                (a.\"start_date\" <= '".$start_year."-01-01' AND a.\"end_date\" >= '".$start_year."-01-01')
                                 OR 
                                (a.\"start_date\" <= '".$end_year."-01-01' AND a.\"end_date\" >= '".$end_year."-01-01')
                            ) 
                        ";
        }

        //table dan join
        $table = "m_kpi_so AS a";
        $join  = NULL;
        $order = "a.code ASC";
        $select = ['id','name_kpi_so','code_kpi_so',
                    'polarisasi','pic_kpi_so','ukuran','frekuensi_pengukuran',
                    'id_so', 'name_pic_kpi_so','name_polarisasi'
                 ];
        $select = $this->m_dashboard->select($select);
        $result = $this->m_global->getDataAll($table, $join, $whereE, $select, null, $order);
        // echo $this->db->last_query();exit;
        // echo '<pre>';print_r($result);exit;
        $data ['data'] = $result;

        //target year
        $where = " is_active = 't' AND status=3 AND year >='".$start_year."' AND year <='".$end_year."'"; 
        $arr = @$this->m_global->getDataAll('m_kpi_so_target_year', null, $where, 'id_kpi_so, year, target, target_from, target_to');
        $arr_target = $arr_target_from = $arr_target_to = [];
        foreach($arr as $row){
            $arr_target[$row->id_kpi_so][$row->year] = $row->target;
            $arr_target_from[$row->id_kpi_so][$row->year] = $row->target_from;
            $arr_target_to[$row->id_kpi_so][$row->year] = $row->target_to;
        }
        $data['arr_target'] = $arr_target;
        $data['arr_target_from'] = $arr_target_from;
        $data['arr_target_to'] = $arr_target_to;

        //variabel tambahan
        $bsc = @$this->m_global->getDataAll('m_bsc', null, ['id'=> $global_id_bsc], 'name')[0]->name;
        $data['bsc'] = @$bsc;
        $data['start_year'] = @$start_year;
        $data['end_year'] = @$end_year;

        if($html){
            return $this->template->display_ajax("app/dashboard/v_graphical_analysis", $data, null, null, TRUE);
        }else{
            $this->template->display_ajax("app/dashboard/v_graphical_analysis", $data);
        }
    }

    public function load_detail_graphical_analysis($html=FALSE)
    {
        //url
        $data['url']  = $this->url;

        //load model
        $this->load->model('app/m_dashboard','m_dashboard');

        //param
        $param = @$this->input->post('filter');
        $id_kpi_so = @$this->input->post('id');
        $id_periode = (@$param['global_id_periode'] == '' ? '1' : @$param['global_id_periode']);
        $id_bsc = (@$param['global_id_bsc'] == '' ? '1' : @$param['global_id_bsc']);
        $arr = @$this->m_global->getDataAll('m_periode', null, ['id'=> $id_periode], 'start_year,end_year')[0];
        $start_year = $arr->start_year;
        $end_year = $arr->end_year;
        $data['id_kpi_so'] = @$id_kpi_so;
        $data['start_year'] = @$start_year;
        $data['end_year'] = @$end_year;
        

        //Ambil datanya kpi-so
        $whereE = " a.is_active = 't' AND a.status_kpi_so = '3' ";
        if($id_kpi_so != ''){ $whereE .= " AND a.id = $id_kpi_so"; }
        if($id_bsc != ''){ $whereE .= " AND a.id_bsc = $id_bsc"; }
        $table = "m_kpi_so AS a";
        $join  = NULL;
        $order = "a.code ASC";
        $select = ['id','name_kpi_so','code_kpi_so',
                    'polarisasi','pic_kpi_so','ukuran','frekuensi_pengukuran',
                    'id_so', 'name_pic_kpi_so','name_polarisasi'
                 ];
        $select = $this->m_dashboard->select($select);
        $result = $this->m_global->getDataAll($table, $join, $whereE, $select, null, $order)[0];
        // echo $this->db->last_query();exit;
        // echo '<pre>';print_r($result);exit;
        $data ['data'] = $result;

        //keterangan performace analysis
        $where = " is_active = 't' AND id_kpi_so='".$id_kpi_so."' AND month='12' AND year >='".$start_year."' AND year <='".$end_year."'"; 
        $select = 'id_kpi_so, year, target, realisasi, pencapaian, penyebab1, penyebab2, recommendations, quick_win';
        $arr = @$this->m_global->getDataAll('m_kpi_so_target_month', null, $where, $select);
        // echo $this->db->last_query();exit;
        $penyebab1 = $recommendations = [];
        foreach($arr as $row){
            $target[$row->id_kpi_so][$row->year] = $row->target;
            $realisasi[$row->id_kpi_so][$row->year] = $row->realisasi;
            $pencapaian[$row->id_kpi_so][$row->year] = $row->pencapaian;
            $penyebab1[$row->id_kpi_so][$row->year] = $row->penyebab1;
            $recommendations[$row->id_kpi_so][$row->year] = $row->recommendations;
        }
        $data['penyebab1'] = $penyebab1;
        $data['recommendations'] = $recommendations;

        //data grafik
        $category_year = "";
        for($year = $start_year; $year <= $end_year; $year++){
            $category_year .= "'".$year."',";
        }
        $category_year = substr($category_year, 0, -1);
        $data['category_year'] = $category_year;
        $d_target = $d_realisasi = $d_pencapaian = [];
        for($year = $start_year; $year <= $end_year; $year++){
            $d_target[] = (@$target[$id_kpi_so][$year] == '' ? '0' : @$target[$id_kpi_so][$year]);
            $d_realisasi[] = (@$realisasi[$id_kpi_so][$year] == '' ? '0' : @$realisasi[$id_kpi_so][$year]);
            $d_pencapaian[] = (@$pencapaian[$id_kpi_so][$year] == '' ? '0' : @$pencapaian[$id_kpi_so][$year]);
        }
        $data['target'] = implode(',',$d_target);
        $data['realisasi'] =  implode(',',$d_realisasi);
        $data['pencapaian'] =  implode(',',$d_pencapaian);
        // echo '<pre>';print_r($data['pencapaian']);exit;

        //html
        if($html){
            return $this->template->display_ajax("app/dashboard/v_detail_graphical_analysis", $data, null, null, TRUE);
        }else{
            $this->template->display_ajax("app/dashboard/v_detail_graphical_analysis", $data);
        }
    }


    public function load_detail_graphical_analysis_stabilize($html=FALSE)
    {
        //url
        $data['url']  = $this->url;

        //load model
        $this->load->model('app/m_dashboard','m_dashboard');

        //param
        $param = @$this->input->post('filter');
        $id_kpi_so = @$this->input->post('id');
        $id_periode = (@$param['global_id_periode'] == '' ? '1' : @$param['global_id_periode']);
        $id_bsc = (@$param['global_id_bsc'] == '' ? '1' : @$param['global_id_bsc']);
        $arr = @$this->m_global->getDataAll('m_periode', null, ['id'=> $id_periode], 'start_year,end_year')[0];
        $start_year = $arr->start_year;
        $end_year = $arr->end_year;
        $data['id_kpi_so'] = @$id_kpi_so;
        $data['start_year'] = @$start_year;
        $data['end_year'] = @$end_year;
        
        //Ambil datanya kpi-so
        $whereE = " a.is_active = 't' AND a.status_kpi_so = '3' ";
        if($id_kpi_so != ''){ $whereE .= " AND a.id = $id_kpi_so"; }
        if($id_bsc != ''){ $whereE .= " AND a.id_bsc = $id_bsc"; }
        $table = "m_kpi_so AS a";
        $join  = NULL;
        $order = "a.code ASC";
        $select = ['id','name_kpi_so','code_kpi_so',
                    'polarisasi','pic_kpi_so','ukuran','frekuensi_pengukuran',
                    'id_so', 'name_pic_kpi_so','name_polarisasi'
                 ];
        $select = $this->m_dashboard->select($select);
        $result = $this->m_global->getDataAll($table, $join, $whereE, $select, null, $order)[0];
        // echo $this->db->last_query();exit;
        // echo '<pre>';print_r($result);exit;
        $data ['data'] = $result;

        //keterangan performace analysis
        $where = " is_active = 't' AND id_kpi_so='".$id_kpi_so."' AND month='12' AND year >='".$start_year."' AND year <='".$end_year."'"; 
        $select = 'id_kpi_so, year, target_from, target_to, realisasi, pencapaian, penyebab1, penyebab2, recommendations, quick_win';
        $arr = @$this->m_global->getDataAll('m_kpi_so_target_month', null, $where, $select);
        // echo $this->db->last_query();exit;
        $penyebab1 = $recommendations = [];
        foreach($arr as $row){
            $target_from[$row->id_kpi_so][$row->year] = $row->target_from;
            $target_to[$row->id_kpi_so][$row->year] = $row->target_to;
            $realisasi[$row->id_kpi_so][$row->year] = $row->realisasi;
            $pencapaian[$row->id_kpi_so][$row->year] = $row->pencapaian;
            $penyebab1[$row->id_kpi_so][$row->year] = $row->penyebab1;
            $recommendations[$row->id_kpi_so][$row->year] = $row->recommendations;
        }
        $data['penyebab1'] = $penyebab1;
        $data['recommendations'] = $recommendations;

        //data grafik
        $category_year = "";
        for($year = $start_year; $year <= $end_year; $year++){
            $category_year .= "'".$year."',";
        }
        $category_year = substr($category_year, 0, -1);
        $data['category_year'] = $category_year;
        $d_target_from = $d_target_to = $d_realisasi = $d_pencapaian = [];
        for($year = $start_year; $year <= $end_year; $year++){
            $d_target_from[] = (@$target_from[$id_kpi_so][$year] == '' ? '0' : @$target_from[$id_kpi_so][$year]);
            $d_target_to[] = (@$target_to[$id_kpi_so][$year] == '' ? '0' : @$target_to[$id_kpi_so][$year]);
            $d_realisasi[] = (@$realisasi[$id_kpi_so][$year] == '' ? '0' : @$realisasi[$id_kpi_so][$year]);
            $d_pencapaian[] = (@$pencapaian[$id_kpi_so][$year] == '' ? '0' : @$pencapaian[$id_kpi_so][$year]);
        }
        $data['target_from'] = implode(',',$d_target_from);
        $data['target_to'] = implode(',',$d_target_to);
        $data['realisasi'] =  implode(',',$d_realisasi);
        $data['pencapaian'] =  implode(',',$d_pencapaian);
        // echo '<pre>';print_r($data['pencapaian']);exit;

        //html
        if($html){
            return $this->template->display_ajax("app/dashboard/v_detail_graphical_analysis_stabilize", $data, null, null, TRUE);
        }else{
            $this->template->display_ajax("app/dashboard/v_detail_graphical_analysis_stabilize", $data);
        }
    }

    public function load_detail_strategic_theme($html=FALSE)
    {
        //url
        $data['url']  = $this->url;

        //load model
        $this->load->model('app/m_dashboard','m_dashboard');

        //param
        $param = @$this->input->post('filter');
        $id_strategic_theme = @$this->input->post('id');
        $id_periode = (@$param['global_id_periode'] == '' ? '1' : @$param['global_id_periode']);
        $id_bsc = (@$param['global_id_bsc'] == '' ? '1' : @$param['global_id_bsc']);
        $arr = @$this->m_global->getDataAll('m_periode', null, ['id'=> $id_periode], 'start_year,end_year')[0];
        $start_year = $arr->start_year;
        $end_year = $arr->end_year;
        $data['id_strategic_theme'] = @$id_strategic_theme;
        $data['start_year'] = @$start_year;
        $data['end_year'] = @$end_year;
        
        //Ambil datanya sr
        $whereE = " a.is_active = 't' AND a.status_sr = '3' ";
        if($id_strategic_theme != ''){ $whereE .= " AND a.id_strategic_theme = $id_strategic_theme"; }
        if($id_bsc != ''){ $whereE .= " AND a.id_bsc = $id_bsc"; }
        $table = "m_strategic_result AS a";
        $join  = NULL;
        $order = "a.code ASC";
        $select = ['id','name_sr','code_sr','target',
                    'polarisasi','pic_sr','ukuran','indikator',
                    'id_strategic_theme', 'name_pic_sr','name_polarisasi'
                 ];
        $select = $this->m_dashboard->select_strategic_theme($select);
        $strategic_result = $this->m_global->getDataAll($table, $join, $whereE, $select, null, $order);
        // echo $this->db->last_query();exit;
        // echo '<pre>';print_r($strategic_result);exit;
        $data['strategic_result'] = $strategic_result;


        //keterangan performace analysis
        $temp = [];
        foreach($strategic_result as $row){
            $arr = [];
            $id_sr = $row->id;
            $where = " is_active = 't' AND id_sr='".$id_sr."' AND year >='".$start_year."' AND year <='".$end_year."'"; 
            $select = 'id_sr, year, month, target, realisasi, pencapaian, keterangan, recommendations';
            $arr = @$this->m_global->getDataAll('m_sr_target_month', null, $where, $select);
            // echo $this->db->last_query();exit;
            $keterangan = $recommendations = $target = $realisasi = $deviasi = $pencapaian = [];
            foreach($arr as $row){
                $target[$row->id_sr][$row->year][$row->month] = h_nilai_index($row->target);
                $realisasi[$row->id_sr][$row->year][$row->month] = h_nilai_index($row->realisasi);
                $deviasi[$row->id_sr][$row->year][$row->month] = h_nilai_index($row->realisasi);
                $pencapaian[$row->id_sr][$row->year][$row->month] = ($row->pencapaian);
                $keterangan[$row->id_sr][$row->year][$row->month] = $row->keterangan;
                $recommendations[$row->id_sr][$row->year][$row->month] = $row->recommendations;
            }
            // echo '<pre>';print_r($target);exit;


            //data grafik
            $category_year = "";
            for($year = $start_year; $year <= $end_year; $year++){
                $category_year .= "'Q2-".substr($year,2,2)."',";
                $category_year .= "'Q4-".substr($year,2,2)."',";
            }
            $category_year = substr($category_year, 0, -1);
            $arr['category_year'] = $category_year;
            // echo '<pre>';print_r($category_year);exit;


            //data grafik
            $d_target = $d_realisasi = $d_pencapaian = $d_deviasi = $d_keterangan = $d_recommendations = [];
            for($year = $start_year; $year <= $end_year; $year++){
                $d_target[] = (@$target[$id_sr][$year][6] == '' ? '0' : round(@$target[$id_sr][$year][6],2));
                $d_target[] = (@$target[$id_sr][$year][12] == '' ? '0' : round(@$target[$id_sr][$year][12],2));
                $d_realisasi[] = (@$realisasi[$id_sr][$year][6] == '' ? '0' : round(@$realisasi[$id_sr][$year][6],2));
                $d_realisasi[] = (@$realisasi[$id_sr][$year][12] == '' ? '0' : round(@$realisasi[$id_sr][$year][12],2));
                $d_pencapaian[] = (@$pencapaian[$id_sr][$year][6] == '' ? '0' : round(@$pencapaian[$id_sr][$year][6],2));
                $d_pencapaian[] = (@$pencapaian[$id_sr][$year][12] == '' ? '0' : round(@$pencapaian[$id_sr][$year][12],2));
                $d_deviasi[] = (@$deviasi[$id_sr][$year][6] == '' ? '0' : round(@$deviasi[$id_sr][$year][6],2));
                $d_deviasi[] = (@$deviasi[$id_sr][$year][12] == '' ? '0' : round(@$deviasi[$id_sr][$year][12],2));
                $d_keterangan[] = (@$keterangan[$id_sr][$year][6] == '' ? '-' : @$keterangan[$id_sr][$year][6]);
                $d_keterangan[] = (@$keterangan[$id_sr][$year][12] == '' ? '-' : @$keterangan[$id_sr][$year][12]);
                $d_recommendations[] = (@$recommendations[$id_sr][$year][6] == '' ? '-' : @$recommendations[$id_sr][$year][6]);
                $d_recommendations[] = (@$recommendations[$id_sr][$year][12] == '' ? '-' : @$recommendations[$id_sr][$year][12]);
            }
            $arr['target'] = implode(',',$d_target);
            $arr['realisasi'] =  implode(',',$d_realisasi);
            $arr['pencapaian'] =  implode(',',$d_pencapaian);
            $arr['deviasi'] =  implode(',',$d_deviasi);
            $arr['keterangan'] =  implode('^',$d_keterangan);
            $arr['recommendations'] =  implode('^',$d_recommendations);
            $temp[$id_sr] = $arr;
            // echo '<pre>';print_r($arr);exit;
        }

        $data['data_sr'] = $temp;


        //html
        if($html){
            return $this->template->display_ajax("app/dashboard/v_detail_strategic_theme", $data, null, null, TRUE);
        }else{
            $this->template->display_ajax("app/dashboard/v_detail_strategic_theme", $data);
        }
    }


    public function load_detail_strategic_result($html=FALSE)
    {
        //url
        $data['url']  = $this->url;
        $data['category_year'] = explode(',', str_replace("'","",@$this->input->post('category_year')));
        $data['target'] = explode(',',@$this->input->post('target'));
        $data['pencapaian'] = explode(',',@$this->input->post('pencapaian'));
        $data['realisasi'] = explode(',',@$this->input->post('realisasi'));
        $data['deviasi'] = explode(',',@$this->input->post('deviasi'));
        $data['keterangan'] = explode('^',@$this->input->post('keterangan'));
        $data['recommendations'] = explode('^',@$this->input->post('recommendations'));
        // echo '<pre>';print_r($data);exit;

        $this->template->display_ajax("app/dashboard/v_detail_strategic_result", $data);
    }


    

}