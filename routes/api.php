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

$router->post('init', 'PaymentController@init');
$router->post('capture', 'PaymentController@capture');

$router->get('orders',  ['as' => 'orders.index', 'uses' => 'OrderController@index']);
$router->get('orders/{id}',  ['as' => 'orders.show', 'uses' => 'OrderController@show']);
