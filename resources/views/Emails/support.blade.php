@extends('Emails.master')

@section('content')
System subdomain: {{ $subdomain }}<br>
Support email from: {{ $email }}<br>
<br>
Message:<br>
{{ $body }}


@stop