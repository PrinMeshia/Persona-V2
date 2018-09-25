<?php
namespace Framework\Lib\Middleware;

use Framework\Lib\Router\Route;
use Framework\Lib\Http\Response;
use Psr\Container\ContainerInterface;
use Framework\Lib\Interfaces\RequestInterface;
use Framework\Lib\Interfaces\ResponseInterface;

class DispatcherMiddleware
{
    private $container;
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    public function __invoke(RequestInterface $request, callable $next)
    {
        $route = $request->getAttribute(Route::class);
        $callback = $route->getCallback();
        if (is_string($callback))
            $callback = $this->container->get($callback);
        if (is_array($callback) && is_string($callback[0])) {
            $callback[0] = $this->container->get($callback[0]);
        }
        $response = call_user_func_array($callback, [$request]);

        if (is_string($response)) {
            return new Response(200, [], $response);
        } elseif ($response instanceof ResponseInterface) {
            return $response;
        } else {
            throw new \Exception('the response is available');
        }
    }
}