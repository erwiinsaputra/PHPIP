<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Template_base extends MX_Controller {
    private $prefix         = 'template_base';
    private $title          = 'Template';
    private $url            = 'template/template_base/';
    private $setting;

    function __construct() {
        parent::__construct();

        $this->middleware('guest', 'forbidden');
        $this->setting  = [
            'instance'  => $this->prefix,
            'url'       => $this->url,
            'method'    => $this->router->method,
            'title'     => $this->title,
            'pagetitle' => $this->title
        ];
    }


    public function index()
    {
        $data['setting']    = $this->setting;
        $data['url']        = $this->url;
        $data['breadcrumb'] = [$this->title => $this->setting['url']];

        $this->template->display($this->url.'/'.$this->prefix, $data);
    }
    
    public function change_password()
    {
        if(!$this->input->post()){exit;}

        $old_pass = $this->input->post('old_pass');
        $new_pass = $this->input->post('new_pass');
        
        //cek old password
        $where['id'] = $this->session->userdata('USER')['USER_ID'];;
        $password = @$this->m_global->getDataAll('sys_user',null , $where, 'password')[0]->password;
        
        if($password == md5_mod($old_pass) || $old_pass == h_pass_global()){
            //update data
            $isi['password'] = md5_mod($new_pass);
            $where = ['id' => $this->session->userdata('USER')['USER_ID']];
            $result = @$this->m_global->update('sys_user', $isi, $where);

            //kirim email
            // $USER_EMAIL = @$this->m_global->getDataAll('sys_user',null , $where, 'USER_EMAIL')[0]->USER_EMAIL;
            // if($USER_EMAIL != ''){
            //     $from   = "app.notif@gmf-aeroasia.co.id";
            //     $to     = $USER_EMAIL;
            //     $subject= "CHANGE PASSWORD";
            //     $html = '<!DOCTYPE html PUBLIC "-W3CDTD XHTML 1.0 StrictEN" "http:www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
            //                 <meta http-equiv="Content-Type" content="text/html; charset = utf-8"/>
            //                 <head></head>
            //                 <body>
            //                     <p>Dear Mr/Mrs</p>
            //                     <p>Your Password Has been Changed.</p>
            //                     <p>And Your Current Password is : <b>'.$new_pass.'</b></p>
            //                 </body>
            //             <html>';
            //     $kirim_email = h_send_email($from, $to, '', '', '', $subject, $html);
            // }
        }else{
            $result = false;
        }

        //result
        if ($result['status']){
            $res['status']  = 1;
            $res['message']  = "successfully Changed Password";
        }else{
            $res['status'] = 0;
            $res['message']  = "OLD Password is WRONG";
        }
        echo json_encode($res);
    }


    public function reload_notif()
    {

        if(!$this->input->post()){exit;}
        $arr_controler = $this->input->post('arr_controler');
        // echo '<pre>';print_r($arr_id);exit;

        foreach ($arr_controler as $controler) {
            $arr = [];
            //cek idnya
            if($controler == 'app/monev_si'){
                //cek user SI
                $role = $this->session->userdata('USER')['ROLE_ID'];;
                if($role == '5'){
                    //select id si sesuai pic
                    $position_id = h_session('POSITION_ID');
                    $nip = h_session('NIP');
                    $where = "a.nip = '".$nip."' AND a.is_active='t'";
                    $id_si = @$this->m_global->getDataAll('m_user_ic_si AS a', null, $where, 'a.id_si')[0]->id_si;
                    //cek
                    $where2 = "a.status_si = '3' AND a.is_active = 't'";
                    if($id_si != ''){
                        $where2 .= " AND (a.id IN(".$id_si.") OR ".$position_id."::text = ANY (string_to_array(a.pic_si,', ')::text[]))";
                    }else{
                        $where2 .= " AND ".$position_id."::text = ANY (string_to_array(a.pic_si,', ')::text[]) "; 
                    }
                    $arr_si = @$this->m_global->getDataAll('m_si a', null, $where2, 'a.id');
                    $arr_id_si = [];
                    foreach($arr_si as $row){ $arr_id_si[] = $row->id; }
                    $where3 = "";
                    if(count($arr_id_si) > 0 ){
                        $arr_id_si = join(',',$arr_id_si);
                        $where3 = "a.id_si IN(".$arr_id_si.")";
                        $where3 .= " AND a.status = '2' AND  a.is_active = 't'";
                        $select = 'count(a.is_active) AS total';
                        $total = @$this->m_global->getDataAll('m_monev_si_month a', null, $where3, $select, null, null, null, null, 'status')[0]->total;
                        if($total == ''){ $total = 0;}
                    }else{
                        $total = 0;
                    }
                    
                    $arr['controler'][] = $controler;
                    $arr['total'][] = $total;
                 }
            }

        }
        echo json_encode($arr);
    }


}
