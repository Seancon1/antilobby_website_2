<?php
use Carbon\Carbon;

$displayedFirst = false;
?>


@extends('layout.app')

 @section('content')
<div class="row">
        <div class="col-lg-12"> 
            <h1>Downloads</h1>
        </div>
    
    @if(Auth::check() && $isAdmin)
    <div class="col-lg-12"> 
      <button class="btn btn-warning" type="button" data-toggle="collapse" data-target="#collapseAdmin" aria-expanded="false" aria-controls="collapseAdmin">
        <span class="oi oi-bell">Admin Panel</span>
      </button>
      <div class="collapse" id="collapseAdmin">
        <div class="card card-body">
            <div class="row">
              <form action="/download" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                  <label for="GroupInput">Version #</label>
                  <input type="text" class="form-control" name="versionInput" id="versionInput" placeholder="version #" required>
                </div>
                <div class="form-group">
                  <label for="GroupInput">Alias Title</label>
                  <input type="text" class="form-control" name="aliasInput" id="aliasInput" placeholder="title" required>
                </div>
                <div class="form-group">
                  <label for="FormControlTextarea1">Extra notes</label>
                  <textarea class="form-control" name="FormNotes" id="FormNotes" rows="3"></textarea>
                </div>
                <div class="form-group">
                  <label for="file">Upload New Version</label>
                  <input type="file" class="form-control-file" name="file" id="file" required>
                </div>
                <button type="submit" class="btn btn-primary">Complete Upload</button>
              </form>
            </div>
        </div>
      </div>
    </div>
    @endif

        <div class="col-lg-6"> 
          <p>Welcome to the Antilobby download page. Here is the official location that all new releases will be made. Please use this space to receive all updates for Antilobby.</p>
          <p>Keep in mind, the Antilobby application does have an auto-updating feature which will soon be using this new location to download the most recent version.</p>
          <p>I hope that you find Antilobby insightful for your day-to-day computer usage. Watch this space!</p>
          <p class="alert alert-info">By downloading this application, you understand it is in early stages of development and may not provide enhanced security measures for your data and may have features that do not work.</p>
        </div>  
        <div class="col-lg-6"> 

            <div class="card text-center">
              <div class="card-header">
                Latest Release (v{{ $currentVersion->version }})
              </div>
              <div class="card-body">
                <h5 class="card-title">{{$currentVersion->alias}}</h5>
                <p class="card-text">{{$currentVersion->notes}}</p>
                <a href="{{ $currentVersion->download_path }}" class="btn btn-success">Download</a>
              </div>
              <div class="card-footer text-muted">
                {{ Carbon::parse($currentVersion->created_at)->diffForHumans() }}
              </div>
            </div>
            <p>&nbsp;</p>
        </div>
</div>
<div class="row">
  <div class="col-lg-6"></div>
  <div class="col-lg-6">
    @foreach($appVersions as $version)
    <div id="accordion">
      <div class="card">
        <div class="card-header" id="heading{{ $version->version }}">
          <h5 class="mb-0">
            <button class="btn btn-link" data-toggle="collapse" data-target="#collapse{{ $version->version }}" aria-expanded="false" aria-controls="collapse{{ $version->version }}">
              Version {{ $version->version }}
            </button> 
            <span class="text-muted">
                ( {{ Carbon::parse($version->created_at)->diffForHumans() }} )
            </span>
          </h5>
        </div>
    
        <div id="collapse{{ $version->version }}" class="collapse" aria-labelledby="heading{{ $version->version }}" data-parent="#accordion">
          <div class="card-body">
            {{ $version->notes }}
            <a href="{{ $version->download_path }}" class="btn btn-outline-secondary" style="margin-top: 5px;">Download</a>
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</div>

@endsection

