<div class="modal fade bs-example-modal-lg" id="LinkedAccountModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Select to Link Account</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group ">
                            <span class="input-group-addon" for="link-search"><div style="width: 7em;">Search:</div></span>
                            <input id="link-search" name="link-search" type="text" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="input-group ">
                            <span class="input-group-addon" for="link-type"><div style="width: 7em;">Type:</div></span>
                            <select id="link-type" name="link-type" type="text" placeholder="choice" class="form-control">
                                <option value="all">All</option>
                                <option value="Client">Client</option>
                                <option value="Vendor">Vendor</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-top: 15px; margin-left: 0px; margin-right: 0px;">
                    {!! PageElement::TableControl('link') !!}
                </div>

                <table id="linksearch" class="table">
                    <thead>
                        <tr id="head">
                            <th>id</th>
                            <th>Type</th>
                            <th>Name</th>
                            <th>Primary Contact</th>
                        </tr>
                    </thead>
                    <tfoot style="display: none;">
                        <tr>
                            <th>id</th>
                            <th>Type</th>
                            <th>Name</th>
                            <th>Primary Contact</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach($clients as $client)
                        <tr>
                            <td>
                                {{ $client->id }}
                            </td>
                            <td>
                                Client
                            </td>
                            <td>
                                {{ $client->getName() }}
                            </td>
                            <td>
                                @if(is_null($client->primarycontact_id))
                                    No Primary Contact Set
                                @else
                                    {{ $client->getPrimaryContactName() }}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @foreach($vendors as $vendor)
                        <tr>
                            <td>
                                {{ $vendor->id }}
                            </td>
                            <td>
                                Vendor
                            </td>
                            <td>
                                {{ $vendor->getName() }}
                            </td>
                            <td>
                                @if(is_null($vendor->primarycontact_id))
                                    No Primary Contact Set
                                @else
                                    {{ $vendor->getPrimaryContactName() }}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div><!-- /.modal-content -->
            <div class="modal-footer">
                <button id="clear-link" type="button" class="btn OS-Button">None</button>
                <button id="set-link" type="button" class="btn OS-Button">Set Link</button>
            </div>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>

<script>
$(document).ready(function() {
    $('#LinkedAccountModal').on('show.bs.modal', function (event) {
        linktable.$('tr.selected').removeClass('selected');

    });

    $("#addvendor").click(function()
    {
        GoToPage("/Vendors/Add/");
    });
    
    $("#addclient").click(function()
    {
        GoToPage("/Clients/Add/");
    });

    // DataTable
    var linktable = $('#linksearch').DataTable({
        "pageLength": 10,
        "columnDefs": [
            { "targets": [0],"visible": false}
        ]
    });

    $('#link-search').on( 'keyup change', function (e) {
        linktable.search( this.value ).draw();
        PageinateUpdate(linktable.page.info(), $('#link-next-page'), $('#link-previous-page'),$('#link-tableInfo'));
    });

    $('#link-type').on( 'change', function () {
        if($(this).val() === "all"){
            linktable
                .columns( 2 )
                .search( "" , true)
                .draw();
        }else {
            linktable
                .columns(2)
                .search("^" + $(this).val() + "$", true, false, true)
                .draw();
        }
        PageinateUpdate(linktable.page.info(), $('#link-next-page'), $('#link-previous-page'),$('#link-tableInfo'));
    });

    $( "#link-previous-page" ).click(function() {
        linktable.page( "previous" ).draw('page');
        PageinateUpdate(linktable.page.info(), $('#po-products-next-page'), $('#link-previous-page'),$('#link-tableInfo'));
    });

    $( "#link-next-page" ).click(function() {
        linktable.page( "next" ).draw('page');
        PageinateUpdate(linktable.page.info(), $('#link-next-page'), $('#link-previous-page'),$('#link-tableInfo'));
    });

    PageinateUpdate(linktable.page.info(), $('#link-next-page'), $('#link-previous-page'),$('#link-tableInfo'));
      
    $('#linksearch tbody').on( 'click', 'tr', function () {

        $row = $(this);
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }
        else {
            linktable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }

    });

    $('#set-link').click(function () {
        $row = linktable.row('.selected').data();
        if($row === undefined){
            $.dialog({
                title: 'Oops...',
                content: 'No link selected.'
            });
        }else{
            SetLink($row[1].toLowerCase(), $row[0], $row[2]);
            $('#LinkedAccountModal').modal('hide');
        }
    });

    $('#clear-link').click(function () {

        SetLink("None", 0, "None");

        $('#LinkedAccountModal').modal('hide');
    });
    
    $("#linksearch").css("width" , "100%");
});

function SetLink($linkedtype, $linkedid, $name){

    console.log($linkedtype);
    console.log($linkedid);
    console.log($name);

    $('#GoToLink').css('diplay', 'inline-block');//inline-block
    $("#linkedtype").val($linkedtype);
    $("#linkedid").val($linkedid);
    $("#account").val($name);
}
</script> 