<button style=" margin-top: 10px;" id="save-details-tab" name="save-details-tab" type="button" class="btn OS-Button col-md-6 col-md-offset-3">Save Your Work Before Leaving</button>
<div class="row">
    <div class="col-md-5" style="margin-top: 10px;">
        {!! Form::OSdatepicker("date_of_introduction", "Date of introduction", $client->date_of_introduction) !!}
        {!! Form::OSinput("mainnumber", "Main Number", $client->phonenumber) !!}
        {!! Form::OSinput("mainemail", "Main email", $client->email) !!}
        {!! Form::OSinput("current_solution", "Current Solution", "", $client->current_solution) !!}
        {!! Form::OSinput("budget", "Budget", "", $client->budget) !!}
        {!! Form::OSinput("decision_maker", "Decision Maker", "", $client->decision_maker) !!}
        {!! Form::OSinput("referral_source", "Referral Source", "", $client->referral_source) !!}
        <div class="input-group ">
            <span class="input-group-addon" for="custom_field_text" style="padding-top: 0px; padding-bottom: 0px;">
                <input style="width: 15em; height: 30px;" id="custom_field_label" name="custom_field_label" type="text" placeholder="Custom Label" value="{{ SettingHelper::GetSetting("client-custom-label") }}" class="form-control">
            </span>
            <input id="custom_field_text" name="custom_field_text" type="text" placeholder="Custom Field" value="{{ $client->custom_field_text }}" class="form-control">
        </div>

        <div class="input-group ">
            <span class="input-group-addon" for="follow_up_date"><div style="width: 15em;">Follow Up Date:</div></span>
            <input id="follow_up_date" name="follow_up_date" type="text" placeholder="click to set" value="{{ $client->follow_up_date }}" class="form-control" readonly>
            <span class="input-group-btn">
                <button id="add-to-schedule" class="btn btn-default" type="button">Add To Schedule</button>
            </span>
        </div>
    </div>
    <div class="col-md-7" style="margin-top: 10px;">
        {!! Form::OSselect("assigned_to", "Acquired by or assigned to", "users") !!}
        {!! Form::OSinput("problem_pain", "Problem/Pain", "", $client->problem_pain) !!}
        {!! Form::OSinput("resistance_to_change", "Resistance to change", "", $client->resistance_to_change) !!}
        {!! Form::OSinput("priorities", "Priorities", "", $client->priorities) !!}
        <div class="input-group ">
            <span class="input-group-addon" for="custom_field_text2" style="padding-top: 0px; padding-bottom: 0px;">
                <input style="width: 15em; height: 30px;" id="custom_field_label2" name="custom_field_label2" type="text" placeholder="Custom Label" value="{{ SettingHelper::GetSetting("client-custom-label2") }}" class="form-control">
            </span>
            <input id="custom_field_text2" name="custom_field_text2" type="text" placeholder="Custom Field" value="{{ $client->custom_field_label }}" class="form-control">
        </div>
        <div class="input-group ">
            <span class="input-group-addon" for="comments"><div style="width: 15em;">Comments:</div></span>
            <textarea id="comments" name="comments" type="text" class="form-control" style="resize: none;" rows="3">{{ $client->comments }}</textarea>
        </div>
    </div>
</div>

<div class="modal fade" id="scheduler-NoteModal" role="dialog" aria-labelledby="NoteModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: none;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="scheduler-NoteModal-Title">Event:</h4>
            </div>
            <div class="modal-body" style="padding-top: 0px;">
                <div class="input-group " style="width: 100%; padding-bottom: 5px;">
                    <input style="z-index: 100000; width: 23%; float: left;" id="scheduler-start-date" name="start-date" class="form-control" readonly>
                    <input style="z-index: 100000; width: 23%; float: left;" id="scheduler-start-time" name="start-time" class="form-control" readonly data-autoclose="true">
                    <div style="width: 8%; float: left; text-align: center; padding-top: 7px;"> To </div>
                    <input style="z-index: 100000; width: 23%; float: left;" id="scheduler-end-date" name="end-date" class="form-control" readonly>
                    <input style="z-index: 100000; width: 23%; float: left;" id="scheduler-end-time" name="end-time" class="form-control" readonly data-autoclose="true">
                </div>

                {!! Form::OSinput("scheduler-title", "Title", "", "", "true", "") !!}

                {!! Form::OStextarea("scheduler-notes", "Note", "", "", "true", "") !!}

                <div class="input-group ">
                    <span class="input-group-addon" for="event-userid"><div style="width: 15em;">User:</div></span>
                    <select id="scheduler-event-userid" name="event-userid" class="form-control">
                        <option value="0">None</option>
                        @foreach(UserHelper::GetAllUsers() as $user)
                            <option value="{{ $user->id }}">{{ $user->email }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer" style="border-top: none;">
                <button id="scheduler-SaveEvent" name="SaveEvent" type="button" class="btn OS-Button" value="">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script>
$(document).ready(function() {

    $('#follow_up_date').datepicker({
        changeMonth: true,
        changeYear: true,
        controlType: 'select',
        parse: "loose",
        dateFormat: "yy-mm-dd",
    });

    $('#add-to-schedule').click(function () {

        $date = $(this).parent().parent().find('input').val();
        if ($date.length < 1)
        {
            alert('Please enter a follow up date first');
        }else{

            $("#scheduler-notes").val("");
            $("#scheduler-title").val("Follow Up");

            $('#scheduler-start-date').val($date);
            $('#scheduler-start-time').val("12:00");
            $('#scheduler-end-date').val($date);
            $('#scheduler-end-time').val("14:00");

            $('#scheduler-NoteModal').modal('show');
        }
    });

    $("#assigned_to").prepend("<option value='none'>None</option>");
    
    @if($client->assigned_to === null)
        $('#assigned_to').val("none");
    @else
        $('#assigned_to').val("{{ $client->assigned_to }}");
    @endif
    
    
    $( "#save-details-tab" ).click(function() {       
        $date_of_introduction = $val = moment($('#date_of_introduction').val()).format('YYYY-MM-DD HH:mm:ss');
        $mainnumber = $('#mainnumber').val();
        $mainemail = $('#mainemail').val();
        $current_solution = $('#current_solution').val();
        $budget = $('#budget').val();
        $decision_maker = $('#decision_maker').val();
        $referral_source = $('#referral_source').val();
        $assigned_to = $('#assigned_to').val();
        $problem_pain = $('#problem_pain').val();
        $resistance_to_change = $('#resistance_to_change').val();
        $priorities = $('#priorities').val();
        $comments = $('#comments').val();   

        $customfieldlabel = $('#custom_field_label').val();
        $customfieldtext = $('#custom_field_text').val();

        $customfieldlabel2 = $('#custom_field_label2').val();
        $customfieldtext2 = $('#custom_field_text2').val();

        $followupdate = $('#follow_up_date').val();
        
        $("body").addClass("loading");
        posting = $.post("/Clients/Update/Details",
        {
            _token: "{{ csrf_token() }}",
            id: "{{ $client->id }}",
            date_of_introduction: $date_of_introduction,
            current_solution: $current_solution,
            budget: $budget,
            decision_maker: $decision_maker,
            referral_source: $referral_source,
            assigned_to: $assigned_to,
            problem_pain: $problem_pain,
            resistance_to_change: $resistance_to_change,
            priorities: $priorities,
            comments: $comments,
            customfieldlabel: $customfieldlabel,
            customfieldtext: $customfieldtext,
            customfieldlabel2: $customfieldlabel2,
            customfieldtext2: $customfieldtext2,
            followupdate: $followupdate,
            mainnumber: $mainnumber,
            email: $mainemail
        });

        posting.done(function( data ) {
            $("body").removeClass("loading");
            if ($.isNumeric(data)) 
            {
                $.dialog({
                    title: 'Success!',
                    content: 'Data Saved.'
                });                
            }else{
                //server validation errors
                ServerValidationErrors(data);
            }
        });

        posting.fail(function() {
            $("body").removeClass("loading");
            $.dialog({
                title: 'Error!',
                content: 'Lost contact with server.'
            });
        });        
    });

    $('#scheduler-SaveEvent').click(function () {

        $event = {};
        $event['id'] = 0;
        $event['title'] =  $("#scheduler-title").val();
        $event['note'] =  $("#scheduler-notes").val();
        $event['userid'] = parseInt($("#scheduler-event-userid").val());

        $event['start'] =  moment($("#scheduler-start-date").val() + " " + $('#scheduler-start-time').val()).format('YYYY-MM-DD HH:mm:ss');
        $event['end'] =  moment($("#scheduler-end-date").val() + " " + $('#scheduler-end-time').val()).format('YYYY-MM-DD HH:mm:ss');

        $event['linkedid'] = "{{ $client->id }}";
        $event['linkedtype'] = "client";

        $event['reminderdate'] = "Click here to set date.";
        $event['reminderemails'] = [];

        $event['repeats'] = 0;
        $event['repeat_freq'] = 0;

        $("body").addClass("loading");

        posting = $.post("/Scheduling/Save",
            {
                _token: "{{ csrf_token() }}",
                event: $event
            });

        posting.done(function( data ) {

            $("body").removeClass("loading");
            switch(data['status']) {
                case "OK":
                    $('#NoteModal').modal('hide');
                    $.confirm({
                        autoClose: 'Close|1000',
                        title: "Success!",
                        content: 'Saved',
                        backgroundDismiss: true,
                        buttons: {
                            Close: function () {

                            }
                        }
                    });
                    break;
                case "error":
                    ServerValidationErrors(data);
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
            alert('failed try again');
        });
    });
});
</script>