<div class="modal fade" id="AddNewProductModal" tabindex="-1" role="dialog" aria-labelledby="AddNewProductModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="addproduct">Add Product</h4>
            </div>
            <div class="modal-body">

                {!! Form::OSinput("productname", "Product Name", "", "", "true", "") !!}

                {!! Form::OSinput("sku", "Sku", "", "", "false", "") !!}

                {!! Form::OSinput("charge", "Charge", "", "", "true", "") !!}

                {!! Form::OSinput("cost", "Cost", "", "", "true", "") !!}

                {!! Form::OSselect("taxable", "Taxable", ['1' => 'Yes','0' => 'No'], "", 1, "false", "") !!}

                {!! Form::OSinput("products-vendors-ref", "Vendor Reference:", "", "", "false", "") !!}

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
        /*
        $('#AddNewProductModal').on('show.bs.modal', function (event) {

        });
        */

        $('#AddNewProductModal').on('hidden.bs.modal', function (event) {
            $('#productname').removeClass('invalid');
            $('#sku').removeClass('invalid');
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

        $("#SaveProduct").click(function()
        {
            $("body").addClass("loading");
            ResetServerValidationErrors();

            $data = {};
            $data['_token'] = "{{ csrf_token() }}";
            $data['id'] = 0;

            $data['productname'] = $('#productname').val();
            $data['sku'] = $('#sku').val();
            $data['upc'] = "";
            $data['charge'] = $('#charge').val();
            $data['cost'] = $('#cost').val();
            $data['taxable'] = $('#taxable').val();
            $data['billingfrequency'] = "none";
            $data['stock'] = 0;
            $data['reorderlevel'] = 0;
            $data['restockto'] = 0;
            $data['vendorid'] = "{{ $vendor->id }}";
            $data['vendorref'] = $('#products-vendors-ref').val();
            $data['trackstock'] = 1;
            $data['companyuse'] = 0;

            if($data['sku'] === ""){
                $('#sku').addClass('invalid');

                $.dialog({
                    title: 'Error!',
                    content: 'Sku is Required'
                });
                throw new Error("Validation Error");
            }

            productpost = $.post("/Products/Save", $data);

            productpost.done(function( data )
            {
                $("body").removeClass("loading");
                switch(data['status']) {
                    case "OK":
                        console.log(data['product']);
                        AddRow(data['product']['id'], 0, data['product']['vendorref'], data['product']['productname'], 1, data['product']['cost']);
                        break;
                    case "notfound":
                        $.dialog({
                            title: 'Oops...',
                            content: 'Unknown Response from server. Please refresh the page and try again.'
                        });
                        break;
                    case "validation":
                        ServerValidationErrors(data['errors']);
                        break;
                    default:
                        console.log(data);
                        $.dialog({
                            title: 'Oops...',
                            content: 'Unknown Response from server. Please refresh the page and try again.'
                        });
                }
            });

            productpost.fail(function() {
                //bootstrap_alert.warning('Failed to post product details!', 'danger', 4000);
                $("body").removeClass("loading");
                alert('Failed to post product details!');
            });
        });

    });
</script>