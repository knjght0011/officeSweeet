@extends('master')

@section('content')   
<a id="link" href="/Clients/Add"><button class="btn OS-Button btn-sm" type="button">Add Client</button></a>
<br />
<br />

<label class="radio-inline"><input type="checkbox" name="showid" value="1" checked="checked">Hide ID field</label>

<div id="activeContainer">
    <div class="row">
        <div class="col-md-6">
            <div id="newSearchPlaceActive"></div>
        </div> 
        <div class="col-md-6">
            <div id="newSearchLengthActive" style="float: right"></div>
        </div> 
    </div> 

    <table id="search" class="table">
        <thead>
            <tr id="head">
                <th class="col-md-1">ID</th>
                <th>Name</th>
                <th>Phone Number</th>
                <th>Address</th>
                <th>Status</th>
                <th>Status</th>
            </tr>
        </thead>
        <tfoot style="display: table-header-group;">
            <tr>
                <th style="visibility: hidden;" class="col-md-1">ID</th>
                <th style="visibility: hidden;">Name</th>
                <th style="visibility: hidden;">Phone Number</th>
                <th style="visibility: hidden;">Address</th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
        <tbody>
            @foreach($clients as $client)
            <tr id="client" value="{{ $client->id }}">
                <td>
                    {{ $client->id }}
                </td>
                <td>
                    <a id="link" href="/Clients/View/{{ $client->id }}">

                        {{ $client->getName() }}

                    </a>
                </td>
                <td>
                    @if(is_null($client->primarycontact))
                        No Primary Contact Set
                    @else
                        <a href="tel:{{ $client->primarycontact->GetprimaryphonenumberRAW() }}">{{ $client->primarycontact->Getprimaryphonenumber() }}</a>
                    @endif
                </td>    
                <td>
                    {{ $client->address->number }} {{ $client->address->address1 }} {{ $client->address->address2 }} {{ $client->address->city }} {{ $client->address->state }} {{ $client->address->zip }}
                </td>
                <td>
                    {{ $client->getStatus() }}
                </td>
                <td>
                    Active
                </td>
            </tr>
            @endforeach
            @foreach($trashed as $trash)
            <tr id="client" value="{{ $trash->id }}">
                <td>
                    {{ $trash->id }}
                </td>
                <td>
                    <a id="link" href="/Clients/Edit/{{ $trash->id }}">
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
                    {{ $trash->address->number }} {{ $trash->address->address1 }} {{ $trash->address->address2 }} {{ $trash->address->city }} {{ $trash->address->state }} {{ $trash->address->zip }}
                </td>
                <td>
                    {{ $trash->getStatus() }}
                </td>
                <td>
                    Inactive
                </td>
            </tr>
            @endforeach            
        </tbody>
    </table>
</div>

<script>

$(document).ready(function() {
     
 
    // DataTable
    
    var table = $('#search').DataTable({
        initComplete: function () {
            this.api().columns().every(function () {
                var column = this;
                var select = $('<select class="form-control"><option value=""></option></select>')
                    .appendTo($(column.footer()).empty())
                    .on('change', function () {
                    var val = $.fn.dataTable.util.escapeRegex(
                    $(this).val());

                    column.search(val ? '^' + val + '$' : '', true, false)
                        .draw();
                });

                column.data().unique().sort().each(function (d, j) {
                    if(d === "Client" || d === "Active"){
                        select.append('<option selected="selected" value="' + d + '">' + d + '</option>');
                    }else{                       
                        select.append('<option value="' + d + '">' + d + '</option>');
                    }
                });
                select.change();
            });
        }       
    });
    $('.dataTables_filter input').unbind().on('keyup', function() {
        var searchTerm = this.value.toLowerCase();
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
           //search only in column 1 and 2
               if (~data[1].toLowerCase().indexOf(searchTerm)) return true;
               if (~data[2].toLowerCase().indexOf(searchTerm)) return true;
               if (~data[3].toLowerCase().indexOf(searchTerm)) return true;
           return false;
       });
       table.draw(); 
       $.fn.dataTable.ext.search.pop();
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
    
    $("#newSearchPlaceActive").html($( "#activeContainer" ).children().find(".dataTables_filter"));
    $("#newSearchLengthActive").html($( "#activeContainer" ).children().find(".dataTables_length"));

       
    $( 'input[name="showid"]:checkbox' ).change(function() {
        var column = table.column( 0 );
        column.visible( ! column.visible() );

    });
    var column = table.column( 0 );
    column.visible( ! column.visible() );
} );
</script> 

@stop