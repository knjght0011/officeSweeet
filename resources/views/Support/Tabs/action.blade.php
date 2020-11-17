<div class="col-md-4">

    <div class="list-group">
        <a class="list-group-item menuitem" href="/Reciepts/New/0" >Add Receipt/Expense</a>
        @if (isset($client))
            <a class="list-group-item menuitem" href="/Checks/New/client/{{ $client->id }}">Write Check</a>
        @else
            @if (isset($vendor))
                <a class="list-group-item menuitem" href="/Checks/New/vendor/{{ $vendor->id }}">Write Check</a>
            @else
                <a class="list-group-item menuitem" href="/Checks/New/" >Write Check</a>
            @endif
        @endif

        @if(Auth::user()->hasPermission('deposits_permission'))
            <a class="list-group-item menuitem" href="#"  data-toggle="modal" data-target="#AddMiscDepositModel">Add Misc Deposit</a>
        @endif

        @if(Auth::user()->hasPermissionMulti('multi_assets_permission', 3))
            <a class="list-group-item menuitem" href="/AssetLiability" >Manage Stock, Inventory, Products/Services & Assets</a>
        @else
            @if(Auth::user()->hasPermissionMulti('multi_assets_permission', 2))
                <a class="list-group-item menuitem" href="/AssetLiability" >Manage Stock, Inventory & Products/Services</a>
            @else
                @if(Auth::user()->hasPermissionMulti('multi_assets_permission', 1))
                    <a class="list-group-item menuitem" href="/AssetLiability" >Manage Stock</a>
                @endif
            @endif
        @endif

        @if(Auth::user()->hasPermission('reporting_permission'))
            <a class="list-group-item menuitem" href="/Reporting/Interactive/Quotes">Show All Open Quotes</a>
            <a class="list-group-item menuitem" href="/Reporting/Interactive/Invoices">Show All Outstanding Invoices</a>
        @endif

        @if(Auth::user()->hasPermission('bulk_email_permission'))
            <a class="list-group-item menuitem" href="/Email/Overview">Manage Group Emails</a>
        @else
            <a class="list-group-item menuitem" href="/Email/Overview">Manage Emails</a>
        @endif

        @if(Auth::user()->hasPermission("vendor_permission") )
            <a class="list-group-item menuitem" href="/PurchaseOrders" >Manage Purchase Orders</a>
        @endif
    </div>
</div>
<div class="col-md-4">

    <div class="list-group">
        @if(Auth::user()->hasPermission("client_permission") )
            <a class="list-group-item menuitem" href="/Home/Clients" >{{ TextHelper::GetText("Client") }} Home</a>
            <a class="list-group-item menuitem" href="/Home/Prospects" >Prospect Home</a>
        @endif
        @if(Auth::user()->hasPermission("vendor_permission") )
            <a class="list-group-item menuitem" href="/Home/Vendors" >Vendor Home</a>
        @endif
        @if(Auth::user()->hasPermission("employee_permission") )
            <a class="list-group-item menuitem" href="/Home/Employees" >Team/Staff Home</a>
        @endif
            
        <a class="list-group-item menuitem" href="#"  id="togglecalc" ><span class="glyphicon glyphicon glyphicon-th"></span> Calculator</a></a>
        <a class="list-group-item menuitem toggletimer" @if(isset($client)) data-clientid="{{ $client->id }}" @endif href="#" ><span class="glyphicon glyphicon glyphicon-th"></span> Timer</a>
    </div>    
</div>
<div class="col-md-4">

    <div class="list-group">
        @if (isset($vendor))
        
        <a class="list-group-item menuitem" href="/Documents/List/vendor/{{ $vendor->id }}" >Create New Document</a>
        <a class="list-group-item menuitem" href="" onclick="load_add_note_modal()" >Add Note</a>
        <a class="list-group-item menuitem" href="" onclick="load_primay_contact_modal()" >Change Primary Contact</a>
        <a class="list-group-item menuitem" href="/Vendors/Edit/{{ $vendor->id }}"  >Edit Vendor Details</a>
        <a class="list-group-item menuitem" href="/PurchaseOrders/New/{{ $vendor->id }}">New Purchase Order</a>
        <!--<a class="list-group-item menuitem" href="#" data-toggle="modal" data-target="#fileupload-modal">Upload File</a> Not working unknown why-->
        @endif
        
        @if(isset($client))
        <a class="list-group-item menuitem" href="/Documents/List/client/{{ $client->id }}">Create New Document</a>
        <a class="list-group-item menuitem" href="/Quote/New/{{ $client->id }}">New Quote/Invoice</a>
        <a class="list-group-item menuitem" href="" onclick="load_add_note_modal()" >Add Note</a>
        <a class="list-group-item menuitem" href="" onclick="load_primay_contact_modal()" >Change Primary Contact</a>
        <a class="list-group-item menuitem" href="/Clients/Edit/{{ $client->id }}">Edit Client Details</a>
        <!--<a class="list-group-item menuitem" href="#" data-toggle="modal" data-target="#fileupload-modal">Upload File</a> Not working unknown why-->
            @if(app()->make('account')->plan_name === "BROKER")
                @if($client->getStatus() === "Prospect")
                <a class="list-group-item menuitem" href="/Signup/{{ $client->id }}">Signup to OS</a>
                @endif
            @endif
        @endif
        
        @if (isset($employee))
        <a class="list-group-item menuitem" href="/Checks/New/employee/{{ $employee->id }}">Write Check</a>
        <a class="list-group-item menuitem" href="/Employees/Edit/{{ $employee->id }}">Edit Team/Staff Details</a>
        <a class="list-group-item menuitem" href="/Documents/List/employee/{{ $employee->id }}">Create New Document</a>
        <a class="list-group-item menuitem" href="" onclick="load_add_note_modal()" >Add Note</a>
        @endif
        
        @if (Request::is('Journal/View*'))
        <a class="list-group-item menuitem" href="/Journal/MonthEnd/Summery" >Month End Summery</a>
                @if(Auth::user()->hasPermission('deposits_permission'))
                    <a class="list-group-item menuitem" href="#"  data-toggle="modal" data-target="#AddClientDepositModel" data-clientid="2">Add Client Deposit</a>
                @endif
        @endif
        
    </div>    
</div>

<script>
$(document).ready(function() {
    $('.menuitem').click(function() {
        $('#ShowHelpHub').modal('hide');
    });
});
</script>
        