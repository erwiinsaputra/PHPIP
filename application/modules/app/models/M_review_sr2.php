<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_review_sr extends CI_Model {

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
        $sel['id_bsc']              = "a.id_bsc";
        $sel['name_bsc']            = "(SELECT b.name FROM m_bsc b WHERE b.id = a.id_bsc ) AS name_bsc";


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

    public function select_sr($select='', $year=''){

        if(!is_array($select)){ 
            if($select != '*'){
                $select = array($select);
            }
        }

        $sel = [];

        $sel['id']                  = "a.id";
        $sel['description']         = "a.description";
        $sel['is_active']           = "a.is_active";
        $sel['created_date']        = "a.created_date";
        $sel['created_by']          = "a.created_by";
        $sel['updated_date']        = "a.updated_date";
        $sel['updated_by']          = "a.updated_by";

        $sel['name']                = "a.name";
        $sel['name_sr']             = "a.name AS name_sr";
        $sel['code']                = "a.code";
        $sel['code_sr']             = "a.code AS code_sr";
        $sel['pic_sr']              = "a.pic_sr";
        $sel['polarisasi']          = "a.polarisasi";
        $sel['ukuran']              = "a.ukuran";
        $sel['frekuensi_pengukuran']= "a.frekuensi_pengukuran";

        if(@$year != ''){ $where_year = "AND b.year = ".$year; }else{ $where_year='';}
        $sel['arr_target']          = "(SELECT STRING_AGG(b.target::character varying, ',')
                                        FROM m_sr_target_year b 
                                        WHERE b.id_sr = a.id  ".$where_year."
                                    ) AS arr_target";
        $sel['arr_target_from']     = "(SELECT STRING_AGG(b.target_from::character varying, ',')
                                        FROM m_sr_target_year b 
                                        WHERE b.id_sr = a.id  ".$where_year."
                                    ) AS arr_target_from";
        $sel['arr_target_to']       = "(SELECT STRING_AGG(b.target_to::character varying, ',')
                                        FROM m_sr_target_year b 
                                        WHERE b.id_sr = a.id  ".$where_year."
                                    ) AS arr_target_to";

        $sel['id_strategic_result']      = "a.id_strategic_result";
        $sel['id_bsc']              = "a.id_bsc";
        $sel['id_sr']               = "a.id_sr";
        
        $sel['name_pic_sr']     = "(SELECT DISTINCT STRING_AGG ( b.\"SINGKATAN_POSISI\" :: CHARACTER VARYING, ',' )
                                        FROM \"ERP_STO_REAL\" b  
                                        WHERE b.\"POSITION_ID\" ::text = ANY (string_to_array(a.pic_sr,', ')::text[])
                                        ) AS name_pic_sr";
        $sel['name_strategic_result']    = "(SELECT b.name FROM m_strategic_result b WHERE b.id = a.id_strategic_result ) AS name_strategic_result";
        $sel['name_periode']        = "(SELECT CONCAT(b.start_year,'-',b.end_year) FROM m_periode b WHERE b.id = a.id_periode ) AS name_periode";
        $sel['name_bsc']            = "(SELECT b.name FROM m_bsc b WHERE b.id = a.id_bsc ) AS name_bsc";
        $sel['name_sr']             = "(SELECT b.name FROM m_sr b WHERE b.id = a.id_sr ) AS name_sr";
        $sel['code_sr']             = "(SELECT b.code FROM m_sr b WHERE b.id = a.id_sr ) AS code_sr";
        $sel['name_polarisasi']     = "(SELECT b.name FROM m_status b WHERE b.id = a.polarisasi ) AS name_polarisasi";

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