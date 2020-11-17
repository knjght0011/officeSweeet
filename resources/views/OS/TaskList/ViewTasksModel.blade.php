<div class="modal fade" id="ViewTasksModal" tabindex="-1" role="dialog" aria-labelledby="ViewTasksModal"
     aria-hidden="true">
    <div class="modal-dialog" style="width: 1200px;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="exampleModalLabel">View Tasks/Projects:</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-2" style="padding-right: 5px;">
                        <button style="width: 100%;" data-toggle="modal" data-target="#AddTasksModal" type="button"
                                data-function="add" class="btn OS-Button">Add New Task/Project
                        </button>
                    </div>
                    <div class="col-md-2" style="padding: 0px;">
                        <button id="edit-task" style="width: 100%;" data-toggle="modal" data-target="#AddTasksModal"
                                type="button" data-function="edit" class="btn OS-Button">Edit Selected
                        </button>
                    </div>
                    <div class="col-md-2" style="padding: 0px; padding-left: 5px;">
                        <button style="width: 100%;" type="button" class="btn OS-Button" id="task-markcomplete">
                            Mark Task/Project Complete
                        </button>
                    </div>
                    <div class="col-md-3">
                        <select id="viewstatus" name="viewstatus" type="text" placeholder="" class="form-control">
                            <option value="notcomplete">All Pending</option>
                            <option style="background-color: #d9edf7;" value="Important">Important</option>
                            <option style="background-color: #fcf8e3;" value="Urgent">Urgent</option>
                            <option style="background-color: #f2dede;" value="Critical">Critical</option>
                            <option style="background-color: #dff0d8;" value="Complete">Complete</option>
                            @foreach (TaskListHelper::GetStatusList() as $status)
                                <option value="{{ $status }}">{{ $status }}</option>
                            @endforeach
                            <option value="all">All</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select id="task-user" name="user" type="text" placeholder="" class="form-control">
                            @foreach(UserHelper::GetAllUsers() as $user)
                                <option value="{{ $user->id }}">{{ $user->getShortName() }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row" style="margin-top: 5px;">
                    <div class="col-md-12">
                        {!! PageElement::TableControl('tasklist') !!}
                    </div>
                </div>
                <br>
                <table id="viewtaskstable" class="table">
                    <thead>
                    <tr id="head">
                        <th>ID</th>
                        <th>USER_ID</th>
                        <th>Task Name</th>
                        <th>Description</th>
                        <th width="128px">Date Assigned</th>
                        <th width="128px">Due Date</th>
                        <th width="128px">Complete Date</th>
                        <th>Status</th>
                        <th width="128px">Due Date ALL</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr id="head">
                        <th>ID</th>
                        <th>USER_ID</th>
                        <th>Task Name</th>
                        <th>Description</th>
                        <th width="128px">Date Assigned</th>
                        <th width="128px">Due Date</th>
                        <th width="128px">Complete Date</th>
                        <th>Status</th>
                        <th width="128px">Due Date ALL</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    @foreach(TaskListHelper::GetAllTasks() as $task)
                        <tr class="{{ $task->statusCSS() }}">
                            <td>{{ $task->id }}</td>
                            <td>{{ $task->user_id }}</td>
                            <td>{{ $task->taskname }}</td>
                            <td>{{ $task->description }}</td>
                            <td>{{ $task->formatDate_created_at_iso() }}</td>
                            <td>{{ $task->getDueDate() }}</td>
                            <td>{{ $task->formatDate() }}</td>
                            <td>{{ $task->status }}</td>
                            <td>{{ $task->getDueDateAll() }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="AddTasksModal" tabindex="-1" role="dialog" aria-labelledby="AddTasksModal"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="exampleModalLabel">Add Task\Project:</h4>
            </div>
            <div class="modal-body">

                {!! Form::OSselect("user", "User", "users", "", 0, "false", "") !!}

                {!! Form::OSinput("taskname", "Task Name", "", "", "true", "") !!}

                {!! Form::OStextarea("taskdescription", "Description", "", "", "true", "") !!}

                <div class="input-group ">
                    <span class="input-group-addon" for="task-status"><div style="width: 15em;">Status:</div></span>
                    <input id="task-status" type="text" name="task-status" list="task-status-list" class="form-control">
                    <datalist id="task-status-list" name="task-status-list">
                        <option style="background-color: #d9edf7;" value="Important">Important</option>
                        <option style="background-color: #fcf8e3;" value="Urgent">Urgent</option>
                        <option style="background-color: #f2dede;" value="Critical">Critical</option>
                        <option style="background-color: #dff0d8;" value="Complete">Complete</option>
                        @foreach (TaskListHelper::GetStatusList() as $status)
                            <option value="{{ $status }}">{{ $status }}</option>
                        @endforeach
                    </datalist>
                </div>
                <div class="input-group ">
                    <span class="input-group-addon" for="duedate"><div style="width: 15em;">Project/Task:</div></span>
                    <input type="checkbox" name="checkboxes" id="IsProject" checked="true" data-on="Project" data-off="Task" data-toggle="toggle" data-width="100%">
                </div>
                <div class="input-group ">
                    <span class="input-group-addon" for="duedate"><div style="width: 15em;">Project Deadline:</div></span>
                    <input id="duedate" name="duedate" type="text" class="form-control" style="z-index: 10000;" readonly>
                </div>

                <input id="taskid" style="display: none;" name="taskid" type="text" value="">
            </div>
            <div class="modal-footer">
                <button id="save-task" name="save-task" type="button" class="btn OS-Button" value="">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        $('#duedate').datetimepicker({
            controlType: 'select',
            parse: "loose",
            dateFormat: "yy-mm-dd",
            timeFormat: "HH:mm",
        });


        $('#AddTasksModal').on('show.bs.modal', function (event) {

            var button = $(event.relatedTarget);
            if (button.data("function") === "edit") {
                $row = window.viewtaskstable.row('.selected2').data();
                if ($row === undefined) {
                    $.dialog({
                        title: 'Oops...',
                        content: 'Please select a task to edit first.'
                    });
                    //alert("please select a row to edit first");
                    event.stopPropagation();
                    event.stopImmediatePropagation();
                    return false;
                } else {
                    $mode = "edit";
                    $('#ViewTasksModal').modal('hide');
                    $('#taskid').val($row[0]);
                    $('#user').val($row[1]);
                    $('#taskname').val($row[2]);
                    //$('#duedate').val($row[2]);
                    $('#taskdescription').val($row[3]);
                    $('#task-status').val($row[7]);
                    if($row[8] === "None Set"){
                        $('#duedate').val("");
                    }else{
                        $('#duedate').val($row[8]);
                    }

                }
            }
            if (button.data("function") === "add") {
                $mode = "new";
                $('#ViewTasksModal').modal('hide');
                $('#taskid').val("0");
                $('#user').val($('#task-user').val());
                $('#taskname').val("");
                //$('#duedate').val("");
                $('#taskdescription').val("");
                $('#task-status').val('');
                $('#duedate').val('');
                $('#IsProject').val('');
            }
        });

        $('#task-markcomplete').click(function () {

            $row = window.viewtaskstable.row('.selected2').data();

            if ($row === undefined) {
                $.dialog({
                    title: 'Oops...',
                    content: 'Please select a task to complete first.'
                });
            }else{

                $data = {};
                $data['_token'] = "{{ csrf_token() }}";


                $data['id'] = $row[0];

                $("body").addClass("loading");

                $post = $.post("/Tasklist/MarkComplete", $data);

                $post.done(function (data) {
                    $("body").removeClass("loading");
                    switch(data['status']) {
                        case "OK":
                            $split = data['returnString'].split("|");

                            window.viewtaskstable.row('.selected2').data($split);

                            window.viewtaskstable.draw();

                            if ( $("#viewstatus option[value='"+ $split[6] +"']").length == 0 ){
                                $('#viewstatus').append($('<option>', {
                                    value: $split[6],
                                    text: $split[6]
                                }));
                            }

                            PageinateUpdate(window.viewtaskstable.page.info(), $('#tasklist-next-page'), $('#tasklist-previous-page'),$('#tasklist-tableInfo'));

                            SavedSuccess($content = 'Saved.', $title = "Success!", $timeout = "2000");
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
            }

        });

        $('#save-task').click(function (e) {

            //Rich - if IsProject checked require deadline + add event to schedule every day
            var $data = new Object();
            $data._token = "{{ csrf_token() }}";
            $data.id = $('#taskid').val();
            $data.user_id = $('#user').val();
            $data.taskname = ValidateInput($('#taskname'));
            $data.description = ValidateInput($('#taskdescription'));
            $data.status = $('#task-status').val();
            $data.isproject = $('#IsProject').val();

            $data.duedate = $('#duedate').val();

            $('#AddTasksModal').modal('hide');
            $("body").addClass("loading");
            ResetServerValidationErrors();

            posting = $.post("/Tasklist/Save", $data);

            posting.done(function (data) {

                $("body").removeClass("loading");

                switch(data['status']) {
                    case "complete":
                        $split = data['returnString'].split("|");
                        if ($mode === "edit") {
                            window.viewtaskstable.row('.selected2').data($split);
                        } else {
                            window.viewtaskstable.row.add($split);
                        }
                        window.viewtaskstable.draw();

                        //if ($('#taskid').val() === "0") {
                        //    SendNotification("New item on your tasklist", "Task Name:" + $data.taskname + "\r\nDescription:" + $data.description, $data.user_id);
                        //}

                        if ( $("#viewstatus option[value='"+ $split[7] +"']").length == 0 ){
                            $('#viewstatus').append($('<option>', {
                                value: $split[7],
                                text: $split[7]
                            }));
                        }

                        SavedSuccess($content = 'Got it...user notified', $title = "Success!", $timeout = "2000");
                        PageinateUpdate(window.viewtaskstable.page.info(), $('#tasklist-next-page'), $('#tasklist-previous-page'),$('#tasklist-tableInfo'));

                        $('#ViewTasksModal').modal('show');
                        break;
                    case "validation":
                        ServerValidationErrors(data['errors']);
                        break;
                    default:
                        $.dialog({
                            title: 'Oops...',
                            content: 'Unknown Response from server. Please refresh the page and try again.'
                        });
                }
            });

            posting.fail(function () {
                $("body").removeClass("loading");
                bootstrap_alert.warning("Failed to contact server", 'danger', 4000);
            });
        });

        $('#AddTasksModal').on('hide.bs.modal', function (event) {
            //$('#ViewTasksModal').modal('show');
        });

        $("#edit-task").click(function (e) {
            $row = window.viewtaskstable.row('.selected').data();
        });

        // DataTable
        window.viewtaskstable = $('#viewtaskstable').DataTable({
            "columnDefs": [
                {"targets": [0, 1, 8], "visible": false}
            ],
            "order": [[ 4, "asc" ]],
            "rowCallback": function (row, data, index) {
                $(row).removeClass('warning');
                $(row).removeClass('danger');
                $(row).removeClass('success');
                $(row).removeClass('info');
                switch (data[7]) {
                    case "Important":
                        $(row).addClass('info');
                        break;
                    case "Urgent":
                        $(row).addClass('warning');
                        break;
                    case "Critical":
                        $(row).addClass('danger');
                        break;
                    case "Complete":
                        $(row).addClass('success');
                        break;
                    default:
                        //$(row).addClass( 'success' );
                        break;
                }
            }
        });

        $('#viewtaskstable tbody').on('click', 'tr', function () {
            $row = $(this);
            if ($(this).hasClass('selected2')) {
                $(this).removeClass('selected2');
            }
            else {
                window.viewtaskstable.$('tr.selected2').removeClass('selected2');
                $(this).addClass('selected2');
            }

            PageinateUpdate(window.viewtaskstable.page.info(), $('#tasklist-next-page'), $('#tasklist-previous-page'),$('#tasklist-tableInfo'));

        });

        $('#viewstatus').on('change', function () {

            if (this.value === "all") {
                window.viewtaskstable
                    .columns(7)
                    .search("", true)
                    .draw();
            } else {
                if (this.value === "notcomplete") {

                    window.viewtaskstable
                        .columns(7)
                        .search("^((?!Complete).)*$", true)
                        .draw();
                } else {
                    window.viewtaskstable
                        .columns(7)
                        .search(this.value, true)
                        .draw();
                }
            }

            PageinateUpdate(window.viewtaskstable.page.info(), $('#tasklist-next-page'), $('#tasklist-previous-page'),$('#tasklist-tableInfo'));

        });
        $('#viewstatus').change();

        $( "#tasklist-previous-page" ).click(function() {
            window.viewtaskstable.page( "previous" ).draw('page');
            PageinateUpdate(window.viewtaskstable.page.info(), $('#tasklist-next-page'), $('#tasklist-previous-page'),$('#tasklist-tableInfo'));
        });

        $( "#tasklist-next-page" ).click(function() {
            window.viewtaskstable.page( "next" ).draw('page');
            PageinateUpdate(window.viewtaskstable.page.info(), $('#tasklist-next-page'), $('#tasklist-previous-page'),$('#tasklist-tableInfo'));
        });

        $('#task-user').on('change', function () {

            window.viewtaskstable
                .columns(1)
                .search(this.value, true)
                .draw();

            PageinateUpdate(window.viewtaskstable.page.info(), $('#tasklist-next-page'), $('#tasklist-previous-page'),$('#tasklist-tableInfo'));

        });
        $('#task-user').val("{{ Auth::user()->id }}");
        $('#task-user').change();

        $("#viewtaskstable").css("width", "100%");

        PageinateUpdate(window.viewtaskstable.page.info(), $('#tasklist-next-page'), $('#tasklist-previous-page'),$('#tasklist-tableInfo'));

    });
</script>