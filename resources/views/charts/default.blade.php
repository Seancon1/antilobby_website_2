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

  <!--
    @php
    //format $colors to work correctly must output 'red', 'blue' instead of current 'red, blue'
    if(!empty($colors)) {
      $newColorText = "";
      $first = true;
      //echo "Received List: " . $colors;
      $colorList = explode(",", trim($colors));
      //var_dump($colorList);

      foreach($colorList as $color) {
        if($first) {
          $newColorText .= "'".$color."'";
        } else {
          $newColorText .= ",'".$color."'";
        }
      }
      //$colors = $newColorText;
    }
    @endphp
  -->

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
              @if(!empty($colors))
                .colors([
                  @foreach ($colorList as $color)
                    '{{ $color }}' {{ "," }}
                  @endforeach
                ])
                @else
                .colors(['green', 'red', 'blue'])
              @endif
              ,
        })
    </script>

    