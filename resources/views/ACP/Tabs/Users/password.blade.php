<br>
<div class="col-md-12">
    <select id="userselect-password" name="selectbasic" class="form-control userselect" style="height: 100%;"></select>
</div>
<br>
<br>
<br>
<div class="row">    
    <label class="col-md-3 control-label" style="margin-top: 25px; text-align: right;" for="changepassword">Change Password</label>
    <div class="col-md-7" style="margin-top: 20px">
        <input id="password" name="password" type="password" placeholder="" class="form-control input-md" autocomplete="off" disabled="true">
    </div>
</div>

<div class="row">
    <label class="col-md-3 control-label" style="margin-top: 25px; text-align: right;" for="confirmpassword">Confirm Password</label>
    <div class="col-md-7" style="margin-top: 20px">
        <input  id="confirmpassword" name="confirmpassword" type="password" placeholder="" class="form-control input-md" autocomplete="off" disabled="true">
    </div>

    <div class="col-md-2" style="margin-top: 20px">
        <button id="passwordsave" name="passwordsave" class="btn btn-default">Update</button>
    </div>
</div>

<script>
$( document ).ready(function() {
    $('#userselect-password').change(function() {       
        $("#password").prop('disabled', false);
        $("#password").val("");
        $("#confirmpassword").prop('disabled', false);
        $("#confirmpassword").val("");
    });
    
    $("#passwordsave").click(function()
    {
        ResetServerValidationErrors();
        $password = $('#password').val();
        if ($password.length >= 6)
        {        
            if ($('#password').val() === $('#confirmpassword').val())
            {
                post = PostPassword($('#userselect-password').val(), $('#password').val(), $('#confirmpassword').val());

                post.done(function( data ) 
                {

                    if (data === "success"){
                        alert("The password has been changed");
                    }else{
                        //server validation errors
                        ServerValidationErrors(data);
                    }
                });

                post.fail(function() {
                    alert( "Failed to post password details" );
                }); 
            }else{
                alert("passwords dont match")
            }        
        }else{
            alert("Password needs to be atleast 6 characters long")
        }  
    });

});

function PostPassword($id, $password, $confirmpassword) {
    return $.post("/Users/Save/Password",
    {

        _token: "{{ csrf_token() }}",
        id: $id ,
        password: $password,
        confirmpassword: $confirmpassword,
    });
}
</script>