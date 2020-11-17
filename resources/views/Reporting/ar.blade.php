@extends('master')

@section('content')


        
    <table id="search" class="table">
        <thead>
            <tr id="head">
                <th class="col-md-1" >Invoice Number</th>
                <th class="col-md-2" >Invoice Date</th>
                <th>Comments</th>
                <th class="col-md-1" >{{ TextHelper::GetText("Client") }} ID</th>
                <th class="col-md-1" >Total Cost</th>
                <th class="col-md-1" >Balance</th>
                
            </tr>
        </thead>
        <tfoot style="visibility: hidden;">
            <tr id="head">
                <th class="col-md-1" >Invoice Number</th>
                <th class="col-md-2" >Invoice Date</th>
                <th>Comments</th>
                <th class="col-md-1" >{{ TextHelper::GetText("Client") }} ID</th>
                <th class="col-md-1" >Total Cost</th>
                <th class="col-md-1" >Balance</th>
                
            </tr>
        </tfoot>
        <tbody> 

            @foreach($OutstandingInvoice as $Invoice)
            @if($Invoice->getBallence() > 0)
            <tr>
                <td>{{$Invoice->getQuoteNumber() }}</td>
                <td>{{$Invoice->GetInvoiceDate() }}</td>
                <td>{{$Invoice->comments }}</td>
                <td><A id="link" href="/Clients/View/{{$Invoice->client_id}}">{{$Invoice->Client->name }} </a></td>
                <td>${{$Invoice->getTotal() }}</td>
                <td>${{$Invoice->getBallence() }}</td>
            </tr>
            @endif
            @endforeach
            <tr>
                <td></td>   
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td align="right">Receivables:</td>
                <td> ${{$subtotal}}</td>
            </tr>
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

@stop
