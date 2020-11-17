<style>
    .permissionlabel{
        width: 100%;
        margin-top: 10px;
    }
    .permissionlabel{
        float: right;
    }
    .permissionsbox{
        padding: 0px 15px 15px;
        background-color: white;
        box-shadow: 0 1px 3px rgba(34, 25, 25, 0.4);
        -moz-box-shadow: 0 1px 2px rgba(34,25,25,0.4);
        -webkit-box-shadow: 0 1px 3px rgba(34, 25, 25, 0.4);
        border-radius: 5px;
        float: left;
        margin: 10px 10px 10px 10px;
        width: calc(50% - 20px);
    }
    #passwordbox{
        padding: 15px 15px 15px;
        background-color: white;
        box-shadow: 0 1px 3px rgba(34, 25, 25, 0.4);
        -moz-box-shadow: 0 1px 2px rgba(34,25,25,0.4);
        -webkit-box-shadow: 0 1px 3px rgba(34, 25, 25, 0.4);
        border-radius: 5px;
        float: left;
        margin: 10px 10px 10px 10px;
        width: calc(33% - 20px);
    }
</style>


<div class="row">
    <div class="col-md-9" style="margin-top: 5px;">
        <button id="disablelogin" class="btn OS-Button" type="button">Disable Login</button>
    </div>

</div>

<div class="" style="width: 66%; float: left;">
    <div class="permissionsbox">
        <div class="checkbox">
            <label for="client_permission" class="permissionlabel">
                {{ TextHelper::GetText("Clients") }}
                <div style="float: right;">
                    <input type="checkbox" name="checkboxes" id="client_permission" class="permission" data-on="Yes" data-off="No" data-toggle="toggle" value="1">
                </div>
            </label>
        </div>

        <div class="checkbox">
            <label for="vendor_permission" class="permissionlabel">
                Vendors
                <div style="float: right;">
                    <input type="checkbox" name="checkboxes" id="vendor_permission" class="permission" data-on="Yes" data-off="No" data-toggle="toggle" value="1">
                </div>
            </label>
        </div>

        <div class="checkbox">
            <label for="employee_permission" class="permissionlabel">
                <div style="width: 40%; float: left;">
                    Team/Staff
                </div>
                <select class="form-control multipermission" id="employee_permission" style="width: 60%;">
                    <option value="0">No</option>
                    <option value="2">Just My Department</option>
                    <option value="1">Full Access</option>
                </select>
            </label>
        </div>

        <div class="checkbox">
            <label for="login_management_permission" class="permissionlabel">
                Login Management/Admin
                <div style="float: right;">
                    <input type="checkbox" name="login_management_permission" id="login_management_permission" class="permission" data-on="Yes" data-off="No" data-toggle="toggle" value="1" >
                </div>
            </label>
        </div>

        <div class="checkbox">
            <label for="journal_permission" class="permissionlabel">
                Journal
                <div style="float: right;">
                    <input type="checkbox" name="checkboxes" id="journal_permission" class="permission" data-on="Yes" data-off="No" data-toggle="toggle" value="1">
                </div>
            </label>
        </div>
    </div>

    <div class="permissionsbox">
        <div class="checkbox">
            <label for="reporting_permission" class="permissionlabel">
                Reporting
                <div style="float: right;">
                    <input type="checkbox" name="checkboxes" id="reporting_permission" class="permission" data-on="Yes" data-off="No" data-toggle="toggle" value="1">
                </div>
            </label>
        </div>

        <div class="checkbox">
            <label for="viewacp" class="permissionlabel">
                Payroll
                <div style="float: right;">
                    <input  type="checkbox" name="checkboxes" id="payroll_permission" class="permission" data-on="Yes" data-off="No" data-toggle="toggle" value="1" >
                </div>
            </label>
        </div>

        <div class="checkbox">
            <label for="deposits_permission" class="permissionlabel">
                Add Deposits
                <div style="float: right;">
                    <input  type="checkbox" name="checkboxes" id="deposits_permission" class="permission" data-on="Yes" data-off="No" data-toggle="toggle" value="1">
                </div>
            </label>
        </div>

        <div class="checkbox">
            <label for="reciepts_permission" class="permissionlabel">
                Add Expenses
                <div style="float: right;">
                    <input type="checkbox" name="checkboxes" id="reciepts_permission" class="permission" data-on="Yes" data-off="No" data-toggle="toggle" value="1">
                </div>
            </label>
        </div>

        <div class="checkbox">
            <label for="checks_permission" class="permissionlabel">
                Write Checks
                <div style="float: right;">
                    <input type="checkbox" name="checkboxes" id="checks_permission" class="permission" data-on="Yes" data-off="No" data-toggle="toggle" value="1">
                </div>
            </label>
        </div>

    </div>

    <div class="permissionsbox">
        <div class="checkbox">
            <label for="chat_permission" class="permissionlabel">
                Chat
                <div style="float: right;">
                    <input type="checkbox" name="checkboxes" id="chat_permission" class="permission" data-on="Yes" data-off="No" data-toggle="toggle" value="1">
                </div>
            </label>
        </div>
        <div class="checkbox">
            <label for="scheduler_permission" class="permissionlabel">
                Scheduler
                <div style="float: right;">
                    <input type="checkbox" name="checkboxes" id="scheduler_permission" class="permission" data-on="Yes" data-off="No" data-toggle="toggle" value="1">
                </div>
            </label>
        </div>
        <div class="checkbox">
            <label for="tasks_permission" class="permissionlabel">
                Task Manager
                <div style="float: right;">
                    <input type="checkbox" name="checkboxes" id="tasks_permission" class="permission" data-on="Yes" data-off="No" data-toggle="toggle" value="1">
                </div>
            </label>
        </div>
        <div class="checkbox">
            <label for="templates_permission" class="permissionlabel">
                Templates
                <div style="float: right;">
                    <input type="checkbox" name="checkboxes" id="templates_permission" class="permission" data-on="Yes" data-off="No" data-toggle="toggle" value="1">
                </div>
            </label>
        </div>
        <div class="checkbox">
            <label for="multi_assets_permission" class="permissionlabel">
                <div style="width: 40%; float: left;">
                Inventory & Assets
                </div>
                    <select class="form-control multipermission" id="multi_assets_permission" style="width: 60%;">
                        <option value="0">No</option>
                        <option value="1">Adjust Company Stock</option>
                        <option value="2">Manage Inventory/Stock</option>
                        <option value="3">Manage All</option>
                    </select>

            </label>
        </div>
    </div>

    <div class="permissionsbox">
        <div class="checkbox">
            <label for="viewacp" class="permissionlabel">
                Access ACP
                <div style="float: right;">
                    <input type="checkbox" name="checkboxes" id="acp_permission" class="permission" data-on="Yes" data-off="No" data-toggle="toggle" value="1" >
                </div>
            </label>
        </div>

        <div id="acp_manage_custom_tables_permission_container" class="checkbox" style="display: none;">
            <label for="acp_manage_custom_tables_permission" class="permissionlabel">
                Manage Custom Tables
                <div style="float: right;">
                    <input type="checkbox" name="checkboxes" id="acp_manage_custom_tables_permission" class="permission" data-on="Yes" data-off="No" data-toggle="toggle" value="1" >
                </div>
            </label>
        </div>

        <div class="checkbox" id="acp_company_info_permission_container" style="display: none;">
            <label for="company_info_permission" class="permissionlabel">
                Edit Company Info
                <div style="float: right;">
                    <input type="checkbox" name="checkboxes" id="acp_company_info_permission" class="permission" data-on="Yes" data-off="No" data-toggle="toggle" value="1" >
                </div>
            </label>
        </div>

        <div class="checkbox" id="acp_subscription_permission_container" style="display: none;">
            <label for="acp_subscription_permission" class="permissionlabel">
                Manage Subscription
                <div style="float: right;">
                    <input type="checkbox" name="checkboxes" id="acp_subscription_permission" class="permission" data-on="Yes" data-off="No" data-toggle="toggle" value="1" >
                </div>
            </label>
        </div>
        <div class="checkbox" id="acp_import_export_permission_container" style="display: none;">
            <label for="acp_import_export_permission" class="permissionlabel">
                Bulk data Import/Export
                <div style="float: right;">
                    <input type="checkbox" name="checkboxes" id="acp_import_export_permission" class="permission" data-on="Yes" data-off="No" data-toggle="toggle" value="1" >
                </div>
            </label>
        </div>
    </div>
</div>

<div id="passwordbox" style="">

    <div class="input-group" style="margin-bottom: 5px;">
        <span class="input-group-addon" for="changepassword"><div style="width: 9em;">Change Password:</div></span>
        <input id="password" name="password" type="password" class="form-control">
    </div>

    <div class="input-group">
        <span class="input-group-addon" for="confirmpassword"><div style="width: 9em;">Confirm Password:</div></span>
        <input id="confirmpassword" name="confirmpassword" type="password" class="form-control">
        <span class="input-group-btn">
            <button id="passwordsave" class="btn btn-default" type="button">Update</button>
        </span>
    </div>

</div>

<script>    
$(document).ready(function() {

    SetUpPage();
    
    $id = "{{ $employee->id }}";
    
    $('#disablelogin').click(function(){

        $.confirm({
            title: 'Warning!',
            content: 'You are about to remove the ability for this user to login, are you sure you want to continue?',
            buttons: {
                confirm: function () {
                    DisableLogin($id);
                },
                cancel: function () {

                },
            }
        });

    });

    $('.multipermission').change(function () {
        SetPermission($id, $(this).prop('id'), $(this).val());
    });

    $('.permission').change(function() {
        if($(this).prop('checked') === true){
            EnablePermission($id, $(this));
        }else{
            DisablePermission($id, $(this));
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
                post = PostPassword("{{ $employee->id }}", $('#password').val(), $('#confirmpassword').val());

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

function PostPassword($id, $password, $confirmpassword) {
    return $.post("/Users/Save/Password",
    {

        _token: "{{ csrf_token() }}",
        id: $id ,
        password: $password,
        confirmpassword: $confirmpassword,
    });
}
function EnablePermission($id, $permission){

    $("body").addClass("loading");
    posting = $.post("/Users/Save/EnablePermission",
        {
            _token: "{{ csrf_token() }}",
            id: $id,
            permission: $permission.prop('id')
        });

    posting.done(function( data ) {
        $("body").removeClass("loading");

        switch(data['status']) {
            case "OK":
                bootstrap_alert_old.warning("Permission enabled", 'success', 2000);

                if($permission.prop('id') === "acp_permission"){
                    $('#acp_manage_custom_tables_permission_container').css('display' , 'block');
                    $('#acp_company_info_permission_container').css('display' , 'block');
                    $('#acp_subscription_permission_container').css('display' , 'block');
                    $('#acp_import_export_permission_container').css('display' , 'block');
                }
                break;
            case "validation":
                ServerValidationErrors(data['errors']);
                break;
            default:
                console.log(data);
                $.dialog({
                    title: 'Oops...',
                    content: 'Unknown Response from server. Please refresh the page and try again.'
                });
        }
    });

    posting.fail(function() {
        $("body").removeClass("loading");
        bootstrap_alert.warning("Failed to post", 'danger', 4000);
    });
}

function DisablePermission($id, $permission){
    $("body").addClass("loading");

    posting = $.post("/Users/Save/DisablePermission",
    {
        _token: "{{ csrf_token() }}",
        id: $id,
        permission: $permission.prop('id')
    });

    posting.done(function( data ) {
        console.log(data);
        $("body").removeClass("loading");

        switch(data['status']) {
            case "OK":
                bootstrap_alert_old.warning("Permission disabled", 'success', 2000);

                if($permission.prop('id') === "acp_permission"){
                    $("#acp_subscription_permission").bootstrapToggle('off');
                    $("#acp_manage_custom_tables_permission").bootstrapToggle('off');
                    $("#acp_company_info_permission").bootstrapToggle('off');
                    $("#acp_import_export_permission").bootstrapToggle('off');

                    $('#acp_manage_custom_tables_permission_container').css('display' , 'none');
                    $('#acp_company_info_permission_container').css('display' , 'none');
                    $('#acp_subscription_permission_container').css('display' , 'none');
                    $('#acp_import_export_permission_container').css('display' , 'none');
                }
                break;
            case "validation":
                ServerValidationErrors(data['errors']);
                break;
            default:
                console.log(data);
                $.dialog({
                    title: 'Oops...',
                    content: 'Unknown Response from server. Please refresh the page and try again.'
                });
        }

    });

    posting.fail(function() {
        $("body").removeClass("loading");
        bootstrap_alert.warning("Failed to post", 'danger', 4000);
    });
}

function SetPermission($id, $permission, $level){

    $("body").addClass("loading");

    posting = $.post("/Users/Save/SetPermission",
        {
            _token: "{{ csrf_token() }}",
            id: $id,
            permission: $permission,
            level: $level,
        });

    posting.done(function( data ) {
        console.log(data);
        $("body").removeClass("loading");

        switch(data['status']) {
            case "OK":

                break;
            case "notallowed":
                $.dialog({
                    title: 'Oops...',
                    content: "You can't change your own, Team/Staff Permission."
                });
                break;
            case "notfound":
                $.dialog({
                    title: 'Oops...',
                    content: 'Unknown Response from server. Please refresh the page and try again.'
                });
                break;
            default:
                console.log(data);
                $.dialog({
                    title: 'Oops...',
                    content: 'Unknown Response from server. Please refresh the page and try again.'
                });
        }

    });

    posting.fail(function() {
        $("body").removeClass("loading");
        bootstrap_alert.warning("Failed to post", 'danger', 4000);
    });
}

function DisableLogin($id){
    $("body").addClass("loading");
    posting = $.post("/Users/DisableLogin",
    {
        _token: "{{ csrf_token() }}",
        id: $id,
    });

    posting.done(function( data ) {
        switch(data['status']) {
            case "OK":
                GoToPage('/Employees/View/{{ $employee->id }}/login');
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

    posting.fail(function() {
        $("body").removeClass("loading");
        bootstrap_alert.warning("Failed to post", 'danger', 4000);
    });
}



function SetUpPage(){

    @if($employee->hasPermission('acp_permission'))
        $('#acp_manage_custom_tables_permission_container').css('display' , 'block');
        $('#acp_company_info_permission_container').css('display' , 'block');
        $('#acp_subscription_permission_container').css('display' , 'block');
        $('#acp_import_export_permission_container').css('display' , 'block');
    @else
        $('#acp_manage_custom_tables_permission_container').css('display' , 'none');
        $('#acp_company_info_permission_container').css('display' , 'none');
        $('#acp_subscription_permission_container').css('display' , 'none');
        $('#acp_import_export_permission_container').css('display' , 'none');
    @endif

    @foreach($employee->permissions as $key => $value)
        @if(substr( $key, 0, 5 ) === "multi")
            $('#{{ $key }}').val('{{ $value }}');
        @else
            @if($key === "employee_permission")
                $('#{{ $key }}').val('{{ $value }}');
            @else
                @if( $value == 1)
                    $('#{{ $key }}').bootstrapToggle('on');
                @else
                    $('#{{ $key }}').bootstrapToggle('off');
                @endif
            @endif
        @endif
    @endforeach


    @if(Auth::user()->id === $employee->id)
        $("#login_management_permission").bootstrapToggle('disable');
        $("#employee_permission").prop('disabled', true);
    @endif
}
</script>