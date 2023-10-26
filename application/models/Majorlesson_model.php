<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Majorlesson_model extends CI_Model {

	protected $table = 'tbl_major_lesson';
    protected $id = 'sha1(id)';
    protected $column_order = array('b.name');
    protected $column_search = array('b.name');
    protected $order = array('b.name' => 'asc');
 
    private function _get_datatables_query()
    {
        $this->db->select('a.id, b.id as id_lesson, b.name as lesson_name, GROUP_CONCAT(c.name SEPARATOR ",") as major');
        $this->db->from('tbl_major_lesson a');
        $this->db->join('tbl_lesson b', 'a.id_lesson = b.id', 'left');
        $this->db->join('tbl_major c', 'a.id_major = c.id', 'left');
        $this->db->group_by('b.name');
 
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
        $this->_get_datatables_query();
        return $this->db->count_all_results();
    }
 
    public function get_all()
    {
        $this->db->select('a.id, b.id as id_lesson, b.name as lesson_name, GROUP_CONCAT(c.name SEPARATOR ",") as major');
        $this->db->from('tbl_major_lesson a');
        $this->db->join('tbl_lesson b', 'a.id_lesson = b.id', 'left');
        $this->db->join('tbl_major c', 'a.id_major = c.id', 'left');
        $this->db->group_by('b.name');
        $query = $this->db->get();
        return $query->result();
    }
 
    public function get_lesson()
    {
        $this->db->select('id_lesson');
        $this->db->from('tbl_major_lesson');
        $data = $this->db->get()->result();

        $id_lesson = [];
        foreach($data as $row){
            $id_lesson[] = $row->id_lesson;
        }
        if($id_lesson === []){
            $id_lesson = NULL;
        }

        $this->db->select('*');
        $this->db->from('tbl_lesson');
        $this->db->where_not_in('id', $id_lesson);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_major_by_lesson($id_lesson)
    {
        $this->db->select('id_major');
        $this->db->from('tbl_major_lesson');
        $this->db->where('sha1(id_lesson)', $id_lesson);
        $query = $this->db->get();
        return $query->result();
    }
 
    public function save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
 
    public function update($id_lesson, $data)
    {
        $this->db->where('sha1(id_lesson)', $id_lesson);
        $this->db->update($this->table, $data);
        return $this->db->affected_rows();
    }
 
    public function delete($id_lesson)
    {
        $this->db->where('sha1(id_lesson)', $id_lesson);
        $this->db->delete($this->table);
        return $this->db->affected_rows();
    }

}

/* End of file Majorlesson_model.php */
/* Location: ./application/models/Majorlesson_model.php */