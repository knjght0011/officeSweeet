@extends('Emails.Templates.system')

@section('content')
<p>Hello {{ $user->firstname }} {{ $user->lastname }},</p>

<p>Here is your link to access the Office Sweeet live demo.</p>

<a id="greenbutton" href="https://livedemo.officesweeet.com/login/{{ $user->password }}" >Access Live Demo</a>

<p>If that button doesn't work simply copy and paste the following address into your browser:</p><p>https://livedemo.officesweeet.com/login/{{ $user->password }}</p>

@stop