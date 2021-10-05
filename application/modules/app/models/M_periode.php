<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_periode extends CI_Model {

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

        $sel['start_year']          = "a.start_year";
        $sel['end_year']            = "a.end_year";
        $sel['id_strategic_theme']  = "a.id_strategic_theme";
        

        $sel['name_strategic_theme'] = "(SELECT DISTINCT STRING_AGG(b.name::character varying, ',')
                                                FROM m_strategic_theme b 
                                                WHERE b.id::CHARACTER = ANY (string_to_array(a.id_strategic_theme,','))
                                    ) AS name_strategic_theme";
       


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