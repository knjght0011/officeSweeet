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
                        <th>Charge</th>
                        <th>tax</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>SKU/UPC</th>
                        <th>Product Name</th>
                        <th>Charge</th>
                        <th>Charge</th>
                        <th>tax</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>{{ $product->SKU }}</td>
                            <td>{{ htmlspecialchars($product->productname) }}</td>
                            <td>${{ $product->getCharge() }}</td>
                            <td>{{ round($product->charge, 2) }}</td>
                            <td>{{ $product->Tax() }}</td>
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
                { "targets": [3,4],"visible": false}
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
                $('#addproduct').html('Select a Product');
            }
            else {
                //table.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
                $('#addproduct').html('Add Product');
            }
        });

        $("#addproduct").click(function()
        {
            $row = table.rows('.selected').data();
            $.each($row, function ($index, $value){
                UpdateQuoute($value);
            });
        });

        $("#productstable").css("width" , "100%");

        UpdatePageinate(table.page.info());
    });

    function UpdateQuoute($info){


        found = false;
        $(".item-row").each(function(i) {

            $(this).find('input').each(function(i) {


                if($(this).attr('name') === "sku"){
                    if($(this).val() === $info[0]){
                        found = true;
                    }
                }
            });
            if(found === true){
                $increment = $(this);
                return false;
            }
        });

        if (typeof $increment == 'undefined'){

            AddTableRow($info[0], $info[1], $info[3], "1", $info[4]);

            /*
            $("#items tbody").append('<tr class="item-row">\n\
                                <td name="sku" class="item-name"><input class="noborder form-control input-md sku" name="sku" type="text" disabled="true" value="'+ $info[0] +'"></td>\n\
                                <td name="description" class="description"><input class="noborder form-control input-md description" name="description" type="text" value="'+ $info[1] +'"></td>\n\
                                <td name="costperunit" ><input class="noborder form-control input-md cost" name="cost" type="number" value="'+ $info[3] +'"></td>\n\
                                <td name="units" style="width: 65px;"><input style="width: 55px; float: left;" class="noborder form-control input-md qty" name="qty" type="number" value="1"></td>\n\
                                <td name="total"><input class="noborder form-control input-md total" name="total" type="number" value="" disabled="true"></td>\n\
                                <td name="tax-percent" style="width: 65px;"><input style="width: 55px; float: left;" class="noborder form-control input-md tax-percent" name="tax-percent" type="number" value="'+ $info[4] +'"></td>\n\
                                <td name="tax-total" id="tax-total" ><input class="noborder form-control input-md tax-total" name="tax-total" type="number" value="0" disabled="true"></td>\n\
                                <td name="line-total" id="line-total" ><input class="noborder form-control input-md line-total" name="line-total" type="number" value="0" disabled="true"></td>\n\
                                <td name="id" hidden="true"><input class="form-control input-md id" name="id" type="number" value="0" disabled="true"></td>\n\
                                <td name="delete" style="border-right: none; border-top: none; border-bottom: none;"><button id="delete" name="delete" type="button" class="btn OS-Button btn-sm" value="0"><span class="glyphicon glyphicon-trash"></span></button></td>\n\
                            </tr>');

            if ($(".delete").length > 0) $(".delete").show();

            $lastrow = $(".item-row:last");
            update_price($lastrow);
            */
            //$lastrow.find('td[name=units]').css("width" , "10px");

        }else{

            $increment.find('input').each(function(i) {
                if($(this).attr('name') === "units"){
                    $value = parseInt($(this).val());
                    $value = $value + 1;
                    $(this).val($value);
                    return false;
                }
            });
            update_price($increment);
            $increment = undefined;
        }

        update_total();
    }
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