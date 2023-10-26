<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usertype extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		is_login();
        if(!is_superadmin()){
            redirect('dashboard','refresh');
        }
	}

	public function index()
	{
		$data['title']		= 'Grup Pengguna';
		$data['content']	= 'usertype/index';
		$this->load->view('layouts/wrapper', $data);
	}

    public function ajax_list()
    {
        $list = $this->Usertype_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = '<input type="checkbox" class="data-check" value="'.sha1($item->id_usertype).'">';
            $row[] = $no;
            $row[] = $item->usertype_name;
 
            //add html for action
            $row[] = '<div class="btn-group">
                        <a class="btn btn-sm btn-warning" href="'.site_url('usertype/edit/'.sha1($item->id_usertype)).'"><i class="fa fa-edit"></i></a>
                        <a class="btn btn-sm btn-danger" href="javascript:void(0)" onclick="delete_data('."'".sha1($item->id_usertype)."'".')"><i class="fa fa-times"></i></a>
                      </div>';
 
            $data[] = $row;
        }
 
        $output = [
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Usertype_model->count_all(),
            "recordsFiltered" => $this->Usertype_model->count_filtered(),
            "data" => $data,
        ];
        //output to json format
        echo json_encode($output);
    }

	public function add()
	{
		$this->form_validation->set_rules('usertype_name', 'Nama Grup Pengguna', 'trim|required');
		
		if ($this->form_validation->run()) {
			$data = [
				'usertype_name' => $this->input->post('usertype_name')
			];
			$save = $this->Usertype_model->save($data);
			$this->session->set_flashdata('success', 'Data telah ditambahkan.');
			redirect('usertype','refresh');
		} else {
			$data['title']		= 'Tambah Grup Pengguna';
			$data['content']	= 'usertype/add';
			$this->load->view('layouts/wrapper', $data);
		}
	}

	public function edit($id)
	{
		$data['usertype'] = $this->Usertype_model->get_by_id($id);

		$this->form_validation->set_rules('usertype_name', 'Nama Grup Pengguna', 'trim|required');
		
		if ($this->form_validation->run()) {
			$data = [
				'usertype_name' => $this->input->post('usertype_name')
			];
			$update = $this->Usertype_model->update($id,$data);
            if($update){
                $this->session->set_flashdata('success', 'Data telah diubah.');
                redirect('usertype','refresh');
            }else{
                $this->session->set_flashdata('warning', 'Tidak ada data yang diubah.');
                redirect('usertype/edit/'.$id,'refresh');
            }
		} else {
			$data['title']		= 'Ubah Grup Pengguna';
			$data['content']	= 'usertype/edit';
			$this->load->view('layouts/wrapper', $data);
		}
	}
 
    public function ajax_delete($id)
    {
        $data['usertype'] = $this->Usertype_model->get_by_id($id);
        $id_usertype = $data['usertype']->id_usertype;
        $this->Usertype_model->delete($id);
        echo json_encode(array("status" => TRUE));
    }
 
    public function ajax_bulk_delete()
    {
        $list_id = $this->input->post('id_usertype',TRUE);
        
        foreach ($list_id as $id){
	        $data['usertype'] = $this->Usertype_model->get_by_id($id);
	        $id_usertype = $data['usertype']->id_usertype;
            $this->Usertype_model->delete($id);
        }
        echo json_encode(array("status" => TRUE));
    }

}

/* End of file Usertype.php */
/* Location: ./application/controllers/admin/Usertype.php */