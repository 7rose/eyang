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
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

Route::get('/', 'ProductController@index');
Route::get('/products/show/{id}', 'ProductController@show');

// 注册和登录
Route::get('/register', 'UserController@register');
Route::post('/register/store', 'UserController@store');
Route::get('/login', 'UserController@login');
Route::post('/login/check', 'UserController@check');
Route::get('/logout', 'UserController@logout');

Route::group(['middleware' => ['login', 'state']], function () {

    // 店
    Route::get('/shops/active', 'ShopController@active');
    Route::post('/shops/active/do', 'ShopController@doActive');

    // 用户
    Route::get('/users/lock/{id}', 'UserController@lock');
    Route::get('/users/unlock/{id}', 'UserController@unlock');
    Route::get('/password_reset', 'UserController@passwordReset');
    Route::post('/password_reset/do', 'UserController@passwordResetDo');

    // 产品
    Route::get('/products/create', 'ProductController@create');
    Route::post('/products/store', 'ProductController@store');
    Route::post('/products/img/store', 'ProductController@imgStore');
    Route::get('/products/edit/{id}', 'ProductController@edit');
    Route::post('/products/update/{id}', 'ProductController@update');

});


Route::get('/test', function() {
    // abort('403');
});
















