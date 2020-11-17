<div class="modal fade bs-example-modal-lg" id="LinkedAccountModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Pick Account</h4>
      </div>
      <div class="modal-body">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#clients" aria-controls="clients" role="tab" data-toggle="tab">Clients</a></li>
            <li role="presentation"><a href="#vendors" aria-controls="vendors" role="tab" data-toggle="tab">Vendors</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="clients">
                <div class="row">
                    <div class="col-md-3">
                        <div id="newSearchPlace-client"></div>
                    </div>
                    <div class="col-md-4">
                        <div id="newPaginate-client"></div>
                    </div>     
                    <div class="col-md-3">
                        <button style="margin-top: 20px;" id="addclient" name="addclient" type="button" class="btn OS-Button">Add Client</button>
                    </div>
                    <div class="col-md-2">
                        <div id="newSearchLength-client" style="float: right"></div>
                    </div> 
                </div>                
                    <table id="clientsearch" class="table">
                        <thead>
                            <tr id="head">
                                <th>Sort</th>
                                <th>id</th>
                                <th>type</th>
                                <th>Name</th>
                            </tr>
                        </thead>
                        <tfoot style="display: none;">
                            <tr>
                                <th>Sort</th>
                                <th>id</th>
                                <th>type</th>
                                <th>Name</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <tr>
                                <td>
                                    0
                                </td> 
                                <td>
                                    0
                                </td> 
                                <td>
                                    client
                                </td>                                 
                                <td>
                                    Miscellaneous Expense
                                </td>   
                            </tr>
                            @foreach($clients as $client)
                            <tr>
                                <td>
                                    1
                                </td> 
                                <td>
                                    {{ $client->id }}
                                </td> 
                                <td>
                                    client
                                </td> 
                                <td>
                                    {{ $client->getName() }}
                                </td>   
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
            </div>
            <div role="tabpanel" class="tab-pane" id="vendors">
                <div class="row">
                    <div class="col-md-3">
                        <div id="newSearchPlace-vendor"></div>
                    </div>
                    <div class="col-md-4">
                        <div id="newPaginate-vendor"></div>
                    </div>     
                    <div class="col-md-3">
                        <button style="margin-top: 20px;" id="addvendor" name="addclient" type="button" class="btn OS-Button">Add Vendor</button>
                    </div>
                    <div class="col-md-2">
                        <div id="newSearchLength-vendor" style="float: right"></div>
                    </div> 
                </div>
                  
                    <table id="vendorsearch" class="table">
                        <thead>
                            <tr id="head">
                                <th>Sort</th>
                                <th>id</th>
                                <th>type</th>
                                <th>Name</th>
                            </tr>
                        </thead>
                        <tfoot style="display: none;">
                            <tr>
                                <th>Sort</th>
                                <th>id</th>
                                <th>type</th>
                                <th>Name</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <tr>
                                <td>
                                    0
                                </td> 
                                <td>
                                    0
                                </td> 
                                <td>
                                    vendor
                                </td>                                 
                                <td>
                                    Miscellaneous Expense
                                </td>   
                            </tr>
                            @foreach($vendors as $vendor)
                            <tr>
                                <td>
                                    1
                                </td> 
                                <td>
                                    {{ $vendor->id }}
                                </td> 
                                <td>
                                    vendor
                                </td> 
                                <td>
                                    {{ $vendor->getName() }}
                                </td>   
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
            </div>
        </div>
      </div>
        
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->    

<script>
$(document).ready(function() {
    $("#addvendor").click(function()
    {
        GoToPage("/Vendors/Add/");
    });
    
    $("#addclient").click(function()
    {
        GoToPage("/Clients/Add/");
    });
    
    $(".select-account").click(function()
    {
        $button = $(this);
        $linkedtype = $button.data('type');
        $linkedid = $button.data('id');
        $name = $button.data('name');
        
        console.log($linkedtype);
        console.log($linkedid);
        console.log($name);
        
        $("#account").val($name);
        $('#LinkedAccountModal').modal('hide');
        
    });
    
    // Setup - add a text input to each footer cell
    $('#clientsearch tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input class="form-control" type="text" placeholder="Search '+title+'" />' );
    });
 
    // DataTable
    var clienttable = $('#clientsearch').DataTable({
        "pageLength": 10,
        "columnDefs": [
            { "targets": [0,1,2],"visible": false}
        ]
      });
      
    $('#clientsearch tbody').on( 'click', 'tr', function () {
        $row = $(this);
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            clienttable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        $row = clienttable.row('.selected').data();
        console.log($row);

        $linkedtype = $row[2];
        $linkedid = $row[1];
        $name = $row[3];
        
        console.log($linkedtype);
        console.log($linkedid);
        console.log($name);
        
        $("#account").val($name);
        $('#LinkedAccountModal').modal('hide');
    } ); 
    
    // Apply the search
    clienttable.columns().every( function () {
        var that = this;
 
        $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    });
    
    $("#newSearchPlace-client").html($( "#clients" ).children().find(".dataTables_filter"));
    $("#newSearchLength-client").html($( "#clients" ).children().find(".dataTables_length"));
    $("#newPaginate-client").html($( "#clients" ).children().find(".dataTables_paginate"));

    // Setup - add a text input to each footer cell
    $('#vendorsearch tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input class="form-control" type="text" placeholder="Search '+title+'" />' );
    });
 
    // DataTable
    var vendortable = $('#vendorsearch').DataTable({
        "pageLength": 10,
        "columnDefs": [
            { "targets": [0,1,2],"visible": false}
        ]
    });

    $('#vendorsearch tbody').on( 'click', 'tr', function () {
        $row = $(this);
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            vendortable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        $row = vendortable.row('.selected').data();
        console.log($row);

        $linkedtype = $row[2];
        $linkedid = $row[1];
        $name = $row[3];
        
        console.log($linkedtype);
        console.log($linkedid);
        console.log($name);
        
        $("#account").val($name);
        $('#LinkedAccountModal').modal('hide');
    } ); 
    
    // Apply the search
    vendortable.columns().every( function () {
        var that = this;
 
        $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    });
    
    $("#newSearchPlace-vendor").html($( "#vendors" ).children().find(".dataTables_filter"));
    $("#newSearchLength-vendor").html($( "#vendors" ).children().find(".dataTables_length"));
    $("#newPaginate-vendor").html($( "#vendors" ).children().find(".dataTables_paginate"));
    
    $("#receiptupload").change(function()
    {
        input = document.getElementById('receiptupload');
        file = input.files[0];
        fr = new FileReader();
        fr.onload = receivedText;
        fr.readAsDataURL(file);
    });
    
    $("#clientsearch").css("width" , "100%");
    
    $("#vendorsearch").css("width" , "100%");
});
</script> 