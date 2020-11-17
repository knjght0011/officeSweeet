@extends('OS.Setup.master')

@section('content')

    <div class="container">

        <h3 style="text-align: center;">Your user account has been marked as needing a password change before you can continue.</h3>
        <h3 style="text-align: center;">Please change your password below.</h3>
        <h3  style="text-align: center;">Your password should be at least 8 letters long with at least one special character (!@#$%&).</h3>

        <div class="input-group" style="margin-bottom: 5px;">
            <span class="input-group-addon" for="changepassword"><div style="width: 15em;">Change Password:</div></span>
            <input id="password" name="password" type="password" class="form-control">
        </div>

        <div class="input-group">
            <span class="input-group-addon" for="confirmpassword"><div style="width: 15em;">Confirm Password:</div></span>
            <input id="confirmpassword" name="confirmpassword" type="password" class="form-control">
            <span class="input-group-btn">
                <button id="passwordsave" class="btn btn-default" type="button">Update</button>
            </span>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#passwordsave").click(function()
            {
                $("body").addClass("loading");
                $password = $('#password').val();
                if ($password.length >= 8)
                {
                    if ($('#password').val() === $('#confirmpassword').val())
                    {
                        post = PostPassword("{{ Auth::user()->id }}", $('#password').val(), $('#confirmpassword').val());

                        post.done(function( data )
                        {
                            switch(data['status']) {
                                case "OK":
                                    window.location.reload();
                                    break;
                                case "validation":
                                    $("body").removeClass("loading");
                                    ServerValidationErrors(data['errors']);
                                    break;
                                default:
                                    $("body").removeClass("loading");
                                    console.log(data);
                                    $.dialog({
                                        title: 'Oops...',
                                        content: 'Unknown Response from server. Please refresh the page and try again.'
                                    });
                            }
                        });

                        post.fail(function() {
                            $("body").removeClass("loading");
                            $.dialog({
                                title: 'Opps...',
                                content: 'Failed to post password details'
                            });
                        });
                    }else{
                        $("body").removeClass("loading");
                        $.dialog({
                            title: 'Opps...',
                            content: 'Passwords dont match'
                        });
                    }
                }else{
                    $("body").removeClass("loading");
                    $.dialog({
                        title: 'Opps...',
                        content: 'Password needs to be atleast 8 characters long'
                    });
                }
            });
        });

        function PostPassword($id, $password, $confirmpassword) {
            return $.post("/Account/Password",
                {

                    _token: "{{ csrf_token() }}",
                    id: $id ,
                    password: $password,
                    confirmpassword: $confirmpassword,
                });
        }

        function ResetServerValidationErrors(){
            $('.invalid').removeClass('invalid');
        }

        function ServerValidationErrors($array) {
            $.each($array, function (index, value) {
                $('#' + index).addClass('invalid');
            });

            $text = "";
            $.each($array, function( index, value ) {
                $text = $text + value + "<br>";
            });
            $.dialog({
                title: 'Oops...',
                content: $text
            });
        }
    </script>
@stop