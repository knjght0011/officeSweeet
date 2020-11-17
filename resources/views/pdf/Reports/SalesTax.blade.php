@extends('pdf.Reports.master')

@section('content')

@include('pdf.Reports.Inserts.header')
<div id="title"> Sales Tax Report </div>



@foreach($invoices as $invoice)
Invoice Number - {{ $invoice->getQuoteNumber() }}
<table class="table">
    <thead>
    <tr>
        <th>Tax(%)</th>
        <th>Amount</th>
        <th>Tax</th>
    </tr>
    </thead>
    <tbody>
        @foreach($invoice->quoteitem as $item)
            <tr>
                <td>{{ floatval($item->tax) }}%</td>
                <td>${{ $item->getSubTotal() }}</td>
                <td>${{ $item->getTax() }}</td>
            </tr>
        @endforeach

    <tr style="background-color: lightblue;">
        <td>Total</td>
        <td>${{ $invoice->getSubTotal() }}</td>
        <td>${{ $invoice->getTax() }}</td>
    </tr>
    </tbody>
</table>
@endforeach

Total
<table class="table">
    <thead>
    <tr>
        <th>Tax(%)</th>
        <th>Gross Sales</th>
        <th>Total Tax Collected</th>
    </tr>
    </thead>
    <tbody>
    @foreach($totaltax as $key => $item)
        <tr>
            <td>{{ floatval($key) }}%</td>
            <td>${{ number_format($total[$key] , 2, '.', '') }}</td>
            <td>${{ number_format($item , 2, '.', '') }}</td>
        </tr>
    @endforeach
    <tr style="background-color: lightblue;">
        <td>Total</td>
        <td>${{ number_format($overalltotal , 2, '.', '') }}</td>
        <td>${{ number_format($overalltaxtotal , 2, '.', '') }}</td>
    </tr>
    </tbody>
</table>
@stop