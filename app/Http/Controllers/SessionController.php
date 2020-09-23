<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $allSessionsFromIP = DB::select(DB::raw("SELECT * FROM `session` WHERE `sessionValue` IN ( SELECT `sessionValue` FROM `ip_table` WHERE `IP` = '" . $request->ip() . "' )"));
        //var_dump($allSessionsFromIP);
        return view('viewsessions', ['allUSessions' => $allSessionsFromIP]);
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
    public function update(Request $request, $id)
    {
        //
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
