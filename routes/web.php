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
    return view('sessions.create');
});

Route::get('register', function () {
    return view('registration.create');
});

Route::post('addTask', 'TaskController@store');

//Route::get('/tasks/{task}', 'TaskController@show');

Route::get('tasksUser', 'TaskController@tasksUser');

Route::get('tasksGuest', 'TaskController@tasksGuest');

Route::post('toggleCompleted', 'TaskController@toggleCompleted');

Route::post('togglePrivate', 'TaskController@togglePrivate');

Route::post('updateBody', 'TaskController@updateBody');

Route::post('updateDeadline', 'TaskController@updateDeadline');

Route::post('deleteTask', 'TaskController@deleteTask');

Route::post('updatePassword', 'UserController@updatePassword');

Route::post('uploadFile', 'FileController@uploadFile');



