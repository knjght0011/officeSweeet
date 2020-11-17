@extends('Emails.Templates.system')

@section('content')
<p>Hello {{ $user->name }},</p>

<p>No judging, but you're getting this email because somebody (hopefully you) went on {{ $subdomain }}.officesweeet.com and requested a password reset. If you actually do need a reset, hit that big "Reset Password" button down there.</p>

<p>If it wasn't you after all, just forget it. Doing nothing is fine. </p>

<a id="greenbutton" href="https://{{ $subdomain }}.officesweeet.com/reset/{{ $reset->token }}" >Reset Password</a>

<p>This Link is valid for the next 30 minutes only, after this time you can request a new link <a href="https://{{ $subdomain }}.officesweeet.com/resetrequest/" >here</a></p>

@stop