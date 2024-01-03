<?php

use src\app\router\Router;

$router = new Router();

Router::get('/api/v1', 'HomeController@index');

// ********************************************************************************************
// ********************************************************************************************

Router::get('   /api/v1/users',         'UserController@selectAll');
Router::get('   /api/v1/users/{id}',    'UserController@selectOne');
Router::post('  /api/v1/users',         'UserController@insert');
Router::put('   /api/v1/users/{id}',    'UserController@update');
Router::delete('/api/v1/users/{id}',    'UserController@delete');
Router::post('  /api/v1/users/deposit', 'UserController@deposit');

// ********************************************************************************************
// ********************************************************************************************

Router::get('   /api/v1/transactions',       'TransactionController@selectAll');
Router::get('   /api/v1/transactions/{id}',  'TransactionController@selectOne');
Router::post('  /api/v1/transactions',       'TransactionController@insert');
Router::delete('/api/v1/transactions/{id}',  'TransactionController@delete');

// ********************************************************************************************
// ********************************************************************************************

Router::get('   /api/v1/notifications',                'NotificationController@selectAll');
Router::get('   /api/v1/notifications/{id}',           'NotificationController@selectOne');
Router::delete('/api/v1/notifications/{id}',           'NotificationController@delete');
