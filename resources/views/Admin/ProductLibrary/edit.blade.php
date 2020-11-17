@extends('master')

@section('content')

    <legend>Product</legend>
  
        <div class="form-group row"> 
            <label class="col-sm-3 form-control-label" for="productname">productname</label>
            <div class="col-sm-6">
                <input id="productname" name="productname" type="text" value="" class="form-control" required="">
            </div>
        </div>
    
        <div class="form-group row"> 
            <label class="col-sm-3 form-control-label" for="sku">sku</label>
            <div class="col-sm-6">
                <input id="sku" name="sku" type="text" value="" class="form-control" required="">
            </div>
        </div>
    
        <div class="form-group row"> 
            <label class="col-sm-3 form-control-label" for="upc">upc</label>
            <div class="col-sm-6">
                <input id="upc" name="upc" type="text" value="" class="form-control" required="">
            </div>
        </div>
        
        <div class="form-group row">
            <label class="col-sm-3 form-control-label" for="charge">charge</label>
            <div class="col-sm-6">
                <input id="charge" name="charge" type="text" value="" class="form-control">
            </div>
        </div>    
            
        <div class="form-group row">
            <label class="col-sm-3 form-control-label" for="cost">cost</label>
            <div class="col-sm-6">
                <input id="cost" name="cost" type="text" value="" class="form-control" required="">
            </div>
        </div>
    
        <div class="form-group row">
            <label class="col-sm-3 form-control-label" for="taxable">taxable</label>
            <div class="col-sm-6">
                <select id="taxable" name="taxable" class="form-control">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
        </div>
    
        <div class="form-group row">
            <label class="col-sm-3 form-control-label" for="billingfrequency">billingfrequency</label>
            <div class="col-sm-6">
                <select id="billingfrequency" name="billingfrequency" class="form-control">
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                    <option value="yearly">Yearly</option>
                </select>
            </div>
        </div>
    
        <div class="form-group row">
            <label class="col-sm-3 form-control-label" for="cost">stock</label>
            <div class="col-sm-6">
                <input id="stock" name="stock" type="text" value="" class="form-control" required="">
            </div>
        </div>
    
        <div class="form-group row">
            <label class="col-sm-3 form-control-label" for="reorderlevel">reorderlevel</label>
            <div class="col-sm-6">
                <input id="reorderlevel" name="reorderlevel" type="text" value="" class="form-control" required="">
            </div>
        </div>
    
        <div class="form-group row">
            <label class="col-sm-3 form-control-label" for="restockto">restockto</label>
            <div class="col-sm-6">
                <input id="restockto" name="restockto" type="text" value="" class="form-control" required="">
            </div>
        </div>
    
        <div class="row">
            <div class="col-md-2">
                <button id="save" name="save" type="button" class="btn OS-Button">Save</button>
            </div>
        </div>  



<script> 
$(document).ready(function() {
    @if(isset($product))
        $('#productname').val("{{ $product->productname }}");
        $('#sku').val("{{ $product->sku }}");
        $('#upc').val("{{ $product->upc }}");
        $('#charge').val("{{ $product->charge }}");
        $('#cost').val("{{ $product->cost }}");
        
        $('#taxable').val("{{ $product->taxable }}");
        $('#billingfrequency').val("{{ $product->billingfrequency }}");
        
        $('#stock').val("{{ $product->stock }}");
        $('#reorderlevel').val("{{ $product->reorderlevel }}");
        $('#restockto').val("{{ $product->restockto }}");
        
        $id = {{ $product->id }};
        
    @else
        $id = 0;

    @endif

    $("#save").click(function()
    {
            productpost = PostProduct($id, $('#productname').val(), $('#sku').val(), $('#upc').val(), $('#charge').val(), $('#cost').val(), $('#taxable').val(), $('#billingfrequency').val(), $('#stock').val(), $('#reorderlevel').val(), $('#restockto').val())
                    
            productpost.done(function( data ) 
            {
                if ($.isNumeric(data)) 
                {
                    $id = data;
                    bootstrap_alert.warning('Success', 'success', 4000);

                }else{
                    //server validation errors
                    ServerValidationErrors(data);
                }
            });

            contactpost.fail(function() {
                bootstrap_alert.warning('Failed to post product details!', 'danger', 4000);
            });
    });

});

function PostProduct($id, $productname, $sku, $upc, $charge, $cost, $taxable, $billingfrequency, $stock, $reorderlevel, $restockto) {
    
    return $.post("/Admin/Products/Save",
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
        restockto: $restockto
    });
}

</script>

@stop