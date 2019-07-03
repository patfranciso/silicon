<?php

class Validate_name_test extends Base_test
{
  public function test_valid_input()
  {
    $input = ['Li','Kolawole','James','Jan' ];
    $session['active_user'] = $this->get_admin_info();
    foreach($input as $item){
      $request = make_post_request($session,['name'=>$item]);
      $output = validate_new_user($request);
      $this->assertEquals($output, right($request));
    }
  }
  public function test_invalid_input()
  {
    $session['active_user'] = $this->get_admin_info();
    $result = left('validate_new_user');

    $input = ['L','','January Morgan','ArnoldSchwarzenneger'];
    foreach($input as $item){
      $request = make_post_request($session,['name'=>$item]);
      $output = validate_new_user($request);
      $this->assertEquals($output, $result);
    }
  }

}
