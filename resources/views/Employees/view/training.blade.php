<div class="row" style="margin-top: 20px;">
    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="training-search"><div>Search:</div></span>
            <input id="training-search" name="training-search" type="text" class="form-control" >
        </div>
    </div>

    <div class="col-md-6">
        {!! PageElement::TableControl('training') !!}
    </div>

    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="training-length"><div>Show:</div></span>
            <select id="training-length" name="training-length" type="text" placeholder="choice" class="form-control">
                <option value="10">10 entries</option>
                <option value="15">15 entries</option>
                <option value="20">20 entries</option>
                <option value="25">25 entries</option>
                <option value="50">50 entries</option>
                <option value="100">100 entries</option>
            </select>
        </div>
    </div>
</div>

<div class="row" style="margin-top: 20px;">
    <div class="col-md-2">
        <button data-toggle="modal" data-target="#AddTrainingModal" type="button" class="btn OS-Button" style="width: 100%;">Add New Training</button>
    </div>
    <div class="col-md-2">
        <button id="CompleteTrainingButton" type="button" class="btn OS-Button" style="width: 100%;" disabled>Mark Selected Training Complete</button>
    </div>
    <div class="col-md-2">
        <button id="DeleteTrainingButton" type="button" class="btn OS-Button" style="width: 100%;" disabled>Delete Selected Training</button>
    </div>
    <div class="col-md-2">
        <button id="EditTrainingButton" data-toggle="modal" data-target="#EditTrainingModal" type="button" class="btn OS-Button" style="width: 100%;" disabled>Edit Selected Training</button>
    </div>
    <div class="col-md-2">
        <button id="AttachFileToTraining" type="button" class="btn OS-Button" style="width: 100%;">Attach File To Selected Training</button>
    </div>
</div>

<table class="table" id="trainingtable">
    <thead>
        <tr>
            <th>id</th>
            <th>module id</th>
            <th>Modules</th>
            <th>Reminder Sent Date</th>
            <th>Scheduled Date</th>
            <th>Due Date</th>
            <th>Completed Date</th>
            <th>Attachment</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>id</th>
            <th>module id</th>
            <th>Modules</th>
            <th>Reminder Sent Date</th>
            <th>Scheduled Date</th>
            <th>Due Date</th>
            <th>Completed Date</th>
            <th>Attachment</th>
        </tr>
    </tfoot>
    <tbody>
    @foreach($employee->TrainingRequests as $TrainingRequest)
        <tr>
            <td>{{ $TrainingRequest->id }}</td>
            <td>{{ $TrainingRequest->TrainingModule->id }}</td>
            <td>{{ $TrainingRequest->TrainingModule->title }}</td>

            @if($TrainingRequest->getScheduleDetails() === null)
                <td>None Set</td>
                <td>None Set</td>
            @else
                @if($TrainingRequest->getScheduleDetails()->reminderdate === null)
                    <td>None Set</td>
                @else
                    <td>{{ $TrainingRequest->getScheduleDetails()->reminderdate->toDateString() }}</td>
                @endif
                <td>{{ $TrainingRequest->getScheduleDetails()->start->format('Y-m-d G:i') }}</td>
            @endif

            @if($TrainingRequest->due === null)
            <td>None Set</td>
            @else
            <td>{{ $TrainingRequest->due }}</td>
            @endif

            <td>{{ $TrainingRequest->Status() }}</td>
            <td style="padding-top: 3px; padding-bottom: 3px;">
                @if($TrainingRequest->filestore_id != null)
                    <button style="width: 100%; padding-top: 2px; padding-bottom: 2px;" type="button" class="btn OS-Button" data-toggle="modal" data-target="#filestore-display-model" data-fileid="{{ $TrainingRequest->filestore_id }}">Show Attachment</button>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="modal fade" id="AddTrainingModal" role="dialog" aria-labelledby="#AddInputModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="exampleModalLabel">Add Training:</h4>
            </div>
            <div class="modal-body">
                <div class="input-group">
                    <span class="input-group-addon">
                        <div style="width: 15em;">Training Module:</div>
                    </span>
                    <select id="training-modules-new" style="width: 100%">
                    @foreach($TrainingModules as $module)
                        @if($module->deleted_at === null)
                        <option value="{{ $module->id }}">{{ $module->title }}</option>
                        @endif
                    @endforeach
                    </select>
                </div>

                <div class="input-group">
                    <span class="input-group-addon">
                        <div style="width: 15em;">Schedule Date:</div>
                    </span>
                    <input id="training-schedule-date-new" style="width: 100%; z-index: 100000;" class="form-control" readonly>
                </div>

                <div class="input-group">
                    <span class="input-group-addon">
                        <div style="width: 15em;">Due Date:</div>
                    </span>
                    <input id="training-due-date-new" style="width: 100%; z-index: 100000;" class="form-control" readonly>
                </div>

                <div class="input-group">
                    <span class="input-group-addon">
                        <div style="width: 15em;">Reminder Date:</div>
                    </span>
                    <input id="training-reminder-date-new" style="width: 100%; z-index: 100000;" class="form-control" readonly disabled>
                </div>

            </div>
            <div class="modal-footer">
                <button id="training-notify-add" name="training-notify-add" type="button" class="btn OS-Button" value="">Notify Employee</button>
                <button id="training-add" name="training-add" type="button" class="btn OS-Button" value="">Add</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="EditTrainingModal" role="dialog" aria-labelledby="#AddInputModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="exampleModalLabel">Edit Training:</h4>
            </div>
            <div class="modal-body">
                <input id="training-id-edit"  name="input-label" class="form-control" style="display: none;">

                <div class="input-group">
                    <span class="input-group-addon">
                        <div style="width: 15em;">Schedule Date:</div>
                    </span>
                    <input id="training-schedule-date-edit" style="width: 100%; z-index: 100000;" class="form-control" readonly>
                </div>

                <div class="input-group">
                    <span class="input-group-addon">
                        <div style="width: 15em;">Due Date:</div>
                    </span>
                    <input id="training-due-date-edit" style="width: 100%; z-index: 100000;" class="form-control" readonly>
                </div>

                <div class="input-group">
                    <span class="input-group-addon">
                        <div style="width: 15em;">Reminder Date:</div>
                    </span>
                    <input id="training-reminder-date-edit" style="width: 100%; z-index: 100000;" class="form-control" readonly>
                </div>
            </div>
            <div class="modal-footer">
                <button id="training-notify-edit" name="training-notify-edit" type="button" class="btn OS-Button" value="">Notify Employee</button>
                <button id="training-edit" name="training-edit" type="button" class="btn OS-Button" value="">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        $('#EditTrainingModal').on('show.bs.modal', function (event) {

            $row = trainingtable.row('.selected').data();

            $rowdata = trainingtable.row('.selected').data();
            if ($rowdata === undefined || $rowdata === null) {
                event.stopPropagation();
                $.dialog({
                    title: 'Oops..',
                    content: 'Please Select a Row.'
                });
            }else{
                $('#training-id-edit').val($row['id']);

                if($row['scheduled_date'] === "None Set"){
                    $('#training-schedule-date-edit').val("");
                    $('#training-reminder-date-edit').prop('disabled', true);
                }else{
                    $('#training-schedule-date-edit').val($row['scheduled_date']);
                    $('#training-reminder-date-edit').prop('disabled', false);
                    if($row['reminder_sent_date'] === "None Set"){
                        $('#training-reminder-date-edit').val("");
                    }else{
                        $('#training-reminder-date-edit').val($row['reminder_sent_date']);
                    }
                }

                if($row['due_date'] === "None Set"){
                    $('#training-due-date-edit').val("");
                }else{
                    $('#training-due-date-edit').val($row['due_date']);
                }
            }
        });

        $('#training-modules-new').select2({
            theme: "bootstrap"
        });


        $('#training-schedule-date-new').datetimepicker({
            changeMonth: true,
            changeYear: true,
            controlType: 'select',
            parse: "loose",
            dateFormat: "yy-mm-dd",
            timeFormat: "HH:mm",
            onSelect: function(dateText, inst) {
                $('#training-reminder-date-new').val();
                $('#training-reminder-date-new').prop('disabled', false);
            }
        });

        $('#training-due-date-new').datepicker({
            changeMonth: true,
            changeYear: true,
            controlType: 'select',
            parse: "loose",
            dateFormat: "yy-mm-dd",
        });

        $('#training-reminder-date-new').datepicker({
            changeMonth: true,
            changeYear: true,
            controlType: 'select',
            parse: "loose",
            dateFormat: "yy-mm-dd",
            beforeShowDay: DisableSpecificDates
        });

        $('#training-schedule-date-edit').datetimepicker({
            changeMonth: true,
            changeYear: true,
            controlType: 'select',
            parse: "loose",
            dateFormat: "yy-mm-dd",
            timeFormat: "HH:mm",
            onSelect: function(dateText, inst) {
                $('#training-reminder-date-edit').val();
                $('#training-reminder-date-edit').prop('disabled', false);
            }
        });

        $('#training-due-date-edit').datepicker({
            changeMonth: true,
            changeYear: true,
            controlType: 'select',
            parse: "loose",
            dateFormat: "yy-mm-dd",
        });

        $('#training-reminder-date-edit').datepicker({
            changeMonth: true,
            changeYear: true,
            controlType: 'select',
            parse: "loose",
            dateFormat: "yy-mm-dd",
            beforeShowDay: DisableSpecificDatesEdit
        });

        var trainingtable = $('#trainingtable').DataTable({
            "pageLength": 10,
            "columns": [
                { "data": "id" },
                { "data": "module_id" },
                { "data": "module" },
                { "data": "reminder_sent_date" },
                { "data": "scheduled_date" },
                { "data": "due_date" },
                { "data": "completed_date" },
                { "data": "attachment" },
            ],
            "columnDefs": [
                {
                    "targets": [0,1],
                    "visible": false
                }
            ]
        });

        $('#trainingtable tbody').on( 'click', 'tr', function () {
            $row = $(this);
            if ( $(this).hasClass('selected') ) {
                $(this).removeClass('selected');
            }
            else {
                trainingtable.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }

            $row = trainingtable.row('.selected').data();
            if($row['completed_date'] === "Pending"){
                $('#CompleteTrainingButton').prop('disabled', false);
                $('#DeleteTrainingButton').prop('disabled', false);

                $('#EditTrainingButton').prop('disabled', false);
            }else{
                $('#CompleteTrainingButton').prop('disabled', true);
                $('#DeleteTrainingButton').prop('disabled', true);

                $('#EditTrainingButton').prop('disabled', true);
            }
        });


        $( "#training-previous-page" ).click(function() {
            trainingtable.page( "previous" ).draw('page');
            PageinateUpdate(trainingtable.page.info(), $('#training-next-page'), $('#training-previous-page'),$('#training-tableInfo'));
        });

        $( "#training-next-page" ).click(function() {
            trainingtable.page( "next" ).draw('page');
            PageinateUpdate(trainingtable.page.info(), $('#training-next-page'), $('#training-previous-page'),$('#training-tableInfo'));
        });

        $('#training-search').on( 'keyup change', function () {
            trainingtable.search( this.value ).draw();
            PageinateUpdate(trainingtable.page.info(), $('#training-next-page'), $('#training-previous-page'),$('#training-tableInfo'));
        });

        $('#training-length').on( 'change', function () {
            trainingtable.page.len( this.value ).draw();
            PageinateUpdate(trainingtable.page.info(), $('#training-next-page'), $('#training-previous-page'),$('#training-tableInfo'));
        });

        PageinateUpdate(trainingtable.page.info(), $('#training-next-page'), $('#training-previous-page'),$('#training-tableInfo'));

        $( "#generaltraining" ).children().find(".dataTables_filter").css('display', 'none');
        $( "#generaltraining" ).children().find(".dataTables_length").css('display', 'none');
        $( "#generaltraining" ).children().find(".dataTables_paginate").css('display', 'none');
        $( "#generaltraining" ).children().find(".dataTables_info").css('display', 'none');
        $('#trainingtable').css('width' , "100%");

        $('#training-add').click(function(){

            $("body").addClass("loading");

            $data = {};
            $data['_token'] = "{{ csrf_token() }}";
            $data['user_id'] = "{{ $employee->id }}";
            $data['trainingmodule_id'] = $('#training-modules-new').val();

            $data['training-schedule-date'] = $('#training-schedule-date-new').val();
            $data['training-due-date'] = $('#training-due-date-new').val();
            $data['training-reminder-date'] = $('#training-reminder-date-new').val();

            $data['status'] = $('#training-status-new').val();

            $post = $.post("/Training/Add", $data);

            $post.done(function( data ) {

                console.log(data);
                $("body").removeClass("loading");
                switch(data['status']) {
                    case "OK":

                        $rowdata = {};
                        $rowdata["id"] = data['TrainingRequest']['id'];
                        $rowdata["module_id"] = data['TrainingModule']['id'];
                        $rowdata["module"] = data['TrainingModule']['title'];

                        if(data['TrainingSchedule'] === null){
                            $rowdata["reminder_sent_date"] = "None Set";
                            $rowdata["scheduled_date"] = "None Set";
                        }else{
                            if(data['TrainingSchedule']['reminderdate'] === null){
                                $rowdata["reminder_sent_date"] = "None Set";
                            }else{
                                $rowdata["reminder_sent_date"] = data['TrainingSchedule']['reminderdate'];
                            }
                            $rowdata["scheduled_date"] = data['TrainingSchedule']['start'];
                        }

                        if(data['TrainingRequest']['due'] === null){
                            $rowdata["due_date"] = "None Set";
                        }else{
                            $rowdata["due_date"] = data['TrainingRequest']['due'];
                        }

                        $rowdata["completed_date"] = "Pending";

                        $rowdata["attachment"] = "";

                        $('#trainingtable').DataTable().row.add($rowdata).draw( false );
                        $('#AddTrainingModal').modal('hide');

                        PageinateUpdate(trainingtable.page.info(), $('#training-next-page'), $('#training-previous-page'),$('#training-tableInfo'));
                        break;
                    case "UserNotFound":
                    case "TrainingModuleNotFound":
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

            $post.fail(function() {
                NoReplyFromServer();
            });
        });

        $('#CompleteTrainingButton').click(function () {
            $.confirm({
                title: 'Are you sure you want to mark this complete?',
                buttons: {
                    confirm: function () {
                        $row = trainingtable.row('.selected').data();

                        $data = {};
                        $data['_token'] = "{{ csrf_token() }}";
                        $data['id'] = $row['id'];

                        $("body").addClass("loading");

                        $post = $.post("/Training/Complete", $data);

                        $post.done(function( data ) {
                            console.log(data);
                            $("body").removeClass("loading");
                            switch(data['status']) {
                                case "OK":

                                    $row = trainingtable.row('.selected').data();
                                    $row['completed_date'] = data['date'];
                                    trainingtable.row('.selected').data($row).draw( false );

                                    $('#CompleteTrainingButton').prop('disabled', true);
                                    $('#DeleteTrainingButton').prop('disabled', true);
                                    $('#EditTrainingButton').prop('disabled', true);
                                    break;
                                case "NotFound":
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

                        $post.fail(function()
                        {
                            $("body").removeClass("loading");
                            alert("Error");
                        });
                    },
                    cancel: function () {

                    },
                }
            });
        });

        $('#DeleteTrainingButton').click(function () {
            $.confirm({
                title: 'Are you sure you want to delete this?',
                buttons: {
                    confirm: function () {
                        $row = trainingtable.row('.selected').data();

                        $data = {};
                        $data['_token'] = "{{ csrf_token() }}";
                        $data['id'] = $row['id'];

                        $("body").addClass("loading");

                        $post = $.post("/Training/Delete", $data);

                        $post.done(function( data ) {
                            console.log(data);
                            $("body").removeClass("loading");
                            switch(data['status']) {
                                case "OK":

                                    $row = trainingtable.row('.selected').data();
                                    $row['completed_date'] = "Canceled";
                                    trainingtable.row('.selected').data($row).draw( false );

                                    $('#CompleteTrainingButton').prop('disabled', true);
                                    $('#DeleteTrainingButton').prop('disabled', true);
                                    $('#EditTrainingButton').prop('disabled', true);
                                    break;
                                case "NotFound":
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

                        $post.fail(function()
                        {
                            $("body").removeClass("loading");
                            alert("Error");
                        });
                    },
                    cancel: function () {

                    },
                }
            });
        });

        $('#training-edit').click(function(){

            $("body").addClass("loading");

            $data = {};
            $data['_token'] = "{{ csrf_token() }}";
            $data['id'] = $('#training-id-edit').val();

            $data['training-schedule-date'] = $('#training-schedule-date-edit').val();
            $data['training-due-date'] = $('#training-due-date-edit').val();
            $data['training-reminder-date'] = $('#training-reminder-date-edit').val();


            $post = $.post("/Training/Edit", $data);

            $post.done(function( data ) {

                console.log(data);
                $("body").removeClass("loading");
                switch(data['status']) {
                    case "OK":

                        $rowdata = trainingtable.row('.selected').data();

                        if(data['TrainingSchedule'] === null){
                            $rowdata["reminder_sent_date"] = "None Set";
                            $rowdata["scheduled_date"] = "None Set";
                        }else{
                            if(data['TrainingSchedule']['reminderdate'] === null){
                                $rowdata["reminder_sent_date"] = "None Set";
                            }else{
                                $rowdata["reminder_sent_date"] = data['TrainingSchedule']['reminderdate'];
                            }
                            $rowdata["scheduled_date"] = data['TrainingSchedule']['start'];
                        }

                        if(data['TrainingRequest']['due'] === null){
                            $rowdata["due_date"] = "None Set";
                        }else{
                            $rowdata["due_date"] = data['TrainingRequest']['due'];
                        }

                        $('#trainingtable').DataTable().row('.selected').data($rowdata).draw( false );
                        $('#EditTrainingModal').modal('hide');

                        PageinateUpdate(trainingtable.page.info(), $('#training-next-page'), $('#training-previous-page'),$('#training-tableInfo'));
                        break;
                    case "UserNotFound":
                    case "TrainingModuleNotFound":
                    case "TrainingRequestNotFound":
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

            $post.fail(function() {
                NoReplyFromServer();
            });
        });

        $('#AttachFileToTraining').click(function () {

            $rowdata = trainingtable.row('.selected').data();
            if ($rowdata === undefined || $rowdata === null) {
                $.dialog({
                    title: 'Oops..',
                    content: 'Please Select a Row.'
                });
            }else{
                $('#fileupload-modal').data('updatetype', 'trainingrequest');
                $('#fileupload-modal').data('updateid', $rowdata['id']);
                $('#fileupload-modal').modal('show');
            }

        });

    });

    function DisableSpecificDates(date) {

        var now = new Date($('#training-schedule-date-new').val());
        //now.setDate(now.getDate() - 1);
        if (date < now) {
            return [true];
        } else {
            return [false];
        }
    }

    function DisableSpecificDatesEdit(date) {

        var now = new Date($('#training-schedule-date-edit').val());
        //now.setDate(now.getDate() - 1);
        if (date < now) {
            return [true];
        } else {
            return [false];
        }
    }

</script>
