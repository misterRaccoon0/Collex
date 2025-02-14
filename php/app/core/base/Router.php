<?php
namespace Core\Base;

trait BaseRoute{
    public string $path;
    public object $route_callback;
}
abstract class Route{
    use BaseRoute;
    public function __construct(string $path, object $callback){
	$this->path = $path;
	$this->route_callback = $callback;
    }
    public function __invoke(): mixed
    {
	return $this["router_callback"]();
    }
}
abstract class BaseRouter{
    public function __construct(string $path, object $callback){
	parent::__construct($path, $callback);
    }
    public function __invoke(): object | null {
	return parent::__invoke();
    }
    public abstract function next(): mixed | null;
}


class GetterRouter extends BaseRouter{
    public function __construct(string $path, object $callback)
    {
	parent::__construct($path, $callback);
    }
    public function next($callback){

    }
}
class PosterRouter extends BaseRouter{
    private static array $__routes = array();
    public function __construct(string $path, object $callback)
    {
	parent::__construct($path, $callback);
    }
    public function next($callback){

    }
}
class PutterRouter extends BaseRouter{
    private static array $__routes = array();
    public function __construct(string $path, object $callback)
    {
	parent::__construct($path, $callback);
    }
    public function next($callback){

    }
}
class DeleterRouter extends BaseRouter{
    private static array $__routes = array();
    public function __construct(string $path, object $callback)
    {
	parent::__construct($path, $callback);
    }
    public function next($callback){

    }
}
class Router extends BaseRouter{
    private static array $__routes = array();
    // @param string $path
    private function __construct(string $path, object $callback)
    {
	parent::__construct($path, $callback);
    }
    public function next(){

    }
    // @param string $path
    public static function route(string $path) : Router {
	$route = new self($path, null);
	return $route;
    }
    public static function route(object $callback) : Router {
	$route = new self(null, $callback);
	return $route;
    }
    // @param string $basepath
    public static function create(string $basepath) : Router {
	$route = new self($basepath);
	return $route;
    }
    public function GET(string $path, object $func) : GetterRouter {
	$router = new GetterRouter($path,$func);
    }
    public function POST(string $path, object $func){
	PosterRouter
    }
    public function PUT(string $path, object $func){
	PutterRouter
    }
    public function DELETE(string $path, object $func){
	DeleterRouter
    }
}
