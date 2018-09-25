<?php
namespace Framework\Helpers\Traits;

use Framework\Lib\Interfaces\ResponseInterface;
use Framework\Lib\Http\Response;


trait RouterTrait {
    public function redirect(string $name, array $params = []):ResponseInterface
    {
        $redirectUrl = $this->router->generateUri($name,$params);
        return (new Response())
            ->setStatusCode(301)
            ->setHeader('Location',$redirectUrl);
            
    }
}