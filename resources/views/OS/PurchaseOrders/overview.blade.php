@extends('master')
@section('content')

    <h3 style="margin-top: 10px;">Purchase Orders</h3>

    <div class="row">
        <div class="col-md-3">
            <button style="width: 100%;" class="btn OS-Button btn" type="button" data-toggle="modal"  data-target="#doc-email-modal">
                Email Selected PO
            </button>
        </div>

        <div class="col-md-3">
            <button style="width: 100%;" class="btn OS-Button btn" type="button" id="UpdateStatus" disabled="">
                Mark as Received
            </button>
        </div>

        <div class="col-md-3">
            <button style="width: 100%;" class="btn OS-Button btn" type="button" id="CancelPO">
                Cancel Selected PO
            </button>
        </div>
    </div>

    <div class="row" style="margin-top: 10px;">
        <div class="col-md-3">
            <div class="input-group ">
                <span class="input-group-addon" for="purchaseorders-search"><div style="width: 7em;">Search:</div></span>
                <input id="purchaseorders-search" name="purchaseorders-search" type="text" placeholder="" value=""
                       class="form-control">
            </div>
        </div>

        <div class="col-md-3">
            <div class="input-group ">
                <span class="input-group-addon" for="purchaseorders-vendor"><div style="width: 7em;">Vendor:</div></span>
                <select id="purchaseorders-vendor" name="purchaseorders-vendor" class="form-control">
                    <option value="all" selected>All</option>
                    @foreach($vendors as $vendor)
                    <option value="{{ $vendor->getName() }}">{{ $vendor->getName() }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-3">
            <div class="input-group ">
                <span class="input-group-addon" for="purchaseorders-status"><div style="width: 7em;">Status:</div></span>
                <select id="purchaseorders-status" name="purchaseorders-status" class="form-control">
                    <option value="allopen" selected>All Open</option>
                    <option value="all">All</option>
                    <option value="Created">Created</option>
                    <option value="Ordered">Ordered</option>
                    <option value="Received">Received</option>
                    <option value="Canceled">Canceled</option>
                </select>
            </div>
        </div>

        <div class="col-md-3">
            <div class="input-group ">
                <span class="input-group-addon" for="purchaseorders-length"><div style="width: 7em;">Show:</div></span>
                <select id="purchaseorders-length" name="purchaseorders-length" type="text" placeholder="choice"
                        class="form-control">
                    <option value="10">10 entries</option>
                    <option value="25">25 entries</option>
                    <option value="50">50 entries</option>
                    <option value="100">100 entries</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row" style="margin-top: 10px;">
        <div class="col-md-12">
            {!! PageElement::TableControl('purchaseorders') !!}
        </div>
    </div>

    <table id="purchaseorders-table" class="table">
        <thead>
            <tr>
                <th>id</th>
                <th>PO#</th>
                <th>Date Created</th>
                <th>Total Value</th>
                <th>Vendor</th>
                <th>Status</th>
                <th>emails</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>id</th>
                <th>PO#</th>
                <th>Date Created</th>
                <th>Total Value</th>
                <th>Vendor</th>
                <th>Status</th>
                <th>emails</th>
            </tr>
        </tfoot>
        <tbody>

        @foreach($PurchaseOrders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->POnumber() }}</td>
                <td>{{ $order->created_at }}</td>
                <td>${{ number_format($order->Total(), 2) }}</td>
                <td>{{ $order->vendor->getName() }}</td>
                <td>{{ $order->getStatus() }}</td>
                <td>{{ $order->vendor->EmailJson() }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @include('OS.PurchaseOrders.modals.overviewemail')

    <script>
        $(document).ready(function () {

            // DataTable
            window.purchaseorderstable = $('#purchaseorders-table').DataTable({
                "columnDefs": [
                    {"targets": [0,6], "visible": false}
                ],
            });

            $('#purchaseorders-table tbody').on('click', 'tr', function () {
                $row = $(this);
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                }
                else {
                    window.purchaseorderstable.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }

                $row = window.purchaseorderstable.row('.selected').data();
                if($row[5] === "Created" || $row[5] === "Ordered" || $row[5] === "Back Ordered"){
                    $('#UpdateStatus').prop('disabled', false);
                }else{
                    $('#UpdateStatus').prop('disabled', true);
                }

            });

            $("#purchaseorders-previous-page").click(function () {
                window.purchaseorderstable.page("previous").draw('page');
                PageinateUpdate(window.purchaseorderstable.page.info(), $('#purchaseorders-next-page'), $('#purchaseorders-previous-page'), $('#purchaseorders-tableInfo'));
            });

            $("#purchaseorders-next-page").click(function () {
                window.purchaseorderstable.page("next").draw('page');
                PageinateUpdate(window.purchaseorderstable.page.info(), $('#purchaseorders-next-page'), $('#purchaseorders-previous-page'), $('#purchaseorders-tableInfo'));
            });

            $('#purchaseorders-search').on('keyup change', function () {
                window.purchaseorderstable.search(this.value).draw();
                PageinateUpdate(window.purchaseorderstable.page.info(), $('#purchaseorders-next-page'), $('#purchaseorders-previous-page'), $('#purchaseorders-tableInfo'));
            });

            $('#purchaseorders-length').on('change', function () {
                window.purchaseorderstable.page.len(this.value).draw();
                PageinateUpdate(window.purchaseorderstable.page.info(), $('#purchaseorders-next-page'), $('#purchaseorders-previous-page'), $('#purchaseorders-tableInfo'));
            });

            $('#purchaseorders-vendor').on('change', function () {

                if (this.value === "all") {
                    window.purchaseorderstable
                        .columns(4)
                        .search("", true)
                        .draw();
                } else {

                    window.purchaseorderstable
                        .columns(4)
                        .search(this.value, true)
                        .draw();

                }

                PageinateUpdate(window.purchaseorderstable.page.info(), $('#purchaseorders-next-page'), $('#purchaseorders-previous-page'), $('#purchaseorders-tableInfo'));

            });

            $('#purchaseorders-vendor').change();

            $('#purchaseorders-status').on('change', function () {

                if (this.value === "all") {
                    window.purchaseorderstable
                        .columns(5)
                        .search("", true)
                        .draw();
                } else if(this.value === "allopen"){
                    window.purchaseorderstable
                        .columns(5)
                        .search("Created|Ordered", true)
                        .draw();
                }else{

                    window.purchaseorderstable
                        .columns(5)
                        .search(this.value, true)
                        .draw();

                }

                PageinateUpdate(window.purchaseorderstable.page.info(), $('#purchaseorders-next-page'), $('#purchaseorders-previous-page'), $('#purchaseorders-tableInfo'));

            });

            $('#purchaseorders-status').change();

            PageinateUpdate(window.purchaseorderstable.page.info(), $('#purchaseorders-next-page'), $('#purchaseorders-previous-page'), $('#purchaseorders-tableInfo'));

            $(".dataTables_filter").css('display', 'none');
            $(".dataTables_length").css('display', 'none');
            $(".dataTables_paginate").css('display', 'none');
            $(".dataTables_info").css('display', 'none');
            $("#window.purchaseorderstable").css("width", "100%");

            $('#UpdateStatus').click(function () {
                $row = window.purchaseorderstable.row('.selected').data();
                if($row != undefined){
                    GoToPage('/PurchaseOrders/Status/' + $row[0]);
                }else{
                    $.dialog({
                        title: 'Opps...',
                        content: 'Nothing Selected...'
                    });
                }
            });

            $('#CancelPO').click(function () {

                $row = window.purchaseorderstable.row('.selected').data();
                if($row != undefined){
                    $.confirm({
                        title: 'Confirm',
                        content: 'Are you sure you want to cancel this Order?',
                        buttons: {
                            confirm: function () {
                                UpdateStatus($row[0], 5);
                            },
                            cancel: function () {

                            }
                        }
                    });
                }else{
                    $.dialog({
                        title: 'Opps...',
                        content: 'Nothing Selected...'
                    });
                }

            });

        });


        function UpdateStatus($id, $status) {

            $data = {};
            $data['_token'] = "{{ csrf_token() }}";
            $data['id'] = $id;
            $data['status'] = $status;

            $("body").addClass("loading");
            $post = $.post("/PurchaseOrders/UpdateStatus", $data);

            $post.done(function (data) {
                debugger;
                $("body").removeClass("loading");
                switch(data['status']) {
                    case "OK":

                        $row = window.purchaseorderstable.row('.selected').data();
                        $row[5] = data['newstatus'];
                        window.purchaseorderstable.row('.selected').data($row).draw( false );

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
