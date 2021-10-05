<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_request_ic extends CI_Model {

    public function __construct(){
        parent::__construct();
    }

    public function select($select=''){

        if(!is_array($select)){ 
            if($select != '*'){
                $select = array($select);
            }
        }

        $sel = [];
    
        $sel['id']                  = "a.id";
        $sel['id_request_ic']       = "a.id AS id_request_ic";
        $sel['is_active']           = "a.is_active";
        $sel['created_date']        = "a.created_date";
        $sel['created_by']          = "a.created_by";
        $sel['request_date']        = "a.request_date";
        $sel['request_by']          = "a.request_by";
        $sel['updated_date']        = "a.updated_date";
        $sel['updated_by']          = "a.updated_by";
        
        $sel['status_request']      = "a.status_request";
        $sel['status_finished']     = "a.status_finished";
        $sel['status_send_to_admin']= "a.status_send_to_admin";
        $sel['keterangan']          = "a.keterangan";
        $sel['keterangan_approval'] = "a.keterangan_approval";
        $sel['request_date']        = "a.request_date";
        $sel['approve_date']        = "a.approve_date";
        $sel['finished_date']       = "a.finished_date";

        $sel['id_bsc']              = "a.id_bsc";
        $sel['id_si']               = "a.id_si";
        $sel['id_pic']              = "a.id_pic";
       
        $sel['name_pic'] = "(SELECT DISTINCT STRING_AGG ( b.\"SINGKATAN_POSISI\" :: CHARACTER VARYING, ',' )
                                        FROM \"ERP_STO_REAL\" b  
                                        WHERE b.\"POSITION_ID\" ::text = ANY (string_to_array(a.id_pic:: CHARACTER VARYING,', ')::text[])
                                        ) AS name_pic";
        $sel['name_pic2'] = "(SELECT DISTINCT STRING_AGG ( b.\"singkatan_posisi\" :: CHARACTER VARYING, ',' )
                                        FROM \"m_pic\" b  
                                        WHERE b.\"position_id_new\" ::text = ANY (string_to_array(a.id_pic:: CHARACTER VARYING,', ')::text[])
                                        ) AS name_pic2";
        $sel['name_bsc']            = "(SELECT b.name FROM m_bsc b WHERE b.id = a.id_bsc ) AS name_bsc";
        $sel['name_si']             = "(SELECT b.name FROM m_si b WHERE b.id = a.id_si ) AS name_si";
        $sel['code_si']             = "(SELECT b.code FROM m_si b WHERE b.id = a.id_si ) AS code_si";
        $sel['name_status_request'] = "(SELECT b.name FROM m_status b WHERE b.type='SO STATUS' AND b.id = a.status_request ) AS name_status_request";
        $sel['name_request_by']     = "(SELECT b.fullname FROM sys_user b WHERE b.id = a.request_by ) AS name_request_by";

        if($select == '*'){
            foreach ($sel as $val) { 
                $diselect[] = $val; 
            }
        }else{
            foreach ($select as $val) {  
                $diselect[] = @$sel[$val]; 
            }
        }
        $select = implode($diselect, ', ');

        return $select;
    }


    public function select_download_excel($select=''){

        if(!is_array($select)){ 
            if($select != '*'){
                $select = array($select);
            }
        }

        $sel = [];
        
        $sel['id']                  = "a.id";
        $sel['name_kpi_so']         = "a.name AS name_kpi_so";
        $sel['code_kpi_so']         = "a.code AS code_kpi_so";
        $sel['id_kpi_so']           = "a.id AS id_kpi_so";
        $sel['id_so']               = "a.id_so";
        $sel['name_so']             = "(SELECT b.name FROM m_so b WHERE b.id = a.id_so ) AS name_so";
        $sel['code_so']             = "(SELECT b.code FROM m_so b WHERE b.id = a.id_so ) AS code_so";
        $sel['polarisasi']          = "a.polarisasi";
        $sel['start_date']          = "a.start_date";
        $sel['end_date']            = "a.end_date";

        if($select == '*'){
            foreach ($sel as $val) { 
                $diselect[] = $val; 
            }
        }else{
            foreach ($select as $val) {  
                $diselect[] = @$sel[$val]; 
            }
        }
        $select = implode($diselect, ', ');

        return $select;
    }


}