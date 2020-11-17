@extends('master')

@section('content')

    <style>
        .delete{
            color: red;
            font-size: 29px;
        }
    </style>

    <h3 style="margin-top: 10px;">Purchase Order</h3>

    <div class="row" style="margin-bottom: 10px;">
        <div class="col-md-3">
            <button style="width: 100%;" class="btn OS-Button" id="po-save" name="po-save" type="button" disabled>
                Save & View Purchase Order
            </button>
        </div>

        <div class="col-md-3">
            <button style="width: 100%;" class="btn OS-Button btn" type="button" data-toggle="modal" data-target="#ProductModal">
                Add Product
            </button>
        </div>

        @if(Auth::user()->hasPermissionMulti('multi_assets_permission', 2))
        <div class="col-md-3">
            <button style="width: 100%;" class="btn OS-Button btn" type="button" data-toggle="modal" data-target="#AddNewProductModal">
                Create New Product For this Vendor
            </button>
        </div>
        @endif

        <div class="col-md-3">
            <button style="width: 100%;" class="btn OS-Button btn BackToVendor" type="button" >
                Back To Vendor
            </button>
        </div>
    </div>

    <div class="row" style="margin-bottom: 10px;">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-addon" for="search"><div style="width: 7em;">Vendor:</div></span>
                <select id="vendor-select" class="form-control">
                    @foreach($vendors as $v)
                        <option value="{{ $v->id }}">{{ $v->getName() }} - {{ $v->address->AddressString() }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-addon" for="search"><div style="width: 7em;">For Client:</div></span>
                <select id="client-select" class="form-control" >
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}"
                                @if(! $order == null)
                                        @if(! $order->quote ==null)
                                            @if($order->quote->client_id == $client->id)
                                                selected="selected"
                                            @endif
                                        @endif
                                @endif
                        >
                            {{ $client->getName() }} - {{ $client->address->AddressString() }} - {{ $client->getPrimaryContactFormatedPhoneNumber() }}</option>

                    @endforeach
                </select>
                <button id="add-client-info">Add to Comment</button>
            </div>
        </div>

        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-addon" for="search"><div style="width: 7em;">Ship To:</div></span>
                <select id="branch-select" class="form-control">
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->AddressString() }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-addon" for="search"><div style="width: 7em;">Tax (%):</div></span>
                <input id="po-tax" class="form-control calculate numonly" value="0">
            </div>
        </div>
    </div>

    @if(isset($order))
        @if($order->status === 2)
            <div class="alert alert-danger">
                Warning: This Purchase Order has  been sent to the vendor. Any changes you make will need to be followed up with the vendor.
            </div>
        @endif
    @endif

    <table id="po-table" class="table">
        <thead>
            <tr>
                <th style="width: 4px;"></th>
                <th class="col-md-1">Your Ref</th>
                <th>Description</th>
                <th class="col-md-1">Units</th>
                <th class="col-md-1">Unit Price</th>
                <th class="col-md-1">Total</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>

    <div class="col-md-10">

        <textarea id="po-comment" class='form-control' style="width: 100%; resize: none;" Rows="5" PLACEHOLDER="Comments"
        >@if(isset($quote)){{ $order->comments }}@else{{ SettingHelper::GetSetting('purchaseordercomment') }}@endif</textarea>

    </div>

    <div class="col-md-2">
        <table id="po-table-totals" class="table" style="text-align: right;">
            <tbody>
                <tr>
                    <td class="col-md-1"><b>Subtotal:</b></td>
                    <td id=""><input id="po-total-subtotal" class='form-control' value='$0.00' readonly></td>
                </tr>
                <tr>
                    <td>Tax:</td>
                    <td><input id="po-total-tax" class='form-control' value='$0.00' readonly></td>
                </tr>
                <tr>
                    <td>Shipping:</td>
                    <td><input id="po-total-shipping" class='form-control total-modifyable numonly calculate' value='$0.00' ></td>
                </tr>
                <!--
                <tr>
                    <td>Other:</td>
                    <td><input id="po-total-other" class='form-control total-modifyable' value='$0.00'></td>
                </tr>
                -->
                <tr>
                    <td><b>Total:</b></td>
                    <td><input id="po-total-total" class='form-control' value='$0.00' readonly></td>
                </tr>
            </tbody>
        </table>
    </div>

    <input id="po-id" style="display: none;">

    <div class="modal fade" id="ShowPdfModel" tabindex="-1" role="dialog" aria-labelledby="ShowPdfModel" aria-hidden="true">
        <div style="margin: 2.5vh auto; width: 95vw" class="modal-dialog" role="document">
            <div style="height: 95vh; width: 95vw;" class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="ShowPdfModel">View Purchase Order</h4>
                </div>
                <div style="height: calc(95vh - 120px);" class="modal-body">
                    <iframe style="width: 100%; height: 100%;"id="ShowPdfFrame" src="{{ url('images/loading4.gif') }}"></iframe>
                </div>
                <div class="modal-footer">

                    <button type="button" id="email" class="btn btn-secondary" data-dismiss="modal">Email to Vendor</button>
                    <button type="button" class="btn btn-secondary BackToVendor" data-dismiss="modal">Back to Vendor</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>



<script>
    $(document).ready(function() {
        SetupPage();

        $('#vendor-select').change(function () {
            $("body").addClass("loading");
            GoToPage('/PurchaseOrders/New/' + $(this).val())
        });

        $('#vendor-select').select2({
            theme: "bootstrap"
        });

        $('#client-select').select2({
            theme: "bootstrap"
        });

        $('#branch-select').select2({
            theme: "bootstrap"
        });

        $('.BackToVendor').click(function () {
            @if(isset($order))
                GoToPage('/Vendors/View/{{ $order->vendor_id }}');
            @else
                GoToPage('/Vendors/View/{{ $sourcevendor }}');
            @endif
        });

        $('#email').click(function () {

            $data = {};
            $email = '{{$vendor->email }}';

            if ($email.length < 5)
            { $email = '{{ $vendor->primarycontact->email }}'; }

            $data['_token'] = "{{ csrf_token() }}";
            $data['body'] = document.getElementById("po-comment").value;
            $data['email'] = $email;
            $data['link_id'] = '{{ $order->id }}';;

            post = $.post("/Email/SendPO", $data);

            post.done(function( data ) {
                $("body").removeClass("loading");
                switch(data['status']) {
                    case "OK":
                        $('#send-client-email-modal').modal('hide');
                        if($('#doc-email-status').val() === "Created"){
                            $.confirm({
                                title: 'Email Sent!',
                                content: 'Would you like to update the Purchase Orders Status to Ordered?',
                                buttons: {
                                    Yes: function () {
                                        UpdateStatus($data['link_id'], 2)
                                    },
                                    No: function () {

                                    },
                                }
                            });
                        }else{
                            SavedSuccess("Email Sent");
                        }
                        break;
                    case "linknotfound":
                        console.log("Link Not Found");
                        $.dialog({
                            title: 'Oops...',
                            content: 'Unknown Response from server. Please refresh the page and try again.'
                        });
                        break;
                    case "disabled":
                        $.dialog({
                            title: 'Oops...',
                            content: 'This has been disabled during the live demo.'
                        });
                        break;
                    default:
                        console.log(data);
                        $.dialog({
                            title: 'Oops...',
                            content: 'Unknown Response from server. Please refresh the page and try again.'
                        });
                }
            });

            post.fail(function() {
                NoReplyFromServer();
            });
        });

        $('#ShowPdfModel').on('show.bs.modal', function (event) {
            //var button = $(event.relatedTarget); // Button that triggered the modal

            var url = "/PurchaseOrders/PDF/";
            var id = $('#po-id').val();

            $('#ShowPdfFrame').attr("src", url + id);
        });

        $().click(function (){
            $data = {};
            $data['_token'] = "{{ csrf_token() }}";

            $data['contact_id'] = $('#doc-email-address').val();
            $data['contact_type'] = "Vendor";
            $data['subject'] = $('#doc-email-subject').val();
            $data['body'] = $('#doc-email-body').val();
            $data['link_id'] = $('#doc-email-id').val();
            $data['type'] = "PurchaseOrder";

            post = $.post("/Email/Send", $data);

            post.done(function( data ) {
                $("body").removeClass("loading");
                switch(data['status']) {
                    case "OK":
                        $('#send-client-email-modal').modal('hide');
                        if($('#doc-email-status').val() === "Created"){
                            $.confirm({
                                title: 'Email Sent!',
                                content: 'Would you like to update the Purchase Orders Status to Ordered?',
                                buttons: {
                                    Yes: function () {
                                        UpdateStatus($data['link_id'], 2)
                                    },
                                    No: function () {

                                    },
                                }
                            });
                        }else{
                            SavedSuccess("Email Sent");
                        }
                        break;
                    case "linknotfound":
                        console.log("Link Not Found");
                        $.dialog({
                            title: 'Oops...',
                            content: 'Unknown Response from server. Please refresh the page and try again.'
                        });
                        break;
                    case "disabled":
                        $.dialog({
                            title: 'Oops...',
                            content: 'This has been disabled during the live demo.'
                        });
                        break;
                    default:
                        console.log(data);
                        $.dialog({
                            title: 'Oops...',
                            content: 'Unknown Response from server. Please refresh the page and try again.'
                        });
                }
            });

            post.fail(function() {
                NoReplyFromServer();
            });
        });

        $('#ShowPdfModel').on('hide.bs.modal', function (event) {
            $('#ShowPdfFrame').attr("src", "{{ url('images/loading4.gif') }}");
        });

        $('.calculate').keyup(function () {
            TableMath();
        });
        $('.total-modifyable').focusout(function(){
            if($(this).val() === ""){
                $(this).val("$0.00");
            }else{
                $string = "$" + parseFloat($(this).val()).toFixed(2);
                $(this).val($string);
            }
        });
        $('.numonly').on('keypress', function(e) {
            keys = ['0','1','2','3','4','5','6','7','8','9','.'];
            return keys.indexOf(event.key) > -1;
        });

        $('#add-client-info').click(function() {

            var sel = document.getElementById("client-select");
            var clientinfo = '\r\n' + sel.options[sel.selectedIndex].text
            clientinfo = clientinfo.replace(/ - /g,'\r\n');

            document.getElementById("po-comment").value += clientinfo;

        })

        $('#po-save').click(function () {

            $("body").addClass("loading");
            ResetServerValidationErrors();

            $data = {};
            $data['_token'] = "{{ csrf_token() }}";
            $data['id'] = $('#po-id').val();
            $data['comment'] = $('#po-comment').val()
            $data['branchid'] = $('#branch-select').val();
            $data['vendorid'] = $('#vendor-select').val();
            $data['items'] = TableData();
            $data['taxpercent'] = parseFloat($('#po-tax').val());

            $shipping = $('#po-total-shipping').val();
            if($shipping.charAt(0) === "$"){
                $data['shipping'] = parseFloat($shipping.substring(1));
            }else{
                $data['shipping'] = parseFloat($shipping);
            }

            $post = $.post("/PurchaseOrders/Save", $data);

            $post.done(function (data) {
                console.log(data);
                $("body").removeClass("loading");
                switch(data['status']) {
                    case "OK":
                        $('#po-id').val(data['PurchaseOrder']['id']);
                        AssignIDsToRows(data['PurchaseOrder']['items']);
                        $('#ShowPdfModel').modal('show');

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

            $post.fail(function () {
                NoReplyFromServer();
            });
        });
    });

    function TableData(){

        $table = [];
        $('#po-table > tbody  > tr').each(function() {
            $row = {};
            $row['productid'] = $(this).data('productid');
            $row['itemid'] = $(this).data('itemid');
            $row['vendorref'] = $(this).find('.vendorref').val();
            $row['description'] = $(this).find('.description').val();
            $row['units'] = $(this).find('.units').val();
            $row['unitcost'] = $(this).find('.unitcost').val();

            $table.push($row);
        });

        return $table;
    }

    function AssignIDsToRows($items) {

        $('#po-table > tbody  > tr').each(function($index, $value) {
            $(this).data('itemid', $items[$index]['id']);
        });

    }

    function AddRow($productid, $itemid, $vendorref, $description, $units, $unitcost){

        $found = false;
        $('#po-table > tbody  > tr').each(function() {
            if($(this).data('productid').toString() === $productid.toString()){
                $(this).find('.units').val(parseInt($(this).find('.units').val()) + 1);
                $found = true;
            }
        });

        if($found === false){
            $row = "<tr data-productid='"+$productid+"' data-itemid='"+$itemid+"'>"+
                        "<td style='padding-right: 0px;'><span class='glyphicon glyphicon-remove delete' aria-hidden='true'></span></td>"+
                        "<td><input class='form-control vendorref' value='"+ $vendorref +"'></td>"+
                        "<td><input class='form-control description' value='"+ $description +"'></td>"+
                        "<td><input class='form-control units' value='"+ $units +"'></td>"+
                        "<td><input class='form-control unitcost' value='"+ parseFloat($unitcost).toFixed(2) +"'></td>"+
                        "<td><input class='form-control total' value='' readonly></td>"+
                    "</tr>";

            $('#po-table').append($row);
        }

        TableMath();

        $('#po-save').prop('disabled', false);

        $('.delete').click(function () {
           $row = $(this).parent().parent();
           $row.remove();

           //$('#po-table tr').length;
        });

        $('.units').keyup(function () {
            TableMath();
        });
        $('.units').focusout(function(){
            if($(this).val() === ""){
                $(this).val(0)
            }
        });
        $('.units').on('keypress', function(e) {
            keys = ['0','1','2','3','4','5','6','7','8','9','.'];
            return keys.indexOf(event.key) > -1;
        });
        $('.unitcost').keyup(function () {
            TableMath();
        });
        $('.unitcost').focusout(function(){
            if($(this).val() === ""){
                $(this).val(0)
            }
        });
        $('.unitcost').on('keypress', function(e) {
            keys = ['0','1','2','3','4','5','6','7','8','9','.'];
            return keys.indexOf(event.key) > -1;
        });
    }

    function TableMath(){

        //subtotal
        $total = 0;
        $('#po-table > tbody  > tr').each(function() {
            $units = $(this).find('.units').val();
            $unitcost = $(this).find('.unitcost').val();
            $(this).find('.total').val(($units * $unitcost).toFixed(2));
            $total = $total + ($units * $unitcost);
        });

        $('#po-total-subtotal').val("$" + $total.toFixed(2));

        //tax
        $taxpercent = parseFloat($('#po-tax').val());
        $totaloneprecent = $total/100;
        $taxtotal = $totaloneprecent * $taxpercent;
        $('#po-total-tax').val("$" + $taxtotal.toFixed(2));

        //shipping
        $shipping = $('#po-total-shipping').val();
        if($shipping.charAt(0) === "$"){
            $shipping = parseFloat($shipping.substring(1));
        }else{
            $shipping = parseFloat($shipping);
        }

        //total
        $total = $total + $taxtotal;
        $total = $total + $shipping;
        $('#po-total-total').val("$" + $total.toFixed(2));
    }


    @if(isset($order))
        function SetupPage() {
            $('#po-id').val({{ $order->id }});
            $('#vendor-select').val({{ $order->vendor_id }});
            $('#po-products-vendors').val({{ $order->vendor_id }});
            $('#branch-select').val({{ $order->branch_id }});
            $('#po-total-shipping').val("${{ number_format($order->shipping , 2) }}");

            var n = {{ $order->taxpercent }};
            var noZeroes = n.toString();
            $('#po-tax').val(noZeroes);

            @foreach($order->items as $item)
            AddRow({{ $item->product_id }} , {{ $item->id }}, "{{ $item->vendorref }}" , "{{ $item->description }}", {{ $item->units }}, {{ $item->unitcost }});
            @endforeach

        }
    @else
        function SetupPage() {
            $('#po-id').val(0);
            $('#vendor-select').val({{ $sourcevendor }});
            $('#po-products-vendors').val({{ $sourcevendor }});
            $('#branch-select').val({{ Auth::user()->branch_id }});

        }
    @endif
</script>

@include('OS.PurchaseOrders.modals.addproduct')
@include('OS.PurchaseOrders.modals.newproduct')
@stop

