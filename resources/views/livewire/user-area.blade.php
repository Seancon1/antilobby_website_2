<div style="">
    <div class="row">
    <div class="col-sm-4"><a class="btn btn-link" href="{{ URL('api/antilobby') }}">Index</a></div>
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            Welcome {{ $username }} ({{ $userIP }}) | <a href="{{ URL("api/antilobby/user/register") }}">Register</a>
        </div>
    </div>
</div>
<hr />