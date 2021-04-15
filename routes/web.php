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
Route::get('public/sessions', 'PublicWebResourceController@GetAllPublicSessions'); //primary url for Public Sessions

Route::get('public/sessions/stats', 'PublicWebResourceController@GetPublicStats'); //Public Stats
Route::get('session/{sessionID}', 'UserWebResourceController@GetSessionSingle'); //Fetch individual session
Route::get('program/{sessionID}/{apptimeID}', 'UserWebResourceController@GetAppSingle'); //Fetch individual app and it's relevant information

Route::get('about', 'VersionController@DisplayAbout'); //Fetch individual app and it's relevant information
Route::get('download', 'VersionController@DisplayDownload'); //Fetch individual app and it's relevant information
Route::post('download', 'VersionController@Store'); //store user upload

/** Download route for client & Version number*/
Route::get('download/latest', 'VersionController@latest'); //selects and begins downloading the most recent release
Route::get('download/latest/version', 'VersionController@version'); //prints the most current upload version

/** Redirect pages */


/** Private pages for auth users */
Route::middleware('auth:sanctum')->get('user/sessions/', 'UserWebResourceController@GetAllUserPrograms');
Route::middleware('auth:sanctum')->get('user/program/totals', 'UserWebResourceController@GetUserProgramTotals');
Route::middleware('auth:sanctum')->get('user/program/totals/{appName}', 'UserWebResourceController@GetUserProgramSingle');
Route::middleware('auth:sanctum')->get('user/program/inspect/{appName}', 'UserWebResourceController@InspectSingleProgram');
Route::middleware('auth:sanctum')->get('user/sessions/stats', 'UserWebResourceController@GetStats'); //Private Stats
Route::middleware('auth:sanctum')->get('user/sessions/stats', 'UserWebResourceController@GetStats'); //Private Stats
Route::middleware('auth:sanctum')->get('user/settings', 'UserWebResourceController@Settings'); //Private Stats


/** Public Chart routes */
Route::get('chart', 'sessionController@chart');
Route::get('chart/{val}', 'sessionController@chart');
Route::get('chart/json/{id}', 'sessionController@chartJSON');
Route::get('chart/public/sessions', 'PublicWebResourceController@GetAllPublicSessions_JSON'); //Main Page Chart
Route::get('chart/public/stats', 'PublicWebResourceController@GetPublicStatsJson'); //Main Page Chart

Route::get('chart/public/app/stats/{sessionID}/{apptimeID}', 'UserWebResourceController@GetAppSingleJson'); //Chart json: individual app times - detailed

//** Private User Charts */
Route::middleware('auth:sanctum')->get('chart/user/stats', 'UserWebResourceController@GetUserStatsJson');

//Below dynamically gets the request so that it can be adapted to
//a chartJSON request
Route::get('charts/{desc}', 'ChartController@index'); //
Route::get('charts/json/{desc}', ['as'=> 'chart.grab', 'uses' => 'ChartController@index']); //<- defined route
Route::get('charts/json/{desc}', 'ChartController@JSONHandler');


//Route::redirect('login', [ 'as' => 'login', 'uses' => 'sessionController@index']);
//Route::redirect('login', 'antilobby');

/** API REQUESTS from Antilobby - START */

Route::middleware('auth:sanctum')->get('user/get', 'UserController@show'); //create a new id and echos it

//Session ID related API requests
Route::middleware('auth:sanctum')->get('user/session/id', 'sessionController@create'); //create a new id and echos it
Route::middleware('auth:sanctum')->post('user/session/update/{id}/{totalTime}','sessionController@update');

//APPTIME updates
Route::middleware('auth:sanctum')->post('user/apptime/{sessionid}/{appName}/{appTime}','AppTime@update');
Route::middleware('auth:sanctum')->post('user/apptime/v2/{sessionid}/{appName}/{appTime}','AppTime@updateWithSegment');
Route::middleware('auth:sanctum')->post('user/apptime/v3/{sessionid}/{appName}/{appTime}','AppTime@updateAppAndDetails');
Route::middleware('auth:sanctum')->put('user/apptime/{sessionid}/{appName}/{appTime}/update','AppTime@update');
Route::middleware('auth:sanctum')->patch('user/apptime/{sessionid}/{appName}/{appTime}/update','AppTime@update');

Route::get('user/session/fetch/{id}', 'sessionController@show'); //get id and show information

/** API REQUESTS from Antilobby - END */

/* Save for later: this is how you call in a blade
Route::get('antilobby/charts/{desc}', ['as'=> 'chart.grab', 'uses' => 'ChartController@index']); //<- defined route
route('chart.grab', ['desc' => 'test'] // call this in blade
**/

/** Additional authentication provided by Laravel resources */

Route::post('sanctum/token', function (Request $request) {
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

Route::get('sanctum/token', function (Request $request) {
    return redirect('/');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
