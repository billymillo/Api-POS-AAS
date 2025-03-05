<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Member extends RestController {
	/**
	 * Konstruktor
	 *
	 * Menggunakan konstruktor parent, dan memuat model dan library form_validation
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('MemberApi_model');
		$this->load->library('form_validation');
	}

	public function index_get($id = null) {
		$id = $this->get('id');
		if($id == null) {
			$member = $this->MemberApi_model->getMember();
		} else {
			$member = $this->MemberApi_model->getMember($id);
		}
		if($member) {
			$this->response([
			   'status' => TRUE,
			   'data'   => $member,
			   'message'=> 'Success'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message'=> 'Id member Doesnt Exist'
			 ], RestController::HTTP_NOT_FOUND);
		}
	}

	public function index_post() {
		$this->form_validation->set_rules('nama', 'Nama Member', 'required|trim');
		$this->form_validation->set_rules('id_peg_system', 'Data Pegawai Member', 'required|trim');		
		$this->form_validation->set_rules('no_tlp', 'Nomor Telepon Member', 'required|trim');	
		$this->form_validation->set_rules('poin', 'Poin Member', 'required|trim');	

		if ($this->form_validation->run() == FALSE) {
			$this->response([
				'status' => FALSE,
				'message' => $this->form_validation->error_array()
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
	
		$data = [
			'nama' => $this->input->post('nama'),
			'id_peg_system' => $this->input->post('id_peg_system'),
			'no_tlp' => $this->input->post('no_tlp'),
			'poin' => $this->input->post('poin'),
		];
	
		if ($this->MemberApi_model->addMember($data) > 0) {
			$this->response([
				'status' => 'true',
				'message' => 'Member berhasil ditambahkan',
			], RestController::HTTP_CREATED);
		} else {
			$this->response([
				'status' => 'false',
				'message' => 'Member gagal ditambahkan',
			], RestController::HTTP_BAD_REQUEST);
		}

	}

	public function status_get($id = null) {
		$id = $this->get('id');
		if($id == null) {
			$status = $this->MemberApi_model->getStatus();
		} else {
			$status = $this->MemberApi_model->getStatus($id);
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

	public function status_post() {
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
		if ($this->MemberApi_model->addStatus($data) > 0) {
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
	
}
