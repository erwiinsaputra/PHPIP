<?php

function h_role_name($role_id='') {
    $CI =& get_instance();
     
    if($role_id == ''){
        $role_id = $CI->session->userdata('USER')['ROLE_ID'];
    }

    //select role name
    $query  = "SELECT id,name FROM sys_role ORDER BY name ASC";
    $arr_role = $CI->db->query($query)->result();

    foreach ($arr_role as $row) {
        $role_name[@$row->id] = @$row->name;
    }

    //result
    if($role_id == 'array') return $role_name;
    if($role_id == '') return '';
    return @$role_name[$role_id];
}

function h_role_folder($role_id='') {
    $CI =& get_instance();
    if($role_id == ''){
        $role_id = $CI->session->userdata('USER')['ROLE_ID'];
    }

    //select role name
    $query  = "SELECT id, folder FROM sys_role ORDER BY name ASC";
    $arr_role = $CI->db->query($query)->result();
    foreach ($arr_role as $row) {
        $role_name[$row->id] = $row->folder;
    }

    //result
    if($role_id == '') return '';
    return $role_name[$role_id];
}

function h_replace_file_name($name) {
    $name = str_replace(' ', '_', $name); 
    $name = str_replace('"', '', $name); 
    $name = str_replace("'", '', $name); 
    return $name;
}

function h_file_type($name) {
    $pecah = explode('.',$name);
    $key = max(array_keys($pecah));
    $result = $pecah[$key];
    return $result;
}



function h_pass_global() {
    return "simo123";
}


function h_session($val) {
    $CI =& get_instance();
    // echo '<pre>';print_r($CI->session->userdata('USER'));exit;
    if($val == 'USER_ID'){
        $res = $CI->session->userdata('USER')['USER_ID'];
    }elseif($val == 'NAME'){
        $res = $CI->session->userdata('USER')['NAME'];
    }elseif($val == 'USERNAME'){
        $res = $CI->session->userdata('USER')['USERNAME'];
    }elseif($val == 'ROLE_ID'){
        $res = $CI->session->userdata('USER')['ROLE_ID'];
    }elseif($val == 'ROLE_NAME'){
        $res = $CI->session->userdata('USER')['ROLE_NAME'];
    }elseif($val == 'JUM_ROLE'){
        $res = @$CI->session->userdata('USER')['JUM_ROLE'];
    }elseif($val == 'ARR_ROLE_NAME'){
        $res = @$CI->session->userdata('USER')['ARR_ROLE_NAME'];
    }elseif($val == 'ARR_ROLE_ID'){
        $res = @$CI->session->userdata('USER')['ARR_ROLE_ID'];
    }elseif($val == 'NIP'){
        $res = $CI->session->userdata('USER')['NIP'];
    }elseif($val == 'EMAIL'){
        $res = $CI->session->userdata('USER')['EMAIL'];
    }elseif($val == 'CONTACT'){
        $res = $CI->session->userdata('USER')['CONTACT'];
    }elseif($val == 'PHOTO'){
        $res = $CI->session->userdata('USER')['PHOTO'];
    }elseif($val == 'TITLE'){
        $res = $CI->session->userdata('USER')['TITLE'];
    }elseif($val == 'DEPARTMENT'){
        $res = $CI->session->userdata('USER')['DEPARTMENT'];
    }elseif($val == 'OFFICE'){
        $res = $CI->session->userdata('USER')['OFFICE'];
    }elseif($val == 'COMPANY'){
        $res = $CI->session->userdata('USER')['COMPANY'];
    }elseif($val == 'POSITION_ID'){
        $res = $CI->session->userdata('USER')['POSITION_ID'];
    }else{
        $res = '';
    }

    return $res;
}



function h_month_english($val='') {
    if($val > 0){
        $key = array(
            '1' =>  'January',
            '2' =>  'February',
            '3' =>  'March',
            '4' =>  'April',
            '5' =>  'May',
            '6' =>  'June',
            '7' =>  'July',
            '8' =>  'August',
            '9' =>  'September',
            '10' => 'October' ,
            '11' => 'November' ,
            '12' => 'December' 
        );
    }else{
        $key = array(
            'January' => '1' ,
            'February' => '2' ,
            'March' => '3' ,
            'April' => '4' ,
            'May' => '5' ,
            'June' => '6' ,
            'July' => '7' ,
            'August' => '8' ,
            'September' => '9' ,
            'October' => '10' ,
            'November' => '11' ,
            'December' => '12' 
       );
    }
    
    if($val == ''){
        return $key;
    }else{
        return $key[$val];
    }
}


function h_format_date($date='', $format='') {
   
    if($date == NULL){
        $date = NULL;
    } else

    if($date =='0000-00-00'){
        $date = NULL;
    } else 

    if($date == ''){ 
        $date = "";
    }else{
        $date = date($format,strtotime($date));
    }

    return $date; 
}

function h_insert_token($type='',$username='', $day='30') {
    $CI =& get_instance();
    $date_now = date('Y-m-d H:i:s');
    $end_date = date('Y-m-d H:i:s', strtotime($date_now." +".$day." days"));
    $token = md5(md5(date('Ymdhis').rand(1,1000)));
    $arr   = array(
            'token_code'            => $token,
            'token_type'            => $type,
            'token_start_date'      => $date_now,
            'token_end_date'        => $end_date,
            'token_username'        => $username,
    );
    $CI->db->insert('sys_token', $arr);
    return $token;
}