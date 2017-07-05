<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('auth/register', 'UserController@register');
Route::post('auth/login', 'UserController@login');
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('user', 'UserController@getAuthUser');
});
Route::get('auth/logout', 'UserController@logout');



Route::post('addTask', 'TaskController@store')-> middleware('auth:api');

Route::get('searchUser', 'UserController@searchUser');

Route::get('tasksUser', 'TaskController@tasksUser');

Route::get('tasksGuest', 'TaskController@tasksGuest');

Route::post('toggleCompleted', 'TaskController@toggleCompleted');

Route::post('togglePrivate', 'TaskController@togglePrivate');

Route::post('updateBody', 'TaskController@updateBody');

Route::post('updateDeadline', 'TaskController@updateDeadline');

Route::post('deleteTask', 'TaskController@deleteTask');

Route::post('updatePassword', 'UserController@updatePassword');

Route::post('uploadFile', 'FileController@uploadFile');

Route::get('getCompleted', 'TaskController@getCompleted')->middleware('auth:api');

Route::get('getNotCompleted', 'TaskController@getNotCompleted')->middleware('auth:api');

Route::post('followTask', 'UserFollowingTasksController@followTask');

Route::post('invite', 'UserController@invite')->middleware('auth:api');

