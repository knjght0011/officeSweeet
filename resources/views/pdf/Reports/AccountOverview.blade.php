@extends('pdf.Reports.landscapemaster')
@section('content')
@include('pdf.Reports.Inserts.header')

<table id="journal-table" class="table">
    <thead>
        <tr id="head">
            <th>Name</th>
            <th>Amount</th>
            <th>Catagory</th>
            <th>Comments</th>
        </tr>
    </thead>
    <tbody>
    @foreach($array as $element)
        <tr >
            <td>{{ $element['name'] }}</td>
            <td>{{ $element['amount'] }}</td>

            <td>@foreach($element['catagorys'] as $key => $value)
                    {{ $key }}->${{ $value }} |
                @endforeach</td>
            <td>{{ $element['comments'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
@stop
