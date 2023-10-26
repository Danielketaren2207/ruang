<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lesson extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		is_login();
        if(is_student()){
            redirect('dashboard','refresh');
        }
	}

	public function index()
	{
		$data['title']		= 'Mata Pelajaran';
		$data['content']	= 'lesson/index';
		$this->load->view('layouts/wrapper', $data);
	}

    public function ajax_list()
    {
        $list = $this->Lesson_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = '<input type="checkbox" class="data-check" value="'.sha1($item->id).'">';
            $row[] = $no;
            $row[] = $item->name;
 
            //add html for action
            $row[] = '<div class="btn-group">
                        <a class="btn btn-sm btn-warning" href="'.site_url('lesson/edit/'.sha1($item->id)).'"><i class="fa fa-edit"></i></a>
                        <a class="btn btn-sm btn-danger" href="javascript:void(0)" onclick="delete_data('."'".sha1($item->id)."'".')"><i class="fa fa-times"></i></a>
                      </div>';
 
            $data[] = $row;
        }
 
        $output = [
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Lesson_model->count_all(),
            "recordsFiltered" => $this->Lesson_model->count_filtered(),
            "data" => $data,
        ];
        //output to json format
        echo json_encode($output);
    }

	public function add()
	{
		$this->form_validation->set_rules('name', 'Nama Mata Pelajaran', 'trim|required');
		
		if ($this->form_validation->run()) {
			$data = [
				'name' => $this->input->post('name')
			];
			$save = $this->Lesson_model->save($data);
			$this->session->set_flashdata('success', 'Data telah ditambahkan.');
			redirect('lesson','refresh');
		} else {
			$data['title']		= 'Tambah Mata Pelajaran';
			$data['content']	= 'lesson/add';
			$this->load->view('layouts/wrapper', $data);
		}
	}

	public function edit($id)
	{
		$data['lesson'] = $this->Lesson_model->get_by_id($id);

		$this->form_validation->set_rules('name', 'Nama Mata Pelajaran', 'trim|required');
		
		if ($this->form_validation->run()) {
			$data = [
				'name' => $this->input->post('name')
			];
			$update = $this->Lesson_model->update($id,$data);
            if($update){
                $this->session->set_flashdata('success', 'Data telah diubah.');
                redirect('lesson','refresh');
            }else{
                $this->session->set_flashdata('warning', 'Tidak ada data yang diubah.');
                redirect('lesson/edit/'.$id,'refresh');
            }
		} else {
			$data['title']		= 'Ubah Mata Pelajaran';
			$data['content']	= 'lesson/edit';
			$this->load->view('layouts/wrapper', $data);
		}
	}
 
    public function ajax_delete($id)
    {
        $this->Lesson_model->delete($id);
        echo json_encode(array("status" => TRUE));
    }
 
    public function ajax_bulk_delete()
    {
        $list_id = $this->input->post('id_lesson',TRUE);
        
        foreach ($list_id as $id){
            $this->Lesson_model->delete($id);
        }
        echo json_encode(array("status" => TRUE));
    }

}

/* End of file Lesson.php */
/* Location: ./application/controllers/admin/Lesson.php */