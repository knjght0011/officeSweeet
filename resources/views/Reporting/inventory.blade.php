@extends('master')

@section('content')


        
    <table id="search" class="table">
        <thead>
            <tr id="head">
                <th>Product Name</th>
                <th>Cost</th>
                <th>Stock</th>
                <th>Re-Order Level</th>
                <th>Re-Stock To</th>
                <th>Amount to Order</th>
                <th>Re-Stock Cost</th>

            </tr>
        </thead>
        <tfoot style="visibility: hidden;">
            <tr id="head">
                <th>Product Name</th>
                <th>Cost</th>
                <th>Stock</th>
                <th>Re-Order Level</th>
                <th>Re-Stock To</th>
                <th>Amount to Order</th>
                <th>Re-Stock Cost</th>

            </tr>
        </tfoot>
        <tbody> 

            @foreach($products as $product)
            <tr>
                <td><a id="link" href="/Admin/Products/Edit/{{ $product->id }}">{{ $product->productname }}</a></td>
                <td>${{ $product->cost }}</td>
                <td>{{ $product->stock }}</td>
                <td>{{ $product->reorderlevel }}</td>
                <td>{{ $product->restockto }}</td>
                <td>{{ $product->restockto - $product->stock }}</td>
                <td>${{$product->restockcost() }}</td>
            </tr>
            @endforeach
            <tr>
                <td></td>   
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    Subtotal: ${{$subtotal}}
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    Tax: $0
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    Total: ${{$subtotal}}
                </td>
            </tr>
            
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