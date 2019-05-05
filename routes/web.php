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
    Route::get('/shops', 'ShopController@index');
    Route::get('/shops/active', 'ShopController@active');
    Route::post('/shops/active/do', 'ShopController@doActive');
    Route::get('/shops/create/{parent_id}', 'ShopController@create');
    Route::post('/shops/store/{parent_id}', 'ShopController@store');

    // 订单
    Route::get('/orders', 'OrderController@index');
    Route::get('/orders/create', 'OrderController@create');
    Route::post('/orders/store', 'OrderController@store');
    Route::post('/orders/bb', 'OrderController@bb'); # 报备
    Route::post('/orders/bb/store/{order_id}', 'OrderController@bbStore'); # 报备
    Route::get('/orders/bb/fail/{id}', 'OrderController@bbFailStore'); # 报备失败
    Route::get('/orders/bb/forms/back/{order_id}', 'OrderController@bbBack'); # 备用表单
    Route::get('/orders/bb/show/{id}', 'OrderController@bbShow'); # 报备失败

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
    Route::get('/products/fs/{id}', 'ProductController@fs'); # 放水
    Route::get('/products/unfs/{id}', 'ProductController@unfs'); # 放水

});


Route::get('/test', function() {
    $a = new App\Helpers\Picker;
    print_r($a->bb(3));

});
















