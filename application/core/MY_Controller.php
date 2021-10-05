<?php

class MY_Controller extends MX_Controller{
    
    public function __construct()
    {
        parent::__construct();
            //kosong
    }

    public function json_output($data = array(), $code = 200, $msg = NULL) {
    	$output = array(
    		'code' => $code,
    		'message' => $msg, 
    		'data' => $data
    	);

    	$json_data = json_encode($output);
    	
    	$this->output
    		->set_content_type('application/json')
    		->set_output($json_data);
    }

    public function parsing_float($value) {
        return is_float($value) ? floatval(sprintf('%.2f', $value)) : intval($value);
    }

    public function get_month() {
        $month = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
        return $month;
    }
}