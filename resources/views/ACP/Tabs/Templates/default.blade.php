

<div class="col-xs-2" style="height: 100%;"> <!-- required for floating -->
    <!-- Nav tabs -->
    <ul class="nav nav-tabs tabs-left" style="height: 100%;">
      <li class="active"><a href="#templates1" data-toggle="tab">Templates Tab 1</a></li>
      <li><a href="#templates2" data-toggle="tab">Sub-Groups</a></li>
      <li><a href="#templates3" data-toggle="tab">Templates Tab 3</a></li>
      <li><a href="#templates4" data-toggle="tab">Templates Tab 4</a></li>
    </ul>
</div>

<div class="col-xs-10">
    <!-- Tab panes -->
    <div class="tab-content">
      <div class="tab-pane active" id="templates1">Templates Tab 1</div>
      <div class="tab-pane" id="templates2">@include('ACP.Tabs.Templates.subgroups')</div>
      <div class="tab-pane" id="templates3">Templates Tab 3</div>
      <div class="tab-pane" id="templates4">Templates Tab 4</div>
    </div>
</div> 