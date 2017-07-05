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
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('auth/register', 'UserController@register');

Route::post('auth/login', 'UserController@login');

Route::group(['middleware' => 'jwt.auth'], function () {

    Route::get('user', 'UserController@getAuthUser');
});

Route::get('/tasks/create','TaskController@create')->middleware('auth:api');

Route::get('/logout','UserController@destroy')->middleware('auth:api');;


Route::post('addTask','TaskController@store')->middleware('auth:api');;

Route::post('deleteTask','TaskController@destroy')->middleware('auth:api');;

Route::get('tasks','TaskController@index');

Route::post('updateTask','TaskController@update')->middleware('auth:api');;

Route::post('uploadFile','FileController@uploadFile')->middleware('auth:api');;

Route::post('updatePassword','UserController@updatePassword')->middleware('auth:api');;

Route::post('search', 'UserController@search');

Route::get('getCompleted', 'TaskController@getCompleted')->middleware('auth:api');

Route::get('getNotCompleted', 'TaskController@getNotCompleted')->middleware('auth:api');

Route::get('getUserTasks', 'TaskController@taskUser')->middleware('auth:api');
//Route::post('getTasksByOwner','TaskController@getTasksByOwner')->middleware('auth:api');
Route::post('followTasks', 'UserFollowingTasksController@followTasks');

Route::post('invite', 'UserController@invite')->middleware('auth:api');
