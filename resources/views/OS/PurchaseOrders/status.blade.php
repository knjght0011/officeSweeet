@extends('master')
@section('content')

    <table class="table" style="border-collapse: collapse; width: 100%; float: left; border: none;">
        <tbody>
        <tr>
            <td style="width: 40%; border: none;">
               <h2 id="purchaseorder-status-display">Status: {{ $PurchaseOrder->getStatus() }}</h2>
                <button style="width: 50%;" class="btn OS-Button btn" type="button" data-toggle="modal" id="purchaseorders-status-save">
                    Update Status
                </button>
            </td>
            <td style="width: 20%; border: none;"></td>
            <td style="width: 40%; border: none; font-size: large; text-align: right;">
                Date: {{ $PurchaseOrder->created_at->toDateString() }}<br>
                PO#: {{ $PurchaseOrder->POnumber() }}
            </td>
        </tr>
        </tbody>
    </table>

    <div class="contents">
        <table class="table" >
            <thead>
            <tr style="background-color: lightblue;">
                <th class="col-md-1">Your Ref</th>
                <th>Description</th>
                <th class="col-md-1">Received</th>
                <th class="col-md-1">Previously Received</th>
                <th class="col-md-1">Units Ordered</th>
                <th class="col-md-1">Unit Cost</th>
                <th class="col-md-1">Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach($PurchaseOrder->items as $item)
                <tr class="PO-Item-Row" data-id="{{ $item->id }}">
                    <td>{{ $item->vendorref }}</td>
                    <td>{{ $item->description }}</td>
                    <td style="padding: 0;"><input type="text" class="form-control receivedinput" value="0"/></td>
                    <td class="previouslyreceived">{{ number_format($item->received , 0) }}</td>
                    <td class="units">{{ number_format($item->units , 0) }}</td>
                    <td>${{ number_format($item->unitcost , 2) }}</td>
                    <td>${{ number_format($item->Total() , 2) }}</td>
                </tr>
            @endforeach

            <tr style="background-color: lightblue;">
                <td style="text-align: center;" colspan="5">Comments</td>
                <td><b>Subtotal:</b></td>
                <td>${{ number_format($PurchaseOrder->Subtotal(), 2) }}</td>
            </tr>
            <tr>
                <td colspan="5" rowspan="3" style="text-align: center;">{{ $PurchaseOrder->comments }}</td>
                <td style="width: 20%; background-color: lightblue;">Tax:</td>
                <td>${{ number_format($PurchaseOrder->TaxAmount(), 2) }}</td>
            </tr>
            <tr>
                <td style="width: 20%; background-color: lightblue;">Shipping:</td>
                <td>${{ number_format($PurchaseOrder->shipping, 2) }}</td>
            </tr>
            <!--
            <tr>
                <td style="width: 20%; background-color: lightblue;">Other:</td>
                <td></td>
            </tr>
            -->
            <tr>
                <td style="width: 20%; background-color: lightblue;"><b>Total:</b></td>
                <td>${{ number_format($PurchaseOrder->Total(), 2) }}</td>
            </tr>
        </tbody>
    </table>


    <!--
    <div class="modal fade" id="UpdateStatus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Upload</h4>
                </div>
                <div class="modal-body">
                    <div class="input-group ">
                        <span class="input-group-addon" for="purchaseorders-status"><div style="width: 7em;">Status:</div></span>
                        <select id="purchaseorders-status" name="purchaseorders-status" class="form-control">
                            <option value="1" selected>Created</option>
                            <option value="2">Ordered</option>
                            <option value="3">Pending Delivery</option>
                            <option value="4">Received</option>
                            <option value="5">Canceled</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="purchaseorders-status-save" type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
    -->

    <script>
        $(document).ready(function () {

            $('#purchaseorders-status').val('{{ $PurchaseOrder->status }}');
            $('.invalid').removeClass('invalid');

            $('#purchaseorders-status-save').click(function () {

                $('.receivedinput').removeClass('invalid');

                $table = {};
                $('.PO-Item-Row').each(function () {

                    $previouslyreceived = parseFloat($(this).find('.previouslyreceived').html());
                    $units = parseFloat($(this).find('.units').html());

                    $remaining = $units - $previouslyreceived;

                    $received = parseFloat($(this).find('.receivedinput').val());

                    $after = $remaining - $received;

                    $table[$(this).data('id')] = $received;
                    if($after < 0){
                        $(this).find('.receivedinput').addClass('invalid');
                    }

                });

                debugger;
                if($('.invalid').length > 0){
                    $.dialog({
                        title: 'Oops..',
                        content: 'Received Cannot be higher than remaining amount left.'
                    });
                }else{
                    Save($table);
                }

            });

        });

        function Save($table) {

            $data = {};
            $data['_token'] = "{{ csrf_token() }}";
            $data['id'] = "{{ $PurchaseOrder->id }}";
            $data['table'] = $table;


            $("body").addClass("loading");
            $post = $.post("/PurchaseOrders/MarkReceived", $data);

            $post.done(function (data) {
                $("body").removeClass("loading");
                switch(data['status']) {
                    case "OK":

                        break;
                    case "notfound":
                        $.dialog({
                            title: 'Oops...',
                            content: 'Unknown Response from server. Please refresh the page and try again.'
                        });
                        break;
                    case "notlogedin":
                        NotLogedIN();
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

        }
    </script>
@stop
