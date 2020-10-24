@extends('layout.app')
 @parent

 @section('content')



<div class="row">
    <div class="col-md-12">@include('charts.default', ['bladePassUrl'=> 'https://www.prestigecode.com/api/antilobby/program/totals?json=true', 'uniqueID' => '1', 'chartType' => 'doughnut', 'chartTitle' => 'Highest to Lowest Totals', 'legend' => 'false'])</div>
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
                    <th scope="col"><a href="{{ url("api/antilobby/program/totals?time=" . $sortFunc) }}">Total Time</a></th>
                    <th scope="col">-</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($SumOfUniquePrograms as $program)
                        <tr>
                            <th scope="row">{{ $program['appName'] }}</th>
                            <td>{{ gmdate("H:i:s", $program['appTime']) }}</td>
                            <td></td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

