<?php

class Assign_user_to_group_usecase
{
  public $check_active_user_is_admin;
  public $check_user_is_not_already_in_group;
  public $save_entry_database;
  public $presenter;

  public function execute($request)
  {
    $result = identity($request)
    ->bind($this->check_active_user_is_admin)
    ->bind($this->check_user_is_not_already_in_group)
    ->bind($this->save_entry_database);
    
    return ($this->presenter)($result);
  }

  public function setCheckActiveUserIsAdmin($check_active_user_is_admin)
  {
    $this->check_active_user_is_admin = $check_active_user_is_admin;

    return $this;
  }

  public function setCheckUserIsNotAlreadyInGroup($check_user_is_not_already_in_group)
  {
    $this->check_user_is_not_already_in_group = $check_user_is_not_already_in_group;

    return $this;
  }

  public function setSaveEntryDatabase($save_entry_database)
  {
    $this->save_entry_database = $save_entry_database;

    return $this;
  }

  public function setPresenter($presenter)
  {
    $this->presenter = $presenter;

    return $this;
  }
}

// bindables
function check_user_is_not_already_in_group($request){
  $user_id = $request->post['user_id'];
  $group_id = $request->post['group_id'];
  $stored_user = get_user_by_id($user_id);
  var_export($stored_user);
  echo intval($user_id). ' vs ' .intval($stored_user->id) ;
  return intval($group_id) !== intval($stored_user->id) ? right($request) : left(__FUNCTION__);
}

function save_entry_database($request){
  $ci =& get_instance();
  $user_id = $request->post['user_id'];
  $group_id = $request->post['group_id'];

  $ci->db->where('id', $user_id);
  $ci->db->update('users', ['group_id'=>$group_id]);
}

function make_assign_user_to_group_usecase(){
  $create_group = new Assign_user_to_group_usecase;
  $create_group->setCheckActiveUserIsAdmin('check_active_user_is_admin')
    ->setCheckUserIsNotAlreadyInGroup('check_user_is_not_already_in_group')
    ->setSaveEntryDatabase('save_entry_database');

  $presenter = function($result){
    $result->match([
      'left'=>function($xx){ 
        $x = $xx->extract();
        $dict = [
          'check_active_user_is_admin' => function(){ 
            api_response(202, ['message' => 'You are not allowed to perform this action!']);
          },
          'check_user_is_not_already_in_group' => function(){ 
            api_response(202, ['message' => 'The user is already in the selected group!']);
          },
          'save_entry_database' => function(){ 
            api_response(202, ['message' => 'Unknown database error!']);
          },
        ];
        return $dict[$x]();
      },
      'right'=>function($x){ 
        $response = [
          'message' => 'Create group success!',
          'group_info' => $x->extract(),
        ];

        api_response(200, $response);
      },
    ]);
  };

  $create_group->setPresenter($presenter);
  return $create_group;
  
}