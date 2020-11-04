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
                    <th scope="col">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @if($doesUserOwnSession || !$isSessionPrivate)
                        @foreach ($FetchedSession as $app)
                            <tr>
                                <th scope="row">
                                    <td>{{ $app->appName }}</td>
                                    <td>{{ gmdate("H:i:s", $app->appTime) }}</td>
                                    <td>-</td>
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

@endsection

