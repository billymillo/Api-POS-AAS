<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Transaksi_Out_Mitra extends RestController {

	public function __construct() {
		parent::__construct();
		$this->load->model('TransaksiOutMitraApi_model');
		$this->load->library('form_validation');
	}

	public function index_get() {
		$id = $this->get('id');
		if($id == null) {
			$mitra = $this->TransaksiOutMitraApi_model->getTransaksiOutMitra();
		} else {
			$mitra = $this->TransaksiOutMitraApi_model->getTransaksiOutMitra($id);
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
				'message'=> 'Id Transaksi Mitra Doesnt Exist'
			 ], RestController::HTTP_NOT_FOUND);
		}
	}
	public function index_post() {
		$this->form_validation->set_rules('id_mitra', 'Mitra ', 'required|integer');
		$this->form_validation->set_rules('total_jumlah', 'Jumlah Produk', 'required|trim|integer');
		$this->form_validation->set_rules('total_transaksi', 'Total Transaksi', 'required|trim|integer');
		$this->form_validation->set_rules('status_transaksi', 'Status Transaksi', 'required|trim');
		$this->form_validation->set_rules('tanggal_awal', 'Tanggal Awal', 'required|trim');
		$this->form_validation->set_rules('tanggal_akhir', 'Tanggal Akhir', 'required|trim');
	
		if ($this->form_validation->run() == FALSE) {
			$this->response([
				'status' => FALSE,
				'message' => $this->form_validation->error_array()
			], RestController::HTTP_BAD_REQUEST);
			return;
		}

        $last_transaksi = $this->TransaksiOutMitraApi_model->getNoTransaksiOutMitra();
		if ($last_transaksi) {
			preg_match('/MTR-OUT(\d+)\d{6}$/', $last_transaksi['no_transaksi_out_mitra'], $matches);
			$i = isset($matches[1]) ? (int)$matches[1] + 1 : 1;
		} else {
			$i = 1;
		}

		$no_transaksi_out_mitra = 'MTR-OUT' . str_pad($i, 3, '0', STR_PAD_LEFT) . date('dmy');
	
		$data = [
			'id_mitra' => $this->input->post('id_mitra'),
			'no_transaksi_out_mitra' => $no_transaksi_out_mitra,
			'total_jumlah' => $this->input->post('total_jumlah') ?? '',
			'total_transaksi' => $this->input->post('total_transaksi') ?? '',
			'status_transaksi' => $this->input->post('status_transaksi') ?? '',
			'tanggal_awal' => $this->input->post('tanggal_awal') ?? '',
			'tanggal_akhir' => $this->input->post('tanggal_akhir') ?? '',
			'user_input' => $this->input->post('user_input') ?? 'system',
		];	
	
		if ($this->TransaksiOutMitraApi_model->addTransaksiOutMitra($data) > 0) {
			$this->response([
				'status' => 'true',
				'message' => 'Transaksi Mitra Telah berhasil ditambahkan',
			], RestController::HTTP_CREATED);
		} else {
			$this->response([
				'status' => 'false',
				'message' => 'Transaksi Mitra gagal ditambahkan',
			], RestController::HTTP_BAD_REQUEST);
		}
	}
}
