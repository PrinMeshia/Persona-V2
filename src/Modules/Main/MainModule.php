<?php
namespace src\Modules\Main;

use App\Lib\Router\Router;
use App\Lib\Interfaces\RequestInterface as Request;
use App\Lib\Interfaces\ResponseInterface as Response;
use App\Lib\Interfaces\RendererInterface;
use App\Lib\DI\ContainerInterface;



class MainModule
{
    private $renderer;
    public function __construct(ContainerInterface $container,RendererInterface $renderer)
    {
        $this->renderer = $renderer;
        $this->renderer->addPath('Main', __DIR__ . DIRECTORY_SEPARATOR . "Views");
        
        $router->get('/new-persona/blog', [$this, 'index'], 'main.index');
        $router->get('/new-persona/blog/{show}', [$this, 'show'], 'main.show');
    }
    public function index(Request $request) : string
    {
        return $this->renderer->render('@Main/index');
    }
    public function show(Request $request) : string
    {
        return $this->renderer->render('@Main/show', [
            'id' => $request->getAttribute('show')
        ]);
    }


}