<?php

function h_ftp_open($file_name='') {
    $CI =& get_instance();

    $CI->load->library('ftp');                
    $ftp_config['hostname'] = 'ftp-01.gmf-aeroasia.co.id'; 
    $ftp_config['username'] = 'usergmf';
    $ftp_config['password'] = 'aeroasia';
    $ftp_config['debug']    = TRUE;
    $CI->load->library('upload');
    $CI->ftp->connect($ftp_config);
}

function h_ftp_upload($folder='',$file='',$tmp_name='') {

    $CI =& get_instance();

    h_ftp_open();

    //make folder
    if($CI->ftp->list_files('/'.$folder)==''){                
       $CI->ftp->mkdir('/'.$folder);
    };
    $CI->ftp->changedir('/'.$folder);
    //$send = $CI->ftp->upload($sourceFileName,$destination,'auto',0775);
    $send = $CI->ftp->upload($tmp_name,$file,'auto');
    $CI->ftp->close();

    return $send;
}

function h_ftp_delete_file($folder='',$file='') {
    $CI =& get_instance();
    h_ftp_open();
    $CI->ftp->changedir('/'.$folder);
    $CI->ftp->delete_file($file);
    $CI->ftp->close();
    return true;
}



function h_random_char($length=10) {
    $str = "";
    $characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
    $max = count($characters) - 1;
    for ($i = 0; $i < $length; $i++) {
        $rand = mt_rand(0, $max);
        $str .= $characters[$rand];
    }
    return $str;
}
