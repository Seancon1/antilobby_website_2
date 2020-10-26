<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;


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

/*
Route::middleware('auth:sanctum')->get('antilobby/protected', function (Request $request) {
    return $request->user();
});
*/

/** Public pages */
Route::get('/', 'sessionController@index');
Route::get('antilobby', 'sessionController@index');
Route::get('antilobby/sessions', 'sessionController@index');
//Route::get('antilobby/sessions/all', 'sessionController@index');
Route::get('antilobby/sessions/stats', 'sessionController@stats');
Route::get('antilobby/sessions/stats/all', 'sessionController@statsall');
Route::get('antilobby/sessions/{sessionID}', 'sessionController@get');

/** Redirect pages */


/** Private pages for auth users */
Route::middleware('auth:sanctum')->get('antilobby/user/sessions/', 'UserWebResourceController@GetAllUserPrograms');
Route::middleware('auth:sanctum')->get('antilobby/user/program/totals', 'UserWebResourceController@GetUserProgramTotals');
Route::middleware('auth:sanctum')->get('antilobby/user/program/totals/{appName}', 'UserWebResourceController@GetUserProgramSingle');


/** Chart routes */
Route::get('antilobby/chart', 'sessionController@chart');
Route::get('antilobby/chart/{val}', 'sessionController@chart');
Route::get('antilobby/chart/json/{id}', 'sessionController@chartJSON');


//Below dynamically gets the request so that it can be adapted to
//a chartJSON request
Route::get('antilobby/charts/{desc}', 'ChartController@index'); //
Route::get('antilobby/charts/json/{desc}', ['as'=> 'chart.grab', 'uses' => 'ChartController@index']); //<- defined route
Route::get('antilobby/charts/json/{desc}', 'ChartController@JSONHandler');


//Route::redirect('login', [ 'as' => 'login', 'uses' => 'sessionController@index']);
//Route::redirect('login', 'antilobby');

/** API REQUESTS from Antilobby - START */

Route::middleware('auth:sanctum')->get('antilobby/user/get', 'UserController@show'); //create a new id and echos it

//Session ID related API requests
Route::middleware('auth:sanctum')->get('antilobby/user/session/id', 'sessionController@create'); //create a new id and echos it
Route::middleware('auth:sanctum')->post('antilobby/user/session/update/{id}/{totalTime}','sessionController@update');

Route::middleware('auth:sanctum')->post('antilobby/user/apptime/{sessionid}/{appName}/{appTime}','AppTime@update');
Route::middleware('auth:sanctum')->put('antilobby/user/apptime/{sessionid}/{appName}/{appTime}/update','AppTime@update');
Route::middleware('auth:sanctum')->patch('antilobby/user/apptime/{sessionid}/{appName}/{appTime}/update','AppTime@update');

Route::get('antilobby/user/session/fetch/{id}', 'sessionController@show'); //get id and show information

/** API REQUESTS from Antilobby - END */

/* Save for later: this is how you call in a blade
Route::get('antilobby/charts/{desc}', ['as'=> 'chart.grab', 'uses' => 'ChartController@index']); //<- defined route
route('chart.grab', ['desc' => 'test'] // call this in blade
**/

/** Additional authentication provided by Laravel resources */

Route::post('antilobby/sanctum/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    return $user->createToken($request->device_name)->plainTextToken;
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
