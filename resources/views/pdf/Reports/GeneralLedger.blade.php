@extends('pdf.Reports.landscapemaster')
@section('content')

@include('pdf.Reports.Inserts.header')

    <table id="journal-table" class="table">
        <thead>
        <tr id="head">
            <th>Date</th>
            <th>Type</th>
            <th>Category</th>
            <th>To/From</th>
            <th>Amount</th>
        </tr>
        </thead>
        <tbody>
        @foreach($array as $element)
            <tr>
                <td>{{ $element['date'] }}</td>
                <td>{{ $element['type'] }}</td>
                <td>{{ $element['category'] }}</td>
                <td>{{ $element['tofrom'] }}</td>
                <td>{{ $element['amount'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@stop
