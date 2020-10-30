@extends('layout.app')
 @parent
 @section('content')
    <div class="flex-center position-ref full-height">
        <div class="content">
            <h3>Public Sessions</h3>

            @if(!$PublicSessions)
                <div class="col-md-12"><p class="alert alert-success">Chart Coming Soon</p></div>
            @else
                <div class="col-md-12">@include('charts.default', ['bladePassUrl'=> 'https://www.prestigecode.com/api/antilobby/chart/public/sessions', 'uniqueID' => '1', 'chartType' => 'bar', 'chartTitle' => 'Lastest 10 Public Sessions (Newest to Oldest)', 'legend' => 'true', 'colors' => '#1B5CB5'])</div>
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
                            <td><a href="{{ URL::to("/api/antilobby/session/") . "/". $session->sessionValue }}">{{ $session->sessionValue }}</a></td>
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

