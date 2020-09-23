
    <!-- Chart's container dd-->
    <div id="chart" style="height: 300px;"></div>
  
    <!-- Your application script -->
    <script>

        var item = "{{ URL::to("/api/antilobby/chart/json/1") }}";
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
