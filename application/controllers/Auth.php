<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct()
  {
    parent::__construct();
    $this->load->library('form_validation');
  }
	
	public function login()
	{
		// Redirect If Authenticated
    $this->session->userdata('active_user') !== null ? redirect('/') : '';
    
		$data['title'] = 'Login';
		$data['subview'] = 'auth/login';
		$this->load->view('template/layout', $data);
	}

	/**
     * Validate and Login User
     *
     * @access 	public
     * @param 	
     * @return 	json(array)
     */

	public function login_attempt()
	{
		$rules = [
			[
				'field' => 'name',
				'label' => 'Name',
				'rules' => 'required'
			],
			[
				'field' => 'password',
				'label' => 'Password',
				'rules' => 'required'
			]
		];

		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run()) {
			$this->load->model('login_model');
			$attempt = $this->login_model->attempt($this->input->post(null, false));
			if ($attempt === null) {
				header("Content-type:application/json");
				echo json_encode(['password' => 'Wrong email or password']);
			} else {
				$this->session->set_userdata('active_user', $attempt);
				header("Content-type:application/json");
				echo json_encode(['status' => 'success']);
			}
		} else {
      header("Content-type:application/json");      
			echo json_encode($this->form_validation->get_all_errors());
		}
	}

	/**
     * Logout User
     *
     * @access 	public
     * @param 	
     * @return 	redirect
     */

	public function logout() {
		$this->session->unset_userdata('active_user');
		redirect('auth/login');
  }
}
