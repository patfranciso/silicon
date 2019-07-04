<?php

class Login_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}
	
	public function attempt($input)
	{
		$query = $this->db->get_where('login', array('name' => $input['name']));

    if($res = $query->row()){
      if(password_verify ( $input['password'], $res->password)){
        unset($res->password);
        unset($res->created);
        unset($res->updated);
        return $res;
      }
      else return null;
    }
    else return null;
  }

}