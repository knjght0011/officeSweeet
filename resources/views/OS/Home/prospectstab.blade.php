@include('Emails.prospectsEmailCompose')
<div class="row" style="margin-top: 20px;">

    <div class="col-md-4">
        <div class="input-group ">
            <span class="input-group-addon" for="prospect-search"><div style="width: 7em;">Search:</div></span>
            <input id="prospect-search" name="prospect-search" type="text" placeholder="" value="" class="form-control">
        </div>
    </div>


    <div class="col-md-3">
        <div class="input-group">
            <span class="input-group-addon" for="prospect-category-search"><div style="width: 7em;">Category:</div></span>
            <select id="prospect-category-search" name="prospect-category-search" class="form-control">
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
            <span class="input-group-addon" for="prospect-length"><div style="width: 7em;">Show:</div></span>
            <select id="prospect-length" name="prospect-length" type="text" placeholder="choice"
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
            <span class="input-group-addon" for="prospect-status"><div style="width: 7em;">Status:</div></span>
            <select id="prospect-status" name="prospect-status" type="text" placeholder="choice"
                    class="form-control">
                <option value="all">All</option>
                <option value="Active" selected>Active</option>
                <option value="Inactive">Inactive</option>
            </select>
        </div>
    </div>

    <div class="col-md-1">
        <button class="btn OS-Button" type="button" data-toggle="modal" data-target="#ProspectRowFilter" style="width: 100%;">Column Filter
        </button>
    </div>
</div>

<div class="row" style="margin-top: 5px;">
    <div class="col-md-12">
        {!! PageElement::TableControl('prospects') !!}
    </div>
</div>

<table id="prospects-table" class="table">
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

<div class="modal fade" id="ProspectRowFilter" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Row Filter</h4>
            </div>
            <div class="modal-body">
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Name:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="name" data-type="prospect" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Main Number:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="clients.phonenumber" data-type="prospect" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Contact FName:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="clientcontacts.firstname" data-type="prospect" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Contact LName:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="clientcontacts.lastname" data-type="prospect" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Phone Number:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="clientcontacts.officenumber" data-type="prospect" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Email:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="clientcontacts.email" data-type="prospect" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Last Accessed:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="last_accessed" data-type="prospect" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Last Note Date:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="last_note_date" data-type="prospect" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Last Note:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="last_note" data-type="prospect" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Number:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="address.number" data-type="prospect" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Addr1:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="address.address1" data-type="prospect" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Addr2:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="address.address2" data-type="prospect" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">City:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="address.city" data-type="prospect" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">State:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="address.state" data-type="prospect" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Zip:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="address.zip" data-type="prospect" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Status:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="status" data-type="prospect" checked>
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
        window.prospectstable = $('#prospects-table').DataTable({
            "order": [[ 0, "desc" ]],
            "language": {
                "emptyTable": "No Data"
            },
            "processing": true,
            "serverSide": true,
            "deferRender ": true,
            "deferLoading": 0,
            "ajax": "/Prospects/Json",
            "pageLength": 10,
            "columns": [
                { "data": "id", "name": "clients.id" },
                { "data": "name", "name": "name", "searchable": true, "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {$(nTd).html("<a href='../Clients/View/"+oData.id+"'>"+oData.name+"</a>");}},
                { "data": "phonenumber", "name": "clients.phonenumber",  "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {$(nTd).html("<a href='tel:"+oData.phonenumber+"'>"+oData.phonenumber+"</a>");}},
                { "data": "firstname", "name": "clientcontacts.firstname", "searchable": true  },
                { "data": "lastname", "name": "clientcontacts.lastname", "searchable": true  },
                { "data": "phone_number", "name": "clientcontacts.officenumber", "searchable": false, "orderable": false, "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) { if(oData.phone_number_raw == 'None'){$(nTd).html("No Primary Contact Set");}else{$(nTd).html("<a href='tel:"+oData.phone_number_raw+"'>"+oData.phone_number+"</a>");}}},
                { "data": "email", "name":"clientcontacts.email", "searchable": false, "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) { $(nTd).html("<a data-toggle='modal' href='#send-popup-compose-email-prospect-tab-choose-modal' data-recipient-id='"+oData.id+"' data-client-contact-id='"+oData.primarycontact_id+"' data-mail='"+oData.email+"' class='email'>"+oData.email+"</a>");}},
                // { "data": "email", "name":"clientcontacts.email", "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) { $(nTd).html("<a href='mailto:"+oData.email+"'>"+oData.email+"</a>");}},
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

        $( "#prospects-previous-page" ).click(function() {
            window.prospectstable.page( "previous" ).draw('page');
            PageinateUpdate(window.prospectstable.page.info(), $('#prospects-next-page'), $('#prospects-previous-page'),$('#prospects-tableInfo'));
        });

        $( "#prospects-next-page" ).click(function() {
            window.prospectstable.page( "next" ).draw('page');
            PageinateUpdate(window.prospectstable.page.info(), $('#prospects-next-page'), $('#prospects-previous-page'),$('#prospects-tableInfo'));
        });

        $('#prospect-search').on( 'keyup change', function () {

            window.prospectstable.search( this.value ).draw();
            PageinateUpdate(window.prospectstable.page.info(), $('#prospects-next-page'), $('#prospects-previous-page'),$('#prospects-tableInfo'));

        });


        $('#prospect-category-search').on( 'keyup change', function () {
            if(this.value === "all"){
                window.prospectstable
                    .columns( "category:name" )
                    .search( "" , true)
                    .draw();
            }else if(this.value === "none"){
                window.prospectstable
                    .columns( "category:name" )
                    .search( "^$", true, false, true)
                    .draw();
            }else{
                window.prospectstable
                    .columns( "category:name" )
                    .search( "^" + $(this).val() + "$", true, false, true)
                    .draw();
            }

            PageinateUpdate(window.prospectstable.page.info(), $('#prospects-next-page'), $('#prospects-previous-page'),$('#prospects-tableInfo'));
        });

        $('#prospect-length').on( 'change', function () {

            window.prospectstable.page.len( this.value ).draw();
            PageinateUpdate(window.prospectstable.page.info(), $('#prospects-next-page'), $('#prospects-previous-page'),$('#prospects-tableInfo'));

        });

        $('#prospect-status').on( 'keyup change', function () {
            if(this.value === "all"){
                window.prospectstable
                    .columns( "status:name" )
                    .search( "" , true)
                    .draw();
            }else{
                window.prospectstable
                    .columns( "status:name" )
                    .search( "^" + $(this).val() + "$", true, false, true)
                    .draw();
            }

            PageinateUpdate(window.prospectstable.page.info(), $('#prospects-next-page'), $('#prospects-previous-page'),$('#prospects-tableInfo'));

        });

        PageinateUpdate(window.prospectstable.page.info(), $('#prospects-next-page'), $('#prospects-previous-page'),$('#prospects-tableInfo'));

        $('#prospects-table').css('width', '100%');

        @foreach(Auth::user()->getHomeColOptions('prospect') as $key => $value)
            @if($value === "0")
            $tablecol = window.prospectstable.column( "{{ $key }}:name" );
            $tablecol.visible(false);

            $('.col-filter-check[data-col="{{ $key }}"][data-type="prospect"]').bootstrapToggle('destroy');
            $('.col-filter-check[data-col="{{ $key }}"][data-type="prospect"]').prop('checked', false);
            $('.col-filter-check[data-col="{{ $key }}"][data-type="prospect"]').bootstrapToggle();
        @endif
        @endforeach
    });
</script>