<?php

namespace Core\Routing;
use ServerException;

abstract class BaseRoute{
    private array $routes = array(
	"GET" => array(),
	"POST" => array(),
	"PUT" => array(),
	"DELETE" => array()
    );
    private array $raw_route = array();
    // @param string $method
    // @param callable $callback
    private function method(string $path, mixed $route, string $method = "*") : self {
	if($method == "*")
	    foreach(array_keys($this->routes) as $key)
		array_push($this->routes[$key][$path], $route);
	else
	    array_push($this->routes[$method][$path], $route);
	return $this;
    }
    public function get(string $path, mixed $callback) : self {
	return $this->method($path, $callback, "GET");
    }
    public function post(string $path, mixed $callback) : self {
	return $this->method($path, $callback, "POST");
    }
    public function put(string $path, mixed $callback) : self {
	return $this->method($path, $callback, "PUT");
    }
    public function delete(string $path, mixed $callback) : self {
	return $this->method($path, $callback, "DELETE");
    }

}

class Middleware extends BaseRoute{
    // @type Route | BaseRoute
    private mixed $parent;
    private $target;
    public function __construct(Route $parent)
    {
	$this->parent = $parent;
    }
    // @param string $name
    // @param array $arguments
    public function __call(string $name, array $arguments): Route
    {
	if(!is_callable($this->parent->{$name}))
	    throw new ServerException("$name is not a function of Route");
	call_user_func($this->parent->{$name},$arguments);
	return $this->parent;
    }
};
class Route extends BaseRoute{
    private Route $child;
    private string $prefix;
    // @param string $method
    // @param string $prefix
    private function __construct(string $prefix)
    {
	$this->prefix = $prefix;
    }
    public static function create(string $prefix = "") : self {
	return new Route($prefix);
    }
    public function middleware(mixed $middleware) : Middleware {
	$middleware_route = new Middleware($this);
	return $middleware_route;
    }
    public function route(mixed $route): Route{
	if(is_string($route))
	    $route = new Route($route);
	else if (!$route instanceof Route)
	    throw new ServerException("Invalid route");
	return $route;
    }
    // @param mixed $child
    public function use(mixed $child) : Route{
	$child = $this->route($child);
	$this->child = $child;
	return $child;
    }
}
