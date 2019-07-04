<?php

// bindables
function remove_user_record($request)
{
  $ci = &get_instance();
  $id = $request->post;
  $ci->db->delete('users', $id);
  if ($ci->db->affected_rows() > 0) {
    return right('Delete user success!');
  } else {
    return left(__FUNCTION__);
  }
}

class Delete_user_usecase
{
  public $check_active_user_is_admin;
  public $remove_user_record;
  public $presenter;
  
  public function execute($request)
  {
    $result = identity($request)
      ->bind($this->check_active_user_is_admin)
      ->bind($this->remove_user_record);
    
    return ($this->presenter)($result);
  }

  public function setCheckActiveUserIsAdmin($check_active_user_is_admin)
  {
    $this->check_active_user_is_admin = $check_active_user_is_admin;

    return $this;
  }

  public function setDeleteUser($remove_user_record)
  {
    $this->remove_user_record = $remove_user_record;

    return $this;
  }
  
  public function setPresenter($presenter)
  {
    $this->presenter = $presenter;

    return $this;
  }
}

function get_delete_user_usecase()
{  
  $delete_user = new Delete_user_usecase;
  $delete_user->setCheckActiveUserIsAdmin('check_active_user_is_admin')
      ->setDeleteUser('remove_user_record');
  
  $presenter = function($result){
    $result->match([
      'left'=>function($xx){ 
        $x = $xx->extract();
        $dict = [
          'check_active_user_is_admin' => function(){ 
            api_response(202, ['message' => 'You are not allowed to perform this action!']);
          },
          'remove_user_record' => function(){ 
            api_response(202, ['message' => 'An expected error occurred!']);
          }
        ];
        return $dict[$x]();
      },
      'right'=>function($x){ 
        $response = [
          'message' => $x->extract()
        ];

        api_response(200, $response);
      },
    ]);
  };

  $delete_user->setPresenter($presenter);
  return $delete_user;
}
