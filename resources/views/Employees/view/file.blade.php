<div class="row" style="margin-top: 15px;">
    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="search"><div style="width: 7em;">Search:</div></span>
            <input id="docs-search" name="docs-search" type="text" placeholder="" value="" class="form-control">
        </div>
    </div>
    <div class="col-md-6">
        {!! PageElement::TableControl('docs') !!}
    </div>
    <div class="col-md-3">

    </div>
</div>

<table id="reportsearch" class="table">
    <thead>
    <tr id="head">
        <th>Name</th>
        <th>Created By</th>
        <th>Last Edited</th>
        <th></th>
    </tr>
    </thead>
    <tfoot style="visibility: hidden;">
    <tr>
        <th>Name</th>
        <th>Created By</th>
        <th>Last Edited</th>
        <th></th>
    </tr>
    </tfoot>
    <tbody>
    @foreach($employee->reports as $report)
        @if($report->originalreport_id === null)
            <tr value="{{ $report->id }}">
                <td><a id="link" href="/Documents/Edit/{{ $report->id }}">{{ $report->name }}</a></td>
                <td>{{ $report->getUserCreatedBy() }}</td>
                <td>{{ $report->formatDateTime_updated_at_iso() }}</td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            Options
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" style="margin-top: 0px;">
                            <li><a href="#" data-toggle="modal" data-target="#reportversions{{ $report->id }}">Version History</a></li>
                            <li><a href="#" data-toggle="modal" data-target="#ShowPdfModel" data-title="View Document" data-url="/Documents/PDF/{{ $report->id }}">Download PDF</a></li>
                            <li><a href="#" data-toggle="modal" data-target="#doc-email-modal" data-reportid="{{ $report->id }}" data-mode="send">Email Document</a></li>
                            <li><a href="/Signing/Setup/{{ $report->id }}">Setup For Signing</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
        @endif
    @endforeach
    </tbody>
</table>

<!--Report History Model-->
@foreach($employee->reports as $report)
    @if($report->originalreport_id === null)
        <div id="reportversions{{ $report->id }}" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">
                            Edit History
                        </h4>
                    </div>
                    <div class="modal-body">
                        <table id="" class="table">
                            <thead>
                            <tr id="head">
                                <th>Name</th>
                                <th>Date Edited</th>
                                <th>Saved By</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($employee->reports as $r)
                                @if($r->originalreport_id == $report->id)
                                    <tr>
                                        <td>{{ $r->name }}</a></td>
                                        <td>{{ $r->formatDate_created_at() }}</td>
                                        <td>{{ $r->getUserCreatedBy() }}</td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach

<script>
    $(document).ready(function() {


        // DataTable
        var reporttable = $('#reportsearch').DataTable({
            "order": [[ 2, "desc" ]]
        });

        $( "#docs-previous-page" ).click(function() {
            reporttable.page( "previous" ).draw('page');
            PageinateUpdate(reporttable.page.info(), $('#docs-next-page'), $('#docs-previous-page'),$('#docs-tableInfo'));
        });

        $( "#docs-next-page" ).click(function() {
            reporttable.page( "next" ).draw('page');
            PageinateUpdate(reporttable.page.info(), $('#docs-next-page'), $('#docs-previous-page'),$('#docs-tableInfo'));
        });

        $('#docs-search').on( 'keyup change', function () {
            reporttable.search( this.value ).draw();
            PageinateUpdate(reporttable.page.info(), $('#docs-next-page'), $('#docs-previous-page'),$('#docs-tableInfo'));
        });

        PageinateUpdate(reporttable.page.info(), $('#docs-next-page'), $('#docs-previous-page'),$('#docs-tableInfo'));

        $( "#docs" ).children().find(".dataTables_filter").css('display', 'none');
        $( "#docs" ).children().find(".dataTables_length").css('display', 'none');
        $( "#docs" ).children().find(".dataTables_paginate").css('display', 'none');
        $( "#docs" ).children().find(".dataTables_info").css('display', 'none');
        $('#reportsearch').css('width' , "100%");
    } );
</script>