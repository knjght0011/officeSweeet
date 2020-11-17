@extends('master')

@section('content')

    <style>
        .delete{
            color: red;
            font-size: 29px;
        }
    </style>

    <h3 style="margin-top: 10px;">Quick Sale</h3>

    <div class="row">
        <div class="col-md-3">

        </div>
        <div class="col-md-6">
            <div class="input-group ">
                <span class="input-group-addon" for="length"><div style="width: 7em;">Sales Person:</div></span>
                <select id="salesperson" name="salesperson" type="text" placeholder="choice" class="form-control">
                    @foreach(\App\Models\User::where('os_support_permission', 0)->get() as $user)
                    <option value="{{ $user->id }}">{{ $user->firstname }} {{ $user->lastname }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>


<table class="table" id="pos-table">
    <thead>
    <tr>
        <th style="width: 4px;"></th>
        <th class="col-md-1">SKU</th>
        <th>Description</th>
        <th class="col-md-1">Units</th>
        <th class="col-md-1">Unit Price</th>
        <th class="col-md-1">Tax (%)</th>
        <th class="col-md-1">Tax ($)</th>
        <th class="col-md-1">CT (%)</th>
        <th class="col-md-1">CT ($)</th>
        <th class="col-md-1">Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($products as $product)
        <tr data-productid='{{ $product->id }}' data-itemid='0'>
            <td style='padding-right: 0px;'><span class='glyphicon glyphicon-remove delete' aria-hidden='true'></span></td>
            <td><input class='form-control sku' value='{{ $product->SKU }}'></td>
            <td><input class='form-control description' value='{{ $product->productname }}'></td>
            <td><input type="number" class='form-control units runmath numonly' value='1'></td>
            <td><input class='form-control unitcost runmath numonly' value='{{ number_format($product->charge, 2, '.', '') }}'></td>
            <td><input class='form-control taxpercent runmath numonly'
                @if($product->taxable === 1)
                value='{{ SettingHelper::GetSalesTax() }}'
                @else
                value='0'
                @endif
                >
            </td>
            <td><input class='form-control taxamount' value='' readonly></td>
            <td><input class='form-control citytaxpercent runmath numonly'
                   @if($product->taxable === 1)
                   value='{{ SettingHelper::GetCityTax() }}'
                   @else
                   value='0'
                    @endif
                    >
            </td>
            <td><input class='form-control citytaxamount' value='' readonly></td>
            <td><input class='form-control total' value='' readonly></td>
            </tr>
    @endforeach
    @foreach ($services as $service)
        <tr data-productid='0' data-itemid='0'>
            <td style='padding-right: 0px;'><span class='glyphicon glyphicon-remove delete' aria-hidden='true'></span></td>
            <td><input class='form-control sku' value='{{ $service->sku }}'></td>
            <td><input class='form-control description' value='{{ $service->description }}'></td>
            <td><input type="number" class='form-control units runmath numonly' value='1'></td>
            <td><input class='form-control unitcost runmath numonly' value='{{ number_format($service->charge, 2, '.', '') }}'></td>
            <td><input class='form-control taxpercent runmath numonly'
                       @if($service->taxable === 1)
                       value='{{ SettingHelper::GetSalesTax() }}'
                       @else
                       value='0'
                        @endif
                >
            </td>
            <td><input class='form-control taxamount' value='' readonly></td>
            <td><input class='form-control citytaxpercent runmath numonly'
                       @if($service->taxable === 1)
                       value='{{ SettingHelper::GetCityTax() }}'
                       @else
                       value='0'
                        @endif
                >
            </td>
            <td><input class='form-control citytaxamount' value='' readonly></td>
            <td><input class='form-control total' value='' readonly></td>
        </tr>
    @endforeach
    </tbody>
</table>


    <div class="col-md-8">
        <div class="row">
            <div class="col-md-6">
                <button style="width: 100%; height: 75px; font-size: xx-large;" class="btn OS-Button btn" type="button" data-toggle="modal" data-target="#PayByCash">
                    Cash
                </button>
            </div>
            <div class="col-md-6">
                <button style="width: 100%; height: 75px; font-size: xx-large;" class="btn OS-Button btn" type="button" data-toggle="modal" data-target="#PayByCheck">
                    Check
                </button>
            </div>
        </div>

        <div class="row" style="margin-top: 30px;">
            <div class="col-md-6">
                <button style="width: 100%; height: 75px; font-size: xx-large;" class="btn OS-Button btn" type="button" data-toggle="modal" data-target="#PayByCardSwipe"
                    @if(SettingHelper::GetSetting('transnational-username') === null)
                    disabled
                    @endif
                >
                    Credit Card Swipe
                </button>
            </div>
            <div class="col-md-6">
                <button style="width: 100%; height: 75px; font-size: xx-large;" class="btn OS-Button btn" type="button" data-toggle="modal" data-target="#PayByCard"
                    @if(SettingHelper::GetSetting('transnational-username') === null)
                    disabled
                    @endif
                >
                    Credit Card
                </button>
            </div>
        </div>
        @if(app()->make('account')->subdomain === "local" or app()->make('account')->subdomain === "demo" )
        <div class="row" style="margin-top: 30px;">
            <div class="col-md-6">
                <button style="width: 100%; height: 75px; font-size: xx-large;" class="btn OS-Button btn" type="button" data-toggle="modal" data-target="#PayByCard">
                    Send PO To Vendor
                </button>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <table id="po-table-totals" class="table" style="text-align: right; font-weight: bold; font-size: xx-large;">
            <tbody>


            <tr>
                <td class="col-md-6"><b>Total Due:</b></td>
                <td class="col-md-6"><input style="font-size: xx-large;" id="pos-total-total" class='form-control' value='$0.00' readonly></td>
            </tr>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="PayByCash" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Pay By Cash</h4>
                </div>
                <div class="modal-body">
                    <div class="input-group">
                        <span class="input-group-addon" for="cash-recived"><div style="width: 7em;">Cash Recived:</div></span>
                        <input id="cash-recived" name="cash-recived" type="number" placeholder="" class="form-control numonly">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon" for="cash-comments"><div style="width: 7em;">Comments:</div></span>
                        <textarea id="cash-comments" name="cash-comments" type="text" placeholder="" class="form-control" style="width: 100%; resize: none;" Rows="5"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="save-cash" type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="PayByCheck" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Pay By Check</h4>
                </div>
                <div class="modal-body">
                    <div class="input-group">
                        <span class="input-group-addon" for="check-number"><div style="width: 7em;">Check No.:</div></span>
                        <input id="check-number" name="check-number" type="text" placeholder="" class="form-control">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon" for="check-comments"><div style="width: 7em;">Comments:</div></span>
                        <textarea id="check-comments" name="check-comments" type="text" placeholder="" class="form-control" style="width: 100%; resize: none;" Rows="5"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="save-check" type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="PayByCardSwipe" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Pay By Card</h4>
                </div>
                <div class="modal-body">
                    Please Scan Card...

                    <input id="card-swipe" style="height: 0px; width: 0px; border: none;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Save & Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="PayByCard" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Pay By Card</h4>
                </div>
                <div class="modal-body">
                    <div class="input-group">
                        <span width="10em" class="input-group-addon" for="cc-firstnameoncard"><div style="width: 10em;">Firstname on Card:</div></span>
                        <input id="cc-firstnameoncard" name="cc-firstnameoncard" type="text" class="form-control input-md">
                    </div>

                    <div class="input-group">
                        <span width="10em" class="input-group-addon" for="cc-lastnameoncard"><div style="width: 10em;">Lastname on Card:</div></span>
                        <input id="cc-lastnameoncard" name="cc-lastnameoncard" type="text" class="form-control input-md">
                    </div>

                    <div class="input-group">
                        <span width="10em" class="input-group-addon" for="cc-cardnumber"><div style="width: 10em;">Card Number:</div></span>
                        <input id="cc-cardnumber" name="cc-cardnumber" type="text" class="form-control input-md">
                    </div>

                    <!--
                    <div class="input-group">
                        <span width="10em" class="input-group-addon" for="cc-cardCVC"><div style="width: 10em;">CV Code:</div></span>
                        <input
                                id="cc-cardCVC"
                                type="tel"
                                class="form-control"
                                name="cardCVC"
                                placeholder="CVC"
                                autocomplete="cc-csc"
                                required
                        />
                    </div>
                    -->

                    <div class="input-group">
                        <span width="10em" class="input-group-addon" for="cc-cardExpiry-month"><div style="width: 10em;">Expiration Date:</div></span>
                        <select
                                style="width: 49%; display: inline-block;"
                                id="cc-cardExpiry-month"
                                class="form-control"
                        >
                            <option value="01">01</option>
                            <option value="02">02</option>
                            <option value="03">03</option>
                            <option value="04">04</option>
                            <option value="05">05</option>
                            <option value="06">06</option>
                            <option value="07">07</option>
                            <option value="08">08</option>
                            <option value="09">09</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                        </select>
                        <select
                                style="width: 49%; display: inline-block;"
                                id="cc-cardExpiry-year"
                                class="form-control"
                        >
                            @foreach(PageElement::Years() as $year)
                                <option value="{{$year}}">{{$year}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="CC-Process-Payment" type="button" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        $(document).ready(function() {

            $('#salesperson').val({{ Auth::user()->id }});

            $('#PayByCard').on('shown.bs.modal', function (event) {
                $('#cc-cardnumber').focus();
            });

            $('#CC-Process-Payment').click(function () {
                $data = {};
                $data['mode'] = "manual";
                $data['_token'] = "{{ csrf_token() }}";
                $data['clientid'] = "{{ $client->id }}";
                $data['method'] = "Card";
                $data['depositcomments'] = "";
                $data['quotecomments'] = "";
                //$data['swipestring'] = $(this).val();
                $data['table'] =  TableData();
                $data['amount'] = $('#pos-total-total').val();

                $data['cardNumber'] = $('#cc-cardnumber').val();
                $data['cardExpiryMonth'] = $('#cc-cardExpiry-month').val();
                $year = $('#cc-cardExpiry-year').val();
                $data['cardExpiryYear'] = $year.slice(-2);
                //$data['cardCVC'] = $('#cc-cardCVC').val();
                $data['firstname'] = $('#cc-firstnameoncard').val();
                $data['lastname'] = $('#cc-lastnameoncard').val();

                console.log($data);
                CardPayment( $data );
            });

            $("#cc-cardnumber").keydown(function(e) {
                if(e.keyCode === 13){
                    if($(this).val().length > 16) {
                        $("body").addClass("loading");

                        $data = {};
                        $data['_token'] = "{{ csrf_token() }}";
                        $data['swipestring'] = $(this).val();

                        posting = $.post("/POS/SwipeDecode", $data);

                        posting.done(function (data) {
                            $("body").removeClass("loading");
                            switch (data['status']) {
                                case "OK":
                                    $('#cc-cardnumber').val(data['swipe']['track1']['CardNumber']);
                                    $('#cc-cardExpiry-month').val(data['swipe']['track1']['ExpireyMonth']);
                                    $('#cc-firstnameoncard').val(data['swipe']['track1']['FirstName']);
                                    $('#cc-lastnameoncard').val(data['swipe']['track1']['LastName']);
                                    $('#cc-cardExpiry-year').val("20" + data['swipe']['track1']['ExpireyYear']);

                                    $('#cc-cardCVC').focus();
                                    break;
                                default:
                                    console.log(data);
                                    $.dialog({
                                        title: 'Oops...',
                                        content: 'Unknown Response from server. Please refresh the page and try again.'
                                    });
                            }
                        });

                        posting.fail(function () {
                            NoReplyFromServer();
                        });
                    }
                }
            });

            $('#PayByCardSwipe').on('shown.bs.modal', function (event) {
                $('#card-swipe').val("");
                $('#card-swipe').focus();
            });

            $('#PayByCardSwipe').on('shown.bs.hide', function (event) {
                $('#card-swipe').val("");
            });

            $("#card-swipe").blur(function() {
                $('#PayByCardSwipe').modal('hide');
            });

            $("#card-swipe").keydown(function(e) {

                if(e.keyCode === 13){
                    if($(this).val().length > 16) {
                        $data = {};
                        $data['mode'] = "swipe";
                        $data['_token'] = "{{ csrf_token() }}";
                        $data['clientid'] = "{{ $client->id }}";
                        $data['method'] = "Card";
                        $data['depositcomments'] = "";
                        $data['quotecomments'] = "";
                        $data['swipestring'] = $(this).val();
                        $data['table'] = TableData();
                        $data['amount'] = $('#pos-total-total').val();
                        $data['salesperson'] = $('#salesperson').val();
                        CardPayment($data);
                    }
                }
            });

            TableMath();

            $('.runmath').change(function () {
                TableMath();
            });

            $('.numonly').on('keypress', function(e) {
                keys = ['0','1','2','3','4','5','6','7','8','9','.'];
                return keys.indexOf(event.key) > -1;
            });

            $('.delete').click(function () {
                $row = $(this).parent().parent();
                $row.remove();

                TableMath();
            });

            $('#save-check').click(function () {

                $('#PayByCheck').modal('hide');
                $.confirm({
                    title: 'Is everything correct?',
                    content: '',
                    buttons: {
                        "Go Back": function () {
                            $('#PayByCheck').modal('show');
                        },
                        "Continue Checkout":{
                            btnClass: 'btn-blue',
                            action: function() {
                                $data = {};
                                $data['_token'] = "{{ csrf_token() }}";
                                $data['clientid'] = "{{ $client->id }}";
                                $data['method'] = "Check";
                                $data['depositcomments'] = "Check Number: " + $('#check-number').val();
                                $data['quotecomments'] = $('#check-comments').val();
                                $data['identifier'] = $('#check-number').val();
                                $data['table'] =  TableData();
                                $data['salesperson'] = $('#salesperson').val();
                                Post($data);
                            }
                        },
                     }
                });

            });

            $('#save-cash').click(function () {

                $('#PayByCash').modal('hide');
                $total = parseFloat($('#pos-total-total').val().substring(1));
                $cash = parseFloat($('#cash-recived').val());
                $changedue = $cash - $total;

                $.confirm({
                    title: 'Is everything correct?',
                    content: '<h1>Change Due: $' + $changedue.toFixed(2) + '</h1>',
                    buttons: {
                        "Go Back": function () {
                            $('#PayByCheck').modal('show');
                        },
                        "Continue Checkout":{
                            btnClass: 'btn-blue',
                            action: function() {
                                $data = {};
                                $data['_token'] = "{{ csrf_token() }}";
                                $data['clientid'] = "{{ $client->id }}";
                                $data['method'] = "Cash";
                                $data['depositcomments'] = "Cash Payment Taken by {{ Auth::user()->name }}";
                                $data['quotecomments'] = $('#cash-comments').val();
                                $data['table'] =  TableData();

                                $data['salesperson'] = $('#salesperson').val();
                                Post($data);
                            }
                        },
                    }
                });
            });
        });

        function Post($data){
            $("body").addClass("loading");
            $post = $.post("/POS/Save", $data);

            $post.done(function (data) {
                $("body").removeClass("loading");
                switch(data['status']) {
                    case "OK":

                        $.confirm({
                            title: 'Print/Email a receipt?',
                            content: '',
                            buttons: {
                                "No": {
                                    btnClass: 'btn-blue',
                                    action: function () {
                                        GoToPage('/Clients/View/{{ $client->id }}/transactions');
                                    }
                                },
                                "Yes": {
                                    btnClass: 'btn-blue',
                                    action: function () {
                                        GoToPage('/Clients/Invoice/PDFPage/' + data['invoiceid']);
                                    }
                                },
                            }
                        });

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
                            content: 'Unknown Response from server.' + data['status']
                        });
                }
            });

            $post.fail(function () {
                NoReplyFromServer();
            });
        }

        function CardPayment($data){
            $("body").addClass("loading");
            $post = $.post("/POS/Swipe", $data);

            $post.done(function (data) {
                $("body").removeClass("loading");
                console.log(data);
                switch(data['status']) {
                    case "OK":
                        if(data['TNresponse'] === "1"){
                            $.confirm({
                                title: 'Print a receipt?',
                                content: '',
                                buttons: {
                                    "No":{
                                        btnClass: 'btn-blue',
                                        action: function() {
                                            GoToPage('/Clients/View/{{ $client->id }}/transactions');
                                        }
                                    },
                                    "Yes":{
                                        btnClass: 'btn-blue',
                                        action: function() {
                                            GoToPage('/Clients/Invoice/PDFPage/' + data['invoiceid']);
                                        }
                                    },
                                }
                            });
                        }else{
                            $.dialog({
                                title: 'Oops...',
                                content: 'responcecode: ' + data['TNresponse'] + ' responcetext: ' + data['TNresponsetext'],
                            });
                        }
                        break;
                    case "notfound":
                        $.dialog({
                            title: 'Oops...',
                            content: 'Unknown Response from server. Please refresh the page and try again.'
                        });
                        break;
                    case "cardinvalid":
                        $.dialog({
                            title: 'Oops...',
                            content: 'Card Details invalid. Reason: ' + data['reason'],
                        });
                        break;
                    case "notninfo":
                        $.dialog({
                            title: 'Oops...',
                            content: 'TN not set up, Please setup Transnational Login Info in ACP.'
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

            $post.fail(function () {
                NoReplyFromServer();
            });
        }

        function TableData(){

            $table = [];
            $('#pos-table > tbody  > tr').each(function() {
                $row = {};
                $row['productid'] = $(this).data('productid');
                //$row['itemid'] = $(this).data('itemid');
                $row['sku'] = $(this).find('.sku').val();
                $row['description'] = $(this).find('.description').val();
                $row['units'] = $(this).find('.units').val();
                $row['unitcost'] = $(this).find('.unitcost').val();
                $row['tax'] = $(this).find('.taxpercent').val();
                $row['citytax'] = $(this).find('.citytaxpercent').val();

                $table.push($row);
            });

            return $table;
        }

        function AdjustStock()
        {

        }

        function TableMath(){

            //subtotal
            $tabeltotal = 0;
            $('#pos-table > tbody  > tr').each(function() {
                $units = parseFloat($(this).find('.units').val());
                $unitcost = parseFloat($(this).find('.unitcost').val());
                $taxpercent = parseFloat($(this).find('.taxpercent').val());

                $citytaxpercent = parseFloat($(this).find('.citytaxpercent').val());

                $rowcost = $units * $unitcost;

                $tax = ($rowcost / 100) * $taxpercent;

                $citytax = ($rowcost / 100) * $citytaxpercent;

                $total = $rowcost + $tax + $citytax;

                $(this).find('.taxamount').val($tax.toFixed(2));
                $(this).find('.citytaxamount').val($citytax.toFixed(2));
                $(this).find('.total').val($total.toFixed(2));

                $tabeltotal = $tabeltotal + $total;
            });

            $('#pos-total-total').val("$" + $tabeltotal.toFixed(2));

            /**
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

             **/
        }
    </script>

@stop