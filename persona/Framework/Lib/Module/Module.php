<?php

namespace Framework\Lib\Module;


use Framework\Lib\Router\Router;
use Framework\Lib\Services\FlashService;
use Framework\Helpers\Traits\RouterTrait;
use Framework\Helpers\Validators\Validator;
use Framework\Lib\Interfaces\RequestInterface;
use Framework\Lib\Interfaces\RendererInterface;




class Module {
    protected $renderer;
    protected $router;
    protected $flash;
    
    /**
     *
     * @param ContainerInterface $container
     */
    public function __construct(Router $router,RendererInterface $renderer){
        $this->router = $router;
        $this->renderer = $renderer;
        $parts = explode('\\',get_class($this));
        $className = str_replace(["Module","module"],"",end($parts));
        $this->renderer->addPath($className, dirname(PUBLIC_PATH). "/src/Modules/".$className."/Views");
    }

    

    
}