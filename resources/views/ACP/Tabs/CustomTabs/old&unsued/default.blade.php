

<div class="col-xs-2" style="height: 100%;"> <!-- required for floating -->
    <!-- Nav tabs -->
    <ul class="nav nav-tabs tabs-left" style="height: 100%;">
        
        <li class="active"><a href="#customtabs-new" data-toggle="tab">Tab Designer</a></li>
        <li><a href="#customtabs-manage" data-toggle="tab">Tab Manager</a></li>
    </ul>
</div>

<div class="col-xs-10">
    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane active" id="customtabs-new">@include('ACP.Tabs.CustomTabs.new')</div>
        <div class="tab-pane" id="customtabs-manage">@include('ACP.Tabs.CustomTabs.manage')</div>
    </div>
</div> 