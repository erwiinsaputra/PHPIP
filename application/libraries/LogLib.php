<?php

class LogLib
{
    /**
     * @param $action
     * @param null $value
     * @param null $table
     * @param null $id
     *
     * Mencatat setiap aksi dari User
     */
    public function logActivity($action, $value = NULL, $table = NULL, $id = NULL)
    {
        $user = $this->session->userdata('USER');
        if(empty($user)){
            $userId = NULL;
        }else{
            $userId = $user['USER_USERNAME'];
        }

        $data['log_user_id']        = $userId;
        $data['log_logaction_id']   = $action;
        $data['log_value']          = $value;
        $data['log_ip']             = $this->input->ip_address();
        $data['log_table']          = $table;
        $data['log_field_id']       = $id;

        $this->m_global->insert('log', $data);
    }
}