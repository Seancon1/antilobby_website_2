<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Request as GlobalRequest;

class UserWebResourceController extends Controller
{
    //
    public function GetUserIDStats(Request $request) {


        return "user data";
    }

    public function GetUserProgramTotals(Request $request) {

        echo $request->user();
        //Collect all user session values
        $AllUserSessionVals = \App\Session::where('user_id', "=", $request->user()->id)->get();

        //var_dump($AllUserSessionVals);
        //var_dump($AllUserSessionVals->sessionValue);

        $collection = collect();
        foreach($AllUserSessionVals as $item) {
            $collection->push($item->sessionValue);
        }

        //print_r($collection->all());

        echo "\n -----------------";
        //$programs = new \App\AppTime::where('sessionValue', "=", $collection->all())->get();

        echo "Count of diff ses vals (" . $collection->count() . ")";
        for($i =0; $i <= $collection->count() - 1; $i++){
            echo $collection[$i] . "\n";
        }

        $programs = \App\Models\AppTime::where('sessionValue', '=', $collection->all())->get();
        print_r($programs->appName);

        return ".end";
        //return view("UserProgramTotals", ['data' => 'datavalue']);
    }

}
