@extends('ACP.main')

@section('content')

<div class="col-xs-2" style="height: 100%;"> <!-- required for floating -->
    <!-- Nav tabs -->
    <ul class="nav nav-tabs tabs-left" style="height: 100%;">
        
        <li class="active"><a href="#customtabs-new" data-toggle="tab">Tab Designer</a></li>

    </ul>
</div>

<div class="col-xs-10">
    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane active" id="customtabs-new">@include('ACP.Tabs.CustomTabs.new')</div>

    </div>
</div>
@stop