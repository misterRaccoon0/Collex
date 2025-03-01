<?php

namespace Core\Routing;
use ServerException;

class Route{
    private Route $child;
    private string $prefix;
    private array $_middleware = array();
    private array $routes = array(
	"GET" => array(),
	"POST" => array(),
	"PUT" => array(),
	"DELETE" => array()
    );
    // @param string $method
    // @param string $prefix
    private function __construct(string $prefix)
    {
	$this->prefix = $prefix;
    }
    public static function create(string $prefix = "") : self {
	return new Route($prefix);
    }
    public function middleware(Route $middleware) : self {
	$route = self::create();
	$route->_middleware = $middleware;
	return $route;
    }
    public function setMiddleware(mixed $middleware) : self{
	if(is_array($middleware))
	    $this->_middleware = $middleware;
	else if ($middleware instanceof Route)
	    $this->_middleware = array($middleware);
	else
	    throw new ServerException("Invalid middleware");
	return $this;
    }
    public function addMiddleware(mixed $middleware) : self {
	if(is_array($middleware))
	    array_push($this->_middleware,...$middleware);
	else if ($middleware instanceof Route)
	    $this->_middleware = array($middleware);
	else
	    throw new ServerException("Invalid middleware");
	return $this;
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
    // @param string $method
    // @param callable $callback
    private function method(string $method, string $path, mixed $route) : self {
	array_push($this->routes[$method][$path], $route);
	return $this;
    }
    public function get(string $path, mixed $callback) : Route {
	return $this->method("GET", $path, $callback);
    }
    public function post(string $path, mixed $callback) : Route {
	return $this->method("POST", $path, $callback);
    }
    public function put(string $path, mixed $callback) : Route {
	return $this->method("PUT", $path, $callback);
    }
    public function delete(string $path, mixed $callback) : Route {
	return $this->method("DELETE", $path, $callback);
    }
}
