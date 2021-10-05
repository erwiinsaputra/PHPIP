<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_si extends CI_Model {

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
        $sel['is_active']           = "a.is_active";
        $sel['created_date']        = "a.created_date";
        $sel['created_by']          = "a.created_by";
        $sel['updated_date']        = "a.updated_date";
        $sel['updated_by']          = "a.updated_by";

        $sel['name']                = "a.name";
        $sel['code']                = "a.code";
        $sel['background_goal']     = "a.background_goal";
        $sel['objective_key_result']= "a.objective_key_result";
        $sel['cek_objective_key_result'] = "a.cek_objective_key_result";
        $sel['pic_si']              = "a.pic_si";
        $sel['id_bsc']              = "a.id_bsc";
        $sel['status_si']           = "a.status_si";
        $sel['start_date']          = "a.start_date";
        $sel['end_date']            = "a.end_date";

        $sel['name_pic_si']         = "(SELECT DISTINCT STRING_AGG ( b.\"SINGKATAN_POSISI\" :: CHARACTER VARYING, ',' )
                                        FROM \"ERP_STO_REAL\" b  
                                        WHERE b.\"POSITION_ID\" ::text = ANY (string_to_array(a.pic_si,', ')::text[])
                                        ) AS name_pic_si";
        $sel['name_bsc']            = "(SELECT b.name FROM m_bsc b WHERE b.id = a.id_bsc ) AS name_bsc";
        $sel['name_status_si']      = "(SELECT b.name FROM m_status b WHERE b.type='SO STATUS' AND b.id = a.status_si ) AS name_status_si";

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