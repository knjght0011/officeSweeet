@extends('master')

@section('content')

<div class="row">
    <div class="col-md-3">
        <button style="width: 100%;" class="btn OS-Button" id="backtoclient" name="backtoclient" type="button" onclick="GoToPage('/Clients/View/{{ $quote->client_id }}')">Back To Client</button>
    </div>
</div>

<legend>Invoice</legend>

<table id="quote-table" class="table">
    <thead>
        <tr style="background-color: lightblue;">
            <th class="col-md-1">SKU</th>
            <th>Description</th>
            <th class="col-md-1">Units</th>
            <th class="col-md-1">Unit Price ($)</th>
            <th class="col-md-1">Price ($)</th>
            <th class="col-md-1">Sales Tax (%)</th>
            <th class="col-md-1">Sales Tax ($)</th>
            <th class="col-md-1">City Tax (%)</th>
            <th class="col-md-1">City Tax ($)</th>
            <th class="col-md-1">Total ($)</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($quote->quoteitem as $item)
        <tr>
            <td>{{ $item->SKU }}</td>
            <td>{{ $item->description }}</td>
            <td>{{ number_format($item->units, 0, ".", "") }}</td>
            <td>${{ number_format($item->costperunit, 2, ".", "") }}</td>
            <td>${{ $item->getSubTotal() }}</td>
            <td>{{ number_format($item->tax, 2, ".", "") }}</td>
            <td>${{ $item->getTax() }}</td>
            <td>{{ number_format($item->citytax, 2, ".", "") }}</td>
            <td>${{ $item->getCityTax() }}</td>
            <td>${{ $item->getTotal() }}</td>
        </tr>
    @endforeach
        <tr style="background-color: lightblue;">
            <td colspan="6"></td>
            <td style="font-weight: bold;">Total:</td>
            <td>${{ $quote->getTotal() }}</td>
        </tr>
    </tbody>
</table>

<legend>Purchase Orders</legend>

<table class="table">
    <thead>
        <tr>
            <th>Vendor</th>
            <th>Items</th>
            <th>Total</th>
            <th>View/Edit</th>
        </tr>
    </thead>
    <tbody>
    @foreach($quote->purchaseorders as $purchaseorder)
    <tr>
        <td><a target="_blank" href="/Vendors/View/{{ $purchaseorder->vendor->id }}">{{ $purchaseorder->vendor->getName() }}</a></td>
        <td>{{ $purchaseorder->NumberOfItems() }}</td>
        <td>${{ $purchaseorder->Total() }}</td>
        <td><a target="_blank" href="/PurchaseOrders/Edit/{{ $purchaseorder->id }}">View/Edit</a></td>
    </tr>
    @endforeach
    </tbody>
</table>


@stop
