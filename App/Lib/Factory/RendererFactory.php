<?php
namespace App\Lib\Factory;



class RendererFactory {
    public function __invoke(DIInterface $container)
    {
        return new Renderer($container->get('view.path'));
    }
}