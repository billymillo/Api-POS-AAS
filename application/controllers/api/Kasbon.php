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

	public function detail_get($id = null) {
		$id = $this->get('id');
		if($id == null) {
			$kasbonDetail = $this->KasbonApi_model->getKasbonDetail();
		} else {
			$kasbonDetail = $this->KasbonApi_model->getKasbonDetail($id);
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
				'message'=> 'Id kasbon detail Doesnt Exist'
			 ], RestController::HTTP_NOT_FOUND);
		}
	}
	public function detail_post() {
		$this->form_validation->set_rules('id_kasbon', 'Detail Kasbon', 'required|trim|integer');		
		$this->form_validation->set_rules('id_transaksi_out', 'Detail Transaksi', 'required|trim|integer');	

		if ($this->form_validation->run() == FALSE) {
			$this->response([
				'status' => FALSE,
				'message' => $this->form_validation->error_array()
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
	
		$data = [
			'id_kasbon' => $this->input->post('id_kasbon'),
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
		$this->form_validation->set_rules('tgl_bayar', 'Tanggal Bayar Kasbon', 'required|trim|date');	
		$this->form_validation->set_rules('total_bayar', 'Total Bayar Kasbon', 'required|trim|integer');	
		$this->form_validation->set_rules('id_stat_ver', 'Status Verifikasi Kasbon', 'required|trim|integer');	

		if ($this->form_validation->run() == FALSE) {
			$this->response([
				'status' => FALSE,
				'message' => $this->form_validation->error_array()
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
	
		$data = [
			'id_kasbon' => $this->input->post('id_kasbon'),
			'tgl_bayar' => $this->input->post('tgl_bayar'),
			'total_bayar' => $this->input->post('total_bayar'),
			'id_stat_ver' => $this->input->post('id_stat_ver'),
		];
	
		if ($this->KasbonApi_model->addKasbonPemba($data) > 0) {
			$this->response([
				'status' => 'true',
				'message' => 'Pembayaran Kasbon berhasil ditambahkan',
			], RestController::HTTP_CREATED);
		} else {
			$this->response([
				'status' => 'false',
				'message' => 'Pembayaran Kasbon gagal ditambahkan',
			], RestController::HTTP_BAD_REQUEST);
		}

	}

}
