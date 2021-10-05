<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_oracle extends CI_Model {

    public function __construct(){
        parent::__construct();
        $this->db = $this->load->database('oracle', TRUE);
    }

    public function countDataAll($table, $join = NULL, $where = NULL, $where_e = NULL, $group = NULL)
    {
        $this->db->select("count(*) as JUMLAH")->from($table);

        if(!is_null($join)){
            foreach ($join as $rows) {
                if(!isset($rows['join'])){ $rows['join'] = 'inner'; }

                $this->db->join($rows['table'], $rows['on'], $rows['join']);
            }
        }

        (!is_null($where) ? $this->db->where($where) : '');
        (!is_null($where_e) ? $this->db->where($where_e, NULL, FALSE) : '');
        (!is_null($group) ? $this->db->group_by($group, NULL, FALSE) : '');

        $query  = $this->db->get();
        $result = @$query->row()->JUMLAH;
        if($result == ''){$result = 0;}

        return $result;
    }

    public function getDataAll($table, $join = NULL, $where = NULL, $select = '*', $where_e = NULL, $order = NULL, $start = 0, $tampil = NULL, $group = NULL, $tipe = 0)
    {
        if(is_array($select)){
            $this->db->select( $select[0], $select[1] )->from($table);
        }else{
            $this->db->select($select)->from($table);
        }

        if(!is_null($join)){
            foreach ($join as $rows) {
                if(!isset($rows['join'])){ $rows['join'] = 'inner'; }

                $this->db->join($rows['table'], $rows['on'], $rows['join']);
            }
        }

        if(!is_null($order)){
            if(is_array($order)){
                $this->db->order_by($order[0], $order[1]);
            }else{
                $this->db->order_by($order, null, FALSE);
            }
        }

        (!is_null($tampil) ? $this->db->limit($tampil, $start) : '');
        (!is_null($where) ? $this->db->where($where) : '');
        (!is_null($where_e) ? $this->db->where($where_e, NULL, FALSE) : '');
        (!is_null($group) ? $this->db->group_by($group, NULL, FALSE) : '');


        if($tipe == 1){
            return $this->db->get();
        }elseif($tipe == 2){
            return $this->db->get()->result_array();
        }elseif($tipe == 3){
            return $this->db->queries[0];
        }else{
            return $this->db->get()->result();
        }
        
    }

    public function getDataAllArray($table, $join = NULL, $where = NULL, $select = '*', $where_e = NULL, $order = NULL, $start = 0, $tampil = NULL, $group = NULL)
    {
        if(is_array($select)){
            $this->db->select( $select[0], $select[1] )->from($table);
        }else{
            $this->db->select($select)->from($table);
        }

        if(!is_null($join)){
            foreach ($join as $rows) {
                if(!isset($rows['join'])){ $rows['join'] = 'inner'; }

                $this->db->join($rows['table'], $rows['on'], $rows['join']);
            }
        }

        (!is_null($order) ? $this->db->order_by($order[0], $order[1]) : '');
        (!is_null($tampil) ? $this->db->limit($tampil, $start) : '');
        (!is_null($where) ? $this->db->where($where) : '');
        (!is_null($where_e) ? $this->db->where($where_e, NULL, FALSE) : '');
        (!is_null($group) ? $this->db->group_by($group, NULL, FALSE) : '');

        $query  = $this->db->get();
        $result = $query->result_array();

        return $result;
    }

    public function insert($table, $data = NULL)
    {
        $result    = $this->db->insert($table, $data);
        if($result == TRUE){
            $result = [];
            $result['status'] = TRUE;
            //$result['id']     = $this->db->insert_id();
        }else{
            $result = [];
            $result['status'] = FALSE;
        }

        return $result;
    }

    public function insertBatch($table, $data = NULL)
    {
        $result    = $this->db->insert_batch($table, $data);
        if($result == TRUE){
            $result = [];
            $result['status'] = TRUE;
//            $result['id']     = $this->db->insert_id();
        }else{
            $result = [];
            $result['status'] = FALSE;
        }

        return $result;
    }

    public function update($table, $data = NULL, $where = NULL, $where_e = NULL)
    {
        (!is_null($where_e) ? $this->db->where($where_e, NULL, FALSE) : '');
        $result    = $this->db->update($table, $data, $where);
        return $result;
    }

    public function delete($table, $where = NULL, $where_e = NULL)
    {
        (!is_null($where_e) ? $this->db->where($where_e, NULL, FALSE) : '');
        (!is_null($where) ? $this->db->where($where) : '');
        $result    = $this->db->delete($table);
        return $result;
    }

    public function validation($table, $where, $where_e = NULL)
    {
        $this->db->select('*')->from($table);
        
        (!is_null($where) ? $this->db->where($where) : '');
        (!is_null($where_e) ? $this->db->where($where_e, NULL, FALSE) : '');

        $query  = $this->db->get();
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
        $this->db->select("count(*) as jumlah")->from($table);

        if(!is_null($join)){
            foreach ($join as $rows) {
                if(!isset($rows['join'])){ $rows['join'] = 'inner'; }

                $this->db->join($rows['table'], $rows['on'], $rows['join']);
            }
        }

        (!is_null($where) ? $this->db->where($where) : '');
        (!is_null($where_e) ? $this->db->where($where_e, NULL, FALSE) : '');
        (!is_null($group) ? $this->db->group_by($group, NULL, FALSE) : '');

        $query  = $this->db->get();
        $result = $query->num_rows();

        return $result;
    }

    public function getLastID($table, $field)
    {
        $this->db->select("MAX(".$field.")+1 as ID")->from($table);

        $query  = $this->db->get();
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
        if(is_array($select)){
            $this->db->select( $select[0], $select[1] )->from($table);
        }else{
            $this->db->select($select)->from($table);
        }

        if(!is_null($join)){
            foreach ($join as $rows) {
                if(!isset($rows['join'])){ $rows['join'] = 'inner'; }

                $this->db->join($rows['table'], $rows['on'], $rows['join']);
            }
        }

        (!is_null($order) ? $this->db->order_by($order) : '');
        (!is_null($tampil) ? $this->db->limit($tampil, $start) : '');
        (!is_null($where) ? $this->db->where($where) : '');
        (!is_null($where_e) ? $this->db->where($where_e, NULL, FALSE) : '');
        (!is_null($group) ? $this->db->group_by($group, NULL, FALSE) : '');

        $query  = $this->db->get();
        $result = $query->result();

        return $result;
    }

    public function oquery($query, $tipe = '0')
    {
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