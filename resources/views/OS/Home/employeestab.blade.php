@include('Emails.employeesTabEmailCompose')
<div class="row" style="margin-top: 20px;">

    <div class="col-md-4">
        <div class="input-group ">
            <span class="input-group-addon" for="employee-search"><div style="width: 7em;">Search:</div></span>
            <input id="employee-search" name="employee-search" type="text" placeholder="" value="" class="form-control">
        </div>
    </div>


    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="department-search"><div style="width: 7em;">Department:</div></span>
            <select id="department-search" name="department-search" class="form-control">
                <option value="all" selected>All</option>
                @foreach(\App\Helpers\EmployeeHelper::AllDepartments() as $department)
                    <option value="{{ $department }}">{{ $department }}</option>
                @endforeach
                <option value="none">None</option>
            </select>
        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group ">
            <span class="input-group-addon" for="employee-length"><div style="width: 7em;">Show:</div></span>
            <select id="employee-length" name="employee-length" type="text" placeholder="choice"
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
            <span class="input-group-addon" for="employee-status"><div style="width: 7em;">Status:</div></span>
            <select id="employee-status" name="employee-status" type="text" placeholder="choice"
                    class="form-control">
                <option value="all">All</option>
                <option value="Active" selected>Active</option>
                <option value="Inactive">Inactive</option>
            </select>
        </div>
    </div>

    <div class="col-md-1">
        <button class="btn OS-Button" type="button" data-toggle="modal" data-target="#EmployeeRowFilter" style="width: 100%;">Column Filter
        </button>
    </div>
</div>

<div class="row" style="margin-top: 5px;">
    <div class="col-md-12">
    {!! PageElement::TableControl('employees') !!}
    </div>
</div>

<table id="employees-table" class="table">
    <thead>
        <tr id="head">
            <th class="datatables-invisible-col">ID</th>
            <th>ID</th>
            <th>Name</th>
            <th>E-Mail</th>
            <th>Phone Number</th>
            <th>Department</th>
            <th>Can Login</th>
            <th>Type</th>
            <th>Status</th>
        </tr>
    </thead>
    <tfoot style="visibility: hidden;">
        <tr>
            <th>ID</th>
            <th>ID</th>
            <th>Name</th>
            <th>E-Mail</th>
            <th>Phone Number</th>
            <th>Department</th>
            <th>Can Login</th>
            <th>Type</th>
            <th>Status</th>
        </tr>
    </tfoot>
    <tbody>
    @foreach(UserHelper::GetAllUsers() as $employee1)
        <tr>
            <td>{{ $employee1->id }}</td>
            <td>{{ $employee1->employeeid }}</td>
            <td><a 
                   href="/Employees/View/{{ $employee1->id }}">{{ $employee1->firstname }} {{ $employee1->middlename }} {{ $employee1->lastname }}</a>
            </td>
            <td>
                <a data-toggle="modal" href='#send-popup-compose-email-employee-tab-modal' data-recipient-id="{{$employee1->id}}" data-client-contact-id="{{$employee1->id}}" data-mail="{{$employee1->email}}" class="email">{{$employee1->email}}</a>
            <td>
                <a href="tel:{{ $employee1->phonenumber }}">{{ $employee1->phonenumber }}</a>
            </td>
            <td>{{ $employee1->department }}</td>
            @if($employee1->canlogin == 1)
                <td><span class="input-group-addon success"><span
                                class="glyphicon glyphicon-ok"></span></span></td>
            @else
                <td><span class="input-group-addon danger"><span
                                class="glyphicon glyphicon-remove"></span></span>
                </td>
            @endif
            <td>{{ $employee1->typeword() }}</td>
            <td>{{ $employee1->getDeleted() }}</td>
        </tr>
    @endforeach
    </tbody>
</table>


<div class="modal fade" id="EmployeeRowFilter" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Row Filter</h4>
            </div>
            <div class="modal-body">
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">ID:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="employee_id" data-type="employee" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Name:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="name" data-type="employee" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Email:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="email" data-type="employee" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Phone Number:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="phone_number" data-type="employee" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Department:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="department" data-type="employee" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Can Login:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="can_login" data-type="employee" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Type:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="type" data-type="employee" checked>
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="status"><div style="width: 15em;">Status:</div></span>
                    <input type="checkbox" name="checkboxes" class="col-filter-check" data-on="Show" data-off="Hide" data-width="100%" data-toggle="toggle" data-col="status" data-type="employee" checked>
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
        window.employeestable = $('#employees-table').DataTable({
            "pageLength": 10,
            "columns": [
                {"name": "id"},
                {"name": "employee_id"},
                {"name": "name"},
                {"name": "email"},
                {"name": "phone_number"},
                {"name": "department"},
                {"name": "can_login"},
                {"name": "type"},
                {"name": "status"},
            ],
            "columnDefs": [
                {
                    "targets": "datatables-invisible-col",
                    "visible": false
                }
            ]
        });

        $( "#employees-previous-page" ).click(function() {
            window.employeestable.page( "previous" ).draw('page');
            PageinateUpdate(window.employeestable.page.info(), $('#employees-next-page'), $('#employees-previous-page'),$('#employees-tableInfo'));
        });

        $( "#employees-next-page" ).click(function() {
            window.employeestable.page( "next" ).draw('page');
            PageinateUpdate(window.employeestable.page.info(), $('#employees-next-page'), $('#employees-previous-page'),$('#employees-tableInfo'));
        });

        $('#employee-search').on( 'keyup change', function () {

            window.employeestable.search( this.value ).draw();
            PageinateUpdate(window.employeestable.page.info(), $('#employees-next-page'), $('#employees-previous-page'),$('#employees-tableInfo'));

        });

        $('#department-search').on( 'keyup change', function () {
            if(this.value === "all"){
                window.employeestable
                    .columns( "department:name" )
                    .search( "" , true)
                    .draw();
            }else if(this.value === "none"){
                window.employeestable
                    .columns( "department:name" )
                    .search( "^$", true, false, true)
                    .draw();
            }else{
                window.employeestable
                    .columns( "department:name" )
                    .search( "^" + $(this).val() + "$", true, false, true)
                    .draw();
            }

            PageinateUpdate(window.employeestable.page.info(), $('#employees-next-page'), $('#employees-previous-page'),$('#employees-tableInfo'));

        });



        $('#employee-length').on( 'change', function () {

            window.employeestable.page.len( this.value ).draw();
            PageinateUpdate(window.employeestable.page.info(), $('#employees-next-page'), $('#employees-previous-page'),$('#employees-tableInfo'));

        });

        $('#employee-status').on( 'keyup change', function () {
            if(this.value === "all"){
                window.employeestable
                    .columns( "status:name" )
                    .search( "" , true)
                    .draw();
            }else{
                window.employeestable
                    .columns( "status:name" )
                    .search( "^" + $(this).val() + "$", true, false, true)
                    .draw();
            }

            PageinateUpdate(window.employeestable.page.info(), $('#employees-next-page'), $('#employees-previous-page'),$('#employees-tableInfo'));

        });
        $('#employee-status').change();

        PageinateUpdate(window.employeestable.page.info(), $('#employees-next-page'), $('#employees-previous-page'),$('#employees-tableInfo'));

        $('#employees-table').css('width', '100%');

        @foreach(Auth::user()->getHomeColOptions('employee') as $key => $value)
            @if($value === "0")
                $tablecol = window.employeestable.column( "{{ $key }}:name" );
                $tablecol.visible(false);

                $('.col-filter-check[data-col="{{ $key }}"][data-type="employee"]').bootstrapToggle('destroy');
                $('.col-filter-check[data-col="{{ $key }}"][data-type="employee"]').prop('checked', false);
                $('.col-filter-check[data-col="{{ $key }}"][data-type="employee"]').bootstrapToggle();
            @endif
        @endforeach
    });
</script>