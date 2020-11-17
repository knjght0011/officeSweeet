<div class="col-xs-2" style="height: 100%;"> <!-- required for floating -->
    <!-- Nav tabs -->
    <ul class="nav nav-tabs tabs-left" style="height: 100%;">
      <li class="active"><a href="#transnational-application" data-toggle="tab">Application</a></li>
      <li><a href="#transnational-settings" data-toggle="tab">Settings</a></li>
    </ul>
</div>

<div class="col-xs-10">
    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane active" id="transnational-application">@include('ACP.Tabs.Transnational.application')</div>
        <div class="tab-pane" id="transnational-settings">@include('ACP.Tabs.Transnational.settings')</div>
    </div>  
</div> 