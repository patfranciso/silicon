<?php
require_once "lib.php";
function controllerName(){
  $ci =& get_instance();
  return $ci->router->fetch_class();
}

 function makeCommand($validate, $handle, $log, $present){
  return function (\Monad $request) use ($validate, $handle, $log, $present){
    return function ($inputState) use ($validate, $handle, $log, $present, $request){
      return $request->bind($validate, $inputState)
        ->bind($handle, $inputState)
        ->bind($log, $inputState)
        ->match ([
          'left'=>function(){printErr("Left output");},
          'right'=>function(){printSuccess("Right output");},
          '_'=>function(){printInfo ( "default");},
        ]);
    };
  };
}


// function createUser(CreateUserRequest $userRequest){
//     - validate()
//     - add record to database
//     - logEventData
//     - return id | record
// }