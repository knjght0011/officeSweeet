@include('header')
<body style="background-image:url('/images/loginbackground2.png');background-size:cover; background-position: center 0%;")>

@desktop
<div class="container"> 
  <div class="row">
    <div class="Absolute-Center is-Responsive">
        <div id="logo-container"></div>
        <div id="login-companyname">Create Your NEW Password Below.</div>
        <div class="col-sm-12 col-md-10 col-md-offset-1">
            <form method="POST" action="{{ url('/reset') }}" accept-charset="UTF-8" id="loginForm">
                {{ csrf_field() }}
                <div class="form-group input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>  
                    <input class="form-control" placeholder="New Password" name="password" type="password">
                </div>
                <div class="form-group input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>  
                    <input class="form-control" placeholder="Confirm New Password" name="password-repeat" type="password">
                </div>

                <input style="display: none;" class="form-control" name="token" type="text" value="{{ $token }}">
                
                <div class="form-group">
                    <input class="btn btn-primary btn-block active" type="submit" value="Reset"></div>
                </div>
             </form>    
            
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
<div id="login-companyname">Create Your NEW Password Below.</div>
<div class="col-sm-12 col-md-10 col-md-offset-1">
    <form method="POST" action="{{ url('/reset') }}" accept-charset="UTF-8" id="loginForm">
        {{ csrf_field() }}
        <div class="form-group input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
            <input class="form-control" placeholder="New Password" name="password" type="password">
        </div>
        <div class="form-group input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
            <input class="form-control" placeholder="Confirm New Password" name="password-repeat" type="password">
        </div>

        <input style="display: none;" class="form-control" name="token" type="text" value="{{ $token }}">

        <div class="form-group">
            <input class="btn btn-primary btn-block active" type="submit" value="Reset"></div>
</div>
</form>

@foreach ($errors->all() as $error)
    <div class="alert alert-danger">
        {{ $error }}
    </div>
    @endforeach
    </div>
@enddesktop
@include('footer')