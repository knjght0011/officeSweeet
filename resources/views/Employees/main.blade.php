@extends('master')

@section('content')
<style>
.dataTables_filter {
    display: none; 
}
.dataTables_length {
    display: none; 
}
.dataTables_info {
    display: none; 
}
.dataTables_paginate {
    display: none; 
}
</style>

<h3 style="margin-top: 10px;">Team/Staff Home</h3>

@desktop
<div class="row">
    <div class="col-md-5">
        <a id="link" href="/Employees/Add"><button class="btn OS-Button btn-sm" type="button">Add Team/Staff</button></a>
        <a id="link" href="/Training"><button class="btn OS-Button btn-sm" type="button">Training by Department</button></a>
    </div>

    @if(Auth::user()->hasPermission("payroll_permission") )
        @if($payroll != null)
        <div id="employee-info" style="float: left; margin-right: 10px; padding-top: 5px; font-size: 22px; font-weight: bold;">
            Next Payroll End Date: {{$payroll->end->toDateString()}}
        </div>
        @endif
    @endif

</div>
@elsedesktop

<div class="sidebar-nav" >
    <div class="navbar navbar-default" role="navigation" style="background-color: #eee; border: none; font-weight: bold; margin-bottom: 10px;">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#additemmenu">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <span class="visible-xs navbar-brand">Add New Contact</span>
        </div>
        <div class="navbar-collapse collapse sidebar-navbar-collapse" id="additemmenu">
            <ul class="nav navbar-nav">
                <li><a id="link" href="/Employees/Add">New Team/Staff</a></li>
                <li><a id="link" href="/Training">Training by Department</a></li>
            </ul>
        </div>
    </div>
</div>
@enddesktop

<div class="row" style="margin-top: 20px;">
    @desktop
    <div style="float:left; width: 18em;  margin-left: 15px;">
    @elsedesktop
    <div class="col-md-6">
    @enddesktop
        <div class="input-group ">   
            <span class="input-group-addon" for="search"><div style="width: 7em;">Search:</div></span>
            <input id="search" name="search" type="text" placeholder="" value="" class="form-control" data-validation-label="Search" data-validation-required="false" data-validation-type="">
        </div>
    </div>

    @desktop
    <div style="float:left; width: 18em; margin-left: 20px;" id="department-search-container">
    @elsedesktop
    <div class="col-md-6" id="department-search-container">
    @enddesktop
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

    @desktop
    <div style="float:left; width: 18em; margin-left: 20px;">
    @elsedesktop
    <div class="col-md-6">
    @enddesktop
            <div class="input-group ">
            <span class="input-group-addon" for="length"><div style="width: 7em;">Show:</div></span>
            <select id="length" name="length" type="text" placeholder="choice" class="form-control">
                <option value="10">10 entries</option>
                <option value="25">25 entries</option>
                <option value="50">50 entries</option>
                <option value="100">100 entries</option>
            </select>
        </div>
    </div>

    @desktop
    <div style="float:left; width: 18em; margin-left: 20px;">
    @elsedesktop
    <div class="col-md-6">
    @enddesktop
        <div class="input-group">
            <span class="input-group-addon" for="status"><div style="width: 7em;">Status:</div></span>
            <select id="status" name="status" type="text" placeholder="choice" class="form-control">
                <option value="all">All</option>
                <option value="Active" selected>Active</option>
                <option value="Inactive">Inactive</option>
            </select>
        </div>
    </div>

</div>

<div class="row" style="margin-top: 15px;">

    <div class="col-md-2">
        <button id="previous-page" name="previous-page" type="button" class="btn OS-Button" style="width: 100%;">Previous</button>
    </div>
    <div class="col-md-8" id="tableInfo" style="text-align: center;">
        
    </div>
    <div class="col-md-2">
        <button id="next-page" name="next-page" type="button" class="btn OS-Button" style="width: 100%;">Next</button>
    </div>

</div>

<div id="employeecontainer">
    <table id="employeetable" class="table">
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
            @if(Auth::user()->hasPermission("employee_permission") )
            @foreach(UserHelper::GetAllUsers() as $employee1)
            <tr>
                <td>{{ $employee1->id }}</td>
                <td>{{ $employee1->employeeid }}</td>
                <td><a id="link" href="/Employees/View/{{ $employee1->id }}">{{ $employee1->firstname }} {{ $employee1->middlename }} {{ $employee1->lastname }}</a></td>
                <td>{!! PageElement::EmailLink($employee1->email) !!}</td>
                <td><a href="tel:{{ $employee1->phonenumber }}">{{ $employee1->phonenumber }}</a></td>
                <td>{{ $employee1->department }}</td>
                @if($employee1->canlogin == 1)
                    <td><span class="input-group-addon success"><span class="glyphicon glyphicon-ok"></span></span></td>
                @else
                    <td><span class="input-group-addon danger"><span class="glyphicon glyphicon-remove"></span></span></td>
                @endif
                <td>{{ $employee1->typeword() }}</td>
                <td>{{ $employee1->getDeleted() }}</td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
</div>


<script> 
$(document).ready(function() {

    $('#status').on( 'keyup change', function () {
        if(this.value === "all"){
            employeetable
                .columns( "status:name" )
                .search( "" , true)
                .draw();
        }else{
            employeetable
                .columns( "status:name" )
                .search( "^" + $(this).val() + "$", true, false, true)
                .draw();
        }

        UpdatePageinate(employeetable.page.info());

    });

    $('#department-search').on( 'keyup change', function () {
        if(this.value === "all"){
            employeetable
                .columns( "department:name" )
                .search( "" , true)
                .draw();
        }else if(this.value === "none"){
            employeetable
                .columns( "department:name" )
                .search( "^$", true, false, true)
                .draw();
        }else{
            employeetable
                .columns( "department:name" )
                .search( "^" + $(this).val() + "$", true, false, true)
                .draw();
        }

        if($('#choice') === "Employee"){
            UpdatePageinate(employeetable.page.info());
        }
    });
    
    $('#search').on( 'keyup change', function () {

        employeetable.search( this.value ).draw();
        UpdatePageinate(employeetable.page.info());
        
    });
    
    $('#length').on( 'change', function () {

        employeetable.page.len( this.value ).draw();
        UpdatePageinate(employeetable.page.info());
        
    });
    
    $( "#previous-page" ).click(function() { 

        employeetable.page( "previous" ).draw('page');
        UpdatePageinate(employeetable.page.info());

    });
    
    $( "#next-page" ).click(function() { 

        employeetable.page( "next" ).draw('page');
        UpdatePageinate(employeetable.page.info());

    });    

    // DataTable
    var employeetable = $('#employeetable').DataTable({
        "pageLength": 10,
        "columns": [
            { "name": "id" },
            { "name": "employee_id" },
            { "name": "name" },
            { "name": "email" },
            { "name": "phone_number" },
            { "name": "department" },
            { "name": "can_login" },
            { "name": "type" },
            { "name": "status" },
        ],
        "columnDefs": [
            {
                "targets": "datatables-invisible-col",
                "visible": false
            }
        ]
    });
    
    $('#choice').change();
    $('#status').change();
});
function UpdatePageinate(info){

    $( "#previous-page" ).prop('disabled', false);
    $( "#next-page" ).prop('disabled', false);
    
    $('#tableInfo').html(
        'Currently showing page '+(info.page+1)+' of '+info.pages+' pages.'
    );
    
    if(info.page === 0){
        $( "#previous-page" ).prop('disabled', true);
    }
    
    if((info.page+1) === info.pages){
        $( "#next-page" ).prop('disabled', true);
    }
}
</script> 
@stop