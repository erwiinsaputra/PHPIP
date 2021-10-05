<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_review_si extends CI_Model {

    public function __construct(){
        parent::__construct();
    }

    public function select($select='',$year='',$month='',$status_si_year=''){

        if(!is_array($select)){ 
            if($select != '*'){
                $select = array($select);
            }
        }

        $sel = [];

        $sel['id']                  = "a.id";
        $sel['id_si']               = "a.id_si";
        $sel['name']                = "(SELECT b.name FROM m_si b WHERE b.id = a.id_si) AS name";
        $sel['code']                = "(SELECT b.code FROM m_si b WHERE b.id = a.id_si) AS code";
        $sel['pic_si']              = "(SELECT b.pic_si FROM m_si b WHERE b.id = a.id_si) AS pic_si";
        $sel['id_bsc']              = "(SELECT b.id_bsc FROM m_si b WHERE b.id = a.id_si) AS id_bsc";
        $sel['status_si']           = "(SELECT b.status_si FROM m_si b WHERE b.id = a.id_si) AS status_si";
        $sel['name_pic_si']         = "(SELECT DISTINCT STRING_AGG ( b.\"SINGKATAN_POSISI\" :: CHARACTER VARYING, ',' )
                                        FROM \"ERP_STO_REAL\" b  
                                        WHERE b.\"POSITION_ID\" ::text = ANY (string_to_array((SELECT c.pic_si FROM m_si c WHERE c.id = a.id_si),', ')::text[])
                                        ) AS name_pic_si";
        $sel['name_status_si']      = "(SELECT b.name FROM m_si b LEFT JOIN m_status c ON c.id = b.status_si WHERE b.id = a.id_si ) AS name_status_si";
        $sel['name_bsc']            = "(SELECT c.name FROM m_si b LEFT JOIN m_bsc c ON c.id = b.id_bsc WHERE b.id = a.id_si) AS name_bsc";
        $sel['year']                = "a.year";
        $sel['month']               = "a.month";
        $sel['status_complete']     = "a.status_complete";
        $sel['complete_on_year']    = "a.complete_on_year";
        $sel['overall_complete']    = "a.overall_complete";
        
        $sel['name_color']          = "(SELECT b.color FROM m_color b WHERE b.id = a.color) AS name_color";
        $sel['code_color']          = "(SELECT b.code FROM m_color b WHERE b.id = a.color) AS code_color";
        $sel['name_status_complete']= "(SELECT b.name FROM m_status b WHERE b.id = a.status_complete) AS name_status_complete";

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

    public function select_kpi_so($select='', $year=''){

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
        $sel['name_kpi_so']         = "a.name AS name_kpi_so";
        $sel['code']                = "a.code";
        $sel['code_kpi_so']         = "a.code AS code_kpi_so";
        $sel['pic_kpi_so']          = "a.pic_kpi_so";
        $sel['polarisasi']          = "a.polarisasi";
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

        $sel['id_perspective']      = "a.id_perspective";
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

}