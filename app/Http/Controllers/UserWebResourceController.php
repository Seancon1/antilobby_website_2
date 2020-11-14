<?php

namespace App\Http\Controllers;

use Chartisan\PHP\Chartisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Request as GlobalRequest;

class UserWebResourceController extends Controller
{
    //
    public function GetUserIDStats(Request $request) {


        return "user data";
    }

    public function GetUserProgramTotals(Request $request) {

        $collection = collect([]);

        foreach(\App\Models\AppTime::where('user_id', '=', $request->user()->id)->cursor() as $app) {
            $collection->push(['name' => $app->appName, 'time' => $app->appTime]);
        }

        $totalsCollection = collect([]);

        foreach($collection->unique('name') as $name) {
            $temp = $collection->where('name', $name['name']);
            //echo $name['name'] ." = ". $temp->sum('time') . " | \n";
            $totalsCollection->put($name['name'], $temp->sum('time'));
        }

        $collectionToDisplay = ($request->input('time') == 'asc') ? $totalsCollection->sort() : $totalsCollection->sortDesc();

        //dd($collectionToDisplay);

        return view("UserProgramTotals", ['request'=> $request, 'SumOfUniquePrograms'=>$collectionToDisplay]);
    }

    public function GetUserProgramSingle(Request $request) {
        //Fetch all instances of the program name

        /** This works using any ID that matches up to the appName */

        //$program = \App\Models\AppTime::where('');
        //$sessionList = \App\Session::where('sessionValue', '=', $program->appName)->get();
        //var_dump($program);

        return '';
    }

    public function GetAllUserPrograms(Request $request) {

        $userSessions = \App\Models\User::find($request->user()->id)
            ->getsessions()
            ->where('time','>', 299)
            ->orderByDesc('created_at')
            ->paginate(20);

        if($request->has('json') && $request->input('json')) {
            $chartCollection = collect([]);
            //iterate through to match
            foreach($userSessions as $session) { $chartCollection->put( $session->sessionValue, $session->time);}

            $outChart = Chartisan::build()
            ->labels($chartCollection->keys()->toArray())
            ->dataset('Items', $chartCollection->values()->toArray())
            ->toJSON();

            return $outChart;
        }

        $userSessions->setPath('/api/antilobby/user/sessions');

        return view('viewSessionOverview', ['FetchedSessions' => $userSessions, 'request' => $request, 'PublicSessions' => false]);
    }

        /**
     * Fetch a single session using the {sessionID}
     * TO-DO: make it detect whether it is public or private & owner is viewing or not
     */
    public function GetSessionSingle(Request $request) {

        $fetchedSession = \App\Models\Session::find($request->sessionID)->apps;
        $sessionData = \App\Models\Session::where('sessionValue', '=', $request->sessionID)->get();
        //dd($sessionData);
        $doesUserOwnSession = (Auth::check()) ? (($request->user()->id == $sessionData[0]->user_id) ? true : false) : false;

        return view('viewSingleSession', ['userIP' => $request->ip(),
        'sessionID' => $request->sessionID,
        'FetchedSession' => $fetchedSession,
        'request' => $request,
        'doesUserOwnSession' => $doesUserOwnSession,
        'isSessionPrivate' => $sessionData[0]->private
        ]);
    }


    /**
     * Fetch a single session using the {sessionID}
     * TO-DO: make it detect whether it is public or private & owner is viewing or not
     */
    public function GetAppSingle(Request $request) {

        //$fetchedSession = \App\Models\Session::find($request->sessionID)->apps;
        $programData = \App\Models\AppTime::where('id', '=', $request->apptimeID)->where('sessionValue', '=', $request->sessionID)->first();
        //dd($sessionData);
        $doesUserOwnProgram = (Auth::check()) ? (($request->user()->id == $programData[0]->user_id) ? true : false) : false;

        return view('viewSingleProgram', ['userIP' => $request->ip(),
        'sessionID' => $request->sessionID,
        'request' => $request,
        'doesUserOwnProgram' => $doesUserOwnProgram,
        'isSessionPrivate' => $programData[0]->private
        ]);
    }

    public function GetAppSingleJson(Request $request) {

        //$fetchedSession = \App\Models\Session::find($request->sessionID)->apps;
        $programData = \App\Models\AppTime::where('id', '=', $request->apptimeID)->where('sessionValue', '=', $request->sessionID)->first();
        //dd($sessionData);
        $doesUserOwnProgram = (Auth::check()) ? (($request->user()->id == $programData[0]->user_id) ? true : false) : false;

        return view('viewSingleProgram', ['userIP' => $request->ip(),
        'sessionID' => $request->sessionID,
        'request' => $request,
        'doesUserOwnProgram' => $doesUserOwnProgram,
        'isSessionPrivate' => $programData[0]->private
        ]);
    }

        /**
     *
     *
     * Private User Stats JSONs
     * JSON ENDPOINT for graphs
     *
     * OUTPUT JSON string
     */
    function GetUserStatsJson(Request $request) {

        $outChart = null;
        $quantifier = ($request->has('show')) ? $request->input('show') : '10' ;
        $type = ($request->has('type')) ? $request->input('type') : null;
        $collectionToDisplay = null;
        $isJSON = ($request->has('json')) ? $request->input('json') : false;
        $datasetDescription = "Items";

        switch($request->input('graph')) {
            case 'TopProcesses':

                //$processes = \App\Models\AppTime::where('private', '=', false)->get();
                $collection = collect([]); //empty collection

                switch($type) {

                    case 'time':

                        foreach(\App\Models\AppTime::where('user_id', '=', $request->user()->id)->cursor() as $app) {
                            $collection->push(['name' => $app->appName, 'time' => $app->appTime]);
                        }

                        $totalsCollection = collect([]);

                        foreach($collection->unique('name') as $name) {
                            $temp = $collection->where('name', $name['name']);
                            //echo $name['name'] ." = ". $temp->sum('time') . " | \n";
                            $totalsCollection->put($name['name'], $temp->sum('time'));
                        }
                        $datasetDescription ="Time (Seconds)";
                        $collectionToDisplay = $totalsCollection->sortDesc();


                    break;

                        case "quantity":

                            foreach(\App\Models\AppTime::where('user_id', '=', $request->user()->id)->cursor() as $app) {
                                $collection->push(['name' => $app->appName]);
                            }

                            $unique = $collection->countBy('name');
                            $collectionToDisplay = $unique->sortDesc();;
                            $datasetDescription ="Count";
                            //dd($unique);

                        break;
                }



                if(isset($collectionToDisplay)) {

                    $collectionToDisplay = $collectionToDisplay->splice(0, $quantifier); //Quantifier is POST['show']/$request->input('show')

                    $outChart = Chartisan::build()
                        ->labels($collectionToDisplay->keys()->toArray())
                        ->dataset($datasetDescription, $collectionToDisplay->values()->toArray())
                        ->toJSON();

                }

            break;
        }

        return $outChart;
     }


     function GetStats(Request $request) {

        $Sessions = \App\Models\Session::where('user_id', '=', $request->user()->id)->where('time', '>=', 300)->get();

        if($Sessions->count() < 1){
            return view('viewAllStats', ['readyTotals' => null, 'request' => $request, 'PublicSessions' => true]);
        }

        $totals = collect([]);
        /*
        foreach($Sessions as $session) {
            $totals->push('totaltime', $session->time);
        }
        */

        $readyTotals = collect([]);
        $readyTotals->put('count', $Sessions->count()); //count
        $readyTotals->put('totaltime', $Sessions->sum('time')); //count

        //dd($readyTotals);

        return view('viewAllStats', ['readyTotals' => $readyTotals, 'request' => $request, 'isPrivate' => true]);
     }

     function Settings(Request $request) {


        return view('settings');
     }
}
