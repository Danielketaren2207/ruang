<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'third_party/Spout/Autoloader/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class Question extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		is_login();
	}

	public function index()
	{
		$this->form_validation->set_rules('id_teacher', 'Guru', 'trim');
		$this->form_validation->set_rules('id_lesson', 'Mata Pelajaran', 'trim');

		if ($this->form_validation->run()) {
			$id_teacher = $this->input->post('id_teacher', TRUE);
			$id_lesson  = $this->input->post('id_lesson', TRUE);

			$question = $this->Question_model->get_all($id_teacher,$id_lesson);

			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();
			$sheet->setCellValue('A1', 'No');
			$sheet->setCellValue('B1', 'Guru');
			$sheet->setCellValue('C1', 'Mata Pelajaran');
			$sheet->setCellValue('D1', 'Soal/Pertanyaan');
			$sheet->setCellValue('E1', 'Pilihan A');
			$sheet->setCellValue('F1', 'Pilihan B');
			$sheet->setCellValue('G1', 'Pilihan C');
			$sheet->setCellValue('H1', 'Pilihan D');
			$sheet->setCellValue('I1', 'Pilihan E');
			$sheet->setCellValue('J1', 'Kunci Jawaban');
			$sheet->setCellValue('K1', 'Bobot Nilai');

			$no = 1;
			$row = 2;

			foreach($question as $data){
				$sheet->setCellValue('A'.$row, $no++);
				$sheet->setCellValue('B'.$row, strip_tags($data->teacher));
				$sheet->setCellValue('C'.$row, strip_tags($data->lesson));
				$sheet->setCellValue('D'.$row, strip_tags($data->question));
				$sheet->setCellValue('E'.$row, strip_tags($data->option_a));
				$sheet->setCellValue('F'.$row, strip_tags($data->option_b));
				$sheet->setCellValue('G'.$row, strip_tags($data->option_c));
				$sheet->setCellValue('H'.$row, strip_tags($data->option_d));
				$sheet->setCellValue('I'.$row, strip_tags($data->option_e));
				$sheet->setCellValue('J'.$row, strip_tags($data->answer));
				$sheet->setCellValue('K'.$row, strip_tags($data->value));
				$row++;
			}
			$writer = new Xlsx($spreadsheet);
			$filename = 'Question-'.time();

			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
			header('Cache-Control: max-age=0');

			$writer->save('php://output');
		} else {
			$data['title']		= 'Soal';
			$data['teacher']	= $this->Teacher_model->get_all();
			$data['lesson']		= $this->Lesson_model->get_all();
			if(is_teacher()){
				$teacher = $this->Teacher_model->get_by_email($this->session->email);
				$id_teacher = $teacher->id;
				$data['lesson']	= $this->Question_model->get_lesson_by_teacher($id_teacher);
				$data['teacher']= $this->Teacher_model->get_by_email($this->session->email);
			}
			$data['content']	= 'question/index';
			$this->load->view('layouts/wrapper', $data);
		}
	}

	public function ajax_list()
	{
		if(is_teacher()){
			$teacher = $this->Teacher_model->get_by_email($this->session->email);
			$id_teacher = $teacher->id;
			$list = $this->Question_model->get_datatables($id_teacher);
		}else{
			$list = $this->Question_model->get_datatables();
		}
		
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $item) {
			$no++;
			$checkbox = '<input type="checkbox" class="data-check" value="'.sha1($item->id).'">';
			$row = array();
			$row[] = $checkbox;
			$row[] = $no;
			$row[] = $item->teacher;
			$row[] = $item->lesson;
			$row[] = $item->question;

			//add html for action
			$row[] = '<div class="btn-group">
				        <a class="btn btn-sm btn-warning" href="'.site_url('question/edit/'.sha1($item->id)).'" title="Ubah"><i class="fa fa-edit"></i></a>
				        <a class="btn btn-sm btn-info" href="'.site_url('question/detail/'.sha1($item->id)).'"  title="Detail"><i class="fa fa-search"></i></a>
						<a class="btn btn-sm btn-danger" href="javascript:void(0)" onclick="delete_data('."'".sha1($item->id)."'".')" title="Hapus"><i class="fa fa-times"></i></a>
					  </div>';

			$data[] = $row;
		}

		if(is_teacher()){
			$output = [
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->Question_model->count_all($id_teacher),
				"recordsFiltered" => $this->Question_model->count_filtered($id_teacher),
				"data" => $data,
			];
		}else{
			$output = [
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->Question_model->count_all(),
				"recordsFiltered" => $this->Question_model->count_filtered(),
				"data" => $data,
			];
		}
		//output to json format
		echo json_encode($output);
	}

	public function get_lesson_by_teacher()
	{
		$id_teacher = $this->input->post('id_teacher',true);
		$data = $this->Question_model->get_lesson_by_teacher($id_teacher);
		echo json_encode($data);
	}

	public function add()
	{
		if(is_teacher()){
			$teacher = $this->Teacher_model->get_by_email($this->session->email);
			$id_teacher = $teacher->id;
			$this->form_validation->set_rules('id_teacher', 'Guru', 'trim');
		}else{
			$this->form_validation->set_rules('id_teacher', 'Guru', 'trim|required');
		}
		
		$this->form_validation->set_rules('id_lesson', 'Mata Pelajaran', 'trim|required');
		$this->form_validation->set_rules('question', 'Soal', 'trim|required');
		$this->form_validation->set_rules('option_a', 'Jawaban A', 'trim|required');
		$this->form_validation->set_rules('option_b', 'Jawaban B', 'trim|required');
		$this->form_validation->set_rules('option_c', 'Jawaban C', 'trim|required');
		$this->form_validation->set_rules('option_d', 'Jawaban D', 'trim|required');
		$this->form_validation->set_rules('option_e', 'Jawaban E', 'trim|required');
		$this->form_validation->set_rules('answer', 'Kunci Jawaban', 'trim|required');
		$this->form_validation->set_rules('value', 'Bobot Nilai', 'trim|required');

		$option = [
			'a' => 'A',
			'b' => 'B',
			'c' => 'C',
			'd' => 'D',
			'e' => 'E'
		];
		
		if ($this->form_validation->run()) {
			$data = [
				'id_teacher'	=> $this->input->post('id_teacher'),
				'id_lesson'		=> $this->input->post('id_lesson'),
				'question'		=> $this->input->post('question'),
				'option_a'		=> $this->input->post('option_a'),
				'option_b'		=> $this->input->post('option_b'),
				'option_c'		=> $this->input->post('option_c'),
				'option_d'		=> $this->input->post('option_d'),
				'option_e'		=> $this->input->post('option_e'),
				'answer'		=> $this->input->post('answer'),
				'value'			=> $this->input->post('value'),
				'created_at'	=> date('Y-m-d H:i:s'),
				'created_by'	=> $this->session->username
			];
			// $this->print($data);
			$save = $this->Question_model->save($data);
			$this->session->set_flashdata('success', 'Data telah ditambahkan.');
			redirect('question','refresh');
		} else {
			$data['title']		= 'Tambah Soal';
			$data['option']		= $option;
			$data['teacher']	= $this->Teacher_model->get_all();
			if(is_teacher()){
				$data['lesson']	= $this->Question_model->get_lesson_by_teacher($id_teacher);
				$data['teacher']= $this->Teacher_model->get_by_email($this->session->email);
			}
			$data['content']	= 'question/add';
			$this->load->view('layouts/wrapper', $data);
		}
	}

	public function edit($id)
	{
		$data['question'] = $this->Question_model->get_by_id($id);
		$id_teacher = $data['question']->id_teacher;

		$this->form_validation->set_rules('question', 'Soal', 'trim|required');
		$this->form_validation->set_rules('option_a', 'Jawaban A', 'trim|required');
		$this->form_validation->set_rules('option_b', 'Jawaban B', 'trim|required');
		$this->form_validation->set_rules('option_c', 'Jawaban C', 'trim|required');
		$this->form_validation->set_rules('option_d', 'Jawaban D', 'trim|required');
		$this->form_validation->set_rules('option_e', 'Jawaban E', 'trim|required');
		$this->form_validation->set_rules('answer', 'Kunci Jawaban', 'trim|required');
		$this->form_validation->set_rules('value', 'Bobot Nilai', 'trim|required');

		$option = [
			'a' => 'A',
			'b' => 'B',
			'c' => 'C',
			'd' => 'D',
			'e' => 'E'
		];
		
		if ($this->form_validation->run()) {
			$data = [
				'id_lesson'		=> $this->input->post('id_lesson'),
				'question'		=> $this->input->post('question'),
				'option_a'		=> $this->input->post('option_a'),
				'option_b'		=> $this->input->post('option_b'),
				'option_c'		=> $this->input->post('option_c'),
				'option_d'		=> $this->input->post('option_d'),
				'option_e'		=> $this->input->post('option_e'),
				'answer'		=> $this->input->post('answer'),
				'value'			=> $this->input->post('value'),
				'updated_at'	=> date('Y-m-d H:i:s'),
				'updated_by'	=> $this->session->username
			];
			// $this->print($data);
			$update = $this->Question_model->update($id, $data);
			if($update){
				$this->session->set_flashdata('success', 'Data telah diubah.');
				redirect('question','refresh');
			}else{
				$this->session->set_flashdata('warning', 'Tidak ada data yang diubah.');
				redirect('question/edit/'.$id,'refresh');
			}
		} else {
			$data['title']		= 'Ubah Soal';
			$data['option']		= $option;
			$data['teacher']	= $this->Teacher_model->get_all();
			if(is_teacher()){
				$data['lesson']	= $this->Question_model->get_lesson_by_teacher($id_teacher);
			}
			$data['content']	= 'question/edit';
			$this->load->view('layouts/wrapper', $data);
		}
	}

	public function detail($id){
		$data['title']		= 'Detail Soal';
		$data['question'] = $this->Question_model->get_by_id($id);
		$data['content']	= 'question/detail';
		$this->load->view('layouts/wrapper', $data);
	}
 
	public function ajax_delete($id)
	{
		$this->Question_model->delete($id);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_bulk_delete()
	{
		$list_id = $this->input->post('id_question',TRUE);
		foreach ($list_id as $id){
			$this->Question_model->delete($id);
		}
		echo json_encode(array("status" => TRUE));
	}

    public function download_format()
    {
        force_download('downloads/format/format-question.xlsx',NULL);
    }

    public function import_excel()
    {
        $config['upload_path']  = './uploads/';
        $config['allowed_types']= 'xls|xlsx';
        $config['file_name']    = 'doc-question-excel-'.time();
        
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
                            'id_teacher'	=> $row->getCellAtIndex(1),
                            'id_lesson'		=> $row->getCellAtIndex(2),
                            'question'		=> '<p>'.$row->getCellAtIndex(3).'</p>',
                            'option_a'		=> '<p>'.$row->getCellAtIndex(4).'</p>',
                            'option_b'		=> '<p>'.$row->getCellAtIndex(5).'</p>',
                            'option_c'		=> '<p>'.$row->getCellAtIndex(6).'</p>',
                            'option_d'		=> '<p>'.$row->getCellAtIndex(7).'</p>',
                            'option_e'		=> '<p>'.$row->getCellAtIndex(8).'</p>',
                            'answer'		=> strtoupper($row->getCellAtIndex(9)),
                            'value'			=> $row->getCellAtIndex(10),
                            'created_at'	=> date('Y-m-d H:i:s'),
                            'created_by'	=> $this->session->username
                        ];
                        $import = $this->Question_model->import($data);
                    }
                    $numRow++;
                }
                $reader->close();
                unlink('uploads/'.$file['file_name']);
                echo json_encode(array("status" => TRUE));
            }
        }
        else{
            echo 'Error :'.$this->upload->display_errors();
        }
    }

	public function print($data)
	{
		echo '<pre>';
		print_r($data);
		echo '</pre>';
	}

}

/* End of file Question.php */
/* Location: ./application/controllers/Question.php */