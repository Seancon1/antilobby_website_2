
<h2>Antilobby Resource Hub</h2>
<div class="alert alert-warning"><p>In order to help protect your data, you now need to make an account to use the features of Antilobby. More features are coming that will allow you to modify your session privacy.</p></div>


<hr>
<a class="btn btn-outline-dark" href="{{ URL::to("/api/antilobby/public/sessions/") }}" role="button">Public Sessions</a>
<a class="btn btn-outline-dark" href="{{ URL::to("/api/antilobby/public/sessions/stats") }}" role="button">Public Stats</a>

@auth
<a class="btn btn-outline-dark" href="{{ URL::to("/api/antilobby/user/sessions/") }}" role="button">All Sessions</a>
<a class="btn btn-outline-dark" href="{{ URL::to("/api/antilobby/user/program/totals") }}" role="button">Program Totals</a>
<a class="btn btn-outline-dark" href="{{ URL::to("/api/antilobby/user/sessions/stats") }}" role="button">Stats</a>
@endauth
