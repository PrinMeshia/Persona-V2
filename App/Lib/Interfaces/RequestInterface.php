<?php
namespace App\Lib\Interfaces;
interface RequestInterface
{
    public function __construct($getParams = [], $postParams = [], $serverParams = []);
    public static function createFromGlobals();
    public function getPathInfo();
    public function mergeParams($params = []);
    public function getRequest();
    public function getAttribute($name);
}