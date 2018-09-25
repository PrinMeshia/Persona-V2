<?php
use function DI\factory;
use function DI\autowire;
use Framework\Lib\Router\Router;
use Framework\Lib\Session\Session;
use Framework\Lib\Factories\PdoFactory;
use Framework\Lib\Render\Extensions\{
    TwigExtensions,
    TwigTextExtensions,
    TwigCsrfExtension
};
use Framework\Lib\Interfaces\{
    SessionInterface,
    RendererInterface,
    PdoDBInterface
};
use Framework\Lib\Factories\TwigRendererFactory;
use Modules\Admin\Widgets\BlogWidget;
use Modules\Admin\AdminTwigExtensions;
use Framework\Lib\Middleware\CsrfMiddleware;



return [
    "env" => \DI\env('ENV','dev'),
    "view.path" => PUBLIC_PATH."/views",
    "cache.path" => dirname(PUBLIC_PATH)."/stockage/tmp//",
    "admin.prefix" => "/admin",
    "blog.prefix" => "/blog",
    "twig.extension" =>[
        \DI\get(TwigExtensions::class),
        \DI\get(TwigTextExtensions::class),
        \DI\get(TwigCsrfExtension::class)
    ],
    "config.database" => [
        "host" => "localhost",
        "user" => "root",
        "pass" => "",
        "name" => "persona"
    ],
    "admin.widgets" =>  \DI\add([
        \DI\get(BlogWidget::class)
    ]),
    SessionInterface::class =>autowire(Session::class),
    CsrfMiddleware::class => autowire()->constructor(\DI\get(SessionInterface::class)),
    Router::class => autowire(),
    RendererInterface::class => factory(TwigRendererFactory::class),
    PdoDBInterface::class => factory(PdoFactory::class),
    \Modules\Admin\AdminModule::class =>  autowire()->constructorParameter("prefix",\DI\get("admin.prefix")),
    \Modules\Admin\Actions\DashboardAction::class => autowire()->constructorParameter("widgets",\DI\get("admin.widgets")),
    \Modules\Admin\AdminTwigExtensions::class => autowire()->constructor(\DI\get("admin.widgets"))
];
