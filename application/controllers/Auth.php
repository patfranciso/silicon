<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	/**
     * Login Form
     *
     * @access 	public
     * @param 	
     * @return 	view
     */
	
	public function login()
	{
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
				'field' => 'email',
				'label' => 'Email',
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
			$attempt = $this->login_model->attempt($this->input->post());
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
  
    /**
    * Register
    *
    * @access  public
    * @param
    * @return  view
    */

    public function register_form()
    {
            $this->data['title'] = 'Register';
            $this->data['subview'] = 'register/main';
            $this->load->view('components/layout', $this->data);
    }
}
