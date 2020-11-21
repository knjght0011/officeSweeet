@extends('Emails.Templates.customer')

@section('content')

<p>{!! $body !!}</p>

{{--@if($token === null)--}}
{{--<p>See attached</p>--}}
{{--@else--}}
{{--<p>To view your file online click <a href="{{ url('/Public/Email/' . $token) }}">HERE</a></p>--}}
{{--@endif--}}
@stop