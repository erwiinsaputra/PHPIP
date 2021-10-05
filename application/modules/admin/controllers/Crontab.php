<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crontab extends MX_Controller {

    function __construct() {
        parent::__construct();
        // $this->middleware('guest', 'forbidden');
    }

    public function index()
    {
        // $this->sync_table_DIRJAB_STO();
        // $this->load->model('template/m_oracle');
        // $a = $this->m_oracle->getDataAll('DATALAKE.DIRJAB_STO A', null, ['A.PERSON_ID'=>'49722']);
        // echo '<pre>';print_r($a);exit;
        echo "auto_increment_id<br>";
        echo "sync_table_DIRJAB_STO<br>";
        echo "sync_table_ERP_STO_REAL<br>";
        echo "update_status_year";
        exit;
    }
   
    public function sync_table_DIRJAB_STO()
    {
        h_sync_table_DIRJAB_STO();
    }

    public function sync_table_ERP_STO_REAL()
    {
        h_sync_table_ERP_STO_REAL();
    }

    public function delete_data()
    {
        h_delete_data();
    }

    public function auto_increment_id()
    {
        h_auto_increment_id();
    }

    public function update_status_year()
    {
        h_update_status_year();
    }

}
