<?php
namespace App\Lib\Router;

use App\Lib\Interfaces\RequestInterface;

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

    public function get(string $path, callable $callable,string $name){
        $this->add($path,$callable,$name,'GET');
    }

    public function post(string $path, callable $callable,string $name){
        $this->add($path,$callable,$name,'POST');
    }
    private function add(string $path, callable $callable,string $name, string $method){
       $this->router->addRoutes(new Route($name, $callable),$method,$path);
    }
    /**
     * @param RequestInterface $request
     * @return Route|null
     */
    public function match(RequestInterface $request): ?Route{
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
    public function generateUri(string $name, array $params = []):?string{
       return $this->router->find($name,$params);
    }
}
