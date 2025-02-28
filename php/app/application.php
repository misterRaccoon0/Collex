<?php
use Core\Routing\Route;
class Application{
    private static $app = null;
    private Route $router;
    // @param array $args
    private function __construct(array $args)
    {
    	$this->router = $args["router"];
    }
    // @param array $args
    public static function create(array $args) : Application{
	if(self::$app === null)
	    self::$app = new Application($args);
	return self::$app;
    }
    public function route() : void{
	if($this->router === null) throw new Exception;
    }   
}
