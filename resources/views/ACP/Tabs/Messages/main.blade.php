@extends('ACP.main')

@section('content')

<div class="col-xs-2" style="height: 100%;"> <!-- required for floating -->
    <!-- Nav tabs -->
    <ul class="nav nav-tabs tabs-left" style="height: 100%;">
        <li class="active"><a href="#emailclientquotetemplates" data-toggle="tab">Email Comment for {{ TextHelper::GetText("Quotes") }}</a></li>
        <li><a href="#emailclientinvoicetemplates" data-toggle="tab">Email Comment for Invoices</a></li>
        <li><a href="#PurchaseOrderComment" data-toggle="tab">Purchase Order Comment</a></li>
        <li><a href="#QuoteComment" data-toggle="tab">{{ TextHelper::GetText("Quote") }} Comment</a></li>
    </ul>
</div>

<div class="col-xs-10">
    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane active" id="emailclientquotetemplates">@include('ACP.Tabs.Messages.clientquote')</div>
        <div class="tab-pane" id="emailclientinvoicetemplates">@include('ACP.Tabs.Messages.clientinvoice')</div>
        <div class="tab-pane" id="PurchaseOrderComment">@include('ACP.Tabs.Messages.purchaseordercomment')</div>
        <div class="tab-pane" id="QuoteComment">@include('ACP.Tabs.Messages.quotecomment')</div>
    </div>
</div>
@stop