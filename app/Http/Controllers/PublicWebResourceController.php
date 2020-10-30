<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Chartisan\PHP\Chartisan;


class PublicWebResourceController extends Controller
{
    /**
     *
     *
     * Public Stats Resource
     *
     */

     /**
      * JSON ENDPOINT
      */
    public function GetAllPublicSessions_JSON(Request $request) {
        $publicSessions = \App\Models\Session::where('private', '=', false)->where('time', '>', 1200)->take(10)->orderByDesc('created_at')->get();

        $collection = collect([]);
        foreach($publicSessions as $session){
            //echo $session->sessionValue ." - " . $session->time . " | \n";
            $collection->put($session->sessionValue, $session->time);
        }



        $outChart = Chartisan::build()
        ->labels($collection->keys()->toArray())
        ->dataset('Items', $collection->values()->toArray())
        ->toJSON();

        //dd($collection);

        return $outChart;
    }

    /**
     * HTML RESOURCE
     */

    public function GetAllPublicSessions(Request $request) {
        $publicSessions = \App\Models\Session::where('private', '=', false)->where('time', '>', 1200)->orderByDesc('created_at')->paginate(20);
        $publicSessions ->setPath('/api/antilobby/public/sessions');

        return view('viewSessionOverview', ['FetchedSessions' => $publicSessions, 'userIP' => $request->ip(), 'PublicSessions' => true]);
    }


    /**
     *
     *
     * Public Stats JSONs
     * JSON ENDPOINT for graphs
     *
     * OUTPUT JSON string
     */
     function GetPublicStatsJson(Request $request) {

        $outChart = null;
        $quantifier = ($request->has('show')) ? $request->input('show') : '10' ;
        $type = ($request->has('type')) ? $request->input('type') : null;
        $collectionToDisplay = null;

        switch($request->input('graph')) {
            case 'TopProcesses':


                //$processes = \App\Models\AppTime::where('private', '=', false)->get();
                $collection = collect([]); //empty collection

                switch($type) {

                    case 'time':

                        foreach(\App\Models\AppTime::where('private', '=', false)->cursor() as $app) {
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

                            foreach(\App\Models\AppTime::where('private', '=', false)->cursor() as $app) {
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
