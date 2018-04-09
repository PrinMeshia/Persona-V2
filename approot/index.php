<?php

use app\Persona;

$startTime = microtime(true);

require_once  '../app/Autoloader.class.php';

Persona::singleton()->run();