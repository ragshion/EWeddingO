<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Peta extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}

	function index(){
		$this->load->view('template/header');
		$this->load->view('peta');
		$this->load->view('template/footer');
	}

	function latlng(){
		$resp = array();
		$resp = $this->db->order_by('status','asc')->get('pengusaha')->result_array();
		foreach ($resp as $r) {
			$kat = explode(";", $r['kategori']);
			$kk = '';
			foreach ($kat as $k) {
				$x = $this->db->where('id',$k)->get('kategori')->row_array();
				$kk .= $x['kategori'].', ';
			}

			$x = $this->db->where('id_usaha',$r['id_usaha'])->get('foto')->row_array();
			$data[] = array(
				'id_usaha' => $r['id_usaha'],
				'nama_usaha' => $r['nama_usaha'],
				'alamat' => $r['alamat'],
				'no_wa' => $r['no_wa'],
				'deskripsi' => $r['deskripsi'],
				'kategori' => substr($kk, 0, -2),
				'foto' => $x['nama_file'],
				'lat' => $r['lat'],
				'lng' => $r['lng'],
				'status' => $r['status']
			);
		}

		header('Content-Type: application/json');
		echo json_encode($data);
	}

}