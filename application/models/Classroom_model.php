<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Classroom_model extends CI_Model {

	protected $table = 'tbl_classroom';
    protected $id = 'sha1(id)';
    protected $column_order = array('name');
    protected $column_search = array('name');
    protected $order = array('name' => 'asc');
 
    private function _get_datatables_query()
    {
        $this->db->select('a.*,b.name as major');
        $this->db->from('tbl_classroom a');
        $this->db->join('tbl_major b', 'a.id_major = b.id', 'left');
 
        $i = 0;
     
        foreach ($this->column_search as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($this->column_search) - 1 == $i){ //last loop
                    $this->db->group_end(); //close bracket
                }
            }
            $i++;
        }
         
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
 
    function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
 
    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
 
    public function get_all()
    {
        $this->db->select('a.*,b.name as major');
        $this->db->from('tbl_classroom a');
        $this->db->join('tbl_major b', 'a.id_major = b.id', 'left');
        $this->db->order_by('name', 'asc');
        $query = $this->db->get();
        return $query->result();
    }
 
    public function get_by_id($id)
    {
        $this->db->select('a.*,b.name as major');
        $this->db->from('tbl_classroom a');
        $this->db->join('tbl_major b', 'a.id_major = b.id', 'left');
        $this->db->where('sha1(a.id)', $id);
        $query = $this->db->get();
        return $query->row();
    }
 
    public function save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
 
    public function update($id, $data)
    {
        $this->db->where($this->id, $id);
        $this->db->update($this->table, $data);
        return $this->db->affected_rows();
    }
 
    public function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }

}

/* End of file Classroom_model.php */
/* Location: ./application/models/Classroom_model.php */