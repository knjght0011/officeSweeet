<div id="address-existing-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    Existing Address
                </h4>
            </div>
            <div class="modal-body" >

                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group ">
                            <span class="input-group-addon" for="search"><div style="width: 7em;">Search:</div></span>
                            <input id="existing-address-search" name="invoice-search" type="text" placeholder="" value="" class="form-control">
                        </div>
                    </div>
                    {{--
                    <div class="col-md-6">
                        <div class="input-group ">
                            <span class="input-group-addon" for="invoice-status"><div style="width: 7em;">Status:</div></span>
                            <select id="invoice-status" name="invoice-status" type="text" placeholder="choice" class="form-control">
                                <option value="outstanding">Outstanding</option>
                                <option value="paid">Paid</option>
                                <option value="all" selected>All</option>
                            </select>
                        </div>
                    </div>
                    --}}
                </div>


                <div class="row" style="margin-top: 15px;">
                    <div class="col-md-12">
                    {!! PageElement::TableControl('existing-address') !!}
                    </div>
                </div>

                <table class="table" id="existing-address-table">
                    <thead>
                    <tr>
                        <th>
                            id
                        </th>
                        <th>
                            Address
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($addresss as $address)
                        <tr>
                            <td>{{ $address->id }}</td>
                            <td>{{ $address->AddressString() }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#address-existing-modal').on('show.bs.modal', function (event) {
        if($('#address_id').val() === "null"){
            existingaddresstable.$('tr.selected').removeClass('selected');
        }
        if($('#address_id').val() === "0"){
            existingaddresstable.$('tr.selected').removeClass('selected');
        }
    });

    $('#address-existing-modal').on('hide.bs.modal', function (event) {
        $length = existingaddresstable.$('tr.selected').length;
        if($length === 0){
            //they didnt click anything might need to move radio button back, not sure how
            if($('#address_id').val() === "null"){
                $('#address-client').prop('checked', true)
            }
        }
    });


    var existingaddresstable = $('#existing-address-table').DataTable({
        "columnDefs": [
            { "targets": [0],"visible": false}
        ]
    });


    $('#existing-address-table').on( 'click', 'tr', function () {
        $row = $(this);
        existingaddresstable.$('tr.selected').removeClass('selected');
        $(this).addClass('selected');
        $data = existingaddresstable.row('.selected').data();
        UpdateAddress($data[0], $data[1]);
        $('#address-existing-modal').modal('hide');
    });

    $( "#existing-address-previous-page" ).click(function() {
        existingaddresstable.page( "previous" ).draw('page');
        PageinateUpdate(existingaddresstable.page.info(), $('#existing-address-next-page'), $('#existing-address-previous-page'),$('#existing-address-tableInfo'));
    });

    $( "#existing-address-next-page" ).click(function() {
        existingaddresstable.page( "next" ).draw('page');
        PageinateUpdate(existingaddresstable.page.info(), $('#existing-address-next-page'), $('#existing-address-previous-page'),$('#existing-address-tableInfo'));
    });

    $('#existing-address-search').on( 'keyup change', function () {
        existingaddresstable.search( this.value ).draw();
        PageinateUpdate(existingaddresstable.page.info(), $('#existing-address-next-page'), $('#existing-address-previous-page'),$('#existing-address-tableInfo'));
    });

    PageinateUpdate(existingaddresstable.page.info(), $('#existing-address-next-page'), $('#existing-address-previous-page'),$('#existing-address-tableInfo'));

    $( "#address-existing-modal" ).children().find(".dataTables_filter").css('display', 'none');
    $( "#address-existing-modal" ).children().find(".dataTables_length").css('display', 'none');
    $( "#address-existing-modal" ).children().find(".dataTables_paginate").css('display', 'none');
    $( "#address-existing-modal" ).children().find(".dataTables_info").css('display', 'none');
    $('#existing-address-table').css('width' , "100%");
});
</script>