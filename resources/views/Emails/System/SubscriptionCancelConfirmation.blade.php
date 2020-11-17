@extends('Emails.Templates.system')

@section('content')
<p>Hello {{ $account->name }},</p>

<p>Below you have your log in credentials and temporary password to your new OfficeSweeet database. Click on the link to your Business URL below, enter your username (email) and copy/paste your temporary password to log in. There will be a message waiting for you once you are logged in.</p>



@stop