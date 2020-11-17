@extends('cardmaster')

@section('content')
    <style>
        tr:nth-child(odd)		{ background-color:#eee; }
        tr:nth-child(even)		{ background-color:#fff; }
        th { background-color:lightblue; }
    </style>
    
<link rel='stylesheet' type='text/css' href='/includes/InvoiceTest/style.css' />
<link rel='stylesheet' type='text/css' href='/includes/InvoiceTest/print.css' media="print" />

<legend>Invoice Number: {{ $quote->getQuoteNumber() }}</legend>
<div class="col-md-12">   
    <legend>Summery</legend>
    <table class="table">
        <tr>
            <td>Quote Created by:</td><td>{{ $quote->getUser() }}</td>
        </tr>
        <tr>
            <td>Invoiced by:</td><td>{{ $quote->getFinalizedUser() }}</td>
        </tr>    
        <tr>
            <td>Total Cost:</td><td>${{ $quote->getTotal() }}</td>
        </tr> 
        <tr>
            <td>Total Payments:</td><td>${{ $quote->getTotalPayments() }}</td>
        </tr> 
        <tr>
            <td>Total Outstanding:</td><td>${{ $quote->getBallence() }}</td>
        </tr> 
    </table>    
</div>
<div class="col-md-12">   
    <legend>Line Items</legend>
    
    <table class="table">

        <tr>
            <th class="col-md-1">SKU</th>
            <th>Description</th>
            <th class="col-md-1">Unit Cost ({{ $currency }})</th>
            <th class="col-md-1">Quantity</th>
            <th class="col-md-1">Price ({{ $currency }})</th>

        </tr>

        @foreach($quote->quoteitem as $item)
            <tr>
                <td name="sku" class="item-name">{{ $item->SKU }}</td>
                <td name="description" class="description">{{ $item->description }}</td>
                <td name="costperunit" >{{ number_format($item->costperunit , 2) }}</td>
                <td name="units" >{{ number_format($item->units , 0) }}</td>
                <td name="total" >{{ number_format($item->costperunit * $item->units , 2) }}</td>
                <td name="id" hidden="true">{{ $item->id }}</td>
            </tr>
        @endforeach

        <tr>
            <td style="background-color:white;" colspan="2" class="blank"> </td>
            <td style="background-color:#eee;" colspan="2" class="total-line">Subtotal</td>
            <td style="background-color:white;" class="total-value"><div id="subtotal">{{ $currency }}{{ $quote->getTotal() }}</div></td>
        </tr>
        <tr>
            <td style="background-color:white; border-top: none;" colspan="2" class="blank"> </td>
            <td style="background-color:#eee;" colspan="2" class="total-line">Tax</td>
            <td style="background-color:white;" class="total-value"><div id="subtotal">{{ $currency }}0.00</div></td>
        </tr>
        <tr>

            <td style="background-color:white; border-top: none;" colspan="2" class="blank"> </td>
            <td style="background-color:#eee;" colspan="2" class="total-line">Total</td>
            <td style="background-color:white;" class="total-value"><div id="total">{{ $currency }}{{ $quote->getTotal() }}</div></td>
        </tr>
    </table>
</div>  

<div class="col-md-12">
    <legend>Payments</legend>
    <table class="table">
        <tr>
            <th class="col-md-1">Date</th>
            <th class="col-md-1">Amount</th>
            <th class="col-md-1">Type</th>
            <th class="col-md-1">Method</th>
            <th class="col-md-1">User</th>
            <th class="">Comments</th>
        </tr>

        @foreach($quote->DepositLink as $DepositLink)
        <tr>
            <td>{{ $DepositLink->deposit->FormatDate() }}</td>
            <td name="amount">{{ $DepositLink->formatedAmount() }}</td>
            <td name="type">Payment</td>
            <td name="method" >{{ $DepositLink->deposit->method }}</td>
            <td name="user_id" >{{ $DepositLink->deposit->getUser() }}</td>
            <td name="comments" >{{ $DepositLink->deposit->comments }}</td>
        </tr>
        @endforeach
    </table>
</div>
@stop