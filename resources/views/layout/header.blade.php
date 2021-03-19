<!doctype html>

<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Antilobby</title>
    <link rel="stylesheet" href="https://www.prestigecode.com/projects/antilobby/resources/mobile.css">
    <!-- Chartisan -->
    <script src="https://unpkg.com/chart.js@2.9.3/dist/Chart.min.js"></script>
    <script src="https://unpkg.com/@chartisan/chartjs@^2.1.0/dist/chartisan_chartjs.umd.js"></script>
    @livewireStyles

    
  </head>
  <body style='margin-left: 10px; margin-right: 10px; margin-bottom: 50px;'>


  <div class="container">
    <p></p>
    <p>
      <!--
        <button class="btn btn-warning" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
            <span class="oi oi-bell">Known Issues</span>
        </button>

      -->
      </p>
      <div class="collapse" id="collapseExample">
        <div class="card card-body">
            <h3>Known Issues</h3>
            <ul>
                <li><p><b>Password Reset & Email Links</b>: Password reset email links are not correctly generated and do not work.</p></li>
            </ul>
        </div>
      </div>

  @livewire('user-area')
