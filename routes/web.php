<?php

Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
Route::post('/login', 'Auth\LoginController@login');
Route::get('/', 'ApplicationController')->name('home');

Route::prefix('api')->group(function () {

    /*
     * Admin
     * Add/edit user (register)
     * Add/edit transaction
     * Add/edit account
     * */
    Route::get('/admin/users', 'Admin\UserController@index');
    Route::get('/admin/user/{id}', 'Admin\UserController@show');
    Route::post('/admin/users', 'Admin\UserController@store');
    Route::patch('/admin/users/{id}', 'Admin\UserController@update');
    Route::delete('/admin/users/{id}', 'Admin\UserController@destroy');

    Route::get('/admin/user/{id}/accounts', 'Admin\UserController@accounts');

    Route::get('/admin/transactions', 'Admin\TransactionController@index');
    Route::get('/admin/transaction/{id}', 'Admin\TransactionController@show');
    Route::post('/admin/transactions', 'Admin\TransactionController@store');
    Route::patch('/admin/transactions/bulk-accept', 'Admin\TransactionController@bulkAccept');
    Route::patch('/admin/transactions/{id}', 'Admin\TransactionController@update');
    Route::delete('/admin/transactions', 'Admin\TransactionController@destroy');

    Route::get('/admin/accounts', 'Admin\AccountController@index');
    Route::get('/admin/account/{id}', 'Admin\AccountController@show');
    Route::post('/admin/accounts', 'Admin\AccountController@store');
    Route::patch('/admin/accounts', 'Admin\AccountController@update');
    Route::delete('/admin/accounts/{id}', 'Admin\AccountController@destroy');

    Route::get('/admin/commissions', 'Admin\CommissionController@index');
    Route::patch('/admin/commissions', 'Admin\CommissionController@update');

    /*
     * Public
     * */
    Route::get('/user', 'User\UserController@show');
    Route::post('/user', 'User\UserController@update');
    Route::get('/user/transactions', 'User\TransactionController@index');
    Route::get('/user/{id}/transactions', 'User\TransactionController@userTransaction');
    Route::get('/user/accounts', 'User\AccountController@index');
    Route::post('/transaction', 'User\TransactionController@store');
    Route::patch('/user/password', 'User\UserController@updatePassword');

});

Route::get('/admin', 'ApplicationController@admin')->name('admin');
Route::get('/admin/{any}', 'ApplicationController@admin')->where('any', '.*');

Route::get('/{any}', 'ApplicationController')->where('any', '.*');
