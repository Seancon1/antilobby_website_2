
<h2>Antilobby Resource Hub</h2>
<div class="alert alert-warning"><p>In the coming weeks, in order to help protect your data, you will need to make an account to use the features of Antilobby.</p></div>


<hr>
<a class="btn btn-outline-dark" href="{{ URL::to("/api/antilobby/sessions/") }}" role="button">Public Sessions</a>
<a class="btn btn-outline-dark" href="{{ URL::to("/api/antilobby/sessions/stats") }}" role="button">Public Stats</a>

@auth
<a class="btn btn-outline-dark" href="{{ URL::to("/api/antilobby/user/sessions/") }}" role="button">All Sessions</a>
<a class="btn btn-outline-dark" href="{{ URL::to("/api/antilobby/user/program/totals") }}" role="button">Program Totals</a>
@endauth
