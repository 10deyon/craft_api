<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return "craft-logo";
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get("/orders", 'AdminController@index');
    $router->get("/order/toggle/{id}", 'AdminController@toggleOrderStatus');
    $router->get("order/{order}", 'OrderController@show');
    $router->patch("order/{order}", 'OrderController@update');
    $router->post("/order", 'OrderController@store');
});
