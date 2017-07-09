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
Route::get('auth/logout','UserController@destroy')->middleware('auth:api');
Route::post('auth/changePassword','UserController@changePassword');



Route::post('addTask','TaskController@store')->middleware('auth:api');

Route::post('toggleComplete','TaskController@toggleComplete')->middleware('auth:api');
Route::post('togglePrivate','TaskController@togglePrivate')->middleware('auth:api');
Route::post('updateTask','TaskController@updateTask')->middleware('auth:api');
Route::post('deleteTask','TaskController@destroy')->middleware('auth:api');
Route::post('updateDeadline','TaskController@updateDeadline')->middleware('auth:api');
Route::post('uploadFile','FileController@upload')->middleware('auth:api');
Route::get('tasksUser','TaskController@showTasksUser')->middleware('auth:api');
Route::get('tasksGuest','TaskController@showTasksGuest');
Route::post('searchUser','UserController@searchUser');
Route::get('getCompleted','TaskController@getCompleted')->middleware('auth:api');
Route::get('getNotCompleted','TaskController@getNotCompleted')->middleware('auth:api');
//Route::post('getTasksByOwner','TaskController@getTasksByOwner')->middleware('auth:api');
Route::post('followTask','UserFollowingTasksController@followTask');

Route::post('invite','UserController@invite')->middleware('auth:api');

Route::get('auth/{provider}', 'UserController@redirectToProvider')->middleware('web');
Route::get('auth/{provider}/callback', 'USerController@handleProviderCallback')->middleware('web');

