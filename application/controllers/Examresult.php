<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'third_party/Spout/Autoloader/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class Examresult extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		is_login();
	}

	public function index()
	{
		$data['title']		= 'Hasil Ujian';
		$data['content']	= 'examresult/index';
		$this->load->view('layouts/wrapper', $data);
	}

	public function ajax_list()
	{
        if(is_teacher()){
            $teacher = $this->Teacher_model->get_by_email($this->session->email);
            $id_teacher = $teacher->id;
            $list = $this->Examresult_model->get_datatables($id_teacher);
        }else{
			$list = $this->Examresult_model->get_datatables(NULL);
        }
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $item) {
			$no++;
			$date = date('Y-m-d', strtotime($item->exam_date_start));
			$time = date('H:i', strtotime($item->exam_date_start));
			$row = array();
			$row[] = $no;
			$row[] = $item->exam;
			$row[] = $item->lesson;
			$row[] = $item->teacher;
			$row[] = $item->noq;
			$row[] = $item->time.' Menit';
			$row[] = date_indo($date).' '.$time;

			//add html for action
			$row[] = '<div class="btn-group">
				        <a class="btn btn-sm btn-info" href="'.site_url('examresult/detail/'.sha1($item->id_exam)).'" title="Detail"><i class="fas fa-search"></i> Lihat Hasil</a>
				        <a class="btn btn-sm btn-warning" href="'.site_url('examresult/summary/'.sha1($item->id_exam)).'" title="Analisis"><i class="fas fa-file"></i> Analisis</a>
					  </div>';

			$data[] = $row;
		}

        if(is_teacher()){
			$output = [
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->Examresult_model->count_all($id_teacher),
				"recordsFiltered" => $this->Examresult_model->count_filtered($id_teacher),
				"data" => $data,
			];
        }else{
			$output = [
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->Examresult_model->count_all(NULL),
				"recordsFiltered" => $this->Examresult_model->count_filtered(NULL),
				"data" => $data,
			];
        }
		//output to json format
		echo json_encode($output);
	}

	public function ajax_list_detail()
	{
		$id = $this->input->post('id', true);
		$list = $this->Examresult_model->get_datatables_detail($id);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $item) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $item->student;
			$row[] = $item->classroom;
			$row[] = $item->major;
			$row[] = $item->total_true;
			$row[] = $item->total_value;

			//add html for action
			$row[] = '<div class="btn-group">
				        <a class="btn btn-sm btn-info" href="'.site_url('examresult/resume/'.sha1($item->id_exam).'/'.sha1($item->id_student)).'" title="Detail"><i class="fas fa-search"></i> Lihat Jawaban</a>
				        <a class="btn btn-sm btn-danger" href="javascript:void(0)" onclick="reset_data('."'".sha1($item->id_exam)."'".','."'".sha1($item->id_student)."'".')" title="Reset"><i class="fas fa-bullseye"></i> Reset Ujian</a>
					  </div>';

			$data[] = $row;
		}

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->Examresult_model->count_all_detail($id),
			"recordsFiltered" => $this->Examresult_model->count_filtered_detail($id),
			"data" => $data,
		];
		//output to json format
		echo json_encode($output);
	}

	public function detail($id)
	{
		$data['title']		= 'Detail Hasil Ujian';
		$data['exam']		= $this->Examresult_model->get_by_id($id);
		$data['content']	= 'examresult/detail';
		$this->load->view('layouts/wrapper', $data);
	}

 	private function print($data)
 	{
		echo "<pre>";
		print_r($data);
		echo "</pre>";
 	}

 	public function summary($id_exam)
 	{
		$data['title']      = 'Analisis Hasil Ujian';
		$data['examresult'] = $this->Examresult_model->get_exam_summary($id_exam);
		$data['content']    = 'examresult/summary';
		$this->load->view('layouts/wrapper', $data);
 	}

 	public function resume($id_exam, $id_student)
 	{
		$examresume = $this->Examresult_model->get_exam_resume($id_exam, $id_student);
		// echo "<pre>";print_r($examresume);die;
		$data['title']      = 'Detail Jawaban Ujian';
		$data['examresume'] = $examresume;
		$data['content']    = 'examresult/resume';
		$this->load->view('layouts/wrapper', $data);
 	}

 	public function reset($id_exam, $id_student)
 	{
 		$this->Examresult_model->delete($id_exam, $id_student);
 		echo json_encode(array("status" => TRUE));
 	}

	public function export_summary($id_exam)
	{
		$index = 0;
		foreach(range('C','Z') as $letter){
		    $alpha_arr[$index] = $letter;
		    $index++;
		}
		
		$examresult = $this->Examresult_model->get_exam_summary($id_exam);

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'No');
		$sheet->setCellValue('B1', 'Nama Siswa');
		$sheet->setCellValue('C1', 'Soal');
		for($i=0; $i<$examresult[0]->noq; $i++){
			$sheet->setCellValue($alpha_arr[$i].'2', ($i+1));
		}
		$sheet->setCellValue($alpha_arr[$examresult[0]->noq].'1', 'Nilai');

		$sheet->getStyle('A1:'.$alpha_arr[$examresult[0]->noq].'1')->getFont()->setBold(true);
		$sheet->getStyle('A2:'.$alpha_arr[$examresult[0]->noq].'2')->getFont()->setBold(true);
		$sheet->getStyle('A1:A2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
		$sheet->getStyle('B1:B2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
		$sheet->getStyle('C1:N1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle($alpha_arr[$examresult[0]->noq].'1:'.$alpha_arr[$examresult[0]->noq].'2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
		$sheet->mergeCells('A1:A2');
		$sheet->mergeCells('B1:B2');
		$sheet->mergeCells('C1:'.$alpha_arr[$examresult[0]->noq-1].'1');
		$sheet->mergeCells($alpha_arr[$examresult[0]->noq].'1:'.$alpha_arr[$examresult[0]->noq].'2');

		$no = 1;
		$row = 3;

		foreach($examresult as $data){
			$answer_arr  = explode(',', $data->list_answer);

			$sheet->setCellValue('A'.$row, $no++);
			$sheet->setCellValue('B'.$row, $data->student);
			foreach($answer_arr as $key => $value){
				$question_exp = explode(':', $value);
				$id_question  = $question_exp[0];
				$question     = $this->Question_model->get_by_id(sha1($id_question));
				if($question->answer == $question_exp[1]){
					$sheet->setCellValue($alpha_arr[$key].$row, 1);
				}else{
					$sheet->setCellValue($alpha_arr[$key].$row, 0);
				}
			}
			$sheet->setCellValue($alpha_arr[$examresult[0]->noq].$row, $data->total_value);
			$row++;
		}

		$sheet->getColumnDimension('B')->setAutoSize(true);

		$writer = new Xlsx($spreadsheet);
		$filename = 'Analisis-Hasil-Ujian-'.time();

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');

		$writer->save('php://output');
	}

}

/* End of file Examresult.php */
/* Location: ./application/controllers/Examresult.php */