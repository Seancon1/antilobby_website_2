<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Request as GlobalRequest;

class AppTime extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //post
        $apptime = new \App\Models\AppTime;
        $apptime->sessionValue = $request->sessionid;
        $apptime->appName = $request->appName;
        $apptime->appTime = $request->appTime;

        $apptime->save();

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //PUT/PATCH

        $apptime = \App\Models\AppTime::find(1)->where([
            ['sessionValue', '=', $request->sessionid],
            ['appName', '=', $request->appName]
        ])->first();

        if(!empty($apptime)){
            $apptime->appTime = $request->appTime; //update any current sessions in progress
            $apptime->user_id = $request->user()->id;
        } else {
            $apptime = new \App\Models\AppTime;
            $apptime->appName = $request->appName;
            $apptime->appTime = $request->appTime;
            $apptime->sessionValue = $request->sessionid;
            $apptime->id = $request->sessionid;
            $apptime->user_id = $request->user()->id;
            $apptime->private = true;
        }

        return ($apptime->save() ? "success" : "error");

    }

    public function updateWithSegment(Request $request)
    {
        //PUT/PATCH

        /**
         * Fetch data segment to save
         *
         */
        $segmentCollection = collect();
         if($request->input('data-segment')) {

            //echo "Segment" . $request->input('data-segment');

            foreach ($request->input() as $key => $value) {
                $segmentCollection->put($key, $value);
            }

            //echo $segmentCollection->toJson();
         }

        $apptime = \App\Models\AppTime::where([
            ['sessionValue', '=', $request->sessionid],
            ['appName', '=', $request->appName]
        ])->first();


        if(!empty($apptime)){
            $apptime->appTime = $request->appTime; //update any current sessions in progress
            $tempCollection = collect((json_decode($apptime->appTimeSpec, true)));
        } else {
            $apptime = new \App\Models\AppTime;
            $apptime->appName = $request->appName;
            $apptime->appTime = $request->appTime;
            $apptime->sessionValue = $request->sessionid;
            $apptime->user_id = $request->user()->id;
            $apptime->private = true;
        }

        return ($apptime->save() ? "success" : "error");

    }


    public function utest(Request $request){
        //echo 'Working...';
        //dd($request->input());
        $collection = collect();

        echo "Saving segment" . $request->input('data-segment');

        foreach ($request->input() as $key => $value) {

            if($key != 'data-segment') {
                //Seperate hour:min
                $hourSplit = explode(':', $key);
                $collection->push(['hour' => $hourSplit[0], 'min' => $hourSplit[1], 'time' => $value]);
            }
            //$collection->put($key, $value);
        }

        //dd($collection);

        $apptime = \App\Models\AppTime::where([
            ['sessionValue', '=', $request->sessionid],
            ['appName', '=', $request->appName]
        ])->first();

        $apptimeID = ($apptime) ? $apptime->id : null;

        if(!empty($apptime)){
            $apptime->appTime = $request->appTime; //update any current sessions in progress
            $apptime->save();
        } else {
            //Make new entries
            $apptime = new \App\Models\AppTime;
            $apptime->appName = $request->appName;
            $apptime->appTime = $request->appTime;
            $apptime->sessionValue = $request->sessionid;
            $apptime->user_id = $request->user()->id;
            $apptime->private = true;
            $apptime->save();
            $apptime->refresh();
            $apptimeID = $apptime->id;
        }

        //Update app time details
        $this->updateQueryDetails($collection, $apptimeID);

        return '';
    }

    /**
     * $inCollection - > Collection to iterate with
     * $appTimeID - > apptime id to tie times with apptime it's saving for
     */
    public function updateQueryDetails($inCollection, $appTimeID) {

        foreach($inCollection as $item) {

            $hourIsLocated = \App\Models\ApptimeDetailsHr::where('apptime_id', '=', $appTimeID)
                            ->where('hour','=', $item['hour'])
                            ->first();

            if($hourIsLocated) {
                $minuteIsLocated =\App\Models\ApptimeDetailsMin::where('apptime_details_hr_id', '=', $hourIsLocated->id)
                        ->where('minute','=', $item['min'])
                        ->first();

                    if($minuteIsLocated) {
                        //update min value
                        $minuteIsLocated->minute = $item['min'];
                        $minuteIsLocated->count = $item['time'];
                        $minuteIsLocated->save();
                    } else {
                        //add minute with val
                        $apptimeDetailsMin = new \App\Models\ApptimeDetailsMin;
                        $apptimeDetailsMin->minute = $item['min'];
                        $apptimeDetailsMin->count = $item['time'];
                        //attach ID of hour entry
                        $apptimeDetailsMin->apptime_details_hr_id = $hourIsLocated->id;
                        $apptimeDetailsMin->save();
                    }
            } else {
                //add hr and min for apptime id
                $apptimeDetailsHr = new \App\Models\ApptimeDetailsHr;
                $apptimeDetailsHr->apptime_id = $appTimeID;
                $apptimeDetailsHr->hour = $item['hour'];

                $apptimeDetailsMin = new \App\Models\ApptimeDetailsMin;
                $apptimeDetailsMin->minute = $item['min'];
                $apptimeDetailsMin->count = $item['time'];

                //Not sure if there is an easier way to do this...
                $apptimeDetailsHr->save();
                $apptimeDetailsMin->save();
                //refresh so I can access the ID that they have been submitted with
                $apptimeDetailsHr->refresh();
                $apptimeDetailsMin->refresh();

                //Attach id's to each other
                $apptimeDetailsHr->apptime_details_min_id = $apptimeDetailsMin->id;
                $apptimeDetailsMin->apptime_details_hr_id = $apptimeDetailsHr->id;

                $apptimeDetailsHr->save();
                $apptimeDetailsMin->save();
            }

        }
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
