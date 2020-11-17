@extends('Emails.Templates.system')

@section('content')
<p>Hello {{ $account->InstallInfo('firstname') }} {{ $account->InstallInfo('lastname') }},</p>

<p>We just received your request.</p>

@if($account->token != null)

    <p>Welcome to Office Sweeet and thank you for registering your account! To verify your email address and activate your account, please click the activation link below or copy and paste the address into your browser:</p>

    <a style="width: 100%;" href="http://api.officesweeet.com/Verify/{{ $account->token }}">http://api.officesweeet.com/Verify/{{ $account->token }}</a>

@else

    <p>We want to let you know what is happening while you wait a few minutes for your login instructions to your new system.</p>

    <p>
    <ol type="1">
        <li>We are creating a separate database & subdomain just for you.</li>
        <li>Once this is complete, we (here at OfficeSweeet) will receive notification that your system is ready to login with your unique password.</li>
        <li>We are sending that information to this email address so that you have<br>
            <ol type="A">
                <li>Link to your business system</li>
                <li>A user name (your email address)</li>
                <li>A complex password that you can change upon logging in</li>
            </ol>
        </li>
    </ol>
    </p>

    <p>
        In a few moments, when you receive an email with your login information, simply click on the link provided (big red button), copy and paste the password WITHOUT spaces and bookmark your page (click the button under the login button) to know how to find your business login next time.
    </p>
    <p>
        Once you login, you will be prompted to:
    <ol type="1">
        <li>Change your password.</li>
        <li>Verify how you want your business to appear.</li>
        <li>Add details about your business address.</li>
    </ol>
    </p>
    <p>
        Once you have this completed, your system will be configured and you can watch a few videos on getting started.
    </p>
    <p>
        The link for the videos can be found in Support/Feedback on the lower left corner.
    </p>
    <p>
        Any problems, please contact us directly at 813-444-5284 or email support@officesweeet.com
    </p>
    <p>
        Once again, thank you for your business.
    </p>
@endif

    <p>
        Your OfficeSweeet Support Team
    </p>

@stop