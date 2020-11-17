@extends('ACP.main')

@section('content')

<div class="col-xs-2" style="height: 100%;"> <!-- required for floating -->
    <!-- Nav tabs -->
    <ul class="nav nav-tabs tabs-left" style="height: 100%;">
      <li class="active"><a href="#import" data-toggle="tab">Import</a></li>
      <li><a href="#export" data-toggle="tab">Export</a></li>
    </ul>
</div>

<div class="col-xs-10">
    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane active" id="import">@include('ACP.Tabs.ImportExport.import')</div>
        <div class="tab-pane" id="export">@include('ACP.Tabs.ImportExport.export')</div>
    </div>  
</div>

@stop
