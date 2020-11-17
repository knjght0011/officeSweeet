<div class="row" style="margin-top: 15px;">
    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="search"><div style="width: 7em;">Search:</div></span>
            <input id="receipts-search" name="receipts-search" type="text" placeholder="" value="" class="form-control">
        </div>
    </div>
    <div class="col-md-6">
        {!! PageElement::TableControl('receipts') !!}
    </div>
    <div class="col-md-3">
        <button type="button" class="btn OS-Button" style="width: 100%;" id="editReceipt">Edit Selected</button>
    </div>
</div>

<table id="receiptssearch" class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Amount</th>
            <th>Description</th>
            <th>Image</th>
            <th>Linked Account</th>
            <th>Entered By Employee</th>
        </tr>
    </thead>
    <tfoot style="visibility: hidden;">
        <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Amount</th>
            <th>Description</th>
            <th>Image</th>
            <th>Linked Account</th>
            <th>Entered By Employee</th>
        </tr>
    </tfoot>
    <tbody>
        @foreach($employee->receipts as $receipt)
            <tr>
                <td>{{ $receipt->id }}</td>
                <td>{{ $receipt->formatDate() }}</td>
                <td>{{ $receipt->formatedAmount() }}</td>
                <td>{{ $receipt->description  }}</td>
                <td>
                    <div class="hover_img">
                        <a href="#">Show Image<span><img src="{{ $receipt->image }}" alt="image" height="250" /></span></a>
                    </div>
                </td>
                <td>{{ $receipt->LinkedAccountName()  }} ({{ $receipt->LinkedType()  }})</td>
                <td>{{ $receipt->getEnteredUser()  }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<script>
$(document).ready(function() {
    
    $('#editReceipt').click(function(e) {
        $row = receiptssearch.row('.selected').data();
        console.log($row);
        var link = document.createElement('a');
        link.href = "/Reciepts/Edit/" + $row[0];
        link.id = "link";
        document.body.appendChild(link);
        link.click(); 
        
    });

    // DataTable
    var receiptssearch = $('#receiptssearch').DataTable({
        "columnDefs": [
            { "targets": [0],"visible": false}
        ],
        "language": {
            "emptyTable": "No Data"
        },
        "order": [[ 0, "desc" ]]
    });


    $('#receiptssearch tbody').on( 'click', 'tr', function () {
        $row = $(this);
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            receiptssearch.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );

    $( "#receipts-previous-page" ).click(function() {
        receiptssearch.page( "previous" ).draw('page');
        PageinateUpdate(receiptssearch.page.info(), $('#receipts-next-page'), $('#receipts-previous-page'),$('#receipts-tableInfo'));
    });

    $( "#receipts-next-page" ).click(function() {
        receiptssearch.page( "next" ).draw('page');
        PageinateUpdate(receiptssearch.page.info(), $('#receipts-next-page'), $('#receipts-previous-page'),$('#receipts-tableInfo'));
    });

    $('#receipts-search').on( 'keyup change', function () {
        receiptssearch.search( this.value ).draw();
        PageinateUpdate(receiptssearch.page.info(), $('#receipts-next-page'), $('#receipts-previous-page'),$('#receipts-tableInfo'));
    });

    PageinateUpdate(receiptssearch.page.info(), $('#receipts-next-page'), $('#receipts-previous-page'),$('#receipts-tableInfo'));

    $( "#receipts" ).children().find(".dataTables_filter").css('display', 'none');
    $( "#receipts" ).children().find(".dataTables_length").css('display', 'none');
    $( "#receipts" ).children().find(".dataTables_paginate").css('display', 'none');
    $( "#receipts" ).children().find(".dataTables_info").css('display', 'none');
    $('#receiptssearch').css('width' , "100%");
    
});
</script>