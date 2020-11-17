<div class="col-md-6" style="padding: 10px;">

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

<div class="col-md-6" style="margin-top: 10px;">
    <div class="col-md-12">
        @if(SettingHelper::GetSetting('gmail-per-user') != null)
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
                <td>Google Account Link</td>
                <td><span id="google-link-status" class="glyphicon glyphicon glyphicon-ok" aria-hidden="true" style="color: green;"></span></td>
                <td><button id="google-link-toggle" type="button" class="btn OS-Button" style="width: 100%;">Enable</button></td>
            </tr>

            </tbody>
        </table>
        @endif
    </div>
</div>

<div class="col-md-6">
    <div class="input-group">
        <span class="input-group-addon" for="confirmpassword"><div style="width: 15em;">Daily Schedule Email:</div></span>
        <input type="checkbox" name="checkboxes" id="daily-schedule-email" data-on="Yes" data-off="No" data-toggle="toggle" data-width="100%"
        @if(Auth::user()->Option('ScheduleEmail'))
        checked
        @endif
        >
    </div>
</div>

<div class="col-md-6">
    <div class="input-group">
        <span class="input-group-addon" for="default-schedule-view"><div style="width: 15em;">Default Schedule View:</div></span>
        <select name="default-schedule-view" id="default-schedule-view" class="form-control">
            <option value="agendaDay">Team View</option>
            <option value="timelineDay">Day</option>
            <option value="listDay">List Day</option>
            <option value="timelineThreeDays">3 Days</option>
            <option value="agendaWeek">Week</option>
            <option value="listWeek">List Week</option>
            <option value="month">Month</option>
        </select>
    </div>
</div>

<div class="col-md-6">
    <div class="input-group">
        <span class="input-group-addon" for="default-view"><div style="width: 15em;">Default View:</div></span>
        <select name="default-view" id="default-view" class="form-control">
            <option value="Home">Home</option>
            @if(Auth::user()->hasPermission('scheduler_permission'))
                <option value="Scheduler"
                @if(Auth::user()->GetOption("default-view") === "Scheduler")
                selected
                @endif
                >Scheduler</option>
            @endif
            @if(Auth::user()->hasPermission('journal_permission'))
                <option value="Journal"
                @if(Auth::user()->GetOption("default-view") === "Journal")
                selected
                @endif
                >Journal</option>
            @endif
            @if(Auth::user()->hasPermission('reporting_permission'))
                <option value="Reporting"
                @if(Auth::user()->GetOption("default-view") === "Reporting")
                selected
                @endif
                >Reporting</option>
            @endif
            @if(Auth::user()->hasPermission('payroll_permission'))
                <option value="Payroll"
                @if(Auth::user()->GetOption("default-view") === "Payroll")
                selected
                @endif
                >Payroll</option>
            @endif
            @if(Auth::user()->hasPermission('templates_permission'))
                <option value="Templates"
                @if(Auth::user()->GetOption("default-view") === "Templates")
                selected
                @endif
                >Templates</option>
            @endif
        </select>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#default-view').change(function () {
        $data = {};
        $data['_token'] = "{{ csrf_token() }}";
        $data['key'] = 'default-view';
        $data['value'] = $(this).val();

        $("body").addClass("loading");
        $post = $.post("/Account/Option", $data);

        $post.done(function (data) {
            $("body").removeClass("loading");
            switch(data['status']) {
                case "OK":

                    break;
                case "notlogedin":
                    NotLogedIN();
                    break;
                default:
                    console.log(data);
                    $.dialog({
                        title: 'Oops...',
                        content: 'Unknown Response from server. Please refresh the page and try again.'
                    });
            }
        });

        $post.fail(function () {
            NoReplyFromServer();
        });
    });


    $('#default-schedule-view').change(function () {

        $data = {};
        $data['_token'] = "{{ csrf_token() }}";

        $data['key'] = 'DefaultScheduleView';
        $data['value'] = $(this).val();

        $("body").addClass("loading");
        $post = $.post("/Account/Option", $data);

        $post.done(function (data) {
            $("body").removeClass("loading");
            switch(data['status']) {
                case "OK":

                    break;
                case "notlogedin":
                    NotLogedIN();
                    break;
                default:
                    console.log(data);
                    $.dialog({
                        title: 'Oops...',
                        content: 'Unknown Response from server. Please refresh the page and try again.'
                    });
            }
        });

        $post.fail(function () {
            NoReplyFromServer();
        });

    });

    $('#daily-schedule-email').change(function () {

        $data = {};
        $data['_token'] = "{{ csrf_token() }}";

        $data['key'] = 'ScheduleEmail';

        if($(this).prop('checked') === true){
            $data['value'] = 1;
        }else{
            $data['value'] = 0
        }

        $("body").addClass("loading");
        $post = $.post("/Account/Option", $data);

        $post.done(function (data) {
            $("body").removeClass("loading");
            switch(data['status']) {
                case "OK":

                    break;
                case "notlogedin":
                    NotLogedIN();
                    break;
                default:
                    console.log(data);
                    $.dialog({
                        title: 'Oops...',
                        content: 'Unknown Response from server. Please refresh the page and try again.'
                    });
            }
        });

        $post.fail(function () {
            NoReplyFromServer();
        });

    });


    @if(Auth::user()->GoogleAccessToken != null)
    $("#google-link-status").removeClass('glyphicon-remove');
    $("#google-link-status").addClass('glyphicon-ok');
    $("#google-link-status").css('color' , 'green');

    $("#google-link-toggle").html('Disable');
    @else

    $("#google-link-status").removeClass('glyphicon-ok');
    $("#google-link-status").addClass('glyphicon-remove');
    $("#google-link-status").css('color' , 'red');

    $("#google-link-toggle").html('Enable');
    @endif

    $("#google-link-toggle").click(function()
    {

        if($("#google-link-toggle").html() === "Enable"){
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

            window.open('/Google/Auth?store=user', 'GMail Auth', $settings);

            $("#google-link-status").removeClass('glyphicon-remove');
            $("#google-link-status").addClass('glyphicon-ok');
            $("#google-link-status").css('color' , 'green');

            $("#google-link-toggle").html('Disable');

        }else{
            DeleteGoogleToken();
            $("#google-link-status").removeClass('glyphicon-ok');
            $("#google-link-status").addClass('glyphicon-remove');
            $("#google-link-status").css('color' , 'red');

            $("#google-link-toggle").html('Enable');

        }
    });

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
                    $("body").removeClass("loading");
                    switch(data['status']) {
                        case "OK":
                            $.dialog({
                                title: 'Success!',
                                content: 'Password Changed.'
                            });
                            break;
                        case "validation":
                            ServerValidationErrors(data['errors']);
                            break;
                        case "notlogedin":
                            NotLogedIN();
                            break;
                        default:
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

function DeleteGoogleToken(){
    $("body").addClass("loading");
    var values = {};
    values["_token"] = "{{ csrf_token() }}";

    values["tokendescriptor"] = "user";

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

function PostPassword($id, $password, $confirmpassword) {
    return $.post("/Account/Password",
        {

            _token: "{{ csrf_token() }}",
            id: $id ,
            password: $password,
            confirmpassword: $confirmpassword,
        });
}
</script>