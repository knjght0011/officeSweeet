<style>
.invoice-status{
text-transform: uppercase;
font-weight: bold;
}

.paid {
color: #779500;
}
.overdue {
color: red;
}
.void {
color: red;
}
.due {
color: #779500;
}
</style>

<div class="row">
    <div class="col-md-4" id="open-invoice-amount">
        <h2>Current Balance: ${{ $client->getBalence(true) }}</h2>
    </div>
</div>
<div class="row" style="margin-top: 15px;">
    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="search"><div style="width: 7em;">Search:</div></span>
            <input id="invoice-search" name="invoice-search" type="text" placeholder="" value="" class="form-control">
        </div>
    </div>
    <div class="col-md-6">
        {!! PageElement::TableControl('invoice') !!}
    </div>

    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="invoice-status"><div style="width: 7em;">Status:</div></span>
            <select id="invoice-status" name="invoice-status" type="text" placeholder="choice" class="form-control">
                <option value="outstanding">Outstanding</option>
                <option value="paid">Paid</option>
                <option value="void">Void</option>
                <option value="all" selected>All</option>
            </select>
        </div>
    </div>
</div>

<table id="invoicesearch" class="table">
    <thead>
        <tr>
            <th>Invoice Number</th>
            <th>Date Created</th>
            <th>Total Charge</th>
            <th>Payment Applied</th>
            <th>Balance</th>
            <th>Status</th>
            <th>Created By</th>
            <th></th>
        </tr>
    </thead>
    <tfoot style="visibility: hidden;">
        <tr>
            <th>Invoice Number</th>
            <th>Date Created</th>
            <th>Total Charge</th>
            <th>Payment Applied</th>
            <th>Balance</th>
            <th>Status</th>
            <th>Created By</th>
            <th></th>
        </tr>
    </tfoot>
    <tbody>
        @foreach($client->getInvoices() as $invoice)
            <tr>
                <td>{{ $invoice->getQuoteNumber() }}</td>
                <td>{{ $invoice->formatDate_created_at_iso() }}</td>
                <td>${{ $invoice->getTotal()  }}</td>
                <td>${{ $invoice->getTotalPayments()  }}</td>
                <td>@if($invoice->deleted_at === null)${{ $invoice->getBallence()  }}@else#.##@endif</td>
                <td><div class="invoice-status"><span class="{{ $invoice->getStatus() }}"> {{ $invoice->getStatus() }} </span></div></td>
                <td>{{ $invoice->getUser() }}</td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            Options
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" style="margin-top: 0px;">
                            @if(Auth::user()->hasPermission('deposits_permission'))
                            <li><a href="#" data-toggle="modal" data-target="#AddInvoiceDeposit" data-quoteid="{{ $invoice->id }}" data-clientid="{{ $client->id }}">Add Payment</a></li>
                            @endif
                            <li><a href="#" data-toggle="modal" data-target="#ShowPdfModel" data-title="View Invoice" data-url="/Clients/Invoice/PDF/{{ $invoice->id }}" data-id="{{ $invoice->id }}" data-quotenumber="{{ $invoice->getQuoteNumber() }}" data-mode="pdf">View Invoice</a></li>
                            <li><a href="#" data-toggle="modal" data-target="#ShowPdfModel" data-title="View Invoice" data-url="/Clients/Invoice/View/{{ $invoice->id }}" data-id="{{ $invoice->id }}" data-quotenumber="{{ $invoice->getQuoteNumber() }}" data-mode="details">View Details</a></li>
                            <li><a data-toggle="modal" data-target="#send-client-email-modal" data-mode="button" data-type="Invoice" data-link_id="{{ $invoice->id }}" data-subject="{{ $invoice->getQuoteNumber() }}">Email Invoice</a></li>
                            @if($invoice->getBalenceFloat() != 0)
                                @if($invoice->deleted_at === null)
                                <li><a href="javascript:void(0);" class="invoice-void" onclick="DeleteInvoiceConfirm($(this));" data-invoiceid="{{ $invoice->id }}">Void Invoice</a></li>
                                @endif
                            @endif
                        </ul>
                      </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>




<script>
$(document).ready(function() {
    // DataTable
    var invoicesearch = $('#invoicesearch').DataTable(
        {
            "order": [[ 1, "desc" ]],
            "language": {
                "emptyTable": "No Data"
            }
        }
    );

    $( "#invoice-previous-page" ).click(function() {
        invoicesearch.page( "previous" ).draw('page');
        PageinateUpdate(invoicesearch.page.info(), $('#invoice-next-page'), $('#invoice-previous-page'),$('#invoice-tableInfo'));
    });

    $( "#invoice-next-page" ).click(function() {
        invoicesearch.page( "next" ).draw('page');
        PageinateUpdate(invoicesearch.page.info(), $('#invoice-next-page'), $('#invoice-previous-page'),$('#invoice-tableInfo'));
    });

    $('#invoice-search').on( 'keyup change', function () {
        invoicesearch.search( this.value ).draw();
        PageinateUpdate(invoicesearch.page.info(), $('#invoice-next-page'), $('#invoice-previous-page'),$('#invoice-tableInfo'));
    });

    $('#invoice-status').on( 'keyup change', function () {
        switch(this.value) {
            case "all":
                invoicesearch
                    .columns( 5 )
                    .search( "" , true)
                    .draw();
                break;
            case "outstanding":
                invoicesearch
                    .columns( 5 )
                    .search( "due" , true)
                    .draw();
                break;
            case "paid":
                invoicesearch
                    .columns( 5 )
                    .search( "paid" , true)
                    .draw();
                break;
            case "void":
                invoicesearch
                    .columns( 5 )
                    .search( "void" , true)
                    .draw();
                break;
        }

        PageinateUpdate(invoicesearch.page.info(), $('#invoice-next-page'), $('#invoice-previous-page'),$('#invoice-tableInfo'));
    });
    $('#invoice-status').change();

    PageinateUpdate(invoicesearch.page.info(), $('#invoice-next-page'), $('#invoice-previous-page'),$('#invoice-tableInfo'));

    $( "#invoices" ).children().find(".dataTables_filter").css('display', 'none');
    $( "#invoices" ).children().find(".dataTables_length").css('display', 'none');
    $( "#invoices" ).children().find(".dataTables_paginate").css('display', 'none');
    $( "#invoices" ).children().find(".dataTables_info").css('display', 'none');
    $('#invoicesearch').css('width' , "100%");

});

function DeleteInvoiceConfirm($button){

    $.confirm({
        title: 'Void Invoice!',
        content: 'Are you sure you want to void this invoice? The total on this invoice will be removed from the clients outstanding balance.' +
        '<form action="" class="formName">' +
        '<div class="form-group">' +
        '<label>Reason:</label>' +
        '<input type="text" placeholder="Reason" class="reason form-control" required />' +
        '</div>' +
        '</form>',
        buttons: {
            confirm: function () {

                var $reason = this.$content.find('.reason').val();
                DeleteInvoice($button, $reason);
            },
            cancel: function () {

            }
        }
    });
}

function DeleteInvoice($button, $reason){

    $("body").addClass("loading");

    $data = {};
    $data['_token'] = "{{ csrf_token() }}";
    $data['id'] = $button.data('invoiceid');
    $data['reason'] = $reason;

    $post = $.post("/Clients/Invoice/Delete", $data);

    $post.done(function (data) {
        $("body").removeClass("loading");
        if(data === "done"){
            ChangeStatusToVoid($button);
            UpdateInvoiceAmount();
        }
        if(data === "fail"){
            $.dialog({
                title: 'Oops...',
                content: 'Failed to find quote, Please refresh the page and try again.'
            });
        }
    });

    $post.fail(function () {
        NoReplyFromServer();
    });
}

function ChangeStatusToVoid($button){
    $row = $button.parent().parent().parent().parent().parent();
    $dtrow = $('#invoicesearch').DataTable().row($row).data();
    $dtrow['4'] = "#.##";
    $dtrow['5'] = '<div class="invoice-status"><span class="void"> void </span></div>';
    $('#invoicesearch').DataTable().row($row).data($dtrow);
    $('#invoicesearch').DataTable().draw();

    $row.find('.invoice-void').remove();

}
function UpdateInvoiceAmount(){

    $rows = $('#invoicesearch').DataTable().rows().data();
    total = 0;

    $.each($rows, function(index, value){
        if(value['5'] != '<div class="invoice-status"><span class="void"> void </span></div>'){
            console.log(value['5']);
            $ballence = value[4].substr(1).replace(/,/g, "");
            console.log($ballence);
            total = total + parseFloat($ballence);
        }else{
            console.log(value['5']);
            console.log("NOT");
        }
    });

    $('#open-invoice-amount').html('<h2>Current Balance: $' + total.toFixed(2) +'</h2>');
}
</script>
