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

<h3 style="margin-top: 10px;">Clients Home</h3>

@desktop
<div class="row">
    <div class="col-md-5">
    @if(app()->make('account')->plan_name === "SOLO")
        <a id="link" href="/Clients/Add"><button class="btn OS-Button btn-sm" type="button">Add Client</button></a>
    @else
        <a id="link" href="/Clients/Add"><button class="btn OS-Button btn-sm" type="button">Add Client/Prospect</button></a>
    @endif
    </div>

    <div id="client-info" style="float: left; margin-right: 10px; padding-top: 5px; text-align: center; font-size: 22px; font-weight: bold;">
        Receivables: ${{ number_format($recevables , 2, '.', '') }}
    </div>
    <div id="prospect-info" style="float: left; margin-right: 10px; padding-top: 5px; font-size: 22px; font-weight: bold;">
        Open Quotes: ${{ number_format($quotevalue , 2, '.', '') }}
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
                @if(app()->make('account')->plan_name === "SOLO")
                    <li><a id="link" href="/Clients/Add">New Client</a></li>
                @else
                    <li><a id="link" href="/Clients/Add">New Client/Prospect</a></li>
                @endif
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
    <div style="float:left; width: 18em; margin-left: 20px;">
    @elsedesktop
    <div class="col-md-6">
    @enddesktop
        <div class="input-group" style="border-color: black; border-style: solid; border-radius: 6px; border-width: 1px;">
            <span class="input-group-addon" for="choice"><div style="width: 7em; font-weight: bold;">Contact Type:</div></span>
            <select id="choice" name="choice" type="text" placeholder="choice" class="form-control">
                <option value="Client"
                        @if($mode === "clients")
                        selected
                        @endif
                >Client</option>
                @if(app()->make('account')->plan_name !== "SOLO")
                <option value="Prospect"
                        @if($mode === "prospects")
                        selected
                        @endif
                >Prospect</option>
                @endif
            </select>
        </div>
    </div>

    @desktop
    <div style="float:left; width: 18em; margin-left: 20px;" id="client-category-search-container">
    @elsedesktop
    <div class="col-md-6" id="client-category-search-container">
    @enddesktop
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
                <th>Main Number</th>
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
                <th>Main Number</th>
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
            @foreach($clients as $cliento)
            <tr id="client" value="{{ $cliento->id }}">
                <td>
                    {{ $cliento->id }}
                </td>

                <td>
                    <a id="link" href="/Clients/View/{{ $cliento->id }}">
                        {{ $cliento->getName() }}
                        @if($cliento->category != "")
                        ({{ $cliento->category }})
                        @endif
                        {{ $cliento->getNameAddon() }}
                    </a>
                </td>
                <td>
                    <a href="tel:{{ $cliento->phonenumber }}">{{ $cliento->phonenumber }}</a>
                </td>
                <td>
                    @if(is_null($cliento->primarycontact_id))
                        No Primary Contact Set
                    @else
                       {{ $cliento->getPrimaryContactName() }}
                    @endif
                </td>  
                <td>
                    @if(is_null($cliento->primarycontact_id))
                        No Primary Contact Set
                    @else
                        <a href="tel:{{ $cliento->primarycontact->GetprimaryphonenumberRAW() }}">{{ $cliento->primarycontact->Getprimaryphonenumber() }}</a>
                    @endif
                </td>    
                <td>
                    @if(is_null($cliento->primarycontact_id))
                        No Primary Contact Set
                    @else
                        {!! PageElement::EmailLink($cliento->primarycontact->email) !!}
                    @endif
                </td>
                @if(app()->make('account')->subdomain === "lls")
                <td>
                    @if($cliento->getAccount() != null)
                    {{ $cliento->getAccount()->activeLastNoteDate }}
                    @endif
                </td>
                @endif
                <td>
                    @if($cliento->accessed_at === null)
                        Never
                    @else
                        {{ $cliento->accessed_at->toDateString() }}
                    @endif
                </td>
                <td>
                    {{ $cliento->LastNoteDate()}}
                </td>
                <td>
                    {{ $cliento->address->number }} {{ $cliento->address->address1 }} {{ $cliento->address->address2 }} {{ $cliento->address->city }} {{ $cliento->address->state }} {{ $cliento->address->zip }}
                </td>
                <td>
                    {{ $cliento->getStatus() }}
                </td>

                <td>
                    {{ $cliento->getDeleted() }}
                </td>
                <td>
                    {{ $cliento->category }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script> 
$(document).ready(function() {

    $('#choice').on( 'change', function () {

        $("#employeecontainer").css("display","none");
        $("#maincontainer").css("display","Inline");
        $("#maintable").css("width" , "100%");
        maintable
            .columns( "type:name" )
            .search( this.value , true)
            .draw();

        UpdatePageinate(maintable.page.info());

    });

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

    $('#client-category-search').on( 'keyup change', function () {
        if(this.value === "all"){
            maintable
                .columns( "category:name" )
                .search( "" , true)
                .draw();
        }else if(this.value === "none"){
            maintable
                .columns( "category:name" )
                .search( "^$", true, false, true)
                .draw();
        }else{
            maintable
                .columns( "category:name" )
                .search( "^" + $(this).val() + "$", true, false, true)
                .draw();
        }

        if($('#choice') === "Client"){
            UpdatePageinate(maintable.page.info());
        }
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
        "serverSide": true,
        "pageLength": 10,
        "columns": [
            { "name": "id" },
            { "name": "name" },
            { "name": "mainnumber" },
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