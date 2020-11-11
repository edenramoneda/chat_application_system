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
// Route::post('/login', 'api\AuthController@login');
Route::post('/uploadimage','PostingsController@UploadImage');
Route::post('/reset-password', 'api\ForgotPasswordController@resetPassword');

Route::prefix('/user')->group(function(){
    Route::post('/login', 'api\AuthController@login');
    Route::middleware('auth:api')->get('/user', 'api\AuthController@UserData');

});

Route::group(['middleware' => 'auth:api'], function () { //prevents "redirected too many times"
    Route::post('/send/{user_id}', 'MessagesController@store');
    Route::get('/messages/{user_id}', 'MessagesController@index');
    Route::get('/user_id/{username}', 'MessagesController@getUserId');
    Route::post('/logout', 'MessagesController@logout');
    Route::get('/all-users', 'MessagesController@getAllUsers');
    Route::put('/user/{user}/online', 'UserOnlineController');
    Route::put('/user/{user}/offline', 'UserOfflineController');
});


