

<div class="col-xs-2" style="height: 100%;"> <!-- required for floating -->
    <!-- Nav tabs -->
    <ul class="nav nav-tabs tabs-left" style="height: 100%;">
      <li class="active"><a href="#emailbasic" data-toggle="tab">Basic Settings</a></li>
      <li><a href="#emailclientquotetemplates" data-toggle="tab">Client Quote Template</a></li>
      <li><a href="#emailclientinvoicetemplates" data-toggle="tab">Client Invoice Template</a></li>
    </ul>
</div>

<div class="col-xs-10">
    <!-- Tab panes -->
    <div class="tab-content">
      <div class="tab-pane active" id="emailbasic">@include('ACP.Tabs.Email.basic')</div>
      <div class="tab-pane" id="emailclientquotetemplates">@include('ACP.Tabs.Email.clientquote')</div>
      <div class="tab-pane" id="emailclientinvoicetemplates">@include('ACP.Tabs.Email.clientinvoice')</div>
    </div>
</div>