<?php

use App\Core\Routing\Router;

// Auth Routes
Router::post('/api/login', 'AuthController@login');
Router::post('/api/register', 'AuthController@register');
Router::post('/api/logout', 'AuthController@logout');

// User Routes
Router::get('/api/users', 'UserController@index');
Router::get('/api/users/{id}', 'UserController@show');
Router::post('/api/users', 'UserController@store');
Router::put('/api/users/{id}', 'UserController@update');
Router::delete('/api/users/{id}', 'UserController@delete');

// Role Routes
Router::get('/api/roles', 'RoleController@index');
Router::get('/api/roles/{id}', 'RoleController@show');
Router::post('/api/roles', 'RoleController@store');
Router::put('/api/roles/{id}', 'RoleController@update');
Router::delete('/api/roles/{id}', 'RoleController@delete');

// Permission Routes
Router::get('/api/permissions', 'PermissionController@index');
Router::get('/api/permissions/{id}', 'PermissionController@show');
Router::post('/api/permissions', 'PermissionController@store');
Router::put('/api/permissions/{id}', 'PermissionController@update');
Router::delete('/api/permissions/{id}', 'PermissionController@delete');

// Profile Routes
Router::get('/api/profile', 'ProfileController@show');
Router::put('/api/profile', 'ProfileController@update');
Router::post('/api/profile/change-password', 'ProfileController@changePassword');