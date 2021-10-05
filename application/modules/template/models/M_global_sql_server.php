<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_dboard extends CI_Model {

    public function __construct(){
        parent::__construct();
    }

    public function countDataAll($table, $join = NULL, $where = NULL, $where_e = NULL, $group = NULL)
    {   
        $db_dboard = $this->load->database('db_dboard',TRUE); 
        $db_dboard->select(" count(*) as JUMLAH ")->from($table);

        if(!is_null($join)){
            foreach ($join as $rows) {
                if(!isset($rows['join'])){ $rows['join'] = 'inner'; }

                $db_dboard->join($rows['table'], $rows['on'], $rows['join']);
            }
        }

        (!is_null($where) ? $db_dboard->where($where) : '');
        (!is_null($where_e) ? $db_dboard->where($where_e, NULL, FALSE) : '');
        (!is_null($group) ? $db_dboard->group_by($group, NULL, FALSE) : '');

        $query  = $db_dboard->get();

        $result = @$query->row()->JUMLAH;

        if($result == ''){$result = 0;}
        
        return $result;
    }

    public function getDataAll($table, $join = NULL, $where = NULL, $select = '*', $where_e = NULL, $order = NULL, $start = NULL, $tampil = NULL, $group = NULL, $tipe = 0, $where_in = null )
    {
        $db_dboard = $this->load->database('db_dboard',TRUE); 
        if(is_array($select)){
            $db_dboard->select( $select[0], $select[1] )->from($table);
        }else{
            $db_dboard->select($select)->from($table);
        }

        if(!is_null($join)){
            foreach ($join as $rows) {
                if(!isset($rows['join'])){ $rows['join'] = 'inner'; }
                $db_dboard->join($rows['table'], $rows['on'], $rows['join']);
            }
        }

        if(!is_null($order)){
            if(is_array($order)){
                $db_dboard->order_by($order[0], $order[1]);
            }else{
                $db_dboard->order_by($order, null, FALSE);
            }
        }

        if(is_null($start)){ $start == 0;}
        (!is_null($tampil) ? $db_dboard->limit($tampil, $start) : '');
        (!is_null($where) ? $db_dboard->where($where) : '');
        (!is_null($where_e) ? $db_dboard->where($where_e, NULL, FALSE) : '');
        (!is_null($where_in) ? $db_dboard->where_in($where_in, NULL, FALSE) : '');
        (!is_null($group) ? $db_dboard->group_by($group, NULL, FALSE) : '');

        $query  = @$db_dboard->get();

        if($tipe == 1){
            $query  = $db_dboard->last_query(); 
            $query  = str_replace("``", "", $query);
            return $query;
        }elseif($tipe == 2){
            return $query->result_array();
        }elseif($tipe == 3){
            $sql  = $db_dboard->last_query(); 
            $sql  = str_replace("``", "", $sql);
            $query = $db_dboard->query($sql)->result();
            return $query;
        }else{
            return $query->result();
        }
    }

    public function getDataAllArray($table, $join = NULL, $where = NULL, $select = '*', $where_e = NULL, $order = NULL, $start = 0, $tampil = NULL, $group = NULL)
    {
        $db_dboard = $this->load->database('db_dboard',TRUE); 
        if(is_array($select)){
            $db_dboard->select( $select[0], $select[1] )->from($table);
        }else{
            $db_dboard->select($select)->from($table);
        }

        if(!is_null($join)){
            foreach ($join as $rows) {
                if(!isset($rows['join'])){ $rows['join'] = 'inner'; }

                $db_dboard->join($rows['table'], $rows['on'], $rows['join']);
            }
        }

        (!is_null($order) ? $db_dboard->order_by($order[0], $order[1]) : '');
        (!is_null($tampil) ? $db_dboard->limit($tampil, $start) : '');
        (!is_null($where) ? $db_dboard->where($where) : '');
        (!is_null($where_e) ? $db_dboard->where($where_e, NULL, FALSE) : '');
        (!is_null($group) ? $db_dboard->group_by($group, NULL, FALSE) : '');

        $query  = $db_dboard->get();
        $result = $query->result_array();

        return $result;
    }

    public function insert($table, $data = NULL)
    {
        $db_dboard = $this->load->database('db_dboard',TRUE); 
        $result    = $db_dboard->insert($table, $data);
        if($result == TRUE){
            $result = [];
            $result['status'] = TRUE;
            $result['id']     = $db_dboard->insert_id();
        }else{
            $result = [];
            $result['status'] = FALSE;
        }

        return $result;
    }

    public function insertBatch($table, $data = NULL)
    {
        $db_dboard = $this->load->database('db_dboard',TRUE); 
        echo '<pre>';print_r($data);
        $result    = $db_dboard->insert_batch($table, $data);
        if($result == TRUE){
            $result = [];
            $result['status'] = TRUE;
//            $result['id']     = $db_dboard->insert_id();
        }else{
            $result = [];
            $result['status'] = FALSE;
        }

        return $result;
    }

    public function update($table, $data = NULL, $where = NULL, $where_e = NULL)
    {
        $db_dboard = $this->load->database('db_dboard',TRUE); 
        (!is_null($where_e) ? $db_dboard->where($where_e, NULL, FALSE) : '');
        $result = $db_dboard->update($table, $data, $where);
        return $result;
    }

    public function delete($table, $where = NULL, $where_e = NULL)
    {
        $db_dboard = $this->load->database('db_dboard',TRUE); 
        (!is_null($where_e) ? $db_dboard->where($where_e, NULL, FALSE) : '');
        (!is_null($where) ? $db_dboard->where($where) : '');
        $result    = $db_dboard->delete($table);
        return $result;
    }

    public function validation($table, $where, $where_e = NULL)
    {
        $db_dboard = $this->load->database('db_dboard',TRUE); 
        $db_dboard->select('*')->from($table);
        
        (!is_null($where) ? $db_dboard->where($where) : '');
        (!is_null($where_e) ? $db_dboard->where($where_e, NULL, FALSE) : '');

        $query  = $db_dboard->get();
        $result = $query->num_rows();
        if($result > 0){
            $result = FALSE;
        }else{
            $result = TRUE;
        }
        return $result;
    }

    // End Core Model

    public function countDataNumRow($table, $join = NULL, $where = NULL, $where_e = NULL, $group = NULL)
    {
        $db_dboard = $this->load->database('db_dboard',TRUE); 
        $db_dboard->select("count(*) as jumlah")->from($table);

        if(!is_null($join)){
            foreach ($join as $rows) {
                if(!isset($rows['join'])){ $rows['join'] = 'inner'; }

                $db_dboard->join($rows['table'], $rows['on'], $rows['join']);
            }
        }

        (!is_null($where) ? $db_dboard->where($where) : '');
        (!is_null($where_e) ? $db_dboard->where($where_e, NULL, FALSE) : '');
        (!is_null($group) ? $db_dboard->group_by($group, NULL, FALSE) : '');

        $query  = $db_dboard->get();
        // $result = $query->num_rows();
        $result = $query->result()[0]->jumlah;

        return $result;
    }

    public function getLastID($table, $field)
    {
        $db_dboard = $this->load->database('db_dboard',TRUE); 
        $db_dboard->select("MAX(".$field.")+1 as ID")->from($table);

        $query  = $db_dboard->get();
        $result = $query->result();
        if(empty($result)){
            $id = 0;
        }else{
            $id = $result[0]->ID;
        }
        return $id;
    }

    public function getDataAllOrder($table, $join = NULL, $where = NULL, $select = '*', $where_e = NULL, $order = NULL, $start = 0, $tampil = NULL, $group = NULL)
    {
        $db_dboard = $this->load->database('db_dboard',TRUE); 
        if(is_array($select)){
            $db_dboard->select( $select[0], $select[1] )->from($table);
        }else{
            $db_dboard->select($select)->from($table);
        }

        if(!is_null($join)){
            foreach ($join as $rows) {
                if(!isset($rows['join'])){ $rows['join'] = 'inner'; }

                $db_dboard->join($rows['table'], $rows['on'], $rows['join']);
            }
        }

        (!is_null($order) ? $db_dboard->order_by($order) : '');
        (!is_null($tampil) ? $db_dboard->limit($tampil, $start) : '');
        (!is_null($where) ? $db_dboard->where($where) : '');
        (!is_null($where_e) ? $db_dboard->where($where_e, NULL, FALSE) : '');
        (!is_null($group) ? $db_dboard->group_by($group, NULL, FALSE) : '');

        $query  = $db_dboard->get();
        $result = $query->result();

        return $result;
    }

    public function oquery($query, $tipe = '0')
    {
        $db_dboard = $this->load->database('db_dboard',TRUE); 
        $this->ora = $this->load->database('oracle',true);
        if($tipe == '1'){
            $data = $this->ora->query($query)->result();
        }elseif($tipe == '2') {
            $data = $this->ora->query($query)->result_array();
        }else{
            $data = $this->ora->query($query);
        }
        return $data;
    }


}

/* End of file m_global.php */
/* Location: ./application/modules/global/models/m_global.php */