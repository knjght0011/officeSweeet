@extends('pdf.Reports.master')

@section('content')
<div id="title"> Payments And Adjustments Report </div>

    <table class="table">
        <thead>
            <tr>
                <th >Date</th>
                <th >Client Name</th>
                <th >Payment ID</th>
                <th >Invoice ID</th>
                <th >Entered By</th>
                <th >Type</th>
                <th >Method</th>
                <th >Amount</th>

            </tr>
        </thead>
        <tbody>
        @foreach ($payments as $payment)
            <tr>
                <td>{{ $payment->created_at }}</td>
                <td>{{ $payment->getClientname() }}</td>
                <td>{{ $payment->id }}</td>
                <td>{{ $payment->quote_id }}</td>
                @if($payment->user === null)
                <td>Automated Payment</td>
                @else
                <td>{{ $payment->user->getShortName() }}</td>
                @endif
                <td>{{ $payment->type }}</td>
                <td>{{ $payment->method }}</td>
                <td>{{ $payment->getAmount() }}</td>
            </tr>
        @endforeach
            <tr style="background-color: lightblue;">
                <td colspan="6"> </td>
                <td colspan="1">Total:</td>
                <td>{{ $total }}</td>
            </tr>
        </tbody>
    </table>

    
@stop