@extends('master')
@section('content')

    <div class="row" style="margin-top: 20px;">

        <div class="col-md-3">
            <div class="input-group ">
                <span class="input-group-addon" for="plans-search"><div style="width: 7em;">Search:</div></span>
                <input id="plans-search" name="plans-search" type="text" placeholder="" value="" class="form-control">
            </div>
        </div>

        <div class="col-md-3">
            <button class="btn OS-Button" type="button" data-toggle="modal" data-target="#AddPlanModal" style="width: 100%;">New Plan
            </button>
        </div>

        <div class="col-md-3">
            <button class="btn OS-Button" type="button" data-toggle="modal" data-target="#EditPlanModal" style="width: 100%;">Edit Plan
            </button>
        </div>

        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-addon" for="plans-status"><div style="width: 7em;">Status:</div></span>
                <select id="plans-status" name="plans-status" type="text" placeholder="choice" class="form-control">
                    <option value="all">All</option>
                    <option value="Active" selected>Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>
        </div>

    </div>

    <div class="row" style="margin-top: 5px;">
        <div class="col-md-12">
            {!! PageElement::TableControl('plans') !!}
        </div>
    </div>

    <table class="table" id="plantable">
    <thead>
        <tr>
            <th class="datatables-invisible-col">id</th>
            <th>Name</th>
            <th>Description</th>
            <th>TN Plan Name</th>
            <th>Max Users</th>
            <th>Cost</th>
            <th>Show On Public</th>
            <th>Show In Subscriptions</th>
            <th>Starts At</th>
            <th>Expires At</th>
            <th>Created At</th>
            <th>Status</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>id</th>
            <th>Name</th>
            <th>Description</th>
            <th>TN Plan Name</th>
            <th>Max Users</th>
            <th>Cost</th>
            <th>Show On Public</th>
            <th>Show In Subscriptions</th>
            <th>Starts At</th>
            <th>Expires At</th>
            <th>Created At</th>
            <th>Status</th>
        </tr>
    </tfoot>
    <tbody>
        @foreach($plans as $plan)
        <tr>
            <td>{{ $plan->id }}</td>
            <td>{{ $plan->name }}</td>
            <td>{{ $plan->description }}</td>
            <td>{{ $plan->tn_plan_name }}</td>
            <td>{{ $plan->numusers }}</td>
            <td>${{ number_format($plan->cost, 2, ".", "") }}</td>
            <td>{{ $plan->showonpublic }}</td>
            <td>{{ $plan->showinsubs }}</td>
            <td>{{ $plan->starts_at->toDateString() }}</td>
            <td>{{ $plan->expires_at->toDateString() }}</td>
            <td>{{ $plan->created_at->toDateString() }}</td>
            @if($plan->deleted_at === null)
            <td>Active</td>
            @else
            <td>Inactive</td>
            @endif
        </tr>
        @endforeach
    </tbody>
</table>

    <div class="modal fade" id="EditPlanModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="EditPlanLabel">Edit Plan</h4>
                </div>
                <div class="modal-body">

                    <input id="plan-edit-id" style="display: none;">
                    
                    <div class="input-group ">
                        <span class="input-group-addon" for="plan-edit-name"><div style="width: 15em;">Name:</div></span>
                        <input id="plan-edit-name" name="plan-edit-name" type="text" placeholder="" value="" class="form-control">
                    </div>

                    <div class="input-group ">
                        <span class="input-group-addon" for="plan-edit-description"><div style="width: 15em;">Description:</div></span>
                        <input id="plan-edit-description" name="plan-edit-description" type="text" placeholder="" value="" class="form-control">
                    </div>

                    <div class="input-group ">
                        <span class="input-group-addon" for="plan-edit-tn_plan_name"><div style="width: 15em;">tn_plan_name:</div></span>
                        <input id="plan-edit-tn_plan_name" name="plan-edit-tn_plan_name" type="text" placeholder="" value="" class="form-control" readonly>
                    </div>

                    <div class="input-group ">
                        <span class="input-group-addon" for="plan-edit-numusers"><div style="width: 15em;">Number Users:</div></span>
                        <input id="plan-edit-numusers" name="plan-edit-numusers" type="text" placeholder="" value="" class="form-control" readonly>
                    </div>

                    <div class="input-group ">
                        <span class="input-group-addon" for="plan-edit-cost"><div style="width: 15em;">Cost:</div></span>
                        <input id="plan-edit-cost" name="plan-edit-cost" type="text" placeholder="" value="" class="form-control" readonly>
                    </div>

                    <div class="input-group">
                        <span class="input-group-addon" for="status"><div style="width: 15em;">promotions.officesweeet.com:</div></span>
                        <input id="plan-edit-showonpublic" type="checkbox" name="checkboxes" data-on="Show" data-off="Don't Show" data-width="100%" data-toggle="toggle" checked>
                    </div>

                    <div class="input-group">
                        <span class="input-group-addon" for="status"><div style="width: 15em;">Show In Subsriptions:</div></span>
                        <input id="plan-edit-showinsubs" type="checkbox" name="checkboxes" data-on="Show" data-off="Don't Show" data-width="100%" data-toggle="toggle" checked>
                    </div>

                    <div class="input-group ">
                        <span class="input-group-addon" for="plan-edit-starts_at"><div style="width: 15em;">Starts At:</div></span>
                        <input id="plan-edit-starts_at" name="plan-edit-starts_at" type="text" placeholder="" value="" class="form-control" style="z-index: 10000;" readonly>
                    </div>

                    <div class="input-group ">
                        <span class="input-group-addon" for="plan-edit-expires_at"><div style="width: 15em;">Expires At:</div></span>
                        <input id="plan-edit-expires_at" name="plan-edit-expires_at" type="text" placeholder="" value="" class="form-control" style="z-index: 10000;" readonly>
                    </div>

                    <div class="input-group">
                        <span class="input-group-addon" for="status"><div style="width: 15em;">Status:</div></span>
                        <input  id="plan-edit-status" type="checkbox" name="checkboxes" data-on="Active" data-off="Inactive" data-width="100%" data-toggle="toggle" checked>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="plan-edit-save" type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="AddPlanModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="EditPlanLabel">Add Plan</h4>
                </div>
                <div class="modal-body">
                    <div class="input-group ">
                        <span class="input-group-addon" for="plan-new-name"><div style="width: 15em;">Name:</div></span>
                        <input id="plan-new-name" name="plan-new-name" type="text" placeholder="" value="" class="form-control planinput">
                    </div>

                    <div class="input-group ">
                        <span class="input-group-addon" for="plan-new-description"><div style="width: 15em;">Description:</div></span>
                        <input id="plan-new-description" name="plan-new-description" type="text" placeholder="" value="" class="form-control planinput">
                    </div>

                    <div class="input-group ">
                        <span class="input-group-addon" for="plan-new-tn_plan_name"><div style="width: 15em;">tn_plan_name:</div></span>
                        <input id="plan-new-tn_plan_name" name="plan-new-tn_plan_name" type="text" placeholder="" value="" class="form-control" readonly>
                    </div>

                    <div class="input-group ">
                        <span class="input-group-addon" for="plan-new-numusers"><div style="width: 15em;">Number Users:</div></span>
                        <input id="plan-new-numusers" name="plan-new-numusers" type="text" placeholder="" value="" class="form-control planinput" >
                    </div>

                    <div class="input-group ">
                        <span class="input-group-addon" for="plan-new-cost"><div style="width: 15em;">Cost:</div></span>
                        <input id="plan-new-cost" name="plan-new-cost" type="text" placeholder="" value="" class="form-control planinput" >
                    </div>

                    <div class="input-group">
                        <span class="input-group-addon" for="status"><div style="width: 15em;">promotions.officesweeet.com:</div></span>
                        <input id="plan-new-showonpublic" type="checkbox" name="checkboxes" data-on="Show" data-off="Don't Show" data-width="100%" data-toggle="toggle" checked>
                    </div>

                    <div class="input-group">
                        <span class="input-group-addon" for="status"><div style="width: 15em;">Show In Subsriptions:</div></span>
                        <input id="plan-new-showinsubs" type="checkbox" name="checkboxes" data-on="Show" data-off="Don't Show" data-width="100%" data-toggle="toggle" checked>
                    </div>

                    <div class="input-group ">
                        <span class="input-group-addon" for="plan-new-starts_at"><div style="width: 15em;">Starts At:</div></span>
                        <input id="plan-new-starts_at" name="plan-new-starts_at" type="text" placeholder="" value="" class="form-control" style="z-index: 10000;" readonly>
                    </div>

                    <div class="input-group ">
                        <span class="input-group-addon" for="plan-new-expires_at"><div style="width: 15em;">Expires At:</div></span>
                        <input id="plan-new-expires_at" name="plan-new-expires_at" type="text" placeholder="" value="" class="form-control" style="z-index: 10000;" readonly>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="plan-new-save" type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
    
<script>
    $(document).ready(function() {

        $('#plan-edit-starts_at').datepicker({
            changeMonth: true,
            changeYear: true,
            controlType: 'select',
            parse: "loose",
            dateFormat: "yy-mm-dd",
        });
        $('#plan-edit-expires_at').datepicker({
            changeMonth: true,
            changeYear: true,
            controlType: 'select',
            parse: "loose",
            dateFormat: "yy-mm-dd",
        });
        $('#plan-new-starts_at').datepicker({
            changeMonth: true,
            changeYear: true,
            controlType: 'select',
            parse: "loose",
            dateFormat: "yy-mm-dd",
        });
        $('#plan-new-expires_at').datepicker({
            changeMonth: true,
            changeYear: true,
            controlType: 'select',
            parse: "loose",
            dateFormat: "yy-mm-dd",
        });

        window.planstable = $('#plantable').DataTable({
            "order": [[ 9, "desc" ]],
            "pageLength": 10,
            "columns": [
                { "name": "id" },
                { "name": "name" },
                { "name": "description" },
                { "name": "tn_plan_name" },
                { "name": "numusers" },
                { "name": "cost" },
                { "name": "showonpublic" },
                { "name": "show_in_subs" },
                { "name": "starts_at" },
                { "name": "expires_at" },
                { "name": "created_at" },
                { "name": "status" },
            ],
            "columnDefs": [
                {
                    "targets": "datatables-invisible-col",
                    "visible": false
                }
            ],
        });

        $('#EditPlanModal').on('show.bs.modal', function (event) {

            $row = window.planstable.row('.selected').data();

            if($row === undefined){
                event.preventDefault();
                $.dialog({
                    title: 'Oops...',
                    content: 'Please Select a Plan from the table.'
                });
            }else{
                $('#plan-edit-id').val($row[0]);
                $('#plan-edit-name').val($row[1]);
                $('#plan-edit-description').val($row[2]);
                $('#plan-edit-tn_plan_name').val($row[3]);
                $('#plan-edit-numusers').val($row[4]);
                $('#plan-edit-cost').val($row[5]);

                if($row[6] === "1"){
                    $('#plan-edit-showonpublic').bootstrapToggle('on')
                }else{
                    $('#plan-edit-showonpublic').bootstrapToggle('off')
                }

                if($row[7] === "1"){
                    $('#plan-edit-showinsubs').bootstrapToggle('on')
                }else{
                    $('#plan-edit-showinsubs').bootstrapToggle('off')
                }

                $('#plan-edit-starts_at').val($row[8]);
                $('#plan-edit-expires_at').val($row[9]);
                $('#plan-edit-created_at').val($row[10]);

                if($row[11] === "Active"){
                    $('#plan-edit-status').bootstrapToggle('on')
                }else{
                    $('#plan-edit-status').bootstrapToggle('off')
                }
            }
        });
        
        $('#AddPlanModal').on('show.bs.modal', function (event) {
            
            $('#plan-edit-id').val("");
            $('#plan-edit-name').val("");
            $('#plan-edit-description').val("");
            $('#plan-edit-tn_plan_name').val("");
            $('#plan-edit-numusers').val("");
            $('#plan-edit-cost').val("");
            $('#plan-edit-showonpublic').bootstrapToggle('off');
            $('#plan-edit-starts_at').val("");
            $('#plan-edit-expires_at').val("");
            $('#plan-edit-created_at').val("");
                     
        });

        $(".planinput").focusout(function(e) {
            $plandata = {};
            $plandata['plan-name'] = $('#plan-new-name').val();
            $plandata['plan-description'] = $('#plan-new-description').val();
            $plandata['plan-numusers'] = $('#plan-new-numusers').val();
            $plandata['plan-cost'] = $('#plan-new-cost').val();

            $tnplanname = $plandata['plan-numusers'] + "user" + $plandata['plan-cost'] +  moment().format('MMMYYYY')

            $('#plan-new-tn_plan_name').val($tnplanname);
        });

        $('#plantable tbody').on( 'click', 'tr', function () {

            $row = $(this);

            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
            }
            else {
                window.planstable.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }
        });

        $( "#plans-previous-page" ).click(function() {
            window.planstable.page( "previous" ).draw('page');
            PageinateUpdate(window.planstable.page.info(), $('#plans-next-page'), $('#plans-previous-page'),$('#plans-tableInfo'));
        });

        $( "#plans-next-page" ).click(function() {
            window.planstable.page( "next" ).draw('page');
            PageinateUpdate(window.planstable.page.info(), $('#plans-next-page'), $('#plans-previous-page'),$('#plans-tableInfo'));
        });

        $('#plans-search').on( 'keyup change', function () {
            window.planstable.search( this.value ).draw();
            PageinateUpdate(window.planstable.page.info(), $('#plans-next-page'), $('#plans-previous-page'),$('#plans-tableInfo'));
        });

        $('#plans-status').on( 'keyup change', function () {
            if(this.value === "all"){
                window.planstable
                    .columns( "status:name" )
                    .search( "" , true)
                    .draw();
            }else{
                window.planstable
                    .columns( "status:name" )
                    .search( "^" + $(this).val() + "$", true, false, true)
                    .draw();
            }

            PageinateUpdate(window.planstable.page.info(), $('#plans-next-page'), $('#plans-previous-page'),$('#plans-tableInfo'));

        });
        $('#plans-status').change();

        PageinateUpdate(window.planstable.page.info(), $('#plans-next-page'), $('#plans-previous-page'),$('#plans-tableInfo'));

        $('#plan-edit-save').click(function () {

            $("body").addClass("loading");
            ResetServerValidationErrors();

            $data = {};
            $data['_token'] = "{{ csrf_token() }}";
            $data['id'] = $('#plan-edit-id').val();
            $data['name'] = $('#plan-edit-name').val();
            $data['description'] = $('#plan-edit-description').val();
            //$data['tn_plan_name'] = $('#plan-edit-tn_plan_name').val(); //fixed, not editable
            ///$data['numusers'] = $('#plan-edit-numusers').val(); //fixed, not editable
            //$data['cost'] = $('#plan-edit-cost').val(); //fixed, not editable

            if($('#plan-edit-showonpublic').prop('checked') === true){
                $data['showonpublic'] = "1"
            }else{
                $data['showonpublic'] = "0";
            }

            if($('#plan-edit-showinsubs').prop('checked') === true){
                $data['showinsubs'] = "1"
            }else{
                $data['showinsubs'] = "0";
            }

            $data['starts_at'] = $('#plan-edit-starts_at').val();
            $data['expires_at'] = $('#plan-edit-expires_at').val();

            if($('#plan-edit-status').prop('checked') === true){
                $data['status'] = "1";
            }else{
                $data['status'] = "0";
            }

            $post = $.post("/Plans/Edit", $data);

            $post.done(function (data) {
                $("body").removeClass("loading");
                switch(data['status']) {
                    case "OK":

                        $row = [];
                        $row['id'] = data['plan']['id'];
                        $row['name'] = data['plan']['name'];
                        $row['description'] = data['plan']['description'];
                        $row['tn_plan_name'] = data['plan']['tn_plan_name'];
                        $row['numusers'] = data['plan']['numusers'];
                        $row['cost'] = data['plan']['cost'];
                        $row['showonpublic'] = data['plan']['showonpublic'];
                        $row['show_in_subs'] = data['plan']['showinsubs'];
                        $row['starts_at'] = data['plan']['starts_at'];
                        $row['expires_at'] = data['plan']['expires_at'];
                        $row['created_at'] = data['plan']['created_at'];

                        if(data['plan']['deleted_at'] === null){
                            $row['status'] = "Active";
                        }else{
                            $row['status'] = "Inactive";
                        }

                        console.log($row);

                        window.planstable.row('.selected').data($row).draw();

                        $('#EditPlanModal').modal('hide');

                        break;
                    case "notfound":
                        $.dialog({
                            title: 'Oops...',
                            content: 'Unknown Response from server. Please refresh the page and try again.'
                        });
                        break;
                    case "validation":
                        ServerValidationErrors(data['errors']);
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

        $('#plan-new-save').click(function () {

            $("body").addClass("loading");
            ResetServerValidationErrors();

            $data = {};
            $data['_token'] = "{{ csrf_token() }}";

            $data['name'] = $('#plan-new-name').val();
            $data['description'] = $('#plan-new-description').val();
            $data['tn_plan_name'] = $('#plan-new-tn_plan_name').val();
            $data['numusers'] = $('#plan-new-numusers').val();
            $data['cost'] = $('#plan-new-cost').val();

            if($('#plan-new-showonpublic').prop('checked') === true){
                $data['showonpublic'] = "1"
            }else{
                $data['showonpublic'] = "0";
            }

            if($('#plan-new-showinsubs').prop('checked') === true){
                $data['showinsubs'] = "1"
            }else{
                $data['showinsubs'] = "0";
            }

            $data['starts_at'] = $('#plan-new-starts_at').val();
            $data['expires_at'] = $('#plan-new-expires_at').val();


            $post = $.post("/Plans/Add", $data);

            $post.done(function (data) {
                $("body").removeClass("loading");
                switch(data['status']) {
                    case "OK":



                        $row = [];
                        $row['id'] = data['plan']['id'];
                        $row['name'] = data['plan']['name'];
                        $row['description'] = data['plan']['description'];
                        $row['tn_plan_name'] = data['plan']['tn_plan_name'];
                        $row['numusers'] = data['plan']['numusers'];
                        $row['cost'] = data['plan']['cost'];
                        $row['starts_at'] = data['plan']['starts_at'];
                        $row['expires_at'] = data['plan']['expires_at'];
                        $row['created_at'] = data['plan']['created_at'];
                        $row['showonpublic'] = data['plan']['showonpublic'];
                        $row['showinsubs'] = data['plan']['showinsubs'];
                        $row['status'] = "Active";

                        window.planstable.row.add($row).draw();

                        $('#AddPlanModal').modal('hide');

                        break;
                    case "notfound":
                        $.dialog({
                            title: 'Oops...',
                            content: 'Unknown Response from server. Please refresh the page and try again.'
                        });
                        break;
                    case "validation":
                        ServerValidationErrors(data['errors']);
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
    });
</script>
@stop


