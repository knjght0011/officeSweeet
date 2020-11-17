@php ini_set('max_execution_time', 180); @endphp
@extends('pdf.Reports.portraitemaster')
@section('content')
@include('pdf.Reports.Inserts.shortheader')

<div>Checking Account Balance: {{ $endingbalance }}</div>
<br/>
<div>REVENUES</div>
<table id="journal-table" class="table">
    <thead>
        <tr id="head">
            <th>Account</th>
            <th>Annual Budget</th>
            <th>MTD Actual</th>
            <th>YTD Actual</th>
            <th>Annual Budget Remaining</th>

        </tr>
    </thead>
    <tbody>
    @foreach($IncomeArray as $element)
        <tr >
            <td>{{ $element['Account'] }}</td>
            <td>{{ $element['budget'] }}</td>
            <td>{{ $element['MTD'] }}</td>
            <td>{{ $element['YTD'] }}</td>
            <td>{{ $element['Remaining'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<div>EXPENSES</div>
<table id="journal-table" class="table">
    <thead>
    <tr id="head">
        <th>Account</th>
        <th>Annual Budget</th>
        <th>MTD Actual</th>
        <th>YTD Actual</th>
        <th>Annual Budget Remaining</th>

    </tr>
    </thead>
    <tbody>
    @foreach($ExpenseArray as $element)
        <tr >
            <td>{{ $element['Account'] }}</td>
            <td>{{ $element['budget'] }}</td>
            <td>{{ $element['MTD'] }}</td>
            <td>{{ $element['YTD'] }}</td>
            <td>{{ $element['Remaining'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
@stop
