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

Route::post('user/signin', 'UserController@signIn');
Route::post('user/signup', 'UserController@signUp');

Route::middleware(['auth.jwt'])->group(function () {
    Route::get('user/getuser', 'UserController@getuser');

    Route::post('blog/storecategory', 'blog\CategoriesController@store');
    Route::get('blog/indexcategories', 'blog\CategoriesController@index');
    Route::get('blog/indextags', 'blog\TagsController@index');
    Route::get('blog/indexposts', 'blog\PostsController@index');
    Route::post('blog/storepost', 'blog\PostsController@store');
    Route::post('blog/storeimage', 'blog\PostsController@storeImage');
    Route::post('blog/deleteimage', 'blog\PostsController@deleteImage');
    Route::get('blog/getpost/{id}', 'blog\PostsController@getPost');
    Route::post('blog/updatepost', 'blog\PostsController@updatePost');
});



