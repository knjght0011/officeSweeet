@include('SignUp.header')
<body style="background-image: url('{{ url('/images/signupbackground2.jpg') }}');
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-size: 105% ;">

<div id="main-container" class="container" style="background: white; margin-top: 10px; border-radius: 20px; padding: 25px;">
    <div class="row">
        <div class="col-md-4 col-md-offset-4"><img width="100%" src="/images/oslogo.png"></div>
    </div>

    <h3 style="text-align: center;     margin-top: 0px;">
        Thank you for verifying your email address.
    </h3>
    <h3 style="text-align: center;     margin-top: 0px;">
        Your OfficeSweeet System is being provisioned, this typically only takes a few minutes.
    </h3>
    <h3 style="text-align: center;     margin-top: 0px;">
        Please wait until the progress bar reaches 100%
    </h3>

    <div class="progress progress-striped active">
        <div id="progress-bar" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="6" style="width: 1%;">
            1%
        </div>
    </div>

    <p style="font-weight: bold;">We want to let you know what is happening while you wait a few minutes for your system to be built.</p>

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

    <p style="font-weight: bold;">
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
    <p style="font-weight: bold;">
        Any problems, please contact us directly at 813-444-5284 or email support@officesweeet.com
    </p>
    <p style="font-weight: bold;">
        Once again, thank you for your business.
    </p>

</div>

<div id="done-message" class="container" style="background: white; margin-top: 10px; border-radius: 20px; padding: 25px; display:none;">
    <div class="row">
        <div class="col-md-4 col-md-offset-4"><img width="100%" src="/images/oslogo.png"></div>
    </div>

    <h3 style="text-align: center;">Congratulations your new system has been provisioned! <h3>

    <h3 style="text-align: center;">The login screen for your new system is <a target="_blank" href="http://{{ $subdomain }}.officesweeet.com"><b>HERE</b></a>.<h3>

    <h3 style="text-align: center;">Please check your email for your unique login and password.<h3>

</div>


<script>
    $(document).ready(function () {

        timerId = setInterval(function () {
            $.get("/ProvisioningCheck/{{ $subdomain }}", function (data) {
                console.log(data);

                if(data === "6"){
                    $('#progress-bar').html('100%');
                    $('#progress-bar').css('width', "100%");

                    $( "#main-container" ).fadeOut( "slow", function() {
                        $('#done-message').fadeIn();
                    });

                    clearInterval(timerId);
                }else{
                    $percentage = 16 * data;
                    $('#progress-bar').html($percentage + "%");
                    $('#progress-bar').css('width', $percentage + "%");
                }

            });
        },2000);


    });

</script>

<!--Start of Tawk.to Script-->
<script type="text/javascript">
    var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
    (function () {
        var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
        s1.async = true;
        s1.src = 'https://embed.tawk.to/599308111b1bed47ceb04bd3/default';
        s1.charset = 'UTF-8';
        s1.setAttribute('crossorigin', '*');
        s0.parentNode.insertBefore(s1, s0);
    })();
</script>
<!--End of Tawk.to Script-->

@include('SignUp.footer')
