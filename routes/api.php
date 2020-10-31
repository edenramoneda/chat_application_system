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
    Route::post('/login', 'api\AuthController@login')->name('login');
    Route::middleware('auth:api')->get('/user', 'api\AuthController@UserData');
});

Route::group(['middleware' => 'auth:api'], function () { //prevents "redirected too many times"
    //Route::get('logout', 'HomeController@logout');

    Route::group(['prefix' => '/categories'],function(){
        Route::get('/','CategoriesController@index');
        Route::post('/add-category','CategoriesController@store');
        Route::get('/edit-category/{id}','CategoriesController@edit');
        Route::post('/update-category/{id}','CategoriesController@update');
        Route::post('/delete-category/{id}','CategoriesController@destroy');
    });

    Route::group(['prefix' => '/my-account'],function(){
        Route::get('/{id}','MyAccountController@index');
        Route::post('/update-user/{id}','MyAccountController@update');
     
    });

    Route::group(['prefix' => '/tags'],function(){
        Route::get('/','TagsController@index');
        Route::post('/add-tag','TagsController@store');
        Route::get('/edit-tag/{id}','TagsController@edit');
        Route::post('/update-tag/{id}','TagsController@update');
        Route::post('/delete-tag/{id}','TagsController@destroy');
    });

    Route::group(['prefix' => '/postings'],function(){
        Route::get('/','PostingsController@index');
        Route::post('/add-post/{user_id}','PostingsController@store');
        Route::get('/edit-post/{id}','PostingsController@edit');
        Route::post('/update-post/{id}/{user_id}','PostingsController@update');
        Route::post('/delete-post/{id}','PostingsController@destroy');
        Route::get('/status','PostingsController@Status');
        
        Route::get('/getimage/{id}','PostingsController@viewImg');
    });

    Route::group(['prefix' => '/users'],function(){
        Route::get('/','UsersController@index');
        Route::post('/add-user','UsersController@store');
        Route::get('/edit-user/{id}','UsersController@edit');
        Route::post('/update-user/{id}','UsersController@update');
        Route::post('/delete-user/{id}','UsersController@destroy');
        Route::get('/roles','UsersController@roles');
    });

        
    //     Route::group(['prefix' => '/roles'],function(){
    //         Route::get('/edit-user-permission/{id}','UsersController@EditUserPermission');
    //         Route::post('/delete-user/{id}','UsersController@DeleteUser');
    //         Route::get('/role-list/{id}','UsersController@RoleList');
    //         Route::post('/update-user-permission/{id}','UsersController@UpdateUserPermission');
    //     });
    // });
});


