<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_bsc extends CI_Model {

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
        $sel['id_workunit']         = "a.id_workunit";
        $sel['id_bsc_type']         = "a.id_bsc_type";
        $sel['id_perspective']      = "a.id_perspective";

        $sel['name_workunit']       = "(SELECT b.name FROM m_workunit b WHERE b.id = a.id_workunit ) AS name_workunit";
        $sel['name_bsc_type']       = "(SELECT b.name FROM m_bsc_type b WHERE b.id = a.id_bsc_type ) AS name_bsc_type";
        $sel['name_perspective']    = "(SELECT DISTINCT STRING_AGG(b.name::character varying, ',')
                                        FROM m_perspective b 
                                        WHERE b.id::CHARACTER = ANY (string_to_array(a.id_perspective,','))
                                    ) AS name_perspective";

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