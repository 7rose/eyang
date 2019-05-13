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

// 注册和登录
Route::get('/register', 'UserController@register');
Route::post('/register/store', 'UserController@store');
Route::get('/login', 'UserController@login');
Route::post('/login/check', 'UserController@check');
Route::get('/logout', 'UserController@logout');

Route::group(['middleware' => ['login', 'state']], function () {
    
    // 供应商
    Route::get('/orgs', 'OrgController@index');
    Route::get('/orgs/create', 'OrgController@create');
    Route::post('/orgs/store', 'OrgController@store');
    Route::get('/orgs/edit/{id}', 'OrgController@edit');
    Route::post('/orgs/update/{id}', 'OrgController@update');

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
    Route::get('/bb/success/{id}', 'OrderController@bbSuccess'); # 报备成功
    Route::get('/bb/success/back_form/{id}', 'OrderController@bbBack'); # 备用表单
    Route::post('/bb/success/store/{id}', 'OrderController@bbSuccessStore'); 
    Route::get('/bb/fail/{id}', 'OrderController@bbFail'); # 报备失败
    Route::post('/bb/fail/store/{id}', 'OrderController@bbFailStore'); 
    Route::get('/bb/show/{id}', 'OrderController@bbShow'); # 下载
    Route::get('/bb/check_fail/{id}', 'OrderController@checkFail'); 
    Route::get('/bb/check_ok/{id}', 'OrderController@checkOk'); 
    Route::get('/download/video/{id}', 'OrderController@videoDownload'); # 视频下载

    // 用户
    Route::get('/users/lock/{id}', 'UserController@lock');
    Route::get('/users/unlock/{id}', 'UserController@unlock');
    Route::get('/password_reset', 'UserController@passwordReset');
    Route::post('/password_reset/do', 'UserController@passwordResetDo');
    Route::get('/users/remove_boss/{id}', 'UserController@removeBoss');
    Route::get('/users/set_boss/{id}', 'UserController@setBoss');
    Route::get('/users/limit_reset/{id}', 'UserController@limitReset');
    Route::get('/users/limit_add/{id}', 'UserController@limitAdd');
    Route::get('/users/limit_cut/{id}', 'UserController@limitCut');

    // 产品
    Route::get('/products/show/{id}', 'ProductController@show')->middleware('limit');
    Route::get('/products/create', 'ProductController@create');
    Route::post('/products/store', 'ProductController@store');
    Route::post('/products/img/store', 'ProductController@imgStore');
    Route::get('/products/edit/{id}', 'ProductController@edit');
    Route::post('/products/update/{id}', 'ProductController@update');
    Route::get('/products/fs/{id}', 'ProductController@fs'); # 放水
    Route::get('/products/unfs/{id}', 'ProductController@unfs'); # 放水
    Route::get('/products/on/{id}', 'ProductController@on'); 
    Route::get('/products/off/{id}', 'ProductController@off'); 
    Route::get('/products/delete/{id}', 'ProductController@delete'); 
    Route::get('/products/slide/{id}', 'ProductController@slide'); 
    Route::get('/products/remove_slide', 'ProductController@removeSlide'); 

});


Route::get('/test', function() {
 
});
















