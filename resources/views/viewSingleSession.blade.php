@extends('layout.app')

 @section('content')


    <div class="flex-center position-ref full-height">
        <div class="content">
            <h1>Showing data of session {{ $sessionID }}</h1>
            <table class="table table-hover">
                <thead>
                    <th scope="col">#</th>
                    <th scope="col">Program Name</th>
                    <th scope="col">Time (Hours : Minutes : Seconds)</th>
                    <th scope="col">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($FetchedSession as $app)
                        <tr>
                            <th scope="row">
                                <td>{{ $app->appName }}</td>
                                <td>{{ gmdate("H:i:s", $app->appTime) }}</td>
                                <td>-</td>
                        </tr>
                    @endforeach



                </tbody>
            </table>
        </div>
    </div>

@endsection

