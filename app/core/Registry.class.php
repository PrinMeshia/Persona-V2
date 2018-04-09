<?php
namespace app\core;

abstract class Registry
{
    protected static $objects = [];
    protected static $conf = [];

    protected function __construct(){}
    public abstract function run();
    public abstract function listen();

    function __set($index, $value)
    {
        self::$objects[ $index ] = new $value();
    }

    function __get($index)
    {
        if(!isset(self::$objects[ $index ])){
            if($this->config->class->{$index}){
                $this->$index = $this->config->class->{$index};
            }
        }
        if( is_object ( self::$objects[ $index ] ) )
            return self::$objects[ $index ];
    }

    function __unset($index)
    {
        if( is_object ( self::$objects[ $index ] ) )
            unset(self::$objects[ $index ]);
    }
}

