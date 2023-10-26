<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Majorlesson extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		is_login();
	}

	public function index()
	{
		$data = $this->Majorlesson_model->get_all();
		$data['title']		= 'Mata Pelajaran ~ Jurusan';
		$data['content']	= 'relation/majorlesson/index';
		$this->load->view('layouts/wrapper', $data);
	}

    public function ajax_list()
    {
        $list = $this->Majorlesson_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
        	$exp = explode(', ', $item->major);
        	$major = [];
        	foreach($exp as $val){
        		$class_span = '<span class="badge badge-primary">'.$val.'</span>';
        		array_push($major, $class_span);
        	}
            $no++;
            $row = array();
            $row[] = '<input type="checkbox" class="data-check" value="'.sha1($item->id_lesson).'">';
            $row[] = $no;
            $row[] = $item->lesson_name;
            $row[] = $major;
 
            //add html for action
            $row[] = '<div class="btn-group">
                        <a class="btn btn-sm btn-warning" href="'.site_url('relation/majorlesson/edit/'.sha1($item->id_lesson)).'"><i class="fa fa-edit"></i></a>
                        <a class="btn btn-sm btn-danger" href="javascript:void(0)" onclick="delete_data('."'".sha1($item->id_lesson)."'".')"><i class="fa fa-times"></i></a>
                      </div>';
 
            $data[] = $row;
        }
 
        $output = [
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Majorlesson_model->count_all(),
            "recordsFiltered" => $this->Majorlesson_model->count_filtered(),
            "data" => $data,
        ];
        //output to json format
        echo json_encode($output);
    }

	public function add()
	{
        $this->form_validation->set_rules('id_lesson', 'Mata Pelajaran', 'trim|required');
		$this->form_validation->set_rules('id_major[]', 'Jurusan', 'trim|required');
		
		if ($this->form_validation->run()) {
			$id_lesson 	= $this->input->post('id_lesson');
			$id_major 	= $this->input->post('id_major');

			$success = FALSE;
			foreach($id_major as $key => $val){
				$data = [
					'id_lesson'	=> $id_lesson,
					'id_major'	=> $val
				];
				$save = $this->Majorlesson_model->save($data);
				if($save){
					$success = TRUE;
				}
			}

			if($success === TRUE){
				$this->session->set_flashdata('success', 'Data telah ditambahkan.');
				redirect('relation/majorlesson','refresh');
			}
		} else {
			$data['title']      = 'Tambah Mata Pelajaran ~ Jurusan';
			$data['lesson']	= $this->Majorlesson_model->get_lesson();
			$data['major']	= $this->Major_model->get_all();
			$data['content']	= 'relation/majorlesson/add';
			$this->load->view('layouts/wrapper', $data);
		}
	}

	public function edit($id_lesson)
	{
		$data['majorlesson'] = $this->Majorlesson_model->get_major_by_lesson($id_lesson);

        $this->form_validation->set_rules('id_lesson', 'Mata Pelajaran', 'trim|required');
		$this->form_validation->set_rules('id_major[]', 'Jurusan', 'trim|required');
		
		if ($this->form_validation->run()) {
			$lesson_id 	= $this->input->post('id_lesson');
			$id_major 	= $this->input->post('id_major');

			$success = FALSE;
			$this->Majorlesson_model->delete($id_lesson);
			foreach($id_major as $key => $val){
				$data = [
					'id_lesson'	=> $lesson_id,
					'id_major'	=> $val
				];
				$save = $this->Majorlesson_model->save($data);
				if($save){
					$success = TRUE;
				}
			}

			if($success === TRUE){
				$this->session->set_flashdata('success', 'Data telah ditambahkan.');
				redirect('relation/majorlesson','refresh');
			}
		} else {
			$data['title']      = 'Ubah Mata Pelajaran ~ Jurusan';
			$data['lesson']	= $this->Lesson_model->get_by_id($id_lesson);
			$data['major']	= $this->Major_model->get_all();
			$data['content']	= 'relation/majorlesson/edit';
			$this->load->view('layouts/wrapper', $data);
		}
	}
 
	public function ajax_delete($id_lesson)
	{
		$delete = $this->Majorlesson_model->delete($id_lesson);
		if($delete){
			echo json_encode(array("status" => TRUE));
		}
	}

	public function ajax_bulk_delete()
	{
		$list_id = $this->input->post('id_majorlesson',TRUE);
		foreach ($list_id as $id_lesson){
			$this->Majorlesson_model->delete($id_lesson);
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

/* End of file Majorlesson.php */
/* Location: ./application/controllers/admin/Majorlesson.php */