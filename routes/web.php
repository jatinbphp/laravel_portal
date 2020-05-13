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

Auth::routes();

Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

Route::get('/', array(
    'as'   => 'dashboard',
    'uses' => 'DashboardController@index',
))->middleware(['guest']);

Route::get('verify/{token}', array(
    'as'   => 'verify',
    'uses' => 'DashboardController@verifyUser',
));

Route::group(['prefix' => 'check'], function () {
    Route::post('user', array(
        'as'   => 'check.user',
        'uses' => 'Auth\RegisterController@checkUserName',
    ));

    Route::post('email', array(
        'as'   => 'check.email',
        'uses' => 'Auth\RegisterController@checkEmail',
    ));
});

Route::group(['middleware' => ['auth']], function () {

    Route::get('home', array(
        'as'   => 'home',
        'uses' => 'HomeController@index',
    ));

    Route::get('password', array(
        'as'   => 'changepassword.get',
        'uses' => 'HomeController@getChangePassword',
    ));

    Route::post('password', array(
        'as'   => 'changepassword.post',
        'uses' => 'HomeController@postChangePassword',
    ));

    Route::post('profile', array(
        'as'   => 'profile',
        'uses' => 'HomeController@profile',
    ));

});
