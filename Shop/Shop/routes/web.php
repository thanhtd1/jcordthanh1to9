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

// return home page
Route::view('/', 'master')->name('home');

// return home page and open login dilog
Route::get('/require_login', function () {
    return redirect('/')->with('require_login',true);
})->name('require_login');

// login controller
Route::post('/login', 'LoginController@login');
Route::post('/register', 'LoginController@register');
Route::get('/logout', 'LoginController@logout');

// get element for js cript
Route::group(['prefix' => 'element'], function () {
    Route::group(['prefix' => 'form'], function () {
        Route::view('login', 'element.form.login');
        Route::view('register', 'element.form.register');
        Route::get('add_product', 'productController@getType');
    });
});

// route for admin
Route::group(['middleware' => ['auth:admins']], function () {
    Route::group(['prefix' => 'admin'], function () {
        Route::group(['prefix' => 'view'], function () {
            Route::view('header', 'admin.element.header');
            Route::view('product-list', 'admin.product-list');
            Route::view('left-sidebar', 'admin.element.left-sidebar');
            Route::get('product/{id}', 'productController@getDetail');
            Route::get('list_product', 'productController@getList');
            Route::post('add_product', 'productController@add');
        });
        Route::view('/', 'admin.index');
    });
});
