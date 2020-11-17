
<div class="row" style="text-align: center; padding-top: 50px;">
    <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#LoginEnableModal" style="">Enable Login</button>
</div>


<!-- Modal -->
<div id="LoginEnableModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Please Set Initial Permissions</h4>
            </div>
            <div class="modal-body">
                <div class="row">


                    <div class="col-md-4">
                        <div class="checkbox">
                            <label for="client_permission">
                                <input type="checkbox" name="checkboxes" id="client_permission" class="permission" data-on="Yes" data-off="No" data-toggle="toggle" value="1">
                                Clients
                            </label>
                        </div>

                        <div class="checkbox">
                            <label for="vendor_permission">
                                <input type="checkbox" name="checkboxes" id="vendor_permission" class="permission" data-on="Yes" data-off="No" data-toggle="toggle" value="1">
                                Vendors
                            </label>
                        </div>

                        <div class="checkbox">
                            <label for="employee_permission">
                                <input type="checkbox" name="checkboxes" id="employee_permission" class="permission" data-on="Yes" data-off="No" data-toggle="toggle" value="1">
                                Team/Staff
                            </label>
                        </div>

                        <div class="checkbox">
                            <label for="login_management_permission">
                                <input type="checkbox" name="login_management_permission" id="login_management_permission" class="permission" data-on="Yes" data-off="No" data-toggle="toggle" value="1" >
                                Login Management/Admin
                            </label>
                        </div>

                        <div class="checkbox">
                            <label for="journal_permission">
                                <input type="checkbox" name="checkboxes" id="journal_permission" class="permission" data-on="Yes" data-off="No" data-toggle="toggle" value="1">
                                Journal
                            </label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="checkbox">
                            <label for="reporting_permission">
                                <input type="checkbox" name="checkboxes" id="reporting_permission" class="permission" data-on="Yes" data-off="No" data-toggle="toggle" value="1">
                                Reporting
                            </label>
                        </div>

                        <div class="checkbox">
                            <label for="viewacp">
                                <input type="checkbox" name="checkboxes" id="payroll_permission" class="permission" data-on="Yes" data-off="No" data-toggle="toggle" value="1" >
                                Payroll
                            </label>
                        </div>

                        <div class="checkbox">
                            <label for="deposits_permission">
                                <input type="checkbox" name="checkboxes" id="deposits_permission" class="permission" data-on="Yes" data-off="No" data-toggle="toggle" value="1">
                                Add Deposits
                            </label>
                        </div>

                        <div class="checkbox">
                            <label for="reciepts_permission">
                                <input type="checkbox" name="checkboxes" id="reciepts_permission" class="permission" data-on="Yes" data-off="No" data-toggle="toggle" value="1">
                                Add Expenses
                            </label>
                        </div>

                        <div class="checkbox">
                            <label for="checks_permission">
                                <input type="checkbox" name="checkboxes" id="checks_permission" class="permission" data-on="Yes" data-off="No" data-toggle="toggle" value="1">
                                Write Checks
                            </label>
                        </div>

                    </div>

                    <div class="col-md-4">
                        <div class="checkbox">
                            <label for="chat_permission">
                                <input type="checkbox" name="checkboxes" id="chat_permission" class="permission" data-on="Yes" data-off="No" data-toggle="toggle" value="1">
                                Chat
                            </label>
                        </div>
                        <div class="checkbox">
                            <label for="scheduler_permission">
                                <input type="checkbox" name="checkboxes" id="scheduler_permission" class="permission" data-on="Yes" data-off="No" data-toggle="toggle" value="1">
                                Scheduler
                            </label>
                        </div>
                        <div class="checkbox">
                            <label for="tasks_permission">
                                <input type="checkbox" name="checkboxes" id="tasks_permission" class="permission" data-on="Yes" data-off="No" data-toggle="toggle" value="1">
                                Task Manager
                            </label>
                        </div>
                        <div class="checkbox">
                            <label for="templates_permission">
                                <input type="checkbox" name="checkboxes" id="templates_permission" class="permission" data-on="Yes" data-off="No" data-toggle="toggle" value="1">
                                Templates
                            </label>
                        </div>
                        <div class="checkbox">
                            <label for="assets_permission">
                                <input type="checkbox" name="checkboxes" id="assets_permission" class="permission" data-on="Yes" data-off="No" data-toggle="toggle" value="1">
                                Asset & Liability Overview
                            </label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="checkbox">
                            <label for="viewacp">
                                <input type="checkbox" name="checkboxes" id="acp_permission" class="permission" data-on="Yes" data-off="No" data-toggle="toggle" value="1" >
                                Access ACP
                            </label>
                        </div>

                        <div id="acp_manage_custom_tables_permission_container" class="checkbox" style="display: none;">
                            <label for="acp_manage_custom_tables_permission">
                                <input type="checkbox" name="checkboxes" id="acp_manage_custom_tables_permission" class="permission" data-on="Yes" data-off="No" data-toggle="toggle" value="1" >
                                Manage Custom Tables
                            </label>
                        </div>
                        <div class="checkbox" id="acp_company_info_permission_container" style="display: none;">
                            <label for="company_info_permission">
                                <input type="checkbox" name="checkboxes" id="acp_company_info_permission" class="permission" data-on="Yes" data-off="No" data-toggle="toggle" value="1" >
                                Edit Company Info
                            </label>
                        </div>
                        <div class="checkbox" id="acp_subscription_permission_container" style="display: none;">
                            <label for="acp_subscription_permission" >
                                <input type="checkbox" name="checkboxes" id="acp_subscription_permission" class="permission" data-on="Yes" data-off="No" data-toggle="toggle" value="1" >
                                Manage Subscription
                            </label>
                        </div>
                        <div class="checkbox" id="acp_import_export_permission_container" style="display: none;">
                            <label for="acp_import_export_permission">
                                <input type="checkbox" name="checkboxes" id="acp_import_export_permission" class="permission" data-on="Yes" data-off="No" data-toggle="toggle" value="1" >
                                Bulk data Import/Export
                            </label>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="checkbox">
                            <label for="send-invite-toggle">
                                <input id="send-invite-toggle" type="checkbox" checked data-toggle="toggle"  data-on="Yes" data-off="No" >
                                Email login details to {{ $employee->email }}
                            </label>
                        </div>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button id="enable-login" type="button" class="btn btn-default" >Enable Login</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<script>
$(document).ready(function() {


    $('#acp_permission').change(function() {
        if($(this).prop('checked') === true){
            $('#acp_manage_custom_tables_permission_container').css('display' , 'block');
            $('#acp_company_info_permission_container').css('display' , 'block');
            $('#acp_subscription_permission_container').css('display' , 'block');
            $('#acp_import_export_permission_container').css('display' , 'block');
        }else{
            $('#acp_manage_custom_tables_permission_container').css('display' , 'none');
            $('#acp_company_info_permission_container').css('display' , 'none');
            $('#acp_subscription_permission_container').css('display' , 'none');
            $('#acp_import_export_permission_container').css('display' , 'none');
        }
    });

    $('#enable-login').click(function () {

        $data = {};
        $data['_token'] = "{{ csrf_token() }}";

        $data['id'] = {{ $employee->id }}

        $('.permission').each(function( index ){
            if($(this).prop('checked') === true){
                $data[$(this).prop('id')] = 1;
            }else{
                $data[$(this).prop('id')] = 0;
            }
        });

        if($('#send-invite-toggle').prop('checked') === true){
            $data['send-invite-toggle'] = 1;
            $.confirm({
                title: 'Perfect!',
                content: 'Your Team Member will receive an email with their login details and temporary password.',
                buttons: {
                    "Continue": function () {
                        SendUserEnable($data);
                    }
                }
            });

        }else{
            $data['send-invite-toggle'] = 0;

            $.confirm({
                title: 'Important!',
                content: 'You have selected NOT to email the user their details, You will need to set their password on the next screen before they will be able to login. Would you still like to continue',
                buttons: {
                    "Continue": function () {
                        SendUserEnable($data);
                    },
                    "Close": function () {

                    }
                }
            });
        }

    });
});

function SendUserEnable($data){

    $("body").addClass("loading");
    ResetServerValidationErrors();

    $post = $.post("/Users/EnableLogin", $data);

    $post.done(function (data) {

        switch(data['status']) {
            case "OK":
                GoToPage('/Employees/View/{{ $employee->id }}/login');
                break;
            case "unabletofinduser":
                $.dialog({
                    title: 'Opps...',
                    content: 'Unable to locate Employee, please refresh the page and try again.'
                });
                break;
            case "noslots":
                Unable(data['slots'], data['count']);
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

    $post.fail(function () {
        NoReplyFromServer();
    });
}

function Unable($slots, $count){
    $('#canlogin').prop('checked', false);
    $.confirm({
        title: 'Unable to enable employee!',
        content: 'You are currently licenced for ' + $slots + ' users and you currently have ' + $count + ' users enabled, Please disable another employee or purchase a higher license.',
        buttons: {
            @if(Auth::user()->hasPermission('subscription_permission'))
            confirm: function () {
                $.confirm({
                    title: 'Upgrade',
                    content: 'Would you like to procede to "My Subscription" to upgrade your package?',
                    buttons: {
                        confirm: function () {
                            window.open("/Subscription", '_blank');
                        },
                        cancel: function () {

                        },
                    }
                });
            },
            @endif
        }
    });
}
</script>