
    <!-- Chart's container dd-->
    <div id="chart" style="height: 250px;"></div>
        <!-- Charting library -->
        <script src="https://unpkg.com/chart.js@2.9.3/dist/Chart.min.js"></script>
        <!-- Chartisan -->
        <script src="https://unpkg.com/@chartisan/chartjs@^2.1.0/dist/chartisan_chartjs.umd.js"></script>

    @php
      if(!empty($bladePassUrl)) {
        $jsonURL = $bladePassUrl . $urlExtension;
        $returnedTitle = "";
      } else {
        $jsonURL = '';
      }
    @endphp

  {{ $jsonURL ?? '' }}
  {{ $urlExtension ?? '' }}
  {{ $returnedTitle ?? '' }}

    <script>

        var item = "https://prestigecode.com/api/antilobby/charts/json/1";
        var chart = new Chartisan({
          el: '#chart',
          url: ''+item,
          hooks: new ChartisanHooks()
              .datasets('bar')
              .title("{{ $returnedTitle }}")
              .legend(true)
              .responsive(),
        })
        
    </script>
