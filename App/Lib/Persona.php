<?php
namespace App\Lib;

use App\Lib\Interfaces\RequestInterface;
use App\Lib\Interfaces\ResponseInterface;
use App\Lib\Http\Response;
use App\Lib\Router\Router;
use App\Lib\DI\ContainerInterface;


class Persona
{   
    /**
     * list of module
     *
     * @var array
     */
    private $components = [];
    /**
     * router
     * 
     * @var ContainerInterface
     */
    private $container;
    public function __construct(ContainerInterface $container, array $components = [])
    {
        $this->container = $container;
        foreach ($components as $component) {
            $this->components[] = $container->get($component);
        }
    }
    /**
     * Undocumented function
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function run(RequestInterface $request):ResponseInterface{
        $uri = $request->getPathInfo();
        if(!empty($uri) && $uri[-1] === "//new-persona//"){
            return (new Response())->redirect(substr($uri,0,-1));
        }
        $route = $this->container->get('Router')->match($request);
        if(is_null($route)){
            return new Response(404,[],'<h1>Error</h1>');
        }
        $request->mergeParams($route->getParams());
        $response = call_user_func_array($route->getCallback(),[$request]);
        if(is_string($response)){
            return new Response(200,[],$response);
        }elseif($response instanceof ResponseInterface ){
            return $response;
        }else{
            throw new \Exception('the response is available');
        }
    }
}
