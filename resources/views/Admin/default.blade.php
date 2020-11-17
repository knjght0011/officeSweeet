@extends('master')

@section('content')
<div class="well fulliframe">
    
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#general" aria-controls="home" role="tab" data-toggle="tab">Template Settings</a></li>
        <li role="presentation"><a href="#template" aria-controls="profile" role="tab" data-toggle="tab">Template Settings</a></li>
        <li role="presentation"><a href="#branch" aria-controls="profile" role="tab" data-toggle="tab">Branch Settings</a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="general">
            @include('Admin.Settings.Tabs.general')
        </div>

        <div role="tabpanel" class="tab-pane" id="template">
            @include('Admin.Settings.Tabs.template')
        </div>

        <div role="tabpanel" class="tab-pane" id="branch">
            @include('Admin.Settings.Tabs.branch')
        </div>
    </div>
    
</div>

<script>
    
$('#myTabs a').click(function (e) {
  e.preventDefault()

  $(this).tab('show')
})

$('#myTabs a[href="#general"]').tab('show')
$('#myTabs a[href="#template"]').tab('show')
$('#myTabs a[href="#branch"]').tab('show')

</script>
@stop

