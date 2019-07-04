<?php
// bindables 
function validate_new_group($post_request){
  $group = $post_request->post;
  return preg_match('/^\w{2,16}$/',@$group['name']) ? right($post_request) : left(__FUNCTION__);
}

function get_group_by_id(int $input)
{
  $ci = &get_instance();
  $query = $ci->db->get_where('groups', ['id' => $input]);

  return $query->row();
}

function create_group($request){
  $ci =& get_instance();

  $group = $request->post; 
  $dcase = $ci->db->insert('groups', $group); 
  if($dcase === true){
    $id = $ci->db->insert_id();
    $group = get_group_by_id($id);

    return right($group);
  }
  else{
    return left(__FUNCTION__);
  }
}

function check_name_is_unique($request){
  $ci =& get_instance();

  $group_name = $request->post['name']; 
  $dcase = $ci->db->get_where('groups', ['name' => $group_name]);
  
  if($dcase->num_rows() === 0){
    return right($request);
  }
  else{
    return left(__FUNCTION__);
  }
}


class Create_group_usecase
{
  public $check_active_user_is_admin;
  public $validate_new_group;
  public $check_name_is_unique;
  public $create_group;
  public $presenter;


  public function execute($request)
  {
    $result = identity($request)
    ->bind($this->check_active_user_is_admin)
    ->bind($this->validate_new_group)
    ->bind($this->check_name_is_unique)
    ->bind($this->create_group);
    // trace($result);
    
    return ($this->presenter)($result);
  }

  public function setCheckActiveUserIsAdmin($check_active_user_is_admin)
  {
    $this->check_active_user_is_admin = $check_active_user_is_admin;

    return $this;
  }

  public function setValidateNewGroup($validate_new_group)
  {
    $this->validate_new_group = $validate_new_group;

    return $this;
  }

  public function setCheckNameIsUnique($check_name_is_unique)
  {
    $this->check_name_is_unique = $check_name_is_unique;

    return $this;
  }

  public function setCreateGroup($create_group)
  {
    $this->create_group = $create_group;

    return $this;
  }

  public function setPresenter($presenter)
  {
    $this->presenter = $presenter;

    return $this;
  }
}


function get_create_group_usecase(){
  $create_group = new Create_group_usecase;
  $create_group->setCheckActiveUserIsAdmin('check_active_user_is_admin')
    ->setValidateNewGroup('validate_new_group')
    ->setCheckNameIsUnique('check_name_is_unique')
    ->setCreateGroup('create_group');

  $presenter = function($result){
    $result->match([
      'left'=>function($xx){ 
        $x = $xx->extract();
        $dict = [
          'check_active_user_is_admin' => function(){ 
            api_response(202, ['message' => 'You are not allowed to perform this action!']);
          },
          'validate_new_group' => function(){ 
            api_response(202, ['message' => 'Please provide a valid name!']);
          },
          'check_name_is_unique' => function(){ 
            api_response(202, ['message' => 'Sorry! Another group has the name you provided!']);
          },
          'create_group' => function(){ 
            api_response(202, ['message' => 'An expected error occurred!']);
          }
        ];
        return $dict[$x]();
      },
      'right'=>function($x){ 
        $response = [
          'message' => 'Create group success!',
          'group_info' => $x->extract(),
        ];

        api_response(201, $response);
      },
    ]);
  };

  $create_group->setPresenter($presenter);
  return $create_group;
  
}