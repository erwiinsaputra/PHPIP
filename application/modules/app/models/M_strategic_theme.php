<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_strategic_theme extends CI_Model {

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
        $sel['indikator']           = "a.indikator";
        $sel['polarisasi']          = "a.polarisasi";
        $sel['ukuran']              = "a.ukuran";
        $sel['target']              = "a.target";
        $sel['target_from']         = "a.target_from";
        $sel['target_to']           = "a.target_to";
        $sel['code']                = "a.code";
        $sel['description']         = "a.description";
        $sel['id_perspective']      = "a.id_perspective";
        $sel['id_periode']          = "a.id_periode";
        $sel['pic_so']              = "a.pic_so";
        $sel['id_bsc']              = "a.id_bsc";

        $sel['name_perspective']    = "(SELECT b.name FROM m_perspective b WHERE b.id = a.id_perspective ) AS name_perspective";
        $sel['name_periode']        = "(SELECT CONCAT(b.start_year,'-',b.end_year) FROM m_periode b WHERE b.id = a.id_periode ) AS name_periode";
        $sel['name_pic']            = "(SELECT b.fullname FROM sys_user b WHERE b.id = a.id_perspective ) AS name_pic";
        $sel['name_bsc']            = "(SELECT b.name FROM m_bsc b WHERE b.id = a.id_bsc ) AS name_bsc";

        $sel['order']               = "a.order";
        $sel['icon']                = "a.icon";


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