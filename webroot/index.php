<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
use Framework\Persona;
use Framework\Lib\Http\Request;
use Modules\Main\MainModule;
use Modules\Admin\AdminModule;
use Framework\Lib\Middleware\{
    TraillingSlashMiddleware,
        MethodMiddleware,
        NotFoundMiddleware,
        RouterMiddleware,
        DispatcherMiddleware,
        CsrfMiddleware
};

chdir(dirname(__dir__));;
define("PUBLIC_PATH", __DIR__);

require "vendor/autoload.php";

$loader = new Twig_Loader_Filesystem();
$app = (new Persona("persona/config/config.php"))
    ->addModule(MainModule::class)
    ->addModule(AdminModule::class)
    ->pipe(TraillingSlashMiddleware::class)
    ->pipe(MethodMiddleware::class)
    ->pipe(CsrfMiddleware::class)
    ->pipe(RouterMiddleware::class)
    ->pipe(DispatcherMiddleware::class)
    ->pipe(NotFoundMiddleware::class);
if (php_sapi_name() !== "cli") {
    $response = $app->listen(Request::createFromGlobals());
    $response->send();
}
    
    