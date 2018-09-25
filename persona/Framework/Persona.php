<?php
namespace Framework;

use DI\ContainerBuilder;
use Framework\Lib\Http\Response;
use Framework\Lib\Router\Router;
use Psr\Container\ContainerInterface;
use Framework\Lib\Interfaces\RequestInterface;
use Framework\Lib\Interfaces\ResponseInterface;



class Persona
{
    /**
     * router
     * 
     * @var ContainerInterface
     */
    private $container;
    private $configurationPath;
    /**
     * list of module
     *
     * @var array
     */
    private $components = [];
    private $middlewares = [];
    private $index = 0;


    public function __construct(string $configurationPath)
    {
        $this->configurationPath = $configurationPath;

    }
    /**
     * Undocumented function
     *
     * @param string $module
     * @return self
     */
    public function addModule(string $module) : self
    {
        $this->components[] = $module;
        return $this;
    }
    /**
     * Undocumented function
     *
     * @param string $middleware
     * @return self
     */
    public function pipe(string $middleware) : self
    {
        $this->middlewares[] = $middleware;
        return $this;
    }

    public function process(RequestInterface $request) : ResponseInterface
    {
        $middleware = $this->getMiddleware();
        if(is_null($middleware)){
            throw new \Exception("no middleware intercepted this request", 1);
        }elseif(is_callable($middleware)){
            return call_user_func_array($middleware,[$request,[$this,'process']]);
        }
        
    }
    /**
     * Undocumented function
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function listen(RequestInterface $request) : ResponseInterface
    {
        foreach ($this->components as $component) {
            $this->getContainer()->get($component);
        }
        return $this->process($request);
    }
    /**
     *
     * @return ContainerInterface
     */
    public function getContainer() : ContainerInterface
    {
        if ($this->container === null) {
            $builder = new ContainerBuilder();
            $env = getenv('ENV') ?: "production";
            if($env == "production"){
                $builder->writeProxiesToFile(true,'/stockage/tmp/proxies');
            }
            $builder->addDefinitions($this->configurationPath);
            $this->container = $builder->build();
        }
        return $this->container;
    }

    private function getMiddleware() : callable
    {
        if (array_key_exists($this->index, $this->middlewares)) {
            $middleware = $this->container->get($this->middlewares[$this->index]);
            $this->index++;
            return $middleware;
        }
        return null;

    }
}
