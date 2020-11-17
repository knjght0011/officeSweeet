

<div class="col-xs-2" style="height: 100%;"> <!-- required for floating -->
    <!-- Nav tabs -->
    <ul class="nav nav-tabs tabs-left" style="height: 100%;">
        <li class="active"><a href="#generalschedule" data-toggle="tab">Scheduler Events</a></li> 
        <li><a href="#generalproducts" data-toggle="tab">Products/Services</a></li>
        <li><a href="#generalexpencereportcatagories" data-toggle="tab">Income/Expense Categories</a></li>
        <li><a href="#generaltemplate" data-toggle="tab">Template Sub-Groups</a></li>
        <!--<li><a href="#generalenvelope" data-toggle="tab">Envelope Mesurements</a></li>-->
        <li><a href="#generalquoteinvoice" data-toggle="tab">Quote/Invoice Layout</a></li>
        <li><a href="#generalchecks" data-toggle="tab">Check Alignment</a></li>
        <li><a href="#generalappearance" data-toggle="tab">Appearance</a></li>
    </ul>
</div>

<div class="col-xs-10">
    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane active" id="generalschedule">@include('ACP.Tabs.General.schedule')</div>
        <div class="tab-pane" id="generalproducts">@include('ACP.Tabs.General.products')</div>
        <div class="tab-pane" id="generalexpencereportcatagories">@include('ACP.Tabs.General.generalexpencereportcatagories')</div>
        <div class="tab-pane" id="generaltemplate">@include('ACP.Tabs.General.template')</div>
    <!--<div class="tab-pane" id="generalenvelope">@include('ACP.Tabs.General.envelope')</div>-->
        <div class="tab-pane" id="generalquoteinvoice">@include('ACP.Tabs.General.quoteinvoice')</div>
        <div class="tab-pane" id="generalchecks">@include('ACP.Tabs.General.checks')</div>
        <div class="tab-pane" id="generalappearance">@include('ACP.Tabs.General.general')</div>
    </div>  
</div> 