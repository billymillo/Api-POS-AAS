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
		$page = $this->get('page') ?? 1;  
		$limit = $this->get('limit') ?? 100;
		if ($id === null) {
			$offset = ($page - 1) * $limit;
			$transaksi = $this->TransaksiOutApi_model->getTransaksiOut($id, $offset, $limit);
		} else {
			$transaksi = $this->TransaksiOutApi_model->getTransaksiOut($id);
		}	
		if ($transaksi) {
			$this->response([
				'status' => TRUE,
				'data' => $transaksi,
				'message' => 'Success'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message' => 'Transaksi Out does not exist'
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
	
		$last_transaksi = $this->TransaksiOutApi_model->getNoTransaksiOut();
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

	public function index_put() {
		$id = $this->put('id');
	
		if (!$id) {
			$this->response([
				'status' => FALSE,
				'message' => 'ID Transaksi harus dikirim dalam request'
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
	
		$transaksi = $this->TransaksiOutApi_model->getTransaksiDataById($id);
		if (!$transaksi) {
			$this->response([
				'status' => FALSE,
				'message' => 'Transaksi tidak ditemukan'
			], RestController::HTTP_NOT_FOUND);
			return;
		}
		
		$data = [
			'id_member' => $this->put('id_member') ?? $transaksi['id_member'],
			'jumlah_produk' => $this->put('jumlah_produk')  ?? $transaksi['jumlah_produk'],
			'id_metode_pembayaran' => $this->put('id_metode_pembayaran') ?? $transaksi['id_metode_pembayaran'],
			'total_transaksi' => $this->put('total_transaksi') + $transaksi['total_transaksi'] ?? $transaksi['total_transaksi'],
			'diskon' => $this->put('diskon') ?? $transaksi['diskon'],
			'status_transaksi' => $this->put('status_transaksi') ?? $transaksi['status_transaksi'],
			'potongan_poin' => $this->put('potongan_poin') ?? $transaksi['potongan_poin'],
			'mendapatkan_poin' => $this->put('mendapatkan_poin') ?? $transaksi['mendapatkan_poin'],
			'user_update' => $this->put('user_update') ?? $transaksi['user_update'],
			'updated_date' => date('Y-m-d H:i:s'),
		];
	
		if ($this->TransaksiOutApi_model->editTransaksiOut($data, $id) > 0) {
			$this->response([
				'status' => TRUE,
				'message' => 'Berhasil Mengubah Transaksi'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message' => 'Gagal Mengubah Transaksi'
			], RestController::HTTP_BAD_REQUEST);
		}
	}
	
	public function detail_get($id = null) {
		$id = $this->get('id');
		$produk = $this->get('produk');
		
		if ($id == null) {
			$transaksiOut = $this->TransaksiOutApi_model->getDetail(null, $produk);
		} else {
			$transaksiOut = $this->TransaksiOutApi_model->getDetail($id, $produk);
		}
		
		if ($transaksiOut) {
			$this->response([
				'status' => TRUE,
				'data'   => $transaksiOut,
				'message'=> 'Success'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message'=> 'Detail Transaksi Tidak Ditemukan'
			], RestController::HTTP_NOT_FOUND);
		}
	}
	

	public function detail_post() {
		$this->form_validation->set_rules('id_produk', 'Produk Id', 'required|trim|integer');
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
	
		$last_transaksi = $this->TransaksiOutApi_model->getLastTransaksiOutId();
		$id_transaksi_out = $last_transaksi ? $last_transaksi['id'] : 1;
	
		$harga_satuan = $this->input->post('harga_satuan');
		$harga_jual = $this->input->post('harga_jual');
		$jumlah = $this->input->post('jumlah');
		$id_produk = $this->input->post('id_produk');
		
		$total_harga_dasar = $harga_satuan * $jumlah;
		$total_harga = $harga_jual * $jumlah;
		$laba = $total_harga - $total_harga_dasar;
	
		$existing_detail = $this->TransaksiOutApi_model->getDetailByProdukAndTransaksi($id_produk, $id_transaksi_out);
	
		if ($existing_detail) {
			$new_jumlah = $existing_detail['jumlah'] + $jumlah;
			$new_total_harga_dasar = $harga_satuan * $new_jumlah;
			$new_total_harga = $harga_jual * $new_jumlah;
			$new_laba = $new_total_harga - $new_total_harga_dasar;
	
			$update_data = [
				'jumlah' => $new_jumlah,
				'total_harga_dasar' => $new_total_harga_dasar,
				'total_harga' => $new_total_harga,
				'laba' => $new_laba,
			];
	
			if ($this->TransaksiOutApi_model->updateDetail($existing_detail['id'], $update_data)) {
				$this->response([
					'status' => 'true',
					'message' => 'Transaksi Detail berhasil diperbarui',
				], RestController::HTTP_OK);
			} else {
				$this->response([
					'status' => 'false',
					'message' => 'Gagal memperbarui transaksi detail',
				], RestController::HTTP_BAD_REQUEST);
			}
		} else {
			$data = [
				'id_transaksi_out' => $id_transaksi_out,
				'id_produk' => $id_produk,
				'jumlah' => $jumlah,
				'harga_satuan' => $harga_satuan,
				'harga_jual' => $harga_jual,
				'total_harga_dasar' => $total_harga_dasar,
				'total_harga' => $total_harga,
				'laba' => $laba,
				'user_input' => $this->input->post('user_input'),
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
	

	public function struk_get() {
		$transaksiOut = $this->TransaksiOutApi_model->getLatestTransaction();
	
		if ($transaksiOut) {
			$this->response([
				'status'  => TRUE,
				'data'    => $transaksiOut,
				'message' => 'Berhasil Mengambil Data Transaksi Struk'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status'  => FALSE,
				'message' => 'No transactions found'
			], RestController::HTTP_NOT_FOUND);
		}
	}	
	

}
