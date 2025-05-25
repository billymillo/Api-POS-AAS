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
		
		if($id == null) {
			$opname = $this->OpnameApi_model->getOpname();
		} else {
			$opname = $this->OpnameApi_model->getOpname($id);
		}
		if($opname) {
			$this->response([
			   'status' => true,
			   'data'   => $opname,
			   'message'=> 'Success'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => false,
				'message'=> 'Opname Doesnt Exist'
			 ], RestController::HTTP_NOT_FOUND);
		}
	}
	
	public function index_post() {
		$this->form_validation->set_rules('status_opname', 'Status Opname', 'required|trim');
		$this->form_validation->set_rules('tipe_opname', 'Tipe Opname', 'required|trim');
		
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
		} else {
			$errors = [];
		}
		if (!empty($errors)) {
			$this->response([
				'status' => FALSE,
				'message' => $errors
			], RestController::HTTP_BAD_REQUEST);
			return;
		}

		$last_transaksi = $this->OpnameApi_model->getNoOpName();
		if ($last_transaksi) {
			preg_match('/OP-NAME(\d+)\d{6}$/', $last_transaksi['no_opname'], $matches);
			$i = isset($matches[1]) ? (int)$matches[1] + 1 : 1; 
		} else {
			$i = 1;
		}

		$no_opname = 'OP-NAME' . str_pad($i, 3, '0', STR_PAD_LEFT) . date('dmy');

		$data = [
			'tanggal' => date('Y-m-d H:i:s'),
			'no_opname' => $no_opname,
			'status_opname' => $this->input->post('status_opname'),
			'tipe_opname' => $this->input->post('tipe_opname'),
			'user_input' => $this->input->post('user_input'),
		];
	
		if ($this->OpnameApi_model->addOpname($data) > 0) {
			$this->response([
				'status' => true,
				'message' => 'Opname berhasil ditambahkan',
			], RestController::HTTP_CREATED);
		} else {
			$this->response([
				'status' => false,
				'message' => 'Opname gagal ditambahkan',
			], RestController::HTTP_BAD_REQUEST);
		}
	}

	public function index_put() {
		$id = $this->put('id');
			
		if (!$id) {
			$this->response([
				'status' => FALSE,
				'message' => 'ID Opname tidak ditemukan'
			], RestController::HTTP_BAD_REQUEST);
			return;
		}

		$data = [
			'status_opname' => $this->put('status_opname'),
			'user_update' => $this->put('user_update') ?? 'system',
		];

		if ($this->OpnameApi_model->editOpname($data, $id) > 0) {
			$this->response([
				   'status' => true,
				   'message'=> 'Berhasil mengubah Status Opname'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				   'status' => false,
				   'message' => 'Tidak Terjadi Perubahan'
			], RestController::HTTP_BAD_REQUEST);
			return;
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

	public function detail_get($id = null) {
		$id = $this->get('id');
		if($id == null) {
			$opnameDetail = $this->OpnameApi_model->getOpnameDet();
		} else {
			$opnameDetail = $this->OpnameApi_model->getOpnameDet($id);
		}
		if($opnameDetail) {
			$this->response([
			   'status' => TRUE,
			   'data'   => $opnameDetail,
			   'message'=> 'Success'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message'=> 'Detail Doesnt Exist'
			 ], RestController::HTTP_NOT_FOUND);
		}
	}

	public function detail_post() {
		$this->form_validation->set_rules('id_produk', 'Id Produk', 'required|trim');
		$this->form_validation->set_rules('id_opname', 'Id Opname', 'required|trim');
		$this->form_validation->set_rules('stok', 'Stok', 'required|trim');
		$this->form_validation->set_rules('stok_asli', 'Stok Asli', 'required|trim');
		$this->form_validation->set_rules('harga_satuan', 'Harga Satuan', 'required|trim');
		$this->form_validation->set_rules('harga_jual', 'Harga Jual', 'required|trim');
		
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
		} else {
			$errors = [];
		}
		if (!empty($errors)) {
			$this->response([
				'status' => FALSE,
				'message' => $errors
			], RestController::HTTP_BAD_REQUEST);
			return;
		}

		$data = [
			'id_produk' => $this->input->post('id_produk'),
			'id_opname' => $this->input->post('id_opname'),
			'stok' => $this->input->post('stok'),
			'stok_asli' => $this->input->post('stok_asli'),
			'harga_satuan' => $this->input->post('harga_satuan'),
			'harga_jual' => $this->input->post('harga_jual'),
			'catatan' => $this->input->post('catatan'),
			'user_input' => 'system',
		];
	
		if ($this->OpnameApi_model->addOpnameDet($data) > 0) {
			$this->response([
				'status' => true,
				'message' => 'Detail Opname berhasil ditambahkan',
			], RestController::HTTP_CREATED);
		} else {
			$this->response([
				'status' => false,
				'message' => 'Detail Opname gagal ditambahkan',
			], RestController::HTTP_BAD_REQUEST);
		}
	}

	public function detail_put() {
		$id = $this->put('id');
			
		if (!$id) {
			$this->response([
				'status' => FALSE,
				'message' => 'ID Detail tidak ditemukan'
			], RestController::HTTP_BAD_REQUEST);
			return;
		}

		$member = $this->OpnameApi_model->getOpnameDataById($id);
		if (!$member) {
			$this->response([
				'status' => FALSE,
				'message' => 'Detail tidak ditemukan'
			], RestController::HTTP_NOT_FOUND);
			return;
		}

		$data = [
			'id_produk' => $this->put('id_produk') ?? $member['id_produk'],
			'id_opname' => $this->put('id_opname') ?? $member['id_opname'],
			'stok' => $this->put('stok') ?? $member['stok'],
			'stok_asli' => $this->put('stok_asli') ?? $member['stok_asli'],
			'harga_satuan' => $this->put('harga_satuan') ?? $member['harga_satuan'],
			'harga_jual' => $this->put('harga_jual') ?? $member['harga_jual'],
			'catatan' => $this->put('catatan') ?? $member['catatan'],
			'user_update' => $this->put('user_update') ?? 'system',
			'updated_date' => date('Y-m-d H:i:s'),
		];

		if ($this->OpnameApi_model->editOpnameDet($data, $id) > 0) {
			$this->response([
				   'status' => true,
				   'message'=> 'Berhasil mengubah isi Detail Opname'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				   'status' => false,
				   'message' => 'Tidak Terjadi Perubahan'
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
	}

	public function pembelian_get($id = null) {
		$id = $this->get('id');
		if($id == null) {
			$opnamePem = $this->OpnameApi_model->getOpnamePem();
		} else {
			$opnamePem = $this->OpnameApi_model->getOpnamePem($id);
		}
		if($opnamePem) {
			$this->response([
			   'status' => TRUE,
			   'data'   => $opnamePem,
			   'message'=> 'Success'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message'=> 'Pembelian Doesnt Exist'
			 ], RestController::HTTP_NOT_FOUND);
		}
	}

	public function pembelian_post() {
		$this->form_validation->set_rules('id_opname', 'Id Opname', 'required|trim');
		$this->form_validation->set_rules('id_produk', 'Id Opname Detail', 'required|trim');
		$this->form_validation->set_rules('jumlah', 'Jumlah', 'required|trim');
		$this->form_validation->set_rules('total_beli', 'Total Beli', 'required|trim');
		
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
		} else {
			$errors = [];
		}
		if (!empty($errors)) {
			$this->response([
				'status' => FALSE,
				'message' => $errors
			], RestController::HTTP_BAD_REQUEST);
			return;
		}

		$data = [
			'id_opname' => $this->input->post('id_opname'),
			'id_produk' => $this->input->post('id_produk'),
			'jumlah' => $this->input->post('jumlah'),
			'total_beli' => $this->input->post('total_beli'),
			'user_input' => 'system',
		];
	
		if ($this->OpnameApi_model->addOpnamePem($data) > 0) {
			$this->response([
				'status' => true,
				'message' => 'Pembelian Opname berhasil ditambahkan',
			], RestController::HTTP_CREATED);
		} else {
			$this->response([
				'status' => false,
				'message' => 'Pembelian Opname gagal ditambahkan',
			], RestController::HTTP_BAD_REQUEST);
		}
	}
}