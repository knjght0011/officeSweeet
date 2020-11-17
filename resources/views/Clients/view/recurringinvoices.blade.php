
<div class="row" style="margin-top: 15px;">
    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="search"><div style="width: 7em;">Search:</div></span>
            <input id="recurringinvoice-search" name="recurringinvoice-search" type="text" placeholder="" value="" class="form-control">
        </div>
    </div>
    <div class="col-md-6">
        {!! PageElement::TableControl('recurringinvoice') !!}
    </div>
    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="recurringinvoice-status"><div style="width: 7em;">Status:</div></span>
            <select id="recurringinvoice-status" name="recurringinvoice-status" type="text" placeholder="choice" class="form-control">
                <option value="outstanding">Outstanding</option>
                <option value="paid">Paid</option>
                <option value="void">Void</option>
                <option value="all" selected>All</option>
            </select>
        </div>
    </div>
</div>

<table id="recurringinvoicesearch" class="table">
    <thead>
        <tr>
            <th>Invoice Number</th>
            <th>Created By</th>
            <th>Total</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Number of Invoices</th>
            <th></th>

        </tr>
    </thead>
    <tfoot style="visibility: hidden;">
        <tr>
            <th>Invoice Number</th>
            <th>Created By</th>
            <th>Total</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Number of Invoices</th>
            <th></th>

        </tr>
    </tfoot>
    <tbody>
        @foreach($client->getRecurringInvoices() as $invoice)
            <tr>
                <td>{{ $invoice->getQuoteNumber() }}</td>
                <td>{{ $invoice->getUser() }}</td>
                <td>${{ $invoice->getTotal()  }}</td>
                <td>{{ $invoice->recurringmaster->StartIso() }}</td>
                <td>{{ $invoice->recurringmaster->EndIso() }}</td>
                <td>{{ $invoice->recurringmaster->Number() }}</td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            Options
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" style="margin-top: 0px;">
                            @if($invoice->recurringmaster->Canend())
                            <li><a href="#" id="stop-recurring" data-quoteid="{{ $invoice->id }}" data-recurringid="{{ $invoice->is_recurring }}">Stop Billing Invoice</a></li>
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
    $('#stop-recurring').click(function(){
        //DeleterecurringinvoiceConfirm($(this));
        DeleterecurringinvoiceConfirm($(this));
    });


    // DataTable
    var recurringinvoicesearch = $('#recurringinvoicesearch').DataTable(

    );

    $( "#recurringinvoice-previous-page" ).click(function() {
        recurringinvoicesearch.page( "previous" ).draw('page');
        PageinateUpdate(recurringinvoicesearch.page.info(), $('#recurringinvoice-next-page'), $('#recurringinvoice-previous-page'),$('#recurringinvoice-tableInfo'));
    });

    $( "#recurringinvoice-next-page" ).click(function() {
        recurringinvoicesearch.page( "next" ).draw('page');
        PageinateUpdate(recurringinvoicesearch.page.info(), $('#recurringinvoice-next-page'), $('#recurringinvoice-previous-page'),$('#recurringinvoice-tableInfo'));
    });

    $('#recurringinvoice-search').on( 'keyup change', function () {
        recurringinvoicesearch.search( this.value ).draw();
        PageinateUpdate(recurringinvoicesearch.page.info(), $('#recurringinvoice-next-page'), $('#recurringinvoice-previous-page'),$('#recurringinvoice-tableInfo'));
    });

    $('#recurringinvoice-status').change();

    PageinateUpdate(recurringinvoicesearch.page.info(), $('#recurringinvoice-next-page'), $('#recurringinvoice-previous-page'),$('#recurringinvoice-tableInfo'));

    $( "#recurringinvoices" ).children().find(".dataTables_filter").css('display', 'none');
    $( "#recurringinvoices" ).children().find(".dataTables_length").css('display', 'none');
    $( "#recurringinvoices" ).children().find(".dataTables_paginate").css('display', 'none');
    $( "#recurringinvoices" ).children().find(".dataTables_info").css('display', 'none');
    $('#recurringinvoicesearch').css('width' , "100%");

});

function DeleterecurringinvoiceConfirm($button){

    $.confirm({
        title: 'Cancel Recurring Invoice!',
        content: 'Are you sure you want to stop this recurring invoice?',
        buttons: {
            confirm: function () {

                Deleterecurringinvoice($button);
            },
            cancel: function () {

            }
        }
    });
}

function Deleterecurringinvoice($button){

    var data = {};
    data["_token"] = "{{ csrf_token() }}";
    data["id"] = $button.data('recurringid');

    $("body").addClass("loading");
    posting = $.post("/Clients/Invoice/RecurringStop", data);

    posting.done(function( data ) {
        $("body").removeClass("loading");
        if(data === "done"){
            SavedSuccess('Recurring Invoice Canceled.');
            UpdateEnd($button);
        }
        if(data === "fail"){
            $.dialog({
                title: 'Oops...',
                content: 'Unable to complete action, please refresh the page and try again.'
            });
        }
    });

    posting.fail(function() {
        $("body").removeClass("loading");
        bootstrap_alert.warning("Failed to contact server", 'danger', 4000);
    });

}

function UpdateEnd($button){

    $row = $button.parent().parent().parent().parent().parent();
    $dtrow = $('#recurringinvoicesearch').DataTable().row($row).data();
    $dtrow['4'] = moment().format("YYYY-MM-DD");
    $('#recurringinvoicesearch').DataTable().row($row).data($dtrow);
    $('#recurringinvoicesearch').DataTable().draw();
}

</script>
