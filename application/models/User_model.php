<?php

class User_model extends CI_Model {

	public function addUser() {
		$data = [
			'name' => htmlspecialchars($this->input->post('name', true)),
			'email' => htmlspecialchars($this->input->post('email', true)),
			'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
			'is_active' => '1',
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
		];	
		return $this->db->insert('users', $data);
	}
	public function loginUser () {
		$nama = $this->input->post('name');
		$password = $this->input->post('password');
		$user = $this->db->get_where('users', ['name' => $nama])->row_array();
		
		if($user) {
			if($user['is_active'] == 1) {
				if(password_verify($password, $user['password'])) {
					$data = [
						'nama' => $user['nama'],
						'email' => $user['email'],
					];
					$this->session->set_userdata($data);
					redirect('user/add');
				} else {
					$this->session->set_flashdata('flash', 'Password Tidak Sesuai');
					redirect('user');		
				}
			} else {
				$this->session->set_flashdata('flash', 'User anda belum aktif');
				redirect('user');	
			}
		} else {
			$this->session->set_flashdata('flash', 'User Tidak Terdaftar');
			redirect('user');
		}
	}


}
