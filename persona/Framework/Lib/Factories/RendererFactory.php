<?php
namespace Framework\Lib\Factories;

use Psr\Container\ContainerInterface;
use Framework\Lib\Render\Renderer;
use Framework\Lib\Router\Router;

class RendererFactory {
    public function __invoke(ContainerInterface $container):Renderer
    {
        return new Renderer($container->get('view.path'));
    }
}