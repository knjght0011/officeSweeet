<div class="row" style="margin-top: 15px;">
    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="search"><div style="width: 7em;">Search:</div></span>
            <input id="clocks-search" name="clocks-search" type="text" placeholder="" value="" class="form-control">
        </div>
    </div>
    <div class="col-md-6">
        {!! PageElement::TableControl('clocks') !!}
    </div>
    <div class="col-md-3">
        <button type="button" class="btn OS-Button" style="width: 100%;" data-toggle="modal" data-target="#editClocks">Edit Selected</button>
    </div>
</div>


<table id="clockstable" class="table">
    <thead>
        <tr id="head">
            <th></th>
            <th></th>
            <th></th>
            <th>In Day</th>
            <th>In Date/Time</th>
            <th>Out Day</th>
            <th>Out Date/Time</th>
            <th>Difference</th>
        </tr>
    </thead>
    <tfoot style="visibility: hidden;">
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th>In Day</th>
            <th>In Date/Time</th>
            <th>Out Day</th>
            <th>Out Date/Time</th>
            <th>Difference</th>
        </tr>
    </tfoot>
    <tbody>
        @foreach($employee->clocks as $clock)
        <tr>
            <td>{{ $clock->id }}</td>
            <td>{{ $clock->inforjava() }}</td>
            <td>{{ $clock->outforjava() }}</td>
            <td>{{ $clock->indayofweek() }}</td>
            <td>{{ $clock->indate() }} / {{ $clock->intime() }} {{ Auth::user()->timezoneAdjustment() }}</td>
            <td>{{ $clock->outdayofweek() }}</td>
            <td>{{ $clock->outdate() }} / {{ $clock->outtime() }} {{ Auth::user()->timezoneAdjustment() }}</td>
            <td>{{ $clock->timedifference() }}</td>
        </tr>
        @endforeach
    </tbody>
</table>



<!-- Modal -->
<div class="modal fade" id="editClocks" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Edit Clocks</h4>
            </div>
            <div class="modal-body">
                <div class="input-group">   
                    <span class="input-group-addon" for="ssn"><div style="width: 15em;">In:</div></span>
                    <input id="in" name="in" class="form-control" style="z-index: 100000;" readonly>
                </div>
                <div class="input-group">   
                    <span class="input-group-addon" for="ssn"><div style="width: 15em;">Out:</div></span>
                    <input id="out" name="out" class="form-control" style="z-index: 100000;" readonly>
                </div>

                    <input style="visibility: hidden;" id="clockid" type="text" name="clockid" class="form-control" >

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button id="saveclocks" type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {

    $('#in').datetimepicker({
        controlType: 'select',
        parse: "loose",
        dateFormat: "yy-mm-dd",
        timeFormat: "HH:mm",
    });

    $('#out').datetimepicker({
        controlType: 'select',
        parse: "loose",
        dateFormat: "yy-mm-dd",
        timeFormat: "HH:mm",
    });

    $('#editClocks').on('show.bs.modal', function (event) {
        $row = clockstable.row('.selected').data();

        if($row[1] === ""){
            $('#in').val("");
        }else{
            $('#in').val(moment($row[1]).format('YYYY-MM-DD HH:mm'));
        }

        if($row[2] === ""){
            $('#out').val("");
        }else{
            $('#out').val(moment($row[2]).format('YYYY-MM-DD HH:mm'));
        }

        $('#clockid').val($row[0]);
    }); 
    
    $('#saveclocks').click(function(e) {
        $("body").addClass("loading");

        $timein = $('#in').val();
        $timeout = $('#out').val();
        
        $clockin = moment($timein).format('YYYY-MM-DD HH:mm:ss');
        $clockout = moment($timeout).format('YYYY-MM-DD HH:mm:ss');
        $clockid = $('#clockid').val();
        
        posting = $.post("/Account/Clock/Update",
        {
            _token: "{{ csrf_token() }}",
            id: $clockid,
            clockin: $clockin,
            clockout: $clockout
        });

        posting.done(function( data ) {

            $("body").removeClass("loading");
            //if ($.isNumeric(data)) 
            if(jQuery.type( data ) === "string")
            {
                $split = data.split(",");

                clockstable.row('.selected').data($split);

                clockstable.draw();
                
                $('#editClocks').modal('hide');
                
            }else{
                //server validation errors
                ServerValidationErrors(data);
            }
        });

        posting.fail(function() {
            $("body").removeClass("loading");
            bootstrap_alert.warning("Failed to contact server", 'danger', 4000);
        });
    });

 
    // DataTable
    var clockstable = $('#clockstable').DataTable({
        "columnDefs": [
            { "type": "datetime-moment", targets: 4 },
            { "targets": [0,1,2],"visible": false}
        ],
        "language": {
            "emptyTable": "No Data"
        },
        "order": [[ 0, "desc" ]]
    });

    $('#clockstable tbody').on( 'click', 'tr', function () {
        $row = $(this);
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            clockstable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );

    $( "#clocks-previous-page" ).click(function() {
        clockstable.page( "previous" ).draw('page');
        PageinateUpdate(clockstable.page.info(), $('#clocks-next-page'), $('#clocks-previous-page'),$('#clocks-tableInfo'));
    });

    $( "#clocks-next-page" ).click(function() {
        clockstable.page( "next" ).draw('page');
        PageinateUpdate(clockstable.page.info(), $('#clocks-next-page'), $('#clocks-previous-page'),$('#clocks-tableInfo'));
    });

    $('#clocks-search').on( 'keyup change', function () {
        clockstable.search( this.value ).draw();
        PageinateUpdate(clockstable.page.info(), $('#clocks-next-page'), $('#clocks-previous-page'),$('#clocks-tableInfo'));
    });

    PageinateUpdate(clockstable.page.info(), $('#clocks-next-page'), $('#clocks-previous-page'),$('#clocks-tableInfo'));

    $( "#clocks" ).children().find(".dataTables_filter").css('display', 'none');
    $( "#clocks" ).children().find(".dataTables_length").css('display', 'none');
    $( "#clocks" ).children().find(".dataTables_paginate").css('display', 'none');
    $( "#clocks" ).children().find(".dataTables_info").css('display', 'none');
    $('#clockstable').css('width' , "100%");

});
</script> 