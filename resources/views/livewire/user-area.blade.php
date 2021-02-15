<div style="">
    <div class="row">
    <div class="col-sm-4"><a class="btn btn-link" href="{{ URL('/') }}">Index</a></div>
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
        @guest
            Welcome Guest ({{ $userIP }}) <a href="{{ URL("login") }}">Login</a> | <a href="{{ URL("register") }}">Register</a>
        @endguest
        @auth
            Welcome {{ auth()->user()->name ?? '' }} | <a href="{{ URL("dashboard") }}">Logout</a>
        @endauth
        </div>

    </div>
</div>
<hr />
