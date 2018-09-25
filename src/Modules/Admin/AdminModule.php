<?php
namespace Modules\Admin;

use Framework\Lib\Module\Module;
use Framework\Lib\Router\Router;
use Framework\Lib\Interfaces\RendererInterface;
use Framework\Lib\Interfaces\RequestInterface as Request;
use Framework\Lib\Interfaces\ResponseInterface as Response;
use Modules\Admin\Actions\DashboardAction;

class AdminModule extends Module
{
    public function __construct(Router $router, RendererInterface $renderer,AdminTwigExtensions $AdminTwigExtensions,    $prefix)
    {
        parent::__construct($router, $renderer);
        if($renderer instanceof \Framework\Lib\Render\TwigRenderer){
            $renderer->getTwig()->addExtension($AdminTwigExtensions);
        }
    }
}
