<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Chartisan\PHP\Chartisan;
use Request as GlobalRequest;

class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $unique_session_list = DB::table('ip_table')
                                ->select('sessionValue')
                                ->where('IP', '=', '69.249.0.73');

        //var_dump($unique_session_list);
        //var_dump($unique_session_list->sessionValue);

        /*
        $allSessionsFromIP = DB::table('session')
                            ->select('*')
                            ->where('sessionValue', 'IN')
                            ->union($unique_session_list)
                            ->get();
        */
        $allSessionsFromIP = DB::select(DB::raw("SELECT * FROM `session` WHERE `sessionValue` IN ( SELECT `sessionValue` FROM `ip_table` WHERE `IP` = '" . $request->ip() . "')"));
        //var_dump($allSessionsFromIP);
        //print_r($allSessionsFromIP);
        return view('viewSessionOverview', ['FetchedSessions' => $allSessionsFromIP, 'userIP' => $request->ip()]);
    }


    public function get(Request $request) {
        $fetchedSession = DB::select(DB::raw("SELECT * FROM `apptime` WHERE `sessionValue` = $request->sessionID"));

        //echo "get request for session id  " . $request->sessionID;
        //echo "\n get request for IP " . $request->ip();
        return view('viewSingleSession', ['userIP' => $request->ip(),
        'sessionID' => $request->sessionID,
        'FetchedSession' => $fetchedSession
        ]);
    }


    public function stats(Request $request) {
        //$fetchedSession = DB::select(DB::raw("SELECT * FROM `apptime` WHERE `sessionValue` = $request->sessionID"));
        //$totalTime = DB::select(DB::raw("SELECT SUM(time) FROM `session` WHERE `sessionValue` IN (select `sessionValue` FROM `ip_table` where `ip` = '".$request->ip()."')"));

        $selectValues = DB::select('Select `sessionValue` FROM `ip_table` WHERE `ip` = "'. $request->ip() .'" ');

        /*
        $selectValues = DB::table('ip_table')
                        ->where('sessionValue', $request->ip())
                        ->pluck('sessionValue');
                        */

        //var_dump($selectValues);
        //var_dump(json_decode(json_encode($selectValues), true));
        $selectValues2 = json_decode(json_encode($selectValues), true); //converts it to an associative array AKA no std classes
        //var_dump(Arr::pluck($selectValues2, 'sessionValue')); //returns only one dimension of sessionValues

        $totalTime = DB::table('session')
                        ->whereIn('sessionValue', Arr::pluck($selectValues2, 'sessionValue'))
                        ->sum('time');
        //print_r($totalTime);
        /*
        $totalTime = getTotalTimeRecorded(getSessionsFromIP($WEBUSER_IP));
        $totalTimeInHours = round(($totalTime/3600), 2);
        $avgTimePerSession = round(($totalTimeInHours / count(getSessionsFromIP($WEBUSER_IP))), 2);
        */

        $totalSessionsRecorded = DB::table('ip_table')
                                ->WHERE('IP', "=", $request->ip())
                                ->count();

        //echo "get request for session id  " . $request->sessionID;
        //echo "\n get request for IP " . $request->ip();
        return view('viewAllStats', ['userIP' => $request->ip(), 'totalTime' => $totalTime, 'totalSessions' => $totalSessionsRecorded]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function chart(Request $request)
    {
        //Build request and pass values statically to chart render
        /*
        $outChart = Chartisan::build()
                ->labels(['First', 'Secondff', 'Third'])
                ->dataset('Sample', [1, 2, 3])
                ->dataset('Sample 2', [3, 2, 1]);

        */
        $sentURL = "chart/json/1";

        return view('charts.chart', ['json' => false, 'sentURL' => $sentURL, 'chartData' => null, 'passedData' => $request->val]);
    }

    public function chartJSON(Request $request)
    {
        $allSessionsFromIP = DB::select(DB::raw("SELECT * FROM `session` WHERE `sessionValue` IN ( SELECT `sessionValue` FROM `ip_table` WHERE `IP` = '" . $request->ip() . "')"));
        $allSessionsFromIP = array_reverse($allSessionsFromIP, true); //flip
        $itemKeys = [];
        $itemVals = [];
        $count = 0;
        foreach($allSessionsFromIP as $session) {
            if($count < 20){
            array_push($itemKeys, $session->sessionValue);
            array_push($itemVals, $session->time);
            $count++;
            }
        }

        $outChart = Chartisan::build()
        ->labels($itemKeys)
        ->dataset('Seconds', $itemVals)
        ->toJSON();

        $jsonOut = json_decode($outChart);
        return view('chartjson', ['json' => true, 'chartData' => $jsonOut, 'passedData' => 'null']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $numbers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 0];
        //$random = Arr::random($numbers, 10) . Arr::random($numbers, 10) . Arr::random($numbers, 10); //better way to do this ik

        //Generate a new ID for a session, returns a unique ID to be used to save app times and update session data
        $session = new \App\Models\Session;

        //Generate a random numeric value
        $session->sessionValue = mt_rand(0,999999999999999);
        $session->user_id = $request->user()->id;
        $session->private = true;
        echo $session->sessionValue;
        $session->save();

        return;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $session = \App\Models\Session::find(1)->where('sessionValue', '=', $id)->get();

        return $session->toJson();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @param  int  $totalTime
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $totalTime)
    {
        //Update session values
        $session = \App\Models\Session::where('sessionValue', '=', $id)->firstOrFail();
        $session->time = $totalTime;
        $session->user_id = $request->user()->id; //putting this here incase old sessions were not created with a linked uid

        //check to see if pair has been added
        $ip_table = \App\Models\ip_table::find(1)->where('IP', '=', $request->ip())->where('sessionValue', '=', $id)->first();
        if(!empty($ip_table)){
            $ip_table->IP = $request->ip();
            $ip_table->sessionValue = $id;
        } else {
            $ip_table = new \App\Models\ip_table;
            $ip_table->IP = $request->ip();
            $ip_table->sessionValue = $id;
            $session->private = true;
        }

        /*
        if($session->save()) {
        return "succesfully updated session" . $id . " value to: " . $totalTime;
        }
        */
        return (($session->save() && $ip_table->save()) ? "success" : "error");

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
