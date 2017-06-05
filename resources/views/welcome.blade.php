
@extends('frontLayout.app')
@section('title')
Home Page
@stop

@section('style')

@stop
@section('content')
<div class="content">
<div class="title m-b-md">
    Laravel  Qr Code Starter page
    
</div>
@if (Sentinel::check() )
     Your name : {{Sentinel::getUser()->first_name}} <br>
     Last name : {{Sentinel::getUser()->last_name}} <br>
     E-mail : {{Sentinel::getUser()->email}} <br>
    @endif

<div class="links">
    <a href="https://github.com/roladn">GitHub</a>
    <a href="https://rolandalla.com/">My Website</a>
    <a href="https://www.facebook.com/rolandalla91">Facebook</a>
    <a href="https://www.youtube.com/channel/UCgW6jORopjpon_42vzi7YkQ">Youtube</a>
</div>
</div>
@endsection

@section('scripts')


@endsection
