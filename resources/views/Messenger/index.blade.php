@extends('master')

@section('content')

    <h3 style="margin-top: 10px;">Message Centre</h3>

<div class="row" style="margin-top: 15px;">
    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="search"><div style="width: 7em;">Search:</div></span>
            <input id="message-search" name="message-search" type="text" placeholder="" value="" class="form-control">
        </div>
    </div>
    <div class="col-md-6">
        {!! PageElement::TableControl('message') !!}
    </div>
    <div class="col-md-2">
        <button type="button" class="btn OS-Button btn-sm" data-toggle="modal" data-target="#createmocal" style="width: 100%">
            New Message
        </button>
    </div>
</div>


    @if (Session::has('error_message'))
        <div class="alert alert-danger" role="alert">
            {{ Session::get('error_message') }}
        </div>
    @endif




<table id="search" class="table">
    <thead>
        <tr id="head">
            <th>Subject</th>
            <th>Creator</th>
            <th>Participants</th>
            <th>Those Unread</th>
            <th>Updated</th>
            <th>Created</th>
        </tr>
    </thead>
    <tfoot style="visibility: hidden;">
        <tr>
            <th>Subject</th>
            <th>Creator</th>
            <th>Participants</th>
            <th>Those Unread</th>
            <th>Updated</th>
            <th>Created</th>
        </tr>
    </tfoot>
    <tbody>

        @foreach($threads as $thread)
            
            @if($thread->isUnread($currentUserId))
                <tr style="font-weight: bold;">
            @else
                <tr>
            @endif
                <td>{!! link_to('messages/' . $thread->id, $thread->subject) !!}</td>
                <td>{{ $thread->creator()->name }}</td>
                <td>{{ $thread->participantsString(Auth::id()) }}</td>
                <td>{!! MessagesHelper::numberOfUnreadParticapants($thread) !!}</td>
                <td>{!! FormatingHelper::DateTimeISO($thread->updated_at) !!}</td>
                <td>{!! FormatingHelper::DateTimeISO($thread->created_at) !!}</td>
            </tr>
        @endforeach
    </tbody>
</table>

@include('Messenger.create')

<script>    
$(document).ready(function() {
    // DataTable
    var inbox = $('#search').DataTable({
            "order": [[ 4, "desc" ]]
    });

    $( "#message-previous-page" ).click(function() {
        inbox.page( "previous" ).draw('page');
        PageinateUpdate(inbox.page.info(), $('#message-next-page'), $('#message-previous-page'),$('#message-tableInfo'));
    });

    $( "#message-next-page" ).click(function() {
        inbox.page( "next" ).draw('page');
        PageinateUpdate(inbox.page.info(), $('#message-next-page'), $('#message-previous-page'),$('#message-tableInfo'));
    });

    $('#message-search').on( 'keyup change', function () {
        inbox.search( this.value ).draw();
        PageinateUpdate(inbox.page.info(), $('#message-next-page'), $('#message-previous-page'),$('#message-tableInfo'));
    });

    PageinateUpdate(inbox.page.info(), $('#message-next-page'), $('#message-previous-page'),$('#message-tableInfo'));

    $(".dataTables_filter").css('display', 'none');
    $(".dataTables_length").css('display', 'none');
    $(".dataTables_paginate").css('display', 'none');
    $(".dataTables_info").css('display', 'none');
    $('#search').css('width' , "100%");

} );
</script> 
@stop