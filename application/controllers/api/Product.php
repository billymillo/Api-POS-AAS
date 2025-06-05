<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Product extends RestController {

	public function __construct() {
		parent::__construct();
		$this->load->model('ProductApi_model');
		$this->load->library('form_validation');
	}

	public function index_get($id = null) {
		$id = $this->get('id');
		$page = $this->get('page') ?? 1;  
		$limit = $this->get('limit') ?? 100;
		if ($id === null) {
			$offset = ($page - 1) * $limit;
			$product = $this->ProductApi_model->getProduct($id, $offset, $limit);
		} else {
			$product = $this->ProductApi_model->getProduct($id);
		}	
		if ($product) {
			$this->response([
				'status' => TRUE,
				'data' => $product,
				'message' => 'Success'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message' => 'Product does not exist'
			], RestController::HTTP_NOT_FOUND);
		}
	}
	
	public function index_post() {
		$this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required|trim');
		$this->form_validation->set_rules('id_kategori_barang', 'Kategori Barang', 'required|trim|integer');
		$this->form_validation->set_rules('id_tipe_barang', 'Tipe Barang', 'required|trim|integer');
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
			'barcode_barang' => $this->input->post('barcode_barang'),
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
			'user_input' => $this->input->post('user_input') ?? 'system',
		];
	
		if ($this->ProductApi_model->addProduct($data) > 0) {
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
		if ($this->input->get('_method') === 'PUT') {
			return $this->index_put();
		}
	
		$this->response([
			'status' => FALSE,
			'message' => 'Metode tidak valid'
		], RestController::HTTP_BAD_REQUEST);
	}


	public function index_put() {
		$id = $this->input->post('id');
	
		if (!$id) {
			$this->response([
				'status' => FALSE,
				'message' => 'ID produk harus dikirim dalam request'
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
	
		$product = $this->ProductApi_model->getProductDataById($id);
		if (!$product) {
			$this->response([
				'status' => FALSE,
				'message' => 'Produk tidak ditemukan'
			], RestController::HTTP_NOT_FOUND);
			return;
		}
	
		$gambar_barang = $product['gambar_barang'];
	
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
			'nama_barang' => $this->input->post('nama_barang') ?? $product['nama_barang'],
			'barcode_barang' => $this->input->post('barcode_barang') ?? $product['barcode_barang'],
			'gambar_barang' => $gambar_barang ?? $product['gambar_barang'],
			'id_kategori_barang' => $this->input->post('id_kategori_barang') ?? $product['id_kategori_barang'],
			'id_tipe_barang' => $this->input->post('id_tipe_barang') ?? $product['id_tipe_barang'],
			'id_mitra_barang' => $this->input->post('id_mitra_barang') ?? $product['id_mitra_barang'],
			'harga_pack' => $this->input->post('harga_pack') ?? $product['harga_pack'],
			'jml_pcs_pack' => $this->input->post('jml_pcs_pack') ?? $product['jml_pcs_pack'],
			'harga_satuan' => $this->input->post('harga_satuan') ?? $product['harga_satuan'],
			'harga_jual' => $this->input->post('harga_jual') ?? $product['harga_jual'],
			'stok' => $this->input->post('stok') ?? $product['stok'],
			'laba' => ($this->input->post('harga_jual') ?? $product['harga_jual']) - ($this->input->post('harga_satuan') ?? $product['harga_satuan']),
			'user_update' => $this->input->post('user_update') ?? 'system',
			'updated_date' => date('Y-m-d H:i:s'),
		];
	
		if ($this->ProductApi_model->editProduct($data, $id) > 0) {
			$this->response([
				'status' => TRUE,
				'message' => 'Berhasil mengupdate produk'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message' => 'Gagal mengupdate produk'
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
	
		$product = $this->ProductApi_model->getProductDataById($id);
		if (!$product) {
			$this->response([
				'status' => FALSE,
				'message' => 'Produk tidak ditemukan'
			], RestController::HTTP_NOT_FOUND);
			return;
		}
	
		$data = [
			'nama_barang' => $this->put('nama_barang') ?? $product['nama_barang'],
			'barcode_barang' => $this->put('barcode_barang') ?? $product['barcode_barang'],
			'gambar_barang' => $this->put('gambar_barang') ?? $product['gambar_barang'],
			'id_kategori_barang' => $this->put('id_kategori_barang') ?? $product['id_kategori_barang'],
			'id_tipe_barang' => $this->put('id_tipe_barang') ?? $product['id_tipe_barang'],
			'id_mitra_barang' => $this->put('id_mitra_barang') ?? $product['id_mitra_barang'],
			'harga_pack' => $this->put('harga_pack') ?? $product['harga_pack'],
			'jml_pcs_pack' => $this->put('jml_pcs_pack') ?? $product['jml_pcs_pack'],
			'harga_satuan' => $this->put('harga_satuan') ?? $product['harga_satuan'],
			'harga_jual' => $this->put('harga_jual') ?? $product['harga_jual'],
			'stok' => $this->put('stok') ?? $product['stok'],
			'laba' => ($this->put('harga_jual') ?? $product['harga_jual']) - ($this->put('harga_satuan') ?? $product['harga_satuan']),			
			'updated_date' => date('Y-m-d H:i:s'),
			'user_update' => $this->input->post('user_update') ?? "system",
		];

		if (!$data['stok']) {
			$this->response([
				'status' => FALSE,
				'message' => 'Jumlah Stok Harus Diisi'
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
	
		if ($this->ProductApi_model->editProduct($data, $id) > 0) {
			$this->response([
				'status' => TRUE,
				'message' => 'Stok produk berhasil diperbarui',
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message' => 'Gagal mengupdate stok'
			], RestController::HTTP_BAD_REQUEST);
		}
	}

	public function kurang_put() {
		$id = $this->put('id');
		
		if (!$id) {
			$this->response([
				'status' => FALSE,
				'message' => 'ID produk harus dikirim dalam request'
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
	
		$product = $this->ProductApi_model->getProductDataById($id);
		if (!$product) {
			$this->response([
				'status' => FALSE,
				'message' => 'Produk tidak ditemukan'
			], RestController::HTTP_NOT_FOUND);
			return;
		}
	
		$stok_dikurangi = $this->put('stok') ?? 0;
		if ($stok_dikurangi <= 0) {
			$this->response([
				'status' => FALSE,
				'message' => 'Jumlah Stok Harus Diisi'
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
	
		$stok_baru = max(0, $product['stok'] - $stok_dikurangi);
	
		$data = [
			'stok' => $stok_baru ?? $product['stok'],
			'updated_date' => date('Y-m-d H:i:s'),
			'user_update' => $this->input->post('user_update') ?? "system",
		];
	
		if ($this->ProductApi_model->editProduct($data, $id) > 0) {
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

	public function tambah_put() {
		$id = $this->put('id');
		
		if (!$id) {
			$this->response([
				'status' => FALSE,
				'message' => 'ID produk harus dikirim dalam request'
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
	
		$product = $this->ProductApi_model->getProductDataById($id);
		if (!$product) {
			$this->response([
				'status' => FALSE,
				'message' => 'Produk tidak ditemukan'
			], RestController::HTTP_NOT_FOUND);
			return;
		}
	
		$stok_tambah = $this->put('stok') ?? 0;
		if ($stok_tambah <= 0) {
			$this->response([
				'status' => FALSE,
				'message' => 'Jumlah stok yang ditambah harus lebih dari 0'
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
	
		$stok_baru = max(0, $product['stok'] + $stok_tambah);
	
		$data = [
			'stok' => $stok_baru ?? $product['stok'],
			'updated_date' => date('Y-m-d H:i:s'),
			'user_update' => $this->input->post('user_update') ?? "system",
		];
	
		if ($this->ProductApi_model->editProduct($data, $id) > 0) {
			$this->response([
				'status' => TRUE,
				'message' => 'Stok berhasil ditambah',
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
	
		if ($this->ProductApi_model->deleteProduct($data, $id)) {
			$this->response([
				'status' => TRUE,
				'message' => 'Produk berhasil Dihapus'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message' => 'Gagal mengubah status Produk'
			], RestController::HTTP_BAD_REQUEST);
		}
	}
	
	public function tipe_get() {
		$id = $this->get('id');
		
		if($id == null) {
			$tipe = $this->ProductApi_model->getTipe();
		} else {
			$tipe = $this->ProductApi_model->getTipe($id);
		}
		if($tipe) {
			$this->response([
			   'status' => true,
			   'data'   => $tipe,
			   'message'=> 'Success'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => false,
				'message'=> 'Id Tipe Doesnt Exist'
			 ], RestController::HTTP_NOT_FOUND);
		}
	}
	public function tipe_post() {
		$this->form_validation->set_rules('tipe', 'Tipe', 'required');
		if ($this->form_validation->run() == FALSE) {
			$this->response([
				'status' => false,
				'message' => 'Tipe Tidak Boleh Kosong'
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
			$data = [
				'tipe' => $this->input->post('tipe'),
				'user_input' => $this->input->post('user_input') ?? "system"
			];
			if ($this->ProductApi_model->addTipe($data) > 0) {
				$this->response([
					'status' => true,
					'message' => 'Tipe berhasil ditambahkan'
				], RestController::HTTP_CREATED);
			} else {
				$this->response([
					'status' => false,
					'message' => 'Tipe gagal dibuat'
				], RestController::HTTP_NOT_FOUND);
			}
	}

	public function tipe_put() {
		$id = $this->put('id');
					
		if (!$id) {
			$this->response([
				'status' => FALSE,
				'message' => 'ID tidak ditemukan'
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
		$data = [
			'tipe' => $this->put('tipe'),
			'updated_date' =>  date('Y-m-d H:i:s'),
			'user_update' => $this->input->post('user_update') ?? "system",
		];

		if ($data['tipe'] == null) {
			$this->response([
				   'status' => FALSE,
				   'message' => 'Isi Tipe'
			], RestController::HTTP_BAD_REQUEST);
		}

		if ($this->ProductApi_model->editTipe($data, $id) > 0) {
			$this->response([
				   'status' => true,
				   'message'=> 'Berhasil Mengubah Tipe'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				   'status' => FALSE,
				   'message' => 'Tidak ada Perubahan'
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
	}

	public function tipe_delete() {
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
			'user_update' => $this->delete('user_update') ?? 'system' // Bisa dikirim user yg menghapus
		];
	
		if ($this->ProductApi_model->deleteTipe($data, $id)) {
			$this->response([
				'status' => TRUE,
				'message' => 'Tipe berhasil Dihapus'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message' => 'Gagal mengubah status tipe'
			], RestController::HTTP_BAD_REQUEST);
		}
	}
	
	public function mitra_get() {
		$id = $this->get('id');
		if($id == null) {
			$mitra = $this->ProductApi_model->getMitra();
		} else {
			$mitra = $this->ProductApi_model->getMitra($id);
		}
		if($mitra) {
			$this->response([
			   'status' => TRUE,
			   'data'   => $mitra,
			   'message'=> 'Success'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message'=> 'Id Mitra Doesnt Exist'
			 ], RestController::HTTP_NOT_FOUND);
			 return;
		}
	}
	public function mitra_post() {
		$this->form_validation->set_rules('nama', 'Nama', 'required|trim');
		$this->form_validation->set_rules('no_tlp', 'Nomor Telepon', 'required|trim|integer');
		$this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
	
		if ($this->form_validation->run() == FALSE) {
			$this->response([
				'status' => FALSE,
				'message' => $this->form_validation->error_array()
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
	
		$data = [
			'nama' => $this->input->post('nama'),
			'no_tlp' => $this->input->post('no_tlp'),
			'email' => $this->input->post('email') ?? NULL,
			'bank_rek' => $this->input->post('bank_rek') ?? NULL,
			'no_rek' => $this->input->post('no_rek') ?? NULL,
			'nama_rek' => $this->input->post('nama_rek') ?? NULL,
			'user_input' => $this->input->post('user_input') ?? "system"
		];
	
		if ($this->ProductApi_model->addMitra($data) > 0) {
			$this->response([
				'status' => true,
				'message' => 'Mitra berhasil ditambahkan',
			], RestController::HTTP_CREATED);
		} else {
			$this->response([
				'status' => false,
				'message' => 'Mitra gagal ditambahkan',
			], RestController::HTTP_BAD_REQUEST);
		}
	}

	public function mitra_put() {
		$id = $this->put('id');
			
		if (!$id) {
			$this->response([
				'status' => FALSE,
				'message' => 'ID produk tidak ditemukan'
			], RestController::HTTP_BAD_REQUEST);
			return;
		}

		$data = [
			'nama' => $this->put('nama'),
			'no_tlp' => $this->put('no_tlp'),
			'email' => $this->put('email'),
			'bank_rek' => $this->put('bank_rek'),
			'no_rek' => $this->put('no_rek'),
			'nama_rek' => $this->put('nama_rek'),
			'updated_date' => date('Y-m-d H:i:s'),
			'user_update' => $this->input->post('user_update'),
		];
		if ($data['nama'] == null) {
			$this->response([
				   'status' => FALSE,
				   'message' => 'Isi Nama Mitra'
			], RestController::HTTP_BAD_REQUEST);
		}
		if ($data['no_tlp'] == null) {
			$this->response([
				   'status' => FALSE,
				   'message' => 'Isi No Telepon Mitra'
			], RestController::HTTP_BAD_REQUEST);
		}
		if ($data['email'] == null) {
			$this->response([
				   'status' => FALSE,
				   'message' => 'Isi Email Mitra'
			], RestController::HTTP_BAD_REQUEST);
		}
		if ($this->ProductApi_model->editMitra($data, $id) > 0) {
			$this->response([
				   'status' => true,
				   'message'=> 'Berhasil mengubah Mitra'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				   'status' => FALSE,
				   'message' => 'Tidak Terjadi Perubahan'
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
	}

	public function mitra_delete() {
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
	
		if ($this->ProductApi_model->deleteMitra($data, $id)) {
			$this->response([
				'status' => TRUE,
				'message' => 'Mitra berhasil Dihapus'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message' => 'Gagal mengubah status Mitra'
			], RestController::HTTP_BAD_REQUEST);
		}
	}
	
	public function kategori_get() {
		$id = $this->get('id');
		if($id == null) {
			$kategori = $this->ProductApi_model->getKategori();
		} else {
			$kategori = $this->ProductApi_model->getKategori($id);
		}
		if($kategori) {
			$this->response([
			   'status' => TRUE,
			   'data'   => $kategori,
			   'message'=> 'Success'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message'=> 'Id Kategori Doesnt Exist'
			 ], RestController::HTTP_NOT_FOUND);
		}
	}

	public function kategori_post() {
		$this->form_validation->set_rules('kategori', 'Kategori', 'required');
		if ($this->form_validation->run() == FALSE) {
			$this->response([
				'status' => FALSE,
				'message' => 'Kategori Tidak Boleh Kosong'
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
		} else {
			$errors = [];
		}
	
		if (empty($_FILES['gambar_kategori']['name'])) {
			$errors['gambar_kategori'] = 'Gambar Barang harus diunggah.';
		}
	
		if (!empty($errors)) {
			$this->response([
				'status' => FALSE,
				'message' => $errors
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
	
		$config['upload_path'] = './kategori/';
		$config['allowed_types'] = 'jpg|jpeg|png|gif';
		$config['max_size'] = 50000;
		$this->load->library('upload', $config);
	
		if (!$this->upload->do_upload('gambar_kategori')) {
			$errors['gambar_kategori'] = strip_tags($this->upload->display_errors());
			$this->response([
				'status' => FALSE,
				'message' => $errors
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
	
		$data_image = $this->upload->data();
			$data = [
				'kategori' => $this->input->post('kategori'),
				'gambar_kategori' => $data_image['file_name'],
				'user_input' => $this->input->post('user_input') ?? "system"
			];
			if ($this->ProductApi_model->addKategori($data) > 0) {
				$this->response([
					'status' => 'true',
					'message' => 'Kategori berhasil ditambahkan'
				], RestController::HTTP_CREATED);
			} else {
				$this->response([
					'status' => FALSE,
					'message' => 'Kategori gagal dibuat'
				], RestController::HTTP_NOT_FOUND);
			}
	}

	public function kategori_edit_post() {
		if ($this->input->get('_method') === 'PUT') {
			return $this->kategori_put();
		}
	
		$this->response([
			'status' => FALSE,
			'message' => 'Metode tidak valid'
		], RestController::HTTP_BAD_REQUEST);
	}

	public function kategori_put() {
		$id = $this->input->post('id');
		if (!$id) {
			$this->response([
				'status' => FALSE,
				'message' => 'ID Kategori harus dikirim dalam request'
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
	
		$kategori = $this->ProductApi_model->getKategoriDataById($id);
		if (!$kategori) {
			$this->response([
				'status' => FALSE,
				'message' => 'Kategori tidak ditemukan'
			], RestController::HTTP_NOT_FOUND);
			return;
		}

		$gambar_kategori = $kategori['gambar_kategori'];

		if (!empty($_FILES['gambar_kategori']['name'])) {
			$config['upload_path']   = './kategori/';
			$config['allowed_types'] = 'jpg|jpeg|png|gif';
			$config['max_size']      = 50000;
			$config['encrypt_name']  = TRUE;
	
			$this->load->library('upload', $config);
	
			if ($this->upload->do_upload('gambar_kategori')) {
				$uploadData = $this->upload->data();
				$gambar_kategori = date('YmdHis') . $uploadData['file_ext'];
				rename($uploadData['full_path'], $uploadData['file_path'] . $gambar_kategori);
			} else {
				$this->response([
					'status' => FALSE,
					'message' => 'Gagal mengupload gambar: ' . $this->upload->display_errors()
				], RestController::HTTP_BAD_REQUEST);
				return;
			}
		}

		$data = [
			'kategori' => $this->input->post('kategori') ?? $kategori['kategori'],
			'gambar_kategori' => $gambar_kategori ?? $kategori['gambar_kategori'],
			'updated_date' => date('Y-m-d H:i:s'),
			'user_update' => $this->input->post('user_update') ?? "system",
		];

		if ($data['kategori'] == null) {
			$this->response([
				   'status' => FALSE,
				   'message' => 'Isi Kategori'
			], RestController::HTTP_BAD_REQUEST);
		}
		// Proses update data
		if ($this->ProductApi_model->editKategori($data, $id) > 0) {
			$this->response([
				'status' => TRUE,
				'message' => 'Berhasil Mengubah Kategori'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message' => 'Gagal Mengubah Kategori'
			], RestController::HTTP_BAD_REQUEST);
		}
	}

	public function kategori_delete() {
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
	
		if ($this->ProductApi_model->deleteKategori($data, $id)) {
			$this->response([
				'status' => TRUE,
				'message' => 'Kategori berhasil Dihapus'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message' => 'Gagal mengubah status Kategori'
			], RestController::HTTP_BAD_REQUEST);
		}
	}
}
