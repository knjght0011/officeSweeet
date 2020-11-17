@extends('master')

@section('content')

<div class="row" style="margin-top: 20px;">
    <div class="col-md-3">
    @if($type === "client")
        <a id="link" href="/Clients/View/{{ $dataID }}"><button style="width: 100%;" class="btn OS-Button" type="button">Back to Client</button></a>
    @else
        @if($type === "vendor")
            <a id="link" href="/Vendors/View/{{ $dataID }}"><button style="width: 100%;" class="btn OS-Button" type="button">Back to Vendor</button></a>
        @else
            <a id="link" href="/Employees/View/{{ $dataID }}"><button style="width: 100%;" class="btn OS-Button" type="button">Back to Employee</button></a>
        @endif
    @endif
    </div>
    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="search"><div style="width: 7em;">Search:</div></span>
            <input id="search" name="search" type="text" placeholder="" value="" class="form-control" data-validation-label="Search" data-validation-required="false" data-validation-type="">
        </div>
    </div>

    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="length"><div style="width: 7em;">Show:</div></span>
            <select id="length" name="length" type="text" placeholder="choice" class="form-control">
                <option value="10">10 entries</option>
                <option value="25">25 entries</option>
                <option value="50">50 entries</option>
                <option value="100">100 entries</option>
            </select>
        </div>
    </div>
</div>


<div class="row" style="margin-top: 15px;">
    <div class="col-md-2">
        <button id="previous-page" name="previous-page" type="button" class="btn OS-Button" style="width: 100%;">Previous</button>
    </div>
    <div class="col-md-8" id="tableInfo" style="text-align: center;">

    </div>
    <div class="col-md-2">
        <button id="next-page" name="next-page" type="button" class="btn OS-Button" style="width: 100%;">Next</button>
    </div>
</div>


<table id="template-list" class="table">
    <thead>
        <tr id="head">
            <th>Name</th>
            <th>Type</th>
            <th>Created By</th>
        </tr>
    </thead>
    <tfoot style="visibility: hidden;">
        <tr>
            <th>Name</th>
            <th>Type</th>
            <th>Created By</th>
        </tr>
    </tfoot>
    <tbody>

        @foreach($templates as $template)
        <tr>
            <td><a id="link" href="/Documents/Generate/{{ $template->id }}/{{ $dataID }}">{{ $template->name }}</a></td>
            <td>{{ $template->type }}</td>
            <td>{{ $template->user->email }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

    
<script>

$(document).ready(function() { 
    // DataTable
    var templatelist = $('#template-list').DataTable();

    $('#template-list tbody').on( 'click', 'tr', function () {
        $row = $(this);
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            templatelist$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );

    $( "#previous-page" ).click(function() {
        templatelist.page( "previous" ).draw('page');
        PageinateUpdate(templatelistpage.info(), $('#next-page'), $('#previous-page'),$('#tableInfo'));
    });

    $( "#next-page" ).click(function() {
        templatelist.page( "next" ).draw('page');
        PageinateUpdate(templatelistpage.info(), $('#next-page'), $('#previous-page'),$('#tableInfo'));
    });

    $('#search').on( 'keyup change', function () {
        templatelist.search( this.value ).draw();
        PageinateUpdate(templatelist.page.info(), $('#next-page'), $('#previous-page'),$('#tableInfo'));
    });

    $('#length').on( 'change', function () {
        templatelist.page.len( this.value ).draw();
        PageinateUpdate(templatelist.page.info(), $('#next-page'), $('#previous-page'),$('#tableInfo'));
    });

    $('#Group').on( 'keyup change', function () {

        if(this.value === "all") {
            templatelist
                .columns(1)
                .search("", true)
                .draw();
        }else {
            templatelist
                .columns(1)
                .search(this.value , true)
                .draw();
        }

        PageinateUpdate(templatelist.page.info(), $('#next-page'), $('#previous-page'),$('#tableInfo'));
    });

    $('#user-filter').on( 'keyup change', function () {

        if(this.value === "all") {
            templatelist
                .columns(3)
                .search("", true)
                .draw();
        }else {
            templatelist
                .columns(3)
                .search(this.value , true)
                .draw();
        }

        PageinateUpdate(templatelist.page.info(), $('#next-page'), $('#previous-page'),$('#tableInfo'));
    });

    PageinateUpdate(templatelist.page.info(), $('#next-page'), $('#previous-page'),$('#tableInfo'));

    $(".dataTables_filter").css('display', 'none');
    $(".dataTables_length").css('display', 'none');
    $(".dataTables_paginate").css('display', 'none');
    $(".dataTables_info").css('display', 'none');
    $('#template-list').css('width' , "100%");



} );
</script>

{{ Link::General("currentdatewords") }}

@stop
