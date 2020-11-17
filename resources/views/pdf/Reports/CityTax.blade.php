@extends('pdf.Reports.master')

@section('content')

@include('pdf.Reports.Inserts.header')
<<<<<<< HEAD

<div id="title"> City Tax Report </div>

=======
<div id="title"> City Tax Report </div>
>>>>>>> New Public Payment Page added

@foreach($invoices as $invoice)
Invoice Number - {{ $invoice->getQuoteNumber() }}
<table class="table">
    <thead>
    <tr>

        <th>City Tax(%)</th>
        <th>Amount</th>
        <th>Tax</th>
    </tr>
    </thead>
    <tbody>
        @foreach($invoice->quoteitem as $item)
            <tr>
                <td>{{ floatval($item->citytax) }}%</td>
                <td>${{ $item->getSubTotal() }}</td>
                <td>${{ $item->getCityTax() }}</td>

            </tr>
        @endforeach

    <tr style="background-color: lightblue;">
        <td>Total</td>
        <td>${{ $invoice->getSubTotal() }}</td>
        <td>${{ $invoice->getCityTax() }}</td>

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