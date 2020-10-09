<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Chartisan\PHP\Chartisan;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ChartController extends Controller
{
    //Grab request dynamically, then transform request into a json url
    //so that I can form
    public function index(Request $request) {

        $bladeName = 'charts.';
        $getURL = '';

        switch($request->desc) {
            case "single_session_total":
                $bladeName .= "chart_single_session_total";
                $getURL .= 'api/antilobby/charts/json/' . $request->desc . '';
            break;

            case "1":
                $bladeName .= "default";
                $getURL = "api/antilobby/charts/json/1";
            break;

            default:
                $bladeName .= "default";
                $getURL = "api/antilobby/charts/json/default";
            break;
        }

        return view($bladeName, ['jsonURL' => $getURL, 'returnedTitle' => $request->desc]);
    }

    public function JSONHandler(Request $request) {

        $chartData = ''; //start with no chart data
        $collection = null;
        $flag = []; //define a space to pass flags to the chart blade
        $chartType = 'bar';
        $chartTitle = "Chart";

        //build data based on income request
        switch($request->desc) {

            case "test":
                //$collection = DB::select(DB::raw("SELECT * FROM `session` WHERE `sessionValue` IN ( SELECT `sessionValue` FROM `ip_table` WHERE `IP` = '" . $request->ip() . "')"));

                $itemKeys = [];
                $itemVals = [];

                for($i = 0; $i < 10; $i++) {
                    array_push($itemKeys, "DATA-KEY".$i);
                    array_push($itemVals, $i);
                }


                $outChart = Chartisan::build()
                ->labels($itemKeys)
                ->dataset('Items', $itemVals)
                ->toJSON();

            break;

            case "stats_top_10_processes":
                $totalProcesses = DB::select(DB::raw("SELECT `appName`, `appTime` FROM `apptime` WHERE `sessionValue` IN (SELECT `sessionValue` FROM `ip_table` WHERE `IP` = '".$request->ip()."')"));
                $uniqueProcess = DB::select(DB::raw("SELECT DISTINCT `appName` FROM `apptime` WHERE `sessionValue` IN (SELECT `sessionValue` FROM `ip_table` WHERE `IP` = '".$request->ip()."')"));

                //var_dump(sizeof($totalProcesses));
                //var_dump(sizeof($uniqueProcess));

                $collection = []; //get keys from uniqueProcesses

                //var_dump(json_encode($totalProcesses), true);
                //var_dump($collection);

                foreach($uniqueProcess as $key) {
                    $collection[$key->appName] = 0;
                }
                //var_dump($collection);

                //go through each key
                foreach($totalProcesses as $item) {
                    if(!Arr::exists($totalProcesses, $item->appName)) {
                        $collection[$item->appName] = $item->appTime;
                    }
                        $collection[$item->appName] += $item->appTime;
                    }

                    arsort($collection);
                    $collection = array_slice($collection, 0, 10);

                    //store all values with key
                    //$tempCollection = array_column($totalProcesses, $key->appName);

                    //add the values
                    /*
                    for($i=0; $i < sizeof($tempCollection); $i++){
                        $collection[$key->appName] += $tempCollection[$i];
                    }
                    */
                //var_dump($collection);

                //var_dump($collection);

                /*
                foreach($uniqueProcess as $key) {
                    foreach($totalProcesses as $value){
                        if($key->appName == $value->appName){
                            $collection[$key->appName] += $value->appTime;
                        }
                    }
                }
                */



                $outChart = Chartisan::build()
                ->labels(array_keys($collection))
                ->dataset('Total Time', array_values($collection))
                ->toJSON();


            break;

            case "stats_overall_hourly_session":
            default:

            $outChart = Chartisan::build()
            ->labels(['No Data'])
            ->dataset('Data', [0])
            ->toJSON();

            break;
        }

        if(!empty($request->user())) {
            //Select personable data
        }

        //$outChart = empty($outChart) ? ['null' => null] : $outChart;
        $jsonOut = json_decode($outChart);
        return view('charts.chartJSONresponse', ['json' => 'true', 'chartData' => $jsonOut, 'flag'=> $flag]);
    }

    public function displayHourlyOverview(Request $request)
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
        return view('chartsJSONresponse', ['json' => true, 'chartData' => $jsonOut, 'passedData' => 'null']);
    }

    public function displaySingleSessionOverView(Request $request)
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
        return view('chartsJSONresponse', ['json' => true, 'chartData' => $jsonOut, 'passedData' => 'null']);
    }

}
