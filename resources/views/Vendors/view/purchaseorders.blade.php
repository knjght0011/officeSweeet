<div class="row">
    <div class="col-md-4" id="open-purchaseorder-amount">
        <h2>Current Total: ${{ number_format($vendor->PoTotal(), 2) }}</h2>
    </div>
</div>
<div class="row" style="margin-top: 15px;">
    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="search"><div style="width: 7em;">Search:</div></span>
            <input id="purchaseorder-search" name="purchaseorder-search" type="text" placeholder="" value="" class="form-control">
        </div>
    </div>

    <div class="col-md-3">
        <button style="width: 100%;" class="btn OS-Button btn" type="button" id="EditPO" disabled>
            Edit PO
        </button>
    </div>

    <div class="col-md-3">
        <button style="width: 100%;" class="btn OS-Button btn" type="button" data-toggle="modal" data-target="#ShowPdfModel" data-mode="PO" data-title="Purchase Order">
            View PO
        </button>
    </div>

    <div class="col-md-3">
        <button style="width: 100%;" class="btn OS-Button btn" type="button" data-toggle="modal" data-target="#doc-email-modal" data-mode="sendpo" data-title="Purchase Order">
            Email PO
        </button>
    </div>

</div>

<div class="row" style="padding-left: 15px; padding-right: 15px; margin-top: 15px;">
    {!! PageElement::TableControl('purchaseorder') !!}
</div>

<table id="purchaseordersearch" class="table">
    <thead>
        <tr>
            <th>PO#</th>
            <th>Date Created</th>
            <th>Total Value</th>
            <th>Status</th>
            <th>id</th>
        </tr>
    </thead>
    <tbody>
        @foreach($vendor->purchaseorders as $order)
        <tr>
            <td>{{ $order->POnumber() }}</td>
            <td>{{ $order->created_at }}</td>
            <td>${{ number_format($order->Total(), 2) }}</td>
            <td>{{ $order->getStatus() }}</td>
            <td>{{ $order->id }}</td>
        </tr>
        @endforeach
    </tbody>
</table>


<script>
    $(document).ready(function() {

        $('#EditPO').click(function () {
            $row = window.purchaseordersearch.row('.selected').data();
            console.log($row);
            if($row != undefined){
                GoToPage('/PurchaseOrders/Edit/' + $row[4]);
            }
        });

        // DataTable
        window.purchaseordersearch = $('#purchaseordersearch').DataTable({
            "columnDefs": [
                {
                    "targets": [4],
                    "visible": false
                }
            ]
        });

        $( "#purchaseorder-previous-page" ).click(function() {
            window.purchaseordersearch.page( "previous" ).draw('page');
            PageinateUpdate(window.purchaseordersearch.page.info(), $('#purchaseorder-next-page'), $('#purchaseorder-previous-page'),$('#purchaseorder-tableInfo'));
        });

        $( "#purchaseorder-next-page" ).click(function() {
            window.purchaseordersearch.page( "next" ).draw('page');
            PageinateUpdate(window.purchaseordersearch.page.info(), $('#purchaseorder-next-page'), $('#purchaseorder-previous-page'),$('#purchaseorder-tableInfo'));
        });

        $('#purchaseorder-search').on( 'keyup change', function () {
            window.purchaseordersearch.search( this.value ).draw();
            PageinateUpdate(window.purchaseordersearch.page.info(), $('#purchaseorder-next-page'), $('#purchaseorder-previous-page'),$('#purchaseorder-tableInfo'));
        });

        $('#purchaseordersearch tbody').on( 'click', 'tr', function () {
            $row = $(this);
            if ( $(this).hasClass('selected') ) {
                $(this).removeClass('selected');
            }
            else {
                window.purchaseordersearch.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }

            $row = window.purchaseordersearch.row('.selected').data();
            if($row[3] === "Created" || $row[3] === "Ordered"){
                $('#EditPO').prop('disabled', false);
            }else{
                $('#EditPO').prop('disabled', true);
            }
        });

        /**
        $('#purchaseorder-status').on( 'keyup change', function () {
            switch(this.value) {
                case "all":
                    window.purchaseordersearch
                        .columns( 5 )
                        .search( "" , true)
                        .draw();
                    break;
                case "outstanding":
                    window.purchaseordersearch
                        .columns( 5 )
                        .search( "due" , true)
                        .draw();
                    break;
                case "paid":
                    window.purchaseordersearch
                        .columns( 5 )
                        .search( "paid" , true)
                        .draw();
                    break;
                case "void":
                    window.purchaseordersearch
                        .columns( 5 )
                        .search( "void" , true)
                        .draw();
                    break;
            }

            PageinateUpdate(window.purchaseordersearch.page.info(), $('#purchaseorder-next-page'), $('#purchaseorder-previous-page'),$('#purchaseorder-tableInfo'));
        });
        $('#purchaseorder-status').change();
        **/
         
        PageinateUpdate(window.purchaseordersearch.page.info(), $('#purchaseorder-next-page'), $('#purchaseorder-previous-page'),$('#purchaseorder-tableInfo'));

        $( "#purchaseorders" ).children().find(".dataTables_filter").css('display', 'none');
        $( "#purchaseorders" ).children().find(".dataTables_length").css('display', 'none');
        $( "#purchaseorders" ).children().find(".dataTables_paginate").css('display', 'none');
        $( "#purchaseorders" ).children().find(".dataTables_info").css('display', 'none');
        $('#purchaseordersearch').css('width' , "100%");

    });
</script>