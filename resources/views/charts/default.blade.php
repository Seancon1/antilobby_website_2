
    <!-- Chart's container dd-->
<div id="chart{{ $uniqueID ?? ''}}" style="height: 250px;"></div>
        <!-- Charting library -->
        <script src="https://unpkg.com/chart.js@2.9.3/dist/Chart.min.js"></script>
        <!-- Chartisan -->
        <script src="https://unpkg.com/@chartisan/chartjs@^2.1.0/dist/chartisan_chartjs.umd.js"></script>
        
    @php
      if(!empty($bladePassUrl)) {
        $jsonURL = $bladePassUrl;
      } else {
        $jsonURL = '';
      }
    @endphp
    
    <script>

        var item{{ $uniqueID ?? ''}} = "{{ $jsonURL ?? ''}}";
        var chart{{ $uniqueID ?? ''}} = new Chartisan({
          el: '#chart' + '{{ $uniqueID ?? '' }}',
          url: ''+item{{ $uniqueID ?? ''}},
          hooks: new ChartisanHooks()
              .datasets('{{ $chartType ?? 'bar' }}')
              .title("{{ $chartTitle ?? '' }}")
              .legend({{ $legend ?? 'true' }})
              .responsive()
              .colors()
              ,
        })

    </script>
