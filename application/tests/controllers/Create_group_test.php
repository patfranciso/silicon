<?php

class Create_group_test extends Base_test
{
  public function test_success()
  {
    $create_group = make_create_group_usecase();
    $test_presenter = $this->mock_presenter();
    $test_create_group = $this->mock_create_group();

    $create_group->setPresenter($test_presenter);
    $create_group->setCreategroup($test_create_group);

    $post = ['name'=>'testGroup'];
    
    $session['active_user'] = $this->get_admin_info();
    $request = make_post_request($session, $post);
    $response = $create_group->execute($request);
    $this->assertEquals($response, 'success');
  }

  public function test_failed_validation()
  {
    $create_group = make_create_group_usecase();
    $test_presenter = $this->mock_presenter();
    $test_create_group = $this->mock_create_group();

    $create_group->setPresenter($test_presenter);
    $create_group->setCreateGroup($test_create_group);

    $post = ['name'=>'no spaces'];
    
    $session['active_user'] = $this->get_admin_info();
    $request = make_post_request($session, $post);
    $response = $create_group->execute($request);
    $this->assertEquals($response, 'validate_new_group');
  }

  public function test_failed_database_op()
  {
    $create_group = make_create_group_usecase();
    $test_presenter = $this->mock_presenter();
    $test_create_group = $this->mock_failed_create_group();

    $create_group->setCreateGroup($test_create_group);
    $create_group->setPresenter($test_presenter);

    $post = ['name'=>'nospaces'];
    
    $session['active_user'] = $this->get_admin_info();
    $request = make_post_request($session, $post);
    $response = $create_group->execute($request);
    $this->assertEquals($response, 'create_group');
  }

  private function mock_presenter(){
    return function($result){
      return $result->match([
        'left'=>function($x){ 
          return $x->extract();
        },
        'right'=>function($x){ return 'success';},
      ]);
    };
  }

  private function mock_create_group(){
    return function() {
      return right(["group_info"=>[
        "id"=>"155",
        "name"=>"testNoSpace",
        "group_id"=>"1",
        "created"=>"2019-07-04 13:14:38",
        "updated"=>"2019-07-04 13:14:38" 
      ]]);
    };
  }

  private function mock_failed_create_group(){
    return function() {return left('create_group');};
  }
}