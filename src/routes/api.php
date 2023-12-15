<?php

use src\app\router\Router;

$router = new Router(); 

Router::get('/api/v1', 'IndexController@index');
Router::get('/api/v1/users', 'UserController@index');