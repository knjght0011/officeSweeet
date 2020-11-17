
<div class="row">
     <label class="col-md-3 control-label" style="margin-top: 25px; text-align: right;" for="email">Email</label>
     <div class="col-md-7" >
        <input id="newemail" name="newemail" type="text" style="margin-top: 20px" class="form-control input-md" required="">
    </div>
</div>

<div class="row">
    <label class="col-md-3 control-label" style="margin-top: 25px; text-align: right;" for="changepassword">Password</label>
    <div class="col-md-7" >
        <input id="newpassword" name="newpassword" type="password" style="margin-top: 20px" placeholder="" class="form-control input-md">
    </div>
</div>

<div class="row">
    <label class="col-md-3 control-label" style="margin-top: 25px; text-align: right;" for="changepassword">Confirm Password</label>
    <div class="col-md-7">
        <input id="newconfirmpassword" name="newconfirmpassword" type="password" style="margin-top: 20px" placeholder="" class="form-control input-md">
    </div>
    <div class="form-group">
        <button id="usersave" name="Submit" class="btn btn-default" style="margin-top: 20px">Submit</button>
    </div>
</div>



<script>
$( document ).ready(function() {
    
    $("#usersave").click(function()
    {
        if ($('#newpassword').val().length < 6)
        {
            if ($('#newpassword').val() === $('#newconfirmpassword').val())
            {
                ResetServerValidationErrors();

                post = PostNewUser($('#newemail').val(), $('#newpassword').val(), $('#newconfirmpassword').val());

                post.done(function( data ) 
                {
                    if (data === "success"){
                        alert("User Created");
                        window.GetUsersData();
                    }else{
                        ServerValidationErrors(data);
                    }
                });

                post.fail(function() {
                    alert( "Failed to post user details" );
                }); 
            }else{
                alert("passwords dont match")
            }  
        }else{
            alert("Password needs to be atleast 6 characters long")
        }              
    });

});

function PostNewUser($email, $password, $confirmpassword) {

    return $.post("/Users/Save/NewUser",
    {
        _token: "{{ csrf_token() }}",
        email: $email,
        password: $password,
        confirmpassword: $confirmpassword,
    });
}
</script>