<div class="row" style="margin-top: 20px;">

    <div class="col-md-4">
        <div class="input-group ">
            <span class="input-group-addon" for="patient-search"><div style="width: 7em;">Search:</div></span>
            <input id="patient-search" name="patient-search" type="text" placeholder="" value="" class="form-control">
        </div>
    </div>


    <div class="col-md-3">
        <div class="input-group">
            <span class="input-group-addon" for="patient-category-search"><div style="width: 7em;">Category:</div></span>
            <select id="patient-category-search" name="patient-category-search" class="form-control">
                <option value="all" selected>All</option>

                <option value="none">None</option>
            </select>
        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group ">
            <span class="input-group-addon" for="patient-length"><div style="width: 7em;">Show:</div></span>
            <select id="patient-length" name="patient-length" type="text" placeholder="choice"
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
            <span class="input-group-addon" for="patient-status"><div style="width: 7em;">Status:</div></span>
            <select id="patient-status" name="patient-status" type="text" placeholder="choice"
                    class="form-control">
                <option value="all">All</option>
                <option value="Active" selected>Active</option>
                <option value="Inactive">Inactive</option>
            </select>
        </div>
    </div>

    <div class="col-md-1">
        <button class="btn OS-Button" type="button" data-toggle="modal" data-target="#PatientRowFilter" style="width: 100%;">Column Filter
        </button>
    </div>
</div>

<div class="row" style="margin-top: 5px;">
    <div class="col-md-12">
        {!! PageElement::TableControl('patients') !!}
    </div>
</div>
<table id="patients-table" class="table">
    <thead>
    <tr>
        <th class="datatables-invisible-col">ID</th>
        <th>Name</th>
        <th>Scheduled</th>
        <th>Mobile Number</th>
        <th>Home Number</th>
        <th>Client</th>
        <th>Comments</th>
    </tr>
    </thead>
    <tfoot style="visibility: hidden;">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Scheduled</th>
        <th>Mobile Number</th>
        <th>Home Number</th>
        <th>Client</th>
        <th>Comments</th>

    </tr>
    </tfoot>
    <tbody>
    @foreach($patients as $pt)
        <tr>
            <td>{{ $pt->id }}</td>
            <td>{{ $pt->firstname }} {{ $pt->lastname }}</td>
            <td>@if($pt->scheduled == 'YES') YES @else NO @endif</td>
            <td><a href="tel:{{ $pt->GetMobile() }}">{{ $pt->GetMobile() }}</a></td>
            <td><a href="tel:{{ $pt->GetHome() }}">{{ $pt->GetHome() }}</a></td>
            <td><a href="/Clients/View/{{ $pt->client_id}}">{{ $pt->client->getName()}}</a></td>
            <td>{{ $pt->comments }}</td>

        </tr>
    @endforeach
    </tbody>
</table>

<div class="modal fade" id="PatientRowFilter" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Row Filter</h4>
            </div>
            <div class="modal-body">
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Name:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="name" data-type="patient" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Scheduled:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="mainnumber" data-type="patient" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Mobile Number:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="primary_contact" data-type="patient" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Home Number:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="phone_number" data-type="patient" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Comments:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="email" data-type="patient" checked>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Save & Close</button>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="patientModel" tabindex="-1" role="dialog" aria-labelledby="patientModel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="">Patient:</h4>
            </div>
            <div class="modal-body">
                <input id="patient-id" name="patient-id" type="text" placeholder="" value="" class="form-control" style="display: none;">
                <div class="input-group ">
                    <span class="input-group-addon" for="patient-firstname"><div class="inputdiv">Firstname:</div></span>
                    <input id="patient-firstname" name="patient-firstname" type="text" placeholder="" value="" class="form-control">
                </div>
                <div class="input-group ">
                    <span class="input-group-addon" for="patient-lastname"><div class="inputdiv">Lastname:</div></span>
                    <input id="patient-lastname" name="patient-lastname" type="text" placeholder="" value="" class="form-control">
                </div>
                <div class="input-group ">
                    <span class="input-group-addon" for="patient-scheduled"><div class="inputdiv">Scheduled:</div></span>
                    <input id="patient-scheduled" name="patient-scheduled" type="checkbox" placeholder="" value="" class="form-control">
                </div>
                <div class="input-group ">
                    <span class="input-group-addon" for="patient-mobilenumber"><div class="inputdiv">Mobile Number:</div></span>
                    <input id="patient-mobilenumber" name="patient-mobilenumber" type="text" placeholder="" value="" class="form-control">
                </div>
                <div class="input-group ">
                    <span class="input-group-addon" for="patient-homenumber"><div class="inputdiv">Home Number:</div></span>
                    <input id="patient-homenumber" name="patient-homenumber" type="text" placeholder="" value="" class="form-control">
                </div>
                <div class="input-group ">
                    <span class="input-group-addon" for="patient-email"><div class="inputdiv">E-Mail:</div></span>
                    <input id="patient-email" name="patient-email" type="text" placeholder="" value="" class="form-control">
                </div>

                <div class="input-group ">
                    <span class="input-group-addon" for="patient-comments"><div class="inputdiv">Comments:</div></span>
                    <textarea id="patient-comments" name="patient-comments" type="text" placeholder="" value="" class="form-control" ></textarea>
                </div>

                <div class="input-group ">
                    <span class="input-group-addon" for="patient-number"><div class="inputdiv">House Name\Number:</div></span>
                    <input id="patient-number" name="patient-number" type="text" placeholder="" class="form-control" required="" disabled>
                </div>

                <div class="input-group ">
                    <span class="input-group-addon" for="patient-address1"><div class="inputdiv">Street:</div></span>
                    <input id="patient-address1" name="patient-address1" type="text" placeholder="Address Line 1" class="form-control" required="" disabled>
                </div>

                <!-- Text input-->
                <div class="input-group ">
                    <span class="input-group-addon" for="patient-address2"><div class="inputdiv">Address Line 2:</div></span>
                    <input id="patient-address2" name="patient-address2" type="text" placeholder="Address Line 2" class="form-control" disabled>
                </div>

                <!-- Text input-->
                <div class="input-group ">
                    <span class="input-group-addon" for="patient-city"><div class="inputdiv">City:</div></span>
                    <input id="patient-city" name="patient-city" type="text" placeholder="City" class="form-control" required="" disabled>
                </div>

                <div class="input-group ">
                    <span class="input-group-addon" for="patient-region"><div class="inputdiv">Region:</div></span>
                    <input id="patient-region" name="patient-region" type="text" placeholder="Region" class="form-control" required="" disabled>
                </div>

                <!-- Text input-->
                <div class="input-group ">
                    <span class="input-group-addon" for="patient-state"><div class="inputdiv">State/Province:</div></span>
                    <input id="patient-state" name="patient-state" type="text" placeholder="State" class="form-control" required="" disabled>
                </div>

                <!-- Text input-->
                <div class="input-group ">
                    <span class="input-group-addon" for="patient-zip"><div class="inputdiv">Postal Code:</div></span>
                    <input id="patient-zip" name="patient-zip" type="text" placeholder="Zip" class="form-control" required="">
                    <span class="input-group-btn">
                    <button id="patient-lookup" name="patient-lookup" type="button" class="btn btn-default">Lookup Address</button>
                </span>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button id="patient-save" type="button" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        // DataTable
        window.patientstable = $('#patients-table').DataTable({
            "order": [[ 0, "desc" ]],
            "language": {
                "emptyTable": "No Data"
            },
            "pageLength": 10,
            "columns": [
                { "name": "id" },
                { "name": "name" },
                { "name": "scheduled" },
                { "name": "mobile_number" },
                { "name": "home_number" },
                { "name": "client" },
                { "name": "comments" }
            ],
            "columnDefs": [
                {
                    "targets": "datatables-invisible-col",
                    "visible": false
                }
            ],
        });

        $( "#patients-previous-page" ).click(function() {
            window.patientstable.page( "previous" ).draw('page');
            PageinateUpdate(window.patientstable.page.info(), $('#patients-next-page'), $('#patients-previous-page'),$('#patients-tableInfo'));
        });

        $( "#patients-next-page" ).click(function() {
            window.patientstable.page( "next" ).draw('page');
            PageinateUpdate(window.patientstable.page.info(), $('#patients-next-page'), $('#patients-previous-page'),$('#patients-tableInfo'));
        });

        $('#patient-search').on( 'keyup change', function () {

            window.patientstable.search( this.value ).draw();
            PageinateUpdate(window.patientstable.page.info(), $('#patients-next-page'), $('#patients-previous-page'),$('#patients-tableInfo'));

        });

        $('#patient-category-search').on( 'keyup change', function () {
            if(this.value === "all"){
                window.patientstable
                    .columns( "category:name" )
                    .search( "" , true)
                    .draw();
            }else if(this.value === "none"){
                window.patientstable
                    .columns( "category:name" )
                    .search( "^$", true, false, true)
                    .draw();
            }else{
                window.patientstable
                    .columns( "category:name" )
                    .search( "^" + $(this).val() + "$", true, false, true)
                    .draw();
            }

            PageinateUpdate(window.patientstable.page.info(), $('#patients-next-page'), $('#patients-previous-page'),$('#patients-tableInfo'));
        });

        $('#patient-length').on( 'change', function () {

            window.patientstable.page.len( this.value ).draw();
            PageinateUpdate(window.patientstable.page.info(), $('#patients-next-page'), $('#patients-previous-page'),$('#patients-tableInfo'));

        });

        $('#patient-status').on( 'keyup change', function () {
            if(this.value === "all"){
                window.patientstable
                    .columns( "status:name" )
                    .search( "" , true)
                    .draw();
            }else{
                window.patientstable
                    .columns( "status:name" )
                    .search( "^" + $(this).val() + "$", true, false, true)
                    .draw();
            }

            PageinateUpdate(window.patientstable.page.info(), $('#patients-next-page'), $('#patients-previous-page'),$('#patients-tableInfo'));

        });
        $('#patient-status').change();

        PageinateUpdate(window.patientstable.page.info(), $('#patients-next-page'), $('#patients-previous-page'),$('#patients-tableInfo'));

        $('#patients-table').css('width', '100%');

        @foreach(Auth::user()->getHomeColOptions('patient') as $key => $value)
                @if($value === "0")
            $tablecol = window.patientstable.column( "{{ $key }}:name" );
        $tablecol.visible(false);

        $('.col-filter-check[data-col="{{ $key }}"][data-type="patient"]').bootstrapToggle('destroy');
        $('.col-filter-check[data-col="{{ $key }}"][data-type="patient"]').prop('checked', false);
        $('.col-filter-check[data-col="{{ $key }}"][data-type="patient"]').bootstrapToggle();

        @endif
        @endforeach
    });
</script>