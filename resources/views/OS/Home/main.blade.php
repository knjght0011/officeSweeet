@extends('master')

@section('content')
<style>
.dataTables_filter {
    display: none; 
}
.dataTables_length {
    display: none; 
}
.dataTables_info {
    display: none; 
}
.dataTables_paginate {
    display: none; 
}
</style>

<h3 style="margin-top: 10px;">Contacts Home</h3>

@desktop
<div class="row">
    <div class="col-md-5">
    @if(Auth::user()->hasPermission("client_permission"))
        @if($clients != null or $prospects != null)
            @if(app()->make('account')->plan_name === "SOLO")
                <a id="link" href="/Clients/Add"><button class="btn OS-Button btn-sm" type="button">Add {{ TextHelper::GetText("Client") }}</button></a>
            @else
                <a id="link" href="/Clients/Add"><button class="btn OS-Button btn-sm" type="button">Add {{ TextHelper::GetText("Client") }}/Prospect</button></a>
            @endif
        @endif
    @else

    @endif

    @if(Auth::user()->hasPermission("vendor_permission") and $vendors != null)
        <a id="link" href="/Vendors/Add"><button class="btn OS-Button btn-sm" type="button">Add Vendor</button></a>
    @endif

    @if(Auth::user()->hasPermission("employee_permission") and $employees != null)
        <a id="link" href="/Employees/Add"><button class="btn OS-Button btn-sm" type="button">Add Team/Staff</button></a>
        <a id="link" href="/Training"><button class="btn OS-Button btn-sm" type="button">Training by Department</button></a>
    @endif
    </div>

    @if(Auth::user()->hasPermission("client_permission") and $clients != null)
    <div id="client-info" style="float: left; margin-right: 10px; padding-top: 5px; text-align: center; font-size: 22px; font-weight: bold;">
        Receivables: ${{ HomeHelper::Recevables($clients) }}
    </div>
    @endif

    @if(Auth::user()->hasPermission("client_permission") and $prospects != null)
    <div id="prospect-info" style="float: left; margin-right: 10px; padding-top: 5px; font-size: 22px; font-weight: bold;">
        Open Quotes: ${{ HomeHelper::OpenQuoteValue($prospects) }}
    </div>
    @endif

    @if(Auth::user()->hasPermission("vendor_permission") and $vendors != null)
    <div id="vendor-info" style="float: left; margin-right: 10px; padding-top: 5px; font-size: 22px; font-weight: bold;">
        Payables: ${{ HomeHelper::Payables($vendors) }}
    </div>
    @endif

    @if(Auth::user()->hasPermission("employee_permission") and $employees != null)
        @if(Auth::user()->hasPermission("payroll_permission") )
            <div id="employee-info" style="float: left; margin-right: 10px; padding-top: 5px; font-size: 22px; font-weight: bold;">
                Next Payroll End Date: {{ HomeHelper::NextPayrollEndDate()}}
            </div>
        @endif
    @endif

</div>
@elsedesktop

<div class="sidebar-nav" >
    <div class="navbar navbar-default" role="navigation" style="background-color: #eee; border: none; font-weight: bold; margin-bottom: 10px;">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#additemmenu">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <span class="visible-xs navbar-brand">Add New Contact</span>
        </div>
        <div class="navbar-collapse collapse sidebar-navbar-collapse" id="additemmenu">
            <ul class="nav navbar-nav">
                @if(Auth::user()->hasPermission("client_permission"))
                    @if($clients != null or $prospects != null)
                        @if(app()->make('account')->plan_name === "SOLO")
                            <li><a id="link" href="/Clients/Add">New {{ TextHelper::GetText("Client") }}</a></li>
                        @else
                            <li><a id="link" href="/Clients/Add">New {{ TextHelper::GetText("Client") }}/Prospect</a></li>
                        @endif
                    @endif
                @endif
                @if(Auth::user()->hasPermission("vendor_permission") and $vendors != null)
                    <li><a id="link" href="/Vendors/Add">New Vendor</a></li>
                @endif
                    @if(Auth::user()->hasPermission("employee_permission") and $employees != null)
                    <li><a id="link" href="/Employees/Add">New Team/Staff</a></li>
                    <li><a id="link" href="/Training">Training by Department</a></li>
                @endif
            </ul>
        </div>
    </div>
</div>
@enddesktop

<div id="view-tabs">
    <ul class="nav nav-tabs" role="tablist">
        @if(Auth::user()->hasPermission("client_permission") and $clients != null)
        <li role="presentation" class="active" style="padding-top: 5px;"><a href="#clients-tab" id="clients-tab-click" aria-controls="profile" role="tab" data-toggle="tab">{{ TextHelper::GetText("Clients") }}</a></li>
        @endif
        @if(Auth::user()->hasPermission("client_permission") and $prospects != null)
        <li role="presentation" style="padding-top: 5px;"><a href="#prospects-tab" id="prospects-tab-click" aria-controls="profile" role="tab" data-toggle="tab">{{ TextHelper::GetText("Prospects") }}</a></li>
        @endif
        @if(Auth::user()->hasPermission("vendor_permission") and $vendors != null)
        <li role="presentation" style="padding-top: 5px;"><a href="#vendors-tab" id="vendors-tab-click" aria-controls="profile" role="tab" data-toggle="tab">Vendors</a></li>
        @endif
        @if(Auth::user()->hasPermission("employee_permission") and $employees != null)
        <li role="presentation" style="padding-top: 5px;"><a href="#employees-tab" id="employees-tab-click" aria-controls="profile" role="tab" data-toggle="tab">Team\Staff</a></li>
        @endif
    </ul>

    <div class="tab-content" style="height: calc(100% - 50px);">
        @if(Auth::user()->hasPermission("client_permission") and $clients != null)
        <div role="tabpanel" class="tab-pane active" id="clients-tab">
            @include('OS.Home.clientstab')
        </div>
        @endif
        @if(Auth::user()->hasPermission("client_permission") and $prospects != null)
        <div role="tabpanel" class="tab-pane" id="prospects-tab">
            @include('OS.Home.prospectstab')
        </div>
        @endif

        @if(Auth::user()->hasPermission("vendor_permission") and $vendors != null)
        <div role="tabpanel" class="tab-pane" id="vendors-tab">
            @include('OS.Home.vendorstab')
        </div>
        @endif

        @if(Auth::user()->hasPermission("employee_permission") and $employees != null)
        <div role="tabpanel" class="tab-pane" id="employees-tab">
            @include('OS.Home.employeestab')
        </div>
        @endif
    </div>
</div>

<div class="modal fade" id="ShowNote" tabindex="-1" role="dialog" aria-labelledby="ShowNote" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="exampleModalLabel">Note:</h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script> 
$(document).ready(function() {

    $('.col-filter-check').change(function () {

        $type = $(this).data('type');
        $col = $(this).data('col');

        switch($type) {
            case "client":
                $tablecol = window.clientstable.column( $col + ":name" );
                break;
            case "prospect":
                $tablecol = window.prospectstable.column( $col + ":name" );
                break;
            case "vendor":
                $tablecol = window.vendorstable.column( $col + ":name" );
                break;
            case "employee":
                $tablecol = window.employeestable.column( $col + ":name" );
                break;
        }

        if($(this).prop('checked') === true){
            $tablecol.visible(true);
            $status = 1;
        }else{
            $tablecol.visible(false);
            $status = 0;
        }

        $data = {};
        $data['_token'] = "{{ csrf_token() }}";
        $data['type'] = $type;
        $data['col'] = $col;
        $data['status'] = $status;

        $post = $.post("/Home/ColSave", $data);

        $post.done(function (data) {
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

    $('#ShowNote').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var note = button.data('note'); // Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this);
        modal.find('.modal-body').html(note);
    });

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {

        var target = $(e.target).attr("href"); // activated tab
        switch(target) {
            case "#employees-tab":
                $("#client-info").css("display","none");
                $("#patient-info").css("display","none");
                $("#prospect-info").css("display","none");
                $("#vendor-info").css("display","none");

                $("#employee-info").css("display","Inline");
                break;
            case "#clients-tab":
                $("#prospect-info").css("display","none");
                $("#patient-info").css("display","none");
                $("#employee-info").css("display","none");
                $("#vendor-info").css("display","none");

                $("#client-info").css("display","Inline");
                $('#client-status').change();
                window.clientstable.columns.adjust().draw(false);
                break;
            case "#vendors-tab":
                $("#client-info").css("display","none");
                $("#patient-info").css("display","none");
                $("#prospect-info").css("display","none");
                $("#employee-info").css("display","none");

                $("#vendor-info").css("display","Inline");
                $('#vendor-status').change();
                window.vendorstable.columns.adjust().draw(false);
                break;
            case "#prospects-tab":
                $("#client-info").css("display","none");
                $("#patient-info").css("display","none");
                $("#employee-info").css("display","none");
                $("#vendor-info").css("display","none");

                $("#prospect-info").css("display","Inline");
                $('#prospect-status').change();
                window.prospectstable.columns.adjust().draw(false);
                break;
        }
    });

    @if(Auth::user()->hasPermission("employee_permission") and $employees != null)
    $("#employees-tab-click").click();
    @endif

    @if(Auth::user()->hasPermission("vendor_permission") and $vendors != null)
    $("#vendors-tab-click").click();
    @endif

    @if(Auth::user()->hasPermission("client_permission") and $prospects != null)
    $("#prospects-tab-click").click();
    @endif

    @if(Auth::user()->hasPermission("client_permission") and $clients != null)
    $("#clients-tab-click").click();
    @endif

});

</script> 
@stop