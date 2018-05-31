@extends('layouts.basic')

@section('content')

<div class="col-md-8 col-centered text-center">
<h1>404 Error</h1>	

<h2>Sorry! The page you are looking for is not found.</h2>

<a href="{{ URL::to('/') }}" class="btn btn-lg"><i class="icon-home"></i> Go back to home page</a>

</div>

@stop

