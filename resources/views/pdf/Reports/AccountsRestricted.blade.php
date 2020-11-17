@php ini_set('max_execution_time', 180); @endphp
@extends('pdf.Reports.portraitemaster')
@section('content')
@include('pdf.Reports.Inserts.header')

<div>Savings Account Balance: {{ $EbalanceTotal }}</div>
<br/>
<table id="journal-table" class="table">
    <thead>
        <tr id="head">
            <th>Account</th>
            <th>Beginning Balance</th>
            <th>Revenue</th>
            <th>Expenses</th>
            <th>Ending Balance</th>

        </tr>
    </thead>
    <tbody>
    @foreach($Array as $element)
        <tr >
            <td>{{ $element['Account'] }}</td>
            <td>{{ $element['BBalance'] }}</td>
            <td>{{ $element['Revenue'] }}</td>
            <td>{{ $element['Expenses'] }}</td>
            <td>{{ $element['EBalance'] }}</td>
        </tr>
    @endforeach

    <tr >
        <td> <b>TOTALS:</b> </td>
        <td><b>{{ $BalanceTotal }}</b></td>
        <td><b>{{ $RevenueTotal }}</b></td>
        <td><b>{{ $ExpenseTotal }}</b></td>
        <td><b>{{ $EbalanceTotal }}</b></td>
    </tr>
    </tbody>
</table>

@stop
