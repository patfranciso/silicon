<?php

class Create_user_test extends Base_test
{
  public function test_success()
  {
    $create_user = get_create_user_usecase();
    $test_presenter = $this->mock_presenter();
    $test_create_user = $this->mock_create_user();

    $create_user->setPresenter($test_presenter);
    $create_user->setCreateUser($test_create_user);

    $post = ['name'=>'testUser'];
    
    $session['active_user'] = $this->get_admin_info();
    $request = make_post_request($session, $post);
    $response = $create_user->execute($request);
    $this->assertEquals($response, 'success');
  }

  public function test_failed_validation()
  {
    $create_user = get_create_user_usecase();
    $test_presenter = $this->mock_presenter();
    $test_create_user = $this->mock_create_user();

    $create_user->setPresenter($test_presenter);
    $create_user->setCreateUser($test_create_user);

    $post = ['name'=>'no spaces'];
    
    $session['active_user'] = $this->get_admin_info();
    $request = make_post_request($session, $post);
    $response = $create_user->execute($request);
    $this->assertEquals($response, 'validate_new_user');
  }

  public function test_failed_database_op()
  {
    $create_user = get_create_user_usecase();
    $test_presenter = $this->mock_presenter();
    $test_create_user = $this->mock_failed_create_user();

    $create_user->setCreateUser($test_create_user);
    $create_user->setPresenter($test_presenter);

    $post = ['name'=>'nospaces'];
    
    $session['active_user'] = $this->get_admin_info();
    $request = make_post_request($session, $post);
    $response = $create_user->execute($request);
    $this->assertEquals($response, 'create_user');
  }

  private function mock_presenter(){
    return function($result){
      return $result->match([
        'left'=>function($xx){ 
          $x = $xx->extract();
          $dict = [
            'check_active_user_is_admin' => function(){ return  'check_active_user_is_admin';},
            'validate_new_user' => function(){ return 'validate_new_user';},
            'create_user' => function(){ return 'create_user';}
          ];
          return $dict[$x]();
        },
        'right'=>function($x){ return 'success';},
      ]);
    };
  }

  private function mock_create_user(){
    return function() {
      return right(["user_info"=>[                   
        "id"=>"155",                     
        "name"=>"testNoSpace",           
        "group_id"=>"1",                 
        "created"=>"2019-07-04 13:14:38",
        "updated"=>"2019-07-04 13:14:38" 
      ]]);
    };
  }                                    

  private function mock_failed_create_user(){
    return function() {return left('create_user');};
  }                                    
}