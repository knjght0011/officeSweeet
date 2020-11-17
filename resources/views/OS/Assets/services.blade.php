<div class="row" style="margin-top: 10px;">
    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="search"><div style="width: 7em;">Search:</div></span>
            <input id="service-search" name="service-search" type="text" placeholder="" value="" class="form-control">
        </div>
    </div>

    <div class="col-md-3">
        <button style="width: 100%;" type="button" class="btn OS-Button" data-toggle="modal" data-target="#addservice"
                data-mode="new"
                data-id="0"
                data-sku=""
                data-description=""
                data-charge="0"
                data-cost="0"
                data-taxable="0">
            Add Service
        </button>
    </div>

    <div class="col-md-3">
        <button style="width: 100%;" type="button" class="btn OS-Button" data-toggle="modal" data-target="#addservice"
                data-mode="edit">
            Edit Service
        </button>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        {!! PageElement::TableControl('service') !!}
    </div>
</div>

    <table id="servicetable" class="table">
        <thead>
            <tr id="head">
                <th>SKU</th>
                <th>Name</th>
                <th>Charge</th>
                <th>Cost</th>
                <th>Taxable</th>
                <th class="datatables-invisible-col">id</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>SKU</th>
                <th>Name</th>
                <th>Charge</th>
                <th>Cost</th>
                <th>Taxable</th>
                <th>id</th>
            </tr>
        </tfoot>
        <tbody>
            @foreach($services as $service)
            <tr>
                <td>{{ $service->sku }}</td>
                <td>{{ $service->description }}</td>
                <td>${{ $service->charge }}</td>
                <td>${{ $service->cost }}</td>
                <td>{{ $service->taxablewords() }}</td>
                <td>{{ $service->id }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>


<div class="modal fade" id="addservice" tabindex="-1" role="dialog" aria-labelledby="addservice" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="addservice">Add service/Service</h4>
        </div>
        <div class="modal-body">
            <div class="input-group ">
                <span class="input-group-addon" for="service-sku"><div style="width: 15em;">Sku:</div></span>
                <input id="service-sku" name="service-sku" type="text" class="form-control">
            </div>

            <div class="input-group ">
                <span class="input-group-addon" for="service-description"><div style="width: 15em;">Name:</div></span>
                <input id="service-description" name="service-description" type="text" class="form-control">
            </div>

            <div class="input-group ">
                <span class="input-group-addon" for="service-charge"><div style="width: 15em;">Charge:</div></span>
                <input id="service-charge" name="service-charge" type="text" class="form-control numonly">
            </div>

            <div class="input-group ">
                <span class="input-group-addon" for="service-cost"><div style="width: 15em;">Cost:</div></span>
                <input id="service-cost" name="service-cost" type="text" class="form-control numonly">
            </div>

            <div class="input-group ">
                <span class="input-group-addon" for="service-taxable"><div style="width: 15em;">Taxable:</div></span>
                <input type="checkbox" name="checkboxes" id="service-taxable" data-on="Yes" data-off="No" data-toggle="toggle" data-width="100%">
            </div>

            <input style="display: none;" id="service-id" name="service-id" type="text" value="" class="form-control">
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button id="Saveservice" name="Saveservice" type="button" class="btn OS-Button">Save</button>
        </div>
    </div>
  </div>
</div>    



<script>

$(document).ready(function() {

    // DataTable
    var servicetable = $('#servicetable').DataTable({
        "columns": [
            { "data": "sku" },
            { "data": "description" },
            { "data": "charge" },
            { "data": "cost" },
            { "data": "taxable" },
            { "data": "id" }
        ],
        "columnDefs": [
            {
                "targets": "datatables-invisible-col",
                "visible": false
            }
        ],
    });

    $('#addservice').on('show.bs.modal', function (event) {

        var button = $(event.relatedTarget); // Button that triggered the modal

        console.log(button);

        if(button.data('mode') === "new"){
            var sku = button.data('sku');
            var description = button.data('description');
            var charge = button.data('charge');
            var cost = button.data('cost');
            $("#service-taxable").bootstrapToggle('off');
            var id = button.data('id');
        }else{
            $row = servicetable.row('.selected').data();
            if($row === undefined){
                event.preventDefault();
                $.dialog({
                    title: 'Oops...',
                    content: 'Please Select a Service from the table.'
                });
            }else{
                var sku = $row['sku'];
                var description = $row['description'];
                var charge = $row['charge'].substring(1);
                var cost = $row['cost'].substring(1);
                if($row['taxable'] === "Yes"){
                    $("#service-taxable").bootstrapToggle('on');
                }else{
                    $("#service-taxable").bootstrapToggle('off');
                }
                var id = $row['id'];
            }
        }

        $('#service-description').val(description);
        $('#service-sku').val(sku);
        $('#service-charge').val(charge);
        $('#service-cost').val(cost);
        $('#service-id').val(id);

    });
    
    $('#addservice').on('hidden.bs.modal', function (event) {
        $('#service-description').removeClass('invalid');
        $('#service-sku').removeClass('invalid');
        $('#service-charge').removeClass('invalid');
        $('#service-cost').removeClass('invalid');
        $('#service-taxable').removeClass('invalid');
    });

    $('#servicetable tbody').on( 'click', 'tr', function () {
        $row = $(this);
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            servicetable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );

    $( "#service-previous-page" ).click(function() {
        servicetable.page( "previous" ).draw('page');
        PageinateUpdate(servicetable.page.info(), $('#service-next-page'), $('#service-previous-page'),$('#service-tableInfo'));
    });

    $( "#service-next-page" ).click(function() {
        servicetable.page( "next" ).draw('page');
        PageinateUpdate(servicetable.page.info(), $('#service-next-page'), $('#service-previous-page'),$('#service-tableInfo'));
    });

    $('#service-search').on( 'keyup change', function () {
        servicetable.search( this.value ).draw();
        PageinateUpdate(servicetable.page.info(), $('#service-next-page'), $('#service-previous-page'),$('#service-tableInfo'));
    });

    PageinateUpdate(servicetable.page.info(), $('#service-next-page'), $('#service-previous-page'),$('#service-tableInfo'));

    $( "#services" ).children().find(".dataTables_filter").css('display', 'none');
    $( "#services" ).children().find(".dataTables_length").css('display', 'none');
    $( "#services" ).children().find(".dataTables_paginate").css('display', 'none');
    $( "#services" ).children().find(".dataTables_info").css('display', 'none');
    $('#servicetable').css('width' , "100%");


   $("#Saveservice").click(function() {

        ResetServerValidationErrors();

        $data = {};
        $data['_token'] = "{{ csrf_token() }}";

        $data['description'] = $('#service-description').val();
        $data['sku'] = $('#service-sku').val();
        $data['charge'] = $('#service-charge').val();
        $data['cost'] = $('#service-cost').val();
        if($('#service-taxable').prop('checked') === true){
            $data['taxable'] = 1;
        }else{
            $data['taxable'] = 0;
        }
        $data['id'] = $('#service-id').val();

        $("body").addClass("loading");
        servicepost = $.post("/Services/Save", $data);

        servicepost.done(function( data ) 
        {
            $("body").removeClass("loading");
            switch(data['status']) {
                case "OK":
                    $row = [];
                    $row['sku'] = data['service']['sku'];
                    $row['description'] = data['service']['description'];
                    $row['charge'] = "$" + data['service']['charge'];
                    $row['cost'] = "$" + data['service']['cost'];
                    if(data['service']['taxable'] === 1){
                        $row['taxable'] = "Yes";
                    }else{
                        $row['taxable'] = "No";
                    }
                    $row['id'] = data['service']['id'];

                    if(data['mode'] === "new"){
                        servicetable.row.add($row).draw();
                    }else{
                        servicetable.row('.selected').data($row).draw();
                    }

                    $('#addservice').modal('hide');

                    SavedSuccess();
                    break;
                case "validation":
                    ServerValidationErrors(data['errors']);
                    break;
                default:
                    console.log(data);
                    $.dialog({
                        title: 'Oops...',
                        content: 'Unknown Response from server. Please refresh the page and try again.'
                    });
            }
        });

        servicepost.fail(function() {
            NoReplyFromServer();
        });
    });

    $('.numonly').on('keypress', function(e) {
        keys = ['0','1','2','3','4','5','6','7','8','9','.'];
        return keys.indexOf(event.key) > -1;
    });

});
</script>