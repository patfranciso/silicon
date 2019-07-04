<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
  
  public function create_user_post(){
    $request = make_post_request();

    $create_user = get_create_user_usecase();
    $create_user->execute($request);    
  }

  public function showAllUsers()
  {
    $result = false;
    $query = $this->db->get('users');
    if ($query->num_rows() > 0) {
        $result['users']  = $query->result();
    }
    echo json_encode($result);
  }

  public function showAllGroups()
  {
    $result = false;
    $query = $this->db->get('groups');
    if ($query->num_rows() > 0) {
        $result['groups']  = $query->result();
    }
    echo json_encode($result);
  }

  public function delete_user_post(){
    $request = make_post_request();

    $delete_user = get_delete_user_usecase();
    $delete_user->execute($request);  
  }
}
