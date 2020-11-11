@extends('layout.app')

 @section('content')




    <div class="flex-center position-ref full-height">
        <div class="content">
        <h3>Settings</h3>
            @livewire('private-setting', ['private' => true])
        </div>
    </div>

@endsection

