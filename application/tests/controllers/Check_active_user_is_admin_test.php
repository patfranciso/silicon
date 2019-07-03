<?php

class Check_active_user_is_admin_test extends Base_test
{
  public function test_valid_admin()
  {
    $session['active_user'] = $this->get_admin_info();
    $request = make_get_request($session,[]);
    $result = check_active_user_is_admin($request);
    $expect = right($request);
    $this->assertEquals($result, $expect);
  }
  
  public function test_invalid_admin()
  {
    $session['active_user'] = $this->get_employee_info();
    $request = make_get_request($session,[]);
    $result = check_active_user_is_admin($request);
    $expect = left('check_active_user_is_admin');
    $this->assertEquals($result, $expect);
  }
  
}
