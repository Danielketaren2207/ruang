<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Auth extends CI_Controller {

	public function login()
	{
		is_logged();
		
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');

		if ($this->form_validation->run()) {
            $email 		= $this->input->post('email');
            $password 	= $this->input->post('password');

			$user = $this->db->get_where('tbl_user', ['email' => $email])->row_array();
			// Cek user pada database
			if($user){
				// Cek status user aktif
				if($user['is_active'] == 1) {
					// Cek status user login
					if($user['is_login'] == 0) {
						// Cek password
						if(password_verify($password, $user['password'])) {
							// Ambil data session
							$data = [
								'id_user'		=> $user['id_user'],
								'name'			=> $user['name'],
								'email'			=> $user['email'],
								'username'		=> $user['username'],
								'phone'			=> $user['phone'],
								'photo'			=> $user['photo'],
								'usertype_id'	=> $user['usertype_id']
							];
							$id_user = sha1($user['id_user']);
							$data2 = [
								'is_login'   => 1,
								'last_login' => date('Y-m-d H:i:s')
							];
							// Simpan data session
							$this->session->set_userdata($data);
							$this->User_model->update($id_user, $data2);
							// Redirect ke halaman admin yang diproteksi
							redirect('dashboard','refresh');
						} else {
							// Jika password salah, back to login
							$this->session->set_flashdata('danger','Maaf, kata sandi salah.');
							redirect('login','refresh');
						}
					} else {
						// Jika user statusnya login, back to login
						$this->session->set_flashdata('danger','Maaf, akun anda saat ini sedang login.');
						redirect('login','refresh');
					}
				} else {
					// Jika user statusnya tidak aktif, back to login
					$this->session->set_flashdata('danger','Maaf, akun anda tidak aktif.');
					redirect('login','refresh');
				}
			} else {
				// Jika tidak ada data user (email dan password salah), back to login
				$this->session->set_flashdata('danger','Maaf, alamat email tidak terdaftar.');
				redirect('login','refresh');
			}
		} else {
			$data['title'] 		= 'Masuk';
			$data['content'] 	= 'auth/login';
			$this->load->view('layouts/auth/wrapper', $data);
		}
	}

	public function logout()
	{
		$id_user = sha1($this->session->id_user);
		$data2 = ['is_login' => 0];
		$this->User_model->update($id_user, $data2);
		// Membuang semua session yang sudah diset pada saat login
		$this->session->sess_destroy();
		// Jika session sudah dibuang, back to login
		$this->session->set_flashdata('success','Berhasil keluar');
		redirect('login','refresh');
	}

	public function change_password()
	{
		is_login();

		$data['user'] = $this->db->get_where('tbl_user', ['id_user' => $this->session->id_user])->row_array();

		$this->form_validation->set_rules('current_password', 'Kata Sandi Saat Ini', 'trim|required');
		$this->form_validation->set_rules('new_password', 'Kata Sandi Baru', 'trim|required|min_length[8]');
		$this->form_validation->set_rules('confirm_password', 'Ulangi Kata Sandi Baru', 'trim|required|min_length[8]|matches[new_password]');

		if ($this->form_validation->run()) {
			$current_password 	= $this->input->post('current_password');
			$new_password 		= $this->input->post('new_password');
			$password_hash 		= password_hash($new_password, PASSWORD_DEFAULT);

			if (password_verify($current_password, $data['user']['password'])) {
				// Cek password tidak boleh sama dengan password sebelumnya
				if ($current_password != $new_password) {
					$this->db->set('password', $password_hash);
					$this->db->where('id_user', $this->session->id_user);
					$this->db->update('tbl_user');

					$this->session->set_flashdata('success', 'Kata Sandi telah diperbaharui.');
					redirect('dashboard','refresh');
				} else {
					$this->session->set_flashdata('danger', 'Kata Sandi baru tidak boleh sama dengan sebelumnya.');
					redirect('password/change','refresh');
				}
			}
			$this->session->set_flashdata('danger', 'Kata Sandi saat ini salah.');
			redirect('password/change','refresh');
		} else {
			$data['title']		= 'Ganti Kata Sandi';
			$data['content']	= 'auth/change_password';
			$this->load->view('layouts/wrapper', $data);
		}
	}

	public function forgot_password()
	{
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

		if ($this->form_validation->run()) {
			$email = $this->input->post('email', true);
			$code  = $this->input->post('code', true);

			$user = $this->User_model->get_by_email($email);
			if($user){
				try {
					$mail = new PHPMailer(true);

					$mail->isSMTP();
					$mail->Host 		= 'smtp.gmail.com';
					$mail->SMTPAuth 	= true;
					$mail->Username 	= 'your@email.com';
					$mail->Password 	= 'yourpassword';
					$mail->SMTPSecure	= 'tls';
					$mail->Port 		= 587;
					$mail->CharSet 		= 'UTF-8';

					$mail->setFrom('noreply@gmail.com', 'Bahri Exam');
					$mail->addAddress($email);

					$mail->isHTML(true);
					$mail->Subject 	= 'Lupa Kata Sandi';
					$mail->Body 	= '
						Halo, '.$user->name.', apakah anda mencoba mengatur ulang kata sandi?<br>
						Berikut ini merupakan link untuk mengatur ulang kata sandi anda. Klik link dibawah ini :<br>
						<a href="'.site_url('password/reset/'.$code).'">'.site_url('password/reset/'.$code).'</a>
					';
					if ($mail->send()) {
						$this->User_model->update(sha1($user->id_user), ['forgot_password_code'=>$code]);
						$this->session->set_flashdata('success','Permintaan terkirim, silahkan cek email anda!');
						redirect('password/forgot','refresh');
					} else {
						$this->session->set_flashdata('danger','Terjadi kesalahan, cobalah beberapa saat lagi.');
						redirect('password/forgot','refresh');
					}
				} catch (Exception $e) {
					$this->session->set_flashdata('danger','Pesan tidak terkirim. Error: '.$mail->ErrorInfo);
					redirect('password/forgot','refresh');
				}
			}else{
				$this->session->set_flashdata('danger', 'Maaf, email tidak terdaftar.');
				redirect('password/forgot','refresh');
			}
		} else {
			$data['title'] 		= 'Lupa Kata Sandi';
			$data['content'] 	= 'auth/forgot_password';
			$this->load->view('layouts/auth/wrapper', $data);
		}
	}

	public function reset_password($code)
	{
		$user = $this->db->get_where('tbl_user', ['forgot_password_code'=>$code])->row();
		if($user){
			$this->form_validation->set_rules('new_password', 'Kata Sandi Baru', 'trim|required|min_length[8]');
			$this->form_validation->set_rules('confirm_password', 'Ulangi Kata Sandi Baru', 'trim|required|min_length[8]|matches[new_password]');

			if ($this->form_validation->run()) {
				$new_password 	= $this->input->post('new_password');
				$password_hash 	= password_hash($new_password, PASSWORD_DEFAULT);

				$data = [
					'password'	=> $password_hash,
					'forgot_password_code'	=> NULL
				];
				$this->User_model->update(sha1($user->id_user), $data);

				$this->session->set_flashdata('success', 'Kata Sandi telah diperbaharui.');
				redirect('login','refresh');
			} else {
				$data['title'] 		= 'Reset Kata Sandi';
				$data['content'] 	= 'auth/reset_password';
				$this->load->view('layouts/auth/wrapper', $data);
			}
		}else{
			$this->load->view('errors/404');
		}
	}

}

/* End of file Auth.php */
/* Location: ./application/controllers/Auth.php */