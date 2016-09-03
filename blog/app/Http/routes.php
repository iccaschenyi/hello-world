<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => ['web']], function () {

    Route::get('/', 'Home\IndexController@index');
    Route::get('/cate/{cate_id}', 'Home\IndexController@cate');
    Route::get('/a/{art_id}', 'Home\IndexController@article');

    Route::get('admin/login', 'Admin\LoginController@login');
    Route::get('admin/code', 'Admin\LoginController@code');
    Route::get('admin/getcode', 'Admin\LoginController@getcode');
    Route::any('admin/login', 'Admin\loginController@login');
    Route::any('admin/crypt', 'Admin\loginController@crypt');
    Route::any('admin', 'Admin\IndexController@index');
    Route::any('admin/info', 'Admin\IndexController@info');

});


Route::group(['middleware' => ['web','admin.login'],'prefix'=>'admin','namespace'=>'Admin',],function () {
    Route::get('', 'IndexController@index');
    Route::get('info', 'IndexController@info');
    Route::get('quit', 'LoginController@quit');
    Route::any('pass', 'IndexController@pass');

    Route::post('/cate/changeorder', 'CategoryController@changeOrder');
    Route::post('/links/changeorder', 'LinksController@changeOrder');
    Route::post('/navs/changeorder', 'NavsController@changeOrder');
    Route::post('/config/changeorder', 'ConfigController@changeOrder');
    Route::post('/config/changecontent', 'ConfigController@changeContent');
    Route::get('/config/putfile', 'ConfigController@putFile');
    Route::resource('category', 'CategoryController');
    Route::resource('navs', 'NavsController');
    Route::resource('config', 'ConfigController');

    Route::resource('article', 'ArticleController');
    Route::resource('links', 'LinksController');
    Route::any('upload', 'CommonController@upload');
});
