<div style="">
    <div class="row">
    <div class="col-sm-4"><a class="btn btn-link" href="{{ URL('api/antilobby') }}">Index</a></div>
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
        @guest
            Welcome Guest ({{ $userIP }}) <a href="{{ URL("api2/login") }}">Login</a> | <a href="{{ URL("api2/register") }}">Register</a>
        @endguest
        @auth
            Welcome {{ auth()->user()->name ?? '' }} <a href="{{ URL("api2/dashboard") }}">Logout</a>
        @endauth
        </div>

    </div>
</div>
<hr />
