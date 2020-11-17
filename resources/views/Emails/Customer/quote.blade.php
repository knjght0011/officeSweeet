@extends('Emails.Templates.customer')

@section('content')

<p>{{ $body }}</p>

<p>See attached {{ $type }}</p>

@if($token != null)
<p>To view your {{ $type }} online and make your payment click <a href="{{ url('/Invoice/' . $token) }}">HERE</a></p>
@endif

@stop