<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_global extends CI_Model {

    public function __construct(){
        parent::__construct();
    }

    public function countDataAll($table, $join = NULL, $where = NULL, $where_e = NULL, $group = NULL)
    {   
        $this->db->select(" count(*) as total ")->from($table);

        if(!is_null($join)){
            foreach ($join as $rows) {
                if(!isset($rows['join'])){ $rows['join'] = 'inner'; }

                $this->db->join($rows['table'], $rows['on'], $rows['join']);
            }
        }
        
        $where2 = NULL;
        if(!is_array($where)){
            $where2 = $where;
            $where = NULL;
        }

        (!is_null($where) ? $this->db->where($where) : '');
        (!is_null($where2) ? $this->db->where($where2, NULL, FALSE) : '');
        (!is_null($where_e) ? $this->db->where($where_e, NULL, FALSE) : '');
        (!is_null($group) ? $this->db->group_by($group, NULL, FALSE) : '');

        $query  = $this->db->get();

        $result = @$query->row()->total;

        if($result == ''){$result = 0;}
        
        return $result;
    }

    public function getDataAll($table, $join = NULL, $where = NULL, $select = '*', $where_e = NULL, $order = NULL, $start = NULL, $tampil = NULL, $group = NULL, $tipe = 0, $where_in = null )
    {
        if(is_array($select)){
            $this->db->select($select[0], $select[1])->from($table);
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
        
        $where2 = NULL;
        if(!is_array($where)){
            $where2 = $where;
            $where = NULL;
        }

        if(is_null($start)){ $start == 0;}
        (!is_null($tampil) ? $this->db->limit($tampil, $start) : '');
        (!is_null($where) ? $this->db->where($where) : '');
        (!is_null($where2) ? $this->db->where($where2, NULL, FALSE) : '');
        (!is_null($where_e) ? $this->db->where($where_e, NULL, FALSE) : '');
        (!is_null($where_in) ? $this->db->where_in($where_in, NULL) : '');
        (!is_null($group) ? $this->db->group_by($group, NULL, FALSE) : '');

        $query  = @$this->db->get();

        if($tipe == 1){
            $query  = $this->db->last_query();
            $query  = str_replace("``", "", $query);
            return $query;
        }elseif($tipe == 2){
            return $query->result_array();
        }elseif($tipe == 3){
            $sql  = $this->db->last_query(); 
            $sql  = str_replace("``", "", $sql);
            $query = $this->db->query($sql)->result();
            return $query;
        }else{
            return $query->result();
        }
    }

    public function insert($table, $data = NULL)
    {
        $result    = $this->db->insert($table, $data);
        if($result == TRUE){
            $result = [];
            $result['status'] = TRUE;
            $result['id']     = $this->db->insert_id();
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
        }else{
            $result = [];
            $result['status'] = FALSE;
        }

        return $result;
    }

    public function update($table, $data = NULL, $where = NULL, $where_e = NULL)
    {
        (!is_null($where_e) ? $this->db->where($where_e, NULL, FALSE) : '');
        $res = $this->db->update($table, $data, $where);
        if($res){
            $result['status'] = TRUE;
        }else{
            $result['status'] = FALSE;
        }
        return $result;
    }

    public function delete($table, $where = NULL, $where_e = NULL)
    {
        (!is_null($where_e) ? $this->db->where($where_e, NULL, FALSE) : '');
        (!is_null($where) ? $this->db->where($where) : '');
        $result    = $this->db->delete($table);
        return $result;
    }

}

/* End of file m_global.php */
/* Location: ./application/modules/global/models/m_global.php */