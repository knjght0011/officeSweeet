@include('header')
<body style="background-image:url('/images/loginbackground2.png');background-size:cover; background-position: center 0%;")>
@desktop
<div class="container"> 
  <div class="row">
    <div class="Absolute-Center is-Responsive">
        <div id="logo-container"></div>            
        <div class="col-sm-12 col-md-10 col-md-offset-1">
            <p>
            <?php
            switch ($message) {
                case "Passwordresetsent":
                    echo "<p>Password reset sent, Please check your email for a link to reset your password.</p>";
                    break;
                case "passwordchanged":
                    echo "<p>You password has been changed. Please click <a href='/login'>Here</a> to Login.</p>";
                    break;
                case "toold":
                    echo "<p>You password recovery period has expired. Please click <a href='/resetrequest'>Here</a> to request another reset.</p>";
                    break;
                case "failedtofind":
                    echo "<p>No password reset found for this token, Please click <a href='/resetrequest'>Here</a> to request another reset.</p>";
                    break;
                default:
                       echo $message;                
            }
            ?>            
        </div>  
    </div>    
  </div>
</div>
@elsedesktop
<div id="logo-container"></div>
<div class="col-sm-12 col-md-10 col-md-offset-1">
    <p>
    <?php
    switch ($message) {
        case "Passwordresetsent":
            echo "<p>Password reset sent, Please check your email for a link to reset your password.</p>";
            break;
        case "passwordchanged":
            echo "<p>You password has been changed. Please click <a href='/login'>Here</a> to Login.</p>";
            break;
        case "toold":
            echo "<p>You password recovery period has expired. Please click <a href='/resetrequest'>Here</a> to request another reset.</p>";
            break;
        case "failedtofind":
            echo "<p>No password reset found for this token, Please click <a href='/resetrequest'>Here</a> to request another reset.</p>";
            break;
        default:
            echo $message;
    }
    ?>
</div>
@enddesktop
@include('footer')