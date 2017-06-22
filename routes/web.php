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
Route::get('register',function(){
    return view('registration.create');
});
Route::post('addTask','TaskController@store');
Route::post('toggleComplete','TaskController@toggleComplete');
Route::post('togglePrivate','TaskController@togglePrivate');
Route::post('updateTask','TaskController@updateTask');
Route::post('deleteTask','TaskController@destroy');
Route::post('updateDeadline','TaskController@updateDeadline');
Route::post('uploadFile','FileController@upload');
Route::get('tasksUser','TaskController@showTasksUser');
Route::get('tasksGuest','TaskController@showTasksGuest');
