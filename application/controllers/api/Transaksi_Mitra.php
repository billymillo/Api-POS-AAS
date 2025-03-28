<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Transaksi_Mitra extends RestController {

	public function __construct() {
		parent::__construct();
		$this->load->model('TransaksiMitraApi_model');
		$this->load->library('form_validation');
	}

	public function index_get() {
		$id = $this->get('id');
		if($id == null) {
			$mitra = $this->TransaksiMitraApi_model->getTransaksiMitra();
		} else {
			$mitra = $this->TransaksiMitraApi_model->getTransaksiMitra($id);
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
		$this->form_validation->set_rules('jumlah_produk', 'Jumlah Produk', 'required|trim|integer');
		$this->form_validation->set_rules('total_transaksi', 'Total Transaksi', 'required|trim|integer');
		$this->form_validation->set_rules('status_transaksi', 'Status Transaksi', 'required|trim');
	
		if ($this->form_validation->run() == FALSE) {
			$this->response([
				'status' => FALSE,
				'message' => $this->form_validation->error_array()
			], RestController::HTTP_BAD_REQUEST);
			return;
		}

        $last_transaksi = $this->TransaksiMitraApi_model->getNoTransaksiInMitra();
		if ($last_transaksi) {
			preg_match('/MTR(\d+)\d{6}$/', $last_transaksi['no_transaksi_in'], $matches);
			$i = isset($matches[1]) ? (int)$matches[1] + 1 : 1;
		} else {
			$i = 1;
		}

		$no_transaksi_in = 'MTR' . str_pad($i, 3, '0', STR_PAD_LEFT) . date('dmy');
	
		$data = [
			'id_mitra' => $this->input->post('id_mitra'),
			'no_transaksi_in' => $no_transaksi_in,
			'jumlah_produk' => $this->input->post('jumlah_produk') ?? '',
			'total_transaksi' => $this->input->post('total_transaksi') ?? '',
			'status_transaksi' => $this->input->post('status_transaksi') ?? '',
			'user_input' => $this->input->post('user_input'),
		];
	
		if ($this->TransaksiMitraApi_model->addTransaksiMitra($data) > 0) {
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

	public function detail_get($id = null) {
		$id = $this->get('id');
		if($id == null) {
			$transaksiMitra = $this->TransaksiMitraApi_model->getDetail();
		} else {
			$transaksiMitra = $this->TransaksiMitraApi_model->getDetail($id);
		}
		if($transaksiMitra) {
			$this->response([
			   'status' => TRUE,
			   'data'   => $transaksiMitra,
			   'message'=> 'Success'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message'=> 'Id status Doesnt Exist'
			 ], RestController::HTTP_NOT_FOUND);
		}
	}

	public function detail_post() {
		$this->form_validation->set_rules('id_produk', 'Produk Info', 'required|trim|integer');
		$this->form_validation->set_rules('jumlah', 'Jumlah Transaksi', 'required|trim|integer');
		$this->form_validation->set_rules('harga_satuan', 'Harga Satuan', 'required|trim|integer');
		$this->form_validation->set_rules('harga_jual', 'Harga Jual', 'required|trim|integer');

		if ($this->form_validation->run() == FALSE) {
			$this->response([
				'status' => FALSE,
				'message' => $this->form_validation->error_array()
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
		$last_transaksi = $this->TransaksiMitraApi_model->getLastTransaksiMitraId();
		$id_transaksi_mitra = $last_transaksi ? $last_transaksi['id'] : 1;

		$harga_satuan = $this->input->post('harga_satuan');
		$jumlah = $this->input->post('jumlah');
		$total = $harga_satuan * $jumlah;

		$data = [
			'id_transaksi_in_mitra' => $id_transaksi_mitra,
			'id_produk' => $this->input->post('id_produk'),
			'jumlah' => $jumlah,
			'harga_satuan' => $harga_satuan,
			'harga_jual' => $this->input->post('harga_jual'),
			'total_harga' => $total,
			'user_input' => $this->input->post('user_input'),
		];
		if ($this->TransaksiMitraApi_model->addDetail($data) > 0) {
			$this->response([
				'status' => 'true',
				'message' => 'Transaksi Mitra berhasil',
			], RestController::HTTP_CREATED);
		} else {
			$this->response([
				'status' => 'false',
				'message' => 'Transaksi Mitra gagal',
			], RestController::HTTP_BAD_REQUEST);
		}

	}
}
