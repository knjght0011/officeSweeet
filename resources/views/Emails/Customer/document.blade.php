@extends('Emails.Templates.customer')

@section('content')

<p>{{ $body }}</p>

<p>See attached Document</p>

@if($signing->token != null)
<p>To view your file online click <a href="{{ url('/Public/Document/' . $signing->token) }}">HERE</a></p>
@endif

@stop