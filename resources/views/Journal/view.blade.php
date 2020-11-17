@extends('master')

@section('content')  



<div class="row">
    <div class="col-md-3">
        <div class="input-group">   
            <span class="input-group-addon" for="month"><div style="width: 5em;">Month:</div></span>
            <select id="month" name="month" class="form-control">
                <option value="01">January</option>
                <option value="02">February</option>
                <option value="03">March</option>
                <option value="04">April</option>
                <option value="05">May</option>
                <option value="06">June</option>
                <option value="07">July</option>
                <option value="08">August</option>
                <option value="09">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
            </select>
        </div>
        <div class="input-group">   
            <span class="input-group-addon" for="year"><div style="width: 5em;">Year:</div></span>
            <select id="year" name="year" class="form-control">
                <option value="2016">2016</option>
                <option value="2017">2017</option>
                <option value="2018">2018</option>
                <option value="2019">2019</option>
                <option value="2020">2020</option>
            </select>
        </div>
        <button style="width: 100%;" id="update" name="update" type="button" class="btn OS-Button">
        Update
        </button>
        <button style="width: 100%;" type="button" class="btn OS-Button" data-toggle="modal" data-target="#viewDetails">
        View Details
        </button>
    </div>
    <div class="col-md-3">
        <table class="table col-md-3">
            <tbody>
                <tr>
                    <td class="col-md-1">
                        <b>Beginning Balance</b>
                    </td>
                    <td class="col-md-1">
                        ${{ $beginningbalance }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Deposits/Credits</b>
                    </td>
                    <td id="dynamic-credits">
                        ${{ number_format($credits , 2, '.', '') }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Withdrawals/Debits</b>
                    </td>
                    <td id="dynamic-debits">
                        ${{ number_format($debits , 2, '.', '') }}
                    </td>
                </tr>
                <tr>
                    <td>
                       <b>Ending Balance</b>
                    </td>
                    <td id="dynamic-ballence">
                        ${{ number_format($endingbalance , 2, '.', '') }}
                    </td>
                </tr>
            </tbody>   
        </table>
    </div>
    <div class="col-md-6">
        <div class="input-group ">
            <span class="input-group-addon" for="search"><div style="width: 7em;">Search:</div></span>
            <input id="search" name="search" type="text" placeholder="" value="" class="form-control" data-validation-label="Search" data-validation-required="false" data-validation-type="">
        </div>
        <div class="input-group ">
            <span class="input-group-addon" for="length"><div style="width: 7em;">Show:</div></span>
            <select id="length" name="length" type="text" placeholder="choice" class="form-control">
                <option value="10">10 entries</option>
                <option value="15" selected>15 entries</option>
                <option value="20">20 entries</option>
                <option value="25">25 entries</option>
                <option value="30">30 entries</option>
                <option value="35">35 entries</option>
                <option value="40">40 entries</option>
                <option value="45">45 entries</option>
                <option value="50">50 entries</option>
                <option value="100">100 entries</option>
            </select>
        </div>
        <div class="input-group">
            <span class="input-group-addon" for="choice"><div style="width: 7em;">Entry Type:</div></span>
            <select id="choice" name="choice" type="text" placeholder="choice" class="form-control">
                <option value="all">All</option>
                <option value="deposit">Deposit ({{ count($deposits) }})</option>
                <option value="check">Check ({{ count($checks) }})</option>
                <option value="receipt">Expense ({{ count($receipts) }})</option>
            </select>
        </div>
        <div style="width: 100%;">
            <button class="OS-Button btn" data-toggle="modal" data-target="#AddMiscDepositModel" style="width: 48%; float: left;">Add Misc Deposit</button>
            <button class="OS-Button btn" onclick="GoToPage('/Reciepts/New/0')" style="width: 48%; float: right;">Add Receipt/Expense</button>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-md-1">
        <button id="previous-page" name="previous-page" type="button" class="btn OS-Button" style="width: 100%;">Previous</button>
    </div>
    <div class="col-md-10" id="tableInfo" style="text-align: center;">

    </div>
    <div class="col-md-1">
        <button id="next-page" name="next-page" type="button" class="btn OS-Button" style="width: 100%;">Next</button>
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
   <tfoot>
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
       @foreach($deposits as $deposit)
       <tr>
           <td style="padding: 0px; height: 30px; width: 30px;">
               <input style="height: 80%; width: 80%;" type="checkbox" class="total-check" data-toggle="tooltip" data-placement="right" data-credit="{{ $deposit->amount }}" data-debit="0" title="Uncheck to remove from balance, e.g. checks that haven't cleared" checked>
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
               @foreach($deposit->catagorys as $key => $value){{ $key }}:{{$value}},@endforeach
           </td>
       </tr>
       @endforeach
       @foreach($equitys as $equity)
           <tr>
               <td style="padding: 0px; height: 30px; width: 30px;">
                   <input style="height: 80%; width: 80%;" type="checkbox" class="total-check" data-toggle="tooltip" data-placement="right" data-credit="{{ $equity->amount }}" data-debit="0" title="Uncheck to remove from balance, e.g. checks that haven't cleared" checked>
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
       @foreach($checks as $check)
        <tr>
            <td style="padding: 0px; height: 30px; width: 30px;">
                <input style="height: 80%; width: 80%;" type="checkbox" class="total-check" data-toggle="tooltip" data-placement="right" data-credit="0" data-debit="{{ $check->amount }}" title="Uncheck to remove from balance, e.g. checks that haven't cleared" checked>
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
           <td style="padding: 0px; height: 30px; width: 30px;">
               <input style="height: 80%; width: 80%;" type="checkbox" class="total-check" data-toggle="tooltip" data-placement="right" data-credit="0" data-debit="{{ $receipt->amount }}" title="Uncheck to remove from balance, e.g. checks that haven't cleared" checked>
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
            <td style="padding-top: 3px; padding-bottom: 3px;">
                @if($receipt->HasAttachment())
                <button style="width: 100%; padding-top: 2px; padding-bottom: 2px;" type="button" class="btn OS-Button" data-toggle="modal" data-target="#filestore-display-model" data-fileid="{{ $receipt->filestore->id }}">Show Attachment</button>
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

{{--
<div class="modal fade" id="ShowPdfModel" tabindex="-1" role="dialog" aria-labelledby="ShowPdfModel" aria-hidden="true">
    <div style="margin: 2.5vh auto; width: 95vw" class="modal-dialog" role="document">
        <div style="height: 95vh; width: 95vw;" class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="ShowPdfModel">View Attachment</h4>
            </div>
            <div style="height: calc(95vh - 120px);" class="modal-body">
                <iframe style="width: 100%; height: 100%;"id="ShowPdfFrame" src="{{ url('images/loading4.gif') }}"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
--}}

<script>   
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip()
    {{--
    $('#ShowPdfModel').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var id = button.data('id');

        var url = "/Reciepts/Attachment/"; // Extract info from data-* attributes

        $('#ShowPdfFrame').attr("src", url + id);  
    }); 
    
    $('#ShowPdfModel').on('hide.bs.modal', function (event) {
        $('#ShowPdfFrame').attr("src", "{{ url('images/loading4.gif') }}");
    });
    --}}

    $('.total-check').click(function(){
        UpdateTotals();
    });
    
    if("{{ $beginningbalance }}" === "Unknown"){
        $.confirm({
            title: 'No end of month:',
            content: 'No end of month: No end-of-month was completed last month. Would you like to head to the end of month summary to do this NOW?',
            buttons: {
                'yes' : function () {
                    GoToPage("/Journal/MonthEnd/Summery");
                },
                'NOT NOW': function () {

                }
            }
        });
    }
    
    $("#year").val("{{ $year }}");
    $("#month").val("{{ $month }}");

    
    $('#update').click(function(e) {
        $month = $('#month').val();
        $year = $('#year').val();
        
        GoToPage("/Journal/View/" + $month + "/" + $year);
    });   

    // DataTable
    var table = $('#journal-table').DataTable({
        "pageLength": 15,
        "columnDefs": [
            { "targets": [7,8,9,10,11],"visible": false}
        ],
        "order": [[ 1, "desc" ]]
      });

    $( "#previous-page" ).click(function() {
        table.page( "previous" ).draw('page');
        PageinateUpdate(table.page.info(), $('#next-page'), $('#previous-page'),$('#tableInfo'));
    });

    $( "#next-page" ).click(function() {
        table.page( "next" ).draw('page');
        PageinateUpdate(table.page.info(), $('#next-page'), $('#previous-page'),$('#tableInfo'));
    });

    $('#search').on( 'keyup change', function () {
        table.search( this.value ).draw();
        PageinateUpdate(table.page.info(), $('#next-page'), $('#previous-page'),$('#tableInfo'));
    });

    $('#length').on( 'change', function () {
        table.page.len( this.value ).draw();
        PageinateUpdate(table.page.info(), $('#next-page'), $('#previous-page'),$('#tableInfo'));
    });

    $('#choice').on( 'keyup change', function () {

        switch(this.value) {
            case "all":
                table
                    .columns( 9 )
                    .search( "" , true)
                    .draw();
                break;
            case "deposit":
                table
                    .columns( 9 )
                    .search( "deposit" , true)
                    .draw();
                break;
            case "receipt":
                table
                    .columns( 9 )
                    .search( "receipt" , true)
                    .draw();
                break;
            case "check":
                table
                    .columns( 9 )
                    .search( "check" , true)
                    .draw();
                break;
        }

        PageinateUpdate(table.page.info(), $('#next-page'), $('#previous-page'),$('#tableInfo'));
    });
    $('#invoice-status').change();
    PageinateUpdate(table.page.info(), $('#next-page'), $('#previous-page'),$('#tableInfo'));

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

    $(".dataTables_filter").css('display', 'none');
    $(".dataTables_length").css('display', 'none');
    $(".dataTables_paginate").css('display', 'none');
    $(".dataTables_info").css('display', 'none');
    $('#journal-table').css('width' , "100%");
});
function UpdateTotals(){

    var credits = 0;
    var debits = 0;

    var openingballence = {{ $beginningbalance }};
    var closeingballence = 0;

    $('#journal-table').DataTable().$('.total-check').each(function() {
        if (this.checked) {
            debits = debits + parseFloat($(this).data('debit'));
            credits = credits + parseFloat($(this).data('credit'));
        }
    });

    closeingballence = openingballence + credits;
    closeingballence = closeingballence - debits;

    $("#dynamic-credits").html('$' + credits.toFixed(2));
    $("#dynamic-debits").html('$' + debits.toFixed(2));
    $("#dynamic-ballence").html('$' + closeingballence.toFixed(2));

}
</script>

@include('Modals.addclientdeposit')
@include('Journal.Modals.DetailsModal')

@include('OS.FileStore.displayfile')
@stop
