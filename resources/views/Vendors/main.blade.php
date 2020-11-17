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

<h3 style="margin-top: 10px;">Vendors Home</h3>

@desktop
<div class="row">
    <div class="col-md-5">
        <a id="link" href="/Vendors/Add"><button class="btn OS-Button btn-sm" type="button">Add Vendor</button></a>
    </div>

    <div id="vendor-info" style="float: left; margin-right: 10px; padding-top: 5px; font-size: 22px; font-weight: bold;">
        Payables: ${{ number_format($unprintedchecks , 2, '.', '') }}
    </div>

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
                <li><a id="link" href="/Vendors/Add">New Vendor</a></li>
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
    <div style="float:left; width: 18em; margin-left: 20px;" id="vendor-category-search-container">
    @elsedesktop
    <div class="col-md-6" id="vendor-category-search-container">
    @enddesktop
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

<div id="maincontainer">

    <table id="maintable" class="table">
        <thead>
            <tr id="head">
                <th class="datatables-invisible-col">ID</th>
                <th>Name</th>
                <th>Primary Contact</th>
                <th>Phone Number</th>
                <th>E-Mail</th>
                @if(app()->make('account')->subdomain === "lls")
                <th>OS Active Date</th>
                @endif
                <th>Last Accessed</th>
                <th>Last Note</th>
                <th>Address</th>
                <th class="datatables-invisible-col">Type</th>
                <th>Status</th>
                <th class="datatables-invisible-col">category</th>
            </tr>
        </thead>
        <tfoot style="visibility: hidden;">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Primary Contact</th>
                <th>Phone Number</th>
                <th>E-Mail</th>
                @if(app()->make('account')->subdomain === "lls")
                <th>OS Active Date</th>
                @endif
                <th>Last Accessed</th>
                <th>Last Note</th>
                <th>Address</th>
                <th>Type</th>
                <th>Status</th>
                <th>category</th>
            </tr>
        </tfoot>
        <tbody>
            @foreach($vendors as $vendor1)
            <tr>
                <td>
                    {{ $vendor1->id }}
                </td>
                <td>
                    <a id="link" href="/Vendors/View/{{ $vendor1->id }}">
                        {{ $vendor1->getName() }}                         
                    </a>
                </td>
                <td>
                    @if(is_null($vendor1->primarycontact_id))
                        No Primary Contact Set
                    @else
                       {{ $vendor1->getPrimaryContactName() }}
                    @endif
                </td>                 
                <td>
                    @if(is_null($vendor1->primarycontact_id))
                        No Primary Contact Set
                    @else
                        <a href="tel:{{ $vendor1->primarycontact->GetprimaryphonenumberRAW() }}">{{ $vendor1->primarycontact->Getprimaryphonenumber() }}</a>
                    @endif
                </td>  
                <td>
                    @if(is_null($vendor1->primarycontact_id))
                        No Primary Contact Set
                    @else
                        {!! PageElement::EmailLink($vendor1->primarycontact->email) !!}
                    @endif
                </td>
                @if(app()->make('account')->subdomain === "lls")
                <td>

                </td>
                @endif
                <td>

                </td>
                <td>

                </td>
                <td>
                    {{ $vendor1->address->number }} {{ $vendor1->address->address1 }} {{ $vendor1->address->address2 }} {{ $vendor1->address->city }} {{ $vendor1->address->state }} {{ $vendor1->address->zip }}
                </td>
                <td>
                    Vendor
                </td>

                <td>
                    {{ $vendor1->getDeleted() }}
                </td>
                <td>
                    {{ $vendor1->category }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script> 
$(document).ready(function() {

    $('#status').on( 'keyup change', function () {
        if(this.value === "all"){
            maintable
                .columns( "status:name" )
                .search( "" , true)
                .draw();
        }else{
            maintable
                .columns( "status:name" )
                .search( "^" + $(this).val() + "$", true, false, true)
                .draw();

        }

        UpdatePageinate(maintable.page.info());

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


        UpdatePageinate(employeetable.page.info());

    });
    
    $('#search').on( 'keyup change', function () {

        maintable.search( this.value ).draw();
        UpdatePageinate(maintable.page.info());

    });
    
    $('#length').on( 'change', function () {

        maintable.page.len( this.value ).draw();
        UpdatePageinate(maintable.page.info());

    });
    
    $( "#previous-page" ).click(function() {

        maintable.page( "previous" ).draw('page');
        UpdatePageinate(maintable.page.info());

    });
    
    $( "#next-page" ).click(function() {

        maintable.page( "next" ).draw('page');
        UpdatePageinate(maintable.page.info());

    });    
 
    // DataTable
    var maintable = $('#maintable').DataTable({
        "order": [[ 0, "desc" ]],
        "language": {
            "emptyTable": "No Data"
        },
        "pageLength": 10,
        "columns": [
            { "name": "id" },
            { "name": "name" },
            { "name": "primary_contact" },
            { "name": "phone_number" },
            { "name": "email" },
            @if(app()->make('account')->subdomain === "lls")
            { "name": "acctive_date" },
            @endif
            { "name": "last_accessed" },
            { "name": "last_note" },
            { "name": "address" },
            { "name": "type" },
            { "name": "status" },
            { "name": "category" }
        ],
        "columnDefs": [
            {
                "targets": "datatables-invisible-col",
                "visible": false
            }
        ],
    });

    @desktop
    @elsedesktop
    var StatusCol = maintable.column("status:name");
    StatusCol.visible(false);
    @enddesktop
    
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