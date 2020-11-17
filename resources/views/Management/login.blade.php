@include('header')
<!---
<body style="background-image:url('/images/loginbackground2.png');background-size:cover;")>
-->
<body>

<div class="container"> 
  <div class="row">
    <div class="Absolute-Center is-Responsive">
        <div class="col-sm-12 col-md-10 col-md-offset-1">
            <form method="POST" action="/login" accept-charset="UTF-8" id="loginForm">
                    <input name="_token" type="hidden" value="{{ csrf_token() }}">
                    <div class="form-group input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>  
                            <input class="form-control" placeholder="E-Mail" name="email" type="text">
                    </div>
                    <div class="form-group input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input class="form-control" placeholder="Password" name="password" type="password" value="">
                    </div>
                    <input id="timezone" style="display: none;" name="timezone" type="text" value="" >
                    <div class="form-group">
                            <input class="btn btn-primary btn-block active" type="submit" value="Login"></div>
                    </div>
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

<script>
    date = new Date();
    $offset = date.getTimezoneOffset();
    $('#timezone').val($offset);
</script>
@include('footer')