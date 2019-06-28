<?php
if(!defined('APPPATH')) exit('No direct script access allowed');

class Cli extends CI_Controller
{
  public function __construct(){
    
    parent::__construct();

    if(!$this->input->is_cli_request()){
      show_error('You don\'t have permission for this action');
      return;
    }
    $this->load->helper('myfns');
  }

  public function index(){
    echo 'Alright'."\n";
  }

  // php index.php cli login
  public function login(){
    $input = ['name'=>'admin', 'password'=>'password'];
    $this->load->model('login_model');
    $attempt = $this->login_model->attempt($input);
    var_dump($this->db->last_query());
    var_dump($attempt);
  }

	public function validate()
	{
		$rules = [
			[
				'field' => 'name',
				'label' => 'Name',
				'rules' => 'regex_match[/^\w+$/]'
			]
		];

		$this->form_validation->set_rules($rules);
		$this->form_validation->set_data(['name'=>'wonder']);
		if ($this->form_validation->run()) {
      echo 'Successful form validation';
		} else {
      echo 'Failed form validation';
		}
  }
  
  public function fun(){
    $id = function($x){ return $x; };
    $id1 = function($x){ return right($x); };
    $cmd = makeCommand($id, $id, $id, $id)(identity(10))(10);
    // trace($cmd);
    $cmd = makeCommand($id, $id, $id, $id)(right(10))(10);
    $cmd = makeCommand($id, $id, $id, $id)(left(10))(10);
  }
}