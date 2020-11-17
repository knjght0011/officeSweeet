@extends('pdf.Reports.master')

@section('content')
<div id="title"> Time Sheets Report </div>

@foreach ($employees as $employee)

{{ $employee->firstname }} {{ $employee->lastname }} - {{ $employee->employeeid }}
    <table class="table">
        <thead>
            <tr>
                <th>In</th>
                <th>Out</th>
                <th>Total Hours</th>
            </tr>
        </thead>
        <tbody>      
            @foreach($employee->clocks as $clock)
            <tr>
                <td>{{ $clock->informateddate() }}</td>
                <td>{{ $clock->outformateddate() }}</td>
                <td>{{ $clock->timedifference() }}</td>
            </tr>
            @endforeach
            <tr style="background-color: lightblue;">
                <td colspan="1"></td>
                <td>Total</td>
                <td>{{ $employee->TotalHours() }}</td>
            </tr>
        </tbody>
    </table>
    <div style="page-break-after: always;"></div>
@endforeach
    
@stop