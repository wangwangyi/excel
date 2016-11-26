<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/



/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::get('/', function(){
    redirect('welcome');
});

Route::group(['middleware' => 'web'], function () {
    Route::auth();


    Route::group(['middleware' => ['auth']], function () {
        Route::get('/', 'IndexController@index');
       // Route::get('/plan', 'IndexController@plan');
        Route::get('/make', 'ExcelController@make');
        Route::get('excel/{num}/{time}','ExcelController@export');
      //  Route::get('/condition','ExcelController@condition');
        Route::post('excel/import','ExcelController@import');
    });
});
