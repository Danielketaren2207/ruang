<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Major extends CI_Controller {

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
		$data['title']		= 'Jurusan';
		$data['content']	= 'major/index';
		$this->load->view('layouts/wrapper', $data);
	}

    public function ajax_list()
    {
        $list = $this->Major_model->get_datatables();
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
                        <a class="btn btn-sm btn-warning" href="'.site_url('major/edit/'.sha1($item->id)).'"><i class="fa fa-edit"></i></a>
                        <a class="btn btn-sm btn-danger" href="javascript:void(0)" onclick="delete_data('."'".sha1($item->id)."'".')"><i class="fa fa-times"></i></a>
                      </div>';
 
            $data[] = $row;
        }
 
        $output = [
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Major_model->count_all(),
            "recordsFiltered" => $this->Major_model->count_filtered(),
            "data" => $data,
        ];
        //output to json format
        echo json_encode($output);
    }

	public function add()
	{
		$this->form_validation->set_rules('name', 'Nama Jurusan', 'trim|required');
		
		if ($this->form_validation->run()) {
			$data = [
				'name' => $this->input->post('name')
			];
			$save = $this->Major_model->save($data);
			$this->session->set_flashdata('success', 'Data telah ditambahkan.');
			redirect('major','refresh');
		} else {
			$data['title']		= 'Tambah Jurusan';
			$data['content']	= 'major/add';
			$this->load->view('layouts/wrapper', $data);
		}
	}

	public function edit($id)
	{
		$data['major'] = $this->Major_model->get_by_id($id);

		$this->form_validation->set_rules('name', 'Nama Jurusan', 'trim|required');
		
		if ($this->form_validation->run()) {
			$data = [
				'name' => $this->input->post('name')
			];
			$update = $this->Major_model->update($id,$data);
            if($update){
                $this->session->set_flashdata('success', 'Data telah diubah.');
                redirect('major','refresh');
            }else{
                $this->session->set_flashdata('warning', 'Tidak ada data yang diubah.');
                redirect('major/edit/'.$id,'refresh');
            }
		} else {
			$data['title']		= 'Ubah Jurusan';
			$data['content']	= 'major/edit';
			$this->load->view('layouts/wrapper', $data);
		}
	}
 
    public function ajax_delete($id)
    {
        $this->Major_model->delete($id);
        echo json_encode(array("status" => TRUE));
    }
 
    public function ajax_bulk_delete()
    {
        $list_id = $this->input->post('id_major',TRUE);
        
        foreach ($list_id as $id){
            $this->Major_model->delete($id);
        }
        echo json_encode(array("status" => TRUE));
    }

}

/* End of file Major.php */
/* Location: ./application/controllers/admin/Major.php */