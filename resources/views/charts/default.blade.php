    @auth
        <!-- Chart generated with Auth -->
    @endauth

    <!-- Chart's container dd-->
<div id="chart{{ $uniqueID ?? ''}}" style="height: 250px;"></div>

    @php
      if(!empty($bladePassUrl)) {
        $jsonURL = $bladePassUrl;
      } else {
        $jsonURL = '';
      }
    @endphp

    <script>
        var item{{ $uniqueID ?? ''}} = "{!! $jsonURL ?? '' !!}";
        var chart{{ $uniqueID ?? ''}} = new Chartisan({
          el: '#chart' + '{{ $uniqueID ?? '' }}',
          url: ''+item{{ $uniqueID ?? ''}},
          hooks: new ChartisanHooks()
              .datasets('{{ $chartType ?? 'bar' }}')
              .title("{{ $chartTitle ?? '' }}")
              .legend({{ $legend ?? 'true' }})
              .responsive()
              .colors(['{{ $colors ?? 'green', 'blue', 'red'}}'])
              ,
        })

    </script>
