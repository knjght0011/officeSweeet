@extends('Emails.Templates.customer')

@section('content')
<p>Hi {{ $user->firstname }} {{ $user->lastname }},</p>

<p>I added you as a team member (employee) in our OfficeSweeet system. Office Sweeet is an Enterprise Business Management solution to enhance teamwork and productivity.</p>

<p>Feel free to use the tutorials and communicate whatever you like to me in the message center.</p>

<table class="table">
    <thead>
        <tr>
            <td>Go here to login :</td><td> <a style="background: #ef5952;border: 1px solid #d44943;font-size: 14px;color: #fff;border-radius: 3px;text-transform: uppercase;padding: 8px 11px;display: inline-block;" href="{{ $subdomain }}.officesweeet.com">{{ $subdomain }}.officesweeet.com</a></td>
        </tr>
        <tr>
            <td>Username:</td><td>{{ $user->email }}</td>
        </tr>
        <tr>
            <td>Password:</td><td>{{ $password }}</td>
        </tr>
    </thead>
</table>

<p>
    When you login for the first time you will be prompted to change your password to something you will remember.
</p>
<p>
    Call me if you have any problems.
</p>
<p>
    {{ $sender }}
</p>
@stop