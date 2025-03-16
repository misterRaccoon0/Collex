<?php

global $route;
switch($_SERVER["SERVER_NAME"]){
    case "api.localhost":
	$route = "api";
	break;
    case "localhost":
    default:
	$route = "web";
	break;
}
$router = app_include("src/routes/$route");

