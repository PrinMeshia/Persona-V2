<?php

namespace Framework\Lib\Middleware;

use Framework\Lib\Interfaces\RequestInterface;
use Framework\Lib\Http\Response;

class NotFoundMiddleware{
    public function __invoke(RequestInterface $request,callable $next){
        return new Response(404, [], 'Error 404');
    }
}