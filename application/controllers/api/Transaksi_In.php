<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Transaksi_In extends RestController {
	public function __construct() {
		parent::__construct();
		$this->load->model('TransaksiInApi_model');
		$this->load->library('form_validation');
	}

	public function index_get($id = null) {
		$id = $this->get('id');
		if($id == null) {
			$transaksiIn = $this->TransaksiInApi_model->getTransaksiIn();
		} else {
			$transaksiIn = $this->TransaksiInApi_model->getTransaksiIn($id);
		}
		if($transaksiIn) {
			$this->response([
			   'status' => TRUE,
			   'data'   => $transaksiIn,
			   'message'=> 'Success'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message'=> 'Id Transaksi In Doesnt Exist'
			 ], RestController::HTTP_NOT_FOUND);
		}
	}

	public function index_post() {
        $this->form_validation->set_rules('jumlah_produk', 'Jumlah Produk', 'required|trim|integer');        
        $this->form_validation->set_rules('total_transaksi', 'Total Transaksi', 'required|trim|integer');

        if ($this->form_validation->run() == FALSE) {
            $this->response([
                'status' => FALSE,
                'message' => $this->form_validation->error_array()
            ], RestController::HTTP_BAD_REQUEST);
            return;
        }

        $last_transaksi = $this->TransaksiInApi_model->getLastTransaksiIn(); 

        $i = ($last_transaksi) ? (int)substr($last_transaksi['no_transaksi_in'], 3, 3) : 0;
		$i++;
        $no_transaksi_in = 'TRN-IN' . str_pad($i, 3, '0', STR_PAD_LEFT) . date('dmy');

        $data = [
            'no_transaksi_in' => $no_transaksi_in,
            'jumlah_produk' => $this->input->post('jumlah_produk'),
            'total_transaksi' => $this->input->post('total_transaksi'),
        ];

        if ($this->TransaksiInApi_model->addTransaksiIn($data) > 0) {
            $this->response([
                'status' => 'true',
                'message' => 'Transaksi Telah berhasil ditambahkan',
            ], RestController::HTTP_CREATED);
        } else {
            $this->response([
                'status' => 'false',
                'message' => 'Transaksi Telah gagal ditambahkan',
            ], RestController::HTTP_BAD_REQUEST);
        }
	}

	public function detail_get($id = null) {
		$id = $this->get('id');
		if($id == null) {
			$transaksiIn = $this->TransaksiInApi_model->getDetail();
		} else {
			$transaksiIn = $this->TransaksiInApi_model->getDetail($id);
		}
		if($transaksiIn) {
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

	public function detail_post() {
		$this->form_validation->set_rules('id_transaksi_in', 'Transaksi Info', 'required|trim|integer');
		$this->form_validation->set_rules('id_produk', 'Produk Info', 'required|trim|integer');
		$this->form_validation->set_rules('jumlah', 'Jumlah Transaksi', 'required|trim|integer');
		$this->form_validation->set_rules('harga_satuan', 'Harga Satuan', 'required|trim|integer');
		$this->form_validation->set_rules('total_harga', 'Total Harga', 'required|trim|integer');
		$this->form_validation->set_rules('tgl_expired', 'Tanggal Expired', 'required|date');

		if ($this->form_validation->run() == FALSE) {
			$this->response([
				'status' => FALSE,
				'message' => $this->form_validation->error_array()
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
		$data = [
			'id_transaksi_in' => $this->input->post('id_transaksi_in'),
			'id_produk' => $this->input->post('id_produk'),
			'jumlah' => $this->input->post('jumlah'),
			'harga_satuan' => $this->input->post('harga_satuan'),
			'total_harga' => $this->input->post('total_harga'),
			'tgl_expired' => $this->input->post('tgl_expired'),
		];
		if ($this->TransaksiInApi_model->addDetail($data) > 0) {
			$this->response([
				'status' => 'true',
				'message' => 'Transaksi Detail berhasil ditambahkan',
			], RestController::HTTP_CREATED);
		} else {
			$this->response([
				'status' => 'false',
				'message' => 'Transaksi Detail gagal ditambahkan',
			], RestController::HTTP_BAD_REQUEST);
		}

	}

	public function pembayaran_get() {
		$id = $this->get('id');
		if($id == null) {
			$pembayaran = $this->TransaksiInApi_model->getPembayaran();
		} else {
			$pembayaran = $this->TransaksiInApi_model->getPembayaran($id);
		}
		if($pembayaran) {
			$this->response([
			   'status' => true,
			   'data'   => $pembayaran,
			   'message'=> 'Success'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => false,
				'message'=> 'Id Pembayaran In Doesnt Exist'
			 ], RestController::HTTP_NOT_FOUND);
		}

	}

	public function pembayaran_post() {
		$this->form_validation->set_rules('metode', 'Metode', 'required');
		if ($this->form_validation->run() == false) {
			$this->response([
				'status' => false,
				'message' => $this->form_validation->error_array()
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
		$data = [
			'metode' => $this->input->post('metode'),
		];
		if ($this->TransaksiInApi_model->addPembayaran($data) > 0) {
			$this->response([
				'status' => true,
				'message' => 'Pembayaran berhasil ditambahkan',
			], RestController::HTTP_CREATED);
		} else {
			$this->response([
				'status' => false,
				'message' => 'Pembayaran gagal ditambahkan',
			], RestController::HTTP_BAD_REQUEST);
		}

	}

	public function pembayaran_put() {
		$id = $this->put('id');
					
		if (!$id) {
			$this->response([
				'status' => false,
				'message' => 'ID tidak ditemukan'
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
		$data = [
			'metode' => $this->put('metode'),
			'update_date' =>  $this->put('update_date'),
		];

		if ($data['metode'] == null) {
			$this->response([
				   'status' => false,
				   'message' => 'Isi Pembayaran'
			], RestController::HTTP_BAD_REQUEST);
		}

		if ($this->TransaksiInApi_model->editPembayaran($data, $id) > 0) {
			$this->response([
				   'status' => true,
				   'message'=> 'Berhasil Mengubah Metode Pembayaran'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				   'status' => false,
				   'message' => 'Tidak ada Perubahan'
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
	}

	public function pembayaran_delete() {
		$id = $this->delete('id');
	
		if (!$id) {
			$this->response([
				'status' => false,
				'message' => 'ID tidak ditemukan'
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
	
		$data = [
			'presence' => 0,
			'update_date' => date('Y-m-d H:i:s'),
			'user_update' => $this->delete('user_update') ?? 'system'
		];
	
		if ($this->TransaksiInApi_model->deletePembayaran($data, $id)) {
			$this->response([
				'status' => true,
				'message' => 'Metode Pembayaran berhasil Dihapus'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => false,
				'message' => 'Gagal mengubah status pembayaran'
			], RestController::HTTP_BAD_REQUEST);
		}
	}

}
