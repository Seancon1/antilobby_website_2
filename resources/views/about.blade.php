@extends('layout.app')

 @section('content')
<div class="row">
        <div class="col-lg-12"> 
            <h1>About</h1>
        </div>
        <div class="col-lg-12"> 
        <p>Antilobby was made after I was curious about how much time I spent on the computer. I wanted a new perspective on my time--either well spent or not--and needed some way
        to track it.
        What better to make than a program that does that for me? </p>
        </div>
        <div class="col-lg-6 col-md-8 col-sm-12">    
            <img src="https://i.gyazo.com/c5098dc18cece75850c1bd0572ad6dc4.png" class="img-fluid" alt="Image from Gyazo" width="500px" />
        </div>
        <div class="col-lg-6 col-md-8 col-sm-12">
        <figure class="wp-block-video aligncenter"><video width="500px" class="img-fluid"autoplay loop muted src="https://i.gyazo.com/2ee718167ea95b47d58511c1df4f5c6f.mp4"></video></figure>
        </div>
</div>

<div class="row" style="margin-top: 2rem;">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
              <h5 class="card-title">Download</h5>
              <p class="card-text">The application is available to download below. By downloading this application, you understand it is in early stages of development and may not provide enhanced security measures for your data and may have features that do not work.</p>
              <a href="/download" class="btn btn-success">Download Page</a>
            </div>
          </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
              <h5 class="card-title">Github</h5>
              <p class="card-text">Take a look at the project on Github! Do you want to contribute? Send a pull request.</p>
              <a href="https://github.com/Seancon1/antilobby_app" class="btn btn-outline-dark">Application</a>
              <a href="https://github.com/Seancon1/antilobby_website_2" class="btn btn-outline-dark">Website</a>
            </div>
          </div>
    </div>
</div>


@endsection

