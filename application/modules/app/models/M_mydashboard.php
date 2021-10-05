<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_mydashboard extends CI_Model {

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
        $sel['element']             = "a.element";
        $sel['description']         = "a.description";
        $sel['request_by']          = "a.request_by";
        $sel['request_date']        = "a.request_date";
        $sel['review_by']           = "a.review_by";
        $sel['review_date']         = "a.review_date";
        $sel['review_status']       = "a.review_status";
        $sel['redirect_page']       = "a.redirect_page";

        $sel['name_review_status']  = "(SELECT b.name_color FROM m_status b WHERE b.id = a.review_status ) AS name_review_status";
        $sel['name_request_by']     = "(SELECT b.fullname FROM sys_user b WHERE b.id = a.request_by ) AS name_request_by";
        $sel['name_review_by']      = "(SELECT b.fullname FROM sys_user b WHERE b.id = a.review_by ) AS name_review_by";
        $sel['name_request_to']     = "(SELECT b.fullname FROM sys_user b WHERE b.nip = a.nip ) AS name_request_to";
       
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