<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Users extends RestController
{
	public function __construct(){
		parent::__construct();
		$this->load->model('UserApi_model');
		$this->load->library('form_validation');
	}
	public function index_get() {
		$id = $this->get('id');
		if($id == null) {
			$user = $this->UserApi_model->getUser();
		} else {
			$user = $this->UserApi_model->getUser($id);
		}

		if($user) {
			$this->response([
			   'status' => TRUE,
			   'data'   => $user,
			   'message'=> 'Success'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message'=> 'Id Users Doesnt Exist'
			 ], RestController::HTTP_NOT_FOUND);
		}
	}
	// Register User
	public function index_post() {
		$this->form_validation->set_rules('name', 'Name', 'required|trim');
		$this->form_validation->set_rules('role', 'Role', 'required|in_list[kasir,admin]');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required|trim');
		
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			$this->response([
				'status' => FALSE,
				'message' => $errors
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
		$data = [
			'name' => htmlspecialchars($this->input->post('name')),
			'role' => $this->input->post('role'),
			'email' => htmlspecialchars($this->input->post('email')),
			'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
			'is_active' => '1',
		];
		if ($this->UserApi_model->registerUser($data) > 0) {
			$this->response([
				'status' => 'true',
				'message' => 'User berhasil didaftarkan'
			], RestController::HTTP_CREATED);
		} else {
			$this->response([
				'status' => FALSE,
				'message' => 'User gagal dibuat'
			], RestController::HTTP_NOT_FOUND);
		}
	}

	public function login_post() {
		$nama = $this->input->post('name');
		$password = $this->input->post('password');
		
		$data = $this->UserApi_model->loginUser($nama);
	
		if ($data) {
			if ($data['is_active'] == 1) {
				if (password_verify($password, $data['password'])) {
					$this->UserApi_model->updateStatus($data['id'], 1);
	
					$this->response([
						'status' => true,
						'name' => $data['name'],
						'role' => $data['role'],
						'id' => $data['id'],
						'message' => 'Login Berhasil'
					], RestController::HTTP_OK);
				} else {
					$this->response([
						'status' => false,
						'message' => 'Password Tidak Sesuai'
					], RestController::HTTP_NOT_FOUND);
				}
			} else {
				$this->response([
					'status' => false,
					'message' => 'User Belum Teraktivasi'
				], RestController::HTTP_NOT_FOUND);
			}
		} else {
			$this->response([
				'status' => false,
				'message' => 'User Tidak Terdaftar'
			], RestController::HTTP_NOT_FOUND);
		}
	}

	public function logout_post() {
		$user_id = $this->input->post('id');
	
		if (!$user_id) {
			$this->response([
				'status' => FALSE,
				'message' => 'ID User diperlukan'
			], RestController::HTTP_BAD_REQUEST);
			return;
		}
	
		if ($this->UserApi_model->updateStatus($user_id, 0)) {
			$this->response([
				'status' => TRUE,
				'message' => 'Logout berhasil'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message' => 'Logout gagal, User tidak ditemukan'
			], RestController::HTTP_NOT_FOUND);
		}
	}
	
	
}
