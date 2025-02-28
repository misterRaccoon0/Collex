<?php
enum HTTPStatus : int{
    case CONTINUE = 100;
    case SWITCHING_PROTOCOLS = 101;
    case PROCESSING = 102;
    case EARLY_HINTS = 103;

    case OK = 200;
    case CREATED = 201;
    case NON_AUTHORITIVE_INFORMATION = 202;
    case NO_CONTENT = 203;
    case RESET_CONTENT = 204;
    case PARTIAL_CONTENT = 205;
    case MULTISTATUS = 206;
    case REPORTED = 207;
    case IM_USED = 226;

    case MULTIPLE_CHOICES = 300;
    case MOVED_PERMANENTLY = 301;
    case FOUND = 302;
    case SEE_OTHER = 303;
    case NOT_MODIFIED = 304;
    case USE_PROXY = 305;
    case UNUSED = 306;
    case TEMPORARY_REDIRECT= 307;
    case PERMANENT_REDIRECT = 308;

    case BAD_REQUEST = 400;
    case UNAUTHORIZED = 401;
    case PAYMENT_REQUIRED= 402;
    case FORBIDDEN= 403;
    case NOT_FOUND = 404;
    case METHOD_NOT_ALLOWED= 405;
    case NOT_ACCEPTABLE= 406;
    case PROXY_AUTHENTICATION_REQUIRED = 407;
    case REQUEST_TIMEOUT= 408;
    case CONFLICT = 409;
    case GONE = 410;
    case LENGTH_REQUIRED = 411;
    case PRECONDITION_FAILED = 412;
    case CONTENT_TOO_LARGE = 413;
    case URI_TOO_LONG = 414;
    case UNSUPPORTED_MEDIA_TYPE = 415;
    case RANGE_NOT_SATISFIABLE = 416;
    case EXPECTATION_FAILED = 417;
    case IM_A_TEAPOT = 418;
    case MISDIRECTED_REQUEST = 421;
    case UNPROCESSABLE_CONTENT = 422;
    case LOCKED = 423;
    case FAILED_DEPENDANCY = 424;
    case TOO_EARLY = 425;
    case UPGRADE_REQUIRED = 426;
    case PRECONDITION_REQUIRED= 428;
    case TOO_MANY_REQUESTS = 429;
    case REQUEST_HEADER_FIELDS_TOO_LARGE = 431;
    case UNAVAILABLE_FOR_LEGAL_REASONS = 451;

    case INTERNAL_SERVER_ERROR = 500;
    case NOT_IMPLEMENTED = 501;
    case BAD_GATEWAY = 502;
    case SERVICE_UNAVAILABLE = 503;
    case GATEWAY_TIMEOUT = 504;
    case HTTP_VERSION_NOT_SUPPORTED = 505;
    case VARIANT_ALSO_NEGOTIATES = 506;
    case INSUFFICIENT_STORAGE = 507;
    case LOOP_DETECTED = 508;
    case NOT_EXTENDED = 510;
    case NETWORK_AUTHENTICATION_REQUIRED = 511;

    public function __invoke(): mixed
    {
	return $this->value;
    }
}

class ServerException extends Exception {
    public function __construct(string $msg)
    {
	parent::__construct($msg, 500);
	
    }
    public function raise() : void{
	throw $this;
    }
}

class HTTPHeader{

    private const response_headers = [
	"content_type" => "Content-Type",

    ];

    private const multivalued_headers = [

    ];

    private array $headers;
    // @param array $headers
    public function __construct(array $headers = array()){
	$this->headers = $headers;
    }

    // @param string $name
    // @param mixed $arguments
    public function __call(string $name, $arguments) : mixed
    {
	$name = "HTTPHeader::$name";
	if(is_callable("HTTPHeader::$name")){
	    return call_user_func($this->{$name},$arguments);
	}
	else if($name == "custom_header")
	    [$name, $value] = $arguments;
	else if(key_exists($name, self::response_headers))
	    [$name, $value] = [self::response_headers[$name], $arguments[0]];
	else if(in_array($name, self::response_headers))
	    $value = $arguments[0];
	else
	    throw new ValueError("Header is not valid");

	if(in_array($name, self::multivalued_headers)){
	    if(in_array($name, $this->headers))
		$this->headers[$name] = array($value);
	    else
		array_push($this->headers[$name], $value);
	}else
	    $this->headers[$name] = $value;
	return $this;
    }
    public function __toString(): string
    {
	$header_array = array();
	foreach(array_keys($this->headers) as $header_name){
	    if(is_array($this->headers[$header_name]))
		foreach($this->headers[$header_name] as $value){
		    array_push($header_array, "$header_name: $value");
		}
	    else
		array_push($header_array, "$header_name: " . $this->headers[$header_name]);
	}
	return implode("\r\n\r\n", $header_array);
    }
}


class Response{
    private readonly mixed $body;
    private int $status; 
    private HTTPHeader $headers;
    //	@param mixed $body
    //	@param HTTPStatus $status
    //	@param array $headers
    public function __construct(
	mixed $body,
	HTTPStatus $status = HTTPStatus::OK,
	array $headers = array()
    ){
	if (!($status instanceof HTTPStatus)){
	    $body = null;
	    $status = HTTPStatus::INTERNAL_SERVER_ERROR;
	}
	$this->body = $body;
	$this->status = $status;
	$this->headers = new HTTPHeader($headers);
    }
    public function __invoke(): mixed
    {
	return [
	    "body" => $this->body,
	    "status" => $this->status,
	    "headers" => $this->headers
	];
    }
    public function header() : HTTPHeader{
	return $this->headers;
    }

}
