@extends('pdf.Reports.master')

@section('content')

@include('pdf.Reports.Inserts.header')

<div style="width: 100%;">
    <table class="table">
        <thead>
            <tr style="background-color: lightblue;">
                <th class="col-md-8">Income</th>
                <th class="col-md-2">$</th>
                <th class="col-md-2"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($profitcatagorys as $catagory => $value)
                <tr>
                    <td>{{ $catagory }}</td>
                    <td>${{ number_format($value, 2) }}</td>
                    <td></td>
                </tr>
            @endforeach
            <tr style="background-color: lightblue;">
                <td>Total Income</td>
                <td>${{ number_format($incometotal, 2) }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>              
        
<div style="width: 100%;">
    <table class="table">
        <thead>
            <tr style="background-color: lightblue;">
                <th class="col-md-8">Expenses</th>
                <th class="col-md-2"></th>
                <th class="col-md-2">$</th>
            </tr>
        </thead>
        <tbody> 
            @foreach($expences as $catagory => $value)
            <tr>
                <td>{{ $catagory }}</td>
                <td></td>
                <td>${{ number_format($value, 2) }}</td>
            </tr>
            @endforeach
            @if($depreciationtotal > 0)
            <tr>
                <td>Depreciation</td>
                <td></td>
                <td>${{ number_format($depreciationtotal, 2) }}</td>
            </tr>
            @endif
            <tr style="background-color: lightblue;">
                <td>Total Expencses</td>
                <td></td>
                <td>${{ number_format($expencestotal, 2) }}</td>
            </tr>
        </tbody>
    </table>
</div>
<div style="width: 100%;">
    <table class="table">
        <thead>  
            <tr style="background-color: lightblue;">
                <th class="col-md-8">Profit/Loss</th>
                <th class="col-md-2">${{ number_format($profitloss, 2) }}</th>
                <th class="col-md-2"></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

@if($expencespie != null)
    <img style="width: 100%;" src='data:image/png;base64,{{$expencespie}}'  />
@endif
@if($incomepie != null)
    <img style="width: 100%;" src='data:image/png;base64,{{$incomepie}}'  />
@endif
@stop

