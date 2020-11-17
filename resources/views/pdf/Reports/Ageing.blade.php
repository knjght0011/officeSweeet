@extends('pdf.Reports.landscapemaster')

@section('content')

<style>

</style>

@include('pdf.Reports.Inserts.header')

    <table class="table">
        <thead>
            <tr style="background-color: lightblue;">
                <th>Client</th>
                <th>Current(0 - 30)</th>
                <th>Past(31 - 60)</th>
                <th>Past(61 - 90)</th>
                <th>Past(90+)</th>
                <th>Total Ballance</th>
            </tr>
        </thead>
        <tbody>      
            @foreach($clients as $client)
            <tr>
                <td>{{ $client->getName() }}</td>
                <td>${{ $client->getBalenceLessThan30(true) }}</td>
                <td>${{ $client->getBalence31to60(true) }}</td>
                <td>${{ $client->getBalence61to90(true) }}</td>
                <td>${{ $client->getBalence90plus(true) }}</td>
                <td>${{ $client->getBalence(true) }}</td>
            </tr>
            @endforeach
            <tr style="background-color: lightblue;">
                <td>Total</td>
                <td>${{ number_format($TotalLessThan30 , 2) }}</td>
                <td>${{ number_format($Total31to60 , 2) }}</td>
                <td>${{ number_format($Total61to90 , 2) }}</td>
                <td>${{ number_format($Total90plus , 2) }}</td>
                <td>${{ number_format($TotalBalence , 2) }}</td>
            </tr>
            <tr style="background-color: #eee;">
                <td>%</td>
                @if($TotalBalence > 0)
                <td>{{ number_format((100 / $TotalBalence) * $TotalLessThan30 , 2) }}%</td>
                <td>{{ number_format((100 / $TotalBalence) * $Total31to60 , 2) }}%</td>
                <td>{{ number_format((100 / $TotalBalence) * $Total61to90 , 2) }}%</td>
                <td>{{ number_format((100 / $TotalBalence) * $Total90plus , 2) }}%</td>
                @else
                <td>0%</td>
                <td>0%</td>
                <td>0%</td>
                <td>0%</td>
                @endif
                <td></td>
            </tr>
        </tbody>
    </table>

@stop

