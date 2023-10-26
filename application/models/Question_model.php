<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Question_model extends CI_Model {

    protected $table = 'tbl_question';
    protected $id = 'sha1(id)';
    protected $column_order = array('b.name','c.name');
    protected $column_search = array('b.name','c.name');
    protected $order = array('b.name' => 'asc');
 
    private function _get_datatables_query($id_teacher=NULL)
    {
        $this->db->select('a.*, b.name as teacher, c.name as lesson');
        $this->db->from('tbl_question a');
        $this->db->join('tbl_teacher b', 'a.id_teacher = b.id', 'left');
        $this->db->join('tbl_lesson c', 'a.id_lesson = c.id', 'left');
        if($id_teacher != NULL){
            $this->db->where('b.id', $id_teacher);
        }
 
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
 
    function get_datatables($id_teacher=NULL)
    {
        $this->_get_datatables_query($id_teacher);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
 
    function count_filtered($id_teacher=NULL)
    {
        $this->_get_datatables_query($id_teacher);
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all($id_teacher=NULL)
    {
        $this->_get_datatables_query($id_teacher);
        return $this->db->count_all_results();
    }
 
    public function get_all($id_teacher=NULL,$id_lesson=NULL)
    {
        $this->db->select('a.*, b.name as teacher, c.name as lesson');
        $this->db->from('tbl_question a');
        $this->db->join('tbl_teacher b', 'a.id_teacher = b.id', 'left');
        $this->db->join('tbl_lesson c', 'a.id_lesson = c.id', 'left');
        if($id_teacher != NULL){
            $this->db->where('a.id_teacher', $id_teacher);
        }
        if($id_lesson != NULL){
            $this->db->where('a.id_lesson', $id_lesson);
        }
        $query = $this->db->get();
        return $query->result();
    }
 
    public function get_by_id($id)
    {
        $this->db->select('a.*, b.name as teacher, c.name as lesson');
        $this->db->from('tbl_question a');
        $this->db->join('tbl_teacher b', 'a.id_teacher = b.id', 'left');
        $this->db->join('tbl_lesson c', 'a.id_lesson = c.id', 'left');
        $this->db->where('sha1(a.id)', $id);
        $query = $this->db->get();
        return $query->row();
    }
 
    public function get_lesson_by_teacher($id_teacher)
    {
        $this->db->select('a.id_lesson, b.name as lesson');
        $this->db->from('tbl_lesson_teacher a');
        $this->db->join('tbl_lesson b', 'a.id_lesson = b.id', 'left');
        $this->db->where('a.id_teacher', $id_teacher);
        $query = $this->db->get();
        return $query->result();
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

    public function import($data)
    {
        $jumlah = count($data);
        if($jumlah > 0) {
            $this->db->replace($this->table, $data);
        }
    }

}

/* End of file Question_model.php */
/* Location: ./application/models/Question_model.php */