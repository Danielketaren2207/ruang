<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exam_model extends CI_Model {

	protected $table = 'tbl_exam';
    protected $id = 'sha1(id)';
    protected $column_order = array('a.name');
    protected $column_search = array('a.name');
    protected $order = array('a.date_end' => 'desc');
 
    private function _get_datatables_query($id_teacher=NULL,$id_major=NULL)
    {
        $this->db->select('a.*, b.name as teacher, c.name as lesson, d.id_major');
        $this->db->from('tbl_exam a');
        $this->db->join('tbl_teacher b', 'a.id_teacher = b.id', 'left');
        $this->db->join('tbl_lesson c', 'a.id_lesson = c.id', 'left');
        $this->db->join('tbl_major_lesson d', 'c.id = d.id_lesson', 'left');
        if($id_teacher != NULL){
            $this->db->where('a.id_teacher', $id_teacher);
        }
        if($id_major != NULL){
            $this->db->where('d.id_major', $id_major);
        }
        if(!is_student()){
            $this->db->group_by('a.id');
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
 
    function get_datatables($id_teacher,$id_major)
    {
        $this->_get_datatables_query($id_teacher,$id_major);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
 
    function count_filtered($id_teacher,$id_major)
    {
        $this->_get_datatables_query($id_teacher,$id_major);
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all($id_teacher,$id_major)
    {
        $this->_get_datatables_query($id_teacher,$id_major);
        return $this->db->count_all_results();
    }
 
    public function get_all()
    {
        $this->db->select('a.*, b.name as teacher, c.name as lesson');
        $this->db->from('tbl_exam a');
        $this->db->join('tbl_teacher b', 'a.id_teacher = b.id', 'left');
        $this->db->join('tbl_lesson c', 'a.id_lesson = c.id', 'left');
        $this->db->order_by('a.date_end', 'desc');
        $query = $this->db->get();
        return $query->result();
    }
 
    public function get_by_id($id)
    {
        $this->db->select('a.*, b.name as teacher, c.name as lesson');
        $this->db->from('tbl_exam a');
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

    public function get_question($question_type, $id_teacher, $id_lesson, $noq)
    {
        $order = ($question_type == 1) ? 'rand()' : 'id';

        $this->db->select('*');
        $this->db->from('tbl_question');
        $this->db->where('id_teacher', $id_teacher);
        $this->db->where('id_lesson', $id_lesson);
        $this->db->order_by($order);
        $this->db->limit($noq);
        return $this->db->get()->result();
    }

    public function get_question_by_id($id_question, $answer=NULL){
        if($answer != NULL){
            $this->db->select('*, '.$answer.' as answer');
        }else{
            $this->db->select('*');
        }
        $this->db->from('tbl_question');
        $this->db->where('id', $id_question);
        return $this->db->get()->row();
    }

    public function get_exam_test($id, $id_exam, $id_student)
    {
        $this->db->select('*');
        $this->db->from('tbl_exam_result');
        $this->db->where('id', $id);
        $this->db->where('sha1(id_exam)', $id_exam);
        $this->db->where('id_student', $id_student);
        return $this->db->get()->row();
    }

    public function get_exam_result($id_student, $id=NULL)
    {
        $this->db->select('*, UNIX_TIMESTAMP(date_end) as time_end');
        $this->db->from('tbl_exam_result');
        $this->db->where('id_student', $id_student);
        if($id != NULL){
            $this->db->where('sha1(id_exam)', $id);
        }
        return $this->db->get();
    }

    public function get_answer_by_id($id_test)
    {
        $this->db->select('list_answer');
        $this->db->from('tbl_exam_result');
        $this->db->where('id', $id_test);
        return $this->db->get()->row();
    }
 
    public function save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
 
    public function save_result($data)
    {
        $this->db->insert('tbl_exam_result', $data);
        return $this->db->insert_id();
    }
 
    public function update($id, $data)
    {
        $this->db->where($this->id, $id);
        $this->db->update($this->table, $data);
        return $this->db->affected_rows();
    }
 
    public function update_result($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_exam_result', $data);
        return $this->db->affected_rows();
    }
 
    public function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }

}

/* End of file Exam_model.php */
/* Location: ./application/models/Exam_model.php */