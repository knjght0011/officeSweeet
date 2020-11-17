    <div class="row"><div class="col-md-12"><br></div></div>
    <a id="link" href="/Admin/Settings/Branches/New">New</a>
    
    <table id="search" class="table">
        <thead>
            <tr id="head">
                <th>House Name/Number</th>
                <th>Street</th>
                <th>Address Line 2</th>
                <th>City</th>
                <th>Region</th>
                <th>State</th>
                <th>Zip</th>
            </tr>
        </thead>
        <tfoot style="display: table-header-group;">
            <tr>
                <th>House Name/Number</th>
                <th>Street</th>
                <th>Address Line 2</th>
                <th>City</th>
                <th>Region</th>
                <th>State</th>
                <th>Zip</th>
            </tr>
        </tfoot>
        <tbody>
            @foreach($branches as $branch)
            <tr>
                <td>{{ $branch->number }}</td>
                <td>{{ $branch->address1 }}</td>
                <td>{{ $branch->address2 }}</td>
                <td>{{ $branch->city }}</td>
                <td>{{ $branch->region }}</td>
                <td>{{ $branch->state }}</td>
                <td>{{ $branch->zip }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>


<script>

$(document).ready(function() {
    // Setup - add a text input to each footer cell
    $('#search tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input class="form-control" type="text" placeholder="Search '+title+'" />' );
    } );
 
    // DataTable
    var table = $('#search').DataTable();
 
    // Apply the search
    table.columns().every( function () {
        var that = this;
 
        $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    } );
} );
</script> 

