@extends('pdf.Reports.master')

@section('content')
<div id="title"> Invoice Report </div>

    <table class="table">
        <thead>
            <tr>
                <th>Quote Number</th>
                <th>Client</th>
                <th>Quote Created By</th>
                <th>Finalized By</th>
                <th>Total Amount</th>

            </tr>
        </thead>
        <tbody>      
            @foreach($invoices as $invoice)
            <tr>
                <td>{{ $invoice->getQuoteNumber() }}</td>
                <td>{{ $invoice->client->getName() }}</td>
                <td>{{ $invoice->getUser() }}</td>
                <td>{{ $invoice->getFinalizedUser() }}</td>
                <td>${{ $invoice->getTotal() }}</td>
            </tr>
            @endforeach
            <tr style="background-color: lightblue;">
                <td colspan="3"></td>
                <td>Total:</td>
                <td>${{ $Total }}</td>
            </tr>
        </tbody>
    </table>

@stop