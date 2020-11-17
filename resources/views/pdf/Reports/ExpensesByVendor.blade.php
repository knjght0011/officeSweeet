@extends('pdf.Reports.master')

@include('pdf.Reports.Inserts.header')

@section('content')
<div id="title"> {{ $name }} List </div>

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>      
            @foreach($items as $item)
            <tr>
                <td>{{ $item->getName() }}</td>
                <td>{{ $item->TotalExpensesFormated($startdate, $enddate) }} </td>

            </tr>
            @endforeach
        </tbody>
    </table>

@stop