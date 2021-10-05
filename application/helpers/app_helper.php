<?php



function h_role_admin()
{
    $role = array('1','7');
    return $role;
}

function h_user_tambahan()
{
    $id = '1,2,3,4,5,6,7';
    return $id;
}


function h_format_angka($angka=0)
{
    if($angka == ''){ return $angka;}
    $new = round(@$angka,2);
    if($new < 1000){
        $new = floatval($new);
    }else{
        $new = number_format($new);
    }
    return $new;
}

function h_number($angka=0)
{
    $angka = str_replace(',','',@$angka);
    $angka = str_replace(' ','',$angka);
    $angka = str_replace('%','',$angka);
    return $angka;
}

function h_pembagian($val_1=0,$val_2=0){
   
    //selain 0/0
    if ($val_1 == '') {$val_1 = 0;}
    if ($val_2 == '') {$val_2 = 0;}
    if ($val_2 == 0) {
        if ($val_1 <> '') {
            $divider = $val_1;
        }else {
            $divider =  1;
        }
    }else { 
        $divider = $val_2;
    }
    //apabila nilai total target 0
    if ($val_2 > 0) {
        $percent = ($val_1 / $divider);
    }else{
        $percent = 0;
    }
    // $percent = ($val_1 / $divider) * 100;
    $percent = (is_float($percent) ? floatval(sprintf('%.2f', $percent)) : intval($percent));
    return $percent;
}

function search()
{
    $m = floatVal(date('m'));
    if($m <= 3){ $triwulan = 1; }
    if($m > 3 && $m <= 6){ $triwulan = 2; }
    if($m > 6 && $m <= 9){ $triwulan = 3; }
    if($m > 9 && $m <= 12){ $triwulan = 4; }
    return $triwulan;
}


function h_text_utf8($str = '')
{
    $str = iconv('UTF-8', 'ISO-8859-1//IGNORE', $str);
    $str = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $str);
    return $str;
}

function h_triwulan_now()
{
    $m = floatVal(date('m'));
    if($m > 0 && $m <= 3){ $triwulan = 1; }
    if($m > 3 && $m <= 6){ $triwulan = 2; }
    if($m > 6 && $m <= 9){ $triwulan = 3; }
    if($m > 9 && $m <= 12){ $triwulan = 4; }
    return $triwulan;
}

function h_quarter_name($month='')
{
    
    if($month >= 1 && $month<= 3){ $q = 1; }
    if($month >= 4 && $month<= 6){ $q = 2; }
    if($month >= 7 && $month<= 9){ $q = 3; }
    if($month >= 10 && $month<= 12){ $q = 4; }
    return $q;
}

function h_text_space_to_br($txt='')
{
    $pecah = explode(' ',$txt);
    $new_text = '';
    foreach($pecah as $val){
        if(!preg_match("/^[a-zA-Z0-9]*$/", $val)){
            $new_text .=  ' '.$val.' ';
        }else{
            $new_text .=  $val.'<br>';
        }
    }
        
    return $new_text;
}


function h_pencapaian($realisasi=0,$target=0,$polarisasi=0)
{

     //target
    $target = str_replace(',','',$target);
    if($target == ''){ $target = '0';}

    //realisasi
    $realisasi = str_replace(',','',$realisasi);
        
    //jika target 0
    if($target == '0'){ 
        $target = '1'; 
        $realisasi = $realisasi+1; 
    }

    //cek polarisasi
    //stabilize
    if($polarisasi == '10'){ 
        $pecah = explode(' - ',$target);
        $from = $pecah[0];
        $to = pecah[1];
        if($realisasi >= $from && $realisasi <= $to){
            $pencapaian = 100;
        }
        if($realisasi < $from){
            $pencapaian = ($realisasi/$from)*100;
        }
        if($realisasi > $to){
            $a = ($realisasi/$to);
            if($a >= 2){
                $pencapaian = 0;
            }else{
                $pencapaian = (2-$a)*100;
            }
        }
    }
    //minimum
    if($polarisasi == '9'){ 
        $a = ($realisasi/$target);
        if($a >= 2){
            $pencapaian = 0;
        }else{
            $pencapaian = (2-$a)*100;
        }
    }
    //maximum
    if($polarisasi == '8'){ 
        $pencapaian = ($realisasi/$target)*100;
    }

    $pencapaian = number_format($pencapaian,2,".","");
    $pencapaian = str_replace('.00','',$pencapaian);
   
    return $pencapaian;
}

function h_color_pencapaian($pencapaian=0)
{
    $CI =& get_instance();
    //bulatkan kebawah
    if($pencapaian != 0 ){ 
        $pencapaian = floor($pencapaian);
    }
    $where_color = "a.\"nilai_awal\"::NUMERIC <= '".floor($pencapaian)."'::NUMERIC AND a.\"nilai_akhir\"::NUMERIC >= '".floor($pencapaian)."'::NUMERIC";
    $color = @$CI->m_global->getDataAll('m_color AS a', NULL,$where_color,"id")[0]->id;
    return $color;
}


function h_localhost($pencapaian=0)
{
    $localhost = array(
        '127.0.0.1',
        '::1',
        'localhost'
    );
    if(in_array($_SERVER['REMOTE_ADDR'], $localhost)){
        $result = TRUE;
    }else{
        $result = FALSE;
    }
    return $result;
}


function h_nilai_index($val='')
{
    if($val == '1A'){ $val = 1;}
    if($val == '1B'){ $val = 2;}
    if($val == '1C'){ $val = 3;}
    if($val == '2A'){ $val = 4;}
    if($val == '2B'){ $val = 5;}
    if($val == '2C'){ $val = 6;}
    if($val == '3A'){ $val = 7;}
    if($val == '3B'){ $val = 8;}
    if($val == '3C'){ $val = 9;}
    return $val;
}

function h_format_angka_excel($val='')
{
    $new_val = str_replace('Rp','',$val);
    $new_val = str_replace(',','',$new_val);
    return $new_val;
}


function h_strip_html_tags($str){
    $str = preg_replace('/(<|>)\1{2}/is', '', $str);
    $str = preg_replace(
        array(// Remove invisible content
            '@<head[^>]*?>.*?</head>@siu',
            '@<style[^>]*?>.*?</style>@siu',
            '@<script[^>]*?.*?</script>@siu',
            '@<noscript[^>]*?.*?</noscript>@siu',
            ),
        "", //replace above with nothing
        $str );
    $str = h_replaceWhitespace($str);
    $str = strip_tags($str);
    return $str;
}

function h_replaceWhitespace($str) {
    $result = $str;
    foreach (array(
        "  ", " \t",  " \r",  " \n",
        "\t\t", "\t ", "\t\r", "\t\n",
        "\r\r", "\r ", "\r\t", "\r\n",
        "\n\n", "\n ", "\n\t", "\n\r",
    ) as $replacement) {
        $result = str_replace($replacement, $replacement[0], $result);
    }
    return $str !== $result ? h_replaceWhitespace($result) : $result;
}

function h_kirim_email_ip($from=null, $to=null, $cc=null, $bcc=null, $title=null, $subject=null, $html=null){
    $CI =& get_instance();

    //config email
    $config = Array(
        'protocol'  => 'smtp',
        
        //SMTP 
        'smtp_host' => '192.168.10.105',
        'smtp_port' => 25,
        'smtp_user' => '',
        'smtp_pass' => '',

        'mailtype'  => 'html',
        'charset'   => 'iso-8859-1',
        'wordwrap'  => TRUE
    );

    $CI->load->library('email', $config);
    $CI->email->set_newline("\r\n");
    $CI->email->set_crlf("\r\n");

    if($cc != null){  $CI->email->cc($cc);}       
    if($bcc != null){  $CI->email->bcc($bcc); }       
    $CI->email->from($from, $title);
    $CI->email->to($to);
    $CI->email->subject($subject);
    $CI->email->message($html);
    $CI->email->send();

    // echo $CI->email->print_debugger();
}

function h_email_to($email=''){

    // if($email == ''){
        $email = 'fds.firdaus.1@gmail.com';
    // $email = 'Abdi.Surya@indonesiapower.co.id';
    // }

    return $email;
}

function h_email_admin(){

    $email = 'fds.firdaus.1@gmail.com';

    return $email;
}

    



?>