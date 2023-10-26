<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'third_party/Spout/Autoloader/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class User extends CI_Controller {

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
        $data['title']      = 'Pengguna';
        $data['content']    = 'user/index';
        $this->load->view('layouts/wrapper', $data);
    }

    public function ajax_list()
    {
        $list = $this->User_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $no++;
            $checkbox = ($item->id_user != 1) ? '<input type="checkbox" class="data-check" value="'.sha1($item->id_user).'">' : '';
            $row = array();
            $row[] = $checkbox;
            $row[] = $no;
            $row[] = $item->name;
            $row[] = $item->email;
            $row[] = $item->username;
            $row[] = $item->usertype_name;
            $row[] = $item->phone;
            if($item->is_active == 1){
                $row[] = '<span class="badge badge-success">Aktif</span>';
            }else{
                $row[] = '<span class="badge badge-danger">Tidak Aktif</span>';
            }
 
            //add html for action
            if($item->id_user == 1){
                $row[] = '
                    <div class="btn-group">
                        <a class="btn btn-sm btn-warning" href="'.site_url('user/edit/'.sha1($item->id_user)).'"><i class="fa fa-edit"></i></a>';
            }else{

                $row[] = '
                    <div class="btn-group">
                        <a class="btn btn-sm btn-warning" href="'.site_url('user/edit/'.sha1($item->id_user)).'"><i class="fa fa-edit"></i></a>
                        <a class="btn btn-sm btn-danger" href="javascript:void(0)" onclick="delete_data('."'".sha1($item->id_user)."'".')"><i class="fa fa-times"></i></a>
                    </div>';
            }
 
            $data[] = $row;
        }
 
        $output = [
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->User_model->count_all(),
            "recordsFiltered" => $this->User_model->count_filtered(),
            "data" => $data,
        ];
        //output to json format
        echo json_encode($output);
    }

    public function add()
    {
        $this->form_validation->set_rules('name', 'Nama Lengkap', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[tbl_user.email]');
        $this->form_validation->set_rules('username', 'Nama Pengguna', 'trim|required|is_unique[tbl_user.username]');
        $this->form_validation->set_rules('password', 'Kata Sandi', 'trim|required|min_length[8]');
        $this->form_validation->set_rules('password_confirm', 'Ulangi Kata Sandi', 'trim|required|min_length[8]|matches[password]');
        $this->form_validation->set_rules('phone', 'Telepon', 'trim|required');
        $this->form_validation->set_rules('usertype_id', 'Grup Pengguna', 'required');

        if ($this->form_validation->run()) {
            // Jika user upload photo
            if (!empty($_FILES['photo']['name'])) {
                // Rename nama photo yang diupload
                $filename = strtolower(url_title($this->input->post('username'))).'-'.date('YmdHis');
                // Konfigurasi photo
                $config['upload_path']  = './uploads/user/';
                $config['allowed_types']= 'gif|jpg|jpeg|png';
                $config['max_size']     = '2048';
                $config['file_name']    = $filename;
                
                $this->load->library('upload', $config);
                
                if (!$this->upload->do_upload('photo')){
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('danger', $error);
                    redirect('user/add','refresh');
                } else {
                    $config['image_library']    = 'gd2';
                    $config['source_image']     = './uploads/user/'.$this->upload->data('file_name');
                    $config['new_image']        = './uploads/user/thumbs/';
                    $config['create_thumb']     = TRUE;
                    $config['maintain_ratio']   = FALSE;
                    $config['width']            = 250;
                    $config['height']           = 250;
                    $config['thumb_marker']     = '';

                    $this->load->library('image_lib', $config);

                    $this->image_lib->resize();

                    $data = [
                        'name'          => $this->input->post('name'),
                        'email'         => $this->input->post('email'),
                        'username'      => $this->input->post('username'),
                        'password'      => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                        'phone'         => $this->input->post('phone'),
                        'photo'         => $this->upload->data('file_name'),
                        'usertype_id'   => $this->input->post('usertype_id'),
                        'is_active'     => 1
                    ];
                    $this->User_model->save($data);
                    $this->session->set_flashdata('success', 'Data telah ditambahkan.');
                    redirect('user','refresh');
                }
            } else {
                // Jika user tidak upload photo
                $data = [
                    'name'          => $this->input->post('name'),
                    'email'         => $this->input->post('email'),
                    'username'      => $this->input->post('username'),
                    'password'      => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                    'phone'         => $this->input->post('phone'),
                    'usertype_id'   => $this->input->post('usertype_id'),
                    'is_active'     => 1
                ];
                $this->User_model->save($data);
                $this->session->set_flashdata('success', 'Data telah ditambahkan.');
                redirect('user','refresh');
            }
        } else {
            $data['title']      = 'Tambah Pengguna';
            $data['usertype']   = $this->db->get('tbl_usertype')->result();
            $data['content']    = 'user/add';
            $this->load->view('layouts/wrapper', $data);
        }
    }

    public function edit($id)
    {
        $data['user'] = $this->User_model->get_by_id($id);
        // var_dump($data['user']); exit;
        
        $this->form_validation->set_rules('name', 'Nama Lengkap', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('username', 'Nama Pengguna', 'trim|required');
        $this->form_validation->set_rules('password', 'Kata Sandi', 'trim|min_length[8]');
        $this->form_validation->set_rules('password_confirm', 'Ulangi Kata Sandi', 'trim|min_length[8]|matches[password]');
        $this->form_validation->set_rules('phone', 'Telepon', 'trim|required');
        $this->form_validation->set_rules('usertype_id', 'Grup Pengguna', 'required');

        if ($this->form_validation->run()) {
            // Jika user upload photo
            if (!empty($_FILES['photo']['name'])) {
                // Rename nama photo yang diupload
                $filename = strtolower(url_title($this->input->post('username'))).'-'.date('YmdHis');
                // Konfigurasi photo
                $config['upload_path']  = './uploads/user/';
                $config['allowed_types']= 'gif|jpg|jpeg|png';
                $config['max_size']     = '1024';
                $config['file_name']    = $filename;
                
                $this->load->library('upload', $config);

                // Jika foto diganti, hapus foto lamanya
                $delete = $this->User_model->get_by_id($id);

                $dir        = './uploads/user/'.$delete->photo;
                $dir_thumbs = './uploads/user/thumbs/'.$delete->photo;

                if (is_file($dir)) {
                    unlink($dir);
                    unlink($dir_thumbs);
                }
                
                if (!$this->upload->do_upload('photo')){
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('danger', $error);
                    redirect('user/edit','refresh');
                } else {
                    $config['image_library']    = 'gd2';
                    $config['source_image']     = './uploads/user/'.$this->upload->data('file_name');
                    $config['new_image']        = './uploads/user/thumbs/';
                    $config['create_thumb']     = TRUE;
                    $config['maintain_ratio']   = FALSE;
                    $config['width']            = 250;
                    $config['height']           = 250;
                    $config['thumb_marker']     = '';

                    $this->load->library('image_lib', $config);

                    $this->image_lib->resize();

                    if ($this->input->post('password') != NULL) {
                        $data = [
                            'name'          => $this->input->post('name'),
                            'email'         => $this->input->post('email'),
                            'username'      => $this->input->post('username'),
                            'password'      => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                            'phone'         => $this->input->post('phone'),
                            'photo'         => $this->upload->data('file_name'),
                            'usertype_id'   => $this->input->post('usertype_id'),
                            'is_active'     => $this->input->post('status')
                        ];
                    } else {
                        $data = [
                            'name'          => $this->input->post('name'),
                            'email'         => $this->input->post('email'),
                            'username'      => $this->input->post('username'),
                            'phone'         => $this->input->post('phone'),
                            'photo'         => $this->upload->data('file_name'),
                            'usertype_id'   => $this->input->post('usertype_id'),
                            'is_active'     => $this->input->post('status')
                        ];
                    }
                    $this->User_model->update($id,$data);
                    $this->session->set_flashdata('success', 'Data telah diubah.');
                    redirect('user','refresh');
                }
            } else {
                // Jika user tidak upload photo
                if ($this->input->post('password') != NULL) {
                    $data = [
                        'name'          => $this->input->post('name'),
                        'email'         => $this->input->post('email'),
                        'username'      => $this->input->post('username'),
                        'password'      => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                        'phone'         => $this->input->post('phone'),
                        'usertype_id'   => $this->input->post('usertype_id'),
                        'is_active'     => $this->input->post('status')
                    ];
                } else {
                    $data = [
                        'name'          => $this->input->post('name'),
                        'email'         => $this->input->post('email'),
                        'username'      => $this->input->post('username'),
                        'phone'         => $this->input->post('phone'),
                        'usertype_id'   => $this->input->post('usertype_id'),
                        'is_active'     => $this->input->post('status')
                    ];
                }
                $update = $this->User_model->update($id,$data);
                if($update){
                    $this->session->set_flashdata('success', 'Data telah diubah.');
                    redirect('user','refresh');
                }else{
                    $this->session->set_flashdata('warning', 'Tidak ada data yang diubah.');
                    redirect('user/edit/'.$id,'refresh');
                }
            }
        } else {
            $data['user']           = $this->User_model->get_by_id($id);
            if ($data['user']) {
                $data['title']      = 'Ubah Pengguna';
                $data['usertype']   = $this->db->get('tbl_usertype')->result();
                $data['content']    = 'user/edit';
                $this->load->view('layouts/wrapper', $data);
            } else {
                $this->session->set_flashdata('danger', 'Maaf, data tidak ditemukan.');
                redirect('dashboard','refresh');
            }
        }
    }
 
    public function ajax_delete($id)
    {
        $this->User_model->delete($id);
        echo json_encode(array("status" => TRUE));
    }
 
    public function ajax_bulk_delete()
    {
        $list_id = $this->input->post('id_user',TRUE);
        
        foreach ($list_id as $id){
            $this->User_model->delete($id);
        }
        echo json_encode(array("status" => TRUE));
    }

    public function download_format()
    {
        force_download('downloads/format/format-user.xlsx',NULL);
    }

    public function import_excel()
    {
        $config['upload_path']  = './uploads/';
        $config['allowed_types']= 'xls|xlsx';
        $config['file_name']    = 'doc-user-excel-'.time();
        
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
                            'name'          => $row->getCellAtIndex(1),
                            'email'         => $row->getCellAtIndex(2),
                            'username'      => $row->getCellAtIndex(3),
                            'phone'         => $row->getCellAtIndex(4),
                            'usertype_id'   => $row->getCellAtIndex(5),
                            'is_active'     => 1
                        ];
                        $import = $this->User_model->import($data);
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

    public function export_excel()
    {
        $user = $this->User_model->get_all();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Name');
        $sheet->setCellValue('C1', 'Email');
        $sheet->setCellValue('D1', 'Username');
        $sheet->setCellValue('E1', 'Usertype');
        $sheet->setCellValue('F1', 'Phone');
        
        $no = 1;
        $row = 2;

        foreach($user as $data)
        {
            $sheet->setCellValue('A'.$row, $no++);
            $sheet->setCellValue('B'.$row, $data->name);
            $sheet->setCellValue('C'.$row, $data->email);
            $sheet->setCellValue('D'.$row, $data->username);
            $sheet->setCellValue('E'.$row, $data->usertype_name);
            $sheet->setCellValue('F'.$row, $data->phone);
            $row++;
        }
        $writer = new Xlsx($spreadsheet);
        $filename = 'User-'.time();
        
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

/* End of file User.php */
/* Location: ./application/modules/user/controllers/User.php */