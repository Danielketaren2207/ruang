<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Errors extends CI_Controller {

	public function error_404()
	{
		$data['title']	= 'Halaman Tidak Ditemukan';
		$this->load->view('errors/404', $data);
	}

}

/* End of file Errors.php */
/* Location: ./application/controllers/Errors.php */