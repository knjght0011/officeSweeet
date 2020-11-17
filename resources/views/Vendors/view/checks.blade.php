<div class="row">
    <div class="col-md-5">
        <div id="newSearchPlaceChecks"></div>
    </div>
    <div class="col-md-2">
        <div id="newPaginateChecks"></div>
    </div>
    <div class="col-md-5">
        <div id="newSearchLengthChecks" style="float: right"></div>
    </div> 
</div>
<div id="newInfoChecks"></div>


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
        @foreach($vendor->checks as $check)
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
    $("#newSearchPlaceChecks").html($( "#checks" ).children().find(".dataTables_filter"));
    $("#newSearchLengthChecks").html($( "#checks" ).children().find(".dataTables_length"));
    $("#newPaginateChecks").html($( "#checks" ).children().find(".dataTables_paginate"));
    $("#newInfoChecks").html($( "#checks" ).children().find(".dataTables_info"));
} ); 
</script>