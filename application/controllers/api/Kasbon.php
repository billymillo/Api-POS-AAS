<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Kasbon extends RestController {

	public function __construct() {
		parent::__construct();
		$this->load->model('KasbonApi_model');
		$this->load->library('form_validation');
	}

	public function index_get($id = null) {
		$id = $this->get('id');
		if($id == null) {
			$kasbon = $this->KasbonApi_model->getKasbon();
		} else {
			$kasbon = $this->KasbonApi_model->getKasbon($id);
		}
		if($kasbon) {
			$this->response([
			   'status' => TRUE,
			   'data'   => $kasbon,
			   'message'=> 'Success'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message'=> 'Id kasbon Doesnt Exist'
			 ], RestController::HTTP_NOT_FOUND);
		}
	}

	public function index_post() {
		$this->form_validation->set_rules('id_member', 'Pegawai Member', 'required|trim|integer');	
		$this->form_validation->set_rules('total_kasbon', 'Total Kasbon', 'required|trim');
		$this->form_validation->set_rules('id_status', 'Opsi Status', 'required|trim|integer');	

		if ($this->form_validation->run() == FALSE) {
			$this->response([
				'status' => FALSE,
				'message' => $this->form_validation->error_array()
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
		
		$tgl_pelunasan = date('Y-m-d', strtotime('+1 month'));
	
		$data = [
			'id_member' => $this->input->post('id_member'),
			'total_kasbon' => $this->input->post('total_kasbon'),
			'tgl_pelunasan' => $tgl_pelunasan,
			'id_status' => $this->input->post('id_status'),
		];
	
		if ($this->KasbonApi_model->addKasbon($data) > 0) {
			$this->response([
				'status' => true,
				'message' => 'Kasbon berhasil ditambahkan',
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => false,
				'message' => 'Kasbon gagal ditambahkan',
			], RestController::HTTP_BAD_REQUEST);
		}

	}

	public function index_put() {
		$id = $this->put('id');
		if (!$id) {
			$this->response([
				'status' => FALSE,
				'message' => 'ID Kasbon harus dikirim dalam request'
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
	
		$kasbon = $this->KasbonApi_model->getKasbonDataById($id);
		if (!$kasbon) {
			$this->response([
				'status' => FALSE,
				'message' => 'Kasbon tidak ditemukan'
			], RestController::HTTP_NOT_FOUND);
			return;
		}
		
		$data = [
			'id_member' => $this->put('id_member') ?? $kasbon['id_member'],
			'total_kasbon' => $this->put('total_kasbon') ?? $kasbon['total_kasbon'],
			'tgl_pelunasan' => $this->put('tgl_pelunasan') ?? $kasbon['tgl_pelunasan'],
			'id_status' => $this->put('id_status') ?? $kasbon['id_status'],
			'presence' => $this->put('presence') ?? $kasbon['presence'],
			'updated_date' => date('Y-m-d H:i:s'),
		];
	
		if ($this->KasbonApi_model->editKasbon($data, $id) > 0) {
			$this->response([
				'status' => TRUE,
				'message' => 'Berhasil Mengubah Kasbon'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message' => 'Gagal Mengubah Kasbon'
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
			'id_status' => 1,
			'total_kasbon' => 0,
			'presence' => 0,
			'updated_date' => date('Y-m-d H:i:s'),
			'user_update' => $this->delete('user_update') ?? 'system'
		];
	
		if ($this->KasbonApi_model->deleteKasbon($data, $id)) {
			$this->response([
				'status' => TRUE,
				'message' => 'Kasbon berhasil Dihapus'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message' => 'Gagal mengubah status Kasbon'
			], RestController::HTTP_BAD_REQUEST);
		}
	}

	public function detail_get($id = null) {
		$id = $this->get('id');
		$kasbon = $this->get('kasbon');
		if($id == null) {
			$kasbonDetail = $this->KasbonApi_model->getKasbonDetail(null, $kasbon);
		} else {
			$kasbonDetail = $this->KasbonApi_model->getKasbonDetail($id, $kasbon);
		}
		if($kasbonDetail) {
			$this->response([
			   'status' => TRUE,
			   'data'   => $kasbonDetail,
			   'message'=> 'Success'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message'=> 'Id Detail Doesnt Exist'
			 ], RestController::HTTP_NOT_FOUND);
		}
	}
	public function detail_post() {
		$this->form_validation->set_rules('id_transaksi_out', 'Detail Transaksi', 'required|trim|integer');	
		if ($this->form_validation->run() == FALSE) {
			$this->response([
				'status' => FALSE,
				'message' => $this->form_validation->error_array()
			], RestController::HTTP_BAD_REQUEST);
			return;
		}

		$last_kasbon = $this->KasbonApi_model->getLastKasbonId();
		$id_kasbon = $last_kasbon ? $last_kasbon['id'] : 1;
	
		$data = [
			'id_kasbon' => $id_kasbon,
			'id_transaksi_out' => $this->input->post('id_transaksi_out'),
		];
	
		if ($this->KasbonApi_model->addKasbonDetail($data) > 0) {
			$this->response([
				'status' => 'true',
				'message' => 'Kasbon Detail berhasil ditambahkan',
			], RestController::HTTP_CREATED);
		} else {
			$this->response([
				'status' => 'false',
				'message' => 'Kasbon Detail gagal ditambahkan',
			], RestController::HTTP_BAD_REQUEST);
		}

	}

	public function pembayaran_get($id = null) {
		$id = $this->get('id');
		if($id == null) {
			$kasbonPemba = $this->KasbonApi_model->getKasbonPemba();
		} else {
			$kasbonPemba = $this->KasbonApi_model->getKasbonPemba($id);
		}
		if($kasbonPemba) {
			$this->response([
			   'status' => TRUE,
			   'data'   => $kasbonPemba,
			   'message'=> 'Success'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message'=> 'Id kasbon pembayaran Doesnt Exist'
			 ], RestController::HTTP_NOT_FOUND);
		}
	}

	public function pembayaran_post() {
		$this->form_validation->set_rules('id_kasbon', 'Informasi Kasbon', 'required|trim|integer');		
		$this->form_validation->set_rules('total_bayar', 'Total Bayar Kasbon', 'required|trim|integer');	

		if ($this->form_validation->run() == FALSE) {
			$this->response([
				'status' => FALSE,
				'message' => $this->form_validation->error_array()
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
	
		$data = [
			'id_kasbon' => $this->input->post('id_kasbon'),
			'tgl_bayar' => date('Y-m-d'),
			'total_bayar' => $this->input->post('total_bayar'),
		];
	
		if ($this->KasbonApi_model->addKasbonPemba($data) > 0) {
			$this->response([
				'status' => 'true',
				'message' => 'Pembayaran Kasbon berhasil',
			], RestController::HTTP_CREATED);
		} else {
			$this->response([
				'status' => 'false',
				'message' => 'Pembayaran Kasbon gagal ditambahkan',
			], RestController::HTTP_BAD_REQUEST);
		}

	}

}
