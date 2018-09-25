<?php
namespace Framework\Lib\Factories;

use Framework\Lib\Router\Router;
use Psr\Container\ContainerInterface;
use Framework\Lib\Render\TwigRenderer;
use Framework\Lib\Render\TwigExtensions;

class TwigRendererFactory {
    public function __invoke(ContainerInterface $container):TwigRenderer
    {
        $debug = $container->get('env') !== 'production';
        $viewPath = $container->get('view.path');
        $loader = new \Twig_Loader_Filesystem($viewPath);
        $twig = new \Twig_Environment($loader,[
            'debug' => $debug,
            'cache' => $debug ? false : $container->get("cache.path").'views',
            'auto_reload' => $debug
        ]);
        if($container->has('twig.extension')){
            foreach ($container->get('twig.extension') as $extension) {
                $twig->addExtension($extension);
            }
        }
       
        return new TwigRenderer($loader,$twig);
    }
}