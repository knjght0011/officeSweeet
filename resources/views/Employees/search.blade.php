@extends('master')

@section('content')  


<a id="link" href="/Employees/Add"><button class="btn OS-Button btn-sm" type="button">Add Employee</button></a>
<a id="link" href="/Employees/Payroll"><button class="btn OS-Button btn-sm" type="button">Payroll</button></a>

<label class="radio-inline"><input type="checkbox" name="showid" value="1" checked="checked">Hide ID field</label>

<div class="row">
    <div class="col-md-6">
        <div id="newSearchPlace"></div>
    </div> 
    <div class="col-md-6">
        <div id="newSearchLength" style="float: right"></div>
    </div> 
</div>

<table id="search" class="table">
    <thead>
        <tr id="head">
            <th class="col-md-1">ID</th>
            <th>Employee ID</th>
            <th>Name</th>
            <th>Type</th>
            <th class="col-md-1">Can Login</th>
            <th class="col-md-6">Address</th>
        </tr>
    </thead>
    <tfoot style="visibility: hidden;">
        <tr>
            <th>ID</th>
            <th>Employee ID</th>
            <th>Name</th>
            <th>Type</th>
            <th>Can Login</th>
            <th>Address</th>
        </tr>
    </tfoot>
    <tbody>

        @foreach($employees as $employee)
        <tr>
            <td>{{ $employee->id }}</td>
            <td>{{ $employee->employeeid }}</td>
            <td><a id="link" href="/Employees/View/{{ $employee->id }}">{{ $employee->firstname }} {{ $employee->middlename }} {{ $employee->lastname }}</a></td>
            
            <td>{{ $employee->typeword() }}</td>
            
            @if($employee->canlogin == 1)
            <td><span class="input-group-addon success"><span class="glyphicon glyphicon-ok"></span></span></td>
            @else
            <td><span class="input-group-addon danger"><span class="glyphicon glyphicon-remove"></span></span></td>
            @endif
            <td>{{ $employee->address->address1 }} {{ $employee->address->address2 }} {{ $employee->address->city }} {{ $employee->address->state }} {{ $employee->address->zip }}</td>
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
    var table = $('#search').DataTable({
        "pageLength": 50,
        "columnDefs": [
            {
                "targets": 0,
                "visible": false
            }
        ]
      });
 
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
    $("#newSearchPlace").html($(".dataTables_filter"));
    $("#newSearchLength").html($(".dataTables_length"));
    
    $( 'input[name="showid"]:checkbox' ).change(function() {
        var column = table.column( 0 );
        column.visible( ! column.visible() );

        var column2 = tabletrashed.column( 0 );
        column2.visible( ! column2.visible() );

    });
} );
</script> 

@stop