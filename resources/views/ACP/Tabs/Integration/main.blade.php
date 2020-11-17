@extends('ACP.main')

@section('content')
<div class="col-xs-2" style="height: 100%;"> <!-- required for floating -->
    <!-- Nav tabs -->
    <ul class="nav nav-tabs tabs-left" style="height: 100%;">
        <li class="active"><a href="#transnational-application" data-toggle="tab">Transnational Application</a></li>
        <li><a href="#transnational-settings" data-toggle="tab">Transnational Settings</a></li>
        <li><a href="#gmail" data-toggle="tab">GMail Settings</a></li>
    </ul>
</div>

<div class="col-xs-10">
    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane active" id="transnational-application">@include('ACP.Tabs.Integration.application')</div>
        <div class="tab-pane" id="transnational-settings">@include('ACP.Tabs.Integration.settings')</div>
        <div class="tab-pane" id="gmail">@include('ACP.Tabs.Integration.gmail')</div>
    </div>  
</div>
@stop