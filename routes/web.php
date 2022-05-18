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

$router->get('/', function () {
    return response()->json("craft-logo");
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get("/all-orders", 'AdminController@index');
    $router->get("/orders/{id}", 'AdminController@showWithStatus');
    $router->post("/order_payment", 'OrderController@store');
    $router->post("/verify_passcode", 'AdminController@verifyPasscode');
    $router->get("/complete_order/{id}", 'AdminController@completeOrder');
});
