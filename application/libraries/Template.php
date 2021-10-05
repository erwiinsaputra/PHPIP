<?php
class Template {

    protected $CI;

    function __construct() {
        $this->CI = &get_instance();
    }

    function display($template, $data = NULL, $js = NULL, $css = NULL) {
        $status_link    = $this->CI->input->is_ajax_request();
        if($status_link){
            $data['_pageheader']    = $this->CI->load->view('template/page_header', @$data['setting'], TRUE);
            $data['_breadcrumb']    = $this->CI->load->view('template/breadcrumb', $data, TRUE);
            $data['_content']       = $this->CI->load->view($template, $data, TRUE);
            $data['_js']            = $this->CI->load->view('template/js', $js, TRUE);
            $data['_css']           = $this->CI->load->view('template/css', $css, TRUE);
            $data['_fullcontent']   = $this->CI->load->view('template/fullcontent_ajax', $data);
        }else{
            $data['_header']        = $this->CI->load->view('template/header', $data, TRUE);
            $data['_sidebar']       = $this->CI->load->view('template/sidebar', $data, TRUE);
            $data['_pageheader']    = $this->CI->load->view('template/page_header', @$data['setting'], TRUE);
            $data['_breadcrumb']    = $this->CI->load->view('template/breadcrumb', $data, TRUE);
            $data['_content']       = $this->CI->load->view($template, $data, TRUE);
            $data['_js']            = $this->CI->load->view('template/js', $js, TRUE);
            $data['_css']           = $this->CI->load->view('template/css', $css, TRUE);
            $data['_fullcontent']   = $this->CI->load->view('template/fullcontent', $data, TRUE);
            $data['_footer']        = $this->CI->load->view('template/footer', $data, TRUE);
            $this->CI->load->view('template/base.php', $data);
        }
    }

    function display_ajax($template, $data = NULL, $js = NULL, $css = NULL, $html=FALSE) {
        $data['_content']       = $this->CI->load->view($template, $data, TRUE);
        $data['_js']            = $this->CI->load->view('template/js', $js, TRUE);
        $data['_css']           = $this->CI->load->view('template/css', $css, TRUE);
        if($html){
            return $this->CI->load->view('template/content_ajax', $data, $html);
        }else{
            $this->CI->load->view('template/content_ajax', $data);
        }
    }
}

?>
