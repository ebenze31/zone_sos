<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::get('/check_user_in_room', 'VideoCallController@check_user_in_room');
Route::get('/video_call_token', 'VideoCallController@token');
Route::get('/get_local_data', 'VideoCallController@get_local_data');
Route::get('/get_remote_data', 'VideoCallController@get_remote_data');
Route::get('/join_room', 'VideoCallController@join_room');
Route::get('/left_room', 'VideoCallController@left_room');
Route::get('/check_status_room', 'VideoCallController@check_status_room');
