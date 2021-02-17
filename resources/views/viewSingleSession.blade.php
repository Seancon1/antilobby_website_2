@extends('layout.app')

 @section('content')

    <div class="flex-center position-ref full-height">
        <div class="content">
            <h1>Showing data of session {{ $sessionID }}</h1>

            @if($doesUserOwnSession)
                <hr />
                <p>This is your session. Change visibility settings below:</p>
                @livewire('private-setting', ['sessionValue' => $sessionID, 'user_id' => $request->user()->id])
            @endif

            <table class="table table-hover">
                <thead>
                    <th scope="col">#</th>
                    <th scope="col">Program Name</th>
                    <th scope="col">Time (Hours : Minutes : Seconds)</th>
                    <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @if($doesUserOwnSession || !$isSessionPrivate)
                        @foreach ($FetchedSession as $app)

                            <tr data-toggle="collapse" data-target="#collapsable{{ $app->id }}" aria-expanded="false" aria-controls="collapsable" onclick="doToggle('plusminus{{ $app->id }}')">
                                <th scope="row" id='plusminus{{ $app->id }}'>+</th>
                                    <td>{{ $app->appName }}</td>
                                    <td>{{ gmdate("H:i:s", $app->appTime) }}</td>
                                    <td></td>
                            </tr>
                            <tr class="collapse" id="collapsable{{ $app->id }}">
                                <td colspan="4" >
                                    <div class="card card-body">
                                        @include('charts.default', ['bladePassUrl'=> 'https://antilobby.prestigecode.com/chart/public/app/stats/' . $sessionID. '/'.$app->id, 'uniqueID' => $app->id, 'chartType' => 'bar', 'chartTitle' => '('.$app->appName .') Detailed Usage', 'legend' => 'true'])
                                    </div>
                                </td>
                            </tr>



                        @endforeach
                    @else
                        <tr>
                            <th scope="row">
                                <td><p>This session is private.</p></td>
                        </tr>
                    @endif

                </tbody>
            </table>
        </div>
    </div>

    <script>
        function doToggle(elementIDName) {
            document.getElementById(elementIDName).innerHTML = (document.getElementById(elementIDName).innerHTML   == '+' ? '-' : '+');
        }
    </script>
@endsection

