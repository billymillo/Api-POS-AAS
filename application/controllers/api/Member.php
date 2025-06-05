<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Member extends RestController {

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
			'saldo' => $this->input->post('saldo'),
			'poin' => $this->input->post('poin'),
			'user_input' => $this->input->post('user_input')
		];
	
		if ($this->MemberApi_model->addMember($data) > 0) {
			$this->response([
				'status' => true,
				'message' => 'Member berhasil ditambahkan',
			], RestController::HTTP_CREATED);
		} else {
			$this->response([
				'status' => false,
				'message' => 'Member gagal ditambahkan',
			], RestController::HTTP_BAD_REQUEST);
		}

	}

	public function index_put() {
		$id = $this->put('id');
			
		if (!$id) {
			$this->response([
				'status' => FALSE,
				'message' => 'ID Member tidak ditemukan'
			], RestController::HTTP_BAD_REQUEST);
			return;
		}

		$member = $this->MemberApi_model->getMemberDataById($id);
		if (!$member) {
			$this->response([
				'status' => FALSE,
				'message' => 'Member tidak ditemukan'
			], RestController::HTTP_NOT_FOUND);
			return;
		}

		$data = [
			'nama' => $this->put('nama') ?? $member['nama'],
			'no_tlp' => $this->put('no_tlp') ?? $member['no_tlp'],
			'saldo' => $this->put('saldo') ?? $member['saldo'],
			'poin' => $this->put('poin') ?? $member['poin'],
			'updated_date' => date('Y-m-d H:i:s'),
		];

		if ($this->MemberApi_model->editMember($data, $id) > 0) {
			$this->response([
				   'status' => true,
				   'message'=> 'Berhasil mengubah isi member'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				   'status' => FALSE,
				   'message' => 'Tidak Terjadi Perubahan'
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
			'user_update' => $this->delete('user_update') ?? 'system'
		];
	
		if ($this->MemberApi_model->deleteMember($data, $id)) {
			$this->response([
				'status' => TRUE,
				'message' => 'Member berhasil Dihapus'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message' => 'Gagal mengubah status Member'
			], RestController::HTTP_BAD_REQUEST);
		}
	}

	public function topup_get($id = null) {
		$id = $this->get('id');
		if($id == null) {
			$member = $this->MemberApi_model->getTopup();
		} else {
			$member = $this->MemberApi_model->getTopup($id);
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
				'message'=> 'Id Topup Doesnt Exist'
			 ], RestController::HTTP_NOT_FOUND);
		}
	}

	public function topup_post() {
		$this->form_validation->set_rules('id_member', 'Id Member', 'required|trim');
		$this->form_validation->set_rules('total_topup', 'Total Topup', 'required|trim');		
		$this->form_validation->set_rules('id_metode', 'Metode Topup', 'required|trim');		

		if ($this->form_validation->run() == FALSE) {
			$this->response([
				'status' => FALSE,
				'message' => $this->form_validation->error_array()
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
	
		$data = [
			'id_member' => $this->input->post('id_member'),
			'total_topup' => $this->input->post('total_topup'),
			'id_metode' => $this->input->post('id_metode'),
			'user_input' => $this->input->post('user_input')
		];
	
		if ($this->MemberApi_model->addTopup($data) > 0) {
			$this->response([
				'status' => true,
				'message' => 'Top Up Berhasil, Saldo berhasil ditambahkan',
			], RestController::HTTP_CREATED);
		} else {
			$this->response([
				'status' => false,
				'message' => 'Top Up Gagal, Saldo gagal ditambahkan',
			], RestController::HTTP_BAD_REQUEST);
		}

	}

	public function topup_put() {
		$id = $this->put('id');
			
		if (!$id) {
			$this->response([
				'status' => FALSE,
				'message' => 'ID Member tidak ditemukan'
			], RestController::HTTP_BAD_REQUEST);
			return;
		}

		$member = $this->MemberApi_model->getMemberDataById($id);
		if (!$member) {
			$this->response([
				'status' => FALSE,
				'message' => 'Member tidak ditemukan'
			], RestController::HTTP_NOT_FOUND);
			return;
		}

		$data = [
			'id_member' => $this->put('id_member') ?? $member['id_member'],
			'total_topup' => $this->put('total_topup') ?? $member['total_topup'],
			'id_metode' => $this->put('id_metode') ?? $member['id_metode'],
			'updated_date' => date('Y-m-d H:i:s'),
		];

		if ($this->MemberApi_model->editTopup($data, $id) > 0) {
			$this->response([
				   'status' => true,
				   'message'=> 'Berhasil mengubah isi member'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				   'status' => FALSE,
				   'message' => 'Tidak Terjadi Perubahan'
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
	}

	public function topup_delete() {
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
			'user_update' => $this->delete('user_update') ?? 'system'
		];
	
		if ($this->MemberApi_model->deleteTopup($data, $id)) {
			$this->response([
				'status' => TRUE,
				'message' => 'Top Up berhasil Dihapus'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message' => 'Gagal mengubah status Top Up'
			], RestController::HTTP_BAD_REQUEST);
		}
	}

}
