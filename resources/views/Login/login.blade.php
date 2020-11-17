@include('header')
<body style="background-image:url('/images/loginbackground2.png');background-size:cover; background-position: center 0%;")>
@desktop
<div class="container"> 
    <div class="row">
        <div class="Absolute-Center is-Responsive">
            <div style="margin: auto; margin-bottom: 10px; width: 300px; height: 200px;"><img width="100%" src="{{ TextHelper::GetLogo() }}"></div>
            <div id="login-companyname">{{ $companyname }}</div>

            <div class="col-sm-12 col-md-10 col-md-offset-1">
                <form method="POST" action="{{ url('/login') }}" accept-charset="UTF-8" id="loginForm">
                    {{ csrf_field() }}
                    <div class="form-group input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input class="form-control" placeholder="E-Mail" name="email" type="text">
                    </div>
                    <div class="form-group input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input class="form-control" placeholder="Password" name="password" type="password" value="">
                    </div>
                    <input id="timezone" style="display: none;" name="timezone" type="text" value="" >
                    <input id="uri" style="display: none;" name="uri" type="text" value="" >
                    <div class="form-group">
                            <input class="btn btn-primary btn-block active" type="submit" value="Login">
                    </div>
                    <a style="width:49%; float: left;" class="btn btn-primary btn-block active" id="bookmark-this" href="#" title="Bookmark This Page">Bookmark This Page</a>
                    <a style="width:49%; float: right; margin-top: 0px;" id="forgotpassword" class="btn btn-primary btn-block active" value="Forgot Password">Forgot Password</a>

                </form>
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
<div id="login-companyname">{{ $companyname }}</div>
<div class="col-sm-12 col-md-10 col-md-offset-1">
    <form method="POST" action="{{ url('/login') }}" accept-charset="UTF-8" id="loginForm">
        {{ csrf_field() }}
        <div class="form-group input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
            <input class="form-control" placeholder="E-Mail" name="email" type="text">
        </div>
        <div class="form-group input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
            <input class="form-control" placeholder="Password" name="password" type="password" value="">
        </div>
        <input id="timezone" style="display: none;" name="timezone" type="text" value="" >
        <input id="uri" style="display: none;" name="uri" type="text" value="" >
        <div class="form-group">
            <input class="btn btn-primary btn-block active" type="submit" value="Login"></div>
        <a style="width:49%; float: left;" class="btn btn-primary btn-block active" id="bookmark-this" href="#" title="Bookmark This Page">Bookmark This Page</a>
        <a style="width:49%; float: right; margin-top: 0px;" id="forgotpassword" class="btn btn-primary btn-block active" value="Forgot Password">Forgot Password</a></div>
</div>
</form>

<!-- if there are login errors, show them here -->
    @foreach ($errors->all() as $error)
    <div class="alert alert-danger">
        {{ $error }}
    </div>
    @endforeach
    </div>

@enddesktop
<script>

    $('#uri').val("{{ $uri }}");


    date = new Date();
    $offset = date.getTimezoneOffset();
    $('#timezone').val($offset);
    
    $('#forgotpassword').click(function(e) {
        var link = document.createElement('a');
        link.href = "/resetrequest";
        link.id = "link";
        document.body.appendChild(link);
        link.click();
    });

  $('#bookmark-this').click(function(e) {
	pageTitle=document.title;
	pageURL=document.location;
	try {
		// Internet Explorer solution
		eval("window.external.AddFa-vorite(pageURL, pageTitle)".replace(/-/g,''));
	}
	catch (e) {
		try {
			// Mozilla Firefox solution
			window.sidebar.addPanel(pageTitle, pageURL, "");
		}
		catch (e) {
			// Opera solution
			if (typeof(opera)=="object") {
				a.rel="sidebar";
				a.title=pageTitle;
				a.url=pageURL;
				return true;
			} else {
				// The rest browsers (i.e Chrome, Safari)
				alert('Press ' + (navigator.userAgent.toLowerCase().indexOf('mac') != -1 ? 'Cmd' : 'Ctrl') + '+D to bookmark this page.');
			}
		}
	}
	return false;
  });

</script>

@include('footer')