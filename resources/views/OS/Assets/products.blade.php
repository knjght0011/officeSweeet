<div class="row" style="margin-top: 10px; margin-bottom: 10px;">
    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="search"><div style="width: 7em;">Search:</div></span>
            <input id="products-search" name="products-search" type="text" placeholder="" value="" class="form-control">
        </div>
    </div>

    <div class="col-md-3">
        <button style="width: 100%;" type="button" class="btn OS-Button" data-toggle="modal" data-mode="new" data-target="#addproduct">
            Add Product
        </button>
    </div>

    <div class="col-md-3">
        <button style="width: 100%;" type="button" class="btn OS-Button" data-toggle="modal" data-mode="edit" data-target="#addproduct">
            Edit Selected Product
        </button>
    </div>

    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="products-sales-tax"><div
                        style="width: 9em;">Sales Tax Rate:</div></span>
            <input id="products-sales-tax" name="products-sales-tax" type="" placeholder=""
                   value="{{ SettingHelper::GetSalesTax() }}" class="form-control"
                   data-validation-label="Sales Tax Rate" data-validation-required="true" data-validation-type=""><span
                    class="input-group-btn"><button id="products-sales-tax-save" class="btn btn-default" type="button">Save</button></span>


        </div>
        <div class="input-group ">
        <span class="input-group-addon" for="products-city-tax"><div
                    style="width: 9em;">City Tax Rate:</div></span>
        <input id="products-city-tax" name="products-city-tax" type="" placeholder=""
               value="{{ SettingHelper::GetCityTax() }}" class="form-control"
               data-validation-label="City Tax Rate" data-validation-required="true" data-validation-type=""><span
                class="input-group-btn"><button id="products-city-tax-save" class="btn btn-default" type="button">Save</button></span>
        </div>
    </div>
</div>

<div class="row" style="margin-top: 10px;">
    <div class="col-md-3">

    </div>
    <div class="col-md-6">
        {!! PageElement::TableControl('products') !!}
    </div>
    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="inventory-manager"><div
                        style="width: 9em;">Inventory Manager:</div></span>
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

<table id="productstable" class="table">
    <thead>
        <tr>
            <th>SKU</th>
            <th>Product Name</th>
            <th>Category</th>
            <th>Location</th>
            <th>Charge</th>
            <th>Cost</th>
            <th>Taxable</th>
            <th>Track Stock</th>
            <th>Stock</th>
            <th>Reorder Level</th>
            <th>Restock To</th>
            <th class="datatables-invisible-col">id</th>
            <th class="datatables-invisible-col">vendor id</th>
            <th class="datatables-invisible-col">vendor ref</th>
            <th class="datatables-invisible-col">company use</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>SKU</th>
            <th>Product Name</th>
            <th>Category</th>
            <th>Location</th>
            <th>Charge</th>
            <th>Cost</th>
            <th>Taxable</th>
            <th>Track Stock</th>
            <th>Stock</th>
            <th>Reorder Level</th>
            <th>Restock To</th>
            <th>id</th>
            <th>vendor id</th>
            <th>vendor ref</th>
            <th>company use</th>
        </tr>
    </tfoot>
    <tbody>
    @foreach($products as $product)
        <tr>
            <td class="col-md-1">{{ $product->SKU }}</td>
            <td>{{ $product->productname }}</td>
            <td>{{ $product->category }}</td>
            <td>{{ $product->location }}</td>
            <td class="col-md-1">${{ $product->getCharge() }}</td>
            <td class="col-md-1">${{ $product->getcost() }}</td>
            <td class="col-md-1">{{ $product->taxablewords() }}</td>
            <td class="col-md-1">{{ $product->trackstockwords() }}</td>
            <td class="col-md-1">{{ $product->stockiftracked()  }}</td>
            <td class="col-md-1">{{ $product->reorderleveliftracked()  }}</td>
            <td class="col-md-1">{{ $product->restocktoiftracked()  }}</td>
            <td>{{ $product->id }}</td>
            <td>{{ $product->vendor_id }}</td>
            <td>{{ $product->vendorref }}</td>
            <td>{{ $product->companyuse }}</td>
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
                <h4 class="modal-title" id="addproduct">Add Product</h4>
            </div>

            <div class="modal-body">

                <div class="input-group ">
                    <span class="input-group-addon" for="products-vendors"><div
                                style="width: 15em;">Vendor:</div></span>
                    <select style="width: 100%;" id="products-vendors" name="products-vendors" type="text"
                            placeholder="choice" class="form-control">
                        @foreach($vendors as $v)
                            <option value="{{ $v->id }}">{{ $v->getName() }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="input-group ">
                    <span class="input-group-addon" for="products-vendors-ref"><div style="width: 15em;">Vendor Ref(Optional):</div></span>
                    <input id="products-vendors-ref" name="products-vendors-ref" type="text" placeholder="" value=""
                           class="form-control addproductinputs">
                </div>

                <div class="input-group ">
                    <span class="input-group-addon" for="productname"><div
                                style="width: 15em;">Product Name:</div></span>
                    <input id="productname" name="productname" type="text" placeholder="" value="" class="form-control addproductinputs">
                </div>

                <div class="input-group ">
                    <span class="input-group-addon" for="products-category"><div
                                style="width: 15em;">Category:</div></span>
                    <input id="products-category" name="products-category" type="text" placeholder="" value="" class="form-control addproductinputs">
                </div>

                <div class="input-group ">
                    <span class="input-group-addon" for="products-location"><div
                                style="width: 15em;">Location:</div></span>
                    <input id="products-location" name="products-location" type="text" placeholder="" value="" class="form-control addproductinputs">
                </div>

                <div class="input-group ">
                    <span class="input-group-addon" for="sku"><div style="width: 15em;">Sku:</div></span>
                    <input id="sku" name="sku" type="text" placeholder="" value="" class="form-control addproductinputs">
                </div>

                <div class="input-group ">
                    <span class="input-group-addon" for="charge"><div style="width: 15em;">Charge:</div></span>
                    <input id="charge" name="charge" type="text" placeholder="" value="" class="form-control addproductinputs numonly">
                </div>

                <div class="input-group ">
                    <span class="input-group-addon" for="cost"><div style="width: 15em;">Cost:</div></span>
                    <input id="cost" name="cost" type="text" placeholder="" value="" class="form-control addproductinputs numonly">
                </div>

                <div class="input-group ">
                    <span class="input-group-addon" for="taxable"><div style="width: 15em;">Taxable:</div></span>
                    <input type="checkbox" name="checkboxes" id="taxable" data-on="Yes" data-off="No"
                           data-toggle="toggle" data-width="100%" class="addproductinputs" checked>
                </div>

                <div class="input-group ">
                    <span class="input-group-addon" for="repeat"><div style="width: 15em;">Track Stock:</div></span>
                    <input type="checkbox" name="checkboxes" id="trackstock" data-on="Held In Inventory" data-off="Ordered On Demand"
                           data-toggle="toggle" data-width="100%" class="addproductinputs" checked>
                </div>

                <div id="stocktrackinfo" style="display: none;">

                    <div class="input-group ">
                        <span class="input-group-addon" for="repeat"><div style="width: 15em;">Purpose:</div></span>
                        <input type="checkbox" name="checkboxes" id="companyuse" data-on="Company Use Only"
                               data-off="For Resale" data-toggle="toggle" value="1" data-width="100%">
                    </div>

                    <div class="input-group ">
                        <span class="input-group-addon" for="stock"><div
                                    style="width: 15em;">Current Stock:</div></span>
                        <input id="stock" name="stock" type="text" placeholder="" value="" class="form-control numonly">
                    </div>

                    <div class="input-group ">
                        <span class="input-group-addon" for="reorderlevel"><div
                                    style="width: 15em;">Reorder Level:</div></span>
                        <input id="reorderlevel" name="reorderlevel" type="text" placeholder="" value=""
                               class="form-control numonly">
                    </div>

                    <div class="input-group ">
                        <span class="input-group-addon" for="restockto"><div
                                    style="width: 15em;">Restock to:</div></span>
                        <input id="restockto" name="restockto" type="text" placeholder="" value="" class="form-control numonly">
                    </div>
                </div>
                <input style="display: none;" id="id" name="id" type="text" value="" class="form-control">

            </div>
            <div class="modal-footer">
                <button id="new-vendor" name="new-vendor" type="button" class="btn OS-Button">Add New Vendor</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button id="SaveProduct" name="SaveProduct" type="button" class="btn OS-Button">Save</button>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function () {

        AddPopup($('#sku'), "left", "Your SKU/Product ID/Reference");
        AddPopup($('#products-vendors-ref'), "left", "The Vendors SKU/Product ID/Reference");

        // DataTable
        var productstable = $('#productstable').DataTable({
            "columns": [
                {"data": "sku"},
                {"data": "description"},
                {"data": "category"},
                {"data": "location"},
                {"data": "charge"},
                {"data": "cost"},
                {"data": "taxable"},
                {"data": "track_stock"},
                {"data": "stock"},
                {"data": "reorder_level"},
                {"data": "restock_to"},
                {"data": "id"},
                {"data": "vendor_id"},
                {"data": "vendor_ref"},
                {"data": "company_use"},
            ],
            "columnDefs": [
                {
                    "targets": "datatables-invisible-col",
                    "visible": false
                }
            ],
        });

        $('#productstable tbody').on( 'click', 'tr', function () {
            $row = $(this);
            if ( $(this).hasClass('selected') ) {
                $(this).removeClass('selected');
            }
            else {
                productstable.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }
        });

        $('#inventory-manager').val("{{  SettingHelper::GetSetting("inventorymanagerid") }}");

        $('#products-vendors').select2({

            theme: "bootstrap",
            dropdownParent: $('#addproduct')
        });

        $('#addproduct').on('show.bs.modal', function (event) {

            var button = $(event.relatedTarget); // Button that triggered the modal

            if(button.data('mode') === "new"){

                $('#products-vendors').val("").trigger('change');
                $('#products-vendors-ref').val("");

                $('#productname').val("");
                $('#sku').val("");
                $('#charge').val("0.00");
                $('#cost').val("0.00");
                $('#taxable').bootstrapToggle('on');

                $('#products-category').val("");
                $('#products-location').val("");

                $('#trackstock').bootstrapToggle('off');
                $('#stocktrackinfo').css('display' , 'none');

                $('#companyuse').bootstrapToggle('off');
                $('#stock').val("0");
                $('#reorderlevel').val("0");
                $('#restockto').val("0");
                $('#id').val("0");

                $('.addproductinputs').prop('disabled', true);

            }else{
                $row = productstable.row('.selected').data();

                if($row === undefined){
                    event.preventDefault();
                    $.dialog({
                        title: 'Oops...',
                        content: 'Please Select a Product from the table.'
                    });
                }else{
                    $('#id').val($row['id']);
                    $('#products-vendors').val($row['vendor_id']).trigger('change');
                    $('#products-vendors-ref').val($row['vendor_ref']);

                    $('#productname').val($row['description']);

                    $('#products-category').val($row['category']);
                    $('#products-location').val($row['location']);

                    $('#sku').val($row['sku']);
                    $('#charge').val($row['charge'].substring(1));
                    $('#cost').val($row['cost'].substring(1));

                    if($row['taxable'] === "Yes"){
                        $('#taxable').bootstrapToggle('on');
                    }else{
                        $('#taxable').bootstrapToggle('off');
                    }

                    if ($row['track_stock'] === "Yes") {
                        $('#stocktrackinfo').css('display', 'block');
                        $('#trackstock').bootstrapToggle('on');

                        if ($row['company_use'] === "1") {
                            $('#companyuse').bootstrapToggle('on');
                        } else {
                            $('#companyuse').bootstrapToggle('off');
                        }

                        $('#stock').val($row['stock']);
                        $('#reorderlevel').val($row['reorder_level']);
                        $('#restockto').val($row['restock_to']);
                    } else {
                        $('#stocktrackinfo').css('display', 'none');
                        $('#trackstock').bootstrapToggle('off');
                        $('#companyuse').bootstrapToggle('off');
                        $('#stock').val("0");
                        $('#reorderlevel').val("0");
                        $('#restockto').val("0");
                    }
                }
            }
        });

        $('#products-vendors').change(function () {
            $('.addproductinputs').prop('disabled', false);
        });

        $('#addproduct').on('hidden.bs.modal', function (event) {
            $('#productname').removeClass('invalid');
            $('#sku').removeClass('invalid');
            $('#charge').removeClass('invalid');
            $('#cost').removeClass('invalid');
            $('#taxable').removeClass('invalid');
            $('#stock').removeClass('invalid');
            $('#reorderlevel').removeClass('invalid');
            $('#restockto').removeClass('invalid');
            $('#products-category').removeClass('invalid');
            $('#products-location').removeClass('invalid');
        });

        $('#trackstock').change(function(){
            if($('#trackstock').prop( "checked")){
                $('#stocktrackinfo').css('display' , 'block');
            }else{
                $('#stocktrackinfo').css('display' , 'none');
            }
        });

        $("#products-previous-page").click(function () {
            productstable.page("previous").draw('page');
            PageinateUpdate(productstable.page.info(), $('#products-next-page'), $('#products-previous-page'), $('#products-tableInfo'));
        });

        $("#products-next-page").click(function () {
            productstable.page("next").draw('page');
            PageinateUpdate(productstable.page.info(), $('#products-next-page'), $('#products-previous-page'), $('#products-tableInfo'));
        });

        $('#products-search').on('keyup change', function () {
            productstable.search(this.value).draw();
            PageinateUpdate(productstable.page.info(), $('#products-next-page'), $('#products-previous-page'), $('#products-tableInfo'));
        });

        PageinateUpdate(productstable.page.info(), $('#products-next-page'), $('#products-previous-page'), $('#products-tableInfo'));

        $("#generalproducts").children().find(".dataTables_filter").css('display', 'none');
        $("#generalproducts").children().find(".dataTables_length").css('display', 'none');
        $("#generalproducts").children().find(".dataTables_paginate").css('display', 'none');
        $("#generalproducts").children().find(".dataTables_info").css('display', 'none');
        $('#productstable').css('width', "100%");

        $("#products-sales-tax-save").click(function () {
            $("body").addClass("loading");

            $tax = $('#products-sales-tax').val();

            taxpost = $.post("/Products/SaveTax",
                {
                    _token: "{{ csrf_token() }}",
                    tax: $tax,
                });

            taxpost.done(function (data) {
                $("body").removeClass("loading");
                $.dialog({
                    title: 'Success!',
                    content: 'Saved.'
                });
            });

            taxpost.fail(function () {
                $("body").removeClass("loading");
                $.dialog({
                    title: 'Oops..',
                    content: 'Server Error, please try again later'
                });

            });

        });

        $("#products-city-tax-save").click(function () {
            $("body").addClass("loading");

            $tax = $('#products-city-tax').val();

            taxpost = $.post("/Products/SaveCityTax",
                {
                    _token: "{{ csrf_token() }}",
                    tax: $tax,
                });

            taxpost.done(function (data) {
                $("body").removeClass("loading");
                $.dialog({
                    title: 'Success!',
                    content: 'Saved.'
                });
            });

            taxpost.fail(function () {
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


        $("#products-city-tax").keydown(function (e) {
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

        $("#inventory-manager-save").click(function () {
            $("body").addClass("loading");

            $inventorymanagerid = $('#inventory-manager').val();

            managerpost = $.post("/Products/SaveInventoryManager",
                {
                    _token: "{{ csrf_token() }}",
                    inventorymanagerid: $inventorymanagerid,
                });

            managerpost.done(function (data) {
                $("body").removeClass("loading");
                $.dialog({
                    title: 'Success!',
                    content: 'Saved.'
                });
            });

            managerpost.fail(function () {
                $("body").removeClass("loading");
                $.dialog({
                    title: 'Oops..',
                    content: 'Server Error, please try again later'
                });

            });

        });

        $("#SaveProduct").click(function () {

            ResetServerValidationErrors();

            $data = {};
            $data['_token'] = "{{ csrf_token() }}";
            $data['productname'] = $('#productname').val();
            $data['sku'] = $('#sku').val();
            $data['upc'] = "";
            $data['charge'] = $('#charge').val();
            $data['cost'] = $('#cost').val();
            $data['taxable'] = $('#taxable').val();
            $data['billingfrequency'] = "none";
            $data['stock'] = $('#stock').val();
            $data['reorderlevel'] = $('#reorderlevel').val();
            $data['restockto'] = $('#restockto').val();
            $data['id'] = $('#id').val();
            $data['vendorid'] = $('#products-vendors').val();
            $data['vendorref'] = $('#products-vendors-ref').val();

            $data['category'] = $('#products-category').val();
            $data['location'] = $('#products-location').val();


            if($('#trackstock').prop( "checked")){
                $data['trackstock'] = 1;
            }else{
                $data['trackstock'] = 0;
            }

            if ($('#companyuse').prop("checked")) {
                $data['companyuse'] = 1;
            } else {
                $data['companyuse'] = 0;
            }

            if ($data['sku'] === "") {
                $('#sku').addClass('invalid');

                $.dialog({
                    title: 'Error!',
                    content: 'Sku is Required'
                });
                throw new Error("Validation Error");
            }

            $("body").addClass("loading");
            productpost = $.post("/Products/Save", $data);

            productpost.done(function (data) {
                $("body").removeClass("loading");
                switch (data['status']) {
                    case "OK":
                        $row = [];
                        $row['sku'] = data['product']['SKU'];
                        $row['description'] = data['product']['productname'];
                        $row['charge'] = "$" + data['product']['charge'];
                        $row['cost'] = "$" + data['product']['cost'];

                        $row['category'] = data['product']['category'];
                        $row['location'] = data['product']['location'];

                        if(data['product']['taxable'] === 1){
                            $row['taxable'] = "Yes";
                        }else{
                            $row['taxable'] = "No";
                        }

                        if(data['product']['trackstock'] === 1){
                            $row['track_stock'] = "Yes";
                            $row['company_use'] = data['product']['companyuse'];
                            $row['stock'] = data['product']['stock'];
                            $row['reorder_level'] = data['product']['reorderlevel'];
                            $row['restock_to'] = data['product']['restockto'];
                        }else{
                            $row['track_stock'] = "No";
                            $row['company_use'] = data['product']['companyuse'];
                            $row['stock'] = "";
                            $row['reorder_level'] = "";
                            $row['restock_to'] = "";
                        }

                        $row['vendor_id'] = data['product']['vendor_id'];
                        $row['vendor_ref'] = data['product']['vendorref'];

                        $row['id'] = data['product']['id'];

                        if(data['mode'] === "new"){
                            productstable.row.add($row).draw();
                        }else{
                            productstable.row('.selected').data($row).draw();
                        }

                        $('#addproduct').modal('hide');
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

            productpost.fail(function () {
                NoReplyFromServer();
            });
        });

        $('#new-vendor').click(function () {
           GoToPage('/Vendors/Add')
        });

    });

    function UpdateStock($id, $stock) {

        $rowdata = $('#productstable').DataTable().row("[data-id='" + $id + "']").data();
        $rowdata[6] = $stock;
        $('#productstable').DataTable().row("[data-id='" + $id + "']").data($rowdata);

    }
</script> 