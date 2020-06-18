<?php

require __DIR__ . "/vendor/autoload.php";

use CoffeeCode\Router\Router;

$router = new Router(BASE_URL);

$router->namespace("Source\Controllers");

$router->group(null);

$router->post("/users/", "User:create");
$router->get("/users/", "User:getAll");
$router->get("/users/{id}", "User:getById");
$router->put("/users/{id}", "User:update");
$router->delete("/users/{id}", "User:delete");

$router->get("/ranking", "DrinkCounter:ranking");
$router->post("/users/{id}/drink", "DrinkCounter:add");

$router->post("/login", "Login:execute");


$router->dispatch();

if ($router->error()) {
    http_response_code($router->error());
}