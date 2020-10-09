
    @php
     if($json == true) {
     header('Content-Type: application/json');
     die(json_encode($chartData));
     }
    @endphp
