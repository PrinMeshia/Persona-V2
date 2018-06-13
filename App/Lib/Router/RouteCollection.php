<?php
namespace App\Lib\Router;

use App\Lib\Interfaces\RequestInterface;

class RouteCollection
{
    /**
     *
     * @var array
     */
    private $routes = [];
    public function __construct()
    {
    }
    /**
     * save route object in array
     *
     * @param Route $route
     * @param string $method
     * @param string $path
     * @return void
     */
    public function addRoutes(Route $route, string $method, string $path)
    {
        $this->routes[$method][$path] = $route;
    }
    /**
     * Search route from current url
     *
     * @param RequestInterface $request
     * @return Route|null
     */
    public function match(RequestInterface $request) : ? Route
    {
        $slugs = [];
        $method = $request->getMethod();
        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $path => $route) {
                if ($this->processUri($path, $slugs, $request)) {
                    $route->addParams($slugs);
                    return $route;
                }
            }
        }
        return null;
    }
    private function processUri($path, array &$slugs, RequestInterface $request) : bool
    {
        $url = $request->getPathInfo();
        $uri = parse_url($url, PHP_URL_PATH);
        $func = $this->matchUriWithRoute($uri, $path, $slugs);
        return $func ? $func : false;
    }
    private function matchUriWithRoute(string $uri, string $path, array &$slugs) : bool
    {
        $uriSeg = preg_split('/[\/]+/', $uri, null, PREG_SPLIT_NO_EMPTY);
        $pathSeg = preg_split('/[\/]+/', $path, null, PREG_SPLIT_NO_EMPTY);
        if (self::compareSegments($uriSeg, $pathSeg, $slugs)) {
            return true;
        }
        return false;
    }
    private function CompareSegments(array $uriSeg, array $pathSeg, array &$slugs) : bool
    {
        if (count($uriSeg) != count($pathSeg)) return false;
        foreach ($uriSeg as $segIndex => $segment) {
            $segPath = $pathSeg[$segIndex];
            $is_slug = preg_match('/^{[^\/]*}$/', $segPath) || preg_match('/^:[^\/]*/', $segPath, $matches);
            if ($is_slug) {
                if (strlen(trim($segment)) === 0) {
                    return false;
                }
                $slugs[str_ireplace([':', '{', '}'], '', $segPath)] = $segment;
            } else if ($segPath !== $segment && $is_slug !== 1)
                return false;
        }
        return true;
    }
    private function findByMethod(string $name, string $method):?string{
        if(array_key_exists($method,$this->routes)){
            foreach ($this->routes[$method] as $key => $value) {
                if($value->getName() == $name){
                    return $key;
                }
            }
        }
        return null;
    }
    public function find(string $name, array $params = []) : ? string
    {
        $path = $this->findByMethod($name,'GET');
        if(is_null($path)){
            $path = $this->findByMethod($name,'POST');
        }
        if(is_null($path)){
            return null;
        }
        foreach ($params as $key => $value) {
            $path = str_ireplace([':'.$key, '{'.$key.'}'], $value, $path);
        }
        return $path;
    }

}
