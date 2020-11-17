@extends('master')

@section('content')

@if(Auth::user()->hasPermission('bulk_email_permission'))
    <h3 style="margin-top: 10px;">Manage Group Emails</h3>
@else
    <h3 style="margin-top: 10px;">Manage Emails</h3>
@endif

<div role="tabpanel" class="tab-pane">
    <ul class="nav nav-tabs" role="tablist">

        @if(Auth::User()->hasPermission('bulk_email_permission'))
            <li role="presentation" class="active" style="padding-top: 5px;"><a href="#SendGroupEmail" aria-controls="profile" role="tab" data-toggle="tab">Send Group Email</a></li>
            <li role="presentation" style="padding-top: 5px;"><a href="#EmailTemplateManager" aria-controls="profile" role="tab" data-toggle="tab">Email Template Manager</a></li>
            <li role="presentation" style="padding-top: 5px;"><a href="#SentStatus" aria-controls="profile" role="tab" data-toggle="tab">Sent Status</a></li>
        @else
            <li role="presentation" class="active" style="padding-top: 5px;"><a href="#SentStatus" aria-controls="profile" role="tab" data-toggle="tab">Sent Status</a></li>
        @endif

    </ul>

    <div class="tab-content" style="width: 100%;">


        @if(Auth::User()->hasPermission('bulk_email_permission'))
            <div role="tabpanel" class="tab-pane" id="EmailTemplateManager">
                @include('OS.EmailTemplate.list')
            </div>
            <div role="tabpanel" class="tab-pane active" id="SendGroupEmail">
                @include('OS.Email.bulksend')
            </div>
            <div role="tabpanel" class="tab-pane" id="SentStatus">
                @include('OS.Email.overview')
            </div>
        @else
            <div role="tabpanel" class="tab-pane active" id="SentStatus">
                @include('OS.Email.overview')
            </div>
        @endif
    </div>
</div>

@stop