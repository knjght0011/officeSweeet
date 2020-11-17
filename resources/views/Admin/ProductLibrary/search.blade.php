@extends('master')

@section('content')

<a id="link" href="/Admin/Products/Add">Add Product</a>

<table id="search" class="table">
    <thead>
        <tr id="head">
            <th>sku</th>
            <th>UPC</th>
            <th>Product Name</th>
            <th>Charge</th>
            <th>Cost</th>
            <th>Taxable</th>
            <th>Stock</th>
        </tr>
    </thead>
    <tfoot style="visibility: hidden;">
        <tr id="head">
            <th>sku</th>
            <th>UPC</th>
            <th>Product Name</th>
            <th>Charge</th>
            <th>Cost</th>
            <th>Taxable</th>
            <th>Stock</th>
        </tr>
    </tfoot>
    <tbody> 
        @foreach($products as $product)
        <tr>
            <td>{{ $product->sku }}</td>
            <td>{{ $product->upc }}</td>
            <td><a id="link" href="/Admin/Products/Edit/{{ $product->id }}">{{ $product->productname }}</a></td>
            <td>${{ $product->getCharge() }}</td>
            <td>${{ $product->getcost() }}</td>
            <td>{{ $product->taxable }}</td>
            <td>{{ $product->billingfrequency }}</td>
            <td>{{ $product->stock }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

    

<script>

$(document).ready(function() {
    // Setup - add a text input to each footer cell
    $('#search tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input class="form-control" type="text" placeholder="Search '+title+'" />' );
    } );
 
    // DataTable
    var table = $('#search').DataTable();
 
    // Apply the search
    table.columns().every( function () {
        var that = this;
 
        $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    } );
} );
</script> 

@stop