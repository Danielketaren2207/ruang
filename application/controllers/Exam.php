<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;

class Exam extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		is_login();
	}

	public function index()
	{
		$data['title']		= 'Ujian';
		$data['content']	= 'exam/index';
		$this->load->view('layouts/wrapper', $data);
	}

    public function ajax_list()
    {
        if(is_student()){
            $student     = $this->Student_model->get_by_email($this->session->email);
            $id_student  = $student->id;
            $id_major    = $student->id_major;
            $list = $this->Exam_model->get_datatables(NULL,$id_major);
        }elseif(is_teacher()){
            $teacher     = $this->Teacher_model->get_by_email($this->session->email);
            $id_teacher  = $teacher->id;
            $list = $this->Exam_model->get_datatables($id_teacher,NULL);
        }else{
            $list = $this->Exam_model->get_datatables(NULL,NULL);
        }
        // $this->print($list);

        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $date_start = date('Y-m-d', strtotime($item->date_start));
            $date_end   = date('Y-m-d', strtotime($item->date_end));
            $no++;
            $row = array();
            if(!is_student()){
                $row[] = '<input type="checkbox" class="data-check" value="'.sha1($item->id).'">';
            }
            $row[] = $no;
            $row[] = $item->name;
            $row[] = $item->lesson;
            $row[] = $item->teacher;
            $row[] = $item->noq;
            $row[] = $item->time.' Menit';
            if(!is_student()){
                $row[] = '<span class="badge badge-info">'.$item->token.'</span>';
            }
            $row[] = date_indo($date_start).' '.date('H:i', strtotime($item->date_start));
            $row[] = date_indo($date_end).' '.date('H:i', strtotime($item->date_end));
 
            //add html for action
            if(is_student()){
                $exam_result = $this->Exam_model->get_exam_result($id_student, sha1($item->id));
                $is_test     = $exam_result->num_rows();
            }
            if(is_student()){
                if($is_test == 1){
                    $row[] = '<div class="btn-group">
                                <a class="btn btn-sm btn-success" target="_blank" href="'.site_url('exam/print/'.sha1($item->id)).'"><i class="fa fa-print"></i> Cetak Hasil</a>
                              </div>';
                }else{
                    $row[] = '<div class="btn-group">
                                <a class="btn btn-sm btn-info" href="'.site_url('exam/token/'.sha1($item->id)).'"><i class="fa fa-file"></i> Ikuti Ujian</a>
                              </div>';
                }
            }else{
                $row[] = '<div class="btn-group">
                            <a class="btn btn-sm btn-warning" href="'.site_url('exam/edit/'.sha1($item->id)).'" title="Ubah"><i class="fa fa-edit"></i></a>
                            <a class="btn btn-sm btn-info" href="javascript:void(0)" onclick="refresh_token('."'".sha1($item->id)."'".')" title="Refresh Token"><i class="fa fa-bullseye"></i></a>
                            <a class="btn btn-sm btn-danger" href="javascript:void(0)" onclick="delete_data('."'".sha1($item->id)."'".')" title="Hapus"><i class="fa fa-times"></i></a>
                          </div>';
            }
 
            $data[] = $row;
        }
 
        if(is_student()){
            $output = [
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->Exam_model->count_all(NULL,$id_major),
                "recordsFiltered" => $this->Exam_model->count_filtered(NULL,$id_major),
                "data" => $data,
            ];
        }elseif(is_teacher()){
            $output = [
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->Exam_model->count_all($id_teacher,NULL),
                "recordsFiltered" => $this->Exam_model->count_filtered($id_teacher,NULL),
                "data" => $data,
            ];
        }else{
            $output = [
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->Exam_model->count_all(NULL,NULL),
                "recordsFiltered" => $this->Exam_model->count_filtered(NULL,NULL),
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

    public function check_max_question()
    {
        $id_teacher = $this->input->post('id_teacher',true);
        $id_lesson  = $this->input->post('id_lesson',true);
        $data = count($this->Question_model->get_all($id_teacher, $id_lesson));
        echo json_encode($data);
    }

	public function add()
	{
        if(is_student()){
            redirect('dashboard','refresh');
        }
        if(is_teacher()){
            $teacher = $this->Teacher_model->get_by_email($this->session->email);
            $id_teacher = $teacher->id;
            $this->form_validation->set_rules('id_teacher', 'Guru', 'trim');
        }else{
            $this->form_validation->set_rules('id_teacher', 'Guru', 'trim|required');
        }
        
        $this->form_validation->set_rules('id_lesson', 'Mata Pelajaran', 'trim|required');
        $this->form_validation->set_rules('name', 'Nama Ujian', 'trim|required');
        $this->form_validation->set_rules('noq', 'Jumlah Soal', 'trim|required|numeric');
        $this->form_validation->set_rules('time', 'Waktu (Menit)', 'trim|required|numeric');
        $this->form_validation->set_rules('date_start', 'Tanggal Mulai', 'trim|required');
        $this->form_validation->set_rules('time_start', 'Waktu Mulai', 'trim|required');
        $this->form_validation->set_rules('date_end', 'Tanggal Selesai', 'trim|required');
        $this->form_validation->set_rules('time_end', 'Waktu Selesai', 'trim|required');
		$this->form_validation->set_rules('type', 'Jenis Soal', 'trim|required');
		
		if ($this->form_validation->run()) {
            $token = random_string('alpha', 6);
			$data = [
                'id_teacher'    => $this->input->post('id_teacher'),
                'id_lesson'     => $this->input->post('id_lesson'),
                'name'          => $this->input->post('name'),
                'noq'           => $this->input->post('noq'),
                'time'          => $this->input->post('time'),
				'type'          => $this->input->post('type'),
                'date_start'    => $this->input->post('date_start').' '.$this->input->post('time_start'),
                'date_end'      => $this->input->post('date_end').' '.$this->input->post('time_end'),
                'token'         => strtoupper($token),
                'created_at'    => date('Y-m-d H:i:s'),
                'created_by'    => $this->session->username
			];
			$save = $this->Exam_model->save($data);
			$this->session->set_flashdata('success', 'Data telah ditambahkan.');
			redirect('exam','refresh');
		} else {
            $data['title']      = 'Tambah Ujian';
            if(is_teacher()){
                $data['lesson'] = $this->Question_model->get_lesson_by_teacher($id_teacher);
                $data['teacher']= $this->Teacher_model->get_by_email($this->session->email);
            }else{
                $data['teacher']= $this->Teacher_model->get_all();
            }
			$data['content']    = 'exam/add';
			$this->load->view('layouts/wrapper', $data);
		}
	}

	public function edit($id)
	{
        if(is_student()){
            redirect('dashboard','refresh');
        }

		$data['exam'] = $this->Exam_model->get_by_id($id);

        $this->form_validation->set_rules('name', 'Nama Ujian', 'trim|required');
        $this->form_validation->set_rules('noq', 'Jumlah Soal', 'trim|required|numeric');
        $this->form_validation->set_rules('time', 'Waktu (Menit)', 'trim|required|numeric');
        $this->form_validation->set_rules('date_start', 'Tanggal Mulai', 'trim|required');
        $this->form_validation->set_rules('time_start', 'Waktu Mulai', 'trim|required');
        $this->form_validation->set_rules('date_end', 'Tanggal Selesai', 'trim|required');
        $this->form_validation->set_rules('time_end', 'Waktu Selesai', 'trim|required');
        $this->form_validation->set_rules('type', 'Jenis Soal', 'trim|required');
        
        if ($this->form_validation->run()) {
            $data = [
                'name'          => $this->input->post('name'),
                'noq'           => $this->input->post('noq'),
                'time'          => $this->input->post('time'),
                'type'          => $this->input->post('type'),
                'date_start'    => $this->input->post('date_start').' '.$this->input->post('time_start'),
                'date_end'      => $this->input->post('date_end').' '.$this->input->post('time_end'),
                'updated_at'    => date('Y-m-d H:i:s'),
                'updated_by'    => $this->session->username
            ];
			$update = $this->Exam_model->update($id,$data);
            if($update){
                $this->session->set_flashdata('success', 'Data telah diubah.');
                redirect('exam','refresh');
            }else{
                $this->session->set_flashdata('warning', 'Tidak ada data yang diubah.');
                redirect('exam/edit/'.$id,'refresh');
            }
		} else {
			$data['title']		= 'Ubah Ujian';
            $data['teacher']    = $this->Teacher_model->get_all();
			$data['content']	= 'exam/edit';
			$this->load->view('layouts/wrapper', $data);
		}
	}

    public function refresh_token($id)
    {
        $token = random_string('alpha', 6);
        $data  = ['token' => strtoupper($token)];

        $update = $this->Exam_model->update($id,$data);
        if($update){
            echo json_encode(array("status" => TRUE));
        }
    }
 
    public function ajax_delete($id)
    {
        $this->Exam_model->delete($id);
        echo json_encode(array("status" => TRUE));
    }
 
    public function ajax_bulk_delete()
    {
        $list_id = $this->input->post('id_exam',TRUE);
        
        foreach ($list_id as $id){
            $this->Exam_model->delete($id);
        }
        echo json_encode(array("status" => TRUE));
    }

    // Exam Page
    public function token($id)
    {
        if(!is_student()){
            redirect('dashboard','refresh');
        }

        $data['exam'] = $this->Exam_model->get_by_id($id);

        $data['title']      = 'Token Ujian';
        $data['student']    = $this->Student_model->get_by_email($this->session->email);
        $data['key']        = urlencode($this->encryption->encrypt($id));
        $data['content']    = 'exam/token';
        $this->load->view('layouts/topnav/wrapper', $data);
    }

    public function check_token()
    {
        $token      = $this->input->post('token');
        $id_exam    = $this->input->post('id_exam');

        $exam = $this->Exam_model->get_by_id($id_exam);
        if($token == $exam->token){
            echo json_encode(array('status' => TRUE));
        }else{
            echo json_encode(array('status' => FALSE));
        }
    }

    public function test()
    {
        $key = $this->input->get('key', true);
        $id  = $this->encryption->decrypt(rawurldecode($key));

        $exam = $this->Exam_model->get_by_id($id);
        $id_exam        = $exam->id;
        $question_type  = $exam->type;
        $id_teacher     = $exam->id_teacher;
        $id_lesson      = $exam->id_lesson;
        $noq            = $exam->noq;

        $question       = $this->Exam_model->get_question($question_type, $id_teacher, $id_lesson, $noq);
        $total_question = count($question);
        $student        = $this->Student_model->get_by_email($this->session->email);
        $id_student     = $student->id;
        $exam_result    = $this->Exam_model->get_exam_result($id_student, $id);
        $is_exam_result = $exam_result->num_rows();
        // $this->print($key);

        if($is_exam_result == 0){
            $list_id_question = '';
            $list_aw_question = '';
            $sort_question    = [];
            $i = 0;
            foreach($question as $row){
                $question_to = new stdClass();
                $question_to->id_question   = $row->id;
                $question_to->file          = $row->file;
                $question_to->question      = $row->question;
                $question_to->option_a      = $row->option_a;
                $question_to->option_b      = $row->option_b;
                $question_to->option_c      = $row->option_c;
                $question_to->option_d      = $row->option_d;
                $question_to->option_e      = $row->option_e;
                $question_to->answer        = $row->answer;
                $sort_question[$i]          = $question_to;
                $i++;
                $list_id_question   .= $row->id.',';
                $list_aw_question   .= $row->id.'::N,';
            }
            $sort_question      = $sort_question;
            $list_id_question   = substr($list_id_question, 0, -1);
            $list_aw_question   = substr($list_aw_question, 0, -1);
            $time_end           = date('Y-m-d H:i:s', strtotime("+{$exam->time} minute"));
            $time_start         = date('Y-m-d H:i:s');

            $data = [
                'id_exam'       => $id_exam,
                'id_student'    => $id_student,
                'list_question' => $list_id_question,
                'list_answer'   => $list_aw_question,
                'total_true'    => 0,
                'total_value'   => 0,
                'value'         => 0,
                'date_start'    => $time_start,
                'date_end'      => $time_end,
                'status'        => 1
            ];
            $this->Exam_model->save_result($data);
            redirect('exam/test/?key='.urlencode($key),'refresh',301);
        }

        $detail_test = $exam_result->row();
        if($detail_test->status == 0){
            redirect('dashboard','refresh');
        }

        $exp_list_answer = explode(',', $detail_test->list_answer);
        $sort_question   = [];
        for($i=0; $i<count($exp_list_answer); $i++){
            $exp_answer      = explode(':', $exp_list_answer[$i]);
            $exp_answer2     = empty($exp_answer[1]) ? "''": "'{$exp_answer[1]}'";
            $get_question    = $this->Exam_model->get_question_by_id($exp_answer[0], $exp_answer2);
            $sort_question[] = $get_question;
        }
        // $this->print($detail_test);

        $answer_arr = [];
        foreach($exp_list_answer as $key => $value){
            $exp_value = explode(':', $value);
            $index  = $exp_value[0];
            $val    = $exp_value[1];
            $doubt  = $exp_value[2];
            $answer_arr[$index] = ['answr'=>$val, 'dbt'=>$doubt];
        }

        $html_question  = '';
        $option_arr     = array('a','b','c','d','e');
        $no             = 1;
        if(!empty($sort_question)){
            foreach($sort_question as $row){
                $dbt = ($answer_arr[$row->id]['dbt'] != '') ? $answer_arr[$row->id]['dbt'] : 'N';
                $html_question .= '
                    <input type="hidden" id="id_question_'.$no.'" name="id_question_'.$no.'" value="'.$row->id.'">
                    <input type="hidden" id="doubt_'.$no.'" name="doubt_'.$no.'" value="'.$dbt.'">
                    <div class="step" id="widget_'.$no.'" style="display:none">
                        '.$row->question.'
                    ';
                for($i=0; $i<count($option_arr); $i++){
                    $opt        = 'option_'.$option_arr[$i];
                    $checked    = ($answer_arr[$row->id]['answr'] == strtoupper($option_arr[$i])) ? 'checked' : '';
                    $opt_name   = ($row->$opt != '') ? substr($row->$opt,3) : '';
                    // var_dump($opt_name);
                    $html_question .= '
                        <div class="custom-control custom-radio">
                          <input type="radio" id="option_'.$option_arr[$i].'_'.$row->id.'" name="option_'.$no.'" class="custom-control-input" value="'.strtoupper($option_arr[$i]).'" onclick="return save()" '.$checked.'>
                          <label class="custom-control-label" for="option_'.$option_arr[$i].'_'.$row->id.'">'.strtoupper($option_arr[$i]).'. '.$opt_name.'</label>
                        </div>
                    ';
                }
                $html_question .= '
                    </div>
                ';
                $no++;
            }
        }

        $id_test = $this->encryption->encrypt($detail_test->id);
        // $this->print($question);
        // exit;
        $data['title']          = 'Lembar Ujian';
        $data['id_test']        = $id_test;
        $data['id_exam']        = sha1($id_exam);
        $data['total_question'] = $total_question;
        $data['html_question']  = $html_question;
        $data['detail_test']    = $detail_test;
        $data['content']        = 'exam/test';
        $this->load->view('layouts/topnav/wrapper', $data);
    }

    public function save_one()
    {
        $id_exam = $this->input->post('id_exam', true);
        $id_test = $this->input->post('id_test', true);
        $id_test = $this->encryption->decrypt($id_test);

        $answer_list = '';
        $input  = $this->input->post(null, true);
        for($i=1; $i<=$input['total_question']; $i++){
            $option      = 'option_'.$i;
            $id_question = 'id_question_'.$i;
            $doubt       = 'doubt_'.$i;
            $answer      = empty($input[$option]) ? '' : $input[$option];
            $answer_list .= $input[$id_question].':'.$answer.':'.$input[$doubt].',';
        }
        $answer_list   = substr($answer_list, 0, -1);
        $data = ['list_answer' => $answer_list];
        $this->Exam_model->update_result($id_test, $data);

        $student = $this->Student_model->get_by_email($this->session->email);
        $id_student = $student->id;
        $exam_result = $this->Exam_model->get_exam_test($id_test,$id_exam,$id_student);
        $list_answer = explode(',', $exam_result->list_answer);
        $list_answerExp = $list_answer[1];
        $total_answer = 0;
        foreach($list_answer as $val){
            $answerExp = explode(':', $val);
            // $this->print($answerExp);
            if($answerExp[1] != ''){
                $total_answer++;
            }
        }
        $total_not_answer = count($list_answer) - $total_answer;
        echo json_encode(array('total_answer' => $total_answer, 'total_not_answer' => $total_not_answer));
    }

    public function save_end()
    {
        $id_test = $this->input->post('id_test', true);
        $id_test = $this->encryption->decrypt($id_test);

        $list_answer = $this->Exam_model->get_answer_by_id($id_test);
        $list_answer = $list_answer->list_answer;

        $exp_answer  = explode(',', $list_answer);

        $sum_true    = 0;
        $sum_false   = 0;
        $value       = 0;
        $sum_value   = 0;
        $noq         = count($exp_answer);
        foreach($exp_answer as $key => $val){
            $exp_val = explode(':', $val);
            $id_question = $exp_val[0];
            $answer      = $exp_val[1];
            $doubt       = $exp_val[2];

            $check_answer = $this->Exam_model->get_question_by_id($id_question, NULL);
            $sum_value = $sum_value + $check_answer->value;

            ($answer == $check_answer->answer) ? $sum_true++ : $sum_false++;
        }

        $total_value = ($sum_true / $noq) * 100;
        $sum_value   = ($sum_value / $noq) * 100;

        $data = [
            'total_true'    => $sum_true,
            'total_value'   => number_format(floor($total_value)),
            'value'         => number_format(floor($sum_value)),
            'date_end'      => date('Y-m-d H:i:s'),
            'status'        => 0
        ];
        $this->Exam_model->update_result($id_test, $data);
        echo json_encode(array('status' => TRUE));
    }

    public function print($id_exam)
    {
        if(!is_student()){
            redirect('dashboard','refresh');
        }
        $exam_result = $this->Examresult_model->get_exam_result($id_exam, NULL);
        $student     = $this->Student_model->get_by_email($this->session->email);
        $id_student  = $student->id;
        $exam_result = $this->Examresult_model->get_exam_result($id_exam, $id_student);

        $data['title'] = 'Cetak Hasil Ujian';
        $data['exam']  = $exam_result;
        $this->load->view('exam/print', $data);
        // echo '<pre>';
        // print_r($exam_result);
        // echo '</pre>';
    }

}

/* End of file Exam.php */
/* Location: ./application/controllers/admin/Exam.php */