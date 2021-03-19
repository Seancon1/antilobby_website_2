@extends('layout.app')

 @section('content')
<div class="row">
        <div class="col-lg-12"> 
            <h1>Downloads</h1>
        </div>
        <div class="col-lg-6"> 
          <p>Antilobby download page will feature the most recent releases along with beta and previous versions. This page is still being put together, so check back later to see it in action.</p>
        </div>        
        <div class="col-lg-6"> 
          @foreach($appVersions as $version)
            <div class="card text-center">
              <div class="card-header">
                Latest Release (v{{ $version->version }})
              </div>
              <div class="card-body">
                <h5 class="card-title">{{$version->alias}}</h5>
                <p class="card-text">{{$version->notes}}</p>
                <a href="#{{ $version->download_path }}" class="btn btn-success">Download</a>
              </div>
              <div class="card-footer text-muted">
                {{ "0" }} days ago
              </div>
            </div>
          @endforeach
        </div>
</div>

@endsection

