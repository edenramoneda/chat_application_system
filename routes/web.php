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
Route::get('/', 'WebController@index');
Route::post('/forgot-password', 'api\ForgotPasswordController@sendpasswordResetLink');

Route::view('/forgot_password', 'reset_password')->name('password.reset');

Route::get('{path}', 'WebController@index')->where('path', '^(?!api\/)[\/\w\.-]*');
// Route::group(['prefix' => '/postings'],function(){
//     Route::get('/','PostingsController@index');
//     Route::get('/add-post','PostingsController@AddPost');
//     Route::post('/submit-post','PostingsController@SubmitPost');
//     Route::get('/edit-post/{id}','PostingsController@EditPost');
//     Route::post('/update-post/{id}','PostingsController@UpdatePost');
//     Route::post('/delete-post/{id}','PostingsController@DeletePost');
// });

Route::group(['middleware' => 'auth'], function () { //prevents "redirected too many times"
    //Route::get('logout', 'HomeController@logout');

   // Route::group(['prefix' => '/categories'],function(){
      //  Route::get('/','HomeController@index');
        // Route::post('/add-category','HomeController@AddCategory');
        // Route::get('/edit-category/{id}','HomeController@EditCategory');
        // Route::post('/update-category/{id}','HomeController@UpdateCategory');
        // Route::post('/delete-category/{id}','HomeController@DeleteCategory');

    // Route::group(['prefix' => '/tags'],function(){
    //     Route::get('/','TagsController@index');
    //     Route::post('/add-tag','TagsController@AddTag');
    //     Route::get('/edit-tag/{id}','TagsController@EditTag');
    //     Route::post('/update-tag/{id}','TagsController@UpdateTag');
    //     Route::post('/delete-tag/{id}','TagsController@DeleteTag');
    // });

    // Route::group(['prefix' => '/users'],function(){
    //     Route::group(['prefix' => '/users'],function(){
    //         Route::get('/','UsersController@index');
    //         Route::post('/add-user','UsersController@AddUser');
    //         Route::get('/edit-user/{id}','UsersController@EditUser');
    //         Route::post('/update-user/{id}','UsersController@UpdateUserData');
    //         Route::post('/delete-user/{id}','UsersController@DeleteUser');
    //     });
        
    //     Route::group(['prefix' => '/roles'],function(){
    //         Route::get('/edit-user-permission/{id}','UsersController@EditUserPermission');
    //         Route::post('/delete-user/{id}','UsersController@DeleteUser');
    //         Route::get('/role-list/{id}','UsersController@RoleList');
    //         Route::post('/update-user-permission/{id}','UsersController@UpdateUserPermission');
    //     });
    // });
});

