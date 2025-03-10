<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Transaksi_Out extends RestController {

	public function __construct() {
		parent::__construct();
		$this->load->model('TransaksiOutApi_model');
		$this->load->library('form_validation');
	}
	public function index_get($id = null) {
		$id = $this->get('id');
		if($id == null) {
			$status = $this->TransaksiOutApi_model->getTransaksiOut();
		} else {
			$status = $this->TransaksiOutApi_model->getTransaksiOut($id);
		}
		if($status) {
			$this->response([
			   'status' => TRUE,
			   'data'   => $status,
			   'message'=> 'Success'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message'=> 'Id status Doesnt Exist'
			], RestController::HTTP_NOT_FOUND);
		}
	}

	public function index_post() {
		$this->form_validation->set_rules('jumlah_produk', 'Jumlah Produk', 'required|trim');
		$this->form_validation->set_rules('id_metode_pembayaran', 'Metode Pembayaran', 'required|trim|integer');
		$this->form_validation->set_rules('total_transaksi', 'Total Transaksi', 'required|trim|decimal');
		$this->form_validation->set_rules('status_transaksi', 'Status Transaksi', 'required');
	
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->response([
				'status' => FALSE,
				'message' => $errors
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
	
		$last_transaksi = $this->TransaksiOutApi_model->getLastTransaksiOut();
		if ($last_transaksi) {
			preg_match('/TRN-OUT(\d+)\d{6}$/', $last_transaksi['no_transaksi_out'], $matches);
			$i = isset($matches[1]) ? (int)$matches[1] + 1 : 1;
		} else {
			$i = 1;
		}
		$no_transaksi_out = 'TRN-OUT' . str_pad($i, 3, '0', STR_PAD_LEFT) . date('dmy');
	
		$data = [
			'id_member' => $this->input->post('id_member'),
			'no_transaksi_out' => $no_transaksi_out,
			'jumlah_produk' => $this->input->post('jumlah_produk'),
			'id_metode_pembayaran' => $this->input->post('id_metode_pembayaran'),
			'total_transaksi' => $this->input->post('total_transaksi'),
			'diskon' => $this->input->post('diskon'),
			'status_transaksi' => $this->input->post('status_transaksi'),
			'potongan_poin' => $this->input->post('potongan_poin'),
			'mendapatkan_poin' => $this->input->post('mendapatkan_poin'),
			'user_input' => $this->input->post('user_input'),			
		];
	
		if ($this->TransaksiOutApi_model->addTransaksiOut($data) > 0) {
			$this->response([
				'status' => true,
				'message' => 'Transaksi Telah berhasil ditambahkan',
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => false,
				'message' => 'Transaksi gagal ditambahkan',
			], RestController::HTTP_BAD_REQUEST);
		}
	}
	

	public function detail_get($id = null) {
		$id = $this->get('id');
		if($id == null) {
			$transaksiOut = $this->TransaksiOutApi_model->getDetail();
		} else {
			$transaksiOut = $this->TransaksiOutApi_model->getDetail($id);
		}
		if($transaksiOut) {
			$this->response([
			   'status' => TRUE,
			   'data'   => $transaksiOut,
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
		$this->form_validation->set_rules('id_transaksi_out', ' Out Info', 'required|trim|integer');
		$this->form_validation->set_rules('id_produk', 'Produk Info', 'required|trim|integer');
		$this->form_validation->set_rules('jumlah', 'Jumlah Transaksi', 'required|trim|integer');
		$this->form_validation->set_rules('harga_satuan', 'Harga Satuan', 'required|trim|integer');
		$this->form_validation->set_rules('harga_jual', 'Harga Jual', 'required|trim|integer');
		$this->form_validation->set_rules('total_harga', 'Total Harga', 'required|date');

		if ($this->form_validation->run() == FALSE) {
			$this->response([
				'status' => FALSE,
				'message' => $this->form_validation->error_array()
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
		$data = [
			'id_transaksi_out' => $this->input->post('id_transaksi_out'),
			'id_produk' => $this->input->post('id_produk'),
			'jumlah' => $this->input->post('jumlah'),
			'harga_satuan' => $this->input->post('harga_satuan'),
			'harga_jual' => $this->input->post('harga_jual'),
			'total_harga' => $this->input->post('total_harga'),
		];
		if ($this->TransaksiOutApi_model->addDetail($data) > 0) {
			$this->response([
				'status' => 'true',
				'message' => 'Transaksi Detail berhasil',
			], RestController::HTTP_CREATED);
		} else {
			$this->response([
				'status' => 'false',
				'message' => 'Transaksi Detail gagal',
			], RestController::HTTP_BAD_REQUEST);
		}

	}

}
