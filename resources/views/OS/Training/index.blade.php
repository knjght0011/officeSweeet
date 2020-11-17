@extends('master')

@section('content')

    <div class="col-md-5">
        <div class="input-group ">
            <span class="input-group-addon" for="department-search"><div style="width: 7em;">Department:</div></span>
            <select id="department-search" name="department-search" class="form-control">
                <option value="all" selected>All</option>
                @foreach(\App\Helpers\EmployeeHelper::AllDepartments() as $department)
                    <option value="{{ $department }}">{{ $department }}</option>
                @endforeach
                <option value="none">None</option>
            </select>
        </div>
    </div>

    <div class="col-md-5">
        <div class="input-group ">
            <span class="input-group-addon" for="department-search"><div style="width: 7em;">Training Module:</div></span>
            <select id="training-search" name="training-search" class="form-control">
                @foreach($trainingModules as $trainingModule)
                    <option value="{{ $trainingModule->id }}">{{ $trainingModule->title }}</option>
                @endforeach

            </select>
        </div>
    </div>

    <div class="col-md-2">
        <button style="width: 100%;" id="training-go" class="btn OS-Button">Search</button>
    </div>

    @if(isset($DepartmentUsers))
    <div class="container">
        <legend>{{ $trainingModules->where('id', $trainingModuleID)->first()->title }}</legend>

        <table class="table">
            <thead>
            <tr>
                <th>Employee</th>
                <th>Status</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($DepartmentUsers as $DepartmentUser)
                <tr>
                    <td>{{ $DepartmentUser->firstname }} {{ $DepartmentUser->lastname }}</td>
                    <td>{{ $DepartmentUser->CheckTraining($trainingModuleID) }}</td>
                    @if($DepartmentUser->CheckTraining($trainingModuleID) === "Never")
                        <td><input class="training-toggle" type="checkbox" name="checkboxes" data-on="Include" data-off="Don't Include" data-width="100%" data-toggle="toggle" data-userid="{{ $DepartmentUser->id }}" checked></td>
                    @else
                        <td></td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>

        <button style="width: 100%;" data-toggle="modal" data-target="#AddTrainingModal" class="btn OS-Button">Next</button>
    </div>

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
                    <button id="training-add" name="training-add" type="button" class="btn OS-Button" value="">Add Training and Notify Employees</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {

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

            $('#training-add').click(function () {


                $("body").addClass("loading");

                $data = {};
                $data['_token'] = "{{ csrf_token() }}";

                $userids = [];
                $('.training-toggle').each(function () {
                    if($(this).prop('checked') === true){
                        $userids.push($(this).data('userid'));
                    }
                });

                $data['user_ids'] = $userids;
                $data['module_id'] = "{{ $trainingModuleID }}";

                $data['training-schedule-date'] = $('#training-schedule-date-new').val();
                $data['training-due-date'] = $('#training-due-date-new').val();
                $data['training-reminder-date'] = $('#training-reminder-date-new').val();

                $post = $.post("/Training/AddBulk", $data);

                $post.done(function (data) {

                    switch(data['status']) {
                        case "OK":
                            window.location.reload();
                            break;
                        case "notfound":
                            $("body").removeClass("loading");
                            $.dialog({
                                title: 'Oops...',
                                content: 'Unknown Response from server. Please refresh the page and try again.'
                            });
                            break;
                        default:
                            console.log(data);
                            $("body").removeClass("loading");
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
        });

        function DisableSpecificDates(date) {

            var now = new Date($('#training-schedule-date-new').val());

            if (date < now) {
                return [true];
            } else {
                return [false];
            }
        }
    </script>
    @endif

    <script>
        $(document).ready(function() {

            $('#training-go').click(function () {
               GoToPage('/Training/' + $('#department-search').val() + '/' + $('#training-search').val());
            });

        });
    </script>


@stop