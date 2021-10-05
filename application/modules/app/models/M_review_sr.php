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

        $sel['indikator']           = "a.indikator";
        $sel['polarisasi']          = "a.polarisasi";
        $sel['ukuran']              = "a.ukuran";
        $sel['target']              = "(CASE WHEN a.target IS NULL THEN CONCAT (a.target_from, ' - ', a.target_to) ELSE a.target END) AS target";

        $sel['name']                = "a.name";
        $sel['code']                = "a.code";
        $sel['description']         = "a.description";
        $sel['id_strategic_theme']  = "a.id_strategic_theme";
        $sel['id_periode']          = "a.id_periode";

        $sel['id_periode']          = "a.id_periode";
        $sel['id_bsc']              = "a.id_bsc";
        $sel['id_strategic_theme']  = "a.id_strategic_theme";
        $sel['pic_sr']              = "a.pic_sr";
        $sel['status_sr']           = "a.status_sr";

        $sel['name_periode']        = "(SELECT CONCAT(b.start_year,'-',b.end_year) FROM m_periode b WHERE b.id = a.id_periode ) AS name_periode";
        $sel['start_year']          = "(SELECT b.start_year FROM m_periode b WHERE b.id = a.id_periode ) AS start_year";
        $sel['end_year']            = "(SELECT b.end_year FROM m_periode b WHERE b.id = a.id_periode ) AS end_year";
        $sel['name_bsc']            = "(SELECT b.name FROM m_bsc b WHERE b.id = a.id_bsc ) AS name_bsc";
        $sel['name_strategic_theme']    = "(SELECT b.name FROM m_strategic_theme b WHERE b.id = a.id_strategic_theme ) AS name_strategic_theme";
        $sel['name_pic_sr']         = "(SELECT DISTINCT STRING_AGG ( b.\"SINGKATAN_POSISI\" :: CHARACTER VARYING, ',' )
                                        FROM \"ERP_STO_REAL\" b  
                                        WHERE b.\"POSITION_ID\" ::text = ANY (string_to_array(a.pic_sr,', ')::text[])
                                        ) AS name_pic_sr";
        $sel['name_status_sr']      = "(SELECT b.name FROM m_status b WHERE b.type='SO STATUS' AND b.id = a.status_sr ) AS name_status_sr";



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


    public function select_target_month($select=''){

        if(!is_array($select)){ 
            if($select != '*'){
                $select = array($select);
            }
        }

        $sel = [];
        
        $sel['id']                  = "a.id";
        $sel['id_sr']               = "a.id_sr";
        $sel['year']                = "a.year";
        $sel['month']               = "a.month";
        $sel['target']              = "a.target";
        $sel['target_from']         = "a.target_from";
        $sel['target_to']           = "a.target_to";
        $sel['pencapaian']          = "a.pencapaian";
        $sel['realisasi']           = "a.realisasi";
        $sel['recommendations']     = "a.recommendations";
        $sel['keterangan']          = "a.keterangan";
        $sel['color']               = "a.color";
        $sel['start_date']          = "a.start_date";
        $sel['end_date']            = "a.end_date";

        $sel['description']         = "a.description";
        $sel['is_active']           = "a.is_active";
        $sel['created_date']        = "a.created_date";
        $sel['created_by']          = "a.created_by";
        $sel['updated_date']        = "a.updated_date";
        $sel['updated_by']          = "a.updated_by";

        $sel['color_name']          = "(SELECT b.color FROM m_color b WHERE b.id = a.color ) AS color_name";


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