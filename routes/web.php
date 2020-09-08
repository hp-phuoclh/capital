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
$lang = isset($_GET['lang']) ? $_GET['lang'] : (isset($_COOKIE['lang']) ? $_COOKIE['lang'] : 'vi' );
setcookie('lang', $lang, time() + (86400 * 30), "/");
App::setLocale($lang);

Auth::routes(['register' => false]);
Route::middleware(['auth'])->group(function () {
    Route::get('/', 'HomeController@index')->name('__');
    Route::get('/notifications', 'HomeController@notifications')->name('notifications');
    Route::post('/read_all_notification', 'HomeController@readAllNotification')->name('readAllNotification');
    Route::get('/notify/{id}', 'HomeController@notify')->name('notify');
    Route::resources([
        'users' => 'UserController',
        'categories' => 'CategoryController',
        'products' => 'ProductController',
        'orders' => 'OrderController',
        'sliders' => 'SliderController',
        'stores' => 'StoreController',
        'admins' => 'AdminController',
        'roles' => 'RoleController',
    ]);

    // products more Route
    Route::group(['prefix' => 'products'], function () {
        Route::post('{id}/images', 'ProductController@loadImages')->name('product.load_image');
        Route::get('/templates/all', 'ProductController@allProducts')->name('product.all');
    });
    // order more Route
    Route::group(['prefix' => 'orders'], function () {
        Route::get('/search/get_user', 'OrderController@get_user')->name('order.get_user');
        Route::get('/search/get_store', 'OrderController@get_store')->name('order.get_store');
        Route::get('/search/get_product', 'OrderController@get_product')->name('order.get_product');
        Route::get('/search/get_product_by_id', 'OrderController@get_product_by_id')->name('order.get_product_by_id');
        Route::get('/refund/{id}', 'OrderController@refund')->name('order.refund');
        Route::post('/refund_update/{id}', 'OrderController@refund_update')->name('order.refund_update');
    });
    //slider
    Route::post('sliders/{id}/images', 'SliderController@loadImages')->name('slider.load_image');
});
// remove image ajax 
Route::post('file/delete', function () {
    return response()->json(['message' => 'delete image successfully']);
});
