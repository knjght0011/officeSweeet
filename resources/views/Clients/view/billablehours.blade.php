<div class="row" style="margin-top: 15px;">
    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="search"><div style="width: 7em;">Search:</div></span>
            <input id="billable-hours-search" name="billable-hours-search" type="text" placeholder="" value="" class="form-control">
        </div>
    </div>
    <div class="col-md-6">
        {!! PageElement::TableControl('billable-hours') !!}
    </div>
    <div class="col-md-3">
        <button type="button" class="btn OS-Button toggletimer" data-clientid="{{ $client->id }}" style="width: 100%;">Timer</button>
    </div>
</div>

<table class="table" id="billable-hours-table">
    <thead>
        <tr>
            <th>id</th>
            <th>User</th>
            <th>Rate</th>
            <th>Hours</th>
            <th>Total</th>
            <th>Comment</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($client->billablehours as $hours)
        <tr>
            <td>{{ $hours->id }}</td>
            <td>{{ $hours->user->getName() }}</td>
            <td>${{ number_format($hours->rate , 2, '.', '') }}</td>
            <td>{{ floatval($hours->hours) }}</td>
            <td>${{ number_format($hours->Total() , 2, '.', '') }}</td>
            <td>{{ $hours->comment }}</td>
            <td>{{ $hours->created_at }}</td>
        </tr>
        @endforeach
    </tbody>
</table>


<div class="modal fade" id="billablehours" tabindex="-1" role="dialog" aria-labelledby="billablehours" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="billablehours">Billable Hours</h4>
            </div>
            <div class="modal-body">
                {!! Form::OSinput("billable-rate", "Hourly Rate ($)", "", 0, "true", "", "number") !!}
                {!! Form::OSinput("billable-hours", "Hours", "", 0, "true", "", "number") !!}
                {!! Form::OStextarea("billable-comment", "Comments") !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button id="billable-save" type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {

    var billablehourstable = $('#billable-hours-table').DataTable({
        "columnDefs": [
            { "targets": [0],"visible": false}
        ],
        "order": [[ 0, "desc" ]],
        "language": {
            "emptyTable": "No Data"
        }
    });
    $( "#billable-hours-previous-page" ).click(function() {
        billablehourstable.page( "previous" ).draw('page');
        PageinateUpdate(billablehourstable.page.info(), $('#billable-hours-next-page'), $('#billable-hours-previous-page'),$('#billable-hours-tableInfo'));
    });

    $( "#billable-hours-next-page" ).click(function() {
        billablehourstable.page( "next" ).draw('page');
        PageinateUpdate(billablehourstable.page.info(), $('#billable-hours-next-page'), $('#billable-hours-previous-page'),$('#billable-hours-tableInfo'));
    });

    $('#billable-hours-search').on( 'keyup change', function () {
        billablehourstable.search( this.value ).draw();
        PageinateUpdate(billablehourstable.page.info(), $('#billable-hours-next-page'), $('#billable-hours-previous-page'),$('#billable-hours-tableInfo'));
    });

    PageinateUpdate(billablehourstable.page.info(), $('#billable-hours-next-page'), $('#billable-hours-previous-page'),$('#billable-hours-tableInfo'));

    $( "#billablehours-tab" ).children().find(".dataTables_filter").css('display', 'none');
    $( "#billablehours-tab" ).children().find(".dataTables_length").css('display', 'none');
    $( "#billablehours-tab" ).children().find(".dataTables_paginate").css('display', 'none');
    $( "#billablehours-tab" ).children().find(".dataTables_info").css('display', 'none');
    $('#billable-hours-table').css('width' , "100%");

    $('#billablehours').on('show.bs.modal', function (event) {
        $(".timerexit").click();
        $mins = _StopWatch.minutes();
        $hours = _StopWatch.hours();

        $time = $hours + "." + parseInt(($mins / 60) * 100);

        $('#billable-hours').val($time);
    });

    $('#billable-save').click(function () {

        $("body").addClass("loading");

        $data = {};
        $data['_token'] = "{{ csrf_token() }}";
        $data['client_id'] = "{{ $client->id }}";
        $data['rate'] = $('#billable-rate').val();
        $data['hours'] = $('#billable-hours').val();
        $data['comment'] = $('#billable-comment').val();

        $post = $.post("/Clients/AddBillableHours", $data);

        $post.done(function (data) {


            $("body").removeClass("loading");
            $split = data.split(",");
            billablehourstable.row.add( $split).draw();
            $('#billablehours').modal('hide')
            //GoToPage('/Clients/View/{{ $client->id }}/billablehours-tab');

        });

        $post.fail(function () {

            $("body").removeClass("loading");
            $.dialog({
                title: 'Oops...',
                content: 'Failed to contact server. Please try again later.'
            });
        });
    });
});
</script>