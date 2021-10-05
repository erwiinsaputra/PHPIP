<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Log_history extends MX_Controller {
    private $prefix         = 'log_history';
    private $table_db       = 'v_log_history';
    private $title          = 'Log History User';
    private $logTable       = '';
    private $url            = 'global/log_history/';
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
        $data['setting']    = $this->setting;
        $data['url']        = $this->url;
        $data['breadcrumb'] = ['Master' => TRUE, $this->title => $this->url];
        $js['custom']       = ['table_tpr_log_history'];
        $this->template->display($this->prefix.'/'.$this->prefix, $data, $js);
    }

    public function get_table()
    {    

        //search default
        $where  = [];
        $whereE = NULL;

        //search global
        $year       = @$_REQUEST['year'];
        if($year != 'all'){ $where['YEAR(log_created_date)'] = $year;}

        //filtering
        $search = [];
        if(isset($_REQUEST['filter'])){
            foreach ($_REQUEST['filter'] as $row) {
                //default parameter
                if(@$row['name'] != ''){
                    $val= $row['value']; $tipe = $row['tipe']; $search[] = $row['name'];
                     $name = $row['name']; $val= $row['value']; $tipe = $row['tipe']; $search[$name] = $val;
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
                        if($tipe == '7'){
                            if($whereE==NULL){$and='';}else{$and=' AND ';}
                            $whereE .= $and." ($name = '' OR $name IS NULL) ";
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
            $order = ['log_created_date','DESC'];
        }
        // echo '<pre>';print_r($order);exit;

        //select
        $select = '*';
        // $select = array_unique(array_merge($addSelect, $search));

        //table dan join
        $join  = NULL;

        //pagging
        $iTotalRecords  = $this->m_global->countDataAll($this->table_db, $join, $where, $whereE);
        $iDisplayLength = intval($_REQUEST['length']);
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
        $iDisplayStart  = intval($_REQUEST['start']);
        $sEcho          = intval($_REQUEST['draw']);
        $end            = $iDisplayStart + $iDisplayLength;
        $i              = 1 + $iDisplayStart;
        $end            = $end > $iTotalRecords ? $iTotalRecords : $end;

        //Ambil datanya
        $result = $this->m_global->getDataAll($this->table_db, $join, $where, $select, $whereE, $order, $iDisplayStart, $iDisplayLength);
        // echo $this->db->last_query();exit;
        $param=[];
        foreach ($result as $rows) {
            $id = $rows->log_id;

            // $btn_history = '<button data-original-title="History" href="'.site_url( $this->url.'show_history/'. $id ) . '" class="btn btn-sm green-meadow ajaxify tooltips"><i class="fa fa-history"></i></>';
            $btn_history = '';
            
            // $btn_view = '<button data-original-title="View" class="btn btn-sm red-sunglo tooltips btn_view_sales" tpm_id="'.$rows->tpm_id.'" ams_id="'.$rows->ams_id.'"><i class="fa fa-eye"></i></button>';
            $log_activity = '<a href="javascript:;" class="btn_more">'.
                                '<div class="text_short">'.substr($rows->log_activity, 0,20).' ... </div>'.
                                '<div class="text_full" style="display:none;">'.$rows->log_activity.'</div>'.
                            '</a>';
            $log_param_id = '<a href="javascript:;" class="btn_more">'.
                                '<div class="text_short">'.substr($rows->log_param_id, 0,20).' ... </div>'.
                                '<div class="text_full" style="display:none;">'.$rows->log_param_id.'</div>'.
                            '</a>';

            $param[] = [
                            'no'=>$i,
                            'log_us_id'=>@$rows->USER_NAME."(".$rows->USER_INITIAL.")",
                            'log_role_id'=>h_role_name(@$rows->log_role_id),
                            'log_ip_address'=>@$rows->log_ip_address,
                            'log_other_user'=>(@$rows->log_other_user == '' ? '-' : @$rows->log_other_user),
                            'log_type'=>@$rows->log_type,
                            'log_created_date'=>@$rows->log_created_date,

                            'log_activity'=>@$log_activity,
                            'log_param_id'=>@$log_param_id,
                            'action'=>$btn_history
                            // ."&nbsp;".$btn_view
                        ];
            $i++;
        }
        $records["data"]            = $param;
        $records["draw"]            = $sEcho;
        $records["recordsTotal"]    = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
    }

    public function select_customer($id=NULL)
    {
        if(is_null($id)){
            $q          = $_GET['q'];
            $parent     = $this->m_global->getDataAll('m_customer', NULL,['cus_name LIKE' => $q.'%'], 'cus_id, cus_name',NULL,['cus_name','ASC']);
            $data = [];
            for ($i=0; $i < count($parent); $i++) {
                $data[$i] = ['id' => $parent[$i]->cus_id, 'name' => $parent[$i]->cus_name];
            }
            echo json_encode(['item' => $data]);
        }else{
            $id         = str_replace('-', ',', $id);
            $where_e    = " `cus_id` IN (".$id.")";
            $parent     = $this->m_global->getDataAll('m_customer', NULL, NULL, '*', $where_e);
            $data       = [];
            for ($i=0; $i < count($parent); $i++) {
                $data[$i] = ['id' => $parent[$i]->cus_id, 'name' => $parent[$i]->cus_name];
            }
            echo json_encode($data);
        }
    }

    public function select_user($id=NULL)
    {
        if(is_null($id)){
            $q          = $_GET['q'];
            $where_e    = " (lower(USER_NAME) LIKE '%".$q."%') OR (lower(USER_INITIAL) LIKE '%".$q."%') ";
            $parent     = $this->m_global->getDataAll('m_user', NULL, $where_e,'USER_ID, USER_NAME, USER_INITIAL',null,['USER_INITIAL','ASC']);
            $data = [];
            for ($i=0; $i < count($parent); $i++) {
                $data[$i] = ['id' => $parent[$i]->USER_ID, 'name' =>  $parent[$i]->USER_NAME.'('.$parent[$i]->USER_INITIAL.')'];
            }
            echo json_encode(['item' => $data]);
        }else{
            $id         = str_replace('-', ',', $id);
            $where_e    = " `USER_ID` IN (".$id.")";
            $parent     = $this->m_global->getDataAll('m_user', NULL, NULL, 'USER_ID, USER_INITIAL, USER_NAME', $where_e);
            $data       = [];
            for ($i=0; $i < count($parent); $i++) {
                $data[$i] = ['id' => $parent[$i]->USER_ID, 'name' => $parent[$i]->USER_NAME.'('.$parent[$i]->USER_INITIAL.')'];
            }
            echo json_encode($data);
        }
    }

    public function select_engine($id=NULL)
    {
        if(is_null($id)){
            $q          = $_GET['q'];
            $parent     = $this->m_global->getDataAll('m_engine', NULL,['eng_name LIKE' => '%'.$q.'%'], 'eng_id, eng_name',NULL,['eng_name','ASC']);
            $data = [];
            for ($i=0; $i < count($parent); $i++) {
                $data[$i] = ['id' => $parent[$i]->eng_id, 'name' => $parent[$i]->eng_name];
            }
            echo json_encode(['item' => $data]);
        }else{
            $id         = str_replace('-', ',', $id);
            $where_e    = " `eng_id` IN (".$id.")";
            $parent     = $this->m_global->getDataAll('m_engine', NULL, NULL, 'eng_id, eng_name', $where_e);
            $data       = [];
            for ($i=0; $i < count($parent); $i++) {
                $data[$i] = ['id' => $parent[$i]->eng_id, 'name' => $parent[$i]->eng_name];
            }
            echo json_encode($data);
        }
    }


    public function select_group($id=NULL)
    {
        if(is_null($id)){
            $q          = $_GET['q'];
            $parent     = $this->m_global->getDataAll('m_group', NULL,['gr_name LIKE' => '%'.$q.'%'], 'gr_id, gr_name',NULL,['gr_name','ASC']);
            $data = [];
            for ($i=0; $i < count($parent); $i++) {
                $data[$i] = ['id' => $parent[$i]->gr_id, 'name' => $parent[$i]->gr_name];
            }
            echo json_encode(['item' => $data]);
        }else{
            $id         = str_replace('-', ',', $id);
            $where_e    = " `gr_id` IN (".$id.")";
            $parent     = $this->m_global->getDataAll('m_group', NULL, NULL, 'gr_id, gr_name', $where_e);
            $data       = [];
            for ($i=0; $i < count($parent); $i++) {
                $data[$i] = ['id' => $parent[$i]->gr_id, 'name' => $parent[$i]->gr_name];
            }
            echo json_encode($data);
        }
    }

    public function select_apu($id=NULL)
    {
        if(is_null($id)){
            $q          = $_GET['q'];
            $parent     = $this->m_global->getDataAll('m_apu', NULL,['apu_name LIKE' => '%'.$q.'%'], 'apu_id, apu_name',NULL,['apu_name','ASC']);
            $data = [];
            for ($i=0; $i < count($parent); $i++) {
                $data[$i] = ['id' => $parent[$i]->apu_id, 'name' => $parent[$i]->apu_name];
            }
            echo json_encode(['item' => $data]);
        }else{
            $id         = str_replace('-', ',', $id);
            $where_e    = " `apu_id` IN (".$id.")";
            $parent     = $this->m_global->getDataAll('m_apu', NULL, NULL, 'apu_id, apu_name', $where_e);
            $data       = [];
            for ($i=0; $i < count($parent); $i++) {
                $data[$i] = ['id' => $parent[$i]->apu_id, 'name' => $parent[$i]->apu_name];
            }
            echo json_encode($data);
        }
    }

    public function select_at($id=NULL)
    {
        if(is_null($id)){
            $q          = $_GET['q'];
            $parent     = $this->m_global->getDataAll('m_ac_type', NULL,['at_name LIKE' => '%'.$q.'%'], 'at_id, at_name',NULL,['at_name','ASC']);
            $data = [];
            for ($i=0; $i < count($parent); $i++) {
                $data[$i] = ['id' => $parent[$i]->at_id, 'name' => $parent[$i]->at_name];
            }
            echo json_encode(['item' => $data]);
        }else{
            $id         = str_replace('-', ',', $id);
            $where_e    = " `at_id` IN (".$id.")";
            $parent     = $this->m_global->getDataAll('m_ac_type', NULL, NULL, 'at_id, at_name', $where_e);
            $data       = [];
            for ($i=0; $i < count($parent); $i++) {
                $data[$i] = ['id' => $parent[$i]->at_id, 'name' => $parent[$i]->at_name];
            }
            echo json_encode($data);
        }
    }

    public function select_maintenance($id=NULL)
    {
        if(is_null($id)){
            $q          = $_GET['q'];
            $parent     = $this->m_global->getDataAll('m_work_type', NULL,['wt_name LIKE' => '%'.$q.'%'], 'wt_id, wt_name',NULL,['wt_name','ASC']);
            $data = [];
            for ($i=0; $i < count($parent); $i++) {
                $data[$i] = ['id' => $parent[$i]->wt_id, 'name' => $parent[$i]->wt_name];
            }
            echo json_encode(['item' => $data]);
        }else{
            $id         = str_replace('-', ',', $id);
            $where_e    = " `wt_id` IN (".$id.")";
            $parent     = $this->m_global->getDataAll('m_work_type', NULL, NULL, 'wt_id, wt_name', $where_e);
            $data       = [];
            for ($i=0; $i < count($parent); $i++) {
                $data[$i] = ['id' => $parent[$i]->wt_id, 'name' => $parent[$i]->wt_name];
            }
            echo json_encode($data);
        }
    }

    public function select_country($id=NULL)
    {
        if(is_null($id)){
            $q          = $_GET['q'];
            $parent     = $this->m_global->getDataAll('m_country', NULL,['cou_name LIKE' => '%'.$q.'%'], 'cou_id, cou_name',NULL,['cou_name','ASC']);
            $data = [];
            for ($i=0; $i < count($parent); $i++) {
                $data[$i] = ['id' => $parent[$i]->cou_id, 'name' => $parent[$i]->cou_name];
            }
            echo json_encode(['item' => $data]);
        }else{
            $id         = str_replace('-', ',', $id);
            $where_e    = " `cou_id` IN (".$id.")";
            $parent     = $this->m_global->getDataAll('m_country', NULL, NULL, 'cou_id, cou_name', $where_e);
            $data       = [];
            for ($i=0; $i < count($parent); $i++) {
                $data[$i] = ['id' => $parent[$i]->cou_id, 'name' => $parent[$i]->cou_name];
            }
            echo json_encode($data);
        }
    }

    public function select_region($id=NULL)
    {
        if(is_null($id)){
            $q          = $_GET['q'];
            $parent     = $this->m_global->getDataAll('m_region', NULL,['reg_name LIKE' => '%'.$q.'%'], 'reg_id, reg_name',NULL,['reg_name','ASC']);
            $data = [];
            for ($i=0; $i < count($parent); $i++) {
                $data[$i] = ['id' => $parent[$i]->reg_id, 'name' => $parent[$i]->reg_name];
            }
            echo json_encode(['item' => $data]);
        }else{
            $id         = str_replace('-', ',', $id);
            $where_e    = " `reg_id` IN (".$id.")";
            $parent     = $this->m_global->getDataAll('m_region', NULL, NULL, 'reg_id, reg_name', $where_e);
            $data       = [];
            for ($i=0; $i < count($parent); $i++) {
                $data[$i] = ['id' => $parent[$i]->reg_id, 'name' => $parent[$i]->reg_name];
            }
            echo json_encode($data);
        }
    }

    public function select_area($id=NULL)
    {
        if(is_null($id)){
            $q          = $_GET['q'];
            $parent     = $this->m_global->getDataAll('m_area', NULL,['area_name LIKE' => '%'.$q.'%'], 'area_id, area_name',NULL,['area_name','ASC']);
            $data = [];
            for ($i=0; $i < count($parent); $i++) {
                $data[$i] = ['id' => $parent[$i]->area_id, 'name' => $parent[$i]->area_name];
            }
            echo json_encode(['item' => $data]);
        }else{
            $id         = str_replace('-', ',', $id);
            $where_e    = " `area_id` IN (".$id.")";
            $parent     = $this->m_global->getDataAll('m_area', NULL, NULL, 'area_id, area_name', $where_e);
            $data       = [];
            for ($i=0; $i < count($parent); $i++) {
                $data[$i] = ['id' => $parent[$i]->area_id, 'name' => $parent[$i]->area_name];
            }
            echo json_encode($data);
        }
    }


    public function select_location($id=NULL)
    {
        if(is_null($id)){
            $q          = $_GET['q'];
            $parent     = $this->m_global->getDataAll('m_location', NULL,['loc_name LIKE' => '%'.$q.'%'], 'loc_id, loc_name',NULL,['loc_name','ASC']);
            $data = [];
            for ($i=0; $i < count($parent); $i++) {
                $data[$i] = ['id' => $parent[$i]->loc_id, 'name' => $parent[$i]->loc_name];
            }
            echo json_encode(['item' => $data]);
        }else{
            $id         = str_replace('-', ',', $id);
            $where_e    = " `loc_id` IN (".$id.")";
            $parent     = $this->m_global->getDataAll('m_location', NULL, NULL, 'loc_id, loc_name', $where_e);
            $data       = [];
            for ($i=0; $i < count($parent); $i++) {
                $data[$i] = ['id' => $parent[$i]->loc_id, 'name' => $parent[$i]->loc_name];
            }
            echo json_encode($data);
        }
    }


    public function export_to_excel()
    {  
        // echo '<pre>';print_r($this->input->post());exit;
        $year               = $this->input->post('year');
        $log_us_id          = $this->input->post('log_us_id');
        $log_role_id        = $this->input->post('log_role_id');
        $log_created_date   = $this->input->post('log_created_date');
        $log_ip_address     = $this->input->post('log_ip_address');
        $log_other_user     = $this->input->post('log_other_user');
        $log_type           = $this->input->post('log_type');
        $log_activity       = $this->input->post('log_activity');
        $log_param_id       = $this->input->post('log_param_id');

        $data['year']       = $year;

        $whereE =NULL;

        if($year != 'all'){ 
            $whereE .= " YEAR(log_created_date) = '$year'";
        }
        
        if($log_us_id != ''){ 
            if($whereE==NULL){$and='';}else{$and=' AND ';}
            $whereE .= $and." log_us_id IN($log_us_id) ";
        }
        if($log_role_id != ''){ 
            if($whereE==NULL){$and='';}else{$and=' AND ';}
            $whereE .= $and." log_role_id IN($log_role_id) ";
        }
        if($log_type != ''){ 
            if($whereE==NULL){$and='';}else{$and=' AND ';}
            $whereE .= $and." log_type = '$log_type'";
        }
        if($log_ip_address != ''){ 
            if($whereE==NULL){$and='';}else{$and=' AND ';}
            $whereE .= $and." log_ip_address LIKE '%$log_ip_address%'";
        }
        if($log_other_user != ''){ 
            if($whereE==NULL){$and='';}else{$and=' AND ';}
            $whereE .= $and." log_other_user LIKE '%$log_other_user%'";
        }
        if($log_created_date != ''){ 
            if($whereE==NULL){$and='';}else{$and=' AND ';}
            $whereE .= $and." log_created_date =  '$log_created_date'";
        }
        if($log_activity != ''){ 
            if($whereE==NULL){$and='';}else{$and=' AND ';}
            $whereE .= $and." log_activity LIKE '%$log_activity%'";
        }
        if($log_param_id != ''){ 
            if($whereE==NULL){$and='';}else{$and=' AND ';}
            $whereE .= $and." log_param_id = '$log_param_id'";
        }
        

        $select = '*';

        //table dan join
        $join  = NULL;

        //pagging
        $data['isi'] = @$this->m_global->getDataAll($this->table_db, $join, $whereE, $select);

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment;filename=Report_Salesplan - ".$year.".xls");
        header("Content-Transfer-Encoding: binary ");

        $this->template->display_ajax($this->url.'log_history_excel',$data);

    }

}
