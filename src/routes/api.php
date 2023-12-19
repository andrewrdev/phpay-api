<?php

use src\app\router\Router;

$router = new Router(); 

Router::get('/api/v1', 'HomeController@index');

Router::get('     /api/v1/users',       'UserController@selectAll');
Router::get('     /api/v1/users/{id}',  'UserController@selectOne');
Router::post('    /api/v1/users',       'UserController@insert');
Router::put('     /api/v1/users/{id}',  'UserController@update');
Router::delete('  /api/v1/users/{id}',  'UserController@delete');
