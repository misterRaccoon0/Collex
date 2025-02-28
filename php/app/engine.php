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
app_include("src/routes/$route");
