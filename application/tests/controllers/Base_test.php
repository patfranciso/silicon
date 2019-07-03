<?php
/**
 * Part of ci-phpunit-test
 *
 * @author     Kenji Suzuki <https://github.com/kenjis>
 * @license    MIT License
 * @copyright  2015 Kenji Suzuki
 * @link       https://github.com/kenjis/ci-phpunit-test
 */

class Base_test extends TestCase
{
	public function __construct(){
    parent::__construct();
    $ci =& get_instance();
    $ci->load->helper('myfns');
  }
  
  public function test_base()
  {
    $this->assertTrue(true);
  }

  protected function get_admin_info(){
    $admin = ['id' => '1', 
    'name' => 'admin', 'kind' => 'admin', 'email' => 'admin@example.com'];
    return $admin; 
  }

  protected function get_employee_info(){
    return ['id' => '2', 
    'name' => 'employee', 'kind' => 'employee', 'email' => 'employee@example.com'];
  }
}
