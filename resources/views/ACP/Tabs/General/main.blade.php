@extends('ACP.main')

@section('content')

<div class="col-xs-2" style="height: 100%;"> <!-- required for floating -->
    <!-- Nav tabs -->
    <ul class="nav nav-tabs tabs-left" style="height: 100%;">
        @if(Auth::user()->hasPermission('scheduler_permission'))
        <li class="active"><a href="#generalschedule" data-toggle="tab">Scheduler Events</a></li>
        @endif
        <li><a href="#generaltraining" data-toggle="tab">Training Library</a></li>
        <li><a href="#generalexpencereportcatagories" data-toggle="tab">Income/Expense Categories</a></li>
        <li><a href="#generalassetcatagories" data-toggle="tab">Asset/Liability Categories</a></li>
        <li><a href="#generaltemplate" data-toggle="tab">Template Sub-Groups</a></li>
        <li><a href="#generalquoteinvoice" data-toggle="tab">{{ TextHelper::GetText("Quote") }}/Invoice Layout</a></li>
        <li><a href="#generalchecks" data-toggle="tab">Check Alignment</a></li>
    </ul>
</div>

<div class="col-xs-10">
    <!-- Tab panes -->
    <div class="tab-content">
        @if(Auth::user()->hasPermission('scheduler_permission'))
        <div class="tab-pane active" id="generalschedule">@include('ACP.Tabs.General.schedule')</div>
        @endif
        <div class="tab-pane" id="generalassetcatagories">@include('ACP.Tabs.General.assetcatagories')</div>
        <div class="tab-pane" id="generalexpencereportcatagories">@include('ACP.Tabs.General.generalexpencereportcatagories')</div>
        <div class="tab-pane" id="generaltemplate">@include('ACP.Tabs.General.template')</div>
        <div class="tab-pane" id="generalquoteinvoice">@include('ACP.Tabs.General.quoteinvoice')</div>
        <div class="tab-pane" id="generalchecks">@include('ACP.Tabs.General.checks')</div>
        <div class="tab-pane" id="generaltraining">@include('ACP.Tabs.General.training')</div>
    </div>  
</div>

<div class="modal fade" id="subcatagoryModal" tabindex="-1" role="dialog" aria-labelledby="subcatagoryModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" >Subcategories</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="subcatagoryModalBody">
                ...
            </div>
            <div class="modal-footer">
                <button id="subcatagory-add" type="button" class="btn btn-primary">Add</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button id="subcatagory-save" type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

@stop
