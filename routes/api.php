<?php

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
Route::group(['prefix' => 'v1'], function () {
    Route::post('/login', 'Api\v1\AuthController@login');
    Route::post('/check_phone', 'Api\v1\AuthController@checkPhone');
    Route::post('/register', 'Api\v1\AuthController@register');

    Route::group(['middleware' => ['auth:api']], function () {
        Route::get('/logout', 'Api\v1\AuthController@logout');
        Route::group(['prefix' => 'account'], function () {
            Route::get('/details', 'Api\v1\UserController@details');
            Route::put('/update', 'Api\v1\UserController@update');
        });

    });

    // categories
    Route::get('/categories', 'Api\v1\CategoryController@index');
    Route::get('/categories/{id}', 'Api\v1\CategoryController@detail');
    // product
    Route::get('/products', 'Api\v1\ProductController@index');
    Route::get('/products/{id}', 'Api\v1\ProductController@detail');
    // store
    Route::get('/stores', 'Api\v1\StoreController@index');
    Route::get('/stores/{id}', 'Api\v1\StoreController@detail');
    // slider
    Route::get('/sliders', 'Api\v1\SliderController@index');

    //admin
    Route::post('/admin/login', 'Api\v1\AuthController@loginAdmin');
    Route::group(['middleware' => ['auth:api_admin']], function () {
        Route::get('/logout', 'Api\v1\AuthController@logout');
        Route::group(['prefix' => 'admin'], function () {
            Route::get('/details', 'Api\v1\AdminController@details');
        });
    });
    Route::group(['middleware' => ['auth:api,api_admin']], function () {
        // order
        Route::group(['prefix' => 'orders'], function () {
            Route::post('/', 'Api\v1\OrderController@store');
            Route::get('/', 'Api\v1\OrderController@index');
            Route::get('/{id}', 'Api\v1\OrderController@detail');
        });

    });
});
