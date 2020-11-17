<style>
.transactionoverview-status{
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
    <div class="col-md-4" id="open-transactionoverview-amount">
        <h2>Current Balance: ${{ $client->getBalence(true) }}</h2>
    </div>
</div>
<div class="row" style="margin-top: 15px;">
    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="search"><div style="width: 7em;">Search:</div></span>
            <input id="transactionoverview-search" name="transactionoverview-search" type="text" placeholder="" value="" class="form-control">
        </div>
    </div>
    <div class="col-md-6">
        {!! PageElement::TableControl('transactionoverview') !!}
    </div>
    <div class="col-md-3">
        <button type="button" class="btn OS-Button" style="width: 100%;" data-toggle="modal" data-target="#ShowPdfModel" data-title="View Overview" data-url="/Reporting/Report/PaymentsClient/d/d/{{ $client->id }}/pdf" data-mode="overview">Print/Email/Download</button>
    </div>
</div>

<table id="transactionoverviewsearch" class="table">
    <thead>
        <tr>
            <th >Date</th>
            <th >Type</th>
            <th >Charge/Debit</th>
            <th >Payment/Credit</th>
            <th>Ending Balance</th>
        </tr>
    </thead>
    <tfoot style="visibility: hidden;">
        <tr>
            <th >Date</th>
            <th >Type</th>
            <th >Charge/Debit</th>
            <th >Payment/Credit</th>
            <th>Ending Balance</th>
        </tr>
    </tfoot>
    <tbody>
        @foreach(array_reverse (ClientOverviewHelper::Data($client, null, null, false)) as $row)
            <tr>
                <td>{{ $row['date'] }}</td>
                <td>{{ $row['type'] }}</td>
                <td>{{ $row['debt'] }}</td>
                <td>{{ $row['credit'] }}</td>
                <td>${{ number_format($row['running'], 2, '.', '') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<script>
$(document).ready(function() {


    // DataTable
    var transactionoverviewsearch = $('#transactionoverviewsearch').DataTable({
        "ordering": false
    });

    $( "#transactionoverview-previous-page" ).click(function() {
        transactionoverviewsearch.page( "previous" ).draw('page');
        PageinateUpdate(transactionoverviewsearch.page.info(), $('#transactionoverview-next-page'), $('#transactionoverview-previous-page'),$('#transactionoverview-tableInfo'));
    });

    $( "#transactionoverview-next-page" ).click(function() {
        transactionoverviewsearch.page( "next" ).draw('page');
        PageinateUpdate(transactionoverviewsearch.page.info(), $('#transactionoverview-next-page'), $('#transactionoverview-previous-page'),$('#transactionoverview-tableInfo'));
    });

    $('#transactionoverview-search').on( 'keyup change', function () {
        transactionoverviewsearch.search( this.value ).draw();
        PageinateUpdate(transactionoverviewsearch.page.info(), $('#transactionoverview-next-page'), $('#transactionoverview-previous-page'),$('#transactionoverview-tableInfo'));
    });

    $('#transactionoverview-status').on( 'keyup change', function () {
        switch(this.value) {
            case "all":
                transactionoverviewsearch
                    .columns( 5 )
                    .search( "" , true)
                    .draw();
                break;
            case "outstanding":
                transactionoverviewsearch
                    .columns( 5 )
                    .search( "due" , true)
                    .draw();
                break;
            case "paid":
                transactionoverviewsearch
                    .columns( 5 )
                    .search( "paid" , true)
                    .draw();
                break;
            case "void":
                transactionoverviewsearch
                    .columns( 5 )
                    .search( "void" , true)
                    .draw();
                break;
        }

        PageinateUpdate(transactionoverviewsearch.page.info(), $('#transactionoverview-next-page'), $('#transactionoverview-previous-page'),$('#transactionoverview-tableInfo'));
    });
    $('#transactionoverview-status').change();

    PageinateUpdate(transactionoverviewsearch.page.info(), $('#transactionoverview-next-page'), $('#transactionoverview-previous-page'),$('#transactionoverview-tableInfo'));

    $( "#transactions-overview" ).children().find(".dataTables_filter").css('display', 'none');
    $( "#transactions-overview" ).children().find(".dataTables_length").css('display', 'none');
    $( "#transactions-overview" ).children().find(".dataTables_paginate").css('display', 'none');
    $( "#transactions-overview" ).children().find(".dataTables_info").css('display', 'none');
    $('#transactionoverviewsearch').css('width' , "100%");

});
</script>
