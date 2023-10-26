<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function is_login()
{
	$CI =& get_instance();

	if($CI->session->id_user == NULL) {
		$CI->session->set_flashdata('warning', 'Silahkan login terlebih dahulu');
		redirect('login','refresh');
	}
}

function is_logged()
{
	$CI =& get_instance();

	if($CI->session->id_user) {
		redirect('dashboard','refresh');
	}
}

function is_superadmin()
{
	$CI =& get_instance();

	$usertype = $CI->session->usertype_id;

	if($usertype == 1) {
		return $usertype;
	}
	return NULL;
}

function is_admin()
{
	$CI =& get_instance();

	$usertype = $CI->session->usertype_id;

	if($usertype == 2) {
		return $usertype;
	}
	return NULL;
}

function is_teacher()
{
	$CI =& get_instance();

	$usertype = $CI->session->usertype_id;

	if($usertype == 3) {
		return $usertype;
	}
	return NULL;
}

function is_student()
{
	$CI =& get_instance();

	$usertype = $CI->session->usertype_id;

	if($usertype == 4) {
		return $usertype;
	}
	return NULL;
}