<div class="modal fade" id="NoteModal" role="dialog" aria-labelledby="NoteModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: none;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="NoteModal-Title">Event:</h4>
            </div>
            <div class="modal-body" style="padding-top: 0px;">
                <div id="view-tabs">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active" ><a href="#NoteModal-Event" aria-controls="profile" role="tab" data-toggle="tab">Event Details</a></li>
                        <li role="presentation"><a href="#NoteModal-Link" aria-controls="profile" role="tab" data-toggle="tab">Links & Reminders</a></li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="NoteModal-Event">
                            <div class="input-group " style="width: 100%; padding-bottom: 5px;">
                                <input style="z-index: 100000; width: 23%; float: left;" id="start-date"  name="start-date" class="form-control" readonly>
                                <input style="z-index: 100000; width: 23%; float: left;" id="start-time" name="start-time" class="form-control" readonly data-autoclose="true">
                                <div style="width: 8%; float: left; text-align: center; padding-top: 7px;"> To </div>
                                <input style="z-index: 100000; width: 23%; float: left;" id="end-date" name="end-date" class="form-control" readonly>
                                <input style="z-index: 100000; width: 23%; float: left;" id="end-time" name="end-time" class="form-control" readonly data-autoclose="true">
                            </div>

                            {!! Form::OSinput("title", "Title", "", "", "true", "") !!}

                            {!! Form::OStextarea("notes", "Note", "", "", "true", "") !!}

                            <div class="input-group ">
                                <span class="input-group-addon" for="event-userid"><div style="width: 15em;">User:</div></span>
                                <select id="event-userid" name="event-userid" class="form-control">
                                    <option value="0">None</option>
                                    @foreach(UserHelper::GetAllUsers() as $user)
                                        <option value="{{ $user->id }}">{{ $user->email }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="input-group ">
                                <span class="input-group-addon" for="repeat"><div style="width: 15em;">Repeat:</div></span>
                                <input type="checkbox" name="checkboxes" id="repeatcheck"  data-on="Repeat" data-off="Off" data-toggle="toggle" value="1" data-width="100%">
                            </div>

                            <div class="input-group" style="display: none;" id="repeat-container">
                                <span class="input-group-addon" for="repeat"><div style="width: 15em;">Repeat Frequency:</div></span>
                                <select id="repeat" name="repeat" class="form-control">
                                    <option value="1">Daily - Every Day</option>
                                    <option value="2">Daily - Weekday Only</option>
                                    <option value="3">Weekly</option>
                                    <option value="4">Monthly</option>
                                    <option value="5">Yearly</option>
                                </select>
                            </div>

                            <div class="input-group" style="display: none;" id="repeat-till-container">
                                <span class="input-group-addon" for="repeat"><div style="width: 15em;">Repeat Until:</div></span>
                                <input id="repeat-till" type="text" name="repeat-till" class="btn btn-default" style=" width:100%;">
                            </div>

                            <input style="display: none;" type="text" class="form-control" id="eventid">
                        </div>

                        <div role="tabpanel" class="tab-pane" id="NoteModal-Link">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <div style="width: 15em;">Linked Account:</div>
                                </span>
                                <select id="account" name="account" style="width: 100%">
                                    <option value="0" data-id="0" data-type="none" SELECTED>None</option>
                                    <optgroup label="Clients">
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" data-id="{{ $client->id }}" data-type="client" data-name="{{ $client->getName() }}">{{ $client->getName() }} - {{ $client->getPrimaryContactName() }}</option>
                                    @endforeach
                                    </optgroup>
                                    <optgroup label="Vendors">
                                    @foreach($vendors as $vendor)
                                        <option value="{{ $vendor->id }}" data-id="{{ $vendor->id }}" data-type="vendor" data-name="{{ $vendor->getName() }}">{{ $vendor->getName() }} - {{ $vendor->getPrimaryContactName() }}</option>
                                    @endforeach
                                    </optgroup>
                                </select>
                            </div>

                            <div class="input-group">
                                <span class="input-group-addon">
                                    <div style="width: 15em;">Reminder Date:</div>
                                </span>

                                <input id="reminder-date" class="form-control input-md" value="Click here to set date." readonly style="z-index: 100000;">

                            </div>

                            <div class="input-group">
                                <span class="input-group-addon">
                                    <div style="width: 15em;">Email Reminder To:</div>
                                </span>
                                <select id="email-to" name="email-to" style="width: 100%" multiple="multiple">
                                </select>
                            </div>

                            <input style="display: none;" type="text" class="form-control" id="linkedid" value="0">
                            <input style="display: none;" type="text" class="form-control" id="linkedtype" value="0">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: none;">
                <button id="copy" name ="copy" type="button" class="btn OS-Button" value="">Copy Date/Time</button>
                <button id="NotifyUser" name="NotifyUser" type="button" class="btn OS-Button" value="">Notify User</button>
                <button id="DeleteEvent" name="DeleteEvent" type="button" class="btn OS-Button" value="">Delete</button>
                <button id="SaveEvent" name="SaveEvent" type="button" class="btn OS-Button" value="">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
$( document ).ready(function() {

    $clientcontactdata = [];
    @foreach($clients as $client)
    var data = [
        @foreach($client->contacts as $contact)
            {
                id: "{{ $contact->email }}",
                text: '{{ $contact->firstname }} {{ $contact->lastname }} - {{ $contact->email }}'
            },
        @endforeach
        ];

        $clientcontactdata[{{ $client->id }}] = data;
    @endforeach

    $vendorcontactdata = [];
    @foreach($vendors as $vendor)
    var data = [
        @foreach($vendor->contacts as $contact)
            {
                id: "{{ $contact->email }}",
                text: '{{ $contact->firstname }} {{ $contact->lastname }} - {{ $contact->email }}'
            },
        @endforeach
        ];

        $vendorcontactdata[{{ $vendor->id }}] = data;
    @endforeach


    $('#account').select2({
        theme: "bootstrap"
    });

    $('#account').on('select2:select', function (e) {
        
        var data = e.params.data;

        $('#email-to').val(null).trigger('change');
        $("#email-to").html("");

        switch($(data['element']).data('type')) {
            case "client":
                $('#email-to').select2({
                    theme: "bootstrap",
                    data: $clientcontactdata[$(data['element']).data('id')]
                });

                $('#NoteModal-Title').html('Event for <a href="/Clients/View/'+$(data['element']).data('id')+'">' + $(data['element']).data('name') + '</s>');
                break;
            case "vendor":
                $('#email-to').select2({
                    theme: "bootstrap",
                    data: $vendorcontactdata[$(data['element']).data('id')]
                });

                $('#NoteModal-Title').html('Event for <a href="/Vendors/View/'+$(data['element']).data('id')+'">' + $(data['element']).data('name') + '</s>');
                break;
        }

        $("#linkedtype").val($(data['element']).data('type'));
        $("#linkedid").val($(data['element']).data('id'));

    });

    $('#email-to').select2({
        theme: "bootstrap"
    });

    $('#repeat-till').datepicker({
        changeMonth: true,
        changeYear: true,
        inline: true,
        onSelect: function(dateText, inst) {

            var d = new Date(dateText);
            $datetest = formatDate(d);
            $("#repeat-till").val($datetest);
        },
        beforeShowDay: DisableSpecificDates
    });


    $('#reminder-date').datepicker({
        changeMonth: true,
        changeYear: true,
        controlType: 'select',
        parse: "loose",
        dateFormat: "yy-mm-dd",
        beforeShowDay: DisableSpecificDatesAfter
    });


    $('#NoteModal').on('show.bs.modal', function (event) { 
        
        $event = $("#NoteModal").data("data-event");

        $('a[href$="#NoteModal-Event"]').click();

        $('#account').prop('disabled', false);
        $('#email-to').prop('disabled', false);

        if($event === "new"){
            $('#DeleteEvent').css('display','none');
            $('#NotifyUser').css('display','none');

            $("#eventid").val("0");
            $("#notes").val("");
            $("#title").val("");
            $('#repeatcheck').prop('disabled', false).prop('checked', false);
            $('#repeat').prop('disabled', true);
            $('#repeat-till').prop('disabled', true).val('Click here to select a date');

            $('#repeat-container').css('display' , 'none');
            $('#repeat-till-container').css('display' , 'none');

            @if(isset($mobileresourceid))
            $("#event-userid").val({{ $mobileresourceid }});
            @else
            $("#event-userid").val({{ Auth::user()->id  }});
            @endif

            var $currentdate = $('#calendar').fullCalendar('getDate');

            $('#start-date').val($currentdate.format('YYYY-MM-DD'));
            $('#start-time').val($currentdate.format('HH:mm'));
            $('#end-date').val($currentdate.add(2, 'hours').format("YYYY-MM-DD"));
            $('#end-time').val($currentdate.format("HH:mm"));

            $("#linkedid").val("0");
            $("#linkedtype").val("0");
            $('#NoteModal-Title').html('Event');
            $("#account").val(0);
            $('#account').trigger('change');

            $('#reminder-date').val("Click here to set date.");

            $('#email-to').val(null).trigger('change');
            $("#email-to").html("");
        }else{

            $('#DeleteEvent').css('display','inline-block');
            $('#NotifyUser').css('display','inline-block');

            $("#eventid").val($event['id']);
            $("#notes").val($event['note']);
            $("#title").val($event['title']);
            $("#event-userid").val($event['resourceId']);

            //$start = $event['start'].format("YYYY-MM-DD[T]HH:mm");
            //$("#start").val($start);

            $('#start-date').val(moment($event['start']).format('YYYY-MM-DD'));
            $('#start-time').val(moment($event['start']).format('HH:mm'));

            if($event['end'] === null){//brand new events have a null end(if its null use start + 2 hours)
                $end = $event['start'].add(2, 'hours');
                $("#end-date").val($end.format("YYYY-MM-DD"));
                $("#end-time").val($end.format("HH:mm"));
            }else{
                $('#end-date').val(moment($event['end']).format('YYYY-MM-DD'));
                $('#end-time').val(moment($event['end']).format('HH:mm'));
            }

            if($event['repeats'] === 1){
                $('#repeat').prop('disabled', true);
                $('#repeat').val($event['repeat_till']);

                $('#repeatcheck').prop('disabled', true);
                $('#repeatcheck').prop('checked', true);

                $('#repeat-till').prop('disabled', true);
                $('#repeat-till').val('');

                $('#repeat-container').css('display' , 'table');
                $('#repeat-till-container').css('display' , 'table');
            }else{
                $('#repeatcheck').prop('disabled', false);
                $('#repeatcheck').prop('checked', false);

                $('#repeat').prop('disabled', true);
                $('#repeat-till').prop('disabled', true);
                $('#repeat-till').val('Click here to select a date');

                $('#repeat-container').css('display' , 'none');
                $('#repeat-till-container').css('display' , 'none');
            }

            
            $("#linkedid").val("0");
            $("#linkedtype").val("0");
            $('#NoteModal-Title').html('Event');
            $("#account").val("None");
            $('#account').trigger('change');
            $('#email-to').val(null).trigger('change');
            $("#email-to").html("");

            switch($event['linktype']) {
                case "patient":
                    $("#linkedid").val($event['linkid']);
                    $("#linkedtype").val("patient");

                    $('#account').prop('disabled', true);
                    $('#email-to').prop('disabled', true);
                    $('#reminder-date').prop('disabled', true);

                    $('#NoteModal-Title').html('Apointment: <a href="/Clients/View/'+$event['client_id']+'">' + $event['title'] + '</a>');
                    break;
                case "client":
                    $("#linkedid").val($event['client_id']);
                    $("#linkedtype").val("client");
                    $("#account").val($event['client_id']);
                    $('#account').trigger('change');

                    $('#email-to').select2({
                        theme: "bootstrap",
                        data: $clientcontactdata[$event['client_id']]
                    });

                    $('#NoteModal-Title').html('Event for <a href="/Clients/View/'+$event['client_id']+'">' + $event['linkname'] + '</a>');
                    break;
                case "vendor":
                    $("#linkedid").val($event['vendor_id']);
                    $("#linkedtype").val("vendor");
                    $("#account").val($event['vendor_id']);
                    $('#account').trigger('change');

                    $('#email-to').select2({
                        theme: "bootstrap",
                        data: $vendorcontactdata[$event['vendor_id']]
                    });


                    $('#NoteModal-Title').html('Event for <a href="/Vendors/View/'+$event['vendor_id']+'">' + $event['linkname'] + '</s>');
                    break;
                case "training":
                    $("#linkedid").val($event['linkid']);
                    $("#linkedtype").val("training");

                    $('#account').prop('disabled', true);
                    $('#email-to').prop('disabled', true);

                    $('#NoteModal-Title').html('Training Event: ' + $event['linkname']);
                    break;
                default:
                // code block
            }

            if($event['reminderdate'] === null){
                $('#reminder-date').val('Click here to set date.');
            }else{
                
                $('#reminder-date').val(moment($event['reminderdate']['date']).format('YYYY-MM-DD'));
            }

            $('#email-to').val($event['reminderemails']);
            $('#email-to').trigger('change');

        }


    });

    $('#start-date').datepicker({
        changeMonth: true,
        changeYear: true,
        controlType: 'select',
        parse: "loose",
        dateFormat: "yy-mm-dd",
    });

    $('#end-date').datepicker({
        changeMonth: true,
        changeYear: true,
        controlType: 'select',
        parse: "loose",
        dateFormat: "yy-mm-dd",
    });

    $('#start-time').clockpicker();
    $('#end-time').clockpicker();

    $('#start-date').change(function () {
        $startdate = moment($('#start-date').val());
        $enddate = moment($('#end-date').val());

        if($startdate.isAfter($enddate)){
            $('#end-date').val($startdate.format('YYYY-MM-DD'));
        }

    });

    $('#end-date').change(function () {
        $startdate = moment($('#start-date').val());
        $enddate = moment($('#end-date').val());

        if($enddate.isBefore($startdate)){
            $('#start-date').val($enddate.format('YYYY-MM-DD'));
        }
    });


    $("#NotifyUser1").click(function(e) {

        $event = $("#NoteModal").data("data-event");

        $subject = "New item on your schedule";
        $message = "Start: " + $event['start'].format('YYYY-MM-DD HH:mm:ss') + "\r\n Title: " + $event['title'];

        SendNotificationandEmail($subject, $message, $event['resourceId']);

    });

    $("#NotifyUser").click(function(e) {

        $("body").addClass("loading");

        $event = $("#NoteModal").data("data-event");

        $data = {};
        $data['_token'] = "{{ csrf_token() }}";
        $data['id'] = $event['id'];

        $event = $("#NoteModal").data("data-event");

        $subject = "New item on your schedule";
        $message = "Start: " + $event['start'].format('YYYY-MM-DD HH:mm:ss') + "\r\n Title: " + $event['title'] + "\r\n Note: " + $event['note'];

        SendNotificationandEmail($subject, $message, $event['resourceId']);

    });

    $("#copy").click(function(e) {
        var options = {  weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };

        $event = $("#NoteModal").data("data-event");
        $eventdatetime = new Date($event['start'].format('YYYY-MM-DD HH:mm:ss')).toLocaleTimeString('en-us', options);;
        var dummy = document.createElement("textarea");
        document.body.appendChild(dummy);
        dummy.value = $eventdatetime;
        dummy.select();
        document.execCommand("copy");
        document.body.removeChild(dummy);

        alert('Date and time copied to clipboard');
    });
    
    $("#repeatcheck").change(function(e) {
        if($("#repeatcheck").is(":checked")){
            $('#repeat').prop('disabled', false);
            $('#repeat-till').prop('disabled', false);

            $('#repeat-container').css('display' , 'table');
            $('#repeat-till-container').css('display' , 'table');
        }else{
            $('#repeat').prop('disabled', true);
            $('#repeat-till').prop('disabled', true);

            $('#repeat-container').css('display' , 'none');
            $('#repeat-till-container').css('display' , 'none');
        }
    });
    
    $("#GoToLink").click(function(e) {
        if($("#linkedid").val() !== "0"){
            if($("#linkedtype").val() === "vendor"){
                GoToPage("/Vendors/View/" + $("#linkedid").val());
            }
            if($("#linkedtype").val() === "client"){
                GoToPage("/Clients/View/" + $("#linkedid").val());
            }
        }else{
            $.dialog({
                title: 'Oops...',
                content: 'No Link Selected'
            });
        }
    });
    
 
    
    $("#SaveEvent").click(function(e) {

        $id = parseInt($("#eventid").val());
        if($id === 0){
            var $event = {};
        }else{
            $event = $("#calendar").fullCalendar('clientEvents', $id)[0];
        }

        $event['title'] =  $("#title").val();
        $event['note'] =  $("#notes").val();
        $event['resourceId'] =  parseInt($("#event-userid").val());
        $event['start'] =  moment($("#start-date").val() + " " + $('#start-time').val());
        $event['end'] =  moment($("#end-date").val() + " " + $('#end-time').val());

        

        $event['linkid'] = $("#linkedid").val();
        $event['linktype'] = $("#linkedtype").val();
        $event['reminderdate'] = $('#reminder-date').val();
        $event['reminderemails'] = [];
        $.each($('#email-to').select2('data'), function(){
            $event['reminderemails'].push(this['id']);
        });
        
        if ($('#repeatcheck').is(':checked')) {
            $event['repeats'] = 1;
            $event['repeat_freq'] = $("#repeat").val();
            $event['repeat_till'] =  $("#repeat-till").val();
        }else{
            $event['repeats'] = 0;
            $event['repeat_freq'] = 0;
        }

        if($event['repeat_till'] === 'Click here to select a date'){
            $("#repeat-till").addClass('invalid');

            $.dialog({
                title: 'Oops...',
                content: 'Please select an end date.'
            });

        }else{

            if ($('#repeatcheck').is(':checked')) {
                $('#repeat').prop('disabled', true);
                $('#repeatcheck').prop('disabled', true);
                $('#repeat-till').prop('disabled', true);
            }

            $('#NoteModal').modal('hide');
            console.log($event);
            $newevent = BuildEvent($event);
            SaveEventData($newevent, $event, false);

        }

    });
    
    $("#DeleteEvent").click(function(e) {

        $event = $("#NoteModal").data("data-event");

        if($event['repeats'] === 1){

            $.confirm({
                title: "This event is part of a chain of repeating events, would you like to delete just this occurrence or all occurrences?",
                buttons: {
                    "JUST THIS OCCURRENCE": function() {
                        deleteEvent($event['id'], 0);
                    },
                    "All OCCURRENCES": function() {
                        deleteEvent($event['id'], 1);
                    },
                    cancel: function() {
                        // nothing to do

                    }
                }
            });

        }else{

            $.confirm({
                title: "Are you sure you want to delete this event?",
                buttons: {
                    confirm: function() {
                        deleteEvent($event['id'], 0);
                    },
                    cancel: function() {
                        // nothing to do

                    }
                }
            });
        }


    });

    function deleteEvent($id, $repeat){
        $("body").addClass("loading");

        posting = $.post("/Scheduling/Delete",
            {
                _token: "{{ csrf_token() }}",
                id: $id,
                repeat: $repeat,
            });

        posting.done(function( data ) {

            $("body").removeClass("loading");
            console.log('data', data);
            if(data === "done"){
                $('#calendar').fullCalendar( 'removeEvents' );
                $('#calendar').fullCalendar( 'refetchEvents' );
            }else{
                $.dialog({
                    title: 'Oops...',
                    content: 'Unknown Error: please refresh the schedule and try again.'
                });
            }
        });

        posting.fail(function() {
            $("body").removeClass("loading");
            bootstrap_alert.warning("Failed to delete event", 'danger', 4000);
        });
    }
});


function DisableSpecificDates(date) {

    var now = new Date();
    //now.setDate(now.getDate() - 1);
    if (date < now) {
        return [false];
    } else {
        return [true];
    }
}

function DisableSpecificDatesAfter(date) {

    $startdate = $('#start-date').val();
    var now = new Date($startdate);
    //now.setDate(now.getDate() - 1);
    if (date > now) {
        return [false];
    } else {
        return [true];
    }
}
</script>

@include('Scheduling.Modals.LinkedAccountModel')