<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Group extends CI_Controller {

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
    $data['title'] = 'Groups';
    $data['subview'] = 'group';
    $this->load->view('template/layout', $data);      
  }
  
}
