@include('Emails.emailCompose')
<div class="row" style="margin-top: 20px;">

    <div class="col-md-4">
        <div class="input-group ">
            <span class="input-group-addon" for="client-search"><div style="width: 7em;">Search:</div></span>
            <input id="client-search" name="client-search" type="text" placeholder="" value="" class="form-control">
        </div>
    </div>


    <div class="col-md-3">
        <div class="input-group">
            <span class="input-group-addon" for="client-category-search"><div style="width: 7em;">Category:</div></span>
            <select id="client-category-search" name="client-category-search" class="form-control">
                <option value="all" selected>All</option>
                @foreach(\App\Helpers\OS\Client\ClientHelper::AllCategorys() as $department)
                    <option value="{{ $department }}">{{ $department }}</option>
                @endforeach
                <option value="none">None</option>
            </select>
        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group ">
            <span class="input-group-addon" for="client-length"><div style="width: 7em;">Show:</div></span>
            <select id="client-length" name="client-length" type="text" placeholder="choice"
                    class="form-control">
                <option value="10">10 entries</option>
                <option value="25">25 entries</option>
                <option value="50">50 entries</option>
                <option value="100">100 entries</option>
            </select>
        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group">
            <span class="input-group-addon" for="client-status"><div style="width: 7em;">Status:</div></span>
            <select id="client-status" name="client-status" type="text" placeholder="choice"
                    class="form-control">
                <option value="all">All</option>
                <option value="Active" selected>Active</option>
                <option value="Inactive">Inactive</option>
            </select>
        </div>
    </div>

    <div class="col-md-1">
        <button class="btn OS-Button" type="button" data-toggle="modal" data-target="#ClientRowFilter" style="width: 100%;">Column Filter
        </button>
    </div>
</div>

<div class="row" style="margin-top: 5px;">
    <div class="col-md-12">
        {!! PageElement::TableControl('clients') !!}
    </div>
</div>

<table id="clients-table" class="table">
    <thead>
    <tr id="head">
        <th class="datatables-invisible-col">ID</th>
        <th>Name</th>
        <th>Main Number</th>
        <th>Contact FName</th>
        <th>Contact LName</th>
        <th>Phone Number</th>
        <th>E-Mail</th>
        @if(app()->make('account')->subdomain === "lls")
            <th>OS Active Date</th>
        @endif
        <th>Last Accessed</th>
        <th>Last Note Date</th>
        <th>Last Note</th>
        <th>Number</th>
        <th>addr1</th>
        <th>addr2</th>
        <th>city</th>
        <th>state</th>
        <th>zip</th>
        <th class="datatables-invisible-col">Type</th>
        <th>Status</th>
        <th class="datatables-invisible-col">category</th>
    </tr>
    </thead>
    <tfoot style="visibility: hidden;">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Main Number</th>
        <th>Contact FName</th>
        <th>Contact LName</th>
        <th>Phone Number</th>
        <th>E-Mail</th>
        @if(app()->make('account')->subdomain === "lls")
            <th>OS Active Date</th>
        @endif
        <th>Last Accessed</th>
        <th>Last Note Date</th>
        <th>Last Note</th>
        <th>Number</th>
        <th>addr1</th>
        <th>addr2</th>
        <th>city</th>
        <th>state</th>
        <th>zip</th>
        <th>Type</th>
        <th>Status</th>
        <th>category</th>
    </tr>
    </tfoot>
    <tbody>

    </tbody>
</table>

<div class="modal fade" id="ClientRowFilter" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Row Filter</h4>
            </div>
            <div class="modal-body">
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Name:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="name" data-type="client" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Main Number:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="clients.phonenumber" data-type="client" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Contact FName:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="clientcontacts.firstname" data-type="client" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Contact LName:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="clientcontacts.lastname" data-type="client" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Phone Number:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="clientcontacts.officenumber" data-type="client" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Email:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="clientcontacts.email" data-type="client" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Last Accessed:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="last_accessed" data-type="client" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Last Note Date:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="last_note_date" data-type="client" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Last Note:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="last_note" data-type="client" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Number:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="address.number" data-type="client" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Addr1:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="address.address1" data-type="client" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Addr2:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="address.address2" data-type="client" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">City:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="address.city" data-type="client" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">State:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="address.state" data-type="client" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Zip:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="address.zip" data-type="client" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Status:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="status" data-type="client" checked>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Save & Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        // DataTable
        window.clientstable = $('#clients-table').DataTable({
            "order": [[ 0, "desc" ]],
            "language": {
                "emptyTable": "No Data"
            },
            "processing": true,
            "serverSide": true,
            "deferRender ": true,
            "ajax": "/Clients/Json",
            "pageLength": 10,
            "drawCallback": function(){PageinateUpdate(window.clientstable.page.info(), $('#clients-next-page'), $('#clients-previous-page'),$('#clients-tableInfo'));},
            "columns": [
                { "data": "id", "name": "clients.id" },
                { "data": "name", "name": "name", "searchable": true, "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {$(nTd).html("<a href='../Clients/View/"+oData.id+"'>"+oData.name+"</a>");}},
                { "data": "phonenumber", "name": "clients.phonenumber",  "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {$(nTd).html("<a href='tel:"+oData.phonenumber+"'>"+oData.phonenumber+"</a>");}},
                { "data": "firstname", "name": "clientcontacts.firstname", "searchable": true  },
                { "data": "lastname", "name": "clientcontacts.lastname", "searchable": true  },
                { "data": "phone_number", "name": "clientcontacts.officenumber", "searchable": false, "orderable": false, "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) { if(oData.phone_number_raw == 'None'){$(nTd).html("No Primary Contact Set");}else{$(nTd).html("<a href='tel:"+oData.phone_number_raw+"'>"+oData.phone_number+"</a>");}}},
                    { "data": "email", "name":"clientcontacts.email", "searchable": false, "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) { $(nTd).html("<a data-toggle='modal' href='#send-popup-compose-email-modal' data-recipient-id='"+oData.id+"' data-client-contact-id='"+oData.primarycontact_id+"' data-mail='"+oData.email+"' class='email'>"+oData.email+"</a>");}},
                    @if(app()->make('account')->subdomain === "lls")
                { "data": "acctive_date", "searchable": false, "orderable": false },
                    @endif
                { "data": "last_accessed", "name": "last_accessed", "searchable": false, "orderable": false },
                { "data": "last_note_date", "name": "last_note_date", "searchable": false, "orderable": false },
                { "data": "last_note", "name": "last_note", "searchable": false, "orderable": false, "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) { $(nTd).html("<a data-toggle='modal' href='#ShowNote' data-note='"+oData.LastNoteContent+"' class='button'>"+oData.LastNote+"</a>");}},
                { "data": "number", "name": 'address.number', "searchable": true},
                { "data": "address1", "name": 'address.address1', "searchable": true},
                { "data": "address2", "name": 'address.address2', "searchable": true},
                { "data": "city", "name": 'address.city', "searchable": true},
                { "data": "state", "name": 'address.state', "searchable": true},
                { "data": "zip", "name": 'address.zip', "searchable": true},
                { "data": "type", "name": "type", "searchable": false, "orderable": false},
                { "data": "status", "name": "status", "searchable": false, "orderable": false},
                { "data": "category", "name": "category", "searchable": true, "orderable": false}
            ],
            "columnDefs": [
                {
                    "targets": "datatables-invisible-col",
                    "visible": false
                }
            ],
        });

        $( "#clients-previous-page" ).click(function() {
            window.clientstable.page( "previous" ).draw('page');
        });

        $( "#clients-next-page" ).click(function() {
            window.clientstable.page( "next" ).draw('page');
        });

        $('#client-search').on( 'keyup change', function () {
            window.clientstable.search( this.value ).draw();
        });

        $('#client-category-search').on( 'keyup change', function () {
            if(this.value === "all"){
                window.clientstable
                    .columns( "category:name" )
                    .search( "" , true)
                    .draw();
            }else if(this.value === "none"){
                window.clientstable
                    .columns( "category:name" )
                    .search( "^$", true, false, true)
                    .draw();
            }else{
                window.clientstable
                    .columns( "category:name" )
                    .search( "^" + $(this).val() + "$", true, false, true)
                    .draw();
            }

        });

        $('#client-length').on( 'change', function () {
            window.clientstable.page.len( this.value ).draw();
        });

        $('#client-status').on( 'keyup change', function () {
            if(this.value === "all"){
                window.clientstable
                    .columns( "status:data" )
                    .search( "" , true)
                    .draw();
            }else{
                window.clientstable
                    .columns( "status:data" )
                    .search( "^" + $(this).val() + "$", true, false, true)
                    .draw();
            }
        });

        $('#client-status').change();

        $('#clients-table').css('width', '100%');

        @foreach(Auth::user()->getHomeColOptions('client') as $key => $value)
            @if($value === "0")
            $tablecol = window.clientstable.column( "{{ $key }}:name" );
            $tablecol.visible(false);

            $('.col-filter-check[data-col="{{ $key }}"][data-type="client"]').bootstrapToggle('destroy');
            $('.col-filter-check[data-col="{{ $key }}"][data-type="client"]').prop('checked', false);
            $('.col-filter-check[data-col="{{ $key }}"][data-type="client"]').bootstrapToggle();

            @endif
        @endforeach

    });
</script>