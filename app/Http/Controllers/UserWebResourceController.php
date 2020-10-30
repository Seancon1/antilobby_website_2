<?php

namespace App\Http\Controllers;

use Chartisan\PHP\Chartisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
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

        $collectionToDisplay = ($request->input('time') == 'asc') ? $totalsCollection->sortAsc() : $totalsCollection->sortDesc();
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
            ->where('time','>', 300)
            ->orderByDesc('created_at')
            ->paginate(20);

        $userSessions->setPath('/api/antilobby/user/sessions');


        return view('viewSessionOverview', ['FetchedSessions' => $userSessions, 'request' => $request, 'PublicSessions' => false]);
    }

        /**
     * Fetch a single session using the {sessionID}
     * TO-DO: make it detect whether it is public or private & owner is viewing or not
     */
    public function GetSessionSingle(Request $request) {

        $fetchedSession = \App\Models\Session::find($request->sessionID)->apps;


        return view('viewSingleSession', ['userIP' => $request->ip(),
        'sessionID' => $request->sessionID,
        'FetchedSession' => $fetchedSession
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

                        $collectionToDisplay = $totalsCollection->sortDesc();


                    break;

                        case "quantity":

                            foreach(\App\Models\AppTime::where('user_id', '=', $request->user()->id)->cursor() as $app) {
                                $collection->push(['name' => $app->appName]);
                            }

                            $unique = $collection->countBy('name');
                            $collectionToDisplay = $unique->sortDesc();;
                            //dd($unique);

                        break;
                }



                if(isset($collectionToDisplay)) {

                    $collectionToDisplay = $collectionToDisplay->splice(0, $quantifier); //Quantifier is POST['show']/$request->input('show')

                    $outChart = Chartisan::build()
                        ->labels($collectionToDisplay->keys()->toArray())
                        ->dataset('Items', $collectionToDisplay->values()->toArray())
                        ->toJSON();

                }

            break;
        }

        return $outChart;
     }


}
