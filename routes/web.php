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
    return $router->app->version();
});

$router->get('/stuff', 'StuffController@index');
$router->post('/create-stuff', 'StuffController@store');
$router->get('/stuff/trash', 'StuffController@deleted');
$router->delete('/stuff/permanent', 'StuffController@permanentDeleteAll');
$router->put('/stuff/restore', 'StuffController@restoreAll');
$router->delete('/stuff/permanent/{id}', 'StuffController@permanentDelete');
$router->put('/stuff/restore/{id}', 'StuffController@restore');

$router->get('/stuff/{id}', 'StuffController@show');
$router->get('/stuff/{id}', 'StuffController@update');
$router->get('/stuff/{id}', 'StuffController@destroy');




$router->get('/stuff-stock', 'StuffStockController@index');
$router->post('/stuff-stock', 'StuffStockController@store');
$router->get('/stuff-stock/trash', 'StuffStockController@deleted');
$router->delete('/stuff-stock', 'StuffStockController@permanenDeleteAll');
$router->delete('/stuff-stock/permanent/{id}', 'StuffStockController@permanentDelete');
// $router->put('/stuff-stock/restore', 'StuffStockController@restoreAll');;
$router->put('/stuff-stock/restore/{id}', 'StuffStockController@restore');

$router->get('/stuff-stock/{id}', 'StuffStockController@show');
$router->put('/stuff-stock/{id}', 'StuffStockController@update');
$router->delete('/stuff-stock/{id}', 'StuffStockController@destroy');


$router->get('/User', 'UserController@index');
$router->post('/User', 'UserController@store');
$router->get('/User/trash', 'UserController@deleted');
$router->delete('/User/permanent', 'UserController@permanentDeleteAll');
$router->put('/User/restore', 'UserController@restoreAll');
$router->delete('/User/permanent/{id}', 'UserController@permanentDelete');
$router->put('/User/restore/{id}', 'UserController@restore');

$router->get('/User/{id}', 'UserController@show');
$router->put('/User/{id}', 'UserController@update');
$router->delete('/User/{id}', 'UserController@destroy');

$router->get('/InboundStuff', 'InboundStuffController@index');
$router->post('/InboundStuff', 'InboundStuffController@store');
$router->get('/InboundStuff/trash', 'InboundStuffController@deleted');
$router->delete('/InboundStuff/permanent', 'InboundStuffController@permanentDeleteAll');
$router->put('/InboundStuff/restore', 'InboundStuffController@restoreAll');
$router->delete('/InboundStuff/permanent/{id}', 'InboundStuffController@permanentDelete');
$router->put('/InboundStuff/restore/{id}', 'InboundStuffController@restore');

$router->get('/InboundStuff/{id}', 'InboundStuffController@show');
$router->put('/InboundStuff/{id}', 'InboundStuffController@update');
$router->delete('/InboundStuff/{id}', 'InboundStuffController@destroy');








