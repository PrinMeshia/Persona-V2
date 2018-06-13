<?php
namespace App\Lib\DI;

class Factory
{
    public static function create($name)
    {
        if (class_exists($name)) {
            return new $name();
        }
    }
} 
