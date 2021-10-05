<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_so extends CI_Model {

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
        $sel['description']         = "a.description";
        $sel['id_perspective']      = "a.id_perspective";
        $sel['pic_so']              = "a.pic_so";
        $sel['id_bsc']              = "a.id_bsc";
        $sel['status_so']           = "a.status_so";
        $sel['start_date']          = "a.start_date";
        $sel['end_date']            = "a.end_date";

        $sel['name_perspective']    = "(SELECT b.name FROM m_perspective b WHERE b.id = a.id_perspective ) AS name_perspective";
        $sel['name_pic_so']         = "(SELECT DISTINCT STRING_AGG ( b.\"SINGKATAN_POSISI\" :: CHARACTER VARYING, ',' )
                                        FROM \"ERP_STO_REAL\" b  
                                        WHERE b.\"POSITION_ID\" ::text = ANY (string_to_array(a.pic_so,', ')::text[])
                                        ) AS name_pic_so";
        $sel['name_bsc']            = "(SELECT b.name FROM m_bsc b WHERE b.id = a.id_bsc ) AS name_bsc";
        $sel['name_status_so']      = "(SELECT b.name FROM m_status b WHERE b.type='SO STATUS' AND b.id = a.status_so ) AS name_status_so";

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