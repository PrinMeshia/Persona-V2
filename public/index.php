<?php

use App\Lib\DI\Container;


define('APPROOT', dirname(dirname(__FILE__)));
  require_once '../App/Autoloader.php';
  $config = json_decode(file_get_contents(APPROOT.'/App/config/service.json'));
  $container = new Container($config);
  $persona = new \App\Lib\Persona($container, [
    \src\Modules\Main\MainModule::class
  ]);
  $response = $persona->run(\App\Lib\Http\Request::createFromGlobals());
  $response->send();