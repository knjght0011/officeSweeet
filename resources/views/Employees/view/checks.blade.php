<div class="row" style="margin-top: 15px;">
    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="search"><div style="width: 7em;">Search:</div></span>
            <input id="checks-search" name="checks-search" type="text" placeholder="" value="" class="form-control">
        </div>
    </div>
    <div class="col-md-6">
        {!! PageElement::TableControl('checks') !!}
    </div>
    <div class="col-md-3">

    </div>
</div>


<table id="checksearch" class="table">
    <thead>
        <tr>
            <th>Check Number</th>
            
            <th>Date</th>
            <th>Amount</th>
            <th>Comments/Memo</th>
            <th></th>
        </tr>
    </thead>
    <tfoot style="visibility: hidden;">
        <tr>
            <th>Check Number</th>
            
            <th>Date</th>
            <th>Amount</th>
            <th>Comments/Memo</th>
            <th></th>
        </tr>
    </tfoot>
    <tbody>
        @foreach($employee->checks as $check)
            <tr>
                <td>{{ $check->checknumber }}</td>
                <td>{{ $check->formatDate() }}</td>
                <td>${{ $check->GetAmount() }}</td>
                <td>{{ $check->memo }}</td>
                <td><a id="link" href="/Checks/Edit/{{$check->id }}"><button class="btn OS-Button btn-sm" type="button">Edit Check</button></a></td>
            </tr>
        @endforeach
    </tbody>
</table>

<script>
$(document).ready(function() {

    
    // Setup - add a text input to each footer cell
    $('#checksearch tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input class="form-control" type="text" placeholder="Search '+title+'" />' );
    } );
    
    // DataTable
    var checksearch = $('#checksearch').DataTable();
 
    // Apply the search
    checksearch.columns().every( function () {
        var that = this;
 
        $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    } );

    $( "#checks-previous-page" ).click(function() {
        checksearch.page( "previous" ).draw('page');
        PageinateUpdate(checksearch.page.info(), $('#checks-next-page'), $('#checks-previous-page'),$('#checks-tableInfo'));
    });

    $( "#checks-next-page" ).click(function() {
        checksearch.page( "next" ).draw('page');
        PageinateUpdate(checksearch.page.info(), $('#checks-next-page'), $('#checks-previous-page'),$('#checks-tableInfo'));
    });

    $('#checks-search').on( 'keyup change', function () {
        checksearch.search( this.value ).draw();
        PageinateUpdate(checksearch.page.info(), $('#checks-next-page'), $('#checks-previous-page'),$('#checks-tableInfo'));
    });

    PageinateUpdate(checksearch.page.info(), $('#checks-next-page'), $('#checks-previous-page'),$('#checks-tableInfo'));

    $( "#checks" ).children().find(".dataTables_filter").css('display', 'none');
    $( "#checks" ).children().find(".dataTables_length").css('display', 'none');
    $( "#checks" ).children().find(".dataTables_paginate").css('display', 'none');
    $( "#checks" ).children().find(".dataTables_info").css('display', 'none');
    $('#checksearch').css('width' , "100%");

} );
</script>