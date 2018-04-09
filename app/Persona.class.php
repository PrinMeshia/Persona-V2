<?php
namespace app;

use app\core\Registry;

class Persona extends Registry
{
    private static $_instances = null;
    protected function __construct()
    {
    }
    
    public static function singleton()
    {
        if (self::$_instances === null) {
            ob_start();
            self::$_instances = new self();
        }
        return self::$_instances;
    }
 

    public function run(){
        echo "Persona initialized";
    }
}
