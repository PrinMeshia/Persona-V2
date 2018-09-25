<?php
namespace Framework\Lib\Router;

use Framework\Lib\Interfaces\RequestInterface;

/**
 * Class register and get routes
 */
class Router 
{
    private $router;
    public function __construct()
    {
        $this->router = new RouteCollection();
    }

    public function delete(string $path, $callable,string $name){
        $this->add($path,$callable,$name,'DELETE');
    }
    public function put(string $path, $callable,string $name){
        $this->add($path,$callable,$name,'PUT');
    }
    public function get(string $path, $callable,string $name){
        $this->add($path,$callable,$name,'GET');
    }

    public function post(string $path, $callable,string $name){
        $this->add($path,$callable,$name,'POST');
    }
    public function add(string $path, $callable,string $name,$method){
       $this->router->addRoutes(new Route($name, $callable),$method,$path);
    }
    /**
     * @param RequestInterface $request
     * @return Route|null
     */
    public function match(RequestInterface $request){
        
        $result = $this->router->match($request);
        return $result ?? null;
    }
/**
 * Retour path of the Route
 *
 * @param string $name
 * @param array $params
 * @return string
 */
    public function generateUri(string $name, array $params = []){
       return $this->router->find($name,$params);
    }
}
