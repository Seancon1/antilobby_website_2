<div style="">
    <div class="row">
    <div class="col-sm-3"><a class="btn btn-link" href="{{ URL('/') }}">Index</a></div>
        <div class="col-sm-4"></div>
        <div class="col-sm-5">
        @guest
            Welcome Guest ({{ $userIP }}) <a href="{{ URL("login") }}">Login</a> | <a href="{{ URL("register") }}">Register</a>
        @endguest
        @auth
        <form action="{{ URL("logout") }}" method="POST">
            @csrf
            Welcome {{ auth()->user()->name ?? '' }} | <a class="btn btn-light" href="{{ URL("dashboard") }}">Dashboard</a> <button class="btn btn-light" type="submit">Logout</button>
        </form>
        @endauth
        </div>

    </div>
</div>
<hr />
