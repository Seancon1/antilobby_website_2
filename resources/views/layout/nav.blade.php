
<h2>Antilobby Resource Hub</h2>
<p>Please paste your Session ID or Mac Address from the application to fetch your information.</p>
<form <?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST">
     <div class="form-group">
    <label for="macAddressDesc">Lookup ID:</label>
    <input type="mac" class="form-control" name='inMac' id="inMac" placeholder="Identifier (IP ONLY RIGHT NOW)" required>
    <small id="emailHelp" class="form-text text-muted">This will be used to locate your data.</small>
  </div>
    <button type="submit" class="btn btn-primary" disabled>Add to lookup</button>
    </form>

<hr>
<a class="btn btn-outline-dark" href="{{ URL::to("/api/antilobby/sessions/") }}" role="button">Show All Sessions</a>
@auth
<a class="btn btn-outline-dark" href="{{ URL::to("/api/antilobby/program/totals") }}" role="button">Show Programs Totals</a>
@endauth
<a class="btn btn-outline-dark" href="{{ URL::to("/api/antilobby/sessions/stats") }}" role="button">Show My Stats</a>
