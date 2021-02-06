<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => 'api'], function ($router) {
    Route::get('menu', 'MenuController@index');

    Route::post('login', 'AuthController@login');
    Route::post('admin/login', 'AuthController@adminLogin');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');

    Route::resource('resource/{table}/resource', 'ResourceController');

    Route::group(['middleware' => 'admin', 'prefix' => 'admin'], function ($router) {
        Route::resource('users', 'Admin\UserController')->except(['create', 'store']);

//        Route::prefix('menu/menu')->group(function () {
//            Route::get('/', 'MenuEditController@index')->name('menu.menu.index');
//            Route::get('/create', 'MenuEditController@create')->name('menu.menu.create');
//            Route::post('/store', 'MenuEditController@store')->name('menu.menu.store');
//            Route::get('/edit', 'MenuEditController@edit')->name('menu.menu.edit');
//            Route::post('/update', 'MenuEditController@update')->name('menu.menu.update');
//            Route::get('/delete', 'MenuEditController@delete')->name('menu.menu.delete');
//        });
//        Route::prefix('menu/element')->group(function () {
//            Route::get('/', 'MenuElementController@index')->name('menu.index');
//            Route::get('/move-up', 'MenuElementController@moveUp')->name('menu.up');
//            Route::get('/move-down', 'MenuElementController@moveDown')->name('menu.down');
//            Route::get('/create', 'MenuElementController@create')->name('menu.create');
//            Route::post('/store', 'MenuElementController@store')->name('menu.store');
//            Route::get('/get-parents', 'MenuElementController@getParents');
//            Route::get('/edit', 'MenuElementController@edit')->name('menu.edit');
//            Route::post('/update', 'MenuElementController@update')->name('menu.update');
//            Route::get('/show', 'MenuElementController@show')->name('menu.show');
//            Route::get('/delete', 'MenuElementController@delete')->name('menu.delete');
//        });

//        Route::resource('roles', 'RoleController');
//        Route::get('/roles/move/move-up', 'RoleController@moveUp')->name('roles.up');
//        Route::get('/roles/move/move-down', 'RoleController@moveDown')->name('roles.down');

        Route::prefix('tests')->group(function () {
            Route::get('/', 'Admin\TestController@index');
            Route::get('/search', 'Admin\TestController@search');
            Route::post('/', 'Admin\TestController@create');
            Route::post('import', 'Admin\TestController@import');
            Route::get('{test}', 'Admin\TestController@show');
            Route::post('{test}', 'Admin\TestController@update');
            Route::delete('{test}', 'Admin\TestController@delete');
        });

        Route::prefix('test-subjects')->group(function () {
            Route::get('/', 'Admin\TestSubjectController@index');
            Route::post('/', 'Admin\TestSubjectController@create');
            Route::get('{testSubject}', 'Admin\TestSubjectController@show');
            Route::post('{testSubject}', 'Admin\TestSubjectController@update');
            Route::delete('{testSubject}', 'Admin\TestSubjectController@delete');
            Route::post('{testSubject}/generate-code', 'Admin\TestSubjectController@generateCode');
            Route::post('{testSubject}/add-tests', 'Admin\TestSubjectController@addTests');
            Route::delete('{testSubject}/delete-test/{userTest}', 'Admin\TestSubjectController@deleteTest');
            Route::get('{testSubject}/results/{userTest}', 'Admin\TestSubjectController@showResult');
            Route::post('{testSubject}/send-invite', 'Admin\TestSubjectController@sendInvite');
        });
    });

    Route::group(['middleware' => ['test-subject']], function () {
        Route::prefix('tests')->group(function () {
            Route::get('/', 'TestSubject\TestController@index');
            Route::group([
                'middleware' => 'belongs-to-user-test',
                'prefix' => '{userTest}',
            ], function () {
                Route::get('', 'TestSubject\TestController@show');
                Route::put('{question}', 'TestSubject\TestController@answer');
            });
        });
    });
});

