@extends('master')

@section('content')  



<div class="row" style="margin-top: 20px; margin-bottom: 20px;">
    <div style="float:left; width: 20em;  margin-left: 20px;">

        <button style="width: 100%;  margin-top: 5px;" type="button" class="btn OS-Button" data-toggle="modal" data-target="#viewDetails">
        View Details
        </button>
        <button id="finalise" style="width: 100%; margin-top: 5px;" type="button" class="btn OS-Button">
        Finalize balance for {{ $month }}
        </button>
    </div>
    <div style="float:left; width: 25em;  margin-left: 20px;">
        <table class="table col-md-3">
            <tbody>
                <tr>
                    <td class="col-md-1">
                        <b>Beginning Balance</b>
                    </td>
                    <td class="col-md-1">
                        ${{ number_format($beginningbalance , 2) }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Deposits/Credits</b>
                    </td>
                    <td>
                        ${{ number_format($credits , 2) }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Withdrawals/Debits</b>
                    </td>
                    <td>
                        ${{ number_format($debits , 2) }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Ending Balance</b>
                    </td>
                    <td>
                        ${{ number_format($endingbalance , 2) }}
                    </td>
                </tr>
            </tbody>   
        </table>
    </div>
    <div style="width: 100%; margin-top: 5px; padding-right: 60px;">
        @if(isset($warning))
            <p>IMPORTANT! In order to use the journal for the first time, you have to create a starting point by completing a month end. Determine what your last month end balance was, then add it to the journal by either creating a miscellaneous deposit OR a miscellaneous expense (in the case of a negative balance).</p>
            <p>Simply click the red + at the bottom-center of the page and select Add Misc Deposit and place your ending balance for last month and DATE IT FOR LAST MONTH.  Then write any details in the comments section.</p>
            <p>Next, click the Finalize Balance button in the top left.  Your beginning balance will show for the current month (which will match your bank statement) and you will be ready to start fresh.</p>
        @endif
    </div>
</div>
<table id="journal-table" class="table">
    <thead>
    <tr id="head">
        <th></th>
        <th class="col-md-1">Date</th>
        <th class="col-md-2">Type</th>
        <th>To/From</th>
        <th class="col-md-1">Income/Credit</th>
        <th class="col-md-1">Expense/Debit</th>
        <th class="col-md-1">Attachment</th>
        <th>ID</th>
        <th>Comment</th>
        <th>Type-Data</th>
        <th>Amount-Data</th>
        <th>Expense-Data</th>
    </tr>
    </thead>
    <tfoot style="visibility: hidden;">
    <tr>
        <th></th>
        <th>Date</th>
        <th>Type</th>
        <th>To/From</th>
        <th>Income/Credit</th>
        <th>Expense/Debit</th>
        <th class="col-md-1">Attachment</th>
        <th>ID</th>
        <th>Comment</th>
        <th>Type-Data</th>
        <th>Amount-Data</th>
        <th>Expense-Data</th>
    </tr>
    </tfoot>
    <tbody>
    @foreach($equitys as $equity)
        <tr>
            <td>

            </td>
            <td>
                {{ $equity->dateforinput() }}
            </td>
            <td>
                Equity
            </td>
            <td>
                {{ $equity->name }}
            </td>
            <td>
                {{ $equity->formatedAmount() }}
            </td>

            <td>

            </td>
            <td style="padding-top: 3px; padding-bottom: 3px;">

            </td>
            <td>
                {{ $equity->id }}
            </td>
            <td>
                {{ $equity->comments }}
            </td>
            <td>
                equity
            </td>
            <td>
                {{ $equity->formatedAmount() }}
            </td>
            <td>
                @foreach($equity->catagorys as $key => $value){{ $key }}:{{$value}},@endforeach
            </td>
        </tr>
    @endforeach
    @foreach($deposits as $deposit)
        <tr>
            <td>

            </td>
            <td>
                {{ $deposit->dateforinput() }}
            </td>
            <td>
                Deposit
            </td>

            <td>
                {{ $deposit->getFrom() }} {{ $deposit->getInvoiceNumbers() }}
            </td>

            <td>
                {{ $deposit->formatedAmount() }}
            </td>

            <td>

            </td>
            <td style="padding-top: 3px; padding-bottom: 3px;">
                @if($deposit->HasAttachment())
                    <button style="width: 100%; padding-top: 2px; padding-bottom: 2px;" type="button" class="btn OS-Button" data-toggle="modal" data-target="#filestore-display-model" data-fileid="{{ $deposit->filestore->id }}">Show Attachment</button>
                @endif
            </td>
            <td>
                {{ $deposit->id }}
            </td>
            <td>
                {{ $deposit->comments }}
            </td>
            <td>
                deposit
            </td>
            <td>
                {{ $deposit->formatedAmount() }}
            </td>
            <td>

            </td>
        </tr>
    @endforeach
    @foreach($checks as $check)
        <tr>
            <td>

            </td>
            <td>
                {{ $check->dateforinput() }}
            </td>
            <td>
                Check Number:{{ $check->checknumber }}
            </td>
            <td>
                {{ $check->payto }}
            </td>
            <td>

            </td>
            <td>
                {{ $check->formatedAmount() }}
            </td>
            <td style="padding-top: 3px; padding-bottom: 3px;">
                @if($check->filestore_id != null)
                    <button style="width: 100%; padding-top: 2px; padding-bottom: 2px;" type="button" class="btn OS-Button" data-toggle="modal" data-target="#filestore-display-model" data-fileid="{{ $check->filestore_id }}">Show Attachment</button>
                @endif
            </td>
            <td>
                {{ $check->id }}
            </td>
            <td>
                {{ $check->comments }}
            </td>
            <td>
                check
            </td>
            <td>
                {{ $check->formatedAmount() }}
            </td>
            <td>
                @foreach($check->catagorys as $key => $value){{ $key }}:{{$value}},@endforeach
            </td>
        </tr>
    @endforeach
    @foreach($receipts as $receipt)
        <tr>
            <td>

            </td>
            <td>
                {{ $receipt->DateString() }}
            </td>
            <td>
                Expense
            </td>
            <td>
                {{ $receipt->LinkedAccountName() }}
            </td>
            <td>

            </td>
            <td>
                {{ $receipt->formatedAmount() }}
            </td>
            <td>
                @if($receipt->HasAttachment())
                    <button style="width: 100%;" type="button" class="btn OS-Button" data-toggle="modal" data-target="#ShowPdfModel" data-id="{{ $receipt->id }}">Show Attachment</button>
                @endif
            </td>
            <td>
                {{ $receipt->id }}
            </td>
            <td>
                {{ $receipt->description }}
            </td>
            <td>
                receipt
            </td>
            <td>
                {{ $receipt->formatedAmount() }}
            </td>
            <td>
                @foreach($receipt->catagorys as $key => $value){{ $key }}:{{$value}},@endforeach
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<script>   
$(document).ready(function(){
    
    @if(isset($warning))
        $.dialog({
            title: 'IMPORTANT!',
            columnClass: 'col-md-8 col-md-offset-2',
            content: 'In order to use the journal for the first time, you have to create a starting point by completing a month end. Determine what your last month end balance was, then add it to the journal by either creating a miscellaneous deposit OR a miscellaneous expense (in the case of a negative balance). <br><br> Simply click the red + at the bottom-center of the page and select Add Misc Deposit and place your ending balance for last month and DATE IT FOR LAST MONTH.  Then write any details in the comments section. <br><br> Next, click the Finalize Balance button in the top left.  Your beginning balance will show for the current month (which will match your bank statement) and you will be ready to start fresh.'
        });
    @endif

    $('#finalise').click(function(e) {

        $.confirm({
            title: 'WARNING!',
            content: 'This will become your begining balance for {{ $month }}, are you sure you wish to proceed?',
            buttons: {
                confirm: function () {

                    $("body").addClass("loading");
                    var get = $.get( "/Journal/MonthEnd/ConfirmRecent", function(  ) { });

                    get.done(function( data ) {
                        console.log(data);

                        switch(data) {
                            case "error":
                                $("body").removeClass("loading");
                                $.dialog({
                                    title: 'Error!',
                                    content: 'Unable to create initial month end, Initial month allready created or other error please contact support if this persits',
                                });
                                break;
                            case "done":
                                GoToPage('/Journal/MonthEnd/Next');
                                break;
                            case "noneleft":
                                GoToPage('/Journal/View');
                                break;
                            default:
                                $("body").removeClass("loading");
                                $.dialog({
                                    title: 'Error!',
                                    content: 'Unknown responce',
                                });
                        }

                    });
                },
                cancel: function () {
                    
                }
            }
        });
    }); 
    
    $('#update').click(function(e) {
        $month = $('#month').val();
        $year = $('#year').val();
        
        var link = document.createElement('a');
        link.href = "/Journal/View/" + $month + "/" + $year;
        link.id = "link";
        document.body.appendChild(link);
        link.click();
    });
 
    // DataTable
    var table = $('#journal-table').DataTable({
        "pageLength": 50,
        "columnDefs": [
            { "targets": [0,7,8,9,10,11],"visible": false}
        ],
        "order": [[ 1, "desc" ]]
      });

    
    $('#journal-table tbody').on( 'click', 'tr', function () {
        $row = $(this);
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });
});
</script>

@include('Journal.Modals.DetailsModal')
@include('OS.FileStore.displayfile')
@stop
