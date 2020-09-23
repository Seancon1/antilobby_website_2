
    <!-- Chart's container dd-->
    <div id="chart" style="height: 300px;"></div>

    <script>
    
        var item = "{{ URL::to("/api/antilobby/chart/json/overview/hourly") }}";
      const chart = new Chartisan({
        el: '#chart',
        url: ''+item,
        hooks: new ChartisanHooks()
            .datasets('bar')
            .title("Session Overview Hours (Last 10)")
            .legend(true)
            .responsive(),
      })
    </script>
