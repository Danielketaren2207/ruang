<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'third_party/Spout/Autoloader/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class Student extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		is_login();
	}

	public function index()
	{
		$data['title']		= 'Siswa';
		$data['content']	= 'student/index';
		$this->load->view('layouts/wrapper', $data);
	}

	public function ajax_list()
	{
		$list = $this->Student_model->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $item) {
			$no++;
			$checkbox = '<input type="checkbox" class="data-check" value="'.sha1($item->id).'">';
			$row = array();
			$row[] = $checkbox;
			$row[] = $no;
			$row[] = $item->name;
			$row[] = $item->nis;
			$row[] = $item->email;
			$row[] = $item->phone;
			$row[] = $item->classroom;
			$row[] = $item->major;

			//add html for action
			if($item->is_user == 1){
				$row[] = '<div class="btn-group">
					        <a class="btn btn-sm btn-warning" href="'.site_url('student/edit/'.sha1($item->id)).'" title="Ubah"><i class="fa fa-edit"></i></a>
							<a class="btn btn-sm btn-danger" href="javascript:void(0)" onclick="delete_data('."'".sha1($item->id)."'".')" title="Hapus"><i class="fa fa-times"></i></a>
						  </div>';
			}else{
				$row[] = '<div class="btn-group">
					        <a class="btn btn-sm btn-warning" href="'.site_url('student/edit/'.sha1($item->id)).'" title="Ubah"><i class="fa fa-edit"></i></a>
							<a class="btn btn-sm btn-info" href="javascript:void(0)" onclick="activated('."'".sha1($item->id)."'".','."'".$item->name."'".')" title="Aktifkan Akun"><i class="fa fa-user-plus"></i></a>
							<a class="btn btn-sm btn-danger" href="javascript:void(0)" onclick="delete_data('."'".sha1($item->id)."'".')" title="Hapus"><i class="fa fa-times"></i></a>
						  </div>';
			}

			$data[] = $row;
		}

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->Student_model->count_all(),
			"recordsFiltered" => $this->Student_model->count_filtered(),
			"data" => $data,
		];
		//output to json format
		echo json_encode($output);
	}

	public function add()
	{
		$this->form_validation->set_rules('name', 'Nama Siswa', 'trim|required');
		$this->form_validation->set_rules('nis', 'NIP', 'trim|required|numeric');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('phone', 'Telepon', 'trim|required');
		$this->form_validation->set_rules('id_classroom', 'Kelas', 'trim|required');
		$this->form_validation->set_rules('id_major', 'Jurusan', 'trim|required');
		
		if ($this->form_validation->run()) {
			$data = [
				'name'			=> $this->input->post('name'),
				'nis'			=> $this->input->post('nis'),
				'email'			=> $this->input->post('email'),
				'phone'			=> $this->input->post('phone'),
				'id_classroom'	=> $this->input->post('id_classroom'),
				'id_major'		=> $this->input->post('id_major'),
				'created_at'	=> date('Y-m-d H:i:s'),
				'created_by'	=> $this->session->username
			];
			$save = $this->Student_model->save($data);
			$this->session->set_flashdata('success', 'Data telah ditambahkan.');
			redirect('student','refresh');
		} else {
			$data['title']		= 'Tambah Siswa';
			$data['classroom']	= $this->Classroom_model->get_all();
			$data['major']		= $this->Major_model->get_all();
			$data['content']	= 'student/add';
			$this->load->view('layouts/wrapper', $data);
		}
	}

	public function edit($id)
	{
		$data['student'] = $this->Student_model->get_by_id($id);

		$this->form_validation->set_rules('name', 'Nama Siswa', 'trim|required');
		$this->form_validation->set_rules('nis', 'NIP', 'trim|required|numeric');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('phone', 'Telepon', 'trim|required');
		$this->form_validation->set_rules('id_classroom', 'Kelas', 'trim|required');
		$this->form_validation->set_rules('id_major', 'Jurusan', 'trim|required');
		
		if ($this->form_validation->run()) {
			$data = [
				'name'			=> $this->input->post('name'),
				'nis'			=> $this->input->post('nis'),
				'email'			=> $this->input->post('email'),
				'phone'			=> $this->input->post('phone'),
				'id_classroom'	=> $this->input->post('id_classroom'),
				'id_major'		=> $this->input->post('id_major'),
				'created_at'	=> date('Y-m-d H:i:s'),
				'created_by'	=> $this->session->username
			];
			$update = $this->Student_model->update($id,$data);
			if($update){
				$this->session->set_flashdata('success', 'Data telah diubah.');
				redirect('student','refresh');
			}else{
				$this->session->set_flashdata('warning', 'Tidak ada data yang diubah.');
				redirect('student/edit/'.$id,'refresh');
			}
		} else {
			$data['title']		= 'Ubah Siswa';
			$data['classroom']	= $this->Classroom_model->get_all();
			$data['major']		= $this->Major_model->get_all();
			$data['content']	= 'student/edit';
			$this->load->view('layouts/wrapper', $data);
		}
	}
 
	public function activated($id)
	{
		$student = $this->Student_model->get_by_id($id);

		$data = [
			'name'			=> $student->name,
			'email'			=> $student->email,
			'username'		=> $student->nis,
			'password'		=> password_hash($student->nis, PASSWORD_DEFAULT),
			'phone'			=> $student->phone,
			'usertype_id'	=> 4,
			'is_active'		=> 1
		];
		$save = $this->User_model->save($data);
		if($save){
			$data2 = ['is_user' => 1];
			$update = $this->Student_model->update($id,$data2);
			if($update){
				echo json_encode(array("status" => TRUE));
			}
		}
	}
 
	public function ajax_delete($id)
	{
		$this->Student_model->delete($id);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_bulk_delete()
	{
		$list_id = $this->input->post('id_student',TRUE);
		foreach ($list_id as $id){
			$this->Student_model->delete($id);
		}
		echo json_encode(array("status" => TRUE));
	}

    public function download_format()
    {
        force_download('downloads/format/format-student.xlsx',NULL);
    }

	public function import_excel()
	{
		$config['upload_path']  = './uploads/';
		$config['allowed_types']= 'xls|xlsx';
		$config['file_name']    = 'doc-student-excel-'.time();

		$this->load->library('upload', $config);

		if ( $this->upload->do_upload('import') ){
			$file = $this->upload->data();
			$reader = ReaderEntityFactory::createXLSXReader();
			$reader->open('uploads/'.$file['file_name']);
			foreach($reader->getSheetIterator() as $sheet) {
				$numRow = 1;
				foreach($sheet->getRowIterator() as $row) {
					if($numRow > 1) {
						$data = [
							'name'			=> $row->getCellAtIndex(1),
							'nis'			=> $row->getCellAtIndex(2),
							'email'			=> $row->getCellAtIndex(3),
							'phone'			=> $row->getCellAtIndex(4),
							'id_classroom'	=> $row->getCellAtIndex(5),
							'id_major'		=> $row->getCellAtIndex(6),
							'created_at'	=> date('Y-m-d H:i:s'),
							'created_by'	=> $this->session->username
						];
						$this->Student_model->import($data);
					}
					$numRow++;
				}
			}
			$reader->close();
			unlink('uploads/'.$file['file_name']);
			echo json_encode(array("status" => TRUE));
		}else{
			echo 'Error :'.$this->upload->display_errors();
		}
	}

	public function export_excel()
	{
		$student = $this->Student_model->get_all();

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'No');
		$sheet->setCellValue('B1', 'Name');
		$sheet->setCellValue('C1', 'NIP');
		$sheet->setCellValue('D1', 'Email');
		$sheet->setCellValue('E1', 'Phone');
		$sheet->setCellValue('F1', 'Kelas');
		$sheet->setCellValue('G1', 'Jurusan');

		$no = 1;
		$row = 2;

		foreach($student as $data){
			$sheet->setCellValue('A'.$row, $no++);
			$sheet->setCellValue('B'.$row, $data->name);
			$sheet->setCellValue('C'.$row, $data->nis);
			$sheet->setCellValue('D'.$row, $data->email);
			$sheet->setCellValue('E'.$row, $data->phone);
			$sheet->setCellValue('F'.$row, $data->classroom);
			$sheet->setCellValue('G'.$row, $data->major);
			$row++;
		}
		$writer = new Xlsx($spreadsheet);
		$filename = 'Student-'.time();

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');

		$writer->save('php://output');
	}

	public function print($data)
	{
		echo '<pre>';
		print_r($data);
		echo '</pre>';
	}

}

/* End of file Student.php */
/* Location: ./application/controllers/Student.php */