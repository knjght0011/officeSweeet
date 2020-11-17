@extends('ACP.main')

@section('content')
<div class="col-xs-2" style="height: 100%;"> <!-- required for floating -->
    <!-- Nav tabs -->
    <ul class="nav nav-tabs tabs-left" style="height: 100%;">
        <li class="active"><a href="#sub-summery" data-toggle="tab">Details</a></li>
        <li><a href="#sub-update" data-toggle="tab">Update Subscription</a></li>
        <li><a href="#sub-cancel" data-toggle="tab">Cancel Subscription</a></li>
        @if(app()->make('account')->plan_name === "SOLO")
        <li><a target="_blank" href="https://www.officesweeet.com/system-features-details" >OfficeSweeet EBM Features</a></li>
        @endif
    </ul>
</div>

<div class="col-xs-10">
    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane active" id="sub-summery">@include('ACP.Tabs.Subscription.summery')</div>
        <div class="tab-pane" id="sub-update">@include('ACP.Tabs.Subscription.update')</div>
        <div class="tab-pane" id="sub-cancel">@include('ACP.Tabs.Subscription.cancel')</div>
    </div>
</div>
@stop