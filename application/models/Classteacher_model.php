<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Classteacher_model extends CI_Model {

	protected $table = 'tbl_class_teacher';
    protected $id = 'sha1(id)';
    protected $column_order = array('b.name');
    protected $column_search = array('b.name');
    protected $order = array('b.name' => 'asc');
 
    private function _get_datatables_query()
    {
        $this->db->select('a.id, b.id as id_teacher, b.name as teacher_name, b.nip as teacher_nip, GROUP_CONCAT(c.name SEPARATOR ",") as classroom');
        $this->db->from('tbl_class_teacher a');
        $this->db->join('tbl_teacher b', 'a.id_teacher = b.id', 'left');
        $this->db->join('tbl_classroom c', 'a.id_classroom = c.id', 'left');
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
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
 
    public function get_all()
    {
        $this->db->select('a.id, b.id as id_teacher, b.name as teacher_name, b.nip as teacher_nip, GROUP_CONCAT(c.name SEPARATOR ",") as classroom');
        $this->db->from('tbl_class_teacher a');
        $this->db->join('tbl_teacher b', 'a.id_teacher = b.id', 'left');
        $this->db->join('tbl_classroom c', 'a.id_classroom = c.id', 'left');
        $this->db->group_by('b.name');
        $query = $this->db->get();
        return $query->result();
    }
 
    public function get_teacher()
    {
        $this->db->select('id_teacher');
        $this->db->from('tbl_class_teacher');
        $data = $this->db->get()->result();

        $id_teacher = [];
        foreach($data as $row){
            $id_teacher[] = $row->id_teacher;
        }
        if($id_teacher === []){
            $id_teacher = NULL;
        }

        $this->db->select('*');
        $this->db->from('tbl_teacher');
        $this->db->where_not_in('id', $id_teacher);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_classroom_by_teacher($id_teacher)
    {
        $this->db->select('id_classroom');
        $this->db->from('tbl_class_teacher');
        $this->db->where('sha1(id_teacher)', $id_teacher);
        $query = $this->db->get();
        return $query->result();
    }
 
    public function save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
 
    public function update($id_teacher, $data)
    {
        $this->db->where('sha1(id_teacher)', $id_teacher);
        $this->db->update($this->table, $data);
        return $this->db->affected_rows();
    }
 
    public function delete($id_teacher)
    {
        $this->db->where('sha1(id_teacher)', $id_teacher);
        $this->db->delete($this->table);
        return $this->db->affected_rows();
    }

}

/* End of file Classteacher_model.php */
/* Location: ./application/models/Classteacher_model.php */