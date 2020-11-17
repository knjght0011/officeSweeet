@extends('Emails.Templates.customer')

@section('content')

<p>{{ \App\Helpers\OS\SettingHelper::GetSetting('companyname') }} has requested your signature</p>

<p>To view the document online click <a href="{{ url('/Public/Document/Signing/' . $signing->token . '/' . $contact->token) }}">HERE</a></p>

@stop