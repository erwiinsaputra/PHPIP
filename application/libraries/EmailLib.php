<?php

/**
 * Created by PhpStorm.
 * User: Alice
 * Date: 12/25/15
 * Time: 9:30 PM
 */
class EmailLib
{
    public $email;

    function __construct($email)
    {
        $this->email = $email;
    }

    public function account($username, $password)
    {
        return true; // baypass
        $subject = 'Pemberitahuan Informasi Akun';
        $message = '
            <p>Selamat datang di Aplikasi BKPM</p>
            <p>Email ini berisi informasi account Anda.</p>
            <div>Username : ' . $username . '</div>
            <div>Password : ' . $password . '</div>
            </br>
            <div>Untuk dapat menggunakan username dan password anda silakan mulai dari sini :</div>
            </br>
            <div>
                <a href="' . base_url('admin') . '" target="_blank">' . base_url('admin') . '</a></br>
                <div>Peringatan : Username dan password akan kadaluarsa dalam 8 hari jika anda tidak login!</div>
            </div>
		';

        return $this->sendEmail($this->email, $subject, $message);
    }

    public function jadwalTraining($attach){
        $subject = 'subject';
        $message = 'message';
        return $this->sendEmail($this->email, $subject, $message, $attach);
    }

    private function sendEmail($to, $subject, $message, $attach = null, $from = 'lhkpn@mitrekasolusi.co.id')
    {
        $CI =& get_instance();
        $CI->load->library('email');
        $config['protocol']  = "smtp";
        $config['smtp_host'] = 'ssl://mail.mitrekasolusi.co.id';
        $config['smtp_port'] = '25';
        $config['smtp_user'] = 'lhkpn@mitrekasolusi.co.id';
        $config['smtp_pass'] = 'rahasia';
        $config['mailtype']  = 'html';
        $config['charset']   = 'iso-8859-1';
        $config['wordwrap']  = TRUE;

        $CI->email->initialize($config);
        $CI->email->set_newline("\r\n");

        $CI->email->from($from, 'NSWI');
        $CI->email->to($to);
        $CI->email->subject($subject);

        if($attach != null){
            foreach($attach as $row){
                $CI->email->attach($row);
            }
        }

        $CI->email->message($message);
        if($CI->email->send()){
            $res['status']  = true;
        }else{
            $res['status']  = false;
        }

        return $res;
    }
}