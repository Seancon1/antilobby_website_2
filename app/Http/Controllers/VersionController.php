<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VersionController extends Controller
{
    /**
     * Display the root page for this VersionController
     *
     * @return \Illuminate\Http\Response
     */
    public function DisplayAbout()
    {
        return view('about');
    }

    public function DisplayDownload(Request $request)
    {
        $isAdmin = false;

        //dd($request->user()->getNameAttribute, $request->user()->getName, $request->user()->name);
        if(Auth::check()) {
           $isAdmin = ($request->user()->account_type >= 9000) ? true : false;
        }
        //$isAdmin = ($request->user()->account_type >= 9000) ? true : false;
        $currentVersion = \App\Models\AppVersion::where('id', '>', '0')->orderByDesc('version')->first();
        $allVersions = \App\Models\AppVersion::where('id', '>', '0')->orderByDesc('version')->skip(1)->take(5)->get();
        return view('download', ['appVersions' => $allVersions, 'currentVersion' => $currentVersion, 'isAdmin' => $isAdmin]);
    }
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
     * Fetches most recent upload for version control.
     *
     * @return \Illuminate\Http\Response
     */
    public function latest()
    {
        $currentVersion = \App\Models\AppVersion::where('id', '>', '0')->orderByDesc('version')->first();
        return redirect($currentVersion->download_path);
    }

     /**
     * Fetches most recent upload for version number.
     *
     * @return \Illuminate\Http\Response
     */
    public function version()
    {
        $currentVersion = \App\Models\AppVersion::where('id', '>', '0')->orderByDesc('version')->first();
        echo $currentVersion->version;
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
        $file = $request->file("file");

        //dd($request);
        //check if admin first
        $isAdmin = ($request->user()->account_type >= 9000) ? true : false;

        if(!$isAdmin)
            return "You cannot do this operation.";

        if($request->file()) {
            $fileName = $request->file->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('versions', $fileName, 'public');

            $versionModel = new \App\Models\AppVersion;

            $versionModel->alias = $request->input('aliasInput');
            $versionModel->notes = $request->input('FormNotes');;
            $versionModel->version = $request->input('versionInput');;
            $versionModel->download_path = '/storage/' . $filePath;
            $versionModel->save();    

            return back()->with('success', 'File was uploaded.');
        }


        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
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
