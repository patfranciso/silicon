<?php

class Delete_group_test extends Base_test
{
  public function test_success()
  {
    $delete_group = get_delete_group_usecase();
    $test_presenter = $this->mock_presenter();
    $test_remove_group_record = $this->mock_remove_group_record();
    $test_check_group_has_no_members = $this->mock_check_group_has_no_members();

    $delete_group->setPresenter($test_presenter);
    $delete_group->setRemoveGroupRecord($test_remove_group_record);
    $delete_group->setCheckGroupHasNoMembers($test_check_group_has_no_members);

    $post = ['group_id'=>4];
    
    $session['active_user'] = $this->get_admin_info();
    $request = make_post_request($session, $post);
    $response = $delete_group->execute($request);
    $this->assertEquals($response, 'success');
  }

  public function test_failed_check_group_has_no_members()
  {
    $delete_group = get_delete_group_usecase();
    $test_presenter = $this->mock_presenter();
    $test_remove_group_record = $this->mock_failed_remove_group_record();
    $test_check_group_has_no_members = $this->mock_failed_check_group_has_no_members();

    $delete_group->setRemoveGroupRecord($test_remove_group_record);
    $delete_group->setCheckGroupHasNoMembers($test_check_group_has_no_members);
    $delete_group->setPresenter($test_presenter);

    $post = ['group_id'=>'1'];
    
    $session['active_user'] = $this->get_admin_info();
    $request = make_post_request($session, $post);
    $response = $delete_group->execute($request);
    $this->assertEquals($response, 'check_group_has_no_members');
  }

  public function test_failed_database_op()
  {
    $delete_group = get_delete_group_usecase();
    $test_presenter = $this->mock_presenter();
    $test_remove_group_record = $this->mock_failed_remove_group_record();
    $test_check_group_has_no_members = $this->mock_check_group_has_no_members();

    $delete_group->setRemoveGroupRecord($test_remove_group_record);
    $delete_group->setCheckGroupHasNoMembers($test_check_group_has_no_members);
    $delete_group->setPresenter($test_presenter);

    $post = ['group_id'=>'1'];
    
    $session['active_user'] = $this->get_admin_info();
    $request = make_post_request($session, $post);
    $response = $delete_group->execute($request);
    $this->assertEquals($response, 'remove_group_record');
  }

  private function mock_presenter(){
    return function($result){
      return $result->match([
        'left'=>function($xx){ 
          $x = $xx->extract();
          $dict = [
            'check_active_user_is_admin' => function(){ return  'check_active_user_is_admin';},
            'check_group_has_no_members' => function(){ return 'check_group_has_no_members';},
            'remove_group_record' => function(){ return 'remove_group_record';}
          ];
          return $dict[$x]();
        },
        'right'=>function($x){ return 'success';},
      ]);
    };
  }

  private function mock_remove_group_record(){
    return function() {
      return right('success');
    };
  }                                    

  private function mock_failed_remove_group_record(){
    return function() {return left('remove_group_record');};
  }  
                                    
  private function mock_check_group_has_no_members(){
    return function() {
      return right('success');
    };
  }                                    

  private function mock_failed_check_group_has_no_members(){
    return function() {return left('check_group_has_no_members');};
  }                                    
}