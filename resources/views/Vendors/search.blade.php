@extends('master')

@section('content')  

    
<a id="link" href="/Vendors/Add"><button class="btn OS-Button btn-sm" type="button">Add Vendor</button></a>

<br />
<br />

<label class="radio-inline"><input type="radio" name="activeradio" value="1" checked="checked">Active Vendors</label>
<label class="radio-inline"><input type="radio" name="activeradio" value="0">Inactive Vendors</label>

<label class="radio-inline"><input type="checkbox" name="showid" value="1" checked="checked">Hide ID field</label>

<div id="activeContainer">
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
            <tr id="head" >
                <th style="width: 50px;" width="50px">ID</th>
                <th>Name</th>
                <th>Phone Number</th>
                <th>Address</th>
            </tr>
        </thead>
        <tfoot style="visibility: hidden;">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Phone Number</th>
                <th>Address</th>
            </tr>
        </tfoot>
        <tbody>
            @foreach($vendors as $vendor)
            <tr>
                <td>
                    {{ $vendor->id }}
                </td>
                <td>
                    <a id="link" href="/Vendors/View/{{ $vendor->id }}">
                        {{ $vendor->getName() }}                         
                    </a>
                </td>
                <td>
                    @if(is_null($vendor->primarycontact))
                        No Primary Contact Set
                    @else
                        <a href="tel:{{ $vendor->primarycontact->GetprimaryphonenumberRAW() }}">{{ $vendor->primarycontact->Getprimaryphonenumber() }}</a>
                    @endif
                </td>
                <td>
                    {{ $vendor->address->number }} {{ $vendor->address->address1 }} {{ $vendor->address->address2 }} {{ $vendor->address->city }} {{ $vendor->address->state }} {{ $vendor->address->zip }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div id="trashedContainer">
    <div class="row">
        <div class="col-md-6">
            <div id="newSearchPlaceTrashed"></div>
        </div> 
        <div class="col-md-6">
            <div id="newSearchLengthTrashed" style="float: right"></div>
        </div> 
    </div> 

    <table id="trashed" class="table">
        <thead>
            <tr id="head">
                <th class="col-md-1">ID</th>
                <th>Name</th>
                <th>Phone Number</th>
                <th>Address</th>
            </tr>
        </thead>
        <tfoot style="visibility: hidden;">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Phone Number</th>
                <th>Address</th>
            </tr>
        </tfoot>
        <tbody>
            @foreach($trashed as $trash)
            <tr id="client" value="{{ $trash->id }}">
                <td>
                    {{ $trash->id }}
                </td>
                <td>
                    <a id="link" href="/Vendors/Edit/{{ $trash->id }}">
                        {{ $trash->getName() }}
                    </a>
                </td>
                <td>
                    @if(is_null($trash->primarycontact))
                        No Primary Contact Set
                    @else
                        <a href="tel:{{ $trash->primarycontact->officenumberRAW() }}">{{ $trash->primarycontact->officenumber }}</a>
                    @endif
                </td>    
                <td>
                    {{ $trash->getaddress()->number }} {{ $trash->getaddress()->address1 }} {{ $trash->getaddress()->address2 }} {{ $trash->getaddress()->city }} {{ $trash->getaddress()->state }} {{ $trash->getaddress()->zip }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

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
    
            // Setup - add a text input to each footer cell
    $('#trashed tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input class="form-control" type="text" placeholder="Search '+title+'" />' );
    } );
 
    // DataTable
    var tabletrashed = $('#trashed').DataTable({
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
    $("#newSearchPlaceTrashed").html($( "#trashedContainer" ).children().find(".dataTables_filter"));
    $("#newSearchLengthTrashed").html($( "#trashedContainer" ).children().find(".dataTables_length"));
    
    $("#trashedContainer").css("display","none");
    
    $( 'input[name="activeradio"]:radio' ).change(function() {
        if($('input[name=activeradio]:checked').val() == 1){
            $("#trashedContainer").css("display","none");
            $("#activeContainer").css("display","Inline");
            $("#search").css("width" , "100%");
        }
        if($('input[name=activeradio]:checked').val() == 0){
            $("#activeContainer").css("display","none");
            $("#trashedContainer").css("display","Inline");
            $("#trashed").css("width" , "100%");
        }
        
    });
    
    $( 'input[name="showid"]:checkbox' ).change(function() {
        var column = table.column( 0 );
        column.visible( ! column.visible() );

        var column2 = tabletrashed.column( 0 );
        column2.visible( ! column2.visible() );

    });
} );
</script> 

@stop