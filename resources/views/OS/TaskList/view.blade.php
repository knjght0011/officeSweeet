@extends('master')
@section('content')

    <div class="col-md-12">
        <button style="width: 100%;" data-toggle="modal" data-target="#AddTasksModalMobile" type="button"
                data-function="add" class="btn OS-Button">Add New Task/Project
        </button>
    </div>
    <div class="col-md-12">
        <button id="edit-task" style="width: 100%;" data-toggle="modal" data-target="#AddTasksModalMobile"
                type="button" data-function="edit" class="btn OS-Button">Edit Selected
        </button>
    </div>
    <div class="col-md-12">
        <button style="width: 100%;" type="button" class="btn OS-Button" id="task-markcomplete">
            Mark Task/Project Complete
        </button>
    </div>
    <div class="col-md-12">
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
    <div class="col-md-12">
        <select id="task-user" name="user" type="text" placeholder="" class="form-control">
            @foreach(UserHelper::GetAllUsers() as $user)
                <option value="{{ $user->id }}">{{ $user->getShortName() }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-12">
        <div class="input-group ">
            <span class="input-group-addon" for="search"><div style="width: 7em;">Search:</div></span>
            <input id="tasks-search" name="tasks-search" type="text" placeholder="" value="" class="form-control">
        </div>
    </div>
    <div class="col-md-12">
        {!! PageElement::TableControl('tasks') !!}
    </div>


    <table id="viewtaskstablemobile" class="table">
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



    <div class="modal fade" id="AddTasksModalMobile" tabindex="-1" role="dialog" aria-labelledby="AddTasksModalMobile"
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
                        <input id="task-status" type="text" name="task-status" list="task-status-list" class="form-control" data-validation-label="Sub-Group" data-validation-required="true" data-validation-type="">
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
                        <span class="input-group-addon" for="duedate"><div style="width: 15em;">Deadline:</div></span>
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

            $('#AddTasksModalMobile').on('show.bs.modal', function (event) {

                debugger;
                var button = $(event.relatedTarget);
                if (button.data("function") === "edit") {
                    $row = viewtaskstablemobile.row('.selected2').data();
                    if ($row === undefined) {
                        alert("please select a row to edit first");
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
                }
            });

            $('#task-markcomplete').click(function () {

                $row = viewtaskstablemobile.row('.selected2').data();

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

                                viewtaskstablemobile.row('.selected2').data($split);

                                viewtaskstablemobile.draw();

                                if ( $("#viewstatus option[value='"+ $split[6] +"']").length == 0 ){
                                    $('#viewstatus').append($('<option>', {
                                        value: $split[6],
                                        text: $split[6]
                                    }));
                                }

                                PageinateUpdate(viewtaskstablemobile.page.info(), $('#tasklist-next-page'), $('#tasklist-previous-page'),$('#tasklist-tableInfo'));

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
                var $data = new Object();
                $data._token = "{{ csrf_token() }}";
                $data.id = $('#taskid').val();
                $data.user_id = $('#user').val();
                $data.taskname = ValidateInput($('#taskname'));
                $data.description = ValidateInput($('#taskdescription'));
                $data.status = $('#task-status').val();

                $data.duedate = $('#duedate').val();


                $('#AddTasksModalMobile').modal('hide');
                $("body").addClass("loading");

                posting = $.post("/Tasklist/Save", $data);

                posting.done(function (data) {

                    $("body").removeClass("loading");

                    switch(data['status']) {
                        case "complete":
                            $split = data['returnString'].split("|");
                            if ($mode === "edit") {
                                viewtaskstablemobile.row('.selected2').data($split);
                            } else {
                                viewtaskstablemobile.row.add($split);
                            }
                            viewtaskstablemobile.draw();

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

            $('#AddTasksModalMobile').on('hide.bs.modal', function (event) {
                //$('#ViewTasksModal').modal('show');
            });

            $("#edit-task").click(function (e) {
                $row = viewtaskstablemobile.row('.selected').data();
                console.log($row);
            });

            // DataTable
            var viewtaskstablemobile = $('#viewtaskstablemobile').DataTable({
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

            $('#viewtaskstablemobile tbody').on('click', 'tr', function () {
                $row = $(this);
                if ($(this).hasClass('selected2')) {
                    $(this).removeClass('selected2');
                }
                else {
                    viewtaskstablemobile.$('tr.selected2').removeClass('selected2');
                    $(this).addClass('selected2');
                }
            });

            $("#tasks-previous-page").click(function () {
                viewtaskstablemobile.page("previous").draw('page');
                PageinateUpdate(viewtaskstablemobile.page.info(), $('#tasks-next-page'), $('#tasks-previous-page'), $('#tasks-tableInfo'));
            });

            $("#tasks-next-page").click(function () {
                viewtaskstablemobile.page("next").draw('page');
                PageinateUpdate(viewtaskstablemobile.page.info(), $('#tasks-next-page'), $('#tasks-previous-page'), $('#tasks-tableInfo'));
            });

            $('#tasks-search').on('keyup change', function () {
                viewtaskstablemobile.search(this.value).draw();
                PageinateUpdate(viewtaskstablemobile.page.info(), $('#tasks-next-page'), $('#tasks-previous-page'), $('#tasks-tableInfo'));
            });


            $('#viewstatus').on('change', function () {

                if (this.value === "all") {
                    viewtaskstablemobile
                        .columns(7)
                        .search("", true)
                        .draw();
                } else {
                    if (this.value === "notcomplete") {

                        viewtaskstablemobile
                            .columns(7)
                            .search("^((?!Complete).)*$", true)
                            .draw();
                    } else {
                        viewtaskstablemobile
                            .columns(7)
                            .search(this.value, true)
                            .draw();
                    }
                }

                PageinateUpdate(viewtaskstablemobile.page.info(), $('#tasks-next-page'), $('#tasks-previous-page'), $('#tasks-tableInfo'));

            });

            $('#viewstatus').change();

            $('#task-user').on('change', function () {

                viewtaskstablemobile
                    .columns(1)
                    .search(this.value, true)
                    .draw();

                PageinateUpdate(viewtaskstablemobile.page.info(), $('#tasks-next-page'), $('#tasks-previous-page'), $('#tasks-tableInfo'));

            });
            $('#task-user').val("{{ Auth::user()->id }}");
            $('#task-user').change();

            PageinateUpdate(viewtaskstablemobile.page.info(), $('#tasks-next-page'), $('#tasks-previous-page'), $('#tasks-tableInfo'));

            $(".dataTables_filter").css('display', 'none');
            $(".dataTables_length").css('display', 'none');
            $(".dataTables_paginate").css('display', 'none');
            $(".dataTables_info").css('display', 'none');
            $("#viewtaskstablemobile").css("width", "100%");

        });
    </script>
@stop
