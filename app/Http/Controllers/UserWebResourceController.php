<?php

namespace App\Http\Controllers;

use App\Models\ApptimeDetailsHr;
use Chartisan\PHP\Chartisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Request as GlobalRequest;
use Carbon\Carbon;

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
            ->where('time','>=', 300)
            ->orderByDesc('created_at')
            ->paginate(20);

            //dd($userSessions);

        if($request->has('json') && $request->input('json')) {
            $chartCollection = collect([]);
            //iterate through to match
            foreach($userSessions as $session) { $chartCollection->put( $session->sessionValue, $session->time);}

            $outChart = Chartisan::build()
            ->labels($chartCollection->keys()->toArray())
            ->dataset('Seconds', $chartCollection->values()->toArray())
            ->toJSON();

            return $outChart;
        }

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
        $startTimeCollection = collect([]);
        $sessionStartTime = \App\Models\Session::find($request->sessionID)->first()->created_at;
        $startTimeCollection->put("{$sessionStartTime->format("h.i")}", 60);
    
       //dd($startTimeCollection);


        $programData = \App\Models\AppTime::where('id', '=', $request->apptimeID)->where('sessionValue', '=', $request->sessionID)->first();

        //dd($programData->hours);
        $defaultCollection = collect([]);

        //Populate the 24hr view with zeros
        for($hr = 0; $hr < 24; $hr++) {
            for($mins = 0; $mins < 60; $mins++) {
                $modifiedMin = $mins < 10 ? '0'.$mins : $mins;
                $defaultCollection->put("{$hr}.{$modifiedMin}", 0);
            }
        }

        //dd($collection);
        $newCollection = collect([]);
        foreach($programData->hours as $hr) {
            foreach($hr->minutes as $mins) {
                //echo "{$hr->hour}:{$mins->minute} = {$mins->count} |";
                //modifiedMin changes format to 01,02 ... 09, 10, preceding 0 for everything under 10
                $modifiedMin = $mins->minute < 10 ? '0'.$mins->minute : $mins->minute;
                $newCollection->put("{$hr->hour}.{$modifiedMin}", $mins->count);
            }
        }
        //dd($newCollection);
        //$mergedCollection = $newCollection->merge($defaultCollection);
        $chartCollection = $defaultCollection->merge($newCollection);

        //dd($chartCollection);
        $startTimeCollection = $defaultCollection->merge($startTimeCollection);
        //dd($startTimeCollection);

        $outChart = Chartisan::build()
        ->labels($chartCollection->keys()->toArray(), $startTimeCollection->keys()->toArray())
        ->dataset('Seconds Per Minute', $chartCollection->values()->toArray())
        ->dataset('Start Time', $startTimeCollection->values()->toArray())
        ->toJSON();

        return $outChart;

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
        $quantifier = ($request->has('show')) ? $request->input('show') : null;
        $type = ($request->has('type')) ? $request->input('type') : null;
        $collectionToDisplay = null;
        $isJSON = ($request->has('json')) ? $request->input('json') : false;
        $datasetDescription = "Items";

        //$processes = \App\Models\AppTime::where('private', '=', false)->get();
        $collection = collect([]); //empty collection

        switch($request->input('graph')) {

            //Count(*) for each hour, 0-24
            case 'commonHour':    

                for($h = 0; $h <= 23; $h++){
                    $hourCount = \App\Models\ApptimeDetailsHr::where('hour', $h)->count();
                    $collection->push($hourCount);
                }
                
                $quantifier = null;
                //$betaView = true;
                $datasetDescription = "Quantity Per Hour";
                $collectionToDisplay = $collection;
                //dd($collectionToDisplay);

            break;

            case 'commonHourPersonal':    

                /*
                for($h = 0; $h <= 23; $h++){
                    $hourCount = \App\Models\ApptimeDetailsHr::where('hour', $h)->count();
                    $collection->push($hourCount);
                }
                */
                $userAppIDs = collect([]);
                $countedHours = collect([]);
                
                //populate all user owned ids
                
                foreach(\App\Models\AppTime::where('user_id', '=', $request->user()->id)->cursor() as $app) {
                    $userAppIDs->push(['appID' => $app->id]);
                }
                
                //$userAppIDs = \App\Models\Session::where($request->user()->id, 'user_id')->apps();
                //dd($userAppIDs);

                $hourCollection = collect([]);

                //Go through and collect all hour commonalities
                foreach($userAppIDs as $idInspect) {
                    
                    $hoursOfID = \App\Models\AppTime::find($idInspect['appID'])->hours()->get();
                    //dd($hoursOfID);
                    //$collection->push($hoursOfID->);
                    
                    foreach($hoursOfID as $item) {
                        //dd($item);
                        //echo $item['hour'];
                        $hourCollection->push($item['hour']);
                    }
                    
                }

                //count now
                $collection = $hourCollection->countBy();

                //fill any missing hours
                for($i = 0; $i < 24; $i++){
                    if(!$collection->has($i)){
                        $collection->put($i, 0);
                    }
                }
                $collection = $collection->sortKeys();

                //dd($collection);
            
                $quantifier = null;
                //$betaView = true;
                $datasetDescription = "Quantity Per Hour";
                $collectionToDisplay = $collection;
                //dd($collectionToDisplay);

            break;

            case 'commonMinute':

                for($m = 0; $m <= 60; $m++){
                    $minCount = \App\Models\ApptimeDetailsMin::where('minute', $m)->count();
                    $collection->push($minCount);
                }
                
                $quantifier = null;
                $datasetDescription = "Quantity Per Minute";
                $collectionToDisplay = $collection;

            break;    

            case 'commonDayOfWeek':
                //prefill so I can just append a count to the value
                $dayOfWeekTotals = [
                    'Monday' => 0,
                    'Tuesday' => 0,
                    'Wednesday' => 0,
                    'Thursday' => 0,
                    'Friday' => 0,
                    'Saturday' => 0,
                    'Sunday' => 0,
                ];

                //fetch all of user's session creation dates, iterate through them using a pointer. Append to current count
                //using Carbon instance from Laravel Eloquent models
                 
                foreach(\App\Models\Session::where('user_id', '=', $request->user()->id)
                ->where('time', '>', 300)
                ->cursor() as $session) {
                    $dayOfWeekTotals[$session->created_at->format('l')]++;
                }
                
                //dd($dayOfWeekTotals);
                $collectionToDisplay = collect($dayOfWeekTotals);

            break;

            //Output graph to show most common week out of 52 weeks
            case 'overview':
                //$outChart = Chartisan::build();
                $rangeCollection = collect([]);
                $currentTime = Carbon::now();
                
                //fetch all sessions in the last 7 days
                /*
                $sessionsInRange = \App\Models\Session::where('user_id', '=', $request->user()->id)
                ->whereBetween('created_at', [$currentTime->subtract(7, "day"), $currentTime->now()])->get();
                */
                //dd($currentTime);

                switch($type) {
                    case 'month':
                        $timeFrameModifier = 31; //may have to change this depending on certain months
                        break;
                    case 'year':
                        $timeFrameModifier = 365;
                        break;
                    case 'week':
                    default:
                        $timeFrameModifier = 7;
                    break;
                }

                //Create a week collection starting with current day at index 7, with possible range of (1-7)
                for($d = $timeFrameModifier, $a = 0; $d > 0; $d--, $a++) {
                    
                    //modify the first date as NOW, otherwise subtract a day for each iteration
                    $range = ($d == $timeFrameModifier) ? Carbon::now() : Carbon::now()->subtract($a,'day');
                    
                    //currently grabbing anything with more than 0 seconds recorded (due to application generating 2 session ids)
                    $sessionsInRange = \App\Models\Session::where('user_id', '=', $request->user()->id)
                    ->where('time', '>', 0)
                    ->where('created_at', 'like', "%{$range->format("m-d")}%")->get();

                    //Base count for each range
                    $sessionInDateRangeCount = 0;


                    if(!$sessionsInRange->isEmpty()) {
                        /* loop through each session of the range and append the amount of apps tracked
                        * this is done incase user has multiple sessions per day
                        */
                        foreach($sessionsInRange as $session) {
                            $sessionInDateRangeCount += $session->apps()->count();
                        }
                    }
                    //echo "{$range->format('l')} ({$range->format("m-d")}) - {$sessionInDateRangeCount} |";
                    $rangeCollection->put("{$range->format('l')} ({$range->format("m-d")})", "{$sessionInDateRangeCount}");

                    //echo "Range: {$range->format('l')} ({$range->format("m-d")}), Count: {$sessionInDateRangeCount} |" . PHP_EOL;
                }

                $datasetDescription = "Process Count";
                $quantifier = null; //for now set quantifier to null to prevent splicing
                $collectionToDisplay = $rangeCollection->reverse();
                //dd($collectionToDisplay, $collectionToDisplay->keys()->toArray(), $collectionToDisplay->values()->toArray());

                //dd($collectionToDisplay);

            break;

            //Output graph that shows most common month out of the year. Current month being the end of the graph
            case 'overview_month':
            
            break;

            case 'overview_year':
            
            break;
            
            case 'TopProcesses':

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

            break;

            case 'demo':
            default:
            
            //Generate random data to be displayed
            $outChart = Chartisan::build();
            
            for($section = 0; $section < rand(5,10); $section++) {

                $collection = collect([]);
                $random = rand(1,100);

                for($abc = 0; $abc < $random; $abc++) {
                    $collection->put("".rand(1,100), rand(0, 1000));
                }
                
                
                $outChart->labels($collection->keys()->toArray())
                ->dataset("DemoSection".$section, $collection->values()->toArray());
            }

            return $outChart->toJSON();
                
            break;
        }

        if(isset($betaView)) {

            $outChart = Chartisan::build();

            foreach($collection as $key => $value){
                $outChart->dataset("Count", (array) $value);
            }

            $outChart->labels((array) $datasetDescription);
            
            return $outChart->toJSON();
        }

        if(isset($collectionToDisplay)) {

            //Null is forced where limiting the returned number of items is wanted
            if(!is_null($quantifier)) {
                           $collectionToDisplay = $collectionToDisplay->splice(0, $quantifier); //Quantifier is POST['show']/$request->input('show')
            }

            //dd($collectionToDisplay, $collectionToDisplay->keys()->toArray(), $collectionToDisplay->values()->toArray());

            $outChart = Chartisan::build()
                ->labels($collectionToDisplay->keys()->toArray())
                ->dataset($datasetDescription, $collectionToDisplay->values()->toArray())
                ->toJSON();

        }

        return $outChart;
     }


    public function GetStats(Request $request) {

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

    public function Settings(Request $request) {
        return view('settings');
     }

     /**
      * Generates Data for Inspect page, if no [?chart=true] then return view
      */
    public function InspectSingleProgram(Request $request) {

        if($request->input('chart') == true) {
            $Chart = Chartisan::build();

            $App = \App\Models\AppTime::where('user_id', '=', $request->user()->id)
            ->where('appName', '=', $request->appName)
            ->orderByDesc('created_at')
            ->get();
    

            /*
            $outChart->labels($collection->keys()->toArray())
                    ->dataset("DemoSection".$section, $collection->values()->toArray());

            $outChart = Chartisan::build()
                ->labels($collectionToDisplay->keys()->toArray())
                ->dataset($datasetDescription, $collectionToDisplay->values()->toArray())
                ->toJSON();
            */

            return 'chart_defined';
        }

        $App = \App\Models\AppTime::where('user_id', '=', $request->user()->id)
        ->where('appName', '=', $request->appName)
        ->orderByDesc('created_at')
        ->paginate(20);
        $collection = collect();

        //TEMPORARY: For now sum, up time from the last 1 week, 1 month, last 6 months
        $timeLastWeek = \App\Models\AppTime::where('user_id', '=', $request->user()->id)
                                            ->where('appName', '=', $request->appName)
                                            ->where('created_at', '>' ,Carbon::now()->subDays(7))
                                            ->sum('appTime');
        $collection->put('week', $timeLastWeek);        
        
        $timeLastMonth = \App\Models\AppTime::where('user_id', '=', $request->user()->id)
                                            ->where('appName', '=', $request->appName)
                                            ->where('created_at', '>' ,Carbon::now()->subDays(31))
                                            ->sum('appTime');
        $collection->put('month', $timeLastMonth);

        $timeLastYear = \App\Models\AppTime::where('user_id', '=', $request->user()->id)
                                            ->where('appName', '=', $request->appName)
                                            ->where('created_at', '>' ,Carbon::now()->subDays(365))
                                            ->sum('appTime');
        $collection->put('year', $timeLastYear);


        return view('programs.inspect', ['appName' => $request->appName, 'apps' => $App, 'timeTotals' => $collection]);
     }

}
