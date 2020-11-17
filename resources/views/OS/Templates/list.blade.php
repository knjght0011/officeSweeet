@extends('master')

@section('content')
    <h3 style="margin-top: 10px;">Template Manager</h3>

    <div class="row" >
        <div class="col-md-3">
            <div class="input-group ">
                <span class="input-group-addon" for="search"><div style="width: 7em;">Search:</div></span>
                <input id="search" name="search" type="text" placeholder="" value="" class="form-control" data-validation-label="Search" data-validation-required="false" data-validation-type="">
            </div>
        </div>

        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-addon" for="Group"><div style="width: 7em;">Group:</div></span>
                <select id="Group" name="Group" type="text" placeholder="Group" class="form-control">
                    <option value="all">All</option>
                    <option value="client">{{ TextHelper::GetText("Client") }}\Prospect</option>
                    <option value="vendor">Vendor</option>
                    <option value="employee">Team/Staff</option>
                    <option value="general">General</option>
                </select>
            </div>
        </div>

        <div class="col-md-3">
            <div class="input-group ">
                <span class="input-group-addon" for="user-filter"><div style="width: 7em;">Created By:</div></span>
                <select id="user-filter" name="user-filter" type="text" placeholder="user-filter" class="form-control">
                    <option value="all">All</option>
                    @foreach(UserHelper::GetAllUsers() as $user)
                        <option value="{{ $user->getShortName() }}">{{ $user->getShortName() }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-2">
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
        <div class="col-md-3">
            <button onclick="$('body').addClass('loading'); GoToPage('/Templates/New');" class="btn OS-Button btn-sm" type="button" style="width: 100%;">Create From Scratch</button>
        </div>
        <div class="col-md-3">
            <button data-toggle="modal" data-target="#UploadDocument" class="btn OS-Button btn-sm" type="button" style="width: 100%;">Create From Word Document</button> <!--<a id="link" href="/Templates/Upload">-->
        </div>
        <div class="col-md-3">
            <button id="edit-selected" class="btn OS-Button btn-sm" type="button" style="width: 100%;">Edit Selected</button>
        </div>
        <div class="col-md-3">
            <button id="delete-selected" class="btn OS-Button btn-sm" type="button" style="width: 100%;">Delete Selected</button>
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

@if (isset($error))
    <div class="alert alert-danger">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        {{ $error }}
    </div>
@endif

@foreach ($errors->all() as $error)
    <div class="alert alert-danger">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        {{ $error }}
    </div>
@endforeach

<table id="template-list" class="table">
    <thead>
        <tr id="head">
            <th>Name</th>
            <th>Group</th>
            <th>Sub-Group</th>
            <th>Created By</th>
            <th></th>
        </tr>
    </thead>
    <tfoot style="visibility: hidden;">
        <tr>
            <th>Name</th>
            <th>Group</th>
            <th>Sub-Group</th>
            <th>Created By</th>
            <th></th>
        </tr>
    </tfoot>
    <tbody>

        @foreach($templates as $template)
        <tr>
            <td>{{ $template->name }}</td>
            <td>{{ $template->type }}</td>
            <td>{{ $template->subgroup }}</td>
            <td>{{ $template->user->getShortName() }}</td>
            <td>{{ $template->id }}</td>
        </tr>
        @endforeach
    </tbody>
</table>


<!-- Modal -->
<form action="/Templates/Upload" method="post" enctype="multipart/form-data">
    <div class="modal fade" id="UploadDocument" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Please select one of your documents to upload</h4>
                </div>
                <div class="modal-body">
                    <input name="_token" type="hidden" value="{{ csrf_token() }}">
                    <input style="width: 100%;" type="file" class="btn OS-Button btn-sm" name="fileToUpload" id="fileToUpload">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </div>
        </div>
    </div>
</form>
<script>

$(document).ready(function() {
    // DataTable
    var table = $('#template-list').DataTable({
        "columnDefs": [
            { "targets": [4],"visible": false}
        ],
    });

    $('#edit-selected').click(function () {
        $row = table.row('.selected').data();

        if ($row === undefined || $row === null) {
            $.dialog({
                title: 'Oops..',
                content: 'Please Select a Row.'
            });
        }else{
            $("body").addClass("loading");
            GoToPage('/Templates/Edit/' + $row[4])
        }

    });

    $('#template-list tbody').on( 'click', 'tr', function () {
        $row = $(this);
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );

    $( "#previous-page" ).click(function() {
        table.page( "previous" ).draw('page');
        PageinateUpdate(table.page.info(), $('#next-page'), $('#previous-page'),$('#tableInfo'));
    });

    $( "#next-page" ).click(function() {
        table.page( "next" ).draw('page');
        PageinateUpdate(table.page.info(), $('#next-page'), $('#previous-page'),$('#tableInfo'));
    });

    $('#search').on( 'keyup change', function () {
        table.search( this.value ).draw();
        PageinateUpdate(table.page.info(), $('#next-page'), $('#previous-page'),$('#tableInfo'));
    });

    $('#length').on( 'change', function () {
        table.page.len( this.value ).draw();
        PageinateUpdate(table.page.info(), $('#next-page'), $('#previous-page'),$('#tableInfo'));
    });

    $('#Group').on( 'keyup change', function () {

        if(this.value === "all") {
            table
                .columns(1)
                .search("", true)
                .draw();
        }else {
            table
                .columns(1)
                .search(this.value , true)
                .draw();
        }

        PageinateUpdate(table.page.info(), $('#next-page'), $('#previous-page'),$('#tableInfo'));
    });

    $('#user-filter').on( 'keyup change', function () {

        if(this.value === "all") {
            table
                .columns(3)
                .search("", true)
                .draw();
        }else {
            table
                .columns(3)
                .search(this.value , true)
                .draw();
        }

        PageinateUpdate(table.page.info(), $('#next-page'), $('#previous-page'),$('#tableInfo'));
    });

    PageinateUpdate(table.page.info(), $('#next-page'), $('#previous-page'),$('#tableInfo'));

    $('#delete-selected').click(function () {
        $row = table.row('.selected').data();
        if($row != undefined){
            $.confirm({
                title: 'You are about to delete a template.',
                content: '',
                buttons: {
                    confirm: function () {
                        $row = table.row('.selected').data();

                        $data = {};
                        $data['_token'] = "{{ csrf_token() }}";
                        $data['id'] = $row[4];

                        $("body").addClass("loading");

                        $post = $.post("/Templates/Delete", $data);

                        $post.done(function( data ) {
                            console.log(data);
                            $("body").removeClass("loading");
                            switch(data['status']) {
                                case "OK":
                                    table.row('.selected').remove().draw( false );
                                    break;
                                case "notfound":
                                    $.dialog({
                                        title: 'Oops...',
                                        content: 'Unknown Response from server. Please refresh the page and try again.'
                                    });
                                    break;
                                default:
                                    console.log(data);
                                    $.dialog({
                                        title: 'Oops...',
                                        content: 'Unknown Response from server. Please refresh the page and try again.'
                                    });
                            }
                        });

                        $post.fail(function()
                        {
                            NoReplyFromServer();
                        });
                    },
                    cancel: function () {

                    },
                }
            });
        }else{
            $.dialog({
                title: 'Opps...',
                content: 'Nothing Selected...'
            });
        }

    });

    $(".dataTables_filter").css('display', 'none');
    $(".dataTables_length").css('display', 'none');
    $(".dataTables_paginate").css('display', 'none');
    $(".dataTables_info").css('display', 'none');
    $('#template-list').css('width' , "100%");

});
</script>
@stop