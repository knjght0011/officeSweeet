@extends('master')

@section('content')

    <h3 style="margin-top: 10px;">User Management</h3>

<div class="row" style="margin-bottom: 5px;">
    <div style="float:left; width: 10em;  margin-left: 20px;">
        <button style="width: 100%;" id="ClockIn" name="save" type="button" class="btn OS-Button col-md-2">Clock In</button>
    </div>
    <div style="float:left; width: 10em;  margin-left: 20px;">
        <button style="width: 100%;" id="ClockOut" name="save" type="button" class="btn OS-Button col-md-2">Clock Out</button>
    </div>
    <div style="float:left; width: 10em;  margin-left: 20px;">
        <button style="width: 100%;" type="button" class="btn OS-Button" data-toggle="modal" data-target="#ShowHelpHub" data-tab="wiki" data-url="k-PXW-ul0Fo">Show Tutorial</button>
    </div>
</div> 

<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#training" aria-controls="profile" role="tab" data-toggle="tab">Training Schedule</a></li>
    <li role="presentation"><a href="#changepassword" aria-controls="profile" role="tab" data-toggle="tab">Settings</a></li>
    <li role="presentation"><a href="#schedulerevents" aria-controls="profile" role="tab" data-toggle="tab">Scheduler Events</a></li>
</ul>

<div class="tab-content" style="height: calc(100% - 42px - 36px - 5px - 46px);">

    <div role="tabpanel" class="tab-pane active" id="training" style="padding: 10px; height: 100%;">
        @include('Account.tabs.training')
    </div>

    <div role="tabpanel" class="tab-pane " id="schedulerevents">
        @include('Account.tabs.scheduler')
    </div>

    <div role="tabpanel" class="tab-pane " id="changepassword">
        @include('Account.tabs.changepassword')
    </div>

</div>

<script>
    
    var offset = new Date().getTimezoneOffset();
    console.log(offset);
    
    $("#ClockIn").click(function()
    {
        $("body").addClass("loading");
        var get = $.get( "/Account/Clock/In", function(  ) { });

        get.done(function( data ) {
            $("body").removeClass("loading");
            $.dialog({
                title: 'Clocked in at:',
                content: data,
            });
        }); 
    });
    
    $("#ClockOut").click(function()
    {
        $("body").addClass("loading");
        var get = $.get( "/Account/Clock/Out", function(  ) { });

        get.done(function( data ) {
            $("body").removeClass("loading");
            $.dialog({
                title: 'Clocked out at:',
                content: data,
            });
        });
    });
 

</script>
@stop