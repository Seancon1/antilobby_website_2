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
Route::get('/', 'PublicWebResourceController@GetAllPublicSessions');
Route::get('antilobby', 'PublicWebResourceController@GetAllPublicSessions');
Route::get('antilobby/public/sessions', 'PublicWebResourceController@GetAllPublicSessions'); //primary url for Public Sessions

Route::get('antilobby/public/sessions/stats', 'PublicWebResourceController@GetPublicStats'); //Public Stats
Route::get('antilobby/session/{sessionID}', 'UserWebResourceController@GetSessionSingle'); //Fetch individual session
Route::get('antilobby/program/{sessionID}/{apptimeID}', 'UserWebResourceController@GetAppSingle'); //Fetch individual app and it's relevant information


/** Redirect pages */


/** Private pages for auth users */
Route::middleware('auth:sanctum')->get('antilobby/user/sessions/', 'UserWebResourceController@GetAllUserPrograms');
Route::middleware('auth:sanctum')->get('antilobby/user/program/totals', 'UserWebResourceController@GetUserProgramTotals');
Route::middleware('auth:sanctum')->get('antilobby/user/program/totals/{appName}', 'UserWebResourceController@GetUserProgramSingle');
Route::middleware('auth:sanctum')->get('antilobby/user/sessions/stats', 'UserWebResourceController@GetStats'); //Private Stats
Route::middleware('auth:sanctum')->get('antilobby/user/sessions/stats', 'UserWebResourceController@GetStats'); //Private Stats
Route::middleware('auth:sanctum')->get('antilobby/user/settings', 'UserWebResourceController@Settings'); //Private Stats


/** Public Chart routes */
Route::get('antilobby/chart', 'sessionController@chart');
Route::get('antilobby/chart/{val}', 'sessionController@chart');
Route::get('antilobby/chart/json/{id}', 'sessionController@chartJSON');
Route::get('antilobby/chart/public/sessions', 'PublicWebResourceController@GetAllPublicSessions_JSON'); //Main Page Chart
Route::get('antilobby/chart/public/stats', 'PublicWebResourceController@GetPublicStatsJson'); //Main Page Chart

Route::get('antilobby/chart/public/app/stats/{sessionID}/{apptimeID}', 'UserWebResourceController@GetAppSingleJson'); //Chart json: individual app times - detailed

//** Private User Charts */
Route::middleware('auth:sanctum')->get('antilobby/chart/user/stats', 'UserWebResourceController@GetUserStatsJson');

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

//APPTIME updates
Route::middleware('auth:sanctum')->post('antilobby/user/apptime/{sessionid}/{appName}/{appTime}','AppTime@update');
Route::middleware('auth:sanctum')->post('antilobby/user/apptime/v2/{sessionid}/{appName}/{appTime}','AppTime@updateWithSegment');
Route::middleware('auth:sanctum')->post('antilobby/user/apptime/v3/{sessionid}/{appName}/{appTime}','AppTime@updateAppAndDetails');
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
