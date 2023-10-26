<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		is_login();
	}

	public function index()
	{
		$teacher = $this->Teacher_model->get_by_email($this->session->email);

		$data['title']	 = 'Beranda';
		$data['student'] = $this->Student_model->get_by_email($this->session->email);
		if(is_superadmin() || is_admin()){
			$data['total_student'] 	= $this->Student_model->count_all();
			$data['total_teacher'] 	= $this->Teacher_model->count_all();
			$data['total_major'] 	= $this->Major_model->count_all();
			$data['total_class'] 	= $this->Classroom_model->count_all();
		}
		if(is_teacher()){
			$data['total_student'] 	= $this->Student_model->count_all();
			$data['total_lesson'] 	= $this->Lessonteacher_model->get_total_lesson_by_teacher($teacher->id);
		}
		$data['total_student_by_major']	 = $this->Student_model->get_total_student_by_major();
		$data['total_student_by_gender'] = $this->Student_model->get_total_student_by_gender();
		$data['content']				 = 'dashboard/index';
		$this->load->view('layouts/wrapper', $data);
	}

	public function profile()
	{
		$id = sha1($this->session->id_user);
		$data['user'] = $this->User_model->get_by_id($id);

		$this->form_validation->set_rules('name', 'Nama Lengkap', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('phone', 'Telepon', 'trim|required');

		if ($this->form_validation->run()) {
			// Jika user upload photo
			if (!empty($_FILES['photo']['name'])) {
				// Rename nama photo yang diupload
				$filename = strtolower(url_title($this->input->post('username'))).'-'.date('YmdHis');
				// Konfigurasi photo
				$config['upload_path'] 	= './uploads/user/';
				$config['allowed_types']= 'gif|jpg|jpeg|png';
				$config['max_size']  	= '1024';
				$config['file_name']  	= $filename;
				
				$this->load->library('upload', $config);

				// Jika foto diganti, hapus foto lamanya
				$dir 		= './uploads/user/'.$data['user']->photo;
				$dir_thumbs	= './uploads/user/thumbs/'.$data['user']->photo;

				if (is_file($dir)) {
					unlink($dir);
					unlink($dir_thumbs);
				}
				
				if (!$this->upload->do_upload('photo')){
					$error = $this->upload->display_errors();
					$this->session->set_flashdata('danger', $error);
					redirect('profile','refresh');
				} else {
					$config['image_library'] 	= 'gd2';
					$config['source_image'] 	= './uploads/user/'.$this->upload->data('file_name');
					$config['new_image'] 		= './uploads/user/thumbs/';
					$config['create_thumb'] 	= TRUE;
					$config['maintain_ratio'] 	= FALSE;
					$config['width']         	= 250;
					$config['height']       	= 250;
					$config['thumb_marker']    	= '';

					$this->load->library('image_lib', $config);

					$this->image_lib->resize();

					$id_user = $this->input->post('id_user');
					$data = [
						'name' 			=> $this->input->post('name'),
						'email' 		=> $this->input->post('email'),
						'phone' 		=> $this->input->post('phone'),
						'photo' 		=> $this->upload->data('file_name')
					];
					$update = $this->User_model->update($id_user,$data);
					if($update){
						$this->session->set_flashdata('success', 'Profil telah diperbaharui.');
						redirect('profile','refresh');
					}else{
						$this->session->set_flashdata('warning', 'Tidak ada data yang diperbaharui.');
						redirect('profile','refresh');
					}
				}
			} else {
				// Jika user tidak upload photo
				$id_user = $this->input->post('id_user');
				$data = [
					'name' 			=> $this->input->post('name'),
					'email' 		=> $this->input->post('email'),
					'phone' 		=> $this->input->post('phone')
				];
				$update = $this->User_model->update($id_user,$data);
				if($update){
					$this->session->set_flashdata('success', 'Profil telah diperbaharui.');
					redirect('profile','refresh');
				}else{
					$this->session->set_flashdata('warning', 'Tidak ada data yang diperbaharui.');
					redirect('profile','refresh');
				}
			}
		} else {
			if ($data['user']) {
				$data['title']		= 'Profil';
				$data['content']	= 'dashboard/profile';
				$this->load->view('layouts/wrapper', $data);
			} else {
				$this->session->set_flashdata('danger', 'Maaf, data tidak ditemukan.');
				redirect('dashboard','refresh');
			}
		}
	}

	public function setting()
	{
		$setting = $this->db->get_where('tbl_setting', ['id'=>1])->row();
		
		$this->form_validation->set_rules('name', 'Nama Aplikasi', 'trim|required');
		$this->form_validation->set_rules('name_school', 'Nama Sekolah', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('phone', 'Telepon', 'trim|required');
		$this->form_validation->set_rules('address', 'Alamat', 'trim|required');
		$this->form_validation->set_rules('exam_rules', 'Peraturan Ujian', 'trim|required');

		if ($this->form_validation->run()) {
			// Jika user upload logo
			if (!empty($_FILES['logo']['name'])) {
				// Rename nama logo yang diupload
				$filename = 'logo-'.date('YmdHis');
				// Konfigurasi logo
				$config['upload_path'] 	= './uploads/setting/';
				$config['allowed_types']= 'gif|jpg|jpeg|png';
				$config['max_size']  	= '1024';
				$config['file_name']  	= $filename;
				
				$this->load->library('upload', $config);

				// Jika foto diganti, hapus foto lamanya
				$dir = './uploads/setting/'.$setting->logo;

				if (is_file($dir)) {
					unlink($dir);
				}
				
				if (!$this->upload->do_upload('logo')){
					$error = $this->upload->display_errors();
					$this->session->set_flashdata('danger', $error);
					redirect('setting','refresh');
				} else {
					$data = [
						'name' 			=> $this->input->post('name'),
						'name_school' 	=> $this->input->post('name_school'),
						'email' 		=> $this->input->post('email'),
						'phone' 		=> $this->input->post('phone'),
						'address' 		=> $this->input->post('address'),
						'logo' 			=> $this->upload->data('file_name'),
						'exam_rules'	=> $this->input->post('exam_rules')
					];
					$update = $this->db->update('tbl_setting', $data, ['id'=>1]);
					if($update){
						$this->session->set_flashdata('success', 'Pengaturan telah diperbaharui.');
						redirect('setting','refresh');
					}else{
						$this->session->set_flashdata('warning', 'Tidak ada data yang diperbaharui.');
						redirect('setting','refresh');
					}
				}
			} else {
				// Jika user tidak upload logo
				$id_user = $this->input->post('id_user');
				$data = [
					'name' 			=> $this->input->post('name'),
					'name_school' 	=> $this->input->post('name_school'),
					'email' 		=> $this->input->post('email'),
					'phone' 		=> $this->input->post('phone'),
					'address' 		=> $this->input->post('address'),
					'exam_rules'	=> $this->input->post('exam_rules')
				];
				$update = $this->db->update('tbl_setting', $data, ['id'=>1]);
				if($update){
					$this->session->set_flashdata('success', 'Pengaturan telah diperbaharui.');
					redirect('setting','refresh');
				}else{
					$this->session->set_flashdata('warning', 'Tidak ada data yang diperbaharui.');
					redirect('setting','refresh');
				}
			}
		} else {
			$data['title']		= 'Pengaturan Aplikasi';
			$data['content']	= 'dashboard/setting';
			$this->load->view('layouts/wrapper', $data);
		}
	}

}

/* End of file Dashboard.php */
/* Location: ./application/controllers/admin/Dashboard.php */