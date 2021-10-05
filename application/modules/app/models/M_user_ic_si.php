<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_user_ic_si extends CI_Model {

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

        $sel['id']              = "a.id";
        $sel['is_active']       = "a.is_active";
        $sel['created_date']    = "a.created_date";
        $sel['created_by']      = "a.created_by";
        $sel['updated_date']    = "a.updated_date";
        $sel['updated_by']      = "a.updated_by";

        $sel['nip']             = "a.nip";
        $sel['id_si']           = "a.id_si";

        $sel['name_si']         = "(SELECT DISTINCT STRING_AGG ( b.\"name\" :: CHARACTER VARYING, ',' )
                                        FROM \"m_si\" b  
                                        WHERE b.\"id\" ::text = ANY (string_to_array(a.id_si,', ')::text[])
                                        ) AS name_si";

        $sel['code']            = "(SELECT DISTINCT STRING_AGG ( b.\"code\" :: CHARACTER VARYING, ',' )
                                        FROM \"m_si\" b  
                                        WHERE b.\"id\" ::text = ANY (string_to_array(a.id_si,', ')::text[])
                                        ) AS code_si";
        $sel['fullname']        = "(SELECT b.fullname FROM sys_user b WHERE b.nip = a.nip ) AS fullname";
        $sel['role_name']       = "(SELECT DISTINCT STRING_AGG ( b.\"name\" :: CHARACTER VARYING, ',' )
                                    FROM \"sys_role\" b 
                                    WHERE b.\"id\" IN(5,9)
                                    AND b.\"id\" ::text = ANY (string_to_array((SELECT c.role_id FROM sys_user c WHERE c.nip = a.nip ),', ')::text[])
                                ) AS role_name";
        $sel['role_id']         = "(SELECT c.role_id FROM sys_user c WHERE c.nip = a.nip ) AS role_id";
        $sel['name_position']   = "(SELECT concat(c.\"SINGKATAN_POSISI\",'<br>(', c.\"POSISI\",')') 
                                        FROM \"sys_user\" b 
                                        LEFT JOIN \"ERP_STO_REAL\" c ON c.\"POSITION_ID\"= b.\"position_id\"
                                        WHERE b.\"nip\" = a.\"nip\" ) AS name_position";

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