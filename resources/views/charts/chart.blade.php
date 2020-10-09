

    <!-- Chart's container dd-->
    <div id="chart" style="height: 300px;"></div>
     <!-- Charting library -->
     <script src="https://unpkg.com/chart.js@2.9.3/dist/Chart.min.js"></script>
     <!-- Chartisan -->
     <script src="https://unpkg.com/@chartisan/chartjs@^2.1.0/dist/chartisan_chartjs.umd.js"></script>

    <!-- Your application script -->
    <script>

        var item = "{{ URL::to("/api/antilobby/chart/json/1") }}";
        //var item ="{{ URL::to("/api/antilobby/charts/json/stats_top_10_processes") }}";
      const chart = new Chartisan({
        el: '#chart',
        url: ''+item,
        hooks: new ChartisanHooks()
            .datasets('bar')
            .title("Session Overview (Last 20)")
            .legend(true)
            .responsive(),
      })

    </script>
