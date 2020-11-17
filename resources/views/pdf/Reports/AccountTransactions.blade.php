@extends('pdf.Reports.landscapemaster')
@section('content')
@include('pdf.Reports.Inserts.header')

<table id="journal-table" class="table">
    <thead>
        <tr id="head">
            <th>Date</th>
            <th>Type</th>
            <th>To/From</th>
            <th>Income/Credit</th>
            <th>Expense/Debit</th>
        </tr>
    </thead>
    <tbody>
    @foreach($array as $element)
        <tr>
            <td>{{ $element['date'] }}</td>
            <td>{{ $element['type'] }}</td>
            <td>{{ $element['tofrom'] }}</td>
            <td>{{ $element['income'] }}</td>
            <td>{{ $element['expense'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
@stop
