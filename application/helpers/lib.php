<?php
require_once('console.php');

abstract class Monad {

  protected $value;

  public function __construct($value) {
    $this->value = $value;
  }

  public static function unit($value) {
    if ($value instanceof static) {
      return $value;
    }
    return new static($value);
  }

  public function bind(callable $function) {
    $numargs = func_num_args();
    $arg_list = func_get_args();
    $args = $numargs < 2 ? [] : array_slice($arg_list,1);
    $vv = $this->runCallback($function, $this->value, $args);
    return isMonad($vv) ? $vv : $this::unit($vv);
  }

  public function extract() {
    if ($this->value instanceof self) {
      return $this->value->extract();
    }
    return $this->value;
  }

  protected function runCallback($function, $value, array $args = array()) {
    array_unshift($args, $value);
    return call_user_func_array($function, $args);
  }

  public function dvi(){
    return ["kind" => strtolower( get_class($this)), "payload" => $this->value];
  }

  public function toString(){
    $vv = get_class($this). ' ('. var_export($this->value, true).')';
    return $vv;
  }

  public function __toString(){
    return $this->toString();
  }

  public function __invoke($input){
    $fn = $this->value;
    if(is_callable($fn)){
      $vv = $fn($input);      
      return isMonad($vv) ? $vv : $this::unit($vv);
    }
    else return $input;
  }

  public function match($dict){
    $dcase = strtolower( get_class($this));

    if( !in_array($dcase, array_keys($dict)) )
      $dcase = '_';

    $numargs = func_num_args();
    $arg_list = func_get_args();
    $args = $numargs < 2 ? [] : array_slice($arg_list,1);

    $val = $this->value;

    if(is_array($dict[$dcase])){
      if( !in_array($val, array_keys($dict[$dcase])) )
        $val = '_';

      return $this->runCallback($dict[$dcase][$val], $this, $args);
    }
    else return $this->runCallback($dict[$dcase], $this, $args);
  }
}

class Identity extends Monad {}

function identity($value){
  return new Identity($value);
}
  
abstract class Either extends Monad{}
class Left extends Either{
  public function bind(callable $function) {
    return $this;
  }
}

class Right extends Either{}

function left($value){
  return new Left($value);
}

function right($value){
  return new Right($value);
}

function printErr($x){
  if (@$_SERVER['SERVER_NAME']) 
   echo ('<pre style="color:red">'.$x.'</pre>') ; 
  else Console::log($x, 'red');
}
function printSuccess($x){
  if (@$_SERVER['SERVER_NAME'] )
    echo '<pre style="color:#1cee23">'.$x.'</pre>' ;
  else Console::log($x, 'light_green');
}
function printInfo($x){
  if (@$_SERVER['SERVER_NAME'] ) 
    echo '<pre style="color:cyan">'.$x.'</pre>' ;
  else Console::log($x, 'cyan');
}

function isMonad($m){
  return $m instanceof Monad;
}
function mToString( $p){
  return var_export($p, true);
}
function trace($m){
  $x = isMonad($m) ? $m->dvi() : $m;
  $dcase = !empty($x['kind']) ? $x['kind']: '_';
  
  if( !in_array($dcase, ['left','right','some','none']) )
    $dcase = '_';
    
  $api = [
    'none'=> function ($x){ return printErr('None');},
    'some'=> function ($x){ return printSuccess(mToString($x['payload']));},
    'left'=> function ($x){ return printErr(mToString($x['payload']));},
    'right'=> function ($x){ return printSuccess(mToString($x['payload']));},
    '_'=> function ($x){ return printInfo(!empty($x['payload'])? mToString($x['payload']): mToString($x));}
  ];
  $api[$dcase]($x);
};

function monadType(Monad $monad){ 
  return strtolower(get_class($monad));
}
