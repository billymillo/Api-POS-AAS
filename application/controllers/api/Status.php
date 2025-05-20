<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Status extends RestController {

	public function __construct() {
		parent::__construct();
		$this->load->model('StatusApi_model');
		$this->load->library('form_validation');
	}

    public function index_get($id = null) {
		$id = $this->get('id');
		if($id == null) {
			$status = $this->StatusApi_model->getStatus();
		} else {
			$status = $this->StatusApi_model->getStatus($id);
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
		$this->form_validation->set_rules('status', 'Status', 'required|trim');

		if ($this->form_validation->run() == FALSE) {
			$this->response([
				'status' => FALSE,
				'message' => $this->form_validation->error_array()
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
		$data = [
			'status' => $this->input->post('status'),
		];
		if ($this->StatusApi_model->addStatus($data) > 0) {
			$this->response([
				'status' => 'true',
				'message' => 'Status berhasil ditambahkan',
			], RestController::HTTP_CREATED);
		} else {
			$this->response([
				'status' => 'false',
				'message' => 'Status gagal ditambahkan',
			], RestController::HTTP_BAD_REQUEST);
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
			'status' => $this->put('status'),
			'updated_date' =>  date('Y-m-d H:i:s'),
			'user_update' => $this->input->post('user_update') ?? "system",
		];

		if ($data['status'] == null) {
			$this->response([
				   'status' => FALSE,
				   'message' => 'Isi Status'
			], RestController::HTTP_BAD_REQUEST);
		}

		if ($this->StatusApi_model->editStatus($data, $id) > 0) {
			$this->response([
				   'status' => true,
				   'message'=> 'Berhasil Mengubah Status'
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
			'user_update' => $this->delete('user_update') ?? 'system' // Bisa dikirim user yg menghapus
		];
	
		if ($this->StatusApi_model->deleteStatus($data, $id)) {
			$this->response([
				'status' => TRUE,
				'message' => 'Status berhasil Dihapus'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message' => 'Gagal mengubah status tipe'
			], RestController::HTTP_BAD_REQUEST);
		}
	}
	
}