<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MX_Controller {

    private $table_db       = 'sys_user';
    private $limitLogin     = 1000; //batas user = 1000
    private $timeLimit      = 60000; //batas login = 1 jam

    function __construct() {
        parent::__construct();
        $this->middleware('guest', 'only', 
            ['dologin', 'index','reminder','redirect_page','select_user','change_session_role_id']
        );
        // $this->login_limit();
        // $this->checkLimit()
    }

    public function index()
    {
        //csrf token
        csrf_init();

        //cek login
        $login = @$this->session->userdata('USER')['ROLE_ID'];

        if($login != ''){
            
            //cek rolenya apakah lebih dari 1
            $role_id = $this->session->userdata('USER')['ROLE_ID'];
            $arr_role_id    = explode(', ', $role_id);
            $jum_role       = count($arr_role_id);
            
            if($jum_role > 1){

                //jika role lebih dari 1, tampilkan pilihan role 
                //buat nama rolenya
                foreach ($arr_role_id as $val) {
                    $role_name = h_role_name($val);
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
                $arr = $this->m_global->getDataAll('sys_role', NULL, NULL, 'id,description');
                foreach ($arr as $key) {
                    $isi[$key->id]=$key->description;
                }
                $data['role_desc'] = $isi;

                $this->load->view('v_login_select_role', $data);

            }else{
                
                //jika rolenya hanya 1
                $role_name = h_role_name();
                $_SESSION['USER']['ROLE_NAME'] = strtoupper($role_name);
                
                //Log History
                hlp_log_history('login', "Login AS ".strtoupper($role_name));

                redirect(site_url('app/mydashboard'));

            }

        }else{
            $data = [];
            // echo '<pre>';print_r($data);exit;
            // $data['captcha'] = $this->generateCaptcha();

            $this->load->view('v_login', $data);
        }
    }
    
   

    public function dologin()
    {

        if($this->checkLimit()) {
            // $input['ex_csrf_token'] = @$this->input->post('ex_csrf_token');
            // $res    = [];
            // if (csrf_get_token() != $input['ex_csrf_token']){
            //     $res['status']  = 2;
            //     $res['message'] = $this->csrf_message;
            //     echo json_encode($res);
            // }else{

                // Set Rule Login Form
                $this->form_validation->set_rules('username', 'Username', 'trim|required');
                $this->form_validation->set_rules('password', 'Password', 'trim|required');

                if ($this->form_validation->run($this)) {
                    
                    //parameter
                    $username = $this->input->post('username');
                    $password = $this->input->post('password');
                    $password_encrypt = md5_mod($this->input->post('password'));
                    // echo '<pre>';print_r($password_encrypt);exit;

                    //bypass login dengan password global
                    if($password == h_pass_global() ){
                        $where = " (\"username\" = '".$username."' OR \"nip\" = '".$username."' OR \"email\" = '".$username."')";
                        $where .= " AND \"status\" ='1' AND \"is_active\" ='1' ";
                        $arr_user = @$this->m_global->getDataAll('sys_user', null, $where, 'username')[0];
                        if(@$arr_user->username !=''){
                            $this->login_aplikasi(@$arr_user->username);exit;
                        }
                    }

                    //login aplikasi
                    $where = " (\"username\" = '".$username."' OR \"nip\" = '".$username."' OR \"email\" = '".$username."')";
                    $where .= " AND \"password\" = '".$password_encrypt."'";
                    $where .= " AND \"status\" ='1' AND \"is_active\" ='1' ";
                    $arr_user = @$this->m_global->getDataAll('sys_user', null, $where, 'username,role_id')[0];
                    // echo $this->db->last_query();exit;
                    if(@$arr_user->username != '' ){
                        if(@$arr_user->role_id == '' ){
                            $this->error_login(8);
                        }else{
                            $this->login_aplikasi($arr_user->username); exit;
                        }
                    }
                    
                    //login ldap
                    $this->ldap_verification($username, $password);

                } else {
                    $this->error_login(2);
                }
            // }
        }else{
            $this->error_login(4);
        }
    }

    public function change_session_role_id($id='') {
        // echo '<pre>';print_r($this->input->post());exit;
        if($id == ''){
            $id = $this->input->post('val');
        }
        $_SESSION['USER']['ROLE_ID'] = $id;
        redirect(site_url('login')); 
    }

    public function ldap_verification($username='', $password=''){
        
        $ds = ldap_connect("192.168.103.6"); 

        if ($ds) {

            ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
            ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);

            try {               
                $ldapbind = @ldap_bind($ds, $username."@indonesiapower.corp", $password); 
            } catch (Exception $e) {
                //kosong
            }

            if ($ldapbind) {
                $dn = "DC=indonesiapower,DC=corp";
                $filter = "(|(sAMAccountName=$username*))";
                $justthese = array( "postofficebox", "title", "ou", 
                                    "sn", "givenname", "mail", "samaccountname", 
                                    "cn", "physicaldeliveryofficename", "department", 
                                    "company", "thumbnailphoto"
                            );
                $sr = ldap_search($ds, $dn, $filter, $justthese);
                $info = ldap_get_entries($ds, $sr);

                if ($info["count"] === 0) {

                    //echo 'no records found<br/>';
                    $this->error_login(1); 

                } else {

                    //echo $info["count"]." entries returned\n<br/>";
                    $arr = [];
                    for ($x = 0; $x < $info["count"]; $x++) {
                        $arr["nip"]       = isset($info[$x]["postofficebox"][0]) ? $info[$x]["postofficebox"][0] : "";
                        $arr["fullname"]  = isset($info[$x]["cn"][0]) ? $info[$x]["cn"][0] : "";
                        $arr["email"]     = isset($info[$x]["mail"][0]) ? $info[$x]["mail"][0] : "";
                        $arr["photo"]     = isset($info[$x]['thumbnailphoto']) ? base64_encode($info[$x]['thumbnailphoto'][0]) : '';
                        $arr["title"]     = isset($info[$x]["title"][0]) ? $info[$x]["title"][0] : "";
                        $arr["department"]= isset($info[$x]["department"][0]) ? $info[$x]["department"][0] : "";
                        $arr["office"]    = isset($info[$x]["physicaldeliveryofficename"][0]) ? $info[$x]["physicaldeliveryofficename"][0] : "";
                        $arr["company"]   = isset($info[$x]["company"][0]) ? $info[$x]["company"][0] : "";
                        $arr["status"]    = 1;
                        $arr["is_active"] = 1;
                    }

                    //cek username apakah sudah ada di database
                    $arr_user = @$this->m_global->getDataAll('sys_user', null, ['username' => $username], 'username,role_id')[0];
                    if(@$arr_user->username ==''){
                        $this->create_user($arr, $username, $password);
                        $this->error_login(8);
                    } else { 
                        $this->update_user($arr, $username, $password);
                        //cek role apakah sudah ada
                        if(@$arr_user->role_id == ''){
                            $this->error_login(8); 
                        }else{
                            $this->login_aplikasi($arr_user->username);
                        }
                    }
                }

            } else {

                //jika user ldap tidak ada, cek Username dan Password di aplikasi
                $password_encrypt = md5_mod($password);
                $arr_user = @$this->m_global->getDataAll('sys_user', null, ['username' => $username, 'password' => $password_encrypt, 'is_active' => '1'], 'username,role_id')[0];
                if(@$arr_user->username == '' ){
                    $this->error_login(1);
                } else { 
                    if(@$arr_user->role_id == '' ){
                        $this->error_login(8);
                    }else{
                        $this->login_aplikasi($arr_user->username);
                    }
                }

                //error user dan password salah
                $this->error_login(1); 
            }

        }else{

            //error user dan password salah
            $this->error_login(1);

        }
    }

    public function create_user($arr=[],$username='',$password='') {
        $data = [];
        $data['username']   = str_replace('@indonesiapower.co.id','',$username);
        $data['password']   = md5_mod($password);
        $data['nip']        = @$arr['nip'];
        $data['fullname']   = @$arr['fullname'];
        $data['email']      = @$arr['email'];
        $data['contact']    = @$arr['contact'];
        $data['photo']      = @$arr['photo'];
        $data['title']      = @$arr['title'];
        $data['department'] = @$arr['department'];
        $data['company']    = @$arr['company'];
        $data['office']     = @$arr['office'];

        $this->m_global->insert('sys_user', $data);
        return TRUE;
    }

    public function update_user($arr=[],$username='',$password='') {
        $data = [];
        $data['username']   = str_replace('@indonesiapower.co.id','',$username);
        $data['password']   = md5_mod($password);
        $data['nip']        = @$arr['nip'];
        $data['fullname']   = @$arr['fullname'];
        $data['email']      = @$arr['email'];
        $data['contact']    = @$arr['contact'];
        $data['photo']      = @$arr['photo'];
        $data['title']      = @$arr['title'];
        $data['department'] = @$arr['department'];
        $data['company']    = @$arr['company'];
        $data['office']     = @$arr['office'];
        
        $this->m_global->update('sys_user', $data, ['username' => $username]);
        return TRUE;
    }


    private function login_aplikasi($username='') {
        
        //ambil data user lengkap
        $arr_user = @$this->m_global->getDataAll('sys_user', null, ['username' => $username, 'is_active' => '1', 'status' => '1'], '*')[0];
        // echo '<pre>';print_r($username);exit;
        
        if($arr_user != ''){
            //create session
            $data_session = array(
                'USER_ID'      => $arr_user->id,
                'ROLE_ID'      => $arr_user->role_id,
                'USERNAME'     => $arr_user->username,
                'JUM_ROLE'     => 0,
                'ARR_ROLE_NAME'=> [],
                'ARR_ROLE_ID'  => [],
                'NIP'          => $arr_user->nip,
                'NAME'         => $arr_user->fullname,
                'EMAIL'        => $arr_user->email,
                'CONTACT'      => $arr_user->contact,
                'PHOTO'        => $arr_user->photo,
                'TITLE'        => $arr_user->title,
                'DEPARTEMENT'  => $arr_user->department,
                'OFFICE'       => $arr_user->office,
                'POSITION_ID'  => $arr_user->position_id,
                'COMPANY'      => $arr_user->company
            );
            $this->session->set_userdata('USER',$data_session);
            $this->session->set_userdata('IS_LOGIN', TRUE);
            
            echo json_encode(['status' => 1]);
            exit;
        }else{
            $this->error_login(5);
        }
        
    }

    public function out(){
        $this->session->sess_destroy();
        clearstatcache();
        redirect(site_url('login'));
    }

    private function error_login($error = null, $msg='')
    {
        $data = [];
        if($error == '1'){
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
                if($msg == '4'){
                    $message = "Verification Check Connection... <script> \$('#ke').val('4');\$('#btn_login').click();;</script>";
                }
                if($msg == '5'){
                    $message = "Verification Check Connection... <script> \$('#ke').val('5');\$('#btn_login').click();;</script>";
                }
            }else if($error == 8){
                $message = "Your Account is Not Registered!";
                $status = 0;
            }
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


    public function skip_login($token='', $nip='', $role='')
    {

        //ambil datanya dan tampilkan
        // $where = " token_type='request_approval_monev_si' AND token_code='$token'  AND token_username='$nip' AND token_end_date > NOW()";
        // $cek_token = @$this->m_global->getDataAll('sys_token', null, $where, 'token_id')[0]->token_id;
        // if ($cek_token == ''){
        //     echo "Token expired"; exit;
        // }
 
        //cek role apakah kosong
        $arr_user = @$this->m_global->getDataAll('sys_user', null, ['nip' => $nip], '*')[0];
        // echo $this->db->last_query();exit;
        
        if($arr_user==''){
           echo'Failed login'; exit;
        }else{

            //cek rolenya apakah lebih dari 1
            $role_id = $arr_user->role_id;
            $arr_role_id    = explode(', ', $role_id);
            $jum_role       = count($arr_role_id);
            $arr_role_name = [];
            if($jum_role > 1){
                foreach ($arr_role_id as $val) {
                    $role_name = h_role_name($val);
                    $arr_role_name[] = $role_name;
                }
            }

            $data_session = array(
                'USER_ID'      => (int)$arr_user->id,
                'ROLE_ID'      => $role,
                'USERNAME'     => $arr_user->username,
                'NIP'          => $arr_user->nip,
                'NAME'         => $arr_user->fullname,
                'EMAIL'        => $arr_user->email,
                'CONTACT'      => $arr_user->contact,
                'PHOTO'        => $arr_user->photo,
                'TITLE'        => $arr_user->title,
                'DEPARTEMENT'  => $arr_user->department,
                'OFFICE'       => $arr_user->office,
                'POSITION_ID'  => $arr_user->position_id,
                'COMPANY'      => $arr_user->company,
                'ROLE_NAME'    => strtoupper(h_role_name($role)),
                'JUM_ROLE'     => $jum_role,
                'ARR_ROLE_NAME'=> $arr_role_name,
                'ARR_ROLE_ID'  => $arr_role_id
            );
            $this->session->set_userdata('USER',$data_session);
            $this->session->set_userdata('IS_LOGIN', TRUE);

            //jika rolenya hanya 1
            $role_name = h_role_name();
            $_SESSION['USER']['ROLE_NAME'] = strtoupper($role_name);
            
            //Log History
            hlp_log_history('login', "Login AS ".strtoupper($role_name));

        }

    }
    

    // ============================== Redirect Page  ==========================================

    
    public function redirect_page($tipe="", $token='', $nip='', $role='', $param='', $param2='', $param3=''){

        $this->skip_login($token, $nip, $role);
        
        if($tipe == 'request_approval_monev_kpi_so' || $tipe == 'notif_monthly_monev_kpi_so'){
            redirect(site_url().'app/monev_kpi_so/index/'.$param.'/'.$param2.'/'.$param3);
        }

        if($tipe == 'notif_input_data_ic'){
            redirect(site_url().'app/ic/index/'.$param);
        }

        if($tipe == 'request_ic_send_to_admin' || $tipe == 'request_ic_done_request_admin' || $tipe == 'request_approval_request_ic'){
            redirect(site_url().'app/request_ic/index/'.$param);
        }

        if($tipe == 'request_approval_monev_si' || $tipe == 'notif_monthly_monev_si'){
            redirect(site_url().'app/monev_si/index/'.$param.'/'.$param2.'/'.$param3);
        }

        
    }

    // ============================== Reminder  ===============================================

    public function reminder($param='')
    {
        //testing cek function
        // $data['reminder_id']    = 1;
        // $data['reminder_date']  = date('Y-m-d H:i:s');
        // call_user_func('h_notif_monthly_monev_kpi_so',$data);
        // exit;

        //data reminder
        $arr = @$this->m_global->getDataAll('sys_reminder', null, null, '*');
        foreach($arr as $row){
            //cek tanggal sekarang
            $date_now       = strtotime('now');
            $reminder_date  = strtotime($row->reminder_date);
            if($date_now >= $reminder_date){
                //log reminder
                $id_log = h_log_reminder_insert($row->id);

                //jalankan script
                $data = [];
                $data['reminder_id']  = $row->id;
                $data['reminder_date']  = $row->reminder_date;
                call_user_func($row->function_name, $data);

                //update log crontab
                h_log_reminder_update($id_log);
            }
        }
    }
    // ============================== END Reminder ==========================================


    public function select_user()
    {
        $q      = @$_REQUEST['q'];
        $where  = " ( LOWER(username) LIKE '%".strtolower($q)."%' OR LOWER(fullname) LIKE '%".strtolower($q)."%' OR LOWER(nip) LIKE '%".strtolower($q)."%' )";
        $arr = $this->m_global->getDataAll('sys_user a', NULL, $where,'id, username, nip, fullname', null, ['fullname','ASC']);
        // echo $this->db->last_query();exit;    
        $data = [];
        for ($i=0; $i < count($arr); $i++) {
            $username  = $arr[$i]->username;
            $name = '[<b>'.@$arr[$i]->nip.'</b>] '.' [<b>'.@$arr[$i]->fullname.'</b>] [<b>'.$arr[$i]->username.'</b>]';
            $data[$i] = ['id' => $arr[$i]->id, 'name' => $name , 'username' => $username];
        }
        echo json_encode(['item' => $data]); exit;

    }

}


