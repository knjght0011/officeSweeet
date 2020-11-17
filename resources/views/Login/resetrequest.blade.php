@include('header')
<body style="background-image:url('/images/loginbackground2.png');background-size:cover; background-position: center 0%;")>
@desktop
<div class="container"> 
  <div class="row">
    <div class="Absolute-Center is-Responsive">
        <div id="logo-container"></div>
        <div id="login-companyname">Please enter the email address associated with your account.</div>
        <div class="col-sm-12 col-md-10 col-md-offset-1">
            <form method="POST" action="{{ url('/resetrequest') }}" accept-charset="UTF-8" id="loginForm">
                {{ csrf_field() }}
                <div class="form-group input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>  
                    <input class="form-control" placeholder="E-Mail" name="email" type="text">
                </div>
                <div class="form-group">
                    <input class="btn btn-primary btn-block active" type="submit" value="Reset"></div>
                </div>
             </form>    
      </div>  
    </div>    
  </div>
</div>
@elsedesktop
<div id="logo-container"></div>
<div id="login-companyname">Please enter the email address associated with your account.</div>
<div class="col-sm-12 col-md-10 col-md-offset-1">
    <form method="POST" action="{{ url('/resetrequest') }}" accept-charset="UTF-8" id="loginForm">
        {{ csrf_field() }}
        <div class="form-group input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
            <input class="form-control" placeholder="E-Mail" name="email" type="text">
        </div>
        <div class="form-group">
            <input class="btn btn-primary btn-block active" type="submit" value="Reset"></div>
</div>
</form>
</div>
@enddesktop
@include('footer')