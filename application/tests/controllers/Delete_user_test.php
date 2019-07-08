<?php

class Delete_user_test extends Base_test
{
  public function test_success()
  {
    $delete_user = make_delete_user_usecase();
    $test_presenter = $this->mock_presenter();
    $test_delete_user = $this->mock_delete_user();

    $delete_user->setPresenter($test_presenter);
    $delete_user->setDeleteUser($test_delete_user);

    $post = ['id'=>'4'];
    
    $session['active_user'] = $this->get_admin_info();
    $request = make_post_request($session, $post);
    $response = $delete_user->execute($request);
    $this->assertEquals($response, 'success');
  }

  public function test_failed_database_op()
  {
    $delete_user = make_delete_user_usecase();
    $test_presenter = $this->mock_presenter();
    $test_delete_user = $this->mock_failed_delete_user();

    $delete_user->setDeleteUser($test_delete_user);
    $delete_user->setPresenter($test_presenter);

    $post = ['name'=>'nospaces'];
    
    $session['active_user'] = $this->get_admin_info();
    $request = make_post_request($session, $post);
    $response = $delete_user->execute($request);
    $this->assertEquals($response, 'delete_user');
  }

  private function mock_presenter(){
    return function($result){
      return $result->match([
        'left'=>function($xx){ 
          $x = $xx->extract();
          $dict = [
            'check_active_user_is_admin' => function(){ return  'check_active_user_is_admin';},
            'delete_user' => function(){ return 'delete_user';}
          ];
          return $dict[$x]();
        },
        'right'=>function($x){ return 'success';},
      ]);
    };
  }

  private function mock_delete_user(){
    return function() {
      return right('success');
    };
  }                                    

  private function mock_failed_delete_user(){
    return function() {return left('delete_user');};
  }                                    
}