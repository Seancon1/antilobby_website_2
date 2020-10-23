<?php

namespace App\Http\Controllers;

use Chartisan\PHP\Chartisan;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Request as GlobalRequest;

class UserWebResourceController extends Controller
{
    //
    public function GetUserIDStats(Request $request) {


        return "user data";
    }

    public function GetUserProgramTotals(Request $request) {

        //echo $request->user();
        //Collect all user session values
        $AllUserSessionVals = \App\Session::where('user_id', "=", $request->user()->id)->get();

        //var_dump($AllUserSessionVals);
        //var_dump($AllUserSessionVals->sessionValue);

        $collection = collect();
        foreach($AllUserSessionVals as $item) {
            $collection->push($item->sessionValue);
        }

        $programs = \App\Models\AppTime::whereIn('sessionValue', $collection)->get();
        //$programs = $programs->sum('appTime');

        //$unique_programs = collect($programs->unique('appName')); //filter all different appNames so I can iterate and add them
        $unique_programs_sum = collect();

        $grouped_keys = $programs->groupBy('appName');
        $grouped_keys->sum('appTime');

        $testCollection = [];

        foreach($grouped_keys as $item) {
            foreach($item as $singleitem) {
                $tempItem = collect(['appName' => $singleitem->appName, 'appTime' => $item->sum('appTime')]);

                if(!$unique_programs_sum->contains($tempItem)){
                    $unique_programs_sum->push($tempItem);
                    $testCollection[$singleitem->appName] = $singleitem->appTime;
                }
            }
        }

        if ($request->input('json') == "true") {

            arsort($testCollection);

            $outChart = Chartisan::build()
            ->labels(array_keys($testCollection))
            ->dataset('Time', array_values($testCollection))
            ->toJSON();

            $jsonOut = json_decode($outChart);

            return view('charts.chartJSONresponse', ['json' => 'true', 'chartData' => $jsonOut, 'flag'=> 0]);

        }

        return view("UserProgramTotals", ['AllUserPrograms' => $programs, 'SumOfUniquePrograms'=>$unique_programs_sum]);
    }

    public function GetUserProgramSingle(Request $request) {
        //Fetch all instances of the program name

        /** This works using any ID that matches up to the appName */

        //$program = \App\Models\AppTime::where('');
        //$sessionList = \App\Session::where('sessionValue', '=', $program->appName)->get();
        //var_dump($program);

        return '';
    }

}
