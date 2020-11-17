<div class="container" style="margin-top: 10px;">
    <h2>Google Mail integration:</h2>
    <p>Currently, if you have an email service set up on your machine/computer, OfficeSweeet will use that email client to send your emails.  Likewise, you would use your existing system to receive email from your clients. However, we are offering an alternate approach for those who wish to send mass emails to all of your clients and/or prospects.</p>
    <p>We offer integration with Gmail whereby you can;</p>
    <ol>
        <li>link your personal/individual Gmail account to OfficeSweeet</li>
        <li>link your company Gmail account to OfficeSweeet or</li>
        <li>create a new Gmail account for your business through Google that is linked with OfficeSweeet ( for example; yourbusiness@gmail.com)</li>
    </ol>
    <p>The advantages to linking a Gmail account with OfficeSweeet will be;</p>
    <ol>
        <li>The ability to send mass/bulk email messages to all of your clients and/or prospects.</li>
        <li>To include many of the other Goggle services offered through this interface.</li>
    </ol>

    <p>IMPORTANT: Should you decide to integrate Google services with OfficeSweeet, when asked to allow OfficeSweeet to manage your emails etc., they are merely using the name OfficeSweeet as the application you are using and not our company.  We cannot view your email messages nor would we want to.  We respect your privacy.  We are merely attempting to make it easier for you to use Google services/apps within your OfficeSweeet application.  If you have any questions, feel free to contact us directly at 813-444-5284.  </p>
        <table class="table">
            <thead>
                <tr>
                    <th>Setting</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Per User G-Mail Integration</td>
                    <td><span id="per-user-status" class="glyphicon glyphicon glyphicon-ok" aria-hidden="true" style="color: green;"></span></td>
                    <td><button id="gmail-per-user-toggle" type="button" class="btn OS-Button" style="width: 100%;">Enable</button></td>
                </tr>
                <tr>
                    <td>Company Wide G-Mail Integration</td>
                    <td><span id="gmail-system-status" class="glyphicon glyphicon glyphicon-ok" aria-hidden="true" style="color: green;"></span></td>
                    <td><button id="gmail-system-toggle" type="button" class="btn OS-Button" style="width: 100%;">Enable</button></td>
                </tr>
            </tbody>
        </table>
</div>
<script>
$(document).ready(function() {

    @if(SettingHelper::GetSetting('gmail-per-user') != null)
        $("#per-user-status").removeClass('glyphicon-remove');
        $("#per-user-status").addClass('glyphicon-ok');
        $("#per-user-status").css('color' , 'green');

        $("#gmail-per-user-toggle").html('Disable');
    @else

        $("#per-user-status").removeClass('glyphicon-ok');
        $("#per-user-status").addClass('glyphicon-remove');
        $("#per-user-status").css('color' , 'red');

        $("#gmail-per-user-toggle").html('Enable');
    @endif

    @if(SettingHelper::GetSetting('gmail-system') != null)
        $("#gmail-system-status").removeClass('glyphicon-remove');
        $("#gmail-system-status").addClass('glyphicon-ok');
        $("#gmail-system-status").css('color' , 'green');

        $("#gmail-system-toggle").html('Disable');
    @else

        $("#gmail-system-status").removeClass('glyphicon-ok');
        $("#gmail-system-status").addClass('glyphicon-remove');
        $("#gmail-system-status").css('color' , 'red');

        $("#gmail-system-toggle").html('Enable');
    @endif

    $("#gmail-per-user-toggle").click(function() {

        $("body").addClass("loading");
        var values = {};
        values["_token"] = "{{ csrf_token() }}";

        if($(this).html() === "Enable"){
            values["gmail-per-user"] = "yes";
        }else{
            values["gmail-per-user"] = "";
        }

        post = $.post("/ACP/General/Save",values);

        post.done(function( data ) {
            $("body").removeClass("loading");
            if(values["gmail-per-user"] === ""){
                $("#per-user-status").removeClass('glyphicon-ok');
                $("#per-user-status").addClass('glyphicon-remove');
                $("#per-user-status").css('color' , 'red');

                $("#gmail-per-user-toggle").html('Enable');
            }else{
                $("#per-user-status").removeClass('glyphicon-remove');
                $("#per-user-status").addClass('glyphicon-ok');
                $("#per-user-status").css('color' , 'green');

                $("#gmail-per-user-toggle").html('Disable');
            }
        });

        post.fail(function() {
            $("body").removeClass("loading");

            $.dialog({
                title: 'Oops...',
                content: 'Unable to save setting. Please refresh the page and try again.'
            });
        });
    });

    $("#gmail-system-toggle").click(function() {
        $height = screen.height;
        $width = screen.width;

        $settings = "height=" + ($height / 6) * 4 + ",";
        $settings += "width=" + $width / 2 + ",";
        $settings += "left=" + $width / 4 + ",";
        $settings += "top=" + $height / 6 + ",";
        $settings += "toolbar=no,";
        $settings += "titlebar=no,";
        $settings += "status=no,";
        $settings += "menubar=no,";
        $settings += "location=no,";
        $settings += "status=no,";
        $settings += "channelmode=yes";

        var wnd = window.open('/Google/Popup/global', 'GMail Auth', $settings);

        var timer = setInterval(function() {
            if(wnd.closed) {
                clearInterval(timer);
                $("body").addClass("loading");
                var get = $.get( "/Setting/gmail-system", function(  ) { });

                get.done(function( data ) {
                    if (data === "") {
                        $("#gmail-system-status").removeClass('glyphicon-ok');
                        $("#gmail-system-status").addClass('glyphicon-remove');
                        $("#gmail-system-status").css('color', 'red');

                        $("#gmail-system-toggle").html('Enable');
                        $("body").removeClass("loading");
                    } else {
                        $("#gmail-system-status").removeClass('glyphicon-remove');
                        $("#gmail-system-status").addClass('glyphicon-ok');
                        $("#gmail-system-status").css('color', 'green');

                        $("#gmail-system-toggle").html('Disable');
                        $("body").removeClass("loading");
                    }
                });
            }
        }, 1000);


    });

    $("#gmail-system-toggle1").click(function() {

        $("body").addClass("loading");
        var get = $.get( "/Setting/gmail-system", function(  ) { });

        get.done(function( data ) {

            if(data === ""){
                $height = screen.height;
                $width = screen.width;

                $settings = "height=" + ($height / 6) * 4 + ",";
                $settings += "width=" + $width / 2 + ",";
                $settings += "left=" + $width / 4 + ",";
                $settings += "top=" + $height / 6 + ",";
                $settings += "toolbar=no,";
                $settings += "titlebar=no,";
                $settings += "status=no,";
                $settings += "menubar=no,";
                $settings += "location=no,";
                $settings += "status=no,";
                $settings += "channelmode=yes";

                var wnd = window.open('/Google/Auth?store=global', 'GMail Auth', $settings);

                $("#gmail-system-status").removeClass('glyphicon-remove');
                $("#gmail-system-status").addClass('glyphicon-ok');
                $("#gmail-system-status").css('color' , 'green');

                $("#gmail-system-toggle").html('Disable');
                $("body").removeClass("loading");
            }else{
                DeleteSystemToken();
                $("#gmail-system-status").removeClass('glyphicon-ok');
                $("#gmail-system-status").addClass('glyphicon-remove');
                $("#gmail-system-status").css('color' , 'red');

                $("#gmail-system-toggle").html('Enable');
                $("body").removeClass("loading");
            }
        });
    });


    /*
    $("#gmail-per-system-toggle-button").click(function() {

        wnd.onunload = function(e){
            console.log("now");
            setTimeout(UpdateButton(), 3000);
            console.log("later");
        };
    });
    */
});
function DeleteSystemToken() {
    $("body").addClass("loading");
    var values = {};
    values["_token"] = "{{ csrf_token() }}";

    values["tokendescriptor"] = "global";

    post = $.post("/Google/DeleteToken",values);

    post.done(function( data ) {
        $("body").removeClass("loading");
        console.log(data);
        if(data === "done"){

        }
        if(data === "none"){

        }
    });

    post.fail(function() {
        $("body").removeClass("loading");

        $.dialog({
            title: 'Oops...',
            content: 'Unable to save setting. Please refresh the page and try again.'
        });
    });
}

function UpdateButton() {
    console.log("start");
    var get = $.get( "/Setting/gmail-system", function(  ) { });

    get.done(function( data ) {
        console.log("done");
        console.log(data);
        if(data === ""){
            $("#gmail-per-system-toggle-button").html('<span class="glyphicon glyphicon glyphicon-remove" aria-hidden="true" style="color: red;"></span> Enable company wide GMail integration.');
        }else{
            $("#gmail-per-system-toggle-button").html('<span class="glyphicon glyphicon glyphicon-ok" aria-hidden="true" style="color: green;"></span> Enable company wide GMail integration.');
        }
    });
}
</script>
<!--
<div class="row" style="margin-top: 10px;">
    <div class="form-group">
        <label class="col-md-2 control-label" for="emailfromaddress">System From Address:</label>
        <div class="col-md-4">
            <input id="emailfromaddress" name="emailfromaddress" type="text" placeholder="admin@officesweet.com" class="form-control input-md" val="{{ SettingHelper::GetSetting('emailfromaddress') }}">
        </div>
        <div class="col-md-4">
            <label class="checkbox-inline" for="checkboxes-3">
                <input type="checkbox" name="checkboxes" id="emailfromuser">
                Sending Users Email
            </label>
        </div>
        <div class="col-md-2">

            <button id="emailsavefromaddress" name="emailsavefromaddress" type="button" class="btn OS-Button" value="">Save</button>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {


    @if(SettingHelper::GetSetting('emailfromaddress') === "user")
        $("#emailfromaddress").prop('disabled', true);
        $("#emailfromuser").prop('checked', true);
        $("#emailfromaddress").val($email);
    @endif
   
    $("#emailfromuser").change(function()
    {
        if(this.checked) {
            $("#emailfromaddress").val("user");
            $("#emailfromaddress").prop('disabled', true);
            
        }else{
            $("#emailfromaddress").val("");
            $("#emailfromaddress").prop('disabled', false);
            
        }
    });
    
    $("#emailsavefromaddress").click(function()
    {
        $("body").addClass("loading");

        $emailfromaddress = $("#emailfromaddress").val();

        
        post = $.post("/ACP/General/Save",
        {
            _token: "{{ csrf_token() }}",
            emailfromaddress: $emailfromaddress,
        });
        
        
        post.done(function( data ) {
             $("body").removeClass("loading");
             alert(data);
        });
        
        post.fail(function() {
             $("body").removeClass("loading");
             alert( "Failed to post settings" );
             //bootstrap_alert.warning("Unable to post data", 'danger', 4000);
        });  
    });
} ); 
</script>
-->