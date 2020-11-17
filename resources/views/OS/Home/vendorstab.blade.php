<div class="row" style="margin-top: 20px;">

    <div class="col-md-4">
        <div class="input-group ">
            <span class="input-group-addon" for="vendor-search"><div style="width: 7em;">Search:</div></span>
            <input id="vendor-search" name="vendor-search" type="text" placeholder="" value="" class="form-control">
        </div>
    </div>


    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="vendor-category-search"><div style="width: 7em;">Category:</div></span>
            <select id="vendor-category-search" name="vendor-category-search" class="form-control">
                <option value="all" selected>All</option>
                @foreach(\App\Helpers\OS\Client\ClientHelper::AllVendorCategorys() as $department)
                    <option value="{{ $department }}">{{ $department }}</option>
                @endforeach
                <option value="none">None</option>
            </select>
        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group ">
            <span class="input-group-addon" for="vendor-length"><div style="width: 7em;">Show:</div></span>
            <select id="vendor-length" name="vendor-length" type="text" placeholder="choice"
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
            <span class="input-group-addon" for="vendor-status"><div style="width: 7em;">Status:</div></span>
            <select id="vendor-status" name="vendor-status" type="text" placeholder="choice"
                    class="form-control">
                <option value="all">All</option>
                <option value="Active" selected>Active</option>
                <option value="Inactive">Inactive</option>
            </select>
        </div>
    </div>

    <div class="col-md-1">
        <button class="btn OS-Button" type="button" data-toggle="modal" data-target="#VendorRowFilter" style="width: 100%;">Column Filter
        </button>
    </div>
</div>

<div class="row" style="margin-top: 5px;">
    <div class="col-md-12">
        {!! PageElement::TableControl('vendors') !!}
    </div>
</div>

<table id="vendors-table" class="table">
    <thead>
    <tr id="head">
        <th class="datatables-invisible-col">ID</th>
        <th>Name</th>
        <th>Contact FName</th>
        <th>Contact LName</th>
        <th>Phone Number</th>
        <th>E-Mail</th>
        <th>Number</th>
        <th>addr1</th>
        <th>addr2</th>
        <th>city</th>
        <th>state</th>
        <th>zip</th>
        <th>Status</th>
        <th class="datatables-invisible-col">category</th>
    </tr>
    </thead>
    <tfoot style="visibility: hidden;">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Contact FName</th>
        <th>Contact LName</th>
        <th>Phone Number</th>
        <th>E-Mail</th>
        <th>Number</th>
        <th>addr1</th>
        <th>addr2</th>
        <th>city</th>
        <th>state</th>
        <th>zip</th>
        <th>Status</th>
        <th>category</th>
    </tr>
    </tfoot>
    <tbody>

    </tbody>
</table>

<div class="modal fade" id="VendorRowFilter" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Row Filter</h4>
            </div>
            <div class="modal-body">
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Name:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="name" data-type="vendor" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Primary Contact:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="primary_contact" data-type="vendor" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Phone Number:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="phone_number" data-type="vendor" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Email:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="email" data-type="vendor" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Number:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="address.number" data-type="vendor" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Addr1:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="address.address1" data-type="vendor" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Addr2:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="address.address2" data-type="vendor" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">City:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="address.city" data-type="vendor" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">State:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="address.state" data-type="vendor" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Zip:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="address.zip" data-type="vendor" checked>
                </div>

                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Status:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="status" data-type="vendor" checked>
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
        window.vendorstable = $('#vendors-table').DataTable({
            "order": [[ 0, "desc" ]],
            "language": {
                "emptyTable": "No Data"
            },
            "processing": true,
            "serverSide": true,
            "deferRender ": true,
            "deferLoading": 0,
            "ajax": "/Vendors/Json",
            "pageLength": 10,
            "columns": [
                { "data": "id", "name": "vendors.id" },
                { "data": "name", "name": "name", "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {$(nTd).html("<a href='../Vendors/View/"+oData.id+"'>"+oData.name+"</a>");}},
                { "data": "firstname", "name": "vendorcontacts.firstname", "searchable": true  },
                { "data": "lastname", "name": "vendorcontacts.lastname", "searchable": true  },
                { "data": "phonenumber", "name": "vendorcontacts.officenumber", "searchable": false, "orderable": false, "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) { if(oData.phone_number_raw == 'None'){$(nTd).html("No Primary Contact Set");}else{$(nTd).html("<a href='tel:"+oData.phone_number_raw+"'>"+oData.phone_number+"</a>");}}},
                { "data": "email", "name":"vendorcontacts.email", "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) { $(nTd).html("<a href='mailto:"+oData.email+"'>"+oData.email+"</a>");}},
                { "data": "number", "name": 'address.number', "searchable": true},
                { "data": "address1", "name": 'address.address1', "searchable": true},
                { "data": "address2", "name": 'address.address2', "searchable": true},
                { "data": "city", "name": 'address.city', "searchable": true},
                { "data": "state", "name": 'address.state', "searchable": true},
                { "data": "zip", "name": 'address.zip', "searchable": true},
                { "data": "status", "name": "status", "searchable": false, "orderable": false},
                { "data": "category", "name": "category", "searchable": true, "orderable": false }
            ],
            "columnDefs": [
                {
                    "targets": "datatables-invisible-col",
                    "visible": false
                }
            ],
        });

        $( "#vendors-previous-page" ).click(function() {
            window.vendorstable.page( "previous" ).draw('page');
            PageinateUpdate(window.vendorstable.page.info(), $('#vendors-next-page'), $('#vendors-previous-page'),$('#vendors-tableInfo'));
        });

        $( "#vendors-next-page" ).click(function() {
            window.vendorstable.page( "next" ).draw('page');
            PageinateUpdate(window.vendorstable.page.info(), $('#vendors-next-page'), $('#vendors-previous-page'),$('#vendors-tableInfo'));
        });

        $('#vendor-search').on( 'keyup change', function () {

            window.vendorstable.search( this.value ).draw();
            PageinateUpdate(window.vendorstable.page.info(), $('#vendors-next-page'), $('#vendors-previous-page'),$('#vendors-tableInfo'));

        });

        $('#vendor-category-search').on( 'keyup change', function () {
            if(this.value === "all"){
                window.vendorstable
                    .columns( "category:name" )
                    .search( "" , true)
                    .draw();
            }else if(this.value === "none"){
                window.vendorstable
                    .columns( "category:name" )
                    .search( "^$", true, false, true)
                    .draw();
            }else{
                window.vendorstable
                    .columns( "category:name" )
                    .search( "^" + $(this).val() + "$", true, false, true)
                    .draw();
            }

            PageinateUpdate(window.vendorstable.page.info(), $('#vendors-next-page'), $('#vendors-previous-page'),$('#vendors-tableInfo'));

        });

        $('#vendor-length').on( 'change', function () {

            window.vendorstable.page.len( this.value ).draw();
            PageinateUpdate(window.vendorstable.page.info(), $('#vendors-next-page'), $('#vendors-previous-page'),$('#vendors-tableInfo'));

        });

        $('#vendor-status').on( 'keyup change', function () {
            if(this.value === "all"){
                window.vendorstable
                    .columns( "status:name" )
                    .search( "" , true)
                    .draw();
            }else{
                window.vendorstable
                    .columns( "status:name" )
                    .search( "^" + $(this).val() + "$", true, false, true)
                    .draw();
            }

            PageinateUpdate(window.vendorstable.page.info(), $('#vendors-next-page'), $('#vendors-previous-page'),$('#vendors-tableInfo'));

        });

        PageinateUpdate(window.vendorstable.page.info(), $('#vendors-next-page'), $('#vendors-previous-page'),$('#vendors-tableInfo'));

        $('#vendors-table').css('width', '100%');

        @foreach(Auth::user()->getHomeColOptions('vendor') as $key => $value)
            @if($value === "0")
                $tablecol = window.vendorstable.column( "{{ $key }}:name" );
                $tablecol.visible(false);

                $('.col-filter-check[data-col="{{ $key }}"][data-type="vendor"]').bootstrapToggle('destroy');
                $('.col-filter-check[data-col="{{ $key }}"][data-type="vendor"]').prop('checked', false);
                $('.col-filter-check[data-col="{{ $key }}"][data-type="vendor"]').bootstrapToggle();
            @endif
        @endforeach
    });
</script>