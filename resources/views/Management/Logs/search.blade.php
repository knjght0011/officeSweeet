@extends('master')

@section('content')

<div class="row" style="margin-top: 15px;">
    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="search"><div>Search:</div></span>
            <input id="log-search" name="log-search" type="text" placeholder="" value="" class="form-control">
        </div>
    </div>
    <div class="col-md-6">
        {!! PageElement::TableControl('log') !!}
    </div>
    <div class="col-md-3">

    </div>
</div>    

<table id="search" class="table">
    <thead>
        <tr id="head">
            <th>ID</th>
            <th>Level</th>
            <th>Message</th>
            <th>User</th>
            <th>Created at</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>ID</th>
            <th>Level</th>
            <th>Message</th>
            <th>User</th>
            <th>Created at</th>
        </tr>
    </tfoot>
    <tbody>
        @foreach($logs->reverse() as $log)
        <tr>
            <td>
                <a href="/Logs/View/{{ $subdomain }}/{{ $log->id }}">{{ $log->id }}</a>
            </td>
            <td>
                {{ $log->level_name }}
            </td>
            <td>
                {{ $log->messageFirstLine() }}
            </td>
            <td>
                @if($log->getUser() !== null)
                {{ $log->getUser()->email }}
                @endif
            </td>
            <td>
                {{ $log->created_at }}
            </td>
        </tr>
        @endforeach      
    </tbody>
</table>

<script>
$(document).ready(function() {
    // DataTable
    var table = $('#search').DataTable(
        {
            "language": {
                "emptyTable": "No Data"
            },
            "order": [[ 0, "desc" ]]
        }
    );

    $( "#log-previous-page" ).click(function() {
        table.page( "previous" ).draw('page');
        PageinateUpdate(table.page.info(), $('#log-next-page'), $('#log-previous-page'),$('#log-tableInfo'));
    });

    $( "#log-next-page" ).click(function() {
        table.page( "next" ).draw('page');
        PageinateUpdate(table.page.info(), $('#log-next-page'), $('#log-previous-page'),$('#log-tableInfo'));
    });

    $('#log-search').on( 'keyup change', function () {
        table.search( this.value ).draw();
        PageinateUpdate(table.page.info(), $('#log-next-page'), $('#log-previous-page'),$('#log-tableInfo'));
    });

    PageinateUpdate(table.page.info(), $('#log-next-page'), $('#log-previous-page'),$('#log-tableInfo'));

    $(".dataTables_filter").css('display', 'none');
    $(".dataTables_length").css('display', 'none');
    $(".dataTables_paginate").css('display', 'none');
    $(".dataTables_info").css('display', 'none');
    $('#search').css('width' , "100%");
} );
</script> 

@stop