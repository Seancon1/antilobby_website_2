<?php

use Illuminate\Support\Facades\Route;

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

Route::get('antilobby/user/register', function () {
    return view('user.register');
});

Route::get('antilobby', 'sessionController@index');
Route::get('antilobby/sessions', 'sessionController@index');
Route::get('antilobby/sessions/all', 'sessionController@index');
Route::get('antilobby/sessions/stats', 'sessionController@stats');
Route::get('antilobby/sessions/stats/all', 'sessionController@statsall');
Route::get('antilobby/sessions/{sessionID}', 'sessionController@get');

Route::get('antilobby/chart', 'sessionController@chart');
Route::get('antilobby/chart/{val}', 'sessionController@chart');
Route::get('antilobby/chart/json/{id}', 'sessionController@chartJSON');

//Below dynamically gets the request so that it can be adapted to 
//a chartJSON request
Route::get('antilobby/charts/{desc}', 'ChartController@index'); //
Route::get('antilobby/charts/json/{desc}', ['as'=> 'chart.grab', 'uses' => 'ChartController@index']); //<- defined route
Route::get('antilobby/charts/json/{desc}', 'ChartController@JSONHandler');
 


/* Save for later: this is how you call in a blade
Route::get('antilobby/charts/{desc}', ['as'=> 'chart.grab', 'uses' => 'ChartController@index']); //<- defined route
route('chart.grab', ['desc' => 'test'] // call this in blade
**/

//not yet implemented
/*
Route::get('antilobby/program/total', 'programController@total');

Route::get('antilobby/program/create/{pname}/{time}', 'programController@create');
Route::get('antilobby/program/create/{pname}/{time}', 'programController@update');

Route::get('antilobby/session/create/{sessionid}', 'sessionController@create');
Route::get('antilobby/session/update/{sessionid}/{newtime}', 'sessionController@update');
*/