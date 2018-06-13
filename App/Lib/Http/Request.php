<?php
namespace App\Lib\Http;

use App\Lib\Interfaces\RequestInterface;


class Request implements RequestInterface
{
    public $params = [];
    protected $server = [];
    public function __construct($getParams = [], $postParams = [], $serverParams = [])
    {

        $params = [];
        $this->params = array_merge($params, $getParams, $postParams);
        $this->server = $serverParams;
    }
    public static function createFromGlobals() : Request
    {
        return new Request($_GET, $_POST, $_SERVER);
    }
    public function getPathInfo() : ? string
    {
        return ($this->server['REQUEST_URI'] ?? '');
    }
    public function mergeParams($params = [])
    {
        $this->params = array_merge($this->params, $params);
    }
    public function getRequest() : array
    {
        return $this->params;
    }
    public function getMethod() : string
    {
        return isset($this->server['REQUEST_METHOD']) ? $this->server['REQUEST_METHOD'] : 'GET';
    }
    public function getAttribute($name) : ?string{
        return $this->params[$name] ?? null;
    }

}