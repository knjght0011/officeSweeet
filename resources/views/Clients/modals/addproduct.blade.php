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
                            <span class="input-group-addon" for="search"><div style="width: 7em;">Search:</div></span>
                            <input id="search" name="search" type="text" placeholder="" value="" class="form-control" data-validation-label="Search" data-validation-required="false" data-validation-type="">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <button style="width: 100%;" id="addproduct" type="button" class="btn OS-Button">Select a Product</button>
                    </div>    

                    <div class="col-md-4">
                        <div class="input-group ">   
                            <span class="input-group-addon" for="length"><div style="width: 7em;">Show:</div></span>
                            <select id="length" name="length" type="text" placeholder="choice" class="form-control">
                                <option value="10">10 entries</option>
                                <option value="25">25 entries</option>
                                <option value="50">50 entries</option>
                                <option value="100">100 entries</option>
                            </select>
                        </div>
                    </div>
                </div>                

                <div class="row" style="margin-top: 15px;">
                    <div class="col-md-2">
                        <button id="previous-page" name="previous-page" type="button" class="btn OS-Button" style="width: 100%;">Previous</button>
                    </div>
                    <div class="col-md-8" id="tableInfo" style="text-align: center;">

                    </div>
                    <div class="col-md-2">
                        <button id="next-page" name="next-page" type="button" class="btn OS-Button" style="width: 100%;">Next</button>
                    </div>
                </div>                
                
                
                <table id="productstable" class="table">
                    <thead>
                        <tr id="head">
                            <th>SKU/UPC</th>  
                            <th>Product Name</th>
                            <th>Charge</th>
                            <th>ID</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>SKU/UPC</th>
                            <th>Product Name</th>
                            <th>Charge</th>
                            <th>ID</th>
                            <th>Type</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>{{ $product->SKU }}</td>
                            <td>{{ htmlspecialchars($product->productname) }}</td>
                            <td>${{ $product->getCharge() }}</td>
                            <td>{{ $product->id }}</td>
                            <td>Product</td>
                        </tr>
                        @endforeach
                        @foreach($services as $service)
                        <tr>
                            <td>{{ $service->SKU }}</td>
                            <td>{{ htmlspecialchars($service->description) }}</td>
                            <td>${{ number_format($service->charge, 2, ".", "") }}</td>
                            <td>{{ $service->id }}</td>
                            <td>Service</td>
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
    $('#ProductModal').on('shown.bs.modal', function () {
        $('div.dataTables_filter input').focus()
    });

    // DataTable
    var table = $('#productstable').DataTable({
        "columnDefs": [
            { "targets": [3],"visible": false}
        ]
    });

    $('#search').on( 'keyup change', function (e) {
        table.search( this.value )
                .draw();
        
        UpdatePageinate(table.page.info());
        
        if (e.which == 13){

            $row = table.row( {search:'applied'} ).data();
            UpdateQuoute($row);
            
            $("#search").val("");
            table.search( '' );
            table.draw();
        } 
    });

    $('#length').on( 'change', function () {
        table.page.len( this.value )
                .draw();
        UpdatePageinate(table.page.info());
    });

    $( "#previous-page" ).click(function() { 
        table.page( "previous" ).draw('page');
        UpdatePageinate(table.page.info());
    });
    
    $( "#next-page" ).click(function() { 
        table.page( "next" ).draw('page');
        UpdatePageinate(table.page.info());
    });          
        
    $('#productstable tbody').on( 'click', 'tr', function () {
        $row = $(this);
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
            $('#addproduct').html('Check Out');
        }
        else {
            //table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            $('#addproduct').html('Check Out');
        }
    });

    $("#addproduct").click(function()
    {

        $row = table.rows('.selected').data();
        if($row.length < 1){
            $.dialog({
                title: 'Oops...',
                content: 'No products have been selected.'
            });
        }else{
            $products = "";
            $services = "";
            $.each($row, function ($index, $value){
                if($value[4] === "Product"){
                    $products = $products + $value[3] + ","
                }else{
                    $services = $services + $value[3] + ","
                }
            });
            if($products === ""){
                $p = 0;
            }else{
                $p = $products.slice(0, -1);
            }
            if($services === ""){
                $s = 0;
            }else{
                $s = $services.slice(0, -1);
            }

            GoToPage('/POS/{{ $client->id }}/' + $p + '/' + $s);
        }
    });
    
    $("#productstable").css("width" , "100%");      

    UpdatePageinate(table.page.info());
}); 

function UpdatePageinate(info){

    $( "#previous-page" ).prop('disabled', false);
    $( "#next-page" ).prop('disabled', false);
    
    $('#tableInfo').html(
        'Currently showing page '+(info.page+1)+' of '+info.pages+' pages.'
    );
    
    if(info.page === 0){
        $( "#previous-page" ).prop('disabled', true);
    }
    
    if((info.page+1) === info.pages){
        $( "#next-page" ).prop('disabled', true);
    }
}
</script>