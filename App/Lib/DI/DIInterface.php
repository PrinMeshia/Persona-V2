<?php
namespace App\Lib\DI;

Interface DIInterface 
{
    /**
     * Add element to the container
     *
     * @param string $key
     * @param callable $callback
     * @return void
     */
    public function set(string $key, callable $callback) : void;
    /**
     * Add element instancied foreach call
     *
     * @param string $key
     * @param callable $callback
     * @return void
     */
    public function setFactory(string $key, callable $callback) : void;
    /**
     * Add instance to the container
     *
     * @param [type] $instance
     * @return void
     */
    public function setInstance($instance);
    /**
     * return instance by key
     *
     * @param string $key
     * @return void
     */
    public function get(string $key);
   

}
