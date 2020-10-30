@extends('layout.app')
 @parent

 @section('content')

<div class="row">
    <div class="col-md-6">@include('charts.default', ['bladePassUrl'=> 'https://www.prestigecode.com/api/antilobby/chart/user/stats?graph=TopProcesses&show=15&type=time', 'uniqueID' => '1', 'chartType' => 'doughnut', 'chartTitle' => 'Top 15 Processes (Time Used)', 'legend' => 'false', 'colors' => '#1B5CB5'])</div>
    <div class="col-md-6">@include('charts.default', ['bladePassUrl'=> 'https://www.prestigecode.com/api/antilobby/chart/user/stats?graph=TopProcesses&show=15&type=quantity', 'uniqueID' => '2', 'chartType' => 'doughnut', 'chartTitle' => 'Top 15 Processes (Count)', 'legend' => 'false', 'colors' => '#1B5CB5'])</div>
</div>

    <!--
  <div class="row">
    <div class="col-md-1"><button type="button" class="btn btn-light" onclick="displayChart()">Update</button></div>
  </div>
-->
    <div class="flex-center position-ref full-height">
        <div class="content">
        <h1>Showing Program Totals</h1>
            <table class="table table-hover">
                <thead>
                    <th scope="col">Program Name</th>
                    @php
                     $sortFunc = ($request->input('time') == 'asc') ? 'desc' : 'asc';
                    @endphp
                    <th scope="col"><a href="{{ url("api/antilobby/user/program/totals?time=" . $sortFunc) }}">Total Time</a></th>
                    <th scope="col">-</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($SumOfUniquePrograms as $key => $value)
                        <tr>
                            <th scope="row">{{ $key }}</th>
                            <td>{{ round(($value/3600), 2) }} hr(s) ({{$value}})</td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

