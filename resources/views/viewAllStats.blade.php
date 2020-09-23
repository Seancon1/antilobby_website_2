@extends('layout.app')
 @parent

 @section('content')
     

    <?php
    $avgPerSession = ($totalSessions < 1) ? '0' : round((($totalTime/3600))/$totalSessions, 2); 
    $baseURL = URL::to('api/antilobby/charts/json/');
    ?>


<div class="row">
<div class="col-md-6">@include('charts.default', ['bladePassUrl'=> 'https://prestigecode.com/api/antilobby/charts/json/stats_top_10_processes', 'uniqueID' => '1', 'chartType' => 'doughnut', 'chartTitle' => 'Top 10 Processes', 'legend' => 'false'])</div>
    <div class="col-md-6">@include('charts.default', ['bladePassUrl'=> 'https://prestigecode.com/api/antilobby/charts/json/test', 'uniqueID' => '2', 'chartType' => 'bar', 'chartTitle' => 'Chart'])</div>
  </div>
<div class="row">
    <div class="col-md-6">@include('charts.default', ['bladePassUrl'=> 'https://prestigecode.com/api/antilobby/charts/json/default', 'uniqueID' => '3'])</div>
    <div class="col-md-6">
        @include('charts.default',
         ['bladePassUrl'=> 'https://prestigecode.com/api/antilobby/charts/json/test', 'uniqueID' => '4'])
    </div>
  </div>

    <!--
  <div class="row">
    <div class="col-md-1"><button type="button" class="btn btn-light" onclick="displayChart()">Update</button></div>
  </div>
-->
    <div class="flex-center position-ref full-height">
        <div class="content">
        <h1>Showing all time stats for {{ $userIP ?? '' }}</h1>
            <table class="table table-hover">
                <thead>
                    <th scope="col">All Time Stats</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                        <tr>
                            <td>Total Time Recorded:</td>
                            <td>{{ round(($totalTime/3600), 2) . " hrs" }} {{ "(" . $totalTime . " ticks)" }}</td>
                        </tr>
                        <tr>
                            <td>Sessions Recorded</td>
                            <td> {{ $totalSessions }}</td>
                        </tr>
                        <tr>
                            <td>Average time per Session</td>
                            <td>{{ $avgPerSession . " hrs" }}</td>
                        </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection

