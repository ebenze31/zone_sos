<?php

use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// ROLE
// - admin_zone

Auth::routes();

Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index')->name('home');

Route::middleware(['auth', 'role:admin_zone'])->group(function () {
    Route::get('/admin_home', 'AdminController@admin_home');

});

Route::get('before_video_call/', 'VideoCallController@before_video_call'); // index
Route::get('video_call_pc/', 'VideoCallController@video_call_pc'); // index
Route::get('video_call_mobile/', 'VideoCallController@video_call_mobile'); // index


Route::resource('zone_agora_chats', 'Zone_agora_chatsController');
Route::resource('zone_data_officer_commands', 'Zone_data_officer_commandsController');
Route::resource('zone_data_operating_officers', 'Zone_data_operating_officersController');
Route::resource('zone_data_operating_units', 'Zone_data_operating_unitsController');
Route::resource('zone_partners', 'Zone_partnersController');
Route::resource('zone_area', 'Zone_areaController');


// FOR DEV //
Route::middleware(['auth', 'role:admin_zone'])->group(function () {
    Route::get('/create_zone_partner', 'FordevController@create_zone_partner');
});
// END FOR DEV //
