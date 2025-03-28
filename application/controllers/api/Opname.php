<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Opname extends RestController {

	public function __construct() {
		parent::__construct();
		$this->load->model('OpnameApi_model');
		$this->load->library('form_validation');
	}

	public function index_get($id = null) {
		$id = $this->get('id');
		$page = $this->get('page') ?? 1;  
		$limit = $this->get('limit') ?? 100;
		if ($id === null) {
			$offset = ($page - 1) * $limit;
			$opname = $this->OpnameApi_model->getOpname($id, $offset, $limit);
		} else {
			$opname = $this->OpnameApi_model->getOpname($id);
		}	
		if ($opname) {
			$this->response([
				'status' => TRUE,
				'data' => $opname,
				'message' => 'Success'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message' => 'Opname does not exist'
			], RestController::HTTP_NOT_FOUND);
		}
	}
	
	public function index_post() {
		$this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required|trim');
		$this->form_validation->set_rules('id_kategori_barang', 'Kategori Barang', 'required|trim|integer');
		$this->form_validation->set_rules('id_tipe_barang', 'Tipe Barang', 'required|trim|integer');
		$this->form_validation->set_rules('harga_pack', 'Harga Pack Barang', 'required|trim|integer');
		$this->form_validation->set_rules('jml_pcs_pack', 'Jumlah Isi Pack', 'required|trim|integer');
		$this->form_validation->set_rules('harga_satuan', 'Harga Satuan Barang', 'required|trim|integer');
		$this->form_validation->set_rules('harga_jual', 'Harga Jual Barang', 'required|trim|integer');
		$this->form_validation->set_rules('stok', 'Stok Barang', 'required|trim|integer');
	
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
		} else {
			$errors = [];
		}
	
		if (empty($_FILES['gambar_barang']['name'])) {
			$errors['gambar_barang'] = 'Gambar Barang harus diunggah.';
		}
	
		if (!empty($errors)) {
			$this->response([
				'status' => FALSE,
				'message' => $errors
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
	
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'jpg|jpeg|png|gif';
		$config['max_size'] = 50000;
		$this->load->library('upload', $config);
	
		if (!$this->upload->do_upload('gambar_barang')) {
			$errors['gambar_barang'] = strip_tags($this->upload->display_errors());
			$this->response([
				'status' => FALSE,
				'message' => $errors
			], RestController::HTTP_BAD_REQUEST);
			return;
		}

		$data_image = $this->upload->data();
		$data_image['file_name'] = date('YmdHis') . $data_image['file_ext'];
		rename($data_image['full_path'], $data_image['file_path'] . $data_image['file_name']);
		
		$data = [
			'nama_barang' => $this->input->post('nama_barang'),
			'gambar_barang' => $data_image['file_name'],
			'id_kategori_barang' => $this->input->post('id_kategori_barang'),
			'id_tipe_barang' => $this->input->post('id_tipe_barang'),
			'id_mitra_barang' => $this->input->post('id_mitra_barang'),
			'harga_pack' => $this->input->post('harga_pack'),
			'jml_pcs_pack' => $this->input->post('jml_pcs_pack'),
			'harga_satuan' => $this->input->post('harga_satuan'),
			'harga_jual' => $this->input->post('harga_jual'),
			'stok' => $this->input->post('stok'),
			'laba' => $this->input->post('harga_jual') - $this->input->post('harga_satuan'),
		];
	
		if ($this->OpnameApi_model->addOpname($data) > 0) {
			$this->response([
				'status' => true,
				'message' => 'Barang berhasil ditambahkan',
			], RestController::HTTP_CREATED);
		} else {
			$this->response([
				'status' => false,
				'message' => 'Barang gagal ditambahkan',
			], RestController::HTTP_BAD_REQUEST);
		}
	}
	
	public function edit_post() {
		// Cek apakah request memiliki _method=PUT
		if ($this->input->get('_method') === 'PUT') {
			return $this->index_put();
		}
	
		$this->response([
			'status' => FALSE,
			'message' => 'Metode tidak valid'
		], RestController::HTTP_BAD_REQUEST);
	}

	public function index_put() {
		$id = $this->input->post('id'); // Gunakan POST karena dikirim sebagai form-data
	
		if (!$id) {
			$this->response([
				'status' => FALSE,
				'message' => 'ID opname harus dikirim dalam request'
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
	
		$opname = $this->OpnameApi_model->getOpnameDataById($id);
		if (!$opname) {
			$this->response([
				'status' => FALSE,
				'message' => 'Opname tidak ditemukan'
			], RestController::HTTP_NOT_FOUND);
			return;
		}
	
		$gambar_barang = $opname['gambar_barang'];
	
		if (!empty($_FILES['gambar_barang']['name'])) {
			$config['upload_path']   = './uploads/';
			$config['allowed_types'] = 'jpg|jpeg|png|gif';
			$config['max_size']      = 50000;
			$config['encrypt_name']  = TRUE;
			$this->load->library('upload', $config);
	
			if ($this->upload->do_upload('gambar_barang')) {
				$uploadData = $this->upload->data();
				$gambar_barang = date('YmdHis') . $uploadData['file_ext'];
				rename($uploadData['full_path'], $uploadData['file_path'] . $gambar_barang);
			} else {
				$this->response([
					'status' => FALSE,
					'message' => 'Gagal mengupload gambar: ' . $this->upload->display_errors()
				], RestController::HTTP_BAD_REQUEST);
				return;
			}
		}
	
		$data = [
			'nama_barang' => $this->input->post('nama_barang') ?? $opname['nama_barang'],
			'gambar_barang' => $gambar_barang ?? $opname['gambar_barang'],
			'id_kategori_barang' => $this->input->post('id_kategori_barang') ?? $opname['id_kategori_barang'],
			'id_tipe_barang' => $this->input->post('id_tipe_barang') ?? $opname['id_tipe_barang'],
			'id_mitra_barang' => $this->input->post('id_mitra_barang') ?? $opname['id_mitra_barang'],
			'harga_pack' => $this->input->post('harga_pack') ?? $opname['harga_pack'],
			'jml_pcs_pack' => $this->input->post('jml_pcs_pack') ?? $opname['jml_pcs_pack'],
			'harga_satuan' => $this->input->post('harga_satuan') ?? $opname['harga_satuan'],
			'harga_jual' => $this->input->post('harga_jual') ?? $opname['harga_jual'],
			'stok' => $this->input->post('stok') ?? $opname['stok'],
			'laba' => ($this->input->post('harga_jual') ?? $opname['harga_jual']) - ($this->input->post('harga_satuan') ?? $opname['harga_satuan']),
			'updated_date' => date('Y-m-d H:i:s'),
		];
	
		if ($this->OpnameApi_model->editOpname($data, $id) > 0) {
			$this->response([
				'status' => TRUE,
				'message' => 'Berhasil mengupdate opname'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message' => 'Gagal mengupdate opname'
			], RestController::HTTP_BAD_REQUEST);
		}
	}

	public function kurang_put() {
		$id = $this->put('id');
		
		if (!$id) {
			$this->response([
				'status' => FALSE,
				'message' => 'ID opname harus dikirim dalam request'
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
	
		$opname = $this->OpnameApi_model->getOpnameDataById($id);
		if (!$opname) {
			$this->response([
				'status' => FALSE,
				'message' => 'Opname tidak ditemukan'
			], RestController::HTTP_NOT_FOUND);
			return;
		}
	
		$stok_dikurangi = $this->put('stok') ?? 0;
		if ($stok_dikurangi <= 0) {
			$this->response([
				'status' => FALSE,
				'message' => 'Jumlah stok yang dikurangi harus lebih dari 0'
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
	
		$stok_baru = max(0, $opname['stok'] - $stok_dikurangi);
	
		$data = [
			'stok' => $stok_baru ?? $opname['stok'],
			'updated_date' => date('Y-m-d H:i:s'),
			'user_input' => $this->input->post('user_input'),
		];
	
		if ($this->OpnameApi_model->editOpname($data, $id) > 0) {
			$this->response([
				'status' => TRUE,
				'message' => 'Stok berhasil dikurangi',
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message' => 'Gagal mengupdate stok'
			], RestController::HTTP_BAD_REQUEST);
		}
	}

	public function ubah_put() {
		$id = $this->put('id');
		
		if (!$id) {
			$this->response([
				'status' => FALSE,
				'message' => 'ID produk harus dikirim dalam request'
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
	
		$opname = $this->OpnameApi_model->getOpnameDataById($id);
		if (!$opname) {
			$this->response([
				'status' => FALSE,
				'message' => 'Produk tidak ditemukan'
			], RestController::HTTP_NOT_FOUND);
			return;
		}
	
		$data = [
			'nama_barang' => $this->put('nama_barang') ?? $opname['nama_barang'],
			'gambar_barang' => $this->put('gambar_barang') ?? $opname['gambar_barang'],
			'id_kategori_barang' => $this->put('id_kategori_barang') ?? $opname['id_kategori_barang'],
			'id_tipe_barang' => $this->put('id_tipe_barang') ?? $opname['id_tipe_barang'],
			'id_mitra_barang' => $this->put('id_mitra_barang') ?? $opname['id_mitra_barang'],
			'harga_pack' => $this->put('harga_pack') ?? $opname['harga_pack'],
			'jml_pcs_pack' => $this->put('jml_pcs_pack') ?? $opname['jml_pcs_pack'],
			'harga_satuan' => $this->put('harga_satuan') ?? $opname['harga_satuan'],
			'harga_jual' => $this->put('harga_jual') ?? $opname['harga_jual'],
			'stok' => $this->put('stok') ?? $opname['stok'],
			'stok_asli' => $this->put('stok_asli') ?? $opname['stok_asli'],
			'laba' => ($this->put('harga_jual') ?? $opname['harga_jual']) - ($this->put('harga_satuan') ?? $opname['harga_satuan']),
			'catatan' => $this->put('catatan') ?? $opname['catatan'],
			'updated_date' => date('Y-m-d H:i:s'),
			'user_update' => $this->input->post('user_update'),
		];

		if (!$data['stok']) {
			$this->response([
				'status' => FALSE,
				'message' => 'Jumlah Stok Harus Diisi'
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
	
		if ($this->OpnameApi_model->editOpname($data, $id) > 0) {
			$this->response([
				'status' => TRUE,
				'message' => 'Produk berhasil diperbarui',
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message' => 'Gagal mengupdate stok'
			], RestController::HTTP_BAD_REQUEST);
		}
	}
	

	public function index_delete() {
		$id = $this->delete('id');
	
		if (!$id) {
			$this->response([
				'status' => FALSE,
				'message' => 'ID tidak ditemukan'
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
	
		$data = [
			'presence' => 0,
			'updated_date' => date('Y-m-d H:i:s'),
			'user_update' => $this->delete('user_update') ?? 'system'
		];
	
		if ($this->OpnameApi_model->deleteOpname($data, $id)) {
			$this->response([
				'status' => TRUE,
				'message' => 'Opname berhasil Dihapus'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message' => 'Gagal mengubah status Opname'
			], RestController::HTTP_BAD_REQUEST);
		}
	}
}