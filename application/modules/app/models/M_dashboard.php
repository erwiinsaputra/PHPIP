<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_dashboard extends CI_Model {

    public function __construct(){
        parent::__construct();
    }

    public function select($select='', $year=''){

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
        $sel['name_kpi_so']         = "a.name AS name_kpi_so";
        $sel['code_kpi_so']         = "a.code AS code_kpi_so";
        $sel['code']                = "a.code";
        $sel['description']         = "a.description";
        $sel['polarisasi']          = "a.polarisasi";
        $sel['pic_kpi_so']          = "a.pic_kpi_so";
        $sel['ukuran']              = "a.ukuran";
        $sel['frekuensi_pengukuran']= "a.frekuensi_pengukuran";

        if(@$year != ''){ $where_year = "AND b.year = ".$year; }else{ $where_year='';}
        $sel['arr_target']          = "(SELECT STRING_AGG(b.target::character varying, ',')
                                        FROM m_kpi_so_target_year b 
                                        WHERE b.id_kpi_so = a.id  ".$where_year."
                                    ) AS arr_target";
        $sel['arr_target_from']     = "(SELECT STRING_AGG(b.target_from::character varying, ',')
                                        FROM m_kpi_so_target_year b 
                                        WHERE b.id_kpi_so = a.id  ".$where_year."
                                    ) AS arr_target_from";
        $sel['arr_target_to']       = "(SELECT STRING_AGG(b.target_to::character varying, ',')
                                        FROM m_kpi_so_target_year b 
                                        WHERE b.id_kpi_so = a.id  ".$where_year."
                                    ) AS arr_target_to";
        // $sel['arr_target']          = "(SELECT b.target FROM m_kpi_so_target_year b WHERE b.id_kpi_so = a.id ) AS arr_target";

        $sel['id_perspective']      = "a.id_perspective";
        $sel['id_periode']          = "a.id_periode";
        $sel['id_bsc']              = "a.id_bsc";
        $sel['id_so']               = "a.id_so";
        
        $sel['name_pic_kpi_so']     = "(SELECT DISTINCT STRING_AGG ( b.\"SINGKATAN_POSISI\" :: CHARACTER VARYING, ',' )
                                        FROM \"ERP_STO_REAL\" b  
                                        WHERE b.\"POSITION_ID\" ::text = ANY (string_to_array(a.pic_kpi_so,', ')::text[])
                                        ) AS name_pic_kpi_so";
        $sel['name_perspective']    = "(SELECT b.name FROM m_perspective b WHERE b.id = a.id_perspective ) AS name_perspective";
        $sel['name_periode']        = "(SELECT CONCAT(b.start_year,'-',b.end_year) FROM m_periode b WHERE b.id = a.id_periode ) AS name_periode";
        $sel['name_bsc']            = "(SELECT b.name FROM m_bsc b WHERE b.id = a.id_bsc ) AS name_bsc";
        $sel['name_so']             = "(SELECT b.name FROM m_so b WHERE b.id = a.id_so ) AS name_so";
        $sel['code_so']             = "(SELECT b.code FROM m_so b WHERE b.id = a.id_so ) AS code_so";
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

    public function select_strategic_theme($select='', $year=''){

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
        $sel['name_sr']             = "a.name AS name_sr";
        $sel['code_sr']             = "a.code AS code_sr";
        $sel['code']                = "a.code";
        $sel['description']         = "a.description";
        $sel['polarisasi']          = "a.polarisasi";
        $sel['target']              = "a.target";
        $sel['pic_sr']              = "a.pic_sr";
        $sel['ukuran']              = "a.ukuran";
        $sel['indikator']          = "a.indikator";
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
        // $sel['arr_target']          = "(SELECT b.target FROM m_sr_target_year b WHERE b.id_sr = a.id ) AS arr_target";

        $sel['id_perspective']      = "a.id_perspective";
        $sel['id_periode']          = "a.id_periode";
        $sel['id_bsc']              = "a.id_bsc";
        $sel['id_strategic_theme']  = "a.id_strategic_theme";
        
        $sel['name_pic_sr']     = "(SELECT DISTINCT STRING_AGG ( b.\"SINGKATAN_POSISI\" :: CHARACTER VARYING, ',' )
                                        FROM \"ERP_STO_REAL\" b  
                                        WHERE b.\"POSITION_ID\" ::text = ANY (string_to_array(a.pic_sr,', ')::text[])
                                        ) AS name_pic_sr";
        $sel['name_perspective']    = "(SELECT b.name FROM m_perspective b WHERE b.id = a.id_perspective ) AS name_perspective";
        $sel['name_periode']        = "(SELECT CONCAT(b.start_year,'-',b.end_year) FROM m_periode b WHERE b.id = a.id_periode ) AS name_periode";
        $sel['name_bsc']            = "(SELECT b.name FROM m_bsc b WHERE b.id = a.id_bsc ) AS name_bsc";
        $sel['name_strategic_theme'] = "(SELECT b.name FROM m_strategic_theme b WHERE b.id = a.id_strategic_theme ) AS name_strategic_theme";
        $sel['code_strategic_theme'] = "(SELECT b.code FROM m_strategic_theme b WHERE b.id = a.id_strategic_theme ) AS code_strategic_theme";
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