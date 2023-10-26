<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Classteacher extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		is_login();
	}

	public function index()
	{
		$data = $this->Classteacher_model->get_all();
		$data['title']		= 'Guru ~ Kelas';
		$data['content']	= 'relation/classteacher/index';
		$this->load->view('layouts/wrapper', $data);
	}

    public function ajax_list()
    {
        $list = $this->Classteacher_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
        	$exp = explode(', ', $item->classroom);
        	$classroom = [];
        	foreach($exp as $val){
        		$class_span = '<span class="badge badge-primary">'.$val.'</span>';
        		array_push($classroom, $class_span);
        	}
            $no++;
            $row = array();
            $row[] = '<input type="checkbox" class="data-check" value="'.sha1($item->id_teacher).'">';
            $row[] = $no;
            $row[] = $item->teacher_name;
            $row[] = $item->teacher_nip;
            $row[] = $classroom;
 
            //add html for action
            $row[] = '<div class="btn-group">
                        <a class="btn btn-sm btn-warning" href="'.site_url('relation/classteacher/edit/'.sha1($item->id_teacher)).'"><i class="fa fa-edit"></i></a>
                        <a class="btn btn-sm btn-danger" href="javascript:void(0)" onclick="delete_data('."'".sha1($item->id_teacher)."'".')"><i class="fa fa-times"></i></a>
                      </div>';
 
            $data[] = $row;
        }
 
        $output = [
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Classteacher_model->count_all(),
            "recordsFiltered" => $this->Classteacher_model->count_filtered(),
            "data" => $data,
        ];
        //output to json format
        echo json_encode($output);
    }

	public function add()
	{
        $this->form_validation->set_rules('id_teacher', 'Guru', 'trim|required');
		$this->form_validation->set_rules('id_classroom[]', 'Kelas', 'trim|required');
		
		if ($this->form_validation->run()) {
			$id_teacher 	= $this->input->post('id_teacher');
			$id_classroom 	= $this->input->post('id_classroom');

			$success = FALSE;
			foreach($id_classroom as $key => $val){
				$data = [
					'id_teacher'	=> $id_teacher,
					'id_classroom'	=> $val
				];
				$save = $this->Classteacher_model->save($data);
				if($save){
					$success = TRUE;
				}
			}

			if($success === TRUE){
				$this->session->set_flashdata('success', 'Data telah ditambahkan.');
				redirect('relation/classteacher','refresh');
			}
		} else {
			$data['title']      = 'Tambah Guru ~ Kelas';
			$data['teacher']	= $this->Classteacher_model->get_teacher();
			$data['classroom']	= $this->Classroom_model->get_all();
			$data['content']	= 'relation/classteacher/add';
			$this->load->view('layouts/wrapper', $data);
		}
	}

	public function edit($id_teacher)
	{
		$data['classteacher'] = $this->Classteacher_model->get_classroom_by_teacher($id_teacher);

        $this->form_validation->set_rules('id_teacher', 'Guru', 'trim|required');
		$this->form_validation->set_rules('id_classroom[]', 'Kelas', 'trim|required');
		
		if ($this->form_validation->run()) {
			$teacher_id 	= $this->input->post('id_teacher');
			$id_classroom 	= $this->input->post('id_classroom');

			$success = FALSE;
			$this->Classteacher_model->delete($id_teacher);
			foreach($id_classroom as $key => $val){
				$data = [
					'id_teacher'	=> $teacher_id,
					'id_classroom'	=> $val
				];
				$save = $this->Classteacher_model->save($data);
				if($save){
					$success = TRUE;
				}
			}

			if($success === TRUE){
				$this->session->set_flashdata('success', 'Data telah ditambahkan.');
				redirect('relation/classteacher','refresh');
			}
		} else {
			$data['title']      = 'Ubah Guru ~ Kelas';
			$data['teacher']	= $this->Teacher_model->get_by_id($id_teacher);
			$data['classroom']	= $this->Classroom_model->get_all();
			$data['content']	= 'relation/classteacher/edit';
			$this->load->view('layouts/wrapper', $data);
		}
	}
 
	public function ajax_delete($id_teacher)
	{
		$delete = $this->Classteacher_model->delete($id_teacher);
		if($delete){
			echo json_encode(array("status" => TRUE));
		}
	}

	public function ajax_bulk_delete()
	{
		$list_id = $this->input->post('id_classteacher',TRUE);
		foreach ($list_id as $id_teacher){
			$this->Classteacher_model->delete($id_teacher);
		}
		echo json_encode(array("status" => TRUE));
	}

	public function print($data)
	{
		echo '<pre>';
		print_r($data);
		echo '</pre>';
	}

}

/* End of file Classteacher.php */
/* Location: ./application/controllers/admin/Classteacher.php */