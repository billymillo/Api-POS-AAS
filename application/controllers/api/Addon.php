<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Addon extends RestController {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('AddonApi_model');
		$this->load->library('form_validation');
	}

	public function index_get() {
		$id = $this->get('id');
		
		if($id == null) {
			$addOn = $this->AddonApi_model->getAddOn();
		} else {
			$addOn = $this->AddonApi_model->getAddOn($id);
		}
		if($addOn) {
			$this->response([
			   'status' => true,
			   'data'   => $addOn,
			   'message'=> 'Success'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => false,
				'message'=> 'Id Add On Doesnt Exist'
			 ], RestController::HTTP_NOT_FOUND);
		}
	}
	public function index_post() {
		$this->form_validation->set_rules('add_on', 'Add On', 'required|trim');
		$this->form_validation->set_rules('harga', 'Harga', 'required|numeric');

		if ($this->form_validation->run() == FALSE) {
			$this->response([
				'status' => false,
				'message' => 'Lengkapi data Add On'
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
			$data = [
				'add_on' => $this->input->post('add_on'),
				'harga' => $this->input->post('harga'),
				'user_input' => $this->input->post('user_input') ?? 'system',
			];
			if ($this->AddonApi_model->addAddOn($data) > 0) {
				$this->response([
					'status' => true,
					'message' => 'Add On berhasil ditambahkan'
				], RestController::HTTP_CREATED);
			} else {
				$this->response([
					'status' => false,
					'message' => 'Add On gagal dibuat'
				], RestController::HTTP_NOT_FOUND);
			}
	}

	public function index_put() {
		$id = $this->put('id');
					
		if (!$id) {
			$this->response([
				'status' => FALSE,
				'message' => 'ID tidak ditemukan'
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
		$data = [
			'add_on' => $this->put('add_on'),
			'harga' => $this->put('harga'),
			'updated_date' =>  date('Y-m-d H:i:s'),
			'user_update' => $this->input->post('user_update') ?? 'system',
		];

		if ($data['add_on'] == null) {
			$this->response([
				   'status' => FALSE,
				   'message' => 'Isi Add On'
			], RestController::HTTP_BAD_REQUEST);
		} else if ($data['harga'] == null) {
			$this->response([
				'status' => FALSE,
				'message' => 'Isi Harga'
		 	], RestController::HTTP_BAD_REQUEST);
		}

		if ($this->AddonApi_model->editAddOn($data, $id) > 0) {
			$this->response([
				   'status' => true,
				   'message'=> 'Berhasil Mengubah Add On'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				   'status' => FALSE,
				   'message' => 'Tidak ada Perubahan'
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
	
		if ($this->AddonApi_model->deleteAddOn($data, $id)) {
			$this->response([
				'status' => TRUE,
				'message' => 'Add On berhasil Dihapus'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message' => 'Gagal mengubah status add on'
			], RestController::HTTP_BAD_REQUEST);
		}
	}

	public function produk_get() {
		$id = $this->get('id');
		
		if($id == null) {
			$addOn = $this->AddonApi_model->getAddOnPr();
		} else {
			$addOn = $this->AddonApi_model->getAddOnPr($id);
		}
		if($addOn) {
			$this->response([
			   'status' => true,
			   'data'   => $addOn,
			   'message'=> 'Success'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => false,
				'message'=> 'Id Add On Doesnt Exist'
			 ], RestController::HTTP_NOT_FOUND);
		}
	}

	public function produk_post() {
    	$this->form_validation->set_rules('id_add_on', 'Id Add On', 'required|trim');

    	if ($this->form_validation->run() == FALSE) {
    	    $this->response([
    	        'status' => false,
    	        'message' => 'Lengkapi data Add On'
    	    ], RestController::HTTP_BAD_REQUEST);
    	    return;
    	}

    	$last_produk = $this->AddonApi_model->getLastProdukId();
    	$id_produk = $last_produk ? $last_produk['id'] : 1;

    	if (!$id_produk) {
    	    $this->response([
    	        'status' => false,
    	        'message' => 'Produk belum tersedia di database'
    	    ], RestController::HTTP_NOT_FOUND);
    	    return;
    	}

    	$data = [
    	    'id_add_on' => $this->input->post('id_add_on'),
    	    'id_produk' => $this->input->post('id_produk') ?? $id_produk,
    	    'user_input' => $this->input->post('user_input') ?? 'system',
    	];

    	if ($this->AddonApi_model->addAddOnPr($data) > 0) {
    	    $this->response([
    	        'status' => true,
    	        'message' => 'Produk Add On berhasil ditambahkan'
    	    ], RestController::HTTP_CREATED);
    	} else {
    	    $this->response([
    	        'status' => false,
    	        'message' => 'Produk Add On gagal dibuat'
    	    ], RestController::HTTP_BAD_REQUEST);
    	}
	}


	public function produk_put() {
		$id = $this->put('id');
					
		if (!$id) {
			$this->response([
				'status' => FALSE,
				'message' => 'ID tidak ditemukan'
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
		$data = [
			'id_add_on' => $this->put('id_add_on'),
			'id_produk' => $this->put('id_produk'),
			'updated_date' =>  date('Y-m-d H:i:s'),
			'user_update' => $this->input->post('user_update') ?? 'system',
		];

		if ($data['id_add_on'] == null) {
			$this->response([
				   'status' => FALSE,
				   'message' => 'Isi Id Add On'
			], RestController::HTTP_BAD_REQUEST);
		} else if ($data['id_produk'] == null) {
			$this->response([
				'status' => FALSE,
				'message' => 'Isi Id Produk'
		 	], RestController::HTTP_BAD_REQUEST);
		}

		if ($this->AddonApi_model->editAddOnPr($data, $id) > 0) {
			$this->response([
				   'status' => true,
				   'message'=> 'Berhasil Mengubah Add On Produk'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				   'status' => FALSE,
				   'message' => 'Tidak ada Perubahan'
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
	}

	public function produk_delete() {
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
	
		if ($this->AddonApi_model->deleteAddOnPr($data, $id)) {
			$this->response([
				'status' => TRUE,
				'message' => 'Produk Add On berhasil Dihapus'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message' => 'Gagal mengubah status Produk Add On'
			], RestController::HTTP_BAD_REQUEST);
		}
	}

	public function transaksi_get() {
		$id = $this->get('id');
		
		if($id == null) {
			$addOn = $this->AddonApi_model->getAddOnTr();
		} else {
			$addOn = $this->AddonApi_model->getAddOnTr($id);
		}
		if($addOn) {
			$this->response([
			   'status' => true,
			   'data'   => $addOn,
			   'message'=> 'Success'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => false,
				'message'=> 'Id Add On Doesnt Exist'
			 ], RestController::HTTP_NOT_FOUND);
		}
	}
	public function transaksi_post() {
		$this->form_validation->set_rules('id_add_on', 'Id Add On', 'required|trim');
		$this->form_validation->set_rules('id_det_transaksi_out', 'Id Detail Transaksi Add On', 'required|trim');

		if ($this->form_validation->run() == FALSE) {
			$this->response([
				'status' => false,
				'message' => 'Lengkapi data Add On'
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
		$last_id = $this->AddonApi_model->getLastTransaksiOutId();
    	$id_transaksi = $last_id ? $last_id['id'] : 1;

		$data = [
			'id_add_on' => $this->input->post('id_add_on'),
			'id_transaksi_out' => $id_transaksi,
			'id_det_transaksi_out' => $this->input->post('id_det_transaksi_out'),
			'user_input' => $this->input->post('user_input') ?? 'system',
		];
		if ($this->AddonApi_model->addAddOnTr($data) > 0) {
			$this->response([
				'status' => true,
				'message' => 'Transaksi Add On berhasil ditambahkan'
			], RestController::HTTP_CREATED);
		} else {
			$this->response([
				'status' => false,
				'message' => 'Transaksi Add On gagal dibuat'
			], RestController::HTTP_NOT_FOUND);
		}
	}

	public function transaksi_put() {
		$id = $this->put('id');
					
		if (!$id) {
			$this->response([
				'status' => FALSE,
				'message' => 'ID tidak ditemukan'
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
		$data = [
			'id_add_on' => $this->put('id_add_on'),
			'id_transaksi_out' => $this->put('id_transaksi_out'),
			'id_det_transaksi_out' => $this->put('id_det_transaksi_out'),
			'updated_date' =>  date('Y-m-d H:i:s'),
			'user_update' => $this->input->post('user_update') ?? 'system',
		];

		if ($data['id_add_on'] == null) {
			$this->response([
				   'status' => FALSE,
				   'message' => 'Isi Id Add On'
			], RestController::HTTP_BAD_REQUEST);
		} else if ($data['id_transaksi_out'] == null) {
			$this->response([
				'status' => FALSE,
				'message' => 'Isi Id Transaksi'
		 	], RestController::HTTP_BAD_REQUEST);
		} else if ($data['id_det_transaksi_out'] == null) {
			$this->response([
				'status' => FALSE,
				'message' => 'Isi Id Detail Transaksi'
			], RestController::HTTP_BAD_REQUEST);
		}

		if ($this->AddonApi_model->editAddOnTr($data, $id) > 0) {
			$this->response([
				   'status' => true,
				   'message'=> 'Berhasil Mengubah Add On Transaksi'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				   'status' => FALSE,
				   'message' => 'Tidak ada Perubahan'
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
	}

	public function transaksi_delete() {
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
	
		if ($this->AddonApi_model->deleteAddOnTr($data, $id)) {
			$this->response([
				'status' => TRUE,
				'message' => 'Transaksi Add On berhasil Dihapus'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message' => 'Gagal mengubah status Transaksi Add On'
			], RestController::HTTP_BAD_REQUEST);
		}
	}
}