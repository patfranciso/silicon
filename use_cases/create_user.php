<?php

// bindables 
function validate_new_user($post_request)
{
  $user = $post_request->post;
  return preg_match('/^\w{2,16}$/', @$user['name']) ? right($post_request) : left(__FUNCTION__);
}

function get_user_by_id(int $input)
{
  $ci = &get_instance();
  $query = $ci->db->get_where('users', ['id' => $input]);

  return $query->row();
}

function create_user($request)
{
  $ci = &get_instance();

  $user = $request->post;
  $dcase = $ci->db->insert('users', $user);
  $result = [];
  if ($dcase === true) {
    $id = $ci->db->insert_id();
    $duser = get_user_by_id($id);

    // var_export(right($duser));
    $result = right($duser);
  } else {
    $result = left(__FUNCTION__);
  }
  return $result;
}

class Create_user_usecase
{
  public $check_active_user_is_admin;
  public $validate_new_user;
  public $create_user;
  public $presenter;


  public function execute($request)
  {
    $result = identity($request)
      ->bind($this->check_active_user_is_admin)
      ->bind($this->validate_new_user)
      ->bind($this->create_user);
    // trace($result);
    
    return ($this->presenter)($result);
  }

  /**
   * @param mixed $check_active_user_is_admin
   *
   * @return self
   */
  public function setCheckActiveUserIsAdmin($check_active_user_is_admin)
  {
    $this->check_active_user_is_admin = $check_active_user_is_admin;

    return $this;
  }

  /**
   * @param mixed $validate_new_user
   *
   * @return self
   */
  public function setValidateNewUser($validate_new_user)
  {
    $this->validate_new_user = $validate_new_user;

    return $this;
  }

  /**
   * @param mixed $create_user
   *
   * @return self
   */
  public function setCreateUser($create_user)
  {
    $this->create_user = $create_user;

    return $this;
  }

  /**
   * @param mixed $presenter
   *
   * @return self
   */
  public function setPresenter($presenter)
  {
    $this->presenter = $presenter;

    return $this;
  }
}

function get_create_user_usecase(){
  $create_user = new Create_user_usecase;
  $create_user->setCheckActiveUserIsAdmin('check_active_user_is_admin')
    ->setValidateNewUser('validate_new_user')
    ->setCreateUser('create_user');

  // $cli_presenter = function($result){
  //   $result->match([
  //     'left'=>function($xx){ 
  //       $x = $xx->extract();
  //       $dict = [
  //         'check_active_user_is_admin' => function(){ printErr('202');},
  //         'validate_new_user' => function(){ printErr('412');},
  //         'create_user' => function(){ printErr('400');}
  //       ];
  //       return $dict[$x]();
  //     },
  //     'right'=>function($x){ printSuccess('Success'.$x);},
  //   ]);
  // };

  // $create_user->setPresenter($cli_presenter);

  $presenter = function($result){
    $result->match([
      'left'=>function($xx){ 
        $x = $xx->extract();
        $dict = [
          'check_active_user_is_admin' => function(){ 
            api_response(202, ['message' => 'You are not allowed to perform this action!']);
          },
          'validate_new_user' => function(){ 
            api_response(202, ['message' => 'Please provide a valid name!']);
          },
          'create_user' => function(){ 
            api_response(202, ['message' => 'An expected error occurred!']);
          }
        ];
        return $dict[$x]();
      },
      'right'=>function($x){ 
        $response = [
          'message' => 'Create user success!',
          'user_info' => $x->extract(),
        ];

        api_response(201, $response);
      },
    ]);
  };

  $create_user->setPresenter($presenter);
  return $create_user;
  
}