<?php
namespace App\Lib\Router;

/**
 * Route
 */
class Route 
{
    /**
     *
     * @var string
     */
    private $name;
    /**
     *
     * @var callable
     */
    private $callback;
    /**
     *
     * @var array
     */
    private $parameters = [];
    /**
     * Undocumented function
     *
     * @param string $name
     * @param callable $callback
     * @param array $parameters
     */
    public function __construct(string $name, callable $callback)
    {
        $this->name =$name;
        $this->callback = $callback;
    }
    /**
     * get route name
     *
     * @return string
     */
    public function getName():string{
        return $this->name;
    }
    /**
     * get callback
     *
     * @return callable
     */
    public function getCallback():callable{
        return $this->callback;
    }
    /**
     * Return url parameter
     *
     * @return array
     */
    public function getParams():array{
        return $this->parameters;
    }

    public function addParams(array $parameters){
        $this->parameters = $parameters;
    }

}

