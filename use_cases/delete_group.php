<?php

// bindables
function remove_group_record($request)
{
  $ci = &get_instance();
  $id = $request->post['group_id'];
  $ci->db->delete('groups', ['id'=>$id]);
  if ($ci->db->affected_rows() > 0) {
    return right('Delete group success!');
  } else {
    return left(__FUNCTION__);
  }
}

function check_group_has_no_members($request){
  $group_id = $request->post['group_id'];
  $ci =& get_instance();
  $query = $ci->db->where('group_id', $group_id)->get('users');
  
  return $query->num_rows() < 1 ? right($request) : left(__FUNCTION__);
}

class Delete_group_usecase
{
  public $check_active_user_is_admin;
  public $remove_group_record;
  public $presenter;
  
  public function execute($request)
  {
    $result = identity($request)
      ->bind($this->check_active_user_is_admin)
      ->bind($this->check_group_has_no_members)
      ->bind($this->remove_group_record);
    
    return ($this->presenter)($result);
  }

  public function setCheckActiveUserIsAdmin($check_active_user_is_admin)
  {
    $this->check_active_user_is_admin = $check_active_user_is_admin;

    return $this;
  }

  public function setRemoveGroupRecord($remove_group_record)
  {
    $this->remove_group_record = $remove_group_record;

    return $this;
  }

  public function setCheckGroupHasNoMembers($check_group_has_no_members)
  {
    $this->check_group_has_no_members = $check_group_has_no_members;

    return $this;
  }
  
  public function setPresenter($presenter)
  {
    $this->presenter = $presenter;

    return $this;
  }
}

function get_delete_group_usecase()
{  
  $delete_group = new Delete_group_usecase;
  $delete_group->setCheckActiveUserIsAdmin('check_active_user_is_admin')
      ->setCheckGroupHasNoMembers('check_group_has_no_members')
      ->setRemoveGroupRecord('remove_group_record');
  
  $presenter = function($result){
    $result->match([
      'left'=>function($xx){ 
        $x = $xx->extract();
        $dict = [
          'check_active_user_is_admin' => function(){
            api_response(202, ['message' => 'You are not allowed to perform this action!']);
          },
          'check_group_has_no_members' => function(){ 
            api_response(202, ['message' => 'This group still has members!']);
          },
          'remove_group_record' => function(){ 
            api_response(202, ['message' => 'An unexpected database error occurred!']);
          },
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

  $delete_group->setPresenter($presenter);
  return $delete_group;
}
