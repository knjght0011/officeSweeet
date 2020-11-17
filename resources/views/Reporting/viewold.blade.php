@extends('master')

@section('content')   
<div class="row" style="margin-bottom: 5px;">
    <div style="float:left; width: 10em;  margin-left: 20px;">
        <button style="width: 100%;" type="button" class="btn OS-Button" data-toggle="modal" data-target="#ShowHelpHub" data-tab="wiki" data-url="k-PXW-ul0Fo">Show Tutorial</button>
    </div>
</div> 

<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#tab1" aria-controls="profile" role="tab" data-toggle="tab">Daily Reports</a></li>
    <li role="presentation"><a href="#tab2" aria-controls="profile" role="tab" data-toggle="tab">Month End Reports</a></li>

</ul>

<div class="tab-content">

    <div role="tabpanel" class="tab-pane active" id="tab1">
        @include('Reporting.tabs.daily')
    </div>

    <div role="tabpanel" class="tab-pane" id="tab2">
        @include('Reporting.tabs.monthly')
        
    </div>

</div>
@stop