@extends('pdf.Reports.master')

@section('content')

    @include('pdf.Reports.Inserts.header')

    <h3>
        Client: {{ $client->getName() }}
    </h3>

    <table class="table">
        <thead>
        <tr>
            <th >Date</th>
            <th >Type</th>
            <th >Charge/Debit</th>
            <th >Payment/Credit</th>
            <th>Ending Balance</th>
        </tr>
        </thead>
        <tbody>
            @foreach(array_reverse($reportdata) as $row)
                <tr>
                    <td>{{ $row['date'] }}</td>
                    <td>{{ $row['type'] }}</td>
                    <td>{{ $row['debt'] }}</td>
                    <td>{{ $row['credit'] }}</td>
                    <td>${{ number_format($row['running'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    
@stop