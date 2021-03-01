@extends('layout.app')

 @section('content')


    <?php
    //$avgPerSession = ($totalSessions < 1) ? '0' : round((($totalTime/3600))/$totalSessions, 2);
    //$baseURL = URL::to('api/antilobby/charts/json/');
    //dd($readyTotals);
    ?>


<div class="row">

@if(Auth::check() && $isPrivate ?? 'false')
<!-- User only graphs -->
<div class="col-md-12">
    <h3>Members Only Data</h3>   
</div>
<div class="col-md-12">@include('charts.default', ['bladePassUrl'=> 'https://antilobby.prestigecode.com/chart/user/stats?graph=commonDayOfWeek', 'uniqueID' => '5', 'chartType' => 'bar', 'chartTitle' => 'Your Most Common Days', 'legend' => 'true', 'colors'=> 'purple, red'])</div>
<div class="col-md-6">@include('charts.default', ['bladePassUrl'=> 'https://antilobby.prestigecode.com/chart/user/stats?graph=commonHourPersonal', 'uniqueID' => '3', 'chartType' => 'bar', 'chartTitle' => 'Your Most Common Hours', 'legend' => 'false', 'colors'=> 'purple, red'])</div>
<div class="col-md-6">@include('charts.default', ['bladePassUrl'=> 'https://antilobby.prestigecode.com/chart/user/stats?graph=commonMinute', 'uniqueID' => '4', 'chartType' => 'bar', 'chartTitle' => 'Public Most Common Minute', 'legend' => 'false', 'colors'=> 'purple, red'])</div>

<div class="col-md-12">
    <p style="color: gray;">The data above is collected by all of your sessions. The right graph is a sum of all public sessions.</p>
</div>
@else

<!-- Public Graphs-->
<div class="col-md-6">@include('charts.default', ['bladePassUrl'=> 'https://antilobby.prestigecode.com/chart/public/stats?graph=TopProcesses&show=15&type=time', 'uniqueID' => '1', 'chartType' => 'doughnut', 'chartTitle' => 'Top 15 Processes by Time Used', 'legend' => 'false'])</div>
<div class="col-md-6">@include('charts.default', ['bladePassUrl'=> 'https://antilobby.prestigecode.com/chart/public/stats?graph=TopProcesses&show=10&type=quantity', 'uniqueID' => '2', 'chartType' => 'doughnut', 'chartTitle' => 'Top 10 Processes by Count', 'legend' => 'false'])</div>

@endif
</div>


    <!--
  <div class="row">
    <div class="col-md-1"><button type="button" class="btn btn-light" onclick="displayChart()">Update</button></div>
  </div>
-->
    <div class="flex-center position-ref full-height">
        <div class="content">
        <h3>Showing all {{ $isPrivate ? 'of Your' : 'Public' }} Stats</h3>
            <table class="table table-hover">
                <thead>
                    <th scope="col">All Time Stats*</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    </tr>
                </thead>

                <tbody>
                        <tr>
                            <td>Total Time Recorded:</td>
                            <td>{{ round(($readyTotals->get('totaltime') /3600), 2) . " hrs" }} {{ "(" . $readyTotals->get('totaltime') . " ticks)" }}</td>
                        </tr>
                        <tr>
                            <td>Sessions Recorded:</td>
                            <td> {{ $readyTotals->get('count') }}</td>
                        </tr>
                        <tr>
                            <td>Average time per Session:</td>
                            <td>{{ round((($readyTotals->get('totaltime') / $readyTotals->get('count'))/ 3600), 2) . " hrs" }}</td>
                        </tr>
                </tbody>
            </table>
            <p>*Note: Only shows sessions of 5 minutes or more.</p>
        </div>
    </div>

@endsection

