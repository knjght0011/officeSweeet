<div class="row" style="margin-top: 10px; margin-bottom: 10px;">
    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="products-sales-tax"><div>Sales Tax Rate:</div></span>
            <input id="products-sales-tax" name="products-sales-tax" type="" placeholder="" value="{{ SettingHelper::GetSalesTax() }}" class="form-control" data-validation-label="Sales Tax Rate" data-validation-required="true" data-validation-type=""><span class="input-group-btn"><button id="products-sales-tax-save" class="btn btn-default" type="button">Save</button></span>
        </div>
    </div>

    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="inventory-manager"><div>Inventory Manager:</div></span>
            <select id="inventory-manager" name="inventory-manager" type="text" placeholder="" class="form-control">
                <option value="">None</option>
                @foreach(UserHelper::GetAllUsers() as $user)
                <option value="{{ $user->id }}">{{ $user->getShortName() }}</option>
                @endforeach
            </select>
            <span class="input-group-btn"><button id="inventory-manager-save" class="btn btn-default" type="button">Save</button></span>
        </div>
    </div>
</div>


<div class="row" style="margin-top: 15px;">
    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="search"><div style="width: 7em;">Search:</div></span>
            <input id="products-search" name="products-search" type="text" placeholder="" value="" class="form-control">
        </div>
    </div>
    <div class="col-md-6">
        {!! PageElement::TableControl('products') !!}
    </div>
    <div class="col-md-3">
        <button style="width: 100%;" type="button" class="btn OS-Button" data-toggle="modal" data-id="0" data-target="#addproduct" data-id="0" data-sku="" data-upc="" data-productname="" data-charge="0.00" data-cost="0.00" data-taxable="1" data-billingfrequency="" data-stock="0" data-reorderlevel="0" data-restockto="0">
            Add Product/Service
        </button>
    </div>
</div>

    <table id="productstable" class="table">
        <thead>
            <tr id="head">
                <th>sku</th>
                <th>UPC</th>
                <th>Product/Service</th>
                <th>Charge</th>
                <th>Cost</th>
                <th>Taxable</th>
                <th>Track Stock</th>
                <th>Stock</th>
                <th>Reorder Level</th>
                <th>Restock To</th>
            </tr>
        </thead>
        <tfoot>
            <tr id="head">
                <th>sku</th>
                <th>UPC</th>
                <th>Product/Service</th>
                <th>Charge</th>
                <th>Cost</th>
                <th>Taxable</th>
                <th>Track Stock</th>
                <th>Stock</th>
                <th>Reorder Level</th>
                <th>Restock To</th>
            </tr>
        </tfoot>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td class="col-md-1">{{ $product->SKU }}</td>
                <td class="col-md-1">{{ $product->upc }}</td>
                <td><a data-toggle="modal" href="#addproduct" class="button" data-id="{{ $product->id }}" data-sku="{{ $product->SKU }}" data-upc="{{ $product->upc }}" data-productname="{{ $product->productname }}" data-charge="{{ $product->getCharge() }}" data-cost="{{ $product->getcost() }}" data-taxable="{{ $product->taxable }}" data-billingfrequency="{{ $product->billingfrequency }}" data-stock="{{ $product->stock }}" data-reorderlevel="{{ $product->reorderlevel }}" data-restockto="{{ $product->restockto }}" data-trackstock="{{ $product->trackstock }}">{{ $product->productname }}</a></td>
                <td class="col-md-1">${{ $product->getCharge() }}</td>
                <td class="col-md-1">${{ $product->getcost() }}</td>
                <td class="col-md-1">{{ $product->taxablewords() }}</td>
                <td class="col-md-1">{{ $product->trackstockwords() }}</td>
                <td class="col-md-1">{{ $product->stockiftracked()  }}</td>
                <td class="col-md-1">{{ $product->reorderleveliftracked()  }}</td>
                <td class="col-md-1">{{ $product->restocktoiftracked()  }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>


<div class="modal fade" id="addproduct" tabindex="-1" role="dialog" aria-labelledby="addproduct" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="addproduct">Add Product/Service</h4>
        </div>
        <div class="modal-body">
            
            {!! Form::OSinput("productname", "Product Name", "", "", "true", "") !!}

            {!! Form::OSinput("sku", "Sku", "", "", "false", "") !!}

            {!! Form::OSinput("upc", "Upc", "", "", "false", "") !!}

            {!! Form::OSinput("charge", "Charge", "", "", "true", "") !!}

            {!! Form::OSinput("cost", "Cost", "", "", "true", "") !!}
            
            {!! Form::OSselect("taxable", "Taxable", ['1' => 'Yes','0' => 'No'], "", 1, "false", "") !!}
            <div class="input-group">
                <span class="input-group-addon" for="sku"><div style="width: 15em;">Track Stock:</div></span>
                <span class="input-group-addon">
                  <input id="trackstock" type="checkbox" aria-label="Checkbox for following text input">
                </span>
          </div>
            
            <div id="stocktrackinfo">
            {!! Form::OSinput("stock", "Current Stock", "", "", "false", "") !!}

            {!! Form::OSinput("reorderlevel", "Reorder Level", "", "", "false", "") !!}

            {!! Form::OSinput("restockto", "Restock to", "", "", "false", "") !!}
            </div>
            <input style="display: none;" id="id" name="id" type="text" value="" class="form-control">

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button id="SaveProduct" name="SaveProduct" type="button" class="btn OS-Button">Save</button>
        </div>
    </div>
  </div>
</div>    



<script>

$(document).ready(function() {

    $('#inventory-manager').val("{{  SettingHelper::GetSetting("inventorymanagerid") }}");


    $('#addproduct').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal

        console.log(button);
        var sku = button.data('sku'); 
        var upc = button.data('upc'); 
        var productname = button.data('productname'); 
        var charge = button.data('charge'); 
        var cost = button.data('cost'); 
        var taxable = button.data('taxable'); 
        //var billingfrequency = button.data('sku'); 
        var stock = button.data('stock'); 
        var id = button.data('id'); 
        var reorderlevel = button.data('reorderlevel'); 
        var restockto = button.data('restockto'); 
        var trackstock = button.data('trackstock');

        if(trackstock === 0){
            $('#stocktrackinfo').css('display' , 'none');
            $('#trackstock').prop( "checked", false );
        }else{
            $('#stocktrackinfo').css('display' , 'block');
            $('#trackstock').prop( "checked", true );
        }
        
        var modal = $(this);
        
        $('#productname').val(productname);
        $('#sku').val(sku);
        $('#upc').val(upc);
        $('#charge').val(charge);
        $('#cost').val(cost);
        $('#taxable').val(taxable);
        //$('#billingfrequency').val(billingfrequency);
        $('#stock').val(stock);
        $('#reorderlevel').val(reorderlevel);
        $('#restockto').val(restockto);
        $('#id').val(id);
    });
    
    $('#addproduct').on('hidden.bs.modal', function (event) {
        $('#productname').removeClass('invalid');
        $('#sku').removeClass('invalid');
        $('#upc').removeClass('invalid');
        $('#charge').removeClass('invalid');
        $('#cost').removeClass('invalid');
        $('#taxable').removeClass('invalid');
        $('#stock').removeClass('invalid');
        $('#reorderlevel').removeClass('invalid');
        $('#restockto').removeClass('invalid');
    });
    
    $('#trackstock').change(function(){
        if($('#trackstock').prop( "checked")){
            $('#stocktrackinfo').css('display' , 'block');
        }else{
            $('#stocktrackinfo').css('display' , 'none');
        }
    });    
    
    //$('#productstable tfoot th').each( function () {
     //   var title = $(this).text();
     //   $(this).html( '<input class="form-control" type="text" placeholder="Search '+title+'" />' );
    //} );
    
    // DataTable
    var productstable = $('#productstable').DataTable({});
 
    // Apply the search
    productstable.columns().every( function () {
        var that = this;
 
        $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    } );

    $( "#products-previous-page" ).click(function() {
        productstable.page( "previous" ).draw('page');
        PageinateUpdate(productstable.page.info(), $('#products-next-page'), $('#products-previous-page'),$('#products-tableInfo'));
    });

    $( "#products-next-page" ).click(function() {
        productstable.page( "next" ).draw('page');
        PageinateUpdate(productstable.page.info(), $('#products-next-page'), $('#products-previous-page'),$('#products-tableInfo'));
    });

    $('#products-search').on( 'keyup change', function () {
        productstable.search( this.value ).draw();
        PageinateUpdate(productstable.page.info(), $('#products-next-page'), $('#products-previous-page'),$('#products-tableInfo'));
    });

    PageinateUpdate(productstable.page.info(), $('#products-next-page'), $('#products-previous-page'),$('#products-tableInfo'));

    $( "#generalproducts" ).children().find(".dataTables_filter").css('display', 'none');
    $( "#generalproducts" ).children().find(".dataTables_length").css('display', 'none');
    $( "#generalproducts" ).children().find(".dataTables_paginate").css('display', 'none');
    $( "#generalproducts" ).children().find(".dataTables_info").css('display', 'none');
    $('#productstable').css('width' , "100%");

    $("#products-sales-tax-save").click(function()
    {
        $("body").addClass("loading");

        $tax = $('#products-sales-tax').val();

        taxpost = $.post("/ACP/Products/SaveTax",
        {
            _token: "{{ csrf_token() }}",
            tax: $tax,
        });

        taxpost.done(function( data )
        {
            $("body").removeClass("loading");
            $.dialog({
                title: 'Success!',
                content: 'Saved.'
            });
        });

        taxpost.fail(function() {
            $("body").removeClass("loading");
            $.dialog({
                title: 'Oops..',
                content: 'Server Error, please try again later'
            });

        });

    });

    $("#products-sales-tax").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            // Allow: Ctrl/cmd+A
            (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: Ctrl/cmd+C
            (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: Ctrl/cmd+X
            (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    $("#inventory-manager-save").click(function()
    {
        $("body").addClass("loading");

        $inventorymanagerid = $('#inventory-manager').val();

        taxpost = $.post("/ACP/General/Save",
            {
                _token: "{{ csrf_token() }}",
                inventorymanagerid: $inventorymanagerid,
            });

        taxpost.done(function( data )
        {
            $("body").removeClass("loading");
            $.dialog({
                title: 'Success!',
                content: 'Saved.'
            });
        });

        taxpost.fail(function() {
            $("body").removeClass("loading");
            $.dialog({
                title: 'Oops..',
                content: 'Server Error, please try again later'
            });

        });

    });

   $("#SaveProduct").click(function()
    {
        $("body").addClass("loading");

        $productname = ValidateInput($('#productname'));
        $sku = ValidateInput($('#sku'));
        $upc = ValidateInput($('#upc'));
        $charge = ValidateInput($('#charge'));
        $cost = ValidateInput($('#cost'));
        $taxable = ValidateInput($('#taxable'));
        $billingfrequency = "none"; //$('#billingfrequency').val();
        $stock = ValidateInput($('#stock'));
        $reorderlevel = ValidateInput($('#reorderlevel'));
        $restockto = ValidateInput($('#restockto'));
        $id = $('#id').val();
        
        if($('#trackstock').prop( "checked")){
            $trackstock = 1;
        }else{
            $trackstock = 0;
        }
        
        
        if($sku === "" & $upc === ""){
            $('#sku').addClass('invalid');
            $('#upc').addClass('invalid');
            
            $.dialog({
                title: 'Error!',
                content: 'Sku or Upc is Required'
            });
            throw new Error("Validation Error");
        }
        
        productpost = PostProduct($id, $productname, $sku, $upc, $charge, $cost, $taxable, $billingfrequency, $stock, $reorderlevel, $restockto, $trackstock);

        productpost.done(function( data ) 
        {

            if ($.isNumeric(data)) 
            {
                $id = data;
                location.href='/ACP/General/generalproducts';
            }else{
                //server validation errors
                $("body").removeClass("loading");
                ServerValidationErrors(data);
            }
        });

        productpost.fail(function() {
            //bootstrap_alert.warning('Failed to post product details!', 'danger', 4000);
            $("body").removeClass("loading");
            alert('Failed to post product details!');
        });
    });

});

function PostProduct($id, $productname, $sku, $upc, $charge, $cost, $taxable, $billingfrequency, $stock, $reorderlevel, $restockto, $trackstock) {
    
    return $.post("/ACP/Products/Save",
    {
        _token: "{{ csrf_token() }}",
        id: $id ,
        productname: $productname,
        sku: $sku,
        upc: $upc,
        charge: $charge,
        cost: $cost,
        taxable: $taxable,
        billingfrequency: $billingfrequency,
        stock: $stock,
        reorderlevel: $reorderlevel,
        restockto: $restockto,
        trackstock: $trackstock
    });
}
</script> 