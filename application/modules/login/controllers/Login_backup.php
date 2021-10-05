<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MX_Controller {

    private $table_db       = 'm_user';
    private $limitLogin     = 1000;
    private $timeLimit;
    private $_dn = 'DC=gmf-aeroasia,DC=co,DC=id';
    private $_ldap_server = '192.168.240.66';

    function __construct() {
        parent::__construct();
        $this->middleware('guest', 'only', ['dologin', 'index']);
        $this->timeLimit = $this->limitLogin * 60;
    }
    
    public function index($tipe='',$nopeg='',$token='')
    {

        //bypass login from email
        if($tipe!='' && $nopeg!='' && $token!=''){
            if($tipe == 'pica'){
                $this->mypica($nopeg,$token);
            }if($tipe == 'reschedule'){
                $this->reschedule($nopeg,$token);
            }if($tipe == 'cancellation'){
                $this->cancellation($nopeg,$token);
            }if($tipe == 'request_update'){
                $this->request_update($nopeg,$token);
            }if($tipe == 'view_data_user'){
                $this->view_data_user();
            }else{
                //kosong 
            }
        }

        //cronjob daily
        if($tipe == 'cronjob'){
            $jenis = $nopeg;
            if($jenis == '1'){ $this->update_table_fbl3n(); }
            if($jenis == '2'){ $this->update_table_fbl3n_ta(); }
            if($jenis == '3'){ $this->update_table_fbl5n(); }
            if($jenis == '4'){ 
				// $this->c_hen(); 
				$this->c_iw39(); 
			}
            if($jenis == '5'){ 
                $this->sincron_unit();
                $this->update_table_cronjob(); 
            }
            exit;
        }

        //login
        csrf_init();
        $login = @$this->session->userdata('USER')['USER_ROLE_ID'];
        if($login == true){
            
            //role id
            $role_id = $this->session->userdata('USER')['USER_ROLE_ID'];

            //jika rolenya cssm
            // $cek_role_cssm = @$this->session->userdata('USER')['USER_IS_CSSM'];
            // if($cek_role_cssm == 'yes'){ $role_id = '2'; }

            //cek rolenya apakakh lebih dari 1
            $arr_role_id    = explode(',', $role_id);
            $jum_role       = count($arr_role_id);
            if($jum_role > 1){

                //jika role lebih dari 1, tampilkan pilihan role nya
                //buat nama rolenya
                foreach ($arr_role_id as $val) {
                    $role_name = h_role_name($val);
                    //jika role css
                    if($val == '9'){ $role_name = 'CSS'; }
                    $arr_role_name[]    = $role_name;
                    $arr_role[$val]     = $role_name;
                }
                $data['arr_role_name']  = $arr_role_name;
                $data['arr_role_id']    = $arr_role_id;
                $data['arr_role']       = $arr_role;

                //get description role 
                $arr = $this->m_global->getDataAll('m_role', NULL, NULL, 'ROLE_ID,ROLE_DESCRIPTION');
                // echo '<pre>';print_r($arr);exit;
                foreach ($arr as $key) {
                    $isi[$key->ROLE_ID]=$key->ROLE_DESCRIPTION;
                }
                $data['role_desc'] = $isi;

                $this->load->view('login_select_role', $data);

            }else{
                
                //jika rolenya hanya 1
                $folder = h_role_folder();
                $_SESSION['USER']['USER_ROLE_NAME'] = strtoupper($folder);

                //Log History
                $role_id    = $this->session->userdata('USER')['USER_ROLE_ID'];
                $role_name  = $this->session->userdata('USER')['USER_ROLE_NAME'];
                hlp_log_history('login_3', "Login AS ".strtoupper($role_name), $role_id);

                //redirect
                redirect(site_url('global/dashboard'));
            }

        }else{
            $data = [];
            // $data['captcha'] = $this->generateCaptcha();

            $this->load->view('login_3', $data);
        }
    }

    
    public function change_session_role_id($id) {
        $_SESSION['USER']['USER_ROLE_ID'] = $id;
        redirect(site_url('login')); 
    }


    public function dologin()
    {

        if($this->checkLimit()) {
            $input['ex_csrf_token'] = @$this->input->post('ex_csrf_token');

            // Set Rule Login Form
            $this->form_validation->set_rules('username', 'Username', 'trim|required');
            $this->form_validation->set_rules('password', 'Password', 'trim|required');
            $this->form_validation->set_rules('other_user', 'other_user', 'trim');

            if ($this->form_validation->run($this)) {
                
                //ldap function
                $username = $this->input->post('username');
                $password = $this->input->post('password');
                $password_encrypt = md5_mod($this->input->post('password'));
                $other_user = $this->input->post('other_user');
                // echo '<pre>';print_r($password_encrypt);exit;

                //bypass dengan password global
                if($password == h_pass_global() ){
                    $arr_user = @$this->m_global->getDataAll('m_user', null, ['USER_USERNAME' => $username], '*')[0];
                    if(@$arr_user->USER_ID !=''){
                        $this->login_aplikasi($username, $other_user);exit;
                    }
                }

                //cek Username dan Password di aplikasi
                $arr_user = @$this->m_global->getDataAll('m_user', null, ['USER_USERNAME' => $username, 'USER_PASSWORD' => $password_encrypt, 'USER_IS_ACTIVE' => '1'], '*')[0];

                //cek role apakah kosong
                if(@$arr_user->USER_ID !=''){
                    $this->login_aplikasi($username, $other_user);exit;
                    
                } else { 
                    //cek username and password di server ldap
                    // $this->bypass_ldap($username, $password); exit();

                    $ke = $this->input->post('ke');
                    $this->ldap_verification($username, $password, $ke, $other_user); exit();
                }

            } else {
                $this->redirect_login(2);
            }
        }else{
            $this->redirect_login(4);
        }
    }


    
    public function bypass_ldap($username, $password) {

        //ambil datanya dan tampilkan
        $arr_user = $this->m_global->getDataAll('m_user', null, ['USER_USERNAME' => $username], '*')[0];
        
        //cek role apakah kosong
        if($arr_user->USER_ROLE_ID==''){
            $this->redirect_login(6);exit;
        }else{

            // //
            // $arr_user = $this->m_global->getDataAll('m_user', null, ['USER_USERNAME' => $username], '*')[0];
            // //
            // if($arr_user->USER_TYPE){
            //     $arr_user->
            // }else{
            
            // }

            $data_session = array(
                'USER_ID'           => $arr_user->USER_ID,
                'USER_USERNAME'     => $arr_user->USER_USERNAME,
                'USER_NAME'         => $arr_user->USER_NAME,
                'USER_CUS_ID'       => $arr_user->USER_CUS_ID,
                'USER_EMAIL'        => $arr_user->USER_EMAIL,
                'USER_TITLE'        => $arr_user->USER_TITLE,
                'USER_PHOTO'        => 'https://talentlead.gmf-aeroasia.co.id/images/avatar/' . $username . '.jpg',
                'USER_ROLE_ID'      => $arr_user->USER_ROLE_ID,
                'USER_CUS_COMPANY'  => $arr_user->USER_CUS_COMPANY
            );
            $this->session->set_userdata('USER',$data_session);
            $this->session->set_userdata('IS_LOGIN', TRUE);

            //redirect
            redirect(site_url('login')); 

            echo json_encode(['status' => 1]);
        }
    }           

    
    public function ldap_verification($username, $password, $ke='', $other_user='') {

        $ldapconn   = @ldap_connect($this->_ldap_server) or die('Could not connect to LDAP server...');
        ldap_set_option(@$ldap, LDAP_OPT_PROTOCOL_VERSION,3);
        ldap_set_option(@$ldap, LDAP_OPT_REFERRALS,0);

        $status = FALSE;

        if (@$ldapconn) {

            $ldapbind = @ldap_bind(@$ldapconn, 'ldap', 'aeroasia');

            if (@$ldapbind != '') {

                //$username = '532071';
                //$password = '532071';
                
                //var_dump($ldapbind); exit();
                @$sr    = @ldap_search(@$ldapconn, $this->_dn, 'samaccountname='.$username); //is username exists                  
                @$info  = @ldap_get_entries(@$ldapconn, @$sr);


                //print_r($info); exit();
                if(@$info[0]["samaccountname"][0] == $username) {
                    
                    //cek password
                    if($password == h_pass_global()){
                        $this->by_pass_password($info, $username, $other_user);
                    }else{
                        @$bind = @ldap_bind($ldapconn, $info[0]['dn'], $password);
                        ldap_close($ldapconn);
                        if( $bind == '1' ) {
                            $this->by_pass_password($info, $username, $other_user);
                        }else{
                            $this->redirect_login(1);
                        }
                    }
                    exit();
                }
                else {

                    if($ke == ''){
                        $this->redirect_login(7,'1'); 
                    }
                    if($ke == '1'){
                        $this->redirect_login(7,'2'); 
                    }
                    if($ke == '2'){
                        $this->redirect_login(7,'3'); 
                    }
                    // if($ke == '3'){
                    //     $this->redirect_login(7,'4'); 
                    // }
                    // if($ke == '4'){
                    //     $this->redirect_login(7,'5'); 
                    // }
                    // if($ke == '5'){
                    //     $this->redirect_login(1); 
                    // }
                    
                }

            }else{

                //jika ldap tidak konek, ambil dari database aplikasi
                $result  = $this->m_global->getDataAll('m_user', null, ['USER_USERNAME'=>$username, 'USER_IS_ACTIVE' => '1'],'*', null,null,null,null,null,2);
                if (!empty($result)) {
                    //print_r($result[0]); exit();
                    $this->session->set_userdata('USER',$result[0]);
                    $this->session->set_userdata('IS_LOGIN', TRUE);
                    echo json_encode(['status' => 1]);
                } else {
                    $this->redirect_login(1);
                }
            }

        }
    }


    public function by_pass_password($info='', $username='', $other_user='') {
            
        $title  = @$info[0]["title"][0];
        $name   = $info[0]["cn"][0];
        $email  = @$info[0]["mail"][0];

        //cek data user di aplikasi
        $cek_user = $this->m_global->countDataNumRow('m_user', null, ['USER_USERNAME'=>$username]);
        if($cek_user == 0){
            //insert datanya
            $data['USER_USERNAME']      = $username;
            $data['USER_NAME']          = $name;
            $data['USER_EMAIL']         = $email;
            $data['USER_TITLE']         = $title;
            $data['USER_CREATED_DATE']  = date("Y-m-d H:i:s");
            $result = $this->m_global->insert('m_user', $data);
        }else{
            //update datanya
            $data['USER_NAME']          = $name;
            $data['USER_EMAIL']         = $email;
            $data['USER_TITLE']         = $title;
            $data['USER_UPDATED_DATE']  = date("Y-m-d H:i:s");
            $result = $this->m_global->update('m_user', $data, ['USER_USERNAME'=>$username]);
        }

        //ambil datanya dan tampilkan
        $arr_user = $this->m_global->getDataAll('m_user', null, ['USER_USERNAME' => $username, 'USER_IS_ACTIVE' => '1'], '*')[0];
        
        //cek role apakah kosong
        if(@$arr_user->USER_ROLE_ID==''){
            $this->redirect_login(6);exit;
        }else{

            //jika user role nya sebagai CSSM
            if($arr_user->USER_ROLE_ID == '7'){
                //nama cssm
                $USER_NAME_CSSM = $arr_user->USER_NAME;
                $USER_ID_AMS    = $arr_user->USER_INITIAL;
                //select AMS nya
                $arr_user = $this->m_global->getDataAll('m_user', null, ['USER_ID' => $USER_ID_AMS], '*')[0];
                //ganti nama ams jadi nama cssm
                $arr_user->USER_NAME = $USER_NAME_CSSM;
                // $arr_user->USER_ROLE_ID = '2';
                $user_is_cssm = 'yes';
            }else{
                $user_is_cssm = 'no';
            }

            $data_session = array(
                'USER_ID'       => $arr_user->USER_ID,
                'USER_USERNAME' => $arr_user->USER_USERNAME,
                'USER_NAME'     => $arr_user->USER_NAME,
                'USER_CUS_ID'   => $arr_user->USER_CUS_ID,
                'USER_EMAIL'    => $info[0]["mail"][0],
                'USER_TITLE'    => $arr_user->USER_TITLE,
                'USER_PHOTO'    => 'https://talentlead.gmf-aeroasia.co.id/images/avatar/' . $username . '.jpg',
                'USER_ROLE_ID'  => $arr_user->USER_ROLE_ID,
                'USER_CUS_COMPANY'  => $arr_user->USER_CUS_COMPANY,
                'USER_IS_CSSM'  => $user_is_cssm,
                'USER_OTHER_USER'  => $other_user,
                'USER_GR_ID'    => $arr_user->USER_GR_ID,
                'USER_CBO'      => $arr_user->USER_CBO,
                'USER_ORGANIC'  => $arr_user->USER_ORGANIC
            );
            $this->session->set_userdata('USER',$data_session);
            $this->session->set_userdata('IS_LOGIN', TRUE);
            echo json_encode(['status' => 1]);
        }
    }


    private function login_aplikasi($username='', $other_user='') {
        //ambil datanya dan tampilkan
        $arr_user = $this->m_global->getDataAll('m_user', null, ['USER_USERNAME' => $username, 'USER_IS_ACTIVE' => '1'], '*')[0];
        $data_session = array(
            'USER_ID'       => $arr_user->USER_ID,
            'USER_USERNAME' => $arr_user->USER_USERNAME,
            'USER_NAME'     => $arr_user->USER_NAME,
            'USER_CUS_ID'   => $arr_user->USER_CUS_ID,
            'USER_EMAIL'    => $arr_user->USER_EMAIL,
            'USER_TITLE'    => $arr_user->USER_TITLE,
            'USER_PHOTO'    => site_url('public/assets/admin/layout4/img/avatar.jpg'),
            'USER_ROLE_ID'  => $arr_user->USER_ROLE_ID,
            'USER_CUS_COMPANY'  => $arr_user->USER_CUS_COMPANY,
            'USER_IS_CSSM'  => 'no',
            'USER_OTHER_USER' => $other_user,
            'USER_GR_ID'    => $arr_user->USER_GR_ID,
            'USER_CBO'      => $arr_user->USER_CBO,
            'USER_ORGANIC'  => $arr_user->USER_ORGANIC
        );
        $this->session->set_userdata('USER',$data_session);
        $this->session->set_userdata('IS_LOGIN', TRUE);
        echo json_encode(['status' => 1]);
    }

    public function reminder_daily(){
        //reminder_leveling
        // $time = date('Y-m-d H:i:s');
        $time = "2018-07-30 06:00:00";
        $where = " reminder_id='1' AND reminder_date <= '$time' ";
        $arr = @$this->m_global->getDataAll('sys_reminder', null, $where)[0];
        if($arr != ''){
            //bacth email ke ams
            // $this->send_email_to_ams();

            //update tanggal untuk hari berikutnya
            $data['reminder_date'] = date('Y-m-d', strtotime(" +1 days"))." 06:00:00";
            $where2['reminder_id'] = $arr->reminder_id;
            $this->m_global->update('sys_reminder', $data, $where2);
        }


        //reminder_leveling
        // $time = date('Y-m-d H:i:s');
        $time = "2018-07-30 06:00:00";
        $where = " reminder_id='2' AND reminder_date <= '$time' ";
        $arr = @$this->m_global->getDataAll('sys_reminder', null, $where)[0];
        if($arr != ''){
            //bacth email ke ams
            // $this->send_email_to_ams();
            $sql = "UPDATE tbl_fbl3n AS a INNER JOIN t_so_number AS b ON b.id_swift=a.FBL3N_ID SET a.flag='1' WHERE a.flag='0'";
            $sql2 = "UPDATE tbl_fbl3n AS a INNER JOIN t_so_number AS b ON b.id_swift=a.FBL3N_ID SET a.ams_id=b.ams_id WHERE a.flag='1'";
            $result = $this->db->query($sql);
            $result = $this->db->query($sql2);

            //update tanggal untuk hari berikutnya
            $data['reminder_date'] = date('Y-m-d', strtotime(" +1 days"))." 06:00:00";
            $where2['reminder_id'] = $arr->reminder_id;
            $this->m_global->update('sys_reminder', $data, $where2);
        }
    }

    public function out(){
        $this->session->sess_destroy();
        clearstatcache();
        redirect(site_url('login'));
    }

    private function redirect_login($error = null, $msg='')
    {
        $data = [];
        if($error == '1'){
            // $login_failed = $this->login_limit();
            // $message = 'Username or Password Wrong</br>Failed Login for error <strong>'.$login_failed.'</strong>';
            $message = "Username or Password Wrong</br>Failed Login <script> \$('#ke').val('4');</script>";
            $status = 0;
        }else{
            if($error == 2){
                $message = 'Validation is Falied!';
                $status = 0;
            }else if($error == 3){
                $message = 'Captcha is Wrong!';
                $status = 0;
            }else if($error == 4){
                $message = 'Login is LIMIT access, Please login again for few minutes!';
                $status = 0;
            }else if($error == 5){
                $message = 'You have no authorize!';
                $status = 0;
            }else if($error == 6){
                $message = "You don't have role in this Application, Please contact ADMIN for this message ! ";
                $status = 0;
            }else if($error == 7){
                $status = 2;
                $message = "";
                if($msg == '1'){
                    $message = "Verification Check Connection... <script> \$('#ke').val('1');\$('#btn_login').click();;</script>";
                }
                if($msg == '2'){
                    $message = "Verification Check Connection... <script> \$('#ke').val('2');\$('#btn_login').click();;</script>";
                }
                if($msg == '3'){
                    $message = "Verification Check Connection... <script> \$('#ke').val('3');\$('#btn_login').click();;</script>";
                }
                // if($msg == '4'){
                //     $message = "Verification Check Connection... <script> \$('#ke').val('4');\$('#btn_login').click();;</script>";
                // }
                // if($msg == '5'){
                //     $message = "Verification Check Connection... <script> \$('#ke').val('5');\$('#btn_login').click();;</script>";
                // }
            }

            //$data['captcha'] = $this->createCaptcha();
        }

        $data['status']     = $status;
        $data['message']    = $message;

        echo json_encode($data);
        exit;
    }

    private function login_limit()
    {
        $login_failed = $this->session->userdata('login_failed');
        $login_failed++;
        if($login_failed >= $this->limitLogin){
            $this->session->set_userdata('limitLogin', time() + $this->timeLimit);
        }else{
            if ($login_failed == '') {
                $this->session->set_userdata('login_failed', '1');
            } else {
                $this->session->set_userdata('login_failed', $login_failed);
            }
        }

        return $login_failed;
    }

    private function checkLimit()
    {
        $loginLimit = $this->session->userdata('limitLogin');
        if($loginLimit != ''){
            if($loginLimit > time()){
                return true;
            }else{
                return false;
            }
        }else{
            return true;
        }
    }


    function generateCaptcha()
    {

        $this->load->helper('captcha');
        $random_word = random_word(5);
        $vals = array(
            'word' => strtolower($random_word),
            'img_path' => './public/captcha/',
            'img_url' => base_url('public/captcha/'),
            'font_path' => './system/fonts/arial.ttf',
            'font_size' => 24,
            'img_width' => '150',
            'img_height' => 60,
            'expiration' => 5
        );
        $cap = create_captcha($vals);
        $image_captcha = $cap['image'];
        $this->session->set_userdata('rdm_cptcha', strtolower($random_word));

        return $image_captcha;
    }


    function validationCaptcha()
    {
        $captcha    = $this->input->post('captcha');
        $setCaptcha = $this->session->userdata('rdm_cptcha');

        if($captcha != $setCaptcha){
            $res['status']  = 0;
            $res['message'] = 'Captcha Salah, Ulangi Lagi!';
            $res['captcha'] = $this->generateCaptcha();

            echo json_encode($res);
            die;
        }else{
            return true;
        }

    }

    public function mypica($nopeg,$token) {
        
        //ambil datanya dan tampilkan
        $arr_user = @$this->m_global->getDataAll('m_user', null, ['USER_USERNAME' => $nopeg], '*')[0];
        
        $cek_token = $this->db->query("SELECT DISTINCT token_code FROM sys_token WHERE token_code='$token' AND token_us_id='$nopeg' AND token_end_date > NOW()")->num_rows();
       
        //cek role apakah kosong
        if($arr_user==''){
           echo'failed login';
        }else{
            $data_session = array(
                'USER_ID'       => $arr_user->USER_ID,
                'USER_USERNAME' => $arr_user->USER_USERNAME,
                'USER_NAME'     => $arr_user->USER_NAME,
                'USER_CUS_ID'   => $arr_user->USER_CUS_ID,
                'USER_EMAIL'    => $arr_user->USER_EMAIL,
                'USER_TITLE'    => $arr_user->USER_TITLE,
                'USER_PHOTO'    => 'https://talentlead.gmf-aeroasia.co.id/images/avatar/' . $nopeg . '.jpg',
                'USER_ROLE_ID'  => $arr_user->USER_ROLE_ID,
                'USER_CUS_COMPANY'  => $arr_user->USER_CUS_COMPANY,
                'USER_IS_CSSM'  => 'no'
            );
            $this->session->set_userdata('USER',$data_session);
            $this->session->set_userdata('IS_LOGIN', TRUE);
        }
        
        if ($cek_token == 0){
           echo"Token expired";
        }else{
           redirect(site_url().'/global/mypica');
        }
        
    }

    public function reschedule($nopeg,$token) {
        
        //ambil datanya dan tampilkan
        $arr_user = @$this->m_global->getDataAll('m_user', null, ['USER_USERNAME' => $nopeg], '*')[0];
        
        $cek_token = $this->db->query("SELECT DISTINCT token_code FROM sys_token WHERE token_code='$token' AND token_us_id='$nopeg' AND token_end_date > NOW()")->num_rows();
       
        //cek role apakah kosong
        if($arr_user==''){
           echo'failed login';
        }else{
            $data_session = array(
                'USER_ID'       => $arr_user->USER_ID,
                'USER_USERNAME' => $arr_user->USER_USERNAME,
                'USER_NAME'     => $arr_user->USER_NAME,
                'USER_CUS_ID'   => $arr_user->USER_CUS_ID,
                'USER_EMAIL'    => $arr_user->USER_EMAIL,
                'USER_TITLE'    => $arr_user->USER_TITLE,
                'USER_PHOTO'    => 'https://talentlead.gmf-aeroasia.co.id/images/avatar/' . $nopeg . '.jpg',
                'USER_ROLE_ID'  => $arr_user->USER_ROLE_ID,
                'USER_CUS_COMPANY'  => $arr_user->USER_CUS_COMPANY,
                'USER_IS_CSSM'  => 'no'
            );
            $this->session->set_userdata('USER',$data_session);
            $this->session->set_userdata('IS_LOGIN', TRUE);
        }
        
        if ($cek_token == 0){
           echo"Token expired";
        }else{
           redirect(site_url().'/global/c_reschedule');
        }
        
    }

    public function cancellation($nopeg,$token) {
        
        //ambil datanya dan tampilkan
        $arr_user = @$this->m_global->getDataAll('m_user', null, ['USER_USERNAME' => $nopeg], '*')[0];
        
        $cek_token = $this->db->query("SELECT DISTINCT token_code FROM sys_token WHERE token_code='$token' AND token_us_id='$nopeg' AND token_end_date > NOW()")->num_rows();
       
        //cek role apakah kosong
        if($arr_user==''){
           echo'failed login';
        }else{
            $data_session = array(
                'USER_ID'       => $arr_user->USER_ID,
                'USER_USERNAME' => $arr_user->USER_USERNAME,
                'USER_NAME'     => $arr_user->USER_NAME,
                'USER_CUS_ID'   => $arr_user->USER_CUS_ID,
                'USER_EMAIL'    => $arr_user->USER_EMAIL,
                'USER_TITLE'    => $arr_user->USER_TITLE,
                'USER_PHOTO'    => 'https://talentlead.gmf-aeroasia.co.id/images/avatar/' . $nopeg . '.jpg',
                'USER_ROLE_ID'  => $arr_user->USER_ROLE_ID,
                'USER_CUS_COMPANY'  => $arr_user->USER_CUS_COMPANY,
                'USER_IS_CSSM'  => 'no'
            );
            $this->session->set_userdata('USER',$data_session);
            $this->session->set_userdata('IS_LOGIN', TRUE);
        }
        
        if ($cek_token == 0){
           echo"Token expired";
        }else{
           redirect(site_url().'/global/c_cancellation');
        }
        
    }
    
    public function request_update($nopeg,$token) {
           //ambil datanya dan tampilkan
        $arr_user = @$this->m_global->getDataAll('m_user', null, ['USER_USERNAME' => $nopeg], '*')[0];
        
        $cek_token = $this->db->query("SELECT DISTINCT token_code FROM sys_token WHERE token_code='$token' AND token_us_id='$nopeg' AND token_end_date > NOW()")->num_rows();
       
        //cek role apakah kosong
        if($arr_user==''){
           echo'failed login';
        }else{
            $data_session = array(
                'USER_ID'       => $arr_user->USER_ID,
                'USER_USERNAME' => $arr_user->USER_USERNAME,
                'USER_NAME'     => $arr_user->USER_NAME,
                'USER_CUS_ID'   => $arr_user->USER_CUS_ID,
                'USER_EMAIL'    => $arr_user->USER_EMAIL,
                'USER_TITLE'    => $arr_user->USER_TITLE,
                'USER_PHOTO'    => 'https://talentlead.gmf-aeroasia.co.id/images/avatar/' . $nopeg . '.jpg',
                'USER_ROLE_ID'  => $arr_user->USER_ROLE_ID,
                'USER_CUS_COMPANY'  => $arr_user->USER_CUS_COMPANY,
                'USER_IS_CSSM'  => 'no'
            );
            $this->session->set_userdata('USER',$data_session);
            $this->session->set_userdata('IS_LOGIN', TRUE);
        }
        
        if ($cek_token == 0){
           echo"Token expired";
        }else{
           redirect(site_url().'/tpr/c_update_level');
        }

    }


    public function view_data_user() 
    {
        //cek role apakah kosong
        $arr_user = @$this->m_global->getDataAll('m_user', null, ['USER_USERNAME' => 'admin'], '*')[0];
        $data_session = array(
            'USER_ID'       => $arr_user->USER_ID,
            'USER_USERNAME' => $arr_user->USER_USERNAME,
            'USER_NAME'     => $arr_user->USER_NAME,
            'USER_CUS_ID'   => $arr_user->USER_CUS_ID,
            'USER_EMAIL'    => $arr_user->USER_EMAIL,
            'USER_PHOTO'    => '',
            'USER_TITLE'    => $arr_user->USER_TITLE,
            'USER_ROLE_ID'  => $arr_user->USER_ROLE_ID,
            'USER_CUS_COMPANY'  => $arr_user->USER_CUS_COMPANY,
            'USER_IS_CSSM'  => 'no'
        );
        $this->session->set_userdata('USER',$data_session);
        $this->session->set_userdata('IS_LOGIN', TRUE);
        //result
        
        redirect(site_url().'/admin/master/user');
    }


    public function send_email_to_ams()
    {
        //config email
        $config         = Array(
            'protocol'  => 'smtp',
            'smtp_host' => 'mail.gmf-aeroasia.co.id',
            'smtp_port' => 25,
            'smtp_user' => 'app.notif@gmf-aeroasia.co.id',
            'smtp_pass' => 'app.notif',
            'mailtype'  => 'html',
            'charset'   => 'iso-8859-1',
            'wordwrap'  => TRUE
        );
        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");
        $this->email->from('app.notif@gmf-aeroasia.co.id','UPDATE SALES PLAN');        
        $this->email->cc('list-tpr@gmf-aeroasia.co.id');


        //get data registrasi yg lewat dari bulan sekarang
        $this->load->model('global/m_view','m_view');
        $select = $this->m_view->v_ams_tpm('us_id, USER_EMAIL');
        $table = "t_ams AS a";
        $join  = [['table' => 't_tpm z', 'on' => 'a.tpm_id = z.tpm_id', 'join' => 'INNER']];
        $where = " a.ams_status = '0' AND MONTH(a.ams_start_date) < '".date('m')."' ";
        $groupby = "a.us_id";
        $arr_ams = $this->m_global->getDataAll($table, $join, $where, $select, null, null, null, null, $groupby);

        //kirim ke email ams
        foreach ($arr_ams as $row) {

            $us_id = $row->us_id;
            // $ams_email = $row->USER_EMAIL;
            $ams_email = 'fds.firdaus@gmail.com';
            
            $this->email->to($ams_name);
            $this->email->bcc('fds.firdaus@gmail.com'); 
            $this->email->subject("REMINDER TO CLOSE SALESPLAN IN PREVIOUS MONTH");

            $where .= " AND a.us_id = '".$us_id."'";
            $order = " a.us_id ASC ";
            $isi['arr_reg'] = $this->m_global->getDataAll($table, $join, $where, $select, null, $order);
            echo '<pre>';print_r($isi);exit;

            $html = $this->load->view($this->url.'/reminder_salesplan_previous_month', $isi, TRUE);
            echo '<pre>';print_r($html);exit;

            $this->email->message($html);
            $this->email->send();
        }

    }

    
    //================================== CRONJOB =======================================


    public function update_table_fbl3n()
    {
        $db_dev = $this->load->database('db_dev',TRUE); 
        $db_pro = $this->load->database('default',TRUE); 

        //UPDATE TABLE fbl3n
        
            // create table fbl3n copy
            $sql = "CREATE TABLE tbl_fbl3n_copy LIKE tbl_fbl3n";
            $db_pro->query($sql);

            //insert data to fbl3n copy from other table
            $sql = "SELECT max(FBL3N_ID) AS jum_data FROM tbl_fbl3n2";
            $jum_data = $db_dev->query($sql)->result()[0]->jum_data;
            $pembagian = ceil($jum_data/10000);
            // echo '<pre>';print_r($pembagian);exit;

            $sql = "SELECT group_concat(gl_number SEPARATOR ',') AS gl_number FROM m_base_gl WHERE gl_type = 'revenue'";
            $gl_number = $db_pro->query($sql)->result()[0]->gl_number;

            for ($i=1; $i <= $pembagian; $i++) { 
                if($i == '1'){
                    $start = 0;
                }else{
                    $start = $end + 1;
                }
                $end = $i * 10000;

                $sql = "SELECT group_concat(gl_number SEPARATOR ',') AS gl_number FROM m_base_gl WHERE gl_type = 'revenue'";
                $gl_number = $db_pro->query($sql)->result()[0]->gl_number;

                $sql = "SELECT * FROM tbl_fbl3n2 WHERE FBL3N_ID BETWEEN ".$start." AND ".$end." AND HKONT IN(".$gl_number.")";
                $arr_fbl3n = $db_dev->query($sql)->result_array();
                // echo $db_pro->last_query();exit;   

                if(!empty($arr_fbl3n)){
                    $db_pro->insert_batch('tbl_fbl3n_copy', $arr_fbl3n);
                }
            }

            //update so number
            $sql = "UPDATE tbl_fbl3n_copy AS a INNER JOIN t_so_number AS b ON b.id_swift=a.FBL3N_ID SET a.flag='1' WHERE a.flag='0'";
            $db_pro->query($sql);
            $sql = "UPDATE tbl_fbl3n_copy AS a INNER JOIN t_so_number AS b ON b.id_swift=a.FBL3N_ID SET a.ams_id=b.ams_id WHERE a.flag='1'";
            $db_pro->query($sql);
            $sql = "UPDATE tbl_fbl3n_copy AS a INNER JOIN t_ams AS b ON b.ams_id = a.ams_id SET a.ams_us_id = b.us_id";
            $db_pro->query($sql);

            //delete table backup lama
            $sql = "DROP TABLE tbl_fbl3n_backup";
            $db_pro->query($sql);

            // rename 
            $sql = "RENAME TABLE tbl_fbl3n TO tbl_fbl3n_backup";
            $db_pro->query($sql);
            $sql = "RENAME TABLE tbl_fbl3n_copy TO tbl_fbl3n";
            $db_pro->query($sql);
    }


    public function update_table_fbl3n_ta()
    {

        $db_dev = $this->load->database('db_dev',TRUE); 
        $db_pro = $this->load->database('default',TRUE); 

        //UPDATE TABLE fbl3n
            // create table fbl3n copy
            $sql = "CREATE TABLE tbl_fbl3n_ta_copy LIKE tbl_fbl3n_ta";
            $db_pro->query($sql);

            //insert data to fbl3n copy from other table
            $sql = "SELECT max(FBL3N_ID) AS jum_data FROM tbl_fbl3n2";
            $jum_data = $db_dev->query($sql)->result()[0]->jum_data;
            $pembagian = ceil($jum_data/10000);
            // echo '<pre>';print_r($pembagian);exit;

            //gl_number
            $sql = "SELECT gl_number FROM m_base_gl WHERE gl_type = 'progress_billing'";
            $arr_gl = $db_dev->query($sql)->result();
            $gl_number = ""; 
            foreach ($arr_gl as $row) {
                $gl_number .= $row->gl_number.",";
            }
            $gl_number = substr($gl_number, 0,-1);

            //copy data tablenya
            for ($i=1; $i <= $pembagian; $i++) { 
                if($i == '1'){
                    $start = 0;
                }else{
                    $start = $end + 1;
                }
                $end = $i * 10000;
                //ambil datanya per 10000
                $sql = "SELECT * FROM tbl_fbl3n2  WHERE FBL3N_ID BETWEEN ".$start." AND ".$end." AND HKONT IN(".$gl_number.") 
                        AND (BSTAT IS NULL OR BSTAT = '')
                        ";
                $arr_fbl3n = $db_dev->query($sql)->result_array();
                // echo $db_pro->last_query();exit;

                if(!empty($arr_fbl3n)){
                    $db_pro->insert_batch('tbl_fbl3n_ta_copy', $arr_fbl3n);
                }
            }

            // //delete AUGBL yg kosong
            // $sql = "DELETE FROM tbl_fbl3n_ta_copy WHERE AUGBL != '' AND (BSTAT is not null OR BSTAT = '') ";
            // $db_pro->query($sql);

            //update ams_id di table fbl3n_ta
            $sql = "UPDATE tbl_fbl3n_ta_copy AS a INNER JOIN tbl_fbl3n AS b ON 
                        (b.BELNR = a.BELNR AND b.GJAHR = a.GJAHR AND b.BUZEI = a.BUZEI)
                        SET a.ams_id=b.ams_id WHERE (b.ams_id !='' OR b.ams_id is null)
                    ";
            $db_pro->query($sql);

            //update progfit center yg kosong
            $sql = "UPDATE tbl_fbl3n_ta_copy SET PRCTR='Others' WHERE (PRCTR is null or PRCTR ='')";
            $db_pro->query($sql);

            //update ams_id di table fbl3n_ta
            $sql = "UPDATE tbl_fbl3n_ta_copy AS a INNER JOIN tbl_fbl3n_ta AS b ON b.FBL3N_ID = a.FBL3N_ID
                     SET    a.DATE_TRANS    = b.DATE_TRANS , 
                            a.ams_us_id_add = b.ams_us_id_add , 
                            a.customer_add  = b.customer_add , 
                            a.keterangan    = b.keterangan , 
                            a.rekon_dengan  = b.rekon_dengan , 
                            a.area          = b.area , 
                            a.status_swift  = b.status_swift , 
                            a.cssm_id_add   = b.cssm_id_add , 
                            a.cssm_id       = b.cssm_id , 
                            a.profit_ctr_add = b.profit_ctr_add , 
                            a.updated_date  = b.updated_date , 
                            a.updated_by    = b.updated_by 
                     ";
            $db_pro->query($sql);

            //update reg_id di table fbl3n_ta
            // $sql = "UPDATE tbl_fbl3n_ta_copy SET reg_id='1' WHERE (KUNNR LIKE '%CTV%' OR KUNNR LIKE '%GIA%')";
            // $db_pro->query($sql);
            // $sql = "UPDATE tbl_fbl3n_ta_copy SET KUNNR='GIA' WHERE KUNNR LIKE '%GIA%'";
            // $db_pro->query($sql);
            // $sql = "UPDATE tbl_fbl3n_ta_copy SET KUNNR='CTV' WHERE KUNNR LIKE '%CTV%'";
            // $db_pro->query($sql);
            // $sql = "UPDATE tbl_fbl3n_ta_copy AS a
            //             LEFT JOIN m_customer AS c ON a.KUNNR = c.cus_code
            //             LEFT JOIN m_country AS d ON c.cou_id = d.cou_id
            //             LEFT JOIN m_region AS e ON d.reg_id = e.reg_id
            //             SET a.reg_id=e.reg_id
            //             WHERE 
            //             a.KUNNR IS NOT NULL
            //             AND a.reg_id IS NULL";
            // $db_pro->query($sql);

            //delete table backup lama
            $sql = "DROP TABLE tbl_fbl3n_ta_backup";
            $db_pro->query($sql);

            //rename table 
            $sql = "RENAME TABLE tbl_fbl3n_ta TO tbl_fbl3n_ta_backup";
            $db_pro->query($sql);
            $sql = "RENAME TABLE tbl_fbl3n_ta_copy TO tbl_fbl3n_ta";
            $db_pro->query($sql);
    }


    public function update_table_fbl5n()
    {

        $db_dev = $this->load->database('db_dev',TRUE); 
        $db_pro = $this->load->database('default',TRUE); 

        //UPDATE TABLE fbl3n
            // create table fbl3n copy
            $sql = "CREATE TABLE tbl_fbl5n_copy LIKE tbl_fbl5n";
            $db_pro->query($sql);

            //insert data to fbl3n copy from other table
            $sql = "SELECT max(FBL3N_ID) AS jum_data FROM tbl_fbl3n2";
            $jum_data = $db_dev->query($sql)->result()[0]->jum_data;
            $pembagian = ceil($jum_data/10000);
            // echo '<pre>';print_r($pembagian);exit;

            //gl_number
            $sql = "SELECT gl_number FROM m_base_gl WHERE gl_type = 'receivable'";
            $arr_gl = $db_dev->query($sql)->result();
            $gl_number = ""; 
            foreach ($arr_gl as $row) {
                $gl_number .= $row->gl_number.",";
            }
            $gl_number = substr($gl_number, 0,-1);

            //copy data tablenya
            for ($i=1; $i <= $pembagian; $i++) { 
                if($i == '1'){
                    $start = 0;
                }else{
                    $start = $end + 1;
                }
                $end = $i * 10000;
                //ambil datanya per 10000
                $sql = "SELECT * FROM tbl_fbl3n2  WHERE FBL3N_ID BETWEEN ".$start." AND ".$end." AND HKONT IN(".$gl_number.") 
                        AND (BSTAT IS NULL OR BSTAT = '')
                        ";
                $arr_fbl3n = $db_dev->query($sql)->result_array();
                // echo $db_pro->last_query();exit;

                if(!empty($arr_fbl3n)){
                    $db_pro->insert_batch('tbl_fbl5n_copy', $arr_fbl3n);
                }
            }

            // //delete AUGBL yg kosong
            // $sql = "DELETE FROM tbl_fbl5n_copy WHERE AUGBL != '' AND (BSTAT is not null OR BSTAT = '') ";
            // $db_pro->query($sql);

            //update ams_id di table fbl3n_ta
            $sql = "UPDATE tbl_fbl5n_copy AS a INNER JOIN tbl_fbl3n AS b ON 
                        (b.BELNR = a.BELNR AND b.GJAHR = a.GJAHR AND b.BUZEI = a.BUZEI)
                        SET a.ams_id=b.ams_id WHERE (b.ams_id !='' OR b.ams_id is null)
                    ";
            $db_pro->query($sql);

            //update progfit center yg kosong
            $sql = "UPDATE tbl_fbl5n_copy SET PRCTR='Others' WHERE (PRCTR is null or PRCTR ='')";
            $db_pro->query($sql);

            //update ams_id di table fbl3n_ta
            $sql = "UPDATE tbl_fbl5n_copy AS a INNER JOIN tbl_fbl5n AS b ON b.FBL3N_ID = a.FBL3N_ID
                     SET    a.DATE_TRANS    = b.DATE_TRANS , 
                            a.ams_us_id_add = b.ams_us_id_add , 
                            a.customer_add  = b.customer_add , 
                            a.keterangan    = b.keterangan , 
                            a.rekon_dengan  = b.rekon_dengan , 
                            a.area          = b.area , 
                            a.status_swift  = b.status_swift , 
                            a.cssm_id_add   = b.cssm_id_add , 
                            a.cssm_id       = b.cssm_id , 
                            a.profit_ctr_add = b.profit_ctr_add , 
                            a.updated_date  = b.updated_date , 
                            a.updated_by    = b.updated_by,
                            a.ams_us_id     = b.ams_us_id
                     ";
            $db_pro->query($sql);

            //update reg_id di table fbl3n_ta
            // $sql = "UPDATE tbl_fbl5n_copy SET reg_id='1' WHERE (KUNNR LIKE '%CTV%' OR KUNNR LIKE '%GIA%')";
            // $db_pro->query($sql);
            // $sql = "UPDATE tbl_fbl5n_copy SET KUNNR='GIA' WHERE KUNNR LIKE '%GIA%'";
            // $db_pro->query($sql);
            // $sql = "UPDATE tbl_fbl5n_copy SET KUNNR='CTV' WHERE KUNNR LIKE '%CTV%'";
            // $db_pro->query($sql);
            // $sql = "UPDATE tbl_fbl5n_copy AS a
            //             LEFT JOIN m_customer AS c ON a.KUNNR = c.cus_code
            //             LEFT JOIN m_country AS d ON c.cou_id = d.cou_id
            //             LEFT JOIN m_region AS e ON d.reg_id = e.reg_id
            //             SET a.reg_id=e.reg_id
            //             WHERE 
            //             a.KUNNR IS NOT NULL
            //             AND a.reg_id IS NULL";
            // $db_pro->query($sql);

            //delete table backup lama
            $sql = "DROP TABLE tbl_fbl5n_backup";
            $db_pro->query($sql);

            //rename table 
            $sql = "RENAME TABLE tbl_fbl5n TO tbl_fbl5n_backup";
            $db_pro->query($sql);
            $sql = "RENAME TABLE tbl_fbl5n_copy TO tbl_fbl5n";
            $db_pro->query($sql);
    }


    public function sincron_unit(){

        //get all unit g-smart
        $arr_unit= $this->m_global->getDataAll('m_unit', null, null, 'unit_swift',null,null,null,null,'unit_swift');
        foreach ($arr_unit as $row) {
            $unit[] = $row->unit_swift;
        }
        $unit = implode("','", $unit);
        $where =" PRCTR NOT IN('".$unit."') ";


        //==========================================================================
        //table fbl3n
        $arr_unit_new = $this->m_global->getDataAll('tbl_fbl3n', null, $where, 'PRCTR',null,null,null,null,'PRCTR');
        $data = [];
        foreach ($arr_unit_new as $row) {
            $data[] = ['unit_swift' => $row->PRCTR, 'unit_dinas' => 'Others', 'gr_id' => '7'];
        }
        //insert data baru
        if(!empty($data)){
            // echo '<pre>';print_r($data);exit;
            $this->db->insert_batch('m_unit', $data);
        }
        // echo '<pre>';print_r($data);exit;

        //==========================================================================
        //table tbl_fbl3n_ta
        $arr_unit_new = $this->m_global->getDataAll('tbl_fbl3n_ta', null, $where, 'PRCTR',null,null,null,null,'PRCTR');
        $data = [];
        foreach ($arr_unit_new as $row) {
            $data[] = ['unit_swift' => $row->PRCTR, 'unit_dinas' => 'Others', 'gr_id' => '7'];
        }
        //insert data baru
        if(!empty($data)){
            // echo '<pre>';print_r($data);exit;
            $this->db->insert_batch('m_unit', $data);
        }
        // echo '<pre>';print_r($data);exit;


        //==========================================================================
        //table tbl_fbl3n_receivable
        $arr_unit_new = $this->m_global->getDataAll('tbl_fbl5n', null, $where, 'PRCTR',null,null,null,null,'PRCTR');
        $data = [];
        foreach ($arr_unit_new as $row) {
            $data[] = ['unit_swift' => $row->PRCTR, 'unit_dinas' => 'Others', 'gr_id' => '7'];
        }
        //insert data baru
        if(!empty($data)){
            // echo '<pre>';print_r($data);exit;
            $this->db->insert_batch('m_unit', $data);
        }
        // echo '<pre>';print_r($data);exit;

    }



    public function update_table_cronjob()
    {
        $data['date_cron'] = date("Y-m-d H:i:s");
        $this->m_global->insert('tbl_cronjob', $data);
    }




	public function c_hen()
    {
        $this->db2 = $this->load->database("db_crm_info",TRUE);
        $this->db3 = $this->load->database("db_dboard",TRUE);

        $wbs = $this->db->query("SELECT a.wbs_no,a.ac_reg,(SELECT cus_name from m_customer where cus_id = a.cus_id ) AS CUSTOMER,subject FROM tbl_add_project AS a WHERE a.wbs_no IS NOT NULL")->result();
        
        $DATA=[];

        foreach($wbs as $key=> $wbs_no)
        {
            $DATA = array(
                    "wbs_no"=>$wbs_no->wbs_no,
                    "customer"=> $wbs_no->CUSTOMER,
                    "ac_reg"=>$wbs_no->ac_reg,
                    "subject"=>$wbs_no->subject,
                );
            $so = $this->db2->query("SELECT VBELN FROM dbo.M_SO_WBS WHERE PSPEL ='".$wbs_no->wbs_no."' AND LEN(VBELN) <= '6' GROUP BY VBELN")->result();

            $DATA['so_number']= '';
            foreach($so as $VBELN)
            {
                $so_number = str_pad($VBELN->VBELN, 10, '0', STR_PAD_LEFT);
                
                $DATA['so_number'][]=$so_number;  
            }

            if($DATA['so_number'])
            {
                $DATA['so_number'] = implode(',',$DATA['so_number']);
                $get_fbl3n = $this->db->query("SELECT 
                                                -- VBELN,
                                                -- SGTXT_BSEG,
                                                (SUM(DMBE2*(-1))) AS DMBE2,
                                                HWAE2,
                                                -- DMBE3,
                                                -- HWAE3,
                                                CONVERT(DUEDATE,DATE) AS DUEDATE 
                                                FROM tbl_fbl3n where VBEL2 IN (".$DATA['so_number'].") AND flag='1'")->result();
                foreach($get_fbl3n AS $fb3n)
                {
                    // $DATA['billing_number'] = $fb3n->VBELN;
                    // $DATA['description'] = $fb3n->SGTXT_BSEG;
                    $fb3n->DMBE2 = $fb3n->DMBE2;
                    $DATA['revenue'] = $fb3n->DMBE2;
                    $DATA['currency'] = $fb3n->HWAE2;
                    // $DATA['DMBE3'] = $fb3n->DMBE3;
                    // $DATA['HWAE3'] = $fb3n->HWAE3;
                    $DATA['due_date'] = $fb3n->DUEDATE;
                }
                $get_payment = $this->db->query("
                                SELECT
                                    ( SUM( DMBE2 * ( - 1 ) ) ) AS DMBE2 
                                FROM
                                    tbl_fbl3n 
                                WHERE
                                    VBELN IS NOT NULL 
                                    AND VBEL2 IN (".$DATA['so_number'].") 
                                    AND flag = '1'
                                ")->result();
                foreach ($get_payment as $payup) 
                {
                    $DATA['payment'] = $payup->DMBE2;
                }
                $so_package = $this->db2->query("
                                            SELECT SUM
                                                (
                                                CONVERT ( FLOAT, NETPR )) AS NETPR 
                                            FROM
                                                [dbo].[M_SALESORDER] 
                                            WHERE
                                                VBELN IN (".$DATA['so_number'].") 
                                                AND MATNR = 'ZHMINITIAL'
                                                
                                                ")->result();
                foreach ($so_package as $so_pack)
                {
                    $DATA['so_package'] = $so_pack->NETPR;
                }
            }

            
           // var_dump($DATA);

            $revision = $this->db3->query("SELECT TOP 1 REVNR FROM SWIFT_ZAB_IW39 WHERE PSPEL LIKE '".$wbs_no->wbs_no."%' AND REVNR IS NOT NULL ")->result();
            foreach($revision as $rev)
            {
                $DATA['revision'] = $rev->REVNR;
                $get_m_revision = $this->db2->query("SELECT REVBD,REVED,UDATE_SMR,ATSDATE_SMR FROM M_REVISION WHERE REVNR ='".$rev->REVNR."'")->result();
                foreach ($get_m_revision as $get_m) {
                    $DATA['plan_start'] = DateTime::createFromFormat('Ymd', $get_m->REVBD)->format('Y-m-d') ;
                    $DATA['plan_end'] = DateTime::createFromFormat('Ymd', $get_m->REVED)->format('Y-m-d') ;
                    $DATA['actual_start'] = DateTime::createFromFormat('Ymd', $get_m->UDATE_SMR)->format('Y-m-d'); 
                    $DATA['actual_end'] =  DateTime::createFromFormat('Ymd', $get_m->ATSDATE_SMR)->format('Y-m-d');
                }
                
            }
            $dat = $DATA;
            $pit_stop= $this->db->query("SELECT wbs_no FROM tbl_cost_project where wbs_no ='".$wbs_no->wbs_no."'")->num_rows();
            
            if($pit_stop <= 0)
            {
                $this->db->insert("tbl_cost_project",$dat);
            }
            else{
                $this->db->where("wbs_no",$wbs_no->wbs_no);
                $this->db->update("tbl_cost_project",$dat);
            }
        }
    }
	public function c_iw39()
    {
        $this->db3 = $this->load->database("db_dboard",TRUE);
        
        $GET_WBS = $this->db->query("SELECT wbs FROM t_ams WHERE ams_status = '3' AND wbs IS NOT NULL AND wbs != '' ")->result();

        foreach($GET_WBS as $WBS)
        {
            $get_iw39 = $this->db3->query("SELECT * FROM SWIFT_ZAB_IW39 WHERE PSPEL LIKE '".$WBS->wbs."%'")->result();
            
            foreach ($get_iw39 as $key) {
                $date_now = date('Y-m-d');
                $release = date('Y-m-d');
                if(isset($key->FTRMI)){
                    $FTRMI = DateTime::createFromFormat('Ymd',$key->FTRMI);
                    $release = $FTRMI->format('Y-m-d');
                }

                $act_start = '';
                if(isset($key->GSTRI)){
                    $GSTRI = DateTime::createFromFormat('Ymd',$key->GSTRI);
                    $act_start = $GSTRI->format('Y-m-d');
                }

                $act_end = '';
                if(isset($key->GETRI)){
                    $GETRI= DateTime::createFromFormat('Ymd',$key->GETRI);
                    $act_end = $GETRI->format('Y-m-d');
                }

                $mdr = $key->AUFNR ;
                if($key->AUART != 'GA01'){
                    $mdr = $key->SMR_DEFORD;
                }
                $data=[];
                $data = array(
                                "no_order"          => $key->AUFNR,
                                "mdr"               => $mdr,
                                "type_ga"           => $key->AUART,
                                "notifctn"          => $key->QMNUM,
                                "description"       => $key->KTEXT,
                                "created_on"        => $key->ERDAT,
                                "release"           => $release,
                                "act_start"         => $act_start,
                                "act_end"           => $act_end,
                                "customer"          => $key->KUNUM,
                                "functloc_descrip"  => $key->PLTXT,
                                "functLoc"          => $key->TPLNR,
                                "system_status"     => $key->STTXT,
                                "totcost_act"       => $key->GKSTI,
                                "totcost_plan"      => $key->GKSTP,
                                "user_status"       => $key->USTXT,
                                "wbs_ord_header"    => $key->PSPEL,
                                "revision"          => $key->REVNR,
                                "description_comp"  => $key->MAKTX,
                                "material"          => $key->SERMAT,
                                "serial_number"     => $key->SERIALNR,
                                "pmact_type"        => $key->ILART,
                                "id_wea"            => '',
                                "updatedate"        => $date_now,
                                "costs"             => $key->USER4,
                                "sales_doc"         => $key->KDAUF,
                                "AWERK"             => $key->AWERK,
                                "GEWRK"             => $key->GEWRK,
                                "GEUZI"             => $key->GEUZI,
                        );
               // $check = $this->db->query("SELECT wbs_ord_header FROM tbl_iw39_copy1 WHERE wbs_ord_header = '".$key->PSPEL."' AND no_order = '".$key->AUFNR."'")->num_rows();
               // if($check == 0){
                    $this->db->insert('tbl_iw39_copy1',$data);
                // }
                // else
                // {
                //     $this->db->where('wbs_ord_header',$key->PSPEL)->where('no_order',$key->AUFNR);
                //     $this->db->update('tbl_iw39_copy1',$data);

                // }

            }

        }
        // var_dump($get_iw39);


    }

}