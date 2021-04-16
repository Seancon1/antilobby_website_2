<?php
use Carbon\Carbon;
use Carbon\CarbonInterval;
?>

@extends('layout.app')

 @section('content')


    <div class="flex-center position-ref full-height">
        <div class="content">
            <h3>Your Activity for {{ $appName ?? '' }}</h3>
            <div class="row">
                <div class="col-md-12">
                    <p>This page shows all of your activity for this selected process. This includes all sessions that
                        include {{ $appName ?? '' }}. The sum of time you have been active on this process is also displayed below.
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <h5 class="card-header">Last 7 Days</h5>
                        <div class="card-body">
                        <h5 class="card-title text-center">{{ CarbonInterval::seconds($timeTotals['week'])->cascade() ?? ''}}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <h5 class="card-header">Last 31 Days</h5>
                        <div class="card-body">
                        <h5 class="card-title text-center">{{ CarbonInterval::seconds($timeTotals['month'])->cascade() ?? ''}}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <h5 class="card-header">Last 365 Days</h5>
                        <div class="card-body">
                        <h5 class="card-title text-center">{{ CarbonInterval::seconds($timeTotals['year'])->cascade() ?? ''}}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <p>&nbsp;</p>

            <table class="table table-hover">
                <thead>
                    <th scope="col"></th>
                    <th scope="col">Session ID</th>
                    <th scope="col">Program</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Date</th>
                </thead>
                <tbody>
                    @foreach($apps as $app)
                    <tr>
                        <th scope="row">
                            <td class="inner-table"><a href="/session/{{ $app->sessionValue }}">{{ $app->sessionValue }}</a></td>
                            <td class="inner-table">{{ $app->appName }}</td>
                            <td class="inner-table">{{ CarbonInterval::seconds($app->appTime)->cascade()->forHumans(['short' => true]) }}</td>
                            <td class="inner-table">{{ $app->created_at }}</td>
                    </tr>
                    @endforeach

                </tbody>
            </table>

            <p>{{ $apps->links() }}</p>

        </div>
    </div>

@endsection

