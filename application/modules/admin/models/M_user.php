<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_user extends CI_Model {

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
        $sel['status']          = "a.status";

        $sel['role_id']         = "a.role_id";
        $sel['username']        = "a.username";
        $sel['password']        = "a.password";
        $sel['fullname']        = "a.fullname";
        $sel['email']           = "a.email";
        $sel['contact']         = "a.contact";
        $sel['photo']           = "a.photo";
        $sel['nip']             = "a.nip";
        $sel['title']           = "a.title";
        $sel['department']      = "a.department";
        $sel['company']         = "a.company";
        $sel['office']          = "a.office";

        $sel['role_name']     = "(SELECT DISTINCT STRING_AGG ( b.\"name\" :: CHARACTER VARYING, ',' )
                                        FROM \"sys_role\" b  
                                        WHERE b.\"id\" ::text = ANY (string_to_array(a.role_id,', ')::text[])
                                        ) AS role_name";
        // $sel['name_role']     = "(SELECT b.name FROM m_status b WHERE b.id = a.polarisasi ) AS name_role";

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