<?php
define('DS',DIRECTORY_SEPARATOR);
require_once 'lib.php';

function is_admin($session = null){
  $session = $session ? $session : $_SESSION;
  $user = (array)@$session['active_user'];
  return @$user['kind'] === 'admin' ? true : false;
}

function have_active_user($session = null){
  $session = $session ? $session : $_SESSION;
  return !empty(@$session['active_user']);
}

function active_user($session = null){
  $session = $session ? $session : $_SESSION;
  return @$session['active_user'];
}

class PostRequest{
  public $session;
  public $post;
  public function __construct(array $session, array $post){
    $this->session = $session;
    $this->post = $post;
  }
}

function make_post_request(array $session = null , array $post = null):PostRequest{
  $session = $session ? $session : $_SESSION;
  $post = $post ? $post : $_POST;
  return new PostRequest($session, $post);
}

class GetRequest{
  public $session;
  public $get;
  public function __construct(array $session, array $get)
  {
    $this->session = $session;
    $this->get = $get;
  }
}

function make_get_request($session=null, $get=null) {
    $session = $session ? $session : $_SESSION;
    $get = $get ? $get : $_GET;
  return new GetRequest($session, $get);
}

// bindable
function check_active_user_is_admin($request = null){
  return is_admin($request->session) ? right($request) : left(__FUNCTION__) ;
}

function api_response(int $code, $response){
  $ci =& get_instance();
  $ci->output
    ->set_status_header($code)
    ->set_content_type('application/json', 'utf-8')
    ->set_output(
      json_encode($response, 
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}

$usecases = scandir(BASEPATH.'..'.DS.'use_cases'.DS);

foreach($usecases as $file){
  if(file_ext($file))
  require_once BASEPATH.'..'.DS.'use_cases'.DS.$file;
}

function file_ext($file)
{
  $path_parts = pathinfo($file);
  return $path_parts['extension'];
}
