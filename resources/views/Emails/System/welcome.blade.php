@extends('Emails.Templates.system')

@section('content')
<p>Hello {{ $account->InstallInfo('firstname') }} {{ $account->InstallInfo('lastname') }},</p>

<p>Below you have your log in credentials and temporary password to your new OfficeSweeet database. Click on the link to your Business URL below (Or copy and paste it into your web browser), enter your username (email) and copy and paste your temporary password to log in. You'll be prompted to change your password and complete your setup.</p>

<table class="table">
    <thead>
        <tr>
            <td>Username:</td><td>{{ $account->InstallInfo('email') }}</td>
        </tr>
        <tr>
            <td>Password:</td><td>{{ $account->InstallInfo('UserPassword') }}</td>
        </tr>
        <tr>
            <td>Your Business URL:</td><td> <a style="background: #ef5952;border: 1px solid #d44943;font-size: 14px;color: #fff;border-radius: 3px;text-transform: uppercase;padding: 8px 11px;display: inline-block;" href="{{ $account->subdomain }}.officesweeet.com">{{ $account->subdomain }}.officesweeet.com</a></td>
        </tr>
    </thead>
</table>

<p>
    We hope that you are thrilled with OfficeSweeet.
</p>
<p>
    If you wish to continue or change your subscription, you can login and <a style="background: green;border: 1px solid green;font-size: 14px;color: #fff;border-radius: 3px;text-transform: uppercase;padding: 8px 11px;display: inline-block;" href="{{ $account->subdomain }}.officesweeet.com/Subscription">Manage Your Subscription here</a>.
</p>
<p>
    Your business is greatly appreciated and we can be reached directly by responding to this email. Thank You!
</p>
@stop