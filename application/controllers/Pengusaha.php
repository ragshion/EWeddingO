<?php
class Pengusaha extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}

	function index(){
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
			$data['data'][] = array(
				'id_usaha' => $r['id_usaha'],
				'nama_usaha' => $r['nama_usaha'],
				'alamat' => $r['alamat'],
				'no_wa' => $r['no_wa'],
				'deskripsi' => $r['deskripsi'],
				'kategori' => substr($kk, 0, -2),
				'foto' => $x['nama_file'],
				'latlng' => $r['lat'].','.$r['lng'],
				'status' => $r['status']
			);
		}
		$data['kategori'] = $this->db->get('kategori')->result_array();
		$this->load->view('template/header');
		$this->load->view('pengusaha',$data);
		$this->load->view('template/footer');
	}

	function simpan(){
		$data = $this->input->post();
		$data['id_usaha'] = uniqid();
		$data['status'] = '1';
		$kategori = '';
		foreach ($data['kategori'] as $d) {
			$kategori .= $d.';';
		}

		if(!empty($_FILES['userFiles']['name'])){
            $filesCount = count($_FILES['userFiles']['name']);

            for($i = 0; $i < $filesCount; $i++){
                $_FILES['userFile']['name'] = $_FILES['userFiles']['name'][$i];
                $_FILES['userFile']['type'] = $_FILES['userFiles']['type'][$i];
                $_FILES['userFile']['tmp_name'] = $_FILES['userFiles']['tmp_name'][$i];
                $_FILES['userFile']['error'] = $_FILES['userFiles']['error'][$i];
                $_FILES['userFile']['size'] = $_FILES['userFiles']['size'][$i];

                $x = $i+1;

                $config['upload_path'] = './uploads/';
                $config['allowed_types'] = 'jpeg|jpg|png|JPG|PNG';
				
				$config['file_name'] = $data['id_usaha'].$x.'.jpg';
                
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if($this->upload->do_upload('userFile')){
                	$filedata = array(
                		'nama_file' => $config['file_name'],
                		'tgl_upload' => date('Y-m-d H:i:s'),
                		'id_usaha' => $data['id_usaha']
                	);

                	$this->db->insert('foto',$filedata);
                	echo 'berhasil';

                }else{
					$error = array ('error' => $this->upload->display_errors());
					var_dump($error);
				}	
            }
            $data['kategori'] = substr($kategori, 0, -1);

            $this->db->insert('pengusaha',$data);

            $this->session->set_flashdata('alert','<div class="alert alert-primary alert-dismissible fade show" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true"><i class="fas fa-times"></i></span> </button> <strong>Berhasil!</strong> Data Pengusaha berhasil disimpan.</div>');

            // echo 'berhasil';

            redirect('pengusaha');
        }else{
        	var_dump($_FILES);
        }
	}

	function hapus($id){
		$this->db->where('id_usaha',$id)->delete('pengusaha');
		redirect('pengusaha');
	}

	function verifikasi($id){
		$data = array(
			'status' => '1'
		);
		$this->db->where('id_usaha',$id)->update('pengusaha',$data);
		redirect('pengusaha');
	}

	function upload(){
		$target_dir = "uploads/tmp/";  
		$target_file_name = $target_dir .$this->input->get('nama_file').'.jpg';  
		$response = array();
		
		if (isset($_FILES["file"]))   
		{  
		   if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file_name)){  
		    $success = true;  
		    $message = "Successfully Uploaded";

		    $resized_file = 'uploads/'.$this->input->get('nama_file').'.jpg';
		    smart_resize_image($target_file_name , null, 512 , 288 , true , $resized_file , true , false ,100);
		     
		    $data['id_usaha'] = $this->input->get('id_usaha');
		    $data['nama_file'] = $this->input->get('nama_file').'.jpg';
		    $data['tgl_upload'] = date('Y-m-d H:i:s');

		    $this->db->insert('foto',$data);

		   }else{  
		    $success = false;  
		    $message = "Error while uploading";  
		   }  
		}  
		else   
		{  
		      $success = false;  
		      $message = "Required Field Missing";  
		}  
		$response["success"] = $success;  
		$response["message"] = $message;

		echo json_encode($response);  
	}

	function android_usulan(){
		$data = $this->input->post();
		$data['password'] = sha1($data['password']);
		$kat = explode(";", $data['kategori']);
		$kategori = "";
		foreach ($kat as $k) {
			$r = $this->db->where('kategori',$k)->get('kategori')->row_array();
			$kategori .= $r['id'].';';
		}
		$data['kategori'] = substr($kategori, 0, -1);
		$latlng = explode(",", $data['latlng']);
		unset($data['latlng']);
		$data['lat'] = $latlng[0];
		$data['lng'] = $latlng[1];
		$data['status'] = '0';
		$this->db->insert('pengusaha',$data);

		$resp = array(
			'respon' => 'Data Anda Akan Diverifikasi Oleh Petugas maximal 1x24jam, setelah itu anda bisa login'
		);

		header('Content-Type: application/json');
		echo json_encode($resp, JSON_PRETTY_PRINT);
	}

	function detail_usaha(){
		$id = $this->input->post('id_usaha');
		$data = array();
		// $data = $this->db->select('post.*, kategori.kategori as post_kategori')
		// 	->from('post')
		// 	->where('id_usaha',$id)
		// 	->join('kategori','post.kategori=kategori.id')
		// 	->get()->result_array();

		$data = $this->db->select('post.*')
			->from('post')
			->where('id_usaha',$id)
			->order_by('tgl_input','desc')
			->get()->result_array();

		header('Content-Type: application/json');
		echo json_encode($data, JSON_PRETTY_PRINT);
	}

	function detail_pengusaha(){
		$id_usaha = $this->input->post('id_usaha');

		$d = $this->db->where('id_usaha',$id_usaha)->get('pengusaha')->row_array();

		if($d){
			$kategori = explode(";", $d['kategori']);
			$kat = '';
			foreach ($kategori as $k) {
				$row = $this->db->where('id',$k)->get('kategori')->row_array();
				$kat .= $row['kategori'].', ';
			}

			$jarak = distance($d['lat'],$d['lng'],$this->input->post('lat'),$this->input->post('lng'));

			$resp = array(
				'id_usaha' => $d['id_usaha'],
				'nama_usaha' => $d['nama_usaha'],
				'alamat' => $d['alamat'],
				'no_wa' => $d['no_wa'],
				'deskripsi' => $d['deskripsi'],
				'kategori' => substr($kat, 0, -2),
				'lat' => $d['lat'],
				'lng' => $d['lng'],
				'jarak' => number_format((float)$jarak, 2, '.', '')." km",
				'status' => 'berhasil'
			);
		}else{
			$resp = array(
				'status' => 'gagal',
				'respon' => 'username atau password salah'
			);
		}

		header('Content-Type: application/json');
		echo json_encode($resp, JSON_PRETTY_PRINT);
	}

	function login(){
		$username = $this->input->post('username');
		$password = $this->input->post('password');

		$d = $this->db->where('username',$username)->where('password',sha1($password))->where('status','1')->get('pengusaha')->row_array();

		if($d){
			$resp = array(
				'id_usaha' => $d['id_usaha'],
				'status' => 'berhasil',
				'username' => $d['username'],
				'respon' => 'Berhasil Login'
			);
		}else{
			$resp = array(
				'status' => 'gagal',
				'respon' => 'username / password salah atau akun belum diverifikasi'
			);
		}

		header('Content-Type: application/json');
		echo json_encode($resp, JSON_PRETTY_PRINT);
	}

	function api_post(){
		$data = array();
		if ($this->input->post('kategori') == 'Semua') {
			$zzz = $this->db->select('pengusaha.*, post.*, kategori.kategori as post_kategori')
				->from('post')
				->join('pengusaha','post.id_usaha=pengusaha.id_usaha','left')
				->join('kategori','post.kategori=kategori.id')
				->where('status','1')
				->get()->result_array();
			// $zzz = $this->db->where('status','1')->get('pengusaha')->result_array();
			
		}else{
			$k = $this->db->where('kategori',$this->input->post('kategori'))->get('kategori')->row_array();
			// $zzz = $this->db->like('kategori',$k['id'])->where('status','1')->get('pengusaha')->result_array();
			$zzz = $this->db->select('pengusaha.*, post.*, kategori.kategori as post_kategori')
				->from('post')
				->join('pengusaha','post.id_usaha=pengusaha.id_usaha','left')
				->join('kategori','post.kategori=kategori.id')
				->where('status','1')
				->where('post.kategori',$k['id'])
				->get()->result_array();
		}

		foreach ($zzz as $d) {
			$kategori = explode(";", $d['kategori']);
			$kat = '';
			foreach ($kategori as $k) {
				$row = $this->db->where('id',$k)->get('kategori')->row_array();
				$kat .= $row['kategori'].', ';
			}

			$jarak = distance($d['lat'],$d['lng'],$this->input->post('lat'),$this->input->post('lng'));

			$data[] = array(
				'id_usaha' => $d['id_usaha'],
				'nama_usaha' => $d['nama_usaha'],
				'alamat' => $d['alamat'],
				'no_wa' => $d['no_wa'],
				'deskripsi' => $d['deskripsi'],
				'kategori' => substr($kat, 0, -2),
				'lat' => $d['lat'],
				'lng' => $d['lng'],
				'jarak' => number_format((float)$jarak, 2, '.', '')." km",
				'keterangan' => $d['keterangan'],
				'post_kategori' => $d['post_kategori'],
				'foto' => $d['foto']
			);

		}

		sort_array_by_value('jarak',$data);
		$data = array_values($data);

		header('Content-Type: application/json');
		echo json_encode($data, JSON_PRETTY_PRINT);
	}

	function api(){
		$data = array();
		if ($this->input->post('kategori') == 'Semua') {
			$zzz = $this->db->where('status','1')->get('pengusaha')->result_array();
			# code...
		}else{
			$k = $this->db->where('kategori',$this->input->post('kategori'))->get('kategori')->row_array();
			$zzz = $this->db->like('kategori',$k['id'])->where('status','1')->get('pengusaha')->result_array();
		}

		foreach ($zzz as $d) {
			$kategori = explode(";", $d['kategori']);
			$kat = '';
			foreach ($kategori as $k) {
				$row = $this->db->where('id',$k)->get('kategori')->row_array();
				$kat .= $row['kategori'].', ';
			}

			$f = $this->db->where('id_usaha',$d['id_usaha'])->limit(1)->get('foto')->row_array();

			$jarak = distance($d['lat'],$d['lng'],$this->input->post('lat'),$this->input->post('lng'));

			$data[] = array(
				'id_usaha' => $d['id_usaha'],
				'nama_usaha' => $d['nama_usaha'],
				'alamat' => $d['alamat'],
				'no_wa' => $d['no_wa'],
				'deskripsi' => $d['deskripsi'],
				'kategori' => substr($kat, 0, -2),
				'foto' => $f['nama_file'],
				'lat' => $d['lat'],
				'lng' => $d['lng'],
				'jarak' => number_format((float)$jarak, 2, '.', '')." km"
			);

		}

		sort_array_by_value('jarak',$data);
		$data = array_values($data);

		header('Content-Type: application/json');
		echo json_encode($data, JSON_PRETTY_PRINT);
	}

	function foto(){
		$id_usaha = $this->input->post('id_usaha');
		$data = $this->db->where('id_usaha',$id_usaha)->order_by('tgl_upload','desc')->get('foto')->result_array();
		header('Content-Type: application/json');
		echo json_encode($data, JSON_PRETTY_PRINT);
	}

	function simpan_post(){
		$data = $this->input->post();
		$data['kategori'] = '1';
		$data['tgl_input'] = date('Y-m-d H:i:s');
		// $k = $this->db->where('kategori',$data['kategori'])->get('kategori')->row_array();
		// $data['kategori'] = $k['id'];
		$this->db->insert('post',$data);
		$resp = array(
			'respon' => 'Berhasil Menambahkan Data'
		);

		header('Content-Type: application/json');
		echo json_encode($resp, JSON_PRETTY_PRINT);
	}

	function kategori(){
		$data = $this->db->get('kategori')->result_array();
		header('Content-Type: application/json');
		echo json_encode($data, JSON_PRETTY_PRINT);
	}

	function cari(){
		$key = $this->input->post('key');
		$data = array();
		$zzz = $this->db->where('status','1')->like('nama_usaha',$key)->get('pengusaha')->result_array();

		foreach ($zzz as $d) {
			$kategori = explode(";", $d['kategori']);
			$kat = '';
			foreach ($kategori as $k) {
				$row = $this->db->where('id',$k)->get('kategori')->row_array();
				$kat .= $row['kategori'].', ';
			}

			$f = $this->db->where('id_usaha',$d['id_usaha'])->limit(1)->get('foto')->row_array();

			$jarak = distance($d['lat'],$d['lng'],$this->input->post('lat'),$this->input->post('lng'));

			$data[] = array(
				'id_usaha' => $d['id_usaha'],
				'nama_usaha' => $d['nama_usaha'],
				'alamat' => $d['alamat'],
				'no_wa' => $d['no_wa'],
				'deskripsi' => $d['deskripsi'],
				'kategori' => substr($kat, 0, -2),
				'foto' => $f['nama_file'],
				'lat' => $d['lat'],
				'lng' => $d['lng'],
				'jarak' => number_format((float)$jarak, 2, '.', '')." km"
			);

		}

		sort_array_by_value('jarak',$data);
		$data = array_values($data);

		header('Content-Type: application/json');
		echo json_encode($data, JSON_PRETTY_PRINT);
	}
}