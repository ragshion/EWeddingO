<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$this->load->view('login');
	}

	function cek_login(){
		$username = $this->input->post('username');
		$password = $this->input->post('password');

		$where = array (
			'username' => $username,
			'password' => sha1($password)
		);

		$cek = $this->db->where($where)->get('admin')->row_array();
		if($cek){
			$data_session = array (
				'username' => $username,
				'status' => 'login'
			);
			$this->session->set_userdata($data_session);
			redirect('');
		}else{
			$this->session->set_flashdata('alert','<div class="alert alert-danger alert-dismissible fade show" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true"><i class="fas fa-times"></i></span> </button> <strong>Gagal!</strong> Username atau Password Salah.</div>');
			redirect('login');
		}
	}

	function logout(){
		
		$this->session->sess_destroy();
		redirect('');
	}
}