<?php
use Core\Routing\Route;

$router = Route::create();
$admin = Route::create("/admin");

$admin->route("/user");

$router->middleware("")->use($admin);

return $router;
