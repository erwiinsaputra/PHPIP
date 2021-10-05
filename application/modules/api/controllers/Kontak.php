<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kontak extends MX_Controller {

    var $API ="";

    function __construct() {
        parent::__construct();
        $this->API="http://localhost/php7/simo_2021_res_api/index.php/kontak";
        $this->load->library('session');
        $this->load->library('curl');
        $this->load->helper('form');
        $this->load->helper('url');
    }
    
    // menampilkan data kontak
    function index(){
        $data['datakontak'] = json_decode($this->curl->simple_get($this->API));
        $this->load->view('kontak/list',$data);
    }
    
    // insert data kontak
    function create(){
        if(isset($_POST['submit'])){
            $data = array(
                'nama'      =>  $this->input->post('nama'),
                'nomor'=>  $this->input->post('nomor'));
            $insert =  $this->curl->simple_post($this->API, $data, array(CURLOPT_BUFFERSIZE => 10)); 
            // var_dump($insert);
            // $this->curl->debug();exit;
            
            if($insert)
            {
                $this->session->set_flashdata('hasil','Insert Data Berhasil');
            }else
            {
               $this->session->set_flashdata('hasil','Insert Data Gagal');
            }
            redirect('api/kontak');
        }else{
            $this->load->view('kontak/create');
        }
    }
    
    // edit data kontak
    function edit($id=''){
        if(isset($_POST['submit'])){
            $data = array(
                'id' =>  $this->input->post('id'),
                'nama' =>  $this->input->post('nama'),
                'nomor'=>  $this->input->post('nomor'));
            $update =  $this->curl->simple_put($this->API, $data, array(CURLOPT_BUFFERSIZE => 10));
            
            if($update)
            {
                $this->session->set_flashdata('hasil','Update Data Berhasil');
            }else
            {
               $this->session->set_flashdata('hasil','Update Data Gagal');
            }
            redirect('api/kontak');
        }else{
            $params = array('id'=>  $id);
            $data['datakontak'] = json_decode($this->curl->simple_get($this->API,$params));
            $this->load->view('kontak/edit',$data);
        }
    }
    
    // delete data kontak
    function delete($id){
        if(empty($id)){
            redirect('api/kontak');
        }else{
            $delete =  $this->curl->simple_delete($this->API, array('id'=>$id), array(CURLOPT_BUFFERSIZE => 10)); 
            if($delete)
            {
                $this->session->set_flashdata('hasil','Delete Data Berhasil');
            }else
            {
               $this->session->set_flashdata('hasil','Delete Data Gagal');
            }
            redirect('api/kontak');
        }
    }
}

?>