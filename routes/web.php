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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/ret_search', array('as' => 'ret-search', 'uses' => 'SearchController@index'));
Route::post('/do_search/{offset}', array('as' => 'do-search', 'uses' => 'SearchController@do_search'));

Route::get('/test', array('as' => 'test', 'uses' => 'SearchController@test'));
Route::get('sample-restful-apis', function () {
    return array(
        1 => "expertphp",
        2 => "demo"
    );
});

