
<h2>Antilobby Resource Hub</h2>

<div class="btn-group" role="group" aria-label="...">
<a class="btn btn-outline-dark" href="{{ URL::to("/api/antilobby/public/sessions/") }}" role="button">Public Sessions</a>
<a class="btn btn-outline-dark" href="{{ URL::to("/api/antilobby/public/sessions/stats") }}" role="button">Public Stats</a>
</div>

@auth

<div class="btn-group" role="group" aria-label="...">
<a class="btn btn-outline-dark" href="{{ URL::to("/api/antilobby/user/sessions/") }}" role="button">All Sessions</a>
<a class="btn btn-outline-dark" href="{{ URL::to("/api/antilobby/user/program/totals") }}" role="button">Program Totals</a>
<a class="btn btn-outline-dark" href="{{ URL::to("/api/antilobby/user/sessions/stats") }}" role="button">Stats</a>
</div>
@endauth
