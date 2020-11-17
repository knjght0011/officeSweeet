<style>
.dataTables_filter {
    display: none; 
}
.dataTables_length {
    display: none; 
}
.dataTables_info {
    display: none; 
}
.dataTables_paginate {
    display: none; 
}
</style>

<div class="modal fade" id="ProductModal">
    <div class="modal-dialog modal-lg" role="document" data-backdrop="">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Add a product</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="input-group ">   
                            <span class="input-group-addon" for="po-products-search"><div style="width: 7em;">Search:</div></span>
                            <input id="po-products-search" name="po-products-search" type="text" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <button style="width: 100%;" id="addproduct" type="button" class="btn OS-Button">Select a Product</button>
                    </div>    

                </div>                

                <div class="col-md-12" style="margin-top: 15px;">
                    {!! PageElement::TableControl('po-products') !!}
                </div>                
                
                
                <table id="po_productstable" class="table">
                    <thead>
                        <tr id="head">
                            <th>Vendor Ref</th>
                            <th>Product Name</th>
                            <th>Vendor</th>
                            <th>Cost</th>
                            <th>Cost</th>
                            <th>tax</th>
                            <th>product_id</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Vendor Ref</th>
                            <th>Product Name</th>
                            <th>Vendor</th>
                            <th>Cost</th>
                            <th>Cost</th>
                            <th>tax</th>
                            <th>product_id</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>{{ $product->vendorref }}</td>
                            <td>{{ htmlspecialchars($product->productname) }}</td>
                            <td>@if($product->vendor_id === null)
                                    None
                                @else
                                    {{ $product->vendor->getName() }}
                            </td>@endif
                            <td>${{ $product->getCost() }}</td>
                            <td>{{ round($product->cost, 2) }}</td>
                            <td>{{ $product->Tax() }}</td>
                            <td>{{ $product->id }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
$(document).ready(function() {

    // DataTable
    var po_productstable = $('#po_productstable').DataTable({
        "columnDefs": [
            { "targets": [4,5,6],"visible": false}
        ]
    });

    $('#po-products-search').on( 'keyup change', function (e) {
        po_productstable.search( this.value )
                .draw();

        PageinateUpdate(po_productstable.page.info(), $('#po-products-next-page'), $('#po-products-previous-page'),$('#po-products-tableInfo'));
        
        if (e.which == 13){

            $row = po_productstable.row( {search:'applied'} ).data();
            UpdateQuoute($row);
            
            $("#search").val("");
            po_productstable.search( '' );
            po_productstable.draw();
        } 
    });

    $( "#po-products-previous-page" ).click(function() {
        po_productstable.page( "previous" ).draw('page');
        PageinateUpdate(po_productstable.page.info(), $('#po-products-next-page'), $('#po-products-previous-page'),$('#po-products-tableInfo'));
    });
    
    $( "#po-products-next-page" ).click(function() {
        po_productstable.page( "next" ).draw('page');
        PageinateUpdate(po_productstable.page.info(), $('#po-products-next-page'), $('#po-products-previous-page'),$('#po-products-tableInfo'));
    });          
        
    $('#po_productstable tbody').on( 'click', 'tr', function () {
        $row = $(this);
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
            $('#addproduct').html('Select a Product');
        }
        else {
            //table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            $('#addproduct').html("Add Product(s)");
        }
    });

    $("#addproduct").click(function()
    {
        $row = po_productstable.rows('.selected').data();
        $.each($row, function ($index, $value){
            AddRow($value[6], 0, $value[0], $value[1], 1, $value[4]);

        });

    });
    
    $("#po_productstable").css("width" , "100%");

    PageinateUpdate(po_productstable.page.info(), $('#po-products-next-page'), $('#po-products-previous-page'),$('#po-products-tableInfo'));

});
</script>