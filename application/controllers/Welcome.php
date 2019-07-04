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

	}
	public function index()
	{
    $data['title'] = 'Welcome';
    $data['subview'] = 'welcome_message';
    $data['active_user'] = $this->session->userdata('active_user');
    $this->load->view('template/layout', $data);      
  }
  
}
