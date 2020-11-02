@extends('layout.app')

 @section('content')

    <div class="flex-center position-ref full-height">
        <div class="content">
            <h1>Fetching all of your Sessions ( {{ $userIP ?? '' }} )</h1>
            <table>
                <thead>
                    <td>id</td>
                    <td>mac</td>
                    <td>timeType</td>
                    <td>time</td>
                    <td>date</td>
                    <td>sessionValue</td>
                    <td>timestamp</td>
                </thead>
                <tbody>
                    @foreach ($FetchedSessions as $session)
                        <tr>
                            <td class="inner-table">{{ $session->id }}</td>
                            <td class="inner-table">{{ $session->mac }}</td>
                            <td class="inner-table">{{ $session->timeType }}</td>
                            <td class="inner-table">{{ $session->time }}</td>
                            <td class="inner-table">{{ $session->date }}</td>
                            <td class="inner-table">{{ $session->sessionValue }}</td>
                            <td class="inner-table">{{ $session->timestamp }}</td>
                        </tr>
                    @endforeach



                </tbody>
            </table>
        </div>
    </div>

@endsection

