<?php
namespace Framework\Lib\Module;
use Framework\Lib\Router\Router;
use Framework\Lib\Services\FlashService;

use Framework\Helpers\Validators\Validator;
use Framework\Lib\Interfaces\RequestInterface;
use Framework\Lib\Interfaces\RendererInterface;

class CrudAction{
    use \Framework\Helpers\Traits\RouterTrait;
    protected $renderer;
    protected $flash;
    protected $router;
    public function __construct(RendererInterface $render,FlashService $flash,Router $router){
        $this->renderer = $render ;
        $this->flash = $flash ;
        $this->router = $router ;
    }
    protected function getValidator(RequestInterface $request){
        return new Validator($request->getRequest());
    }    
    protected function formParams(array $params):array{
        return $params;
    }
    
}