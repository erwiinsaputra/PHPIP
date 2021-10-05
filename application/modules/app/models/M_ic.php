<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_ic extends CI_Model {

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
        $sel['id_action_plan']      = "a.id AS id_action_plan";
        $sel['is_active']           = "a.is_active";
        $sel['created_date']        = "a.created_date";
        $sel['created_by']          = "a.created_by";
        $sel['updated_date']        = "a.updated_date";
        $sel['updated_by']          = "a.updated_by";
        
        $sel['status_action_plan']  = "a.status_action_plan";

        $sel['name']                = "a.name";
        $sel['code']                = "a.code";
        $sel['name_action_plan']    = "a.name AS name_action_plan";
        $sel['code_action_plan']    = "a.code AS code_action_plan";
        $sel['weighting_factor']     = "a.weighting_factor";
        $sel['deliverable']         = "a.deliverable";
        $sel['budget_currency']     = "a.budget_currency";
        $sel['pic_action_plan']     = "a.pic_action_plan";
        $sel['start_date']          = "a.start_date";
        $sel['end_date']            = "a.end_date";

        $sel['id_bsc']              = "a.id_bsc";
        $sel['id_si']               = "a.id_si";
       
        $sel['name_pic_action_plan'] = "(SELECT DISTINCT STRING_AGG ( b.\"SINGKATAN_POSISI\" :: CHARACTER VARYING, ',' )
                                        FROM \"ERP_STO_REAL\" b  
                                        WHERE b.\"POSITION_ID\" ::text = ANY (string_to_array(a.pic_action_plan,', ')::text[])
                                        ) AS name_pic_action_plan";
        $sel['name_pic_action_plan2'] = "(SELECT DISTINCT STRING_AGG ( b.\"singkatan_posisi\" :: CHARACTER VARYING, ',' )
                                        FROM \"m_pic\" b  
                                        WHERE b.\"position_id_new\" ::text = ANY (string_to_array(a.pic_action_plan,', ')::text[])
                                        ) AS name_pic_action_plan2";
        $sel['name_bsc']            = "(SELECT b.name FROM m_bsc b WHERE b.id = a.id_bsc ) AS name_bsc";
        $sel['name_si']             = "(SELECT b.name FROM m_si b WHERE b.id = a.id_si ) AS name_si";
        $sel['code_si']             = "(SELECT b.code FROM m_si b WHERE b.id = a.id_si ) AS code_si";
        $sel['name_status_action_plan']  = "(SELECT b.name FROM m_status b WHERE b.type='SO STATUS' AND b.id = a.status_action_plan ) AS name_status_action_plan";

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