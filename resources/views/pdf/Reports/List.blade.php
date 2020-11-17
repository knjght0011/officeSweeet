@extends('pdf.Reports.master')

@section('content')

    @include('pdf.Reports.Inserts.header')

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Total Invoiced Amount</th>
                <th>Payments</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>      
            @foreach($items as $item)
            <tr>
                <td>{{ $item->getName() }}</td>
                <td>${{ $item->getTotalInvoiceAmountBetweenDates($startdate, $enddate) }}</td>
                <td>${{ $item->getTotalInvoicePaymentsBetweenDates($startdate, $enddate) }}</td>
                <td>${{ $item->getTotalInvoiceBalenceBetweenDates($startdate, $enddate) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

@stop