@extends('layout.app')
 @section('content')
    <div class="flex-center position-ref full-height">
        <div class="content">
            @if(!$PublicSessions)
            <h4>Your Sessions</h4>
                <div class="col-md-12">@include('charts.default', ['bladePassUrl'=> 'https://antilobby.prestigecode.com/user/sessions?json=true', 'uniqueID' => '1', 'chartType' => 'bar', 'chartTitle' => 'Latest 20 Sessions (Newest to Oldest)', 'legend' => 'true', 'colors' => '#1B5CB5'])</div>
            @else
            <h4>Public Sessions</h4>
                <div class="col-md-12">@include('charts.default', ['bladePassUrl'=> 'https://antilobby.prestigecode.com/chart/public/sessions', 'uniqueID' => '2', 'chartType' => 'bar', 'chartTitle' => 'Latest 10 Public Sessions (Newest to Oldest)', 'legend' => 'true'])</div>
            @endif

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Session ID</th>
                        <th scope="col">Time (Hours : Minutes : Seconds)</th>
                        <th scope="col">Date</th>
                        </tr>
                </thead>
                <tbody>

                    @foreach ($FetchedSessions as $session)
                        <tr>
                            <th scope="row">
                            <td><a href="{{ URL::to("session/") . "/". $session->sessionValue }}">{{ $session->sessionValue }}</a></td>
                            <td>{{ gmdate("H:i:s", $session->time) }}</td>
                            <td>{{ $session->date }}</td>

                            </th>
                        </tr>
                    @endforeach



                </tbody>
            </table>

        <p>{{ $FetchedSessions->links() }}</p>
        </div>
    </div>

@endsection

