@extends('layout.app')
 @parent
 @section('content')
    <div class="flex-center position-ref full-height">
        <div class="content">
            <h3>Fetching all of your Sessions</h3>
            @guest
            @yield('OverallChart', View::make('charts.chart'))
            @endguest

            @auth
            <p>Chart Coming Soon!</p>
            @endauth

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
                    <?php
                        if(empty($request)) {
                            $FetchedSessions = array_reverse($FetchedSessions, true);
                        }
                        else {
                            # code...
                        }
                        ?>
                    @foreach ($FetchedSessions as $session)
                        <tr>
                            <th scope="row">
                            <td><a href="{{ URL::to("/api/antilobby/sessions/") . "/". $session->sessionValue }}">{{ $session->sessionValue }}</a></td>
                            <td>{{ gmdate("H:i:s", $session->time) }}</td>
                            <td>{{ $session->date }}</td>

                            </th>
                        </tr>
                    @endforeach



                </tbody>
            </table>
        </div>
    </div>

@endsection

