<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Errors extends MX_Controller {

    private $title = 'Error';

    function __construct() {
        parent::__construct();
    }

    public function index()
    {
        $this->error_404();
    }

    private function error_404()
    {
        
        $this->load->view('error/error_404');
        
    }
}
