<?php

namespace Core\Routing;
use ServerException;

class Route{
    private Route $child;
    private string $prefix;
    private Route $_middleware;
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
    public function middleware(Route $middleware){
	$route = new Route();
	$route->middleware = $middleware;
    }
    public function route(mixed $route): Route{
	if(is_string($child))
	    $route = new Route($child);
	else if (!$child instanceof Route)
	    throw new ServerException("Invalid route");
	return $route;
    }
    // @param mixed $child
    public function use(mixed $child) : Route{
	$child = $this->route($child);
	$this->child = $child;
	return $child;
    }
    public function middleware(mixed $middleware) : self {
	switch(gettype($middleware)){
	    case "array":
		$this->_middleware = $middleware;
		break;
	    case "callable":
		$this->_middleware = array($middleware);
		break;
	    default:
		throw new ServerException("Invalid middleware");
		break;
	}
	return $this;
    }
    // @param string $method
    // @param callable $callback
    private function method(string $method, callable $callback) : self {
	array_push($this->routes->{$method}, $callback);
	return $this;
    }
    public function get(callable $callback) : Route {
	return $this->method("GET",$callback);
    }
    public function post(callable $callback) : Route {
	return $this->method("POST",$callback);
    }
    public function put(callable $callback) : Route {
	return $this->method("PUT",$callback);
    }
    public function delete(callable $callback) : Route {
	return $this->method("DELETE",$callback);
    }
}
