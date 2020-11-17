@extends('ACP.main')

@section('content')

<div class="col-xs-2" style="height: 100%;"> <!-- required for floating -->
    <!-- Nav tabs -->
    <ul class="nav nav-tabs tabs-left" style="height: 100%;">
      <li class="active"><a href="#companyinfo-namelogo" data-toggle="tab">Company Info</a></li>
      <li><a href="#companyinfo-branch" data-toggle="tab">Branche/Location</a></li>
    </ul>
</div>

<div class="col-xs-10">
    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane active" id="companyinfo-namelogo">@include('ACP.Tabs.CompanyInfo.namelogo')</div>
        <div class="tab-pane" id="companyinfo-branch">@include('ACP.Tabs.CompanyInfo.branch')</div>
    </div>  
</div>

@stop
