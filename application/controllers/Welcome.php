<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {


	protected $data;
	
	public function __construct() {
		parent::__construct();

		// Redirect If Not Authenticated
		$this->session->userdata('active_user') == null ? redirect('auth/login') : '';

		// Get Authenticated User
		$this->data['active_user'] = $this->session->userdata('active_user');
		$this->data['active_user_group'] = $this->group_m->get_group($this->data['active_user']->group_id);

	}
	public function index()
	{
		$this->load->view('welcome_message');
	}
}
