@extends('pdf.Reports.master')

@section('content')

@include('pdf.Reports.Inserts.header')
   
@foreach($employees as $employee)
{{ $employee->firstname }} {{ $employee->lastname }} - {{ $employee->employeeid }}
<table class="table">
    
    <thead>
        <tr style="background-color: lightblue;">
            <td class="col-md-1">
                Description
            </td>
            <td>
                Comment
            </td>
            <td class="col-md-1">
                Taxable
            </td>
            <td class="col-md-1">
                Net Pay($)
            </td>
            <td class="col-md-1">
                Units
            </td>
            <td class="col-md-1">
                Total($)
            </td>
        </tr>
    </thead>
    <tbody>
        @if($payroll->PayrollForUser($employee->id) !== false)
        @foreach($payroll->PayrollForUser($employee->id) as $item)
        <tr>
            <td>
                {{ $item->description }}
            </td>
            <td>
                {{ $item->comment }}
            </td>
            <td>
                {{ $item->TaxWords() }}
            </td>
            <td>
                {{ number_format($item->netpay , 2, '.', '') }}
            </td>
            <td>
                {{ $item->units }}
            </td>
            <td>
                {{ number_format($item->total, 2, '.', '') }}
            </td>
        </tr>
        @endforeach
        @else
        
        @endif
        <tr style="background-color: lightblue;">
            <td colspan="4">

            </td>    
            <td>
                Total
            </td>  
            <td>
                {{ $payroll->PayrollForUserTotal($employee->id) }}
            </td>                    
        </tr>

    </tbody>
</table>

@endforeach
@stop

