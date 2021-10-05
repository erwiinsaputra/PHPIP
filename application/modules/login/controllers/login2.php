<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MX_Controller {

    private $table_db       = 'm_user';
    private $limitLogin     = 1000;
    private $_dn            = 'DC=gmf-aeroasia,DC=co,DC=id';
    private $_ldap_server   = '192.168.240.66';
    private $timeLimit;

    function __construct() {
        parent::__construct();
        $this->middleware('guest', 'only', ['dologin', 'index']);
        $this->timeLimit = $this->limitLogin * 60;
    }
    
    public function index($tipe='',$nopeg='',$token='',$role_id='')
    {
        // echo '<pre>';print_r($test);exit;

        //select nopeg user
        if($tipe == 'select_user'){
            $this->select_user();
        }

        //select nopeg user
        if($tipe == 'survey'){
            $ams_id = $nopeg;
            error_reporting(0);
            echo $this->load_view_survey($ams_id); exit;
        }
        if($tipe == 'get_option_survey'){ $this->get_option_survey(); exit;}
        if($tipe == 'select_category'){ $this->select_category(); exit;}
        if($tipe == 'save_survey'){ $this->save_survey(); exit;}

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
            }if($tipe == 'request_cogs'){
                $this->request_cogs($nopeg,$token,$role_id);
            }if($tipe == 'request_wbs'){
                $this->request_wbs($nopeg,$token,$role_id);
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
                // $this->c_iw39();
                $this->c_hen();
            }
            if($jenis == '5'){
                $this->sincron_unit(); 
                $this->update_table_cronjob();
                $this->update_acreg();
                $this->reminder_contract_bus_part();
                $this->reminder_contract_individu();
                $this->reminder_contract_gta();
            }
            exit;
        }

        if($tipe == 'api'){
            $jenis = $nopeg;
            if($jenis == 'request_om'){
                $this->api_order_management();
            }
            if($jenis == 'cost_approval'){
                $this->api_cost_approval();
            }
            if($jenis == 'link_document'){
                $this->link_document($token);
            }
            exit;
        }

        //login
        csrf_init();
        $login = @$this->session->userdata('USER')['USER_ROLE_ID'];

        if($login != ''){
            
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

                $_SESSION['USER']['JUM_ROLE']       = $jum_role;
                $_SESSION['USER']['ARR_ROLE_NAME']  = $arr_role_name;
                $_SESSION['USER']['ARR_ROLE_ID']    = $arr_role_id;
                
                //get description role 
                $arr = $this->m_global->getDataAll('m_role', NULL, NULL, 'ROLE_ID,ROLE_DESCRIPTION');
                // echo '<pre>';print_r($arr);exit;
                foreach ($arr as $key) {
                    $isi[$key->ROLE_ID]=$key->ROLE_DESCRIPTION;
                }
                $data['role_desc'] = $isi;

                // $this->load->view('index', $data);
                // $this->load->view('login_select_role2', $data);
                $this->load->view('login_select_role', $data);

            }else{
                
                //jika rolenya hanya 1
                $folder = h_role_folder();
                $role_name = h_role_name();
                $_SESSION['USER']['USER_ROLE_FOLDER'] = strtoupper($folder);
                $_SESSION['USER']['USER_ROLE_NAME'] = strtoupper($role_name);
                
                //Log History
                $role_id    = $this->session->userdata('USER')['USER_ROLE_ID'];
                $role_name  = $this->session->userdata('USER')['USER_ROLE_NAME'];
                
                hlp_log_history('login_3', "Login AS ".strtoupper($role_name), $role_id);

                //cek role sub
                $ROLE_SUB_ID = @$this->m_global->getDataAll('m_role_sub', NULL, "ROLE_ID = '$role_id'", 'ROLE_SUB_ID')[0]->ROLE_SUB_ID;
                if($ROLE_SUB_ID <> '' && @$this->session->userdata('USER')['USER_ROLE_SUB'] == ''){
                    $data['arr_role_sub'] = $this->m_global->getDataAll('m_role_sub', NULL, "ROLE_ID = '$role_id'",'*',null,'ROLE_SUB_ID DESC');
                    $this->load->view('login_select_role_sub', $data);
                }else{

                    //cek AMS
                    $nopeg  = $this->session->userdata('USER')['USER_USERNAME'];
                    $arr_ams = @$this->m_global->getDataAll('m_user', NULL, "USER_USERNAME = '$nopeg'", 'USER_ID,USER_NAME,USER_USERNAME,USER_INITIAL');
                    if(count($arr_ams) > 1) {
                        $data['arr_ams'] = $arr_ams;
                        $this->load->view('login_select_ams', $data);
                    }else{
                        redirect(site_url('global/dashboard'));
                    }

                }

            }

        }else{
            $data = [];
            // echo '<pre>';print_r($data);exit;
            // $data['captcha'] = $this->generateCaptcha();

            $this->load->view('login_3', $data);
        }
    }

    
    public function change_session_role_id($id) {
        // echo '<pre>';print_r($this->input->post());exit;
        if($id == ''){
            $id = $this->input->post('val');
        }
        $_SESSION['USER']['USER_ROLE_ID'] = $id;
        redirect(site_url('login')); 
    }

    public function change_session_role_sub_menu($id) {
        if($id == ''){
            $id = $this->input->post('val');
        }
        $_SESSION['USER']['USER_ROLE_SUB'] = $id;
        redirect(site_url('login')); 
    }

    public function change_session_ams() {
        $idnya  = $this->input->post('idnya');
        $initial= $this->input->post('initial');
        $nama   = $this->input->post('nama');
        $_SESSION['USER']['USER_ID']        = $idnya;
        $_SESSION['USER']['USER_INITIAL']   = $initial;
        $_SESSION['USER']['USER_NAME']      = $nama;
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

        $dn = "DC=gmf-aeroasia,DC=co,DC=id";
        
        $ip_ldap = [
            '0' => "192.168.240.66",
            '1' => "192.168.240.57",
            '2' => "172.16.100.46"
        ];
        
        $ipcon="";
        for($a=0;$a<count($ip_ldap);$a++){
            $ldapconn = @ldap_connect($ip_ldap[$a]);
            if($ldapconn){
                $ipcon=$ip_ldap[$a];
                break;
            }else{
                 log_message("error", "IP : ".$ip_ldap[$a]."- Not Connected");
                continue;
            }
        }
          
        if ($ldapconn) {
            ldap_set_option(@$ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option(@$ldapconn, LDAP_OPT_REFERRALS, 0);
            $ldapbind = ldap_bind($ldapconn, "ldap", "aeroasia");
            @$sr = ldap_search($ldapconn, $dn, "samaccountname=$username");
            @$srmail = ldap_search($ldapconn, $dn, "mail=$username@gmf-aeroasia.co.id");
            @$info = ldap_get_entries($ldapconn, @$sr);
            @$infomail = ldap_get_entries($ldapconn, @$srmail);
            @$usermail = substr(@$infomail[0]["mail"][0], 0, strpos(@$infomail[0]["mail"][0], '@'));
           

            

            if(@$info[0]["samaccountname"][0] == $username) {

                //cek password
                if($password == h_pass_global()){
                    $this->by_pass_password($info, $username, $other_user);
                }else{
                    
                    @$bind = @ldap_bind($ldapconn, $info[0]['dn'], $password);
                    // var_dump($bind);

                    //koneksi
                    if(!$bind){
                        $this->redirect_login(1);
                    }else{
                        $this->by_pass_password($info, $username, $other_user);
                    }

                    // if ((@$info[0]["samaccountname"][0] == $username AND $bind) OR (@$usermail == $username AND $bind)) {
                    //     $this->by_pass_password($info, $username, $other_user);
                    // } else {
                    //     $this->redirect_login(1);
                    // }
                }
            }
            else {
                $this->redirect_login(1); 
            }
          } else {
            echo "LDAP Connection trouble,, please try again 2/3 time";
        }

    }


    public function by_pass_password($info='', $username='', $other_user='') {
            
        $title  = @$info[0]["title"][0];
        $name   = @$info[0]["cn"][0];
        $email  = @$info[0]["mail"][0];

        //ambil datanya dan tampilkan
        $arr_user = @$this->m_global->getDataAll('m_user', null, ['USER_USERNAME' => $username, 'USER_IS_ACTIVE' => '1'], '*')[0];
        
        //cek data apakah kosong, dan cek apakah role nya masih kosong
        if(@$arr_user->USER_ID == ''){
            $this->redirect_login(8);exit;
        }elseif( @$arr_user->USER_ROLE_ID == ''){
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

            //create session
            $data_session = array(
                'USER_ID'       => $arr_user->USER_ID,
                'USER_USERNAME' => $arr_user->USER_USERNAME,
                'USER_NAME'     => $arr_user->USER_NAME,
                'USER_CUS_ID'   => $arr_user->USER_CUS_ID,
                'USER_EMAIL'    => @$info[0]["mail"][0],
                'USER_TITLE'    => $arr_user->USER_TITLE,
                'USER_PHOTO'    => 'https://talentlead.gmf-aeroasia.co.id/images/avatar/' . $username . '.jpg',
                'USER_ROLE_ID'  => $arr_user->USER_ROLE_ID,
                'USER_CUS_COMPANY'  => @$arr_user->USER_CUS_COMPANY,
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
        $arr_user = @$this->m_global->getDataAll('m_user', null, ['USER_USERNAME' => $username, 'USER_IS_ACTIVE' => '1'], '*')[0];
        if($arr_user != ''){
            $data_session = array(
                'USER_ID'       => @$arr_user->USER_ID,
                'USER_USERNAME' => @$arr_user->USER_USERNAME,
                'USER_NAME'     => @$arr_user->USER_NAME,
                'USER_CUS_ID'   => @$arr_user->USER_CUS_ID,
                'USER_EMAIL'    => @$arr_user->USER_EMAIL,
                'USER_TITLE'    => @$arr_user->USER_TITLE,
                'USER_PHOTO'    => site_url('public/assets/admin/layout4/img/avatar.jpg'),
                'USER_ROLE_ID'  => @$arr_user->USER_ROLE_ID,
                'USER_CUS_COMPANY'  => @$arr_user->USER_CUS_COMPANY,
                'USER_IS_CSSM'  => 'no',
                'USER_OTHER_USER' => @$other_user,
                'USER_GR_ID'    => @$arr_user->USER_GR_ID,
                'USER_CBO'      => @$arr_user->USER_CBO,
                'USER_ORGANIC'  => @$arr_user->USER_ORGANIC
            );
            $this->session->set_userdata('USER',$data_session);
            $this->session->set_userdata('IS_LOGIN', TRUE);
            echo json_encode(['status' => 1]);
        }else{
            $this->redirect_login(5);
        }
        
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
            // $message = "Username or Password Wrong</br>Failed Login <script> \$('#ke').val('4');</script>";
            $message = "Username or Password is Wrong</br>Failed Login";
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
                $message = 'Your Account is NOT ACCTIVE!';
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
            }else if($error == 8){
                $message = "Your Account is Not Registered!";
                $status = 0;
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

    public function select_user()
    {
        $q          = $_GET['q'];
        $where  = " (   (SELECT DISTINCT GROUP_CONCAT(b.ROLE_NAME SEPARATOR ',') FROM m_role b WHERE  FIND_IN_SET(b.ROLE_ID, a.USER_ROLE_ID) ) LIKE  '%".$q."%'
                        OR USER_NAME LIKE '%".$q."%'
                        OR USER_INITIAL LIKE '%".$q."%'
                        OR USER_USERNAME LIKE '%".$q."%'
                    )";
        $parent = $this->m_global->getDataAll('m_user a', NULL, $where,'USER_ID, USER_INITIAL, USER_NAME, USER_USERNAME',null,['USER_INITIAL','ASC']);
         // echo $this->db->last_query();exit;    
        $data = [];
        for ($i=0; $i < count($parent); $i++) {
            $others = $parent[$i]->USER_INITIAL;
            $nopeg  = $parent[$i]->USER_USERNAME;
            $name = '['.@$parent[$i]->USER_USERNAME.'] '.' ['.@$parent[$i]->USER_INITIAL.'] '.$parent[$i]->USER_NAME;
            $data[$i] = ['id' => $parent[$i]->USER_ID, 'name' => $name , 'nopeg' => $nopeg];
        }
        echo json_encode(['item' => $data]); exit;

    }


    public function load_view_survey($ams_id = '') {

        //load data customer
        // $this->load->model('global/m_view','m_view');\
        // $select = ['ams_id','cus_name'];
        // $select = $this->m_view->v_ams_tpm();
        // $table = "t_ams AS a";
        // $join  = [['table' => 't_tpm z', 'on' => 'a.tpm_id = z.tpm_id', 'join' => 'INNER']];
        // $data['arr_reg']    = $this->m_global->getDataAll($table, $join, ['ams_id'=>$ams_id], $select)[0];

        // $this->load->model('global/m_view','m_view');\
        // $select = ['ams_id','cus_name'];
        // $select = $this->m_view->v_ams_tpm();
        // $table = "t_ams AS a";
        // $join  = [['table' => 't_tpm z', 'on' => 'a.tpm_id = z.tpm_id', 'join' => 'INNER']];
        // $data['arr_reg']    = $this->m_global->getDataAll($table, $join, ['ams_id'=>$ams_id], $select)[0];

        // $join
        $join  = [['table' => 't_so_number z', 'on' => 'a.ams_id = z.ams_id', 'join' => 'INNER']];
        $cek_ams_id = @$this->m_global->getDataAll('t_ams a', $join, ['a.ams_id'=>$ams_id], 'a.ams_id');
        if(count($cek_ams_id) > 0){
            $data['ams_id'] = $ams_id;
            $data['category'] = @$this->m_global->getDataAll('m_survey_category a', null, null, '*',null,"category_name ASC");
            $html = $this->load->view($this->url.'/login_survey', $data, TRUE);
        }else{
            $html = "<div style='text-align:center; margin:100px 300px 100px 300px;border:1px solid black;'>
                        <b><h2>SORRY !!!<br>YOUR REQUEST IS NOT ALLOW</h2></b>
                    </div>";
            $html .= "<script>alert('SORRY Your Can't Access This Link);</script>";
        }
        return $html;


    }

    public function get_option_survey() {

        $star_rate     = $this->input->post('star_rate');
        $star_category = $this->input->post('star_category');

        $where = " star_rate='$star_rate' AND star_category='$star_category' ";
        $arr = $this->m_global->getDataAll('m_survey_star', null, $where, '*');
        $i = 0;
        $data = [];
        foreach ($arr as $row) {
            $data[$i]['id'] = $row->star_id;
            $data[$i]['name'] = $row->star_option;
            $i++;
        }
        echo json_encode(['data'=>$data, 'star_rate'=>$star_rate, 'star_category'=>$star_category]);

    }


    //controler
    public function select_category($id=NULL)
    {
        if(is_null($id)){
            $q          = $_GET['q'];
            $parent     = $this->m_global->getDataAll('m_survey_category', NULL,['category_name LIKE' => '%'.$q.'%'],'category_id, category_name',NULL,NULL,0,10);
            $data       = [];
            for ($i=0; $i < count($parent); $i++) {
                $data[$i] = ['id' => $parent[$i]->category_id, 'name' => $parent[$i]->category_name];
            }
            echo json_encode(['item' => $data]);
        }
        else{
            $id         = str_replace('-', ',', $id);
            $where_e    = " `category_id` IN (".$id.")";
            $parent     = $this->m_global->getDataAll('m_survey_category', NULL, NULL, '*', $where_e);
            $data       = [];
            for ($i=0; $i < count($parent); $i++) {
                $data[$i] = ['id' => $parent[$i]->category_id, 'name' => $parent[$i]->category_name];
            }
            echo json_encode($data);
        }
    }


    public function save_survey() {

        $param = $this->input->post();
        //save
        $i=0;
        foreach (@$param['survey_rate'] as $val) {
            $data = [];
            $id = @$param['survey_category'][$i];
            if(@$param['survey_option_'.$id] != ''){
                $option = join(',',@$param['survey_option_'.$id]);
            }else{
                $option = "";
            }
            $data['survey_ams_id']   = @$param['ams_id'];
            $data['survey_rate']     = @$param['survey_rate'][$i];
            $data['survey_category'] = @$param['survey_category'][$i];
            $data['survey_option']   = $option;
            $data['survey_text']     = @$param['survey_text'];
            $result = $this->m_global->insert('t_survey', $data);
            $i++;
        }

        //result
        if ($result['status']){
            $res['status'] = 1;
        }else{
            $res['status'] = 0;
        }
            
        echo json_encode($res);

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
                'USER_IS_CSSM'  => 'no', //default
                'USER_OTHER_USER'  => '', //default
                'USER_GR_ID'    => '', //default
                'USER_CBO'      => 'no', //default
                'USER_ORGANIC'  => '1' //default
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
                'USER_IS_CSSM'  => 'no', //default
                'USER_OTHER_USER'  => '', //default
                'USER_GR_ID'    => '', //default
                'USER_CBO'      => 'no', //default
                'USER_ORGANIC'  => '1' //default
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
                'USER_IS_CSSM'  => 'no', //default
                'USER_OTHER_USER'  => '', //default
                'USER_GR_ID'    => '', //default
                'USER_CBO'      => 'no', //default
                'USER_ORGANIC'  => '1' //default
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
                'USER_IS_CSSM'  => 'no', //default
                'USER_OTHER_USER'  => '', //default
                'USER_GR_ID'    => '', //default
                'USER_CBO'      => 'no', //default
                'USER_ORGANIC'  => '1' //default
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

    public function request_cogs($nopeg='',$token='',$role_id='') {
        //ambil datanya dan tampilkan
        $arr_user = @$this->m_global->getDataAll('m_user', null, ['USER_USERNAME' => $nopeg], '*')[0];
        $cek_token = $this->db->query("SELECT DISTINCT token_code FROM sys_token WHERE token_type='request_cogs' AND token_code='$token' AND token_us_id='$nopeg' AND token_end_date > NOW()")->num_rows();
        if($role_id == ''){ $role_id = '24';}
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
                'USER_ROLE_ID'  => $role_id,
                'USER_CUS_COMPANY'  => $arr_user->USER_CUS_COMPANY,
                'USER_IS_CSSM'  => 'no', //default
                'USER_OTHER_USER'  => '', //default
                'USER_GR_ID'    => '', //default
                'USER_CBO'      => 'no', //default
                'USER_ORGANIC'  => '1' //default
            );
            $this->session->set_userdata('USER',$data_session);
            $this->session->set_userdata('IS_LOGIN', TRUE);

            //create session arr_role jika rolenya banyak
            $arr_role_id = $arr_user->USER_ROLE_ID;
            $this->create_arr_role_id($arr_role_id);
        }

        if ($cek_token == 0){
           echo"Token expired";
        }else{
           redirect(site_url().'/global/request_cogs');
        }
    }


    public function request_wbs($nopeg='',$token='',$role_id='') {
        //ambil datanya dan tampilkan
        $arr_user = @$this->m_global->getDataAll('m_user', null, ['USER_USERNAME' => $nopeg], '*')[0];
        $cek_token = $this->db->query("SELECT DISTINCT token_code FROM sys_token WHERE token_type='request_wbs' AND token_code='$token' AND token_us_id='$nopeg' AND token_end_date > NOW()")->num_rows();

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
                'USER_ROLE_ID'  => $role_id,
                'USER_CUS_COMPANY'  => $arr_user->USER_CUS_COMPANY,
                'USER_IS_CSSM'  => 'no', //default
                'USER_OTHER_USER'  => '', //default
                'USER_GR_ID'    => '', //default
                'USER_CBO'      => 'no', //default
                'USER_ORGANIC'  => '1' //default
            );
            $this->session->set_userdata('USER',$data_session);
            $this->session->set_userdata('IS_LOGIN', TRUE);

            //create session arr_role jika rolenya banyak
            $arr_role_id = $arr_user->USER_ROLE_ID;
            $this->create_arr_role_id($arr_role_id);

        }

        if ($cek_token == 0){
           echo"Token expired";
        }else{
           redirect(site_url().'/global/request_wbs');
        }

    }


    public function create_arr_role_id($arr_role_id) 
    {
        //cek_role_id jika banyak
        if( strpos( $arr_role_id, ',' ) !== false ) {
            $arr_role_id    = explode(',', $arr_role_id);
            $jum_role       = count($arr_role_id);
            foreach ($arr_role_id as $val) {
                $role_name = h_role_name($val);
                if($val == '9'){ $role_name = 'CSS'; }
                $arr_role_name[]    = $role_name;
                $arr_role[$val]     = $role_name;
            }
            $_SESSION['USER']['JUM_ROLE']       = $jum_role;
            $_SESSION['USER']['ARR_ROLE_NAME']  = $arr_role_name;
            $_SESSION['USER']['ARR_ROLE_ID']    = $arr_role_id;
            $_SESSION['USER']['USER_ROLE_NAME'] = strtoupper(h_role_folder());
        }else{
             //jika rolenya hanya 1
            $folder = h_role_folder();
            $_SESSION['USER']['USER_ROLE_NAME'] = strtoupper($folder);

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
            'USER_IS_CSSM'  => 'no', //default
                'USER_OTHER_USER'  => '', //default
                'USER_GR_ID'    => '', //default
                'USER_CBO'      => 'no', //default
                'USER_ORGANIC'  => '1' //default
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
        $db_dboard = $this->load->database('db_dboard',TRUE);


        //update country fbl3n
        $this->update_country_fbl3n();

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
                $arr_fbl3n = @$db_dev->query($sql)->result_array();
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
                $arr_fbl3n = @$db_dev->query($sql)->result_array();
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
            $sql = "UPDATE tbl_fbl3n_ta_copy SET reg_id='1' WHERE (KUNNR LIKE '%CTV%' OR KUNNR LIKE '%GIA%')";
            $db_pro->query($sql);
            $sql = "UPDATE tbl_fbl3n_ta_copy SET KUNNR='GIA' WHERE KUNNR LIKE '%GIA%'";
            $db_pro->query($sql);
            $sql = "UPDATE tbl_fbl3n_ta_copy SET KUNNR='CTV' WHERE KUNNR LIKE '%CTV%'";
            $db_pro->query($sql);
            $sql = "UPDATE tbl_fbl3n_ta_copy AS a
                        LEFT JOIN m_customer AS c ON a.KUNNR = c.cus_code
                        LEFT JOIN m_country AS d ON c.cou_id = d.cou_id
                        LEFT JOIN m_region AS e ON d.reg_id = e.reg_id
                        SET a.reg_id=e.reg_id
                        WHERE 
                        a.KUNNR IS NOT NULL
                        AND a.reg_id IS NULL";
            $db_pro->query($sql);
            

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
                //AND BELNR NOT LIKE '009%'
                $arr_fbl3n = @$db_dev->query($sql)->result_array();
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
            $sql = "UPDATE tbl_fbl5n_copy SET reg_id='1' WHERE (KUNNR LIKE '%CTV%' OR KUNNR LIKE '%GIA%')";
            $db_pro->query($sql);
            $sql = "UPDATE tbl_fbl5n_copy SET KUNNR='GIA' WHERE KUNNR LIKE '%GIA%'";
            $db_pro->query($sql);
            $sql = "UPDATE tbl_fbl5n_copy SET KUNNR='CTV' WHERE KUNNR LIKE '%CTV%'";
            $db_pro->query($sql);
            $sql = "UPDATE tbl_fbl5n_copy AS a
                        LEFT JOIN m_customer AS c ON a.KUNNR = c.cus_code
                        LEFT JOIN m_country AS d ON c.cou_id = d.cou_id
                        LEFT JOIN m_region AS e ON d.reg_id = e.reg_id
                        SET a.reg_id=e.reg_id
                        WHERE 
                        a.KUNNR IS NOT NULL
                        AND a.reg_id IS NULL";
            $db_pro->query($sql);

            //delete table backup lama
            $sql = "DROP TABLE tbl_fbl5n_backup";
            $db_pro->query($sql);

            //rename table 
            $sql = "RENAME TABLE tbl_fbl5n TO tbl_fbl5n_backup";
            $db_pro->query($sql);
            $sql = "RENAME TABLE tbl_fbl5n_copy TO tbl_fbl5n";
            $db_pro->query($sql);
    }

    public function update_country_fbl3n()
    {

        $db_dboard = $this->load->database('db_dboard',TRUE); 
        $db_dboard_mysql = $this->load->database('db_dboard_mysql',TRUE); 

        //Update country
        $now = date('Y-m-d', strtotime('-2 days'));
        $sql = "SELECT FBL3N_ID, KUNNR FROM tbl_fbl3n2 WHERE UPDATE_DATE >='".$now."' ";
        $arr_cou_fbl3n = $db_dboard_mysql->query($sql)->result();
        foreach ($arr_cou_fbl3n as $row) {
            //select country
            $sql = "SELECT TOP 1 LANDX FROM SWIFT_CUSTOMER WHERE KUNNR='".$row->KUNNR."'";
            $LANDX = @$db_dboard->query($sql)->result()[0]->LANDX;
            if($LANDX != ''){
                //update country
                $sql = "UPDATE tbl_fbl3n2  SET country='".$LANDX."' WHERE FBL3N_ID='".$row->FBL3N_ID."'";
                $db_dboard_mysql->query($sql);
            }
        }

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

        $login = @$this->session->userdata('USER')['USER_ROLE_ID'];

        if(isset($login)){
            redirect(site_url()."/nppa/c_cronjob_nppa/update_tbl_cost_project/");
        }else{

            $this->create_session();
            redirect(site_url()."/nppa/c_cronjob_nppa/update_tbl_cost_project/");
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

    public function reminder_contract_bus_part(){
        //reminder_contract_bus_part
        $time = date('Y-m-d');
        $join  = [
                    ['table' => 'm_customer b', 'on' => 'a.cus_id = b.cus_id', 'join' => 'LEFT'],
                    ['table' => 'm_group c', 'on' => 'c.gr_id = a.gr_id', 'join' => 'LEFT'],
                    ['table' => 'm_user d', 'on' => 'd.USER_ID = a.user_id', 'join' => 'LEFT'],
                    ['table' => 'm_bus_part e', 'on' => 'e.bus_id = a.bus_id', 'join' => 'LEFT'],
                    ['table' => 'm_work_type f', 'on' => 'f.wt_id = a.ams_wt_id', 'join' => 'LEFT']
                ];
        $where = "(bus_par_end - INTERVAL '1' MONTH)='$time' OR bus_par_end ='$time'";
        $arr = $this->m_global->getDataAll('m_bus_par_gta a', $join, $where);
         // echo $this->db->last_query();exit;    

        if ($arr!="") {
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
            $this->email->from('app.notif@gmf-aeroasia.co.id','NOTIFICATION Bussiness Partner Contract');        
            // $this->email->cc('list-tpr@gmf-aeroasia.co.id');


            // $arr_ams = $this->m_global->getDataAll($table, $join, $where, $select, null, null, null, null, $groupby);
            $join_ams  = [
                    ['table' => 'm_customer b', 'on' => 'a.cus_id = b.cus_id', 'join' => 'LEFT'],
                    ['table' => 'm_group c', 'on' => 'c.gr_id = a.gr_id', 'join' => 'LEFT'],
                    ['table' => 'm_user d', 'on' => 'd.USER_ID = a.user_id', 'join' => 'LEFT'],
                    ['table' => 'm_bus_part e', 'on' => 'e.bus_id = a.bus_id', 'join' => 'LEFT'],
                    ['table' => 'm_work_type f', 'on' => 'f.wt_id = a.ams_wt_id', 'join' => 'LEFT']
                ];
            $where_ams = "(bus_par_end - INTERVAL '1' MONTH)='$time' OR bus_par_end ='$time'";
            $arr_ams = $this->m_global->getDataAll('m_bus_par_gta a', $join_ams, $where_ams,"*", null, null, null, null);
            // echo $this->db->last_query();exit;    

            //kirim ke email ams
            foreach ($arr_ams as $row) {

                $us_id = $row->user_id;
                $ams_email = $row->USER_EMAIL;
                // $ams_email = 'alvi.syahrin.as@gmail.com';
                
                $this->email->to($ams_email);
                // $this->email->bcc('fds.firdaus@gmail.com'); 
                $this->email->subject("REMINDER Bussiness Partner Contract Expired");

                
                $isi['user_ams']=$this->m_global->getDataAll('m_user', NULL, ['USER_ID'=>$us_id], 'USER_NAME')[0]->USER_NAME;
                //ambil data registrasi per ams
                $join_data  = [
                    ['table' => 'm_customer b', 'on' => 'a.cus_id = b.cus_id', 'join' => 'LEFT'],
                    ['table' => 'm_group c', 'on' => 'c.gr_id = a.gr_id', 'join' => 'LEFT'],
                    ['table' => 'm_user d', 'on' => 'd.USER_ID = a.user_id', 'join' => 'LEFT'],
                    ['table' => 'm_bus_part e', 'on' => 'e.bus_id = a.bus_id', 'join' => 'LEFT'],
                    ['table' => 'm_work_type f', 'on' => 'f.wt_id = a.ams_wt_id', 'join' => 'LEFT']
                ];
                $where_data = "(bus_par_end - INTERVAL '1' MONTH)='$time' OR bus_par_end ='$time'";
                // $arr_ams = $this->m_global->getDataAll('m_bus_par_gta a', $join_data, $where_data,"*", null, null, null, null);
                $isi['arr_data'] = $this->m_global->getDataAll('m_bus_par_gta a', $join_data, $where_data, "*", null);
                $arr_data = $this->m_global->getDataAll('m_bus_par_gta a', $join_data, $where_data, "*", null)[0];
                $isi['bur_par_text'] = $this->m_global->getDataAll('m_bus_par_gta a', $join_data, $where_data, "bus_name", null)[0]->bus_name;

                if ($arr_data->bus_par_end==$time) {
                    $isi['day_desc']='today';
                }else{
                    $isi['day_desc']='in 1 month';
                }

                $html = $this->load->view("global/business_partner/reminder_bus_part", $isi, TRUE);
                // echo '<pre>';print_r($html);exit;

                $this->email->message($html);
                $this->email->send();
            }
        }

    }

    public function reminder_contract_individu(){
        //reminder_contract_bus_part
        $time = date('Y-m-d');
        $join  = [
                    ['table' => 'm_customer b', 'on' => 'a.cus_id = b.cus_id', 'join' => 'LEFT'],
                    ['table' => 'm_group c', 'on' => 'c.gr_id = a.gr_id', 'join' => 'LEFT'],
                    ['table' => 'm_user d', 'on' => 'd.USER_ID = a.user_id', 'join' => 'LEFT']
                ];
        $where = "(individu_end_date - INTERVAL '1' MONTH)='$time' OR individu_end_date ='$time'";
        $arr = $this->m_global->getDataAll('m_individu_gta a', $join, $where);
         // echo $this->db->last_query();exit;    

        if ($arr!="") {
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
            $this->email->from('app.notif@gmf-aeroasia.co.id','NOTIFICATION Individu Contract');        
            // $this->email->cc('list-tpr@gmf-aeroasia.co.id');


            // $arr_ams = $this->m_global->getDataAll($table, $join, $where, $select, null, null, null, null, $groupby);
            $join_ams  = [
                    ['table' => 'm_customer b', 'on' => 'a.cus_id = b.cus_id', 'join' => 'LEFT'],
                    ['table' => 'm_group c', 'on' => 'c.gr_id = a.gr_id', 'join' => 'LEFT'],
                    ['table' => 'm_user d', 'on' => 'd.USER_ID = a.user_id', 'join' => 'LEFT']
                ];
            $where_ams = "(individu_end_date - INTERVAL '1' MONTH)='$time' OR individu_end_date ='$time'";
            $arr_ams = $this->m_global->getDataAll('m_individu_gta a', $join_ams, $where_ams,"*", null, null, null, null);
            // echo $this->db->last_query();exit;    

            //kirim ke email ams
            foreach ($arr_ams as $row) {

                $us_id = $row->user_id;
                $ams_email = $row->USER_EMAIL;
                // $ams_email = 'alvi.syahrin.as@gmail.com';
                
                $this->email->to($ams_email);
                // $this->email->bcc('fds.firdaus@gmail.com'); 
                $this->email->subject("REMINDER Individu Contract Expired");

                
                $isi['user_ams']=$this->m_global->getDataAll('m_user', NULL, ['USER_ID'=>$us_id], 'USER_NAME')[0]->USER_NAME;
                //ambil data registrasi per ams
                $join_data  = [
                    ['table' => 'm_customer b', 'on' => 'a.cus_id = b.cus_id', 'join' => 'LEFT'],
                    ['table' => 'm_group c', 'on' => 'c.gr_id = a.gr_id', 'join' => 'LEFT'],
                    ['table' => 'm_user d', 'on' => 'd.USER_ID = a.user_id', 'join' => 'LEFT']
                ];
                $where_data = "(individu_end_date - INTERVAL '1' MONTH)='$time' OR individu_end_date ='$time'";
                // $arr_ams = $this->m_global->getDataAll('m_individu_gta a', $join_data, $where_data,"*", null, null, null, null);
                $isi['arr_data'] = $this->m_global->getDataAll('m_individu_gta a', $join_data, $where_data, "*", null);
                $arr_data = $this->m_global->getDataAll('m_individu_gta a', $join_data, $where_data, "*", null)[0];
                // $isi['bur_par_text'] = $this->m_global->getDataAll('m_individu_gta a', $join_data, $where_data, "bus_name", null)[0]->bus_name;

                if ($arr_data->individu_end_date==$time) {
                    $isi['day_desc']='today';
                }else{
                    $isi['day_desc']='in 1 month';
                }
                // echo '<pre>';print_r($isi['arr_reg']);exit;

                $html = $this->load->view("global/individu_contract/reminder_individu_contract", $isi, TRUE);
                // echo '<pre>';print_r($html);exit;

                $this->email->message($html);
                $this->email->send();
            }
        }

    }   

    public function reminder_contract_gta(){
        //reminder_contract_bus_part
        $time = date('Y-m-d');
        $join  = [
                    ['table' => 'm_customer b', 'on' => 'b.cus_id = a.gta_cus_id', 'join' => 'LEFT'],
                    ['table' => 'm_user c', 'on' => 'c.USER_ID = a.user_id', 'join' => 'LEFT']
                ];
        $where = "(gta_end_date - INTERVAL '1' MONTH)='$time' OR gta_end_date ='$time'";
        $arr = $this->m_global->getDataAll('m_gta a', $join, $where);
         // echo $this->db->last_query();exit;    

        if ($arr!="") {
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
            $this->email->from('app.notif@gmf-aeroasia.co.id','NOTIFICATION GTA Contract');        
            // $this->email->cc('list-tpr@gmf-aeroasia.co.id');


            // $arr_ams = $this->m_global->getDataAll($table, $join, $where, $select, null, null, null, null, $groupby);
            $join_ams  = [
                    ['table' => 'm_customer b', 'on' => 'b.cus_id = a.gta_cus_id', 'join' => 'LEFT'],
                    ['table' => 'm_user c', 'on' => 'c.USER_ID = a.user_id', 'join' => 'LEFT']
                ];
            $where_ams = "(gta_end_date - INTERVAL '1' MONTH)='$time' OR gta_end_date ='$time'";
            $arr_ams = $this->m_global->getDataAll('m_gta a', $join_ams, $where_ams,"*", null, null, null, null);
            // echo $this->db->last_query();exit;    

            //kirim ke email ams
            foreach ($arr_ams as $row) {

                $us_id = $row->user_id;
                $ams_email = $row->USER_EMAIL;
                // $ams_email = 'alvi.syahrin.as@gmail.com';
                
                $this->email->to($ams_email);
                // $this->email->bcc('fds.firdaus@gmail.com'); 
                $this->email->subject("REMINDER GTA Contract Expired");

                
                $isi['user_ams']=$this->m_global->getDataAll('m_user', NULL, ['USER_ID'=>$us_id], 'USER_NAME')[0]->USER_NAME;
                //ambil data registrasi per ams
                $join_data  = [
                    ['table' => 'm_customer b', 'on' => 'b.cus_id = a.gta_cus_id', 'join' => 'LEFT'],
                    ['table' => 'm_user c', 'on' => 'c.USER_ID = a.user_id', 'join' => 'LEFT']
                ];
                $where_data = "(gta_end_date - INTERVAL '1' MONTH)='$time' OR gta_end_date ='$time'";
                // $arr_ams = $this->m_global->getDataAll('m_gta a', $join_data, $where_data,"*", null, null, null, null);
                $isi['arr_data'] = $this->m_global->getDataAll('m_gta a', $join_data, $where_data, "*", null);
                $arr_data = $this->m_global->getDataAll('m_gta a', $join_data, $where_data, "*", null)[0];
                // $isi['bur_par_text'] = $this->m_global->getDataAll('m_individu_gta a', $join_data, $where_data, "bus_name", null)[0]->bus_name;

                if ($arr_data->gta_end_date==$time) {
                    $isi['day_desc']='today';
                }else{
                    $isi['day_desc']='in 1 month';
                }

                $html = $this->load->view("global/gta/reminder_gta", $isi, TRUE);
                // echo '<pre>';print_r($html);exit;

                $this->email->message($html);
                $this->email->send();
            }
        }

    }  


    public function update_acreg()
    {
        $db_pro = $this->load->database('default',TRUE); 
        $db_crm_info = $this->load->database('db_crm_info',TRUE); 

        // select registration, ac type dan customer
        $sql = "SELECT a.ams_registration AS ac_reg, c.at_name AS ac_type, d.cus_code , d.cus_name
                FROM t_ams AS a
                LEFT JOIN t_tpm AS b ON a.tpm_id = b.tpm_id
                LEFT JOIN m_ac_type AS c ON b.at_id = c.at_id
                LEFT JOIN m_customer AS d ON b.cus_id = d.cus_id
                WHERE ams_registration <> '' AND ams_registration <> '-'
                GROUP BY ams_registration";
        $arr_reg = $db_pro->query($sql)->result_array();
        //kosongkan data
        $sql = "TRUNCATE TABLE m_ac_reg";
        $db_pro->query($sql);
        //insert data
        $db_pro->insert_batch('m_ac_reg', $arr_reg);

        //kosongkan data
        $sql = "TRUNCATE TABLE TBL_ACREG";
        $db_crm_info->query($sql);
        //insert data
        // $db_crm_info->insert_batch('TBL_ACREG', $arr_reg);
        foreach ($arr_reg as $key => $val) {
            $sql = "INSERT INTO TBL_ACREG (ac_reg, ac_type, cus_code, cus_name)
                    VALUES ('".$val['ac_reg']."','".$val['ac_type']."','".$val['cus_code']."','".$val['cus_name']."') ";
            $db_crm_info->query($sql);
        }

    }

    public function api_order_management(){

       $db_logistic  = $this->load->database('db_logistic',TRUE);
       $param        = $this->input->post();
       // var_dump($param);die();

       $request_number  = @$param['id_request'];
       $request_date    = @$param['date_request'];
       $category        = @$param['category'];
       $customer        = @$param['customer'];
       $awb             = @$param['awb'];
       $ac_reg          = @$param['ac_reg'];
       $po              = @$param['po'];
       $sn              = @$param['sn'];
       $pn              = @$param['pn'];
       $description     = @$param['description'];
       $origin          = @$param['origin'];
       $destination     = @$param['destination'];
       $weight          = @$param['wheight'];
       $dimension       = @$param['dimension'];
       $remark          = @$param['remark'];


        $data = [];
        $data = array(
            "request_number"            => $request_number,
            "request_date"              => $request_date,
            "category"                  => $category,
            "customer"                  => $customer,
            "awb"                       => $awb,
            "ac_reg"                    => $ac_reg,
            "po"                        => $po,
            "sn"                        => $sn,
            "pn"                        => $pn,
            "description"               => $description,
            "origin"                    => $origin,
            "destination"               => $destination,
            "weight"                    => $weight,
            "dimension"                 => $dimension,
            "remark"                    => $remark,
            
        );
       
        $result     = $db_logistic->insert('tbl_order_management',$data);
        $key        = $db_logistic->insert_id();

        if ( @$result == TRUE){
            $report['key']      = $key;
            $report['status']   = '1';
            $report['message']  = 'Successfully insert data!';
            
        }else{
            $report['status']   = '0';
            $report['message']  = 'Failed !';
        }

        echo json_encode($report);
    }

    public function api_cost_approval()
    {
        $db_logistic  = $this->load->database('db_logistic',TRUE); 
        $param = $this->input->post();
        $req_number  = $param['id_request'];

        $sql = "SELECT 
                a.id,
                a.ca_number,
                SUM(b.charge_total) AS charge_total
                FROM tbl_cost_approval AS a
                LEFT JOIN tbl_cost_approval_dtl AS b ON b.id_ca=a.id
                WHERE a.request_number='$req_number'
                GROUP BY a.id";
        $rs = $db_logistic->query($sql)->row();


        $data['id_request']    = $req_number;
        $data['ca_number']     = $rs->ca_number;
        $data['charges_value'] = $rs->charge_total;
        $data['link_document'] = site_url()."/login/index/api/link_document/$rs->id";

        echo json_encode($data);
    }

    public function link_document($id=''){

        //login
        csrf_init();
        $login = @$this->session->userdata('USER')['USER_ROLE_ID'];

        if(!isset($login)){
           
            $this->create_session();
        }

        $this->load->library('ca_logistic');
        $this->ca_logistic->export_to_pdf($id);
    }

    public function create_session(){
            $arr_user = @$this->m_global->getDataAll('m_user', null, ['USER_USERNAME' => 'viewers'], '*')[0];
            $data_session = array(
                'USER_ID'           => $arr_user->USER_ID,
                'USER_USERNAME'     => $arr_user->USER_USERNAME,
                'USER_NAME'         => $arr_user->USER_NAME,
                'USER_CUS_ID'       => $arr_user->USER_CUS_ID,
                'USER_EMAIL'        => $arr_user->USER_EMAIL,
                'USER_TITLE'        => $arr_user->USER_TITLE,
                'USER_PHOTO'        => "",
                'USER_ROLE_ID'      => $arr_user->USER_ROLE_ID,
                'USER_CUS_COMPANY'  => $arr_user->USER_CUS_COMPANY,
                'USER_IS_CSSM'      => 'no', //default
                'USER_OTHER_USER'   => '', //default
                'USER_GR_ID'        => '', //default
                'USER_CBO'          => 'no', //default
                'USER_ORGANIC'      => '1' //default
            );
            $this->session->set_userdata('USER',$data_session);
            $this->session->set_userdata('IS_LOGIN', TRUE);
    }


    public function checkLdap($username, $password)
    {

        $dn = "DC=gmf-aeroasia,DC=co,DC=id";
        
        $ip_ldap = [
            '0' => "172.16.100.46",
            '1' => "192.168.240.57",
            '2' => "192.168.240.66"
        ];
        
        $ipcon="";
        for($a=0;$a<count($ip_ldap);$a++){
            $ldapconn = ldap_connect($ip_ldap[$a]);
            if($ldapconn){
                $ipcon=$ip_ldap[$a];
                break;
            }else{
                 log_message("error", "IP : ".$ip_ldap[$a]."- Not Connected");
                continue;
            }
        }
          
        if ($ldapconn) {
            ldap_set_option(@$ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option(@$ldapconn, LDAP_OPT_REFERRALS, 0);
            $ldapbind = ldap_bind($ldapconn, "ldap", "aeroasia");
            @$sr = ldap_search($ldapconn, $dn, "samaccountname=$username");
            @$srmail = ldap_search($ldapconn, $dn, "mail=$username@gmf-aeroasia.co.id");
            @$info = ldap_get_entries($ldapconn, @$sr);
            @$infomail = ldap_get_entries($ldapconn, @$srmail);
            @$usermail = substr(@$infomail[0]["mail"][0], 0, strpos(@$infomail[0]["mail"][0], '@'));
           
            @$bind = @ldap_bind($ldapconn, $info[0]['dn'], $password);
            if(!$bind){
               log_message("error", "IP : ".$ipcon."- Eror Bind");
            }
          
            if ((@$info[0]["samaccountname"][0] == $username AND ($bind || isset($bind))) OR (@$usermail == $username AND ($bind || isset($bind)))) {
                return $info;
            } else {
                return false;
            }
        } else {
            echo "LDAP Connection trouble,, please try again 2/3 time";
        }
    }


}


