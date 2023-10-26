<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Classroom extends CI_Controller {

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
		$data['title']		= 'Kelas';
		$data['content']	= 'classroom/index';
		$this->load->view('layouts/wrapper', $data);
	}

    public function ajax_list()
    {
        $list = $this->Classroom_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = '<input type="checkbox" class="data-check" value="'.sha1($item->id).'">';
            $row[] = $no;
            $row[] = $item->name;
            $row[] = $item->major;
 
            //add html for action
            $row[] = '<div class="btn-group">
                        <a class="btn btn-sm btn-warning" href="'.site_url('classroom/edit/'.sha1($item->id)).'"><i class="fa fa-edit"></i></a>
                        <a class="btn btn-sm btn-danger" href="javascript:void(0)" onclick="delete_data('."'".sha1($item->id)."'".')"><i class="fa fa-times"></i></a>
                      </div>';
 
            $data[] = $row;
        }
 
        $output = [
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Classroom_model->count_all(),
            "recordsFiltered" => $this->Classroom_model->count_filtered(),
            "data" => $data,
        ];
        //output to json format
        echo json_encode($output);
    }

	public function add()
	{
        $this->form_validation->set_rules('name', 'Nama Kelas', 'trim|required');
		$this->form_validation->set_rules('id_major', 'Jurusan', 'trim|required');
		
		if ($this->form_validation->run()) {
			$data = [
                'name'      => $this->input->post('name'),
				'id_major'  => $this->input->post('id_major')
			];
			$save = $this->Classroom_model->save($data);
			$this->session->set_flashdata('success', 'Data telah ditambahkan.');
			redirect('classroom','refresh');
		} else {
            $data['title']      = 'Tambah Kelas';
			$data['major']		= $this->Major_model->get_all();
			$data['content']	= 'classroom/add';
			$this->load->view('layouts/wrapper', $data);
		}
	}

	public function edit($id)
	{
		$data['classroom'] = $this->Classroom_model->get_by_id($id);

        $this->form_validation->set_rules('name', 'Nama Kelas', 'trim|required');
        $this->form_validation->set_rules('id_major', 'Jurusan', 'trim|required');
        
        if ($this->form_validation->run()) {
            $data = [
                'name'      => $this->input->post('name'),
                'id_major'  => $this->input->post('id_major')
            ];
			$update = $this->Classroom_model->update($id,$data);
            if($update){
                $this->session->set_flashdata('success', 'Data telah diubah.');
                redirect('classroom','refresh');
            }else{
                $this->session->set_flashdata('warning', 'Tidak ada data yang diubah.');
                redirect('classroom/edit/'.$id,'refresh');
            }
		} else {
			$data['title']		= 'Ubah Kelas';
            $data['major']      = $this->Major_model->get_all();
			$data['content']	= 'classroom/edit';
			$this->load->view('layouts/wrapper', $data);
		}
	}
 
    public function ajax_delete($id)
    {
        $this->Classroom_model->delete($id);
        echo json_encode(array("status" => TRUE));
    }
 
    public function ajax_bulk_delete()
    {
        $list_id = $this->input->post('id_classroom',TRUE);
        
        foreach ($list_id as $id){
            $this->Classroom_model->delete($id);
        }
        echo json_encode(array("status" => TRUE));
    }

}

/* End of file Classroom.php */
/* Location: ./application/controllers/admin/Classroom.php */