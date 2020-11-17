<style>
    .dataTables_filter {
        display: none;
    }
    .dataTables_length {
        display: none;
    }
    .dataTables_info {
        display: none;
    }
    .dataTables_paginate {
        display: none;
    }
</style>
<div class="row" style="margin-top: 20px;">
    <div class="col-md-4">
        <div class="input-group ">
            <span class="input-group-addon" for="Schedule-search"><div style="width: 7em;">Search:</div></span>
            <input id="Schedules-search" name="Schedule-search" type="text" placeholder="" value="" class="form-control">
        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group ">
            <span class="input-group-addon" for="Schedule-length"><div style="width: 7em;">Show:</div></span>
            <select id="Schedules-length" name="Schedule-length" type="text" placeholder="choice"
                    class="form-control">
                <option value="10">10 entries</option>
                <option value="25">25 entries</option>
                <option value="50">50 entries</option>
                <option value="100">100 entries</option>
            </select>
        </div>
    </div>

    <div class="col-md-1">
        <button class="btn OS-Button" type="button" data-toggle="modal" data-target="#ScheduleSearchRowFilter" style="width: 100%;">Column Filter</button>
    </div>
</div>

<div class="row" style="margin-top: 5px;">
    <div class="col-md-12">
        {!! PageElement::TableControl('schedules') !!}
    </div>
</div>

<table id="ScheduleSearch-table" class="table">
    <thead>
    <tr id="head">
        <th class="datatables-invisible-col">ID</th>
        <th>Title</th>
        <th>Start Time</th>
        <th>Contents</th>
    </tr>
    </thead>
    <tfoot style="visibility: hidden;">
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Start Time</th>
        <th>Contents</th>
    </tr>
    </tfoot>
    <tbody>

    </tbody>
</table>

<div class="modal fade" id="ScheduleSearchRowFilter" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Row Filter</h4>
            </div>
            <div class="modal-body">
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Title:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="title" data-type="schedules" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Start Time:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="start" data-type="schedules" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Contents:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="contents" data-type="schedules" checked>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Save & Close</button>
            </div>
        </div>
    </div>
</div>



<script>
    $(document).ready(function () {

        // DataTable
        window.searchtable = $('#ScheduleSearch-table').DataTable({
            "order": [[ 0, "desc" ]],
            "language": {
                "emptyTable": "No Data"
            },
            "processing": true,
            "serverSide": true,
            "deferRender ": true,
            "ajax": "/Scheduling/Json",
            "pageLength": 10,
            "drawCallback": function(){PageinateUpdate(window.searchtable.page.info(), $('#schedules-next-page'), $('#schedules-previous-page'),$('#schedules-tableInfo'));},
            "columns": [
                { "data": "event_id", "name": "event_id" },
                { "data": "title", "name": "title" },
                { "data": "start", "name": "start" },
                { "data": "contents", "name": "contents" },
            ],
            "columnDefs": [
                {
                    "targets": "datatables-invisible-col",
                    "visible": false
                }
            ],
        });

        $( "#schedules-previous-page" ).click(function() {
            window.searchtable.page( "previous" ).draw('page');
        });

        $( "#schedules-next-page" ).click(function() {
            alert('test');
            window.searchtable.page( "next" ).draw('page');
        });

        $('#Schedules-search').on( 'keyup change', function () {
            window.searchtable.search( this.value ).draw();
        });

        $('#Schedules-length').on( 'change', function () {
            window.searchtable.page.len( this.value ).draw();
        });

        //$('#Schedule-status').change();

        $('#schedules-table').css('width', '100%');

        @foreach(Auth::user()->getHomeColOptions('schedules') as $key => $value)
                @if($value === "0")

        $tablecol.visible(false);

        $('.col-filter-check[data-col="{{ $key }}"][data-type="schedules"]').bootstrapToggle('destroy');
        $('.col-filter-check[data-col="{{ $key }}"][data-type="schedules"]').prop('checked', false);
        $('.col-filter-check[data-col="{{ $key }}"][data-type="schedules"]').bootstrapToggle();

        @endif
        @endforeach
        //window.clientstable.columns.adjust().draw(false);
    });
</script>