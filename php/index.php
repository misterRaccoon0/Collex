<?php
    define("BASEDIR", __DIR__);
    define("APPDIR", realpath(BASEDIR . "/app"));
    define("URI", $_SERVER["REQUEST_URI"]);

    function app_require($module){
	return require_once(join("/",[APPDIR,$module]));
    }

    phpinfo();

    //app_require("engine.php");
?>
