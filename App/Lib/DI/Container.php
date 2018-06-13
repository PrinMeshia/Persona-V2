<?php
namespace App\Lib\DI;

class Container implements ContainerInterface
{
    private $registry = [];
    private $factory = [];
    private $instance = [];
    private $parameters = [];

    public function __construct($config)
    {
        $this->build($config);
    }
    public function setParameter(string $key, $value)
    {
        $this->parameters[$key] = $value;
    }
    public function setParameters(array $parameters)
    {

        $this->parameters = array_merge($this->parameters, $parameters);
    }
    public function getParameter(string $key)
    {
        if (array_key_exists($key))
            return $this->parameters[$key];
    }
    /**
     * Add element to the container
     *
     * @param string $key
     * @param callable $callback
     * @return void
     */
    public function set(string $key, callable $callback) : void
    {
        $this->registry[$key] = $callback;
    }
    /**
     * Add element instancied foreach call
     *
     * @param string $key
     * @param callable $callback
     * @return void
     */
    public function setFactory(string $key, callable $callback) : void
    {
        $this->factory[$key] = $callback;
    }
    /**
     * Add instance to the container
     *
     * @param [type] $instance
     * @return void
     */
    public function setInstance($instance)
    {
        $reflection = new \ReflectionClass($instance);
        $this->instance[$reflection->getName()] = $instance;
    }
    /**
     * return instance by key
     *
     * @param string $key
     * @return void
     */
    public function get(string $key)
    {
        if (isset($this->factory[$key])) {
            return $this->factory[$key];
        }
        if (!isset($this->instance[$key])) {
            if (isset($this->registry[$key])) {
                $this->instance[$key] = $this->registry[$key]($this);
            } else {
                $this->instance[$key] = $this->resolve($key);
            }
        }
        return $this->instance[$key];
    }
    /**
     * create instance of class by name 
     *
     * @param mixed $key
     * @return void
     */
    private function resolve($key)
    {
       
        $reflectionClass = new \ReflectionClass($key);
        if ($reflectionClass->isInstantiable()) {
            $constructor = $reflectionClass->getConstructor();
            if ($constructor) {
                $params = $constructor->getParameters();
                $constructorParams = [];
                foreach ($params as $param) {
                    if ($param->getClass()) {
                        $constructorParams[] = $this->get($param->getClass()->getName());
                    } else {
                        $constructorParams[] = $param->getDefaultValue();
                    }
                }
                return $reflectionClass->newInstanceArgs($constructorParams);
            } else {
                return $reflectionClass->newInstance();
            }
        } else if(interface_exists($key)){

        }else{
            throw new \Exception($key . " is not an instanciable Class", 1);
        }
    }
    /**
     * Undocumented function
     *
     * @param string $path
     */
    private function build($config)
    {
        $container = $this;
        $this->setParameters((array)$config->parameters);
        foreach ($config->services as $service) {
            $this->setFactory($service->name, function ($container) use ($service) {
                $param = $container->get($service->parameter) ?? null;
                $object = $service->class;
                
                return new $object($param);
            });
        }
    }


}
