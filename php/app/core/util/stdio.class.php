<?php
namespace Util;
class Std{
    private static $__instance;
    private $in;
    private $out;
    private $err;
    private function __construct()
    {
    	$this->in = fopen("php://in","r");
    	$this->out = fopen("php://out","w");
    	$this->err = fopen("php://out","w");
    }
    public function instance() : self {
	if(is_null(self::$__instance))
	    self::$__instance = new Std();
	return self::$__instance;
    }
    public function input(int $bufsize = 65536) : mixed{
	return fread($this->in, $bufsize);
    }
    public function send(mixed $output) : void{
	fwrite($this->out, $output);
    }
    public function error(mixed $output) : void{
	fwrite($this->err, $output);
    }
    public function __destruct()
    {
    	fclose($this->in);
    	fclose($this->out);
    	fclose($this->err);
    }
}
