<?php

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


$router->post('/login', 'LoginController@index');
$router->post('/register', 'UserController@register');


$router->get('/checklists/templates', 'TemplatesController@index');
$router->get('/checklists/templates/{id}', 'TemplatesController@show');
$router->post('/checklists/templates', 'TemplatesController@store');

$router->post('/checklists/complete', 'ItemsController@complete');
$router->post('/checklists/incomplete', 'ItemsController@incomplete');
$router->delete('/checklists/{checklistId}/items/{itemId}', 'ItemsController@delete');
$router->get('/checklists/{checklistId}/items/{itemId}', 'ItemsController@show');
$router->post('/checklists/{checklistId}/items', 'ItemsController@store');
$router->get('/checklists/{checklistId}/items', 'ItemsController@index');
$router->patch('/checklists/{checklistId}/items/{itemId}', 'ItemsController@update');
$router->post('/checklists/{checklistId}/items/_bulk', 'ItemsController@bulk_update');


$router->get('/checklists', ['middleware' => 'auth', 'uses' =>  'ChecklistsController@index']);
$router->delete('/checklists/{checklistId}', 'ChecklistsController@delete');
$router->get('/checklists/{id}', 'ChecklistsController@show');
$router->post('/checklists', 'ChecklistsController@store');
$router->patch('/checklists/{checklistId}', 'ChecklistsController@update');




