@extends('Emails.Templates.customer')

@section('content')
<p>Greetings!</p>

<p>You have an appointment with us, {{ \App\Helpers\OS\SettingHelper::GetSetting('companyname') }}, on {{ $event->start->setTimezone(SettingHelper::GetTimeZoneInfo()['gmtAdjustment'])->toDayDateTimeString() }}.</p>

<p>We are looking forward to it.</p>

@if($phonenumber != "")
<p>If you need to change or cancel your appointment, please call {{ $phonenumber }} today.</p>
@endif

<p>Regards,<br>{{ \App\Helpers\OS\SettingHelper::GetSetting('companyname') }}</p>
@stop