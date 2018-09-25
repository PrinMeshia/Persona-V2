<?php
namespace Framework\Lib\Middleware;

use Framework\Lib\Http\Response;
use Framework\Lib\Interfaces\RequestInterface;


class TraillingSlashMiddleware{
    public function __invoke(RequestInterface $request,callable $next){
        
        $uri = $request->getPathInfo();
        if (!empty($uri) && $uri[-1] === "//persona-v2//") {
            return (new Response())->redirect(substr($uri, 0, -1));
        }
        return $next($request);
    }
}