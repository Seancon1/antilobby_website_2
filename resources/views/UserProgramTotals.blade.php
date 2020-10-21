@extends('layout.app')
 @parent

 @section('content')



<div class="row">
    <div class="col-md-6">@include('charts.default', ['bladePassUrl'=> 'https://www.prestigecode.com/api/antilobby/charts/json/test', 'uniqueID' => '1', 'chartType' => 'bar', 'chartTitle' => 'Chart'])</div>
    <div class="col-md-6">@include('charts.default', ['bladePassUrl'=> 'https://www.prestigecode.com/api/antilobby/charts/json/test', 'uniqueID' => '2', 'chartType' => 'bar', 'chartTitle' => 'Chart'])</div>
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
                    <th scope="col">All Time Stats</th>
                    <th scope="col">1</th>
                    <th scope="col">2</th>
                    <th scope="col">3</th>
                    </tr>
                </thead>
                <tbody>
                        <tr>
                            <th scope="row">0</th>
                            <td>1</td>
                            <td>2</td>
                            <td>3</td>
                        </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection

