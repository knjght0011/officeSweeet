@extends('pdf.Reports.master')

@section('content')


@include('pdf.Reports.Inserts.header')

<div style="width: 100%;">
    <div id="title"> 
        <b>Assets</b>
    </div>
    <table class="table">
        <thead>
            <tr id="head">
                <th class="col-md-1">SKU</th>
                <th class="col-md-1">Product Name</th>
                <th class="col-md-1">Cost</th>
                <th class="col-md-1">Current Stock</th>
                <th class="col-md-1">Value</th>
            </tr>    
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->SKU }}</td>
                <td>{{ $product->productname }}</td>
                <td>${{ number_format($product->cost ,2) }}</td>
                <td>{{ $product->stock }}</td>
                <td>${{ number_format($product->stock * $product->cost ,2) }}</td>
            </tr>
            @endforeach

            <tr style="background-color: lightblue;">
                <td colspan="3"></td>
                <td>Total</td>
                <td>${{ number_format($inventorytotal , 2) }}</td>
            </tr>
        </tbody>
    </table>
</div>  

@stop

