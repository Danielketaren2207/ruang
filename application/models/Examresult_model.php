<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Examresult_model extends CI_Model {

    protected $table = 'tbl_exam_result';
    protected $id = 'sha1(id)';
    protected $column_order = array('b.name');
    protected $column_search = array('b.name');
    protected $order = array('a.id' => 'desc');
 
    private function _get_datatables_query($id_teacher=NULL)
    {
        $this->db->select('a.*, b.name as exam, b.noq, b.time, b.date_start as exam_date_start, c.name as teacher, d.name as lesson');
        $this->db->from('tbl_exam_result a');
        $this->db->join('tbl_exam b', 'a.id_exam = b.id', 'left');
        $this->db->join('tbl_teacher c', 'b.id_teacher = c.id', 'left');
        $this->db->join('tbl_lesson d', 'b.id_lesson = d.id', 'left');
        if($id_teacher != NULL){
            $this->db->where('b.id_teacher', $id_teacher);
        }
        $this->db->group_by('a.id_exam');
 
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
 
    function get_datatables($id_teacher)
    {
        $this->_get_datatables_query($id_teacher);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
 
    function count_filtered($id_teacher)
    {
        $this->_get_datatables_query($id_teacher);
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all($id_teacher)
    {
        $this->_get_datatables_query($id_teacher);
        return $this->db->count_all_results();
    }
 
    public function get_all()
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $query = $this->db->get();
        return $query->result();
    }
 
    public function get_by_id($id)
    {
        $this->db->select('a.*, MIN(a.total_value) as min_value, FORMAT(FLOOR(AVG(a.total_value)),0) as avg_value, MAX(a.total_value) as max_value, b.name as exam_name, b.noq, b.time, b.date_start as exam_date_start, b.date_end as exam_date_end, c.name as teacher, d.name as lesson');
        $this->db->from('tbl_exam_result a');
        $this->db->join('tbl_exam b', 'a.id_exam = b.id', 'left');
        $this->db->join('tbl_teacher c', 'b.id_teacher = c.id', 'left');
        $this->db->join('tbl_lesson d', 'b.id_lesson = d.id', 'left');
        $this->db->where('sha1(a.id_exam)', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_exam_result($id_exam, $id_student)
    {
        $this->db->select('a.total_true, a.total_value, a.date_start, a.date_end, TIMEDIFF(a.date_end, a.date_start) as exam_time, b.name as exam_name, b.noq, b.time, b.date_start as exam_date_start, b.date_end as exam_date_end, c.name as teacher, c.nip, d.name as lesson, e.name as student, e.nis, e.email, e.phone, f.name as classroom, g.name as major');
        $this->db->from('tbl_exam_result a');
        $this->db->join('tbl_exam b', 'a.id_exam = b.id', 'left');
        $this->db->join('tbl_teacher c', 'b.id_teacher = c.id', 'left');
        $this->db->join('tbl_lesson d', 'b.id_lesson = d.id', 'left');
        $this->db->join('tbl_student e', 'a.id_student = e.id', 'left');
        $this->db->join('tbl_classroom f', 'e.id_classroom = f.id', 'left');
        $this->db->join('tbl_major g', 'e.id_major = g.id', 'left');
        $this->db->where('sha1(id_exam)', $id_exam);
        if($id_student != NULL){
            $this->db->where('id_student', $id_student);
        }
        return $this->db->get()->row();
    }

    public function get_exam_summary($id_exam)
    {
        $this->db->select('a.*, b.name as exam_name, b.noq, c.name as teacher, d.name as lesson, e.name as student, f.name as classroom, g.name as major');
        $this->db->from('tbl_exam_result a');
        $this->db->join('tbl_exam b', 'a.id_exam = b.id', 'left');
        $this->db->join('tbl_teacher c', 'b.id_teacher = c.id', 'left');
        $this->db->join('tbl_lesson d', 'b.id_lesson = d.id', 'left');
        $this->db->join('tbl_student e', 'a.id_student = e.id', 'left');
        $this->db->join('tbl_classroom f', 'e.id_classroom = f.id', 'left');
        $this->db->join('tbl_major g', 'e.id_major = g.id', 'left');
        $this->db->where('sha1(a.id_exam)', $id_exam);
        return $this->db->get()->result();
    }

    public function get_exam_resume($id_exam, $id_student)
    {
        $this->db->select('a.*, b.name as exam_name, b.noq, c.name as teacher, d.name as lesson, e.name as student, f.name as classroom, g.name as major');
        $this->db->from('tbl_exam_result a');
        $this->db->join('tbl_exam b', 'a.id_exam = b.id', 'left');
        $this->db->join('tbl_teacher c', 'b.id_teacher = c.id', 'left');
        $this->db->join('tbl_lesson d', 'b.id_lesson = d.id', 'left');
        $this->db->join('tbl_student e', 'a.id_student = e.id', 'left');
        $this->db->join('tbl_classroom f', 'e.id_classroom = f.id', 'left');
        $this->db->join('tbl_major g', 'e.id_major = g.id', 'left');
        $this->db->where('sha1(a.id_exam)', $id_exam);
        $this->db->where('sha1(a.id_student)', $id_student);
        return $this->db->get()->row();
    }


    // DETAIL DATA ========================================================= //
    protected $column_order_detail = array('a.name');
    protected $column_search_detail = array('a.name');
    protected $order_detail = array('d.total_value' => 'desc');
 
    private function _get_datatables_query_detail($id)
    {
        $this->db->select('a.name as student, b.name as classroom, c.name as major, d.id_exam, d.id_student, d.total_true, d.total_value');
        $this->db->from('tbl_student a');
        $this->db->join('tbl_classroom b', 'a.id_classroom = b.id', 'left');
        $this->db->join('tbl_major c', 'a.id_major = c.id', 'left');
        $this->db->join('tbl_exam_result d', 'a.id = d.id_student', 'left');
        $this->db->where('sha1(d.id_exam)', $id);
 
        $i = 0;
     
        foreach ($this->column_search_detail as $item) // loop column 
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
 
                if(count($this->column_search_detail) - 1 == $i){ //last loop
                    $this->db->group_end(); //close bracket
                }
            }
            $i++;
        }
         
        if(isset($_POST['order_detail'])) // here order_detail processing
        {
            $this->db->order_by($this->column_order_detail_detail[$_POST['order_detail']['0']['column']], $_POST['order_detail']['0']['dir']);
        } 
        else if(isset($this->order_detail))
        {
            $order_detail = $this->order_detail;
            $this->db->order_by(key($order_detail), $order_detail[key($order_detail)]);
        }
    }
 
    function get_datatables_detail($id)
    {
        $this->_get_datatables_query_detail($id);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
 
    function count_filtered_detail($id)
    {
        $this->_get_datatables_query_detail($id);
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all_detail($id)
    {
        $this->_get_datatables_query_detail($id);
        return $this->db->count_all_results();
    }
 
    public function delete($id_exam, $id_student)
    {
        $this->db->where('sha1(id_exam)', $id_exam);
        $this->db->where('sha1(id_student)', $id_student);
        $this->db->delete($this->table);
    }

}

/* End of file Examresult_model.php */
/* Location: ./application/models/Examresult_model.php */