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
.productselected {
    background-color: lightgray;
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
                            <span class="input-group-addon" for="product-type"><div style="width: 7em;">Type:</div></span>
                            <select id="product-type" name="product-type" type="text" class="form-control">
                                <option value="Product">Products</option>
                                <option value="Service">Services</option>
                                <option value="Expense">Expenses</option>
                                <option value="Billable Hours">Billable Hours</option>
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
                            <th class="datatables-invisible-col">id</th>
                            <th>SKU</th>
                            <th>Product Name</th>
                            <th>Charge</th>
                            <th>Type</th>
                            <th class="datatables-invisible-col">cost</th>
                            <th class="datatables-invisible-col">units</th>
                            <th class="datatables-invisible-col">tax</th>
                            <th class="datatables-invisible-col">city tax</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>id</th>
                            <th>SKU</th>
                            <th>Product Name</th>
                            <th>Charge</th>
                            <th>Type</th>
                            <th>cost</th>
                            <th>units</th>
                            <th>tax</th>
                            <th>City tax</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>{{ $product->SKU }}</td>
                                <td>{{ htmlspecialchars($product->productname) }}</td>
                                <td>${{ $product->getCharge() }}</td>
                                <td>Product</td>
                                <td>{{ round($product->charge, 2) }}</td>
                                <td>1</td>
                                <td>{{ $product->Tax() }}</td>
                                <td>{{ $product->CityTax() }}</td>
                            </tr>
                        @endforeach
                        @foreach($services as $service)
                            <tr>
                                <td>{{ $service->id }}</td>
                                <td>{{ $service->sku }}</td>
                                <td>{{ htmlspecialchars($service->description) }}</td>
                                <td>${{ $service->charge }}</td>
                                <td>Service</td>
                                <td>{{ round($service->charge, 2) }}</td>
                                <td>1</td>
                                <td>{{ $service->Tax() }}</td>
                                <td>{{ $service->CityTax() }}</td>
                            </tr>
                        @endforeach
                        @foreach($expenses as $expense)
                            <tr>
                                <td>{{ $expense->id }}</td>
                                <td>Expense-{{ $expense->id }}</td>
                                <td>{{ $expense->description }} {{ $expense->CatagoryString() }} {{ $expense->DateString() }}</td>
                                <td>${{ number_format($expense->amount, 2) }}</td>
                                <td>Expense</td>
                                <td>{{ number_format($expense->amount, 2) }}</td>
                                <td>1</td>
                                <td>0</td>
                            </tr>
                        @endforeach
                        @foreach($hours as $hour)
                            <tr>
                                <td>{{ $hour->id }}</td>
                                <td>Hours-{{ $hour->id }}</td>
                                <td>{{ $hour->Description() }}</td>
                                <td>${{ number_format($hour->Total() , 2, '.', '') }}</td>
                                <td>Billable Hours</td>
                                <td>{{ number_format($hour->rate , 2, '.', '') }}</td>
                                <td>{{ floatval($hour->hours) }}</td>
                                <td>0</td>
                                <td>0</td>
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
        "columns": [
            { "data": "id" },
            { "data": "sku" },
            { "data": "name" },
            { "data": "charge" },
            { "data": "type" },
            { "data": "cost" },
            { "data": "units" },
            { "data": "tax" },
            { "data": "citytax"},
        ],
        "columnDefs": [
            {
                "targets": "datatables-invisible-col",
                "visible": false
            }
        ],
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

    $('#product-type').on( 'keyup change', function () {

        $('.productselected').removeClass('productselected');

        table.columns( 4 )
                .search( "^" + $(this).val() + "$", true, false, true)
                .draw();

        UpdatePageinate(table.page.info());
    });

    $('#product-type').change();

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
        if ( $(this).hasClass('productselected') ) {
            $(this).removeClass('productselected');
            $('#addproduct').html('Select an Item');
        }
        else {
            //table.$('tr.productselected').removeClass('productselected');
            $(this).addClass('productselected');
            $('#addproduct').html('Add To Quote');
        }
    });

    $("#addproduct").click(function()
    {
        $row = table.rows('.productselected').data();

        $.each($row, function ($index, $value){
            AddRow($value['id'], $value['type'], 0, $value['sku'], $value['name'], $value['units'], $value['cost'], $value['tax'], $value['citytax']);
        });
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