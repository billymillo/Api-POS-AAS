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
		$limit = $this->get('limit') ?? 1000;
		$sort = $this->get('sort') ?? 'asc';
	
		if ($id === null) {
			$offset = ($page - 1) * $limit;
			$transaksi = $this->TransaksiOutApi_model->getTransaksiOut($id, $offset, $limit, $sort);
		} else {
			$transaksi = $this->TransaksiOutApi_model->getTransaksiOut($id, null, null, $sort);
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
			'total_transaksi' => $this->put('total_transaksi') ?? $transaksi['total_transaksi'],
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

	public function detail_post(){
    	$this->form_validation->set_rules('id_produk', 'id_produk', 'required|trim|integer');
    	$this->form_validation->set_rules('jumlah', 'jumlah', 'required|trim|integer');
    	$this->form_validation->set_rules('harga_satuan', 'harga_satuan', 'required|trim|integer');
    	$this->form_validation->set_rules('harga_jual', 'harga_jual', 'required|trim|integer');
    	$this->form_validation->set_rules('harga_add_on', 'harga_add_on', 'required|trim|integer');

    	if ($this->form_validation->run() == FALSE) {
    	    $errors = $this->form_validation->error_array();
    	    $this->response([
    	        'status' => FALSE,
    	        'message' => $errors
    	    ], RestController::HTTP_BAD_REQUEST);
    	    return;
    	}

    	$last_transaksi = $this->TransaksiOutApi_model->getLastTransaksiOutId();
    	$id_transaksi_out = $last_transaksi ? $last_transaksi['id'] : 1;

    	// Ambil nilai input
    	$jumlah = $this->input->post('jumlah');
    	$harga_satuan = $this->input->post('harga_satuan');
    	$harga_jual = $this->input->post('harga_jual');
    	$harga_add_on = $this->input->post('harga_add_on');

    	// Hitung otomatis
    	$total_harga_dasar = $jumlah * $harga_satuan;
    	$total_harga = $jumlah * $harga_jual + $harga_add_on;
    	$laba = $total_harga - $total_harga_dasar;

    	$data = [
    	    'id_transaksi_out' => $id_transaksi_out,
    	    'id_produk' => $this->input->post('id_produk'),
    	    'jumlah' => $jumlah,
    	    'harga_satuan' => $harga_satuan,
    	    'harga_jual' => $harga_jual,
    	    'harga_add_on' => $harga_add_on,
    	    'total_harga_dasar' => $total_harga_dasar,
    	    'total_harga' => $total_harga,
    	    'laba' => $laba,
			'saldo' => $total_harga_dasar,
    	    'user_input' => $this->input->post('user_input') ?? 'system',
    	];

    	$id_detail = $this->TransaksiOutApi_model->addDetail($data);

		if ($id_detail !== false) {
		    $this->response([
		        'status' => TRUE,
		        'message' => 'Transaksi telah berhasil ditambahkan',
		        'data' => [
		            'id' => $id_detail,
		        ]
		    ], RestController::HTTP_OK);
		} else {
		    $this->response([
		        'status' => FALSE,
		        'message' => 'Transaksi gagal ditambahkan',
		    ], RestController::HTTP_BAD_REQUEST);
		}

	}

	public function detail_put() {
		$id = $this->put('id');
		
		if (!$id) {
			$this->response([
				'status' => FALSE,
				'message' => 'ID produk harus dikirim dalam request'
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
	
		$transaksi = $this->TransaksiOutApi_model->getDetailDataById($id);
		if (!$transaksi) {
			$this->response([
				'status' => FALSE,
				'message' => 'Produk tidak ditemukan'
			], RestController::HTTP_NOT_FOUND);
			return;
		}
	
		$data = [
			'id_transaksi_out' => $this->put('id_transaksi_out') ?? $transaksi['id_transaksi_out'],
    	    'id_produk' => $this->put('id_produk') ?? $transaksi['id_produk'],
    	    'jumlah' => $this->put('jumlah') ?? $transaksi['jumlah'],
    	    'harga_satuan' => $this->put('harga_satuan') ?? $transaksi['harga_satuan'],
    	    'harga_jual' => $this->put('harga_jual') ?? $transaksi['harga_jual'],
    	    'harga_add_on' => $this->put('harga_add_on') ?? $transaksi['harga_add_on'],
    	    'total_harga_dasar' => $this->put('total_harga_dasar') ?? $transaksi['total_harga_dasar'],
    	    'total_harga' => $this->put('total_harga') ?? $transaksi['total_harga'],
    	    'laba' => $this->put('laba') ?? $transaksi['laba'],
			'saldo' => $this->put('saldo') ?? $transaksi['saldo'],
    	    'user_update' => $this->input->post('user_update') ?? 'system',
		];


		if ($this->TransaksiOutApi_model->updateDetail($data, $id) > 0) {
			$this->response([
				'status' => TRUE,
				'message' => 'Update Detail Transaksi berhasil diperbarui',
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message' => 'Gagal mengupdate Detail Transaksi',
			], RestController::HTTP_BAD_REQUEST);
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
