<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}

	function index(){
		$data['data'] = $this->db->get('kategori')->result_array();
		$this->load->view('template/header');
		$this->load->view('kategori',$data);
		$this->load->view('template/footer');
	}

	function simpan(){
		$data = $this->input->post();
		$this->db->insert('kategori',$data);
		redirect('kategori');
	}

	function hapus($id){
		$this->db->where('id',$id)->delete('kategori');
		redirect('kategori');
	}

	function ubah(){
		$data = $this->db->where('id',$this->input->post('id'))->get('kategori')->row_array();
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function update(){
		$data = $this->input->post();
		$this->db->where('id',$data['id'])->update('kategori',$data);
		redirect('kategori');
	}
}