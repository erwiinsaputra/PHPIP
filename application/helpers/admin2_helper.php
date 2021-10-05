<?php

function createRandomPassword($length) {
    $chars = "234567890abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $i = 0;
    $password = "";
    while ($i <= $length) {
        $password .= $chars{mt_rand(0,strlen($chars)-1)};
        $i++;
    }
    return $password;
}

function random_word($length = 5) {
    $chars = "1234567890abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $i = 0;
    $captcha = "";
    while ($i < $length) {
        $captcha .= $chars{mt_rand(0,strlen($chars)-1)};
        $i++;
    }
    return $captcha;
}

function getSetting($find, $source='session')
{
    $CI =& get_instance();
    if($source=='session'){
        $USER_SETTINGS = $CI->session->userdata('USER_SETTINGS');
    }else if($source=='table'){
        $sql = "SELECT * FROM m_settingS WHERE OWNER = '".$CI->session->userdata('PEG_NIP_BARU')."'";
        $USER_SETTINGS = $CI->db->query($sql)->result();
    }
	foreach ($USER_SETTINGS as $item){
	 	if($item->SETTING == $find){ return $item->VALUE;}
	} 
}
/** Get setting value for current cursor user
 * 
 * @return setting value
 */
function getUserSetting($find, $USER_SETTINGS)
{
    foreach ($USER_SETTINGS as $item){
        if($item->SETTING == $find){ return $item->VALUE;}
    }   
}
/** Get setting value for current cursor user
 * 
 * @return setting value
 */
function getOwnerSetting($SETTING, $OWNER)
{
    $CI =& get_instance();
    $sql = "SELECT *
        FROM m_settingS
        WHERE SETTING LIKE '".$SETTING."' AND OWNER LIKE '".$OWNER."'";
    $USER_SETTINGS = $CI->db->query($sql);
    if ($USER_SETTINGS->num_rows() > 0)
    {
        foreach ($USER_SETTINGS->result() as $item){
            if($item->SETTING == $SETTING && $item->OWNER == $OWNER){ return $item->VALUE;}
        }
    }
    return 0;  
}


/// start class ng
class ng{
    /** 
     * generate pagination
     * 
     * @return config pagination
     */
    static public function genPagination(){
        $CI =& get_instance();    
        $CI->load->library('pagination');
        $config['base_url']         = $CI->base_url;
        $config['total_rows']       = @$CI->total_rows;
        $config['per_page']         = $CI->limit;
        $config['uri_segment']      = $CI->uri_segment;
        $config['full_tag_open']    = '<ul class="pagination tsc_pagination tsc_paginationA tsc_paginationA01">';
        $config['full_tag_close']   = '</ul>';
        $config['prev_link']        = '← Previous';
        $config['prev_tag_open']    = '<li>';
        $config['prev_tag_close']   = '</li>';
        $config['next_link']        = 'Next →';
        $config['next_tag_open']    = '<li>';
        $config['next_tag_close']   = '</li>';
        $config['cur_tag_open']     = '<li class="active"><a href="'.$_SERVER['PHP_SELF'].'">';
        $config['cur_tag_close']    = '</a></li>';
        $config['num_tag_open']     = '<li>';
        $config['num_tag_close']    = '</li>';
        $config['first_tag_open']   = '<li>';
        $config['first_tag_close']  = '</li>';
        $config['last_tag_open']    = '<li>';
        $config['last_tag_close']   = '</li>';
        $config['num_links'] = 5;
        $CI->pagination->initialize($config); 
        return $CI->pagination->create_links();      
    }
    /** 
     * generate pagination
     * 
     * @return config pagination frontend
     */
    static public function genPaginationFront(){
        $CI =& get_instance();    
        $CI->load->library('pagination');
        $config['base_url']         = $CI->base_url;
        $config['total_rows']       = @$CI->total_rows;
        $config['per_page']         = $CI->limit;
        $config['uri_segment']      = $CI->uri_segment;
        $config['full_tag_open']    = '<ul class="pagination">';
        $config['full_tag_close']   = '</ul>';
        $config['prev_link']        = '← Previous';
        $config['prev_tag_open']    = '<li class="page">';
        $config['prev_tag_close']   = '</li>';
        $config['next_link']        = 'Next →';
        $config['next_tag_open']    = '<li class="page">';
        $config['next_tag_close']   = '</li>';
        $config['cur_tag_open']     = '<li class="page active"><a href="'.$_SERVER['PHP_SELF'].'">';
        $config['cur_tag_close']    = '</a></li>';
        $config['num_tag_open']     = '<li class="page">';
        $config['num_tag_close']    = '</li>';
        $config['first_tag_open']   = '<li>';
        $config['first_tag_close']  = '</li class="page">';
        $config['last_tag_open']    = '<li class="page">';
        $config['last_tag_close']   = '</li>';
        $config['num_links'] = 5;
        $CI->pagination->initialize($config); 
        return $CI->pagination->create_links();      
    }

    /** 
     * generate breadcrumb
     * 
     * @return html breadcrumb
     */
    static public function genBreadcrumb($li){
        $breadcrumb = '';
        if(is_array($li)){
            $c = count($li);
            $i=0;
            foreach ($li as $label => $url) {
                ++$i;
                if($label=='Dashboard'){$label = ' <i class="fa fa-home"></i> ';}
                $breadcrumb .= '<li><a href="'.$url.'" style="'.($i==$c?'font-weight:bold;':'').'">'.$label.'</a></li>';
            }
        }

        return '<ol class="breadcrumb">
                    '.$breadcrumb.'                    
                </ol>';
        return '<div class="row">
            <div id="breadcrumb" class="col-xs-12">
                <ol class="breadcrumb">
                    '.$breadcrumb.'                    
                </ol>
            </div>
        </div>';
    }
    /** 
     * generate upload
     * 
     * @return filename
     */    
    static public function genUpload($FILE, $infix, $id){
        $CI =& get_instance();
        $allowedExts = array("gif", "jpeg", "jpg", "png", "pdf", "doc", "docx");
        $temp = explode(".", $_FILES[$FILE]["name"]);
        $extension = end($temp);

        $prefix = $CI->session->userdata('INST_SATKERKD').'_'.$CI->session->userdata('UNITKERJA_IDK');

        $newName = $prefix. '_' .$infix. '_' .$id. '_' . $_FILES[$FILE]["name"];

        $type = array(
            "image/gif",
            "image/jpeg",
            "image/jpg",
            "image/pjpeg",
            "image/x-png",
            "image/png",
            "application/msword",
            "application/doc",
            "application/txt",
            "application/pdf",
            "text/pdf",
        );

        $maxsize = 200000000;

        if (in_array($_FILES[$FILE]["type"], $type) && ($_FILES[$FILE]["size"] < $maxsize) && in_array($extension, $allowedExts)) {
            if ($_FILES[$FILE]["error"] > 0) {
                // echo "Return Code: " . $_FILES[$FILE]["error"] . "<br>";
            } else {
                if (file_exists("upload/" . $newName)) {
                    // echo $_FILES[$FILE]["name"] . " already exists. ";
                } else {
                    // chmod('upload/', 0777);
                    move_uploaded_file($_FILES[$FILE]["tmp_name"], "./upload/" . $newName);
                }
                return $newName;
            }
        } else {
            // echo "Invalid file";
        }
        return '';
    }

    static public function islogin(){
        // return true;
        $CI =& get_instance();
        if($CI->session->userdata('logged_in')!=true || $CI->session->userdata('productkey')!=productkey){
            echo '<script language="javascript">location="' . base_url() . 'auth/";</script>';
            exit();
        }
    }

    static public function csstheme(){
        $CI =& get_instance();

        $sql = "SELECT *
        FROM `m_settingS` WHERE OWNER = 'oa' AND SETTING like 'theme%'";
        $query = $CI->db->query($sql);
        if($query->num_rows()>0){
            foreach ($query->result() as $row) {
                $theme[$row->SETTING] = json_decode($row->VALUE);
            }
        }

        if($CI->session->userdata('USR')=='admin'){; 
            $selectedTheme=$theme['theme.2'];
        }else{
            $selectedTheme=$theme['theme.3'];
        }
        ?>
        <style type="text/css">
            body, #sidebar-left, .main-menu .dropdown-menu{
              background: <?=$selectedTheme->colorSide;?> url(<?= base_url(); ?>images/devoops_pattern_b10.png) 0 0 repeat !important;
            }
            #logo{
              background: <?=$selectedTheme->colorLogo;?> url(<?= base_url(); ?>images/devoops_pattern_b10.png) 0 0 repeat !important;
            }
            #breadcrumb{
              background: <?=$selectedTheme->colorBreadcrumb;?> url(<?= base_url(); ?>images/devoops_pattern_b10.png) 0 0 repeat !important;
            }
            #dashboard_links .nav{
              background: <?=$selectedTheme->colorNav;?> url(<?= base_url(); ?>images/devoops_pattern_b10.png) 0 0 repeat !important;
            }
        </style>
        <?php
    }

    static public function dateindo($datetime){
        return date('d-m-Y', strtotime($datetime));
    }
    
    static public function datetimeindo($date){
        return date('d-m-Y H:i:s', strtotime($date));
    }

    static public function datetimesys($datetime){
        return date('Y-m-d H:i:s', strtotime($datetime));
    }

    static public function datesys($date){
        return date('Y-m-d', strtotime($date));
    }

    static public function exportDataTo($data, $mode='pdf', $view, $filename){
        $CI =& get_instance();
        $html = $CI->load->view($view, $data, TRUE);
        if($mode=='pdf'){
            $CI->load->library('pdf');
            $pdf = $CI->pdf->load();
            $pdf->SetFooter($_SERVER['HTTP_HOST'].'|{PAGENO}|'.date(DATE_RFC822)); // Add a footer for good measure <img src="https://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
            $pdf->WriteHTML($html); // write the HTML into the PDF
            // $pdf->Output($pdfFilePath, 'F'); // save to file because we can
            $pdf->Output();
        }else if($mode=='excel'){
            header("Content-type: application/vnd.ms-excel;charset=UTF-8"); 
            header("Content-Disposition: attachment; filename=\"".$filename.".xls");
            header("Cache-control: private");
            echo $html;
        }else if($mode=='word'){
            header("Content-type: application/vnd.ms-word;charset=UTF-8"); 
            header("Content-Disposition: attachment; filename=\"".$filename.".doc");
            header("Cache-control: private");
            echo $html;
        }        
    }

    static public function logActivity($activity){
        $CI =& get_instance();
        $data = array(
            'USERNAME'      => $CI->session->userdata('USR'),
            'ACTIVITY'      => $activity, 
            'CREATED_TIME'  => time(),
            'CREATED_BY'    => $CI->session->userdata('USR'),
            'CREATED_IP'    => $CI->input->ip_address(),        
        );
        $CI->db->insert('sys_user_activity', $data);
    }  
    static public function filesize_formatted($file)
    {
        if(substr($file, -1)=='/'){
            return '';
        }else if(!file_exists($file)){
            return '';
        }
        
        $bytes = filesize($file);

        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            return $bytes . ' bytes';
        } elseif ($bytes == 1) {
            return '1 byte';
        } else {
            return '0 bytes';
        }
    }
    static public function printDariSd($a, $b){
        if($b>0){
            echo ' Dari : '.$a.' s/d '.$b;
        }else{
            echo ' Tahun : '.$a;
        }
    }
    static public function text_filter($message, $type="") {
        if (intval($type) == 2) {
            $message = htmlspecialchars(trim($message), ENT_QUOTES);
        } else {
            $message = strip_tags(urldecode($message));
            $message = htmlspecialchars(trim($message), ENT_QUOTES);
        }

        return $message;
    }    
    static public function mail_send($to, $subject, $message, $from = null, $attach = null, $cc = null) {
        $CI =& get_instance();
        
        if(ENV_USE_LDAP == true){
            bkpm::mail_send($to, $subject, $message, $from = null, $attach = null, $cc = null);
        }else{
            // mail($to, $subject, $message, $headers);
            if ($from != null) {
                $from = $from;
            }else{
                $from = "noreply@bkpm.go.id";
            }
            
            $subject = ng::text_filter($subject);

            $header  = $CI->config->item('mail_server');
            $config['protocol'] = "smtp";
            $config['smtp_host'] = $CI->config->item('smtp_host'); 
            $config['smtp_port'] = $CI->config->item('smtp_port');
            $config['smtp_user'] = $CI->config->item('smtp_user');
            $config['smtp_pass'] = $CI->config->item('smtp_pass');
            $config['mailtype'] = 'html';
            $config['charset'] = 'utf-8';
            $config['newline'] = "\r\n";
            $config['wordwrap'] = TRUE;

            $CI->email->initialize($config);
            $CI->email->from($from, "Aplikasi NSWI");
            $CI->email->to($to);
            $CI->email->subject($subject);
            $CI->email->message($message);

            if(!is_null($attach)) {
                $CI->email->attach($attach);
            }
            if(!is_null($cc)) {
                $CI->email->cc($cc);
            }
            if (!$CI->email->send()) {
                // show_error($CI->email->print_debugger());
                return false;
            }
            else {
                return true;
            }
        }
    }    
}
/// end class ng

function number_rupiah($number){
    return number_format ( $number , 2 , "," , "." );
}

function provinsiKotaList($items, &$PROVINSI, &$KOTA){
    $CI =& get_instance();
    $IDPROV = array();
    $IDKOT = array();

    foreach ($items as $item_reff) {
        if($item_reff->PROVINSI && $item_reff->PROVINSI != '-- propinsi --'){
            $IDPROV[] = $item_reff->PROVINSI;
        }    
        if($item_reff->KOTA && $item_reff->KOTA != '-- kota --'){
            $IDKOT[] = $item_reff->PROVINSI.$item_reff->KOTA;
        }
    }

    $IDPROV = array_unique($IDPROV);
    $IDKOT = array_unique($IDKOT);

    if(count($IDPROV)){
        $sql = "SELECT * FROM M_AREA WHERE IDPROV IN (".implode(', ', $IDPROV).") AND IDKOT = ''";
        $areas =  $CI->db->query($sql)->result();
        foreach ($areas as $area) {
            $PROVINSI[$area->IDPROV] = (array) $area;
        }
    }

    if(count($IDKOT)){
        $sql = "SELECT * FROM M_AREA WHERE CONCAT(IDPROV,IDKOT) IN (".implode(', ', $IDKOT).") AND IDKEC = ''";
        $areas =  $CI->db->query($sql)->result();
        foreach ($areas as $area) {
            $KOTA[$area->IDPROV.$area->IDKOT] = (array) $area;
        }
    }
}

//function display($data, $die = false){
//    echo '<pre>';
//    print_r($data);
//    echo '</pre>';
//    ($die ? die : '');
//}
/* Upload SAVE FILE */
function save_file($file, $file_name, $file_size, $folder, $flag, $size)
{
	if($file!=''){
	  $ret['error'] = 0;
	  $pict = getimagesize($file);
		//if (!(($pict[2]==1) || ($pict[2]==2))) :
		$extension = strtolower(substr($file_name,-4));
		// if(!in_array($extension, array('.xls', '.pdf', 'docx','pptx','xlsx','.doc'))) :
		if(!in_array($extension, array('.jpg','.png','.jpeg','.pdf','.tiff','.doc','docx','.xls','xlsx'))) :
			$ret['error'] = 1;
			// $ret['msg'] = "Please, File ".$tail." must be xls,pdf,docx or GIF format...";
			$ret['msg'] = "Please, File ".$extension." must be xls,pdf,docx or GIF format...";
			return $ret;
			exit();
		endif;
	
	  if ($file == "none") :
			$ret['error'] = 1;
			$ret['msg'] = "Please, Fill file field...";
			return $ret;
			exit();
		endif;
		if ($flag) :
			if ($file_size >= $size*1024) :
				$ret['error'] = 1;
				$ret['msg'] = "File size too large. Maximum file size $size KB...";
				return $ret;
				exit();
			endif;
		endif;
		$name_file = time()."-". trim($file_name);
	
		if (!@copy ($file,$folder."/".$name_file)) :
			$ret['error'] = 1;
			$ret['msg'] = "Copy file failed. Please check the file $file_name... $file -> $folder/$name_file";
			return $ret;
			exit();
		endif;
	
		$ret['nama_file'] = $name_file;
		return $ret;
		exit();
	}
}


/** Print Role
 * 
 * @return html multiple role
 */
function printRole($ID_ROLE, $ROLE){
    $role = '';
    $arrRole = explode(',', $ID_ROLE);
    foreach ($arrRole as $key) {
        if(isset($ROLE[trim($key)])) {
            $role[] = '<span class="badge bg-' . $ROLE[trim($key)]['COLOR'] . '">' . $ROLE[trim($key)]['ROLE'] . '</span>';
        }
    }
    return implode(', ', $role);
}

function doresetpasswordAll($post){
    $type = $post['act'];
    if($type == 'dorepas1'){
        $CI =& get_instance();
        $CI->db->trans_begin();
        $CI->load->model('muser', '', TRUE);
        $data['USERNAME']              = $post['USERNAME'];
        $getUser                       = $CI->muser->getUser($data['USERNAME']);
        $data['REQUEST_RESET']         = '1';
        $data['REQUEST_RESET_KEY']     = md5($data['USERNAME'].date('Y-m-d H:i:s').'Mitreka');
        $data['REQUEST_RESET_TIME']    = date('Y:m:d H:i:s');
        $date                          = date('Y-m-d', strtotime(date('Y-m-d') . ' +1 day'));
        $data['REQUEST_RESET_EXPIRED'] = $date." ".date('H:i:s');
        $urlDate                       = $date."_".date('H:i:s');
        $CI->muser->updtResetPass($data);

        $CI->load->library('email');

        $config['protocol']     = 'smtp';
        $config['smtp_host']    = $CI->config->item('smtp_host');
        $config['useragent']    = "CodeIgniter";
        $config['mailpath']     = "/usr/bin/sendmail"; // or "/usr/sbin/sendmail"
        $config['smtp_port']    = $CI->config->item('smtp_port');
        $config['smtp_timeout'] = '7';
        $config['smtp_user']    = $CI->config->item('smtp_user');
        $config['smtp_pass']    = $CI->config->item('smtp_pass');
        $config['charset']      = 'utf-8';
        $config['newline']      = "\r\n";
        $config['mailtype']     = 'text'; // or html
        $config['validation']   = TRUE; // bool whether to validate email or not      

        $CI->email->initialize($config);
        $CI->email->set_newline("\r\n");
        $CI->email->from('apptester@mitrekasolusi.co.id','Mitreka (Admin NSWI)');
        $CI->email->to($getUser[0]->EMAIL); 

        $CI->email->subject('Reset Password User');
        $text = 'Link : '.base_url().'auth/resetpassword/'.$data['REQUEST_RESET_KEY'];
        $CI->email->message($text);  

        $send = $CI->email->send(); 

        if ($CI->db->trans_status() === FALSE){
            $CI->db->trans_rollback();
        }else{
            $CI->db->trans_commit();
        }

        $result = intval($CI->db->trans_status());

    }else{
        $CI =& get_instance();
        $CI->db->trans_begin();
        $CI->load->model('muser', '', TRUE);
        // $data['USERNAME']              = $post['username'];
        // $getUser                       = $CI->muser->getUser2($data['USERNAME']);

        $data = array();
        $data['REQUEST_RESET']         = '1';
        $data['REQUEST_RESET_KEY']     = md5($post['USERNAME'].date('Y-m-d H:i:s').'Mitreka');
        $data['REQUEST_RESET_TIME']    = date('H:i:s');
        $data['REQUEST_RESET_EXPIRED'] = date('Y-m-d', strtotime(date('Y-m-d') . ' +1 day'));
        $data['USERNAME']               = $post['USERNAME'];

        $CI->muser->updtResetPass($data);

        $CI->load->library('email');

        $config['protocol']     = 'smtp';
        $config['smtp_host']    = $CI->config->item('smtp_host');
        $config['useragent']    = "CodeIgniter";
        $config['mailpath']     = "/usr/bin/sendmail"; // or "/usr/sbin/sendmail"
        $config['smtp_port']    = $CI->config->item('smtp_port');
        $config['smtp_timeout'] = '7';
        $config['smtp_user']    = $CI->config->item('smtp_user');
        $config['smtp_pass']    = $CI->config->item('smtp_pass');
        $config['charset']      = 'utf-8';
        $config['newline']      = "\r\n";
        $config['mailtype']     = 'text'; // or html
        $config['validation']   = TRUE; // bool whether to validate email or not      

        $CI->email->initialize($config);
        $CI->email->set_newline("\r\n");
        $CI->email->from('apptester@mitrekasolusi.co.id','Mitreka (Admin NSWI)');
        $CI->email->to($post['EMAIL']); 

        $CI->email->subject('Reset Password User');
        $text = 'Link : http://localhost:8181/bkpm_public/auth/lupapassword';
        $CI->email->message($text);  

        $send = $CI->email->send(); 

        if ($CI->db->trans_status() === FALSE){
            $CI->db->trans_rollback();
        }else{
            $CI->db->trans_commit();
        }

        $result = intval($CI->db->trans_status());
    }
    return $result;
}
//
//function bulan($bulan)
//{
//    $aBulan = array('Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
//
//    return $aBulan[$bulan];
//}
//
//function tgl_format($tgl)
//{
//    $tanggal    = date('j', strtotime($tgl));
//    $bulan      = bulan( date('n', strtotime($tgl))-1 );
//    $tahun      = date('Y', strtotime($tgl));
//    return $tanggal.' '.$bulan.' '.$tahun;
//}

function getColor($num) {
    $hash = md5('color' . $num); // modify 'color' to get a different palette
    return array(
        hexdec(substr($hash, 0, 2)), // r
        hexdec(substr($hash, 2, 2)), // g
        hexdec(substr($hash, 4, 2))); //b
}

function Namabulan($par=null){
    if($par == '01'){
        $nama = 'Januari';
    }else if($par == '02'){
        $nama = 'Februari';
    }else if($par == '03'){
        $nama = 'Maret';
    }else if($par == '04'){
        $nama = 'April';
    }else if($par == '05'){
        $nama = 'Mei';
    }else if($par == '06'){
        $nama = 'Juni';
    }else if($par == '07'){
        $nama = 'Juli';
    }else if($par == '08'){
        $nama = 'Agustus';
    }else if($par == '09'){
        $nama = 'September';
    }else if($par == '10'){
        $nama = 'Oktober';
    }else if($par == '11'){
        $nama = 'November';
    }else{
        $nama = 'Desember';
    }
    return $nama;
}

function h_month_name($par=null){
    if($par == '01' || $par == '1'){
        $nama = 'January';
    }else if($par == '02' || $par == '2'){
        $nama = 'February';
    }else if($par == '03' || $par == '3'){
        $nama = 'March';
    }else if($par == '04' || $par == '4'){
        $nama = 'April';
    }else if($par == '05' || $par == '5'){
        $nama = 'May';
    }else if($par == '06' || $par == '6'){
        $nama = 'June';
    }else if($par == '07' || $par == '7'){
        $nama = 'July';
    }else if($par == '08' || $par == '8'){
        $nama = 'August';
    }else if($par == '09' || $par == '9'){
        $nama = 'September';
    }else if($par == '10' || $par == '10'){
        $nama = 'October';
    }else if($par == '11' || $par == '11'){
        $nama = 'November';
    }else{
        $nama = 'December';
    }
    return $nama;
}

function h_group_name($par=null){
    if($par == '01' || $par == '1'){
        $nama = 'Airframe';
    }else if($par == '02' || $par == '2'){
        $nama = 'Component';
    }else if($par == '03' || $par == '3'){
        $nama = 'Engineering';
    }else if($par == '04' || $par == '4'){
        $nama = 'Engine';
    }else if($par == '05' || $par == '5'){
        $nama = 'GASS';
    }else if($par == '06' || $par == '6'){
        $nama = 'IGET';
    }else if($par == '07' || $par == '7'){
        $nama = 'Learning';
    }else if($par == '08' || $par == '8'){
        $nama = 'Line';
    }else if($par == '09' || $par == '9'){
        $nama = 'Logistic';
    }else if($par == '10' || $par == '10'){
        $nama = 'Material & Trading';
    }else{
        $nama = 'DoA';
    }
    return $nama;
}


function pembanding($jabatanpn, $jabatan){
    $pembanding = ['ID_JABATAN','ESELON','LEMBAGA','UNIT_KERJA'];

    foreach($jabatanpn as $row){
        foreach($jabatan as $baris){
            foreach($pembanding as $pem){
                if($row->$pem != $baris->$pem){
                    return 0;
                }
            }
        }
    }

    return true;
}

function replaceRegex($string)
{
    $string = preg_replace_callback('/\[([\w\s]*)\]/', 'numberFormat', $string);
    return $string;
}

function numberFormat($matches)
{
    return number_format($matches[1], 0, ',', '.');
}

function ifaTetap($array){
    if($array->STATUS == '1' && $array->IS_CHECKED == '0'){
        return true;
    }else{
        return false;
    }
}

function kirim_email($to, $subject, $view = NULL, $param = null){
    $CI =& get_instance();
    $config['protocol']  = "smtp";
    $config['smtp_host'] = ''; 
    $config['smtp_port'] = '';
    $config['smtp_user'] = '';
    $config['smtp_pass'] = '';
    $config['mailtype']  = 'html';
    $config['charset']   = 'utf-8';
    $config['wordwrap']  = TRUE;

    $CI->email->initialize($config);
    $CI->email->set_newline("\r\n");

    $CI->email->from('', '');
    $CI->email->to($to);
    $CI->email->subject($subject);
    
    $message = $CI->load->view($view, $param, TRUE);
    
    $CI->email->message($message);

    // $CI->email->print_debugger();

    return $CI->email->send();
}


