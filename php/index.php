<?php
    define("BASEDIR", __DIR__);
    define("APPDIR", realpath(BASEDIR . "/app"));
    define("URL", $_SERVER["REDIRECT_URL"]);

    function app_require($module){
	return require_once(join("/",[APPDIR,$module . (str_ends_with($module , ".php")?"":".php")]));
    }
    function app_include($module){
	return include_once(join("/",[APPDIR,$module . (str_ends_with($module , ".php")?"":".php")]));
    }
    function lazy_require(string $module){
	return function () use (&$module){
	    return app_require($module);
	};
    }
    function lazy_include(string $module){
	return function () use (&$module){
	    return app_include($module);
	};
    }
    app_require("engine");
?>
