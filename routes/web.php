<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('register',function(){
      return view('registration.create');
});

Route::get('login',function(){
      return view('session.login');
});

Route::get('/tasks/create','TaskController@create');

Route::get('/logout','UserController@destroy');


Route::post('addTask','TaskController@store');

Route::post('deleteTask','TaskController@destroy');

Route::get('tasks','TaskController@index');

Route::post('updateTask','TaskController@update');

Route::post('uploadFile','FileController@uploadFile');

Route::post('updatePassword','UserController@updatePassword');

Route::post('search','UserController@search');
