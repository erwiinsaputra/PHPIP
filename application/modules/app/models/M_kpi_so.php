<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_kpi_so extends CI_Model {

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
        $sel['id_kpi_so']           = "a.id AS id_kpi_so";
        $sel['is_active']           = "a.is_active";
        $sel['created_date']        = "a.created_date";
        $sel['created_by']          = "a.created_by";
        $sel['updated_date']        = "a.updated_date";
        $sel['updated_by']          = "a.updated_by";
        $sel['status_kpi_so']       = "a.status_kpi_so";

        $sel['name']                = "a.name";
        $sel['name_kpi_so']         = "a.name AS name_kpi_so";
        $sel['code_kpi_so']         = "a.code AS code_kpi_so";
        $sel['code']                = "a.code";
        $sel['description']         = "a.description";
        $sel['polarisasi']          = "a.polarisasi";
        $sel['pic_kpi_so']          = "a.pic_kpi_so";
        $sel['ukuran']              = "a.ukuran";
        $sel['frekuensi_pengukuran']= "a.frekuensi_pengukuran";

        $sel['arr_target']          = "(SELECT STRING_AGG(b.target::character varying, ',' ORDER BY b.year)
                                        FROM m_kpi_so_target_year b 
                                        WHERE b.id_kpi_so = a.id 
                                    ) AS arr_target";
        $sel['arr_target_from']     = "(SELECT STRING_AGG(b.target_from::character varying, ',' ORDER BY b.year)
                                        FROM m_kpi_so_target_year b 
                                        WHERE b.id_kpi_so = a.id 
                                    ) AS arr_target_from";
        $sel['arr_target_to']       = "(SELECT STRING_AGG(b.target_to::character varying, ',' ORDER BY b.year)
                                        FROM m_kpi_so_target_year b 
                                        WHERE b.id_kpi_so = a.id 
                                    ) AS arr_target_to";
        // $sel['arr_target']          = "(SELECT b.target FROM m_kpi_so_target_year b WHERE b.id_kpi_so = a.id ) AS arr_target";
        
        $sel['id_perspective']      = "a.id_perspective";
        $sel['id_bsc']              = "a.id_bsc";
        $sel['id_so']               = "a.id_so";
        $sel['start_date']          = "a.start_date";
        $sel['end_date']            = "a.end_date";
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
        $sel['name_status_kpi_so']  = "(SELECT b.name FROM m_status b WHERE b.type='SO STATUS' AND b.id = a.status_kpi_so ) AS name_status_kpi_so";

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