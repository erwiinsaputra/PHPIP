<?php

function h_log_reminder_insert($id="")
{
    $CI =& get_instance();

    $data = [];
    $data['id_reminder'] = $id;
    $data['date_start'] = date("Y-m-d H:i:s");
    $data['status']     = 'Started';
    $CI->m_global->insert('sys_reminder_log', $data);
    $id_log = $CI->db->insert_id(); 
    return $id_log;
}

function h_log_reminder_update($id="")
{
    $CI =& get_instance();
    $data = [];
    $data['date_end'] = date("Y-m-d H:i:s");
    $data['status'] = 'Finished';
    $CI->m_global->update('sys_reminder_log', $data, ['id'=>$id]);
}

function h_sync_table_DIRJAB_STO()
{
    $CI =& get_instance();
    
    //load database
    $CI->load->model('template/m_oracle'); 
    $db_simo = $CI->load->database('default',TRUE); 
    $db_datalake = $CI->load->database('oracle',TRUE); 

    // create table_copy
    $sql = 'CREATE TABLE "DIRJAB_STO_COPY" AS TABLE "DIRJAB_STO"';
    $db_simo->query($sql);

    //kosongkan isiny
    $sql = 'TRUNCATE TABLE "DIRJAB_STO_COPY"';
    $db_simo->query($sql);

    //insert data to table_copy from other table
    $total = $CI->m_oracle->getDataAll("DIRJAB_STO", null, null, "MAX(POSITION_ID) AS TOTAL")[0]->TOTAL;

    $pembagian = ceil($total/1000);
    // echo '<pre>';print_r($total);exit;

    for ($i=1; $i <= $pembagian; $i++) { 
        if($i == '1'){
            $start = 0;
        }else{
            $start = $end + 1;
        }
        $end = $i * 1000;

        $sql = 'SELECT * FROM "DIRJAB_STO" WHERE "POSITION_ID" BETWEEN '.$start.' AND '.$end;
        $arr_data = $CI->m_oracle->oquery($sql)->result_array();
        // echo $CI->m_oracle->last_query();exit; 
        
        if(!empty($arr_data)){
            $db_simo->insert_batch('"DIRJAB_STO_COPY"', $arr_data);
        }
    }

    // rename 
    $sql = 'DROP TABLE "DIRJAB_STO"';
    $db_simo->query($sql);

    $sql = 'ALTER TABLE "DIRJAB_STO_COPY" RENAME TO "DIRJAB_STO"';
    $db_simo->query($sql);
}


function h_sync_table_ERP_STO_REAL()
{
    $CI =& get_instance();

    //load database
    $CI->load->model('template/m_oracle'); 
    $db_simo = $CI->load->database('default',TRUE); 
    $db_datalake = $CI->load->database('oracle',TRUE); 

    // create table_copy
    $sql = 'CREATE TABLE "ERP_STO_REAL_COPY" AS TABLE "ERP_STO_REAL"';
    $db_simo->query($sql);

    //kosongkan isiny
    $sql = 'TRUNCATE TABLE "ERP_STO_REAL_COPY"';
    $db_simo->query($sql);

    //insert data to table_copy from other table
    $total = $CI->m_oracle->getDataAll("ERP_STO_REAL", null, null, "MAX(POSITION_ID) AS TOTAL")[0]->TOTAL;
    $pembagian = ceil($total/1000);
    // echo '<pre>';print_r($total);exit;
    for ($i=1; $i <= $pembagian; $i++) { 
        if($i == '1'){
            $start = 0;
        }else{
            $start = $end + 1;
        }
        $end = $i * 1000;

        $sql = 'SELECT * FROM "ERP_STO_REAL" WHERE "POSITION_ID" BETWEEN '.$start.' AND '.$end;
        $arr_data = $CI->m_oracle->oquery($sql)->result_array();
        if(!empty($arr_data)){
            $db_simo->insert_batch('"ERP_STO_REAL_COPY"', $arr_data);
        }
    }

    // rename 
    $sql = 'DROP TABLE "ERP_STO_REAL"';
    $db_simo->query($sql);

    $sql = 'ALTER TABLE "ERP_STO_REAL_COPY" RENAME TO "ERP_STO_REAL"';
    $db_simo->query($sql);

    //replace ada 2 spasi di singkatan jabatan
    $arr = @$CI->m_global->getDataAll('ERP_STO_REAL', null, "\"SINGKATAN_POSISI\" LIKE '%  %'", '"POSITION_ID","SINGKATAN_POSISI"');
    foreach($arr as $row){
        $data = [];
        $data['SINGKATAN_POSISI'] = str_replace('  ',' ',$row->SINGKATAN_POSISI);
        $arr = @$CI->m_global->update('ERP_STO_REAL', $data, ['POSITION_ID'=>$row->POSITION_ID]);
    }

    
    //update Data Karyawan
    $where = ['is_active'=>'t'];
    $select = " a.\"id\", ";
    $select .= " (SELECT b.\"POSISI\" FROM \"ERP_STO_REAL\" b WHERE b.\"POSITION_ID\" = a.\"position_id\" ) AS posisi, ";
    $select .= " (SELECT b.\"SINGKATAN_POSISI\" FROM \"ERP_STO_REAL\" b WHERE b.\"POSITION_ID\" = a.\"position_id\" ) AS singkatan_posisi ";
    $arr = @$CI->m_global->getDataAll('sys_user a', null, $where, $select);
    foreach($arr as $row){
        $data = [];
        $data['title'] = $row->posisi;
        $data['singkatan_jabatan'] = $row->singkatan_posisi;
        @$CI->m_global->update('sys_user', $data, ['id'=>$row->id]);
    }

}




function h_delete_data($id="")
{
    $CI =& get_instance();

    //delete file so
    $arr = @$CI->m_global->getDataAll('m_file_so', null, ['is_active'=>'f'], 'file_name');
    if(count($arr) > 0){
        foreach($arr as $row){
            unlink(FCPATH."files/so/h_". $row->file_name);
        }
    }

    //delete data in table
    $sql = "select t.table_name, array_agg(c.column_name::text) as columns
            from information_schema.tables t
            inner join information_schema.columns c on t.table_name = c.table_name
            where t.table_schema = ' and t.tah_ble_type= 'BASE TABLE' and c.table_schema = '
        h_    group by t.table_name";
    $arr = $CI->db->query($sql)->result();
    if(count($arr) > 0){
        $pengecualian = ['DIRJAB_STO','ERP_STO_REAL','sys_sessions','sys_token'];
        foreach($arr as $row){
            $table = $row->table_name;
            if(in_array($table,$pengecualian)){
                continue;
            }
            $CI->m_global->delete($table, ['is_active'=>'f']);
        }
    }
}

function h_auto_increment_id()
{
    $CI =& get_instance();

    //delete data in table
    $sql = "select t.table_name, array_agg(c.column_name::text) as columns
                from information_schema.tables t
                inner join information_schema.columns c on t.table_name = c.table_name
                where t.table_schema = 'public' and t.table_type= 'BASE TABLE' and c.table_schema = 'public'
                group by t.table_name";
    $arr = $CI->db->query($sql)->result();
    if(count($arr) > 0){
        $pengecualian = ['DIRJAB_STO','ERP_STO_REAL','sys_sessions','sys_token'];
        foreach($arr as $row){
            $table = $row->table_name;
            if(in_array($table,$pengecualian)){
                continue;
            }
            $sql = "SELECT setval('".$table."_id_seq', coalesce(max(id), 0) + 1, false) FROM ".$table.";
                    ALTER TABLE ".$table." ALTER COLUMN id SET DEFAULT nextval('".$table."_id_seq'); ";
            $res = $CI->db->query($sql);
        }
    }
}


function h_update_status_year()
{

    $CI =& get_instance();

    $where = "parent = 0";
    $arr_parent = @$CI->m_global->getDataAll('m_action_plan', null, $where, 'parent, id');
    foreach($arr_parent as $row){

        $where = "id_action_plan = ".$row->id;
        $arr_year = @$CI->m_global->getDataAll('m_action_plan_year', null, $where, 'year');
        foreach($arr_year as $row2){

            $year = $row2->year;

            $where = [];
            $id_parent = $row->id;
            $where['a.parent'] = $id_parent;
            $select = " (SELECT b.status_complete FROM m_action_plan_year b WHERE b.id_action_plan = a.id AND b.year = $year) AS status_complete";
            $arr_status_year = @$CI->m_global->getDataAll('m_action_plan a', null, $where, $select);
            $arr_status_sub = [];
            foreach($arr_status_year as $row3){
                $arr_status_sub[] = ($row3->status_complete == '' ? '13' : $row3->status_complete);
            }

            //cek status
            if(in_array('13',$arr_status_sub)){ $cek1 = 'ada'; }else{ $cek1 = ''; }
            if(in_array('12',$arr_status_sub)){ $cek2 = 'ada'; }else{ $cek2 = ''; }
            if(in_array('11',$arr_status_sub)){ $cek3 = 'ada'; }else{ $cek3 = ''; }
            if($cek1 == 'ada' && $cek2 == '' && $cek3 == ''){
                $status_parent = '13';
            }elseif($cek1 == '' && $cek2 == '' && $cek3 == 'ada'){
                $status_parent = '11';
            }else{
                $status_parent = '12';
            }

            //update status complete parent
            $data = $where = [];
            $data['status_complete'] = $status_parent;
            $where = ['id_action_plan' => $id_parent, 'year' => $year, 'is_active'=> 't'];
            $res = $CI->m_global->update('m_action_plan_year', $data, $where);

        }
    }

}


function h_notif_monthly_monev_si($param=[])
{
    $CI =& get_instance();

    //select pic IC
    $where  = "a.is_active='t' AND a.role_id ='9' ";
    $select = "a.nip";
    $select .= ", (SELECT DISTINCT STRING_AGG ( b.\"code\" :: CHARACTER VARYING, ' ^ ' )
                    FROM \"m_si\" b  
                    WHERE b.\"id\" ::text = ANY (string_to_array(a.id_si,', ')::text[])
                ) AS code_si";
    $select .= ", (SELECT DISTINCT STRING_AGG ( b.\"name\" :: CHARACTER VARYING, ' ^ ' )
                    FROM \"m_si\" b  
                    WHERE b.\"id\" ::text = ANY (string_to_array(a.id_si,', ')::text[])
                ) AS name_si";
    $select .= ", (SELECT DISTINCT STRING_AGG ( b.\"id\" :: CHARACTER VARYING, ' ^ ' )
                    FROM \"m_si\" b  
                    WHERE b.\"id\" ::text = ANY (string_to_array(a.id_si,', ')::text[])
                ) AS id_si";
    $arr        = @$CI->m_global->getDataAll('m_user_ic_si AS a', null, $where, $select);
    $arr_user = $arr_id_si = $arr_name_si = $arr_code_si = [];
    foreach($arr as $row){ 
        $arr_user[$row->nip] = $row->nip;
        $arr_id_si[$row->nip] = $row->id_si;
        $arr_code_si[$row->nip] = $row->code_si;
        $arr_name_si[$row->nip] = $row->name_si;
    }

    //kirim ke user pic SI
    $arr_nip    = join("','",$arr_user);
    $where      = "a.nip IN('".$arr_nip."') AND a.is_active='t'";
    $arr        = @$CI->m_global->getDataAll('sys_user AS a', null, $where, 'a.nip, a.fullname, a.email, a.title');
    $i = 0;
    foreach($arr as $row){
        $data = [];
        $data['nip']        = $row->nip;
        $data['fullname']   = $row->fullname;
        $data['email']      = $row->email;
        $data['title']      = $row->title;

        $token = h_insert_token('notif_monthly_monev_si',$row->nip, '30');
        $link = site_url().'login/redirect_page/notif_monthly_monev_si/'.$token.'/'.$row->nip.'/9';
        $data['link'] = $link;

        $to         = h_email_to($row->email);
        $from       = 'noreply@indonesiapower.co.id';
        $title      = "Reminder Monitoring & Evaluation SI";
        $subject    = "Reminder Monitoring & Evaluation SI";
        $data['subject'] = $subject;

        //kirim email html
        $html = $CI->load->view('app/monev_si/v_monev_si_email_notif_monthly', $data, TRUE);
        h_kirim_email_ip($from, $to, null, null, $title, $subject, $html);

        //testing, kirim 1 kali untuk tes
        // if($i == 0){
        //     //kirim email html
        //     $html = $CI->load->view('app/monev_si/v_monev_si_email_notif_monthly', $data, TRUE);
        //     h_kirim_email_ip($from, $to, null, null, $title, $subject, $html);
        // } $i++;
        
        //testing, untuk cek html
        // $CI->load->view('app/monev_si/v_monev_si_email_notif_monthly', $data);

        //================================== Notif Inbox ======================================
        //description SI
        $pecah = explode(' ^ ',$arr_code_si[$row->nip]); 
        $pecah2 = explode(' ^ ',$arr_name_si[$row->nip]);
        $pecah3 = explode(' ^ ',$arr_id_si[$row->nip]);
        
        foreach($pecah as $key=>$val){
            //nama SI
            $si_code = $val;
            $si_name = $pecah2[$key];
            $id_si = $pecah3[$key];
            $date_reminder = strtotime($param['reminder_date']);
            $year = date('Y', strtotime("-1 month", $date_reminder));
            $month = (int)date('m', strtotime("-1 month", $date_reminder));
            $link_new = $link.'/'.$id_si.'/'.$year.'/'.$month;

            //insert to inbox
            $data = [];
            $data['element']     = "SI - ".$si_code;
            $data['type_inbox']  = "SI";
            $data['description'] = "Penginputan Monitoring & Evaluation SI, <br>
                                        Bulan : ".date('F Y', strtotime("-1 month"))."<br>
                                        Untuk SI: (".$si_code.") ".h_text_br($si_name,40);
            $data['param_id']       = $id_si;
            $data['review_status']  = 18;
            $data['request_by']     = 1;
            $data['request_date']   = date('Y-m-d H:i:s');
            $data['nip']            = $row->nip;
            $data['role_id']        = 9;
            $data['redirect_page']  = $link_new;
            $result = $CI->m_global->insert('m_inbox', $data);
        }
        //==============================================================================

    }

    //update tanggal reminder
    h_update_reminder_date($param['reminder_id'],$param['reminder_date'],'+1 month');

}

function h_notif_monthly_monev_kpi_so($param=[])
{
    $CI =& get_instance();

    //select pic KPI-SO dari master user KPI-SO
    $where  = "a.is_active='t' AND a.role_id ='10' ";
    $select = "a.nip";
    $select .= ", (SELECT DISTINCT STRING_AGG ( b.\"code\" :: CHARACTER VARYING, ' ^ ' )
                    FROM \"m_kpi_so\" b  
                    WHERE b.\"id\" ::text = ANY (string_to_array(a.id_kpi_so,', ')::text[])
                ) AS code_kpi_so";
    $select .= ", (SELECT DISTINCT STRING_AGG ( b.\"name\" :: CHARACTER VARYING, ' ^ ' )
                    FROM \"m_kpi_so\" b  
                    WHERE b.\"id\" ::text = ANY (string_to_array(a.id_kpi_so,', ')::text[])
                ) AS name_kpi_so";
    $select .= ", (SELECT DISTINCT STRING_AGG ( b.\"id\" :: CHARACTER VARYING, ' ^ ' )
                FROM \"m_kpi_so\" b  
                WHERE b.\"id\" ::text = ANY (string_to_array(a.id_kpi_so,', ')::text[])
            ) AS id_kpi_so";
    $arr        = @$CI->m_global->getDataAll('m_user_kpi_so AS a', null, $where, $select);
    $arr_user = $arr_id_kpi_so = $arr_name_kpi_so = $arr_code_kpi_so = [];
    foreach($arr as $row){ 
        $arr_user[$row->nip] = $row->nip;
        $arr_id_kpi_so[$row->nip] = $row->id_kpi_so;
        $arr_code_kpi_so[$row->nip] = $row->code_kpi_so;
        $arr_name_kpi_so[$row->nip] = $row->name_kpi_so;
    }

    //kirim ke user pic KPI-SO
    $arr_nip    = join("','",$arr_user);
    $where      = "a.nip IN('".$arr_nip."') AND a.is_active='t'";
    $arr        = @$CI->m_global->getDataAll('sys_user AS a', null, $where, 'a.nip, a.fullname, a.email, a.title');
    $i = 0;
    foreach($arr as $row){
        $data = [];
        $data['nip']        = $row->nip;
        $data['fullname']   = $row->fullname;
        $data['email']      = $row->email;
        $data['title']      = $row->title;

        $token = h_insert_token('notif_monthly_monev_kpi_so',$row->nip, '30');
        $link = site_url().'login/redirect_page/notif_monthly_monev_kpi_so/'.$token.'/'.$row->nip.'/10';
        $data['link'] = $link;

        $to         = h_email_to($row->email);
        $from       = 'noreply@indonesiapower.co.id';
        $title      = "Reminder Monitoring & Evaluation KPI-SO";
        $subject    = "Reminder Monitoring & Evaluation KPI-SO";
        $data['subject'] = $subject;

        //kirim email html
        $html = $CI->load->view('app/monev_si/v_monev_si_email_notif_monthly', $data, TRUE);
        h_kirim_email_ip($from, $to, null, null, $title, $subject, $html);

        //testing, kirim 1 kali untuk tes
        // if($i == 0){
        //     //kirim email html
        //     $html = $CI->load->view('app/monev_si/v_monev_si_email_notif_monthly', $data, TRUE);
        //     h_kirim_email_ip($from, $to, null, null, $title, $subject, $html);
        // } $i++;
        
        //testing, untuk cek html
        // $CI->load->view('app/monev_kpi_so/v_monev_kpi_so_email_notif_monthly', $data);

        //================================== Notif Inbox ======================================
        //description kpi-so
        $pecah = explode(' ^ ',$arr_code_kpi_so[$row->nip]); 
        $pecah2 = explode(' ^ ',$arr_name_kpi_so[$row->nip]);
        $pecah3 = explode(' ^ ',$arr_id_kpi_so[$row->nip]);
        foreach($pecah as $key=>$val){
            //nama kpi-so
            $kpi_so_code = $val;
            $kpi_so_name = $pecah2[$key];
            $id_kpi_so   = $pecah3[$key];
            $date_reminder = strtotime($param['reminder_date']);
            $year = date('Y', strtotime("-1 month", $date_reminder));
            $month = (int)date('m', strtotime("-1 month", $date_reminder));
            $link_new = $link.'/'.$id_kpi_so.'/'.$year.'/'.$month;

            //insert to inbox
            $data = [];
            $data['element']     = "KPI-SO - ".$kpi_so_code;
            $data['type_inbox']  = "KPI-SO";
            $data['description'] = "Penginputan Monitoring & Evaluation KPI-SO, <br>
                                        Bulan : ".date('F Y', strtotime("-1 month"))."<br>
                                        Untuk KPI-SO: (".$kpi_so_code.") ".h_text_br($kpi_so_name,40);
            $data['param_id']       = $id_kpi_so;
            $data['review_status']  = 18;
            $data['request_by']     = 1;
            $data['request_date']   = date('Y-m-d H:i:s');
            $data['nip']            = $row->nip;
            $data['role_id']        = 10;
            $data['redirect_page']  = $link_new;
            $result = $CI->m_global->insert('m_inbox', $data);
        }
        //==============================================================================

    }

    //update tanggal reminder
    h_update_reminder_date($param['reminder_id'],$param['reminder_date'],'+3 month');

}

function h_update_data_karyawan($param=[])
{
    $CI =& get_instance();

    h_sync_table_DIRJAB_STO();
    h_sync_table_ERP_STO_REAL();

    h_update_reminder_date($param['reminder_id'],$param['reminder_date'],'+1 days');
        
}

function h_update_reminder_date($reminder_id='',$reminder_date='', $count='')
{
    $CI =& get_instance();

    //update tanggal reminder
    $date_old = strtotime($reminder_date);
    $date_new = date("Y-m-d H:i:s", strtotime($count, $date_old));
    $data['reminder_date'] = $date_new;
    $CI->m_global->update('sys_reminder', $data, ['id'=>$reminder_id]);
}

