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

        $last_transaksi = $this->TransaksiInApi_model->getNoTransaksiIn();
		if ($last_transaksi) {
			preg_match('/TRN-IN(\d+)\d{6}$/', $last_transaksi['no_transaksi_in'], $matches);
			$i = isset($matches[1]) ? (int)$matches[1] + 1 : 1;
		} else {
			$i = 1;
		}

		$no_transaksi_in = 'TRN-IN' . str_pad($i, 3, '0', STR_PAD_LEFT) . date('dmy');
        $data = [
            'no_transaksi_in' => $no_transaksi_in,
            'jumlah_produk' => $this->input->post('jumlah_produk'),
            'total_transaksi' => $this->input->post('total_transaksi'),
			'user_input' => $this->input->post('user_input'),
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
			$transaksiDet = $this->TransaksiInApi_model->getDetail();
		} else {
			$transaksiDet = $this->TransaksiInApi_model->getDetail($id);
		}
		if($transaksiDet) {
			$this->response([
			   'status' => TRUE,
			   'data'   => $transaksiDet,
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
		$last_transaksi = $this->TransaksiInApi_model->getLastTransaksiInId();
		$id_transaksi_in = $last_transaksi ? $last_transaksi['id'] : 1;

		$harga_satuan = $this->input->post('harga_satuan');
		$jumlah = $this->input->post('jumlah');
		$total = $harga_satuan * $jumlah;

		$data = [
			'id_transaksi_in' => $id_transaksi_in,
			'id_produk' => $this->input->post('id_produk'),
			'jumlah' => $jumlah,
			'harga_satuan' => $harga_satuan,
			'harga_jual' => $this->input->post('harga_jual'),
			'total_harga' => $total,
			'user_input' => $this->input->post('user_input'),
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
			'user_input' => $this->input->post('user_input')
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
			'updated_date' =>  $this->put('updated_date'),
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
			'updated_date' => date('Y-m-d H:i:s'),
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
