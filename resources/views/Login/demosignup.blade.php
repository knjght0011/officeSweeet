@include('header')
<body style="background-image:url('/images/loginbackground2.png');background-size:cover; background-position: center 0%;">
@desktop
<div class="container">
    <div class="row">
        <div class="Absolute-Center is-Responsive" style="max-width: 50%;">
            <div id="logo-container"></div>
            <div id="login-companyname">Live Demo Signup</div>

            <div class="col-sm-12 col-md-10 col-md-offset-1">
                @if(session('done'))
                    <div class="col-md-12 well" style="text-align: center;">
                        <p>Thank you, Please check your E-Mail for your login info.</p>
                    </div>


                    <p>sptRecordConversion(75809);</p>
                    <!-- Google Code for Opt in to Live Demo Conversion Page -->
                    <script type="text/javascript">
                        /* <![CDATA[ */
                        var google_conversion_id = 809562352;
                        var google_conversion_label = "X9yGCJPxw4IBEPDhg4ID";
                        var google_remarketing_only = false;
                        /* ]]> */
                    </script>
                    <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
                    </script>
                    <noscript>
                        <div style="display:inline;">
                            <img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/809562352/?label=X9yGCJPxw4IBEPDhg4ID&amp;guid=ON&amp;script=0"/>
                        </div>
                    </noscript>
                @else
                    <form method="POST" action="{{ url('/DemoSignup') }}" accept-charset="UTF-8" id="loginForm">
                        {{ csrf_field() }}
                        <div class="input-group ">
                            <span class="input-group-addon" for="firstname"><div style="width: 15em;">First Name:</div></span>
                            <input id="firstname" name="firstname" type="text" value="" class="form-control"
                                   required="">
                        </div>
                        <div class="input-group ">
                            <span class="input-group-addon" for="lastname"><div
                                        style="width: 15em;">Last Name:</div></span>
                            <input id="lastname" name="lastname" type="text" value="" class="form-control" required="">
                        </div>
                        <div class="input-group ">
                            <span class="input-group-addon" for="email"><div style="width: 15em;">E-Mail Address:</div></span>
                            <input id="email" name="email" type="text" value="" class="form-control" required="">
                        </div>
                        <div class="form-group">
                            <input class="btn btn-primary btn-block active" type="submit" value="Send Access Link">
                        </div>

                        <div class="col-md-12 well" style="text-align: center;">
                            <p>When you click the button above, we will email you an access link to OfficeSweeet. When
                                you access
                                the Live Demo, you will be presented with some brief instructions to get you
                                started.</p>
                        </div>
                    </form>
                @endif
            </div>
            <!-- if there are login errors, show them here -->
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger">
                    {{ $error }}
                </div>
            @endforeach
        </div>
    </div>
</div>
</div>
@elsedesktop
<div id="logo-container"></div>
<div id="login-companyname">Live Demo Signup</div>

<div class="col-sm-12 col-md-10 col-md-offset-1">
    @if(session('done'))
        <div class="col-md-12 well" style="text-align: center;">
            <p>Thank you, Please check your E-Mail for your login info.</p>
        </div>
    @else
        <form method="POST" action="{{ url('/DemoSignup') }}" accept-charset="UTF-8" id="loginForm">
            {{ csrf_field() }}
            <div class="input-group ">
                <span class="input-group-addon" for="firstname"><div style="width: 15em;">First Name:</div></span>
                <input id="firstname" name="firstname" type="text" value="" class="form-control"
                       required="">
            </div>
            <div class="input-group ">
                            <span class="input-group-addon" for="lastname"><div
                                        style="width: 15em;">Last Name:</div></span>
                <input id="lastname" name="lastname" type="text" value="" class="form-control" required="">
            </div>
            <div class="input-group ">
                <span class="input-group-addon" for="email"><div style="width: 15em;">E-Mail Address:</div></span>
                <input id="email" name="email" type="text" value="" class="form-control" required="">
            </div>
            <div class="form-group">
                <input class="btn btn-primary btn-block active" type="submit" value="Send Access Link">
            </div>

            <div class="col-md-12 well" style="text-align: center;">
                <p>When you click the button above, we will email you an access link to OfficeSweeet. When
                    you access
                    the Live Demo, you will be presented with some brief instructions to get you
                    started.</p>
            </div>
        </form>
    @endif
</div>
<!-- if there are login errors, show them here -->
@foreach ($errors->all() as $error)
    <div class="alert alert-danger">
        {{ $error }}
    </div>
@endforeach
@enddesktop
@include('footer')