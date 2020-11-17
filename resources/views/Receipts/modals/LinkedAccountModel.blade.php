<div class="modal fade bs-example-modal-lg" id="LinkedAccountModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Choose Account</h4>
        </div>
        <div class="modal-body" >

            <div class="row">
                <div class="col-md-4" style="padding-right: 5px;">
                    <div class="input-group">
                        <span class="input-group-addon" for="linkedaccount-search"><div>Search:</div></span>
                        <input id="linkedaccount-search" name="linkedaccount-search" type="text" placeholder="" value="" class="form-control">
                    </div>
                </div>
                <div class="col-md-2" style="padding-left: 5px; padding-right: 5px;">
                    <button style="width: 100%;" id="addclient" name="addclient" type="button" class="btn OS-Button">Add Client</button>
                </div>
                <div class="col-md-2" style="padding-left: 5px; padding-right: 5px;">
                    <button style="width: 100%;" id="addvendor" name="addclient" type="button" class="btn OS-Button">Add Vendor</button>
                </div>
                <div class="col-md-4" style="padding-left: 5px;">
                    <div class="input-group ">
                        <span class="input-group-addon" for="linkedaccount-status"><div>Type:</div></span>
                        <select id="linkedaccount-status" name="linkedaccount-status" type="text" placeholder="choice" class="form-control">
                            <option value="vendor">Vendors</option>
                            <option value="client">Clients</option>
                            <option value="all" selected>All</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 10px;">
                <div class="col-md-12">
                    {!! PageElement::TableControl('linkedaccount') !!}
                </div>
            </div>
                <table id="linkedaccount" class="table">
                    <thead>
                        <tr id="head">
                            <th>Sort</th>
                            <th>id</th>
                            <th>type</th>
                            <th id="col1">Name</th>
                            <th id="col2">Type</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Sort</th>
                            <th>id</th>
                            <th>type</th>
                            <th>Name</th>
                            <th>Type</th>
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
                                Miscellaneous Expense
                            </td>
                            <td>
                                Miscellaneous Expense
                            </td>
                            <td>
                                None
                            </td>
                        </tr>
                        @foreach($clients as $client)
                        <tr>
                            <td>
                                2
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
                            <td>
                                Client
                            </td>
                        </tr>
                        @endforeach
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
                                <td>
                                    Vendor
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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

    $( "#linkedaccount-previous-page" ).click(function() {
        linkedaccount.page( "previous" ).draw('page');
        PageinateUpdate(linkedaccount.page.info(), $('#linkedaccount-next-page'), $('#linkedaccount-previous-page'),$('#linkedaccount-tableInfo'));
        //$('#col1').css('width' , '70%');
        //$('#col2').css('width' , '30%');
    });

    $( "#linkedaccount-next-page" ).click(function() {
        linkedaccount.page( "next" ).draw('page');
        PageinateUpdate(linkedaccount.page.info(), $('#linkedaccount-next-page'), $('#linkedaccount-previous-page'),$('#linkedaccount-tableInfo'));
        //$('#col1').css('width' , '70%');
        //$('#col2').css('width' , '30%');
    });

    $('#linkedaccount-search').on( 'keyup change', function () {
        linkedaccount.search( this.value ).draw();
        PageinateUpdate(linkedaccount.page.info(), $('#linkedaccount-next-page'), $('#linkedaccount-previous-page'),$('#linkedaccount-tableInfo'));
    });

    $('#linkedaccount-status').on( 'keyup change', function () {
        switch(this.value) {
            case "all":
                linkedaccount
                    .columns( 2 )
                    .search( "" , true)
                    .draw();
                break;
            case "client":
                linkedaccount
                    .columns( 2 )
                    .search( "client" , true)
                    .draw();
                break;
            case "vendor":
                linkedaccount
                    .columns( 2 )
                    .search( "vendor" , true)
                    .draw();
                break;
        }

        PageinateUpdate(linkedaccount.page.info(), $('#linkedaccount-next-page'), $('#linkedaccount-previous-page'),$('#linkedaccount-tableInfo'));
    });



    // DataTable
    var linkedaccount = $('#linkedaccount').DataTable({
        "pageLength": 10,
        "columnDefs": [{ "targets": [0,1,2],"visible": false}]
      });

    PageinateUpdate(linkedaccount.page.info(), $('#linkedaccount-next-page'), $('#linkedaccount-previous-page'),$('#linkedaccount-tableInfo'));

    $('#linkedaccount tbody').on( 'click', 'tr', function () {
        $row = $(this);
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            linkedaccount.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        $row = linkedaccount.row('.selected').data();
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


    $( "#LinkedAccountModal" ).children().find(".dataTables_filter").css('display', 'none');
    $( "#LinkedAccountModal" ).children().find(".dataTables_length").css('display', 'none');
    $( "#LinkedAccountModal" ).children().find(".dataTables_paginate").css('display', 'none');
    $( "#LinkedAccountModal" ).children().find(".dataTables_info").css('display', 'none');
    $("#linkedaccount").css("width" , "100%");
    $('#col1').css('width' , '80%');
    $('#col2').css('width' , '20%');

});
</script> 