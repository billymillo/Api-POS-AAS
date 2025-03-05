<?php

class User extends CI_Controller 
{	
	public function __construct() {
		parent::__construct();
		$this->load->model('User_model');
		$this->load->library('form_validation');
	}
	public function berhasil() 
	{
		$data['title'] = 'Berhasil';
		$this->load->view('template/header', $data);
		$this->load->view('template/footer');
		$this->load->view('test/berhasil');
	}
	public function index(){
		$data['title'] = 'User Login Page';
		$this->form_validation->set_rules('name', 'User name', 'required|max_length[255]');
		$this->form_validation->set_rules('password', 'User password', 'required|min_length[8]');

		if($this->form_validation->run() == FALSE) {
			$this->load->view('test/login');
			$this->load->view('template/header', $data);
			$this->load->view('template/footer');
		} else {
			$this->User_model->loginUser();
			$this->session->set_flashdata('flash', 'User Login Error');
		}
	}


	public function add() 
	{
		$data['title'] = 'User Registration';
		$this->form_validation->set_rules('name', 'User name', 'required|max_length[255]');
		$this->form_validation->set_rules('email', 'User email', 'required|valid_email');
		$this->form_validation->set_rules('password', 'User password', 'required|min_length[8]');

		if($this->form_validation->run() == FALSE) {
			$this->load->view('test/register');
			$this->load->view('template/header', $data);
			$this->load->view('template/footer');
		} else {
			$this->User_model->addUser();
			$this->session->set_flashdata('flash', 'Ditambahkan');
			redirect('user/berhasil');
		}
	}
}
