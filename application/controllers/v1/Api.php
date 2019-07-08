<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
  
  public function create_user_post(){
    $request = make_post_request();

    $create_user = make_create_user_usecase();
    $create_user->execute($request);    
  }
  
  public function create_group_post(){
    $request = make_post_request();

    $create_group = make_create_group_usecase();
    $create_group->execute($request);    
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
  
  public function assign_user_to_group()
  {
    $request = make_post_request();

    $assign_user_to_group = make_assign_user_to_group_usecase();
    $assign_user_to_group->execute($request);  
  }

  public function delete_user_post(){
    $request = make_post_request();

    $delete_user = make_delete_user_usecase();
    $delete_user->execute($request);  
  }

  public function delete_group_post(){
    $request = make_post_request();

    $delete_group = make_delete_group_usecase();
    $delete_group->execute($request);  
  }
}
