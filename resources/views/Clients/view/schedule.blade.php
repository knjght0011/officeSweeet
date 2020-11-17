<div class="row" style="margin-top: 15px;">
    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="search"><div style="width: 7em;">Search:</div></span>
            <input id="schedule-search" name="schedule-search" type="text" placeholder="" value="" class="form-control">
        </div>
    </div>
    <div class="col-md-6">
        {!! PageElement::TableControl('schedule') !!}
    </div>
    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="schedule-status"><div style="width: 7em;">Status:</div></span>
            <select id="schedule-status" name="schedule-status" type="text" placeholder="choice" class="form-control">
                <option value="current" selected>All Pending & Today</option>
                <option value="pending">Pending</option>
                <option value="today">Today</option>
                <option value="past">Past</option>
                <option value="canceled">Canceled</option>
                <option value="all">All</option>
            </select>
        </div>
    </div>
</div>

<table id="schedulesearch" class="table">
    <thead>
        <tr>
            <th class="col-md-2">Date</th>
            <th class="col-md-2">Title</th>
            <th class="col-md-2">Details</th>
            <th class="col-md-2">Team\Staff</th>
            <th class="col-md-2">Status</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th class="col-md-2">Start</th>
            <th class="col-md-2">Title</th>
            <th class="col-md-2">Details</th>
            <th class="col-md-2">Team\Staff</th>
            <th class="col-md-2">Status</th>
        </tr>
    </tfoot>
    <tbody>
    @foreach($client->getFutureSchedule() as $schedule)
        <tr>
            <td>{{ $schedule->start }}</td>
            <td>{{ $schedule->title }}</td>
            @if(strlen($schedule->contents) >50)
                <td>
                    <a data-toggle="tooltip" data-placement="bottom" title="{{ $schedule->contents }}" >{{ substr($schedule->contents,0,50)."..." }}</a>
                </td>
            @else
                <td>{{ $schedule->contents }}</td>
            @endif
            <td>{{ $schedule->firstname }} {{ $schedule->lastname }}</td>
            @if($schedule->deleted_at === null)
                <td>Pending</td>
            @else
                <td>Canceled</td>
            @endif
        </tr>
    @endforeach
    @foreach($client->getCurrentSchedule() as $schedule)
        <tr>
            <td>{{ $schedule->start }}</td>
            <td>{{ $schedule->title }}</td>
            @if(strlen($schedule->contents) >50)
                <td>
                    <a data-toggle="tooltip" data-placement="bottom" title="{{ $schedule->contents }}" >{{ substr($schedule->contents,0,50)."..." }}</a>
                </td>
            @else
                <td>{{ $schedule->contents }}</td>
            @endif
            <td>{{ $schedule->firstname }} {{ $schedule->lastname }}</td>
            @if($schedule->deleted_at === null)
                <td>Today</td>
            @else
                <td>Canceled</td>
            @endif
        </tr>
    @endforeach
    @foreach($client->getPreviousSchedule() as $schedule)
        <tr>
            <td>{{ $schedule->start }}</td>
            <td>{{ $schedule->title }}</td>
            @if(strlen($schedule->contents) >50)
                <td>
                    <a data-toggle="tooltip" data-placement="bottom" title="{{ $schedule->contents }}" >{{ substr($schedule->contents,0,50)."..." }}</a>
                </td>
            @else
                <td>{{ $schedule->contents }}</td>
            @endif
            <td>{{ $schedule->firstname }} {{ $schedule->lastname }}</td>
            @if($schedule->deleted_at === null)
                <td>Past</td>
            @else
                <td>Canceled</td>
            @endif
        </tr>
    @endforeach

    </tbody>
</table>


<script>
    $(document).ready(function() {
        // DataTable
        var schedulesearch = $('#schedulesearch').DataTable(
            {
                "language": {
                    "emptyTable": "No Data"
                },
                "order": [[ 0, "desc" ]]
            }
        );

        $( "#schedule-previous-page" ).click(function() {
            schedulesearch.page( "previous" ).draw('page');
            PageinateUpdate(schedulesearch.page.info(), $('#schedule-next-page'), $('#schedule-previous-page'),$('#schedule-tableInfo'));
        });

        $( "#schedule-next-page" ).click(function() {
            schedulesearch.page( "next" ).draw('page');
            PageinateUpdate(schedulesearch.page.info(), $('#schedule-next-page'), $('#schedule-previous-page'),$('#schedule-tableInfo'));
        });

        $('#schedule-search').on( 'keyup change', function () {
            schedulesearch.search( this.value ).draw();
            PageinateUpdate(schedulesearch.page.info(), $('#schedule-next-page'), $('#schedule-previous-page'),$('#schedule-tableInfo'));
        });


        $('#schedule-status').on( 'keyup change', function () {
            switch(this.value) {
                case "all":
                    schedulesearch
                        .columns( 4 )
                        .search( "" , true)
                        .draw();
                    break;
                case "current":
                    schedulesearch
                        .columns( 4 )
                        .search( "pending|today" , true)
                        .draw();
                    break;
                default:
                    schedulesearch
                        .columns( 4 )
                        .search( this.value , true)
                        .draw();
                    break;
            }

            PageinateUpdate(schedulesearch.page.info(), $('#schedule-next-page'), $('#schedule-previous-page'),$('#schedule-tableInfo'));
        });
        $('#schedule-status').change();


        PageinateUpdate(schedulesearch.page.info(), $('#schedule-next-page'), $('#schedule-previous-page'),$('#schedule-tableInfo'));

        $( "#schedules" ).children().find(".dataTables_filter").css('display', 'none');
        $( "#schedules" ).children().find(".dataTables_length").css('display', 'none');
        $( "#schedules" ).children().find(".dataTables_paginate").css('display', 'none');
        $( "#schedules" ).children().find(".dataTables_info").css('display', 'none');
        $('#schedulesearch').css('width' , "100%");

    });
</script>