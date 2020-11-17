<div class="row">
    <div class="col-md-4" id="open-quotes-amount">
        <h2>Open {{ TextHelper::GetText("Quotes") }}: ${{ $client->getOpenQuoteValue(false) }}</h2>
    </div>
</div>
<div class="row" style="margin-top: 15px;">
    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="search"><div style="width: 7em;">Search:</div></span>
            <input id="quote-search" name="quote-search" type="text" placeholder="" value="" class="form-control">
        </div>
    </div>
    <div class="col-md-6">
        {!! PageElement::TableControl('quote') !!}
    </div>
    <div class="col-md-3">

    </div>
</div>

<table id="quotesearch" class="table">
    <thead>
        <tr>
            <th>{{ TextHelper::GetText("Quotes") }} Number</th>
            <th>Created By</th>
            <th>Total</th>
            <th>Last Edited</th>
            <th></th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>{{ TextHelper::GetText("Quotes") }} Number</th>
            <th>Created By</th>
            <th>Total</th>
            <th>Last Edited</th>
            <th></th>
        </tr>
    </tfoot>
    <tbody>
        @foreach($client->getQuotes() as $quote)
            <tr>
                <td>{{ $quote->getQuoteNumber() }}</td>
                <td>{{ $quote->getUser() }}</td>
                <td>{{ $quote->getTotal()  }}</td>
                <td>{{ $quote->formatDate_updated_at_iso() }}</td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            Options
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" style="margin-top: 0px;">
                            <li><a href="/Quote/Edit/{{ $quote->id }}" >Edit</a></li>
                            <li><a href="javascript:void(0);" id="quote-delete" onclick="DeleteQuoteConfirm($(this));" data-quoteid="{{ $quote->id }}">Delete</a></li>
                            <li><a data-toggle="modal" data-target="#send-client-email-modal" data-mode="button" data-type="Quote" data-link_id="{{ $quote->id }}" data-subject="{{ $quote->getQuoteNumber() }}">Email Quote</a></li>
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
    var quotesearch = $('#quotesearch').DataTable(
        {
        "order": [[ 3, "desc" ]],
        });

    $( "#quote-previous-page" ).click(function() {
        quotesearch.page( "previous" ).draw('page');
        PageinateUpdate(quotesearch.page.info(), $('#quote-next-page'), $('#quote-previous-page'),$('#quote-tableInfo'));
    });

    $( "#quote-next-page" ).click(function() {
        quotesearch.page( "next" ).draw('page');
        PageinateUpdate(quotesearch.page.info(), $('#quote-next-page'), $('#quote-previous-page'),$('#quote-tableInfo'));
    });

    $('#quote-search').on( 'keyup change', function () {
        quotesearch.search( this.value ).draw();
        PageinateUpdate(quotesearch.page.info(), $('#quote-next-page'), $('#quote-previous-page'),$('#quote-tableInfo'));
    });

    PageinateUpdate(quotesearch.page.info(), $('#quote-next-page'), $('#quote-previous-page'),$('#quote-tableInfo'));

    $( "#quotes" ).children().find(".dataTables_filter").css('display', 'none');
    $( "#quotes" ).children().find(".dataTables_length").css('display', 'none');
    $( "#quotes" ).children().find(".dataTables_paginate").css('display', 'none');
    $( "#quotes" ).children().find(".dataTables_info").css('display', 'none');
    $('#quotesearch').css('width' , "100%");

    UpdateAmount();
} );

function DeleteQuoteConfirm($button){

    $.confirm({
        title: 'Delete Quote!',
        content: 'Are you sure you want to delete this quote?',
        buttons: {
            confirm: function () {
                DeleteQuote($button);
            },
            cancel: function () {

            }
        }
    });
}

function DeleteQuote($button){

    $("body").addClass("loading");

    $data = {};
    $data['_token'] = "{{ csrf_token() }}";
    $data['id'] = $button.data('quoteid');

    $post = $.post("/Quote/Delete", $data);

    $post.done(function (data) {
        $("body").removeClass("loading");
        if(data === "done"){
            DeleteRow($button);
            UpdateAmount();
        }
        if(data === "fail"){
            $.dialog({
                title: 'Oops...',
                content: 'Failed to find quote, Please refresh the page and try again.'
            });
        }

        //SavedSuccess();
    });

    $post.fail(function () {
        NoReplyFromServer();
    });
}

function DeleteRow($button){
    $row = $button.parent().parent().parent().parent().parent();
    $dtrow = $('#quotesearch').DataTable().row($row);
    $dtrow.remove().draw();
}
function UpdateAmount(){

    $rows = $('#quotesearch').DataTable().rows().data();
    total = 0;
    $.each($rows, function(index, value){
        total = total + parseFloat(value[2]);
    });
    $('#open-quotes-amount').html('<h2>Open Quotes: $' + total.toFixed(2) +'</h2>');
}
</script>