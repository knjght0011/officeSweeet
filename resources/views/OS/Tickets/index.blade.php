@extends('master')

@section('content')

    <h3 style="margin-top: 10px;">Tickets</h3>

<div class="row">
    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon"><div style="width: 7em;">Search:</div></span>
            <input type="text" class="form-control" onkeyup="window._ticketSystem.searchTable($(this).val())">
        </div>
    </div>

    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon"><div style="width: 7em;">Status:</div></span>
            <select id="ticket-status-filter" type="text" placeholder="choice" class="form-control" >

                <option value="Open">Open</option>
                <option value="On Hold">On Hold</option>
                <option value="Closed">Closed</option>

            </select>
        </div>
    </div>

    <div class="col-md-12">
        {!! PageElement::TableControl('ticket-status') !!}
    </div>


</div>

<div class="container">
    <table class="table" id="thread-table">
        <thead>
            <tr>
                <th>id</th>
                <th>Subject</th>
                <th>Subdomain</th>
                <th>Status</th>
                <th>Last Message</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>

<script>
$(document).ready(function () {

    $('#thread-table tbody').on('click', 'tr', function (e) {
        e.stopPropagation();
        window._ticketSystem.tableClick(this);
    });

    $( "#ticket-status-previous-page" ).click(function() {
        window._ticketSystem.threadtable.page( "previous" ).draw('page');
        PageinateUpdate(window._ticketSystem.threadtable.page.info(), $('#ticket-status-next-page'), $('#ticket-status-previous-page'),$('#ticket-status-tableInfo'));
    });

    $( "#ticket-status-next-page" ).click(function() {
        window._ticketSystem.threadtable.page( "next" ).draw('page');
        PageinateUpdate(window._ticketSystem.threadtable.page.info(), $('#ticket-status-next-page'), $('#ticket-status-previous-page'),$('#ticket-status-tableInfo'));
    });

    $('#ticket-status-search').on( 'keyup change', function () {
        window._ticketSystem.threadtable.search( this.value ).draw();
        PageinateUpdate(window._ticketSystem.threadtable.page.info(), $('#ticket-status-next-page'), $('#ticket-status-previous-page'),$('#ticket-status-tableInfo'));
    });

    $('#ticket-status-filter').on( 'change', function () {

        window._ticketSystem.threadtable
            .columns( 3 )
            .search( $(this).val() , true)
            .draw();


        PageinateUpdate(window._ticketSystem.threadtable.page.info(), $('#ticket-status-next-page'), $('#ticket-status-previous-page'),$('#ticket-status-tableInfo'));
    });

});
</script>
@stop