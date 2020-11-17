
<style>
    div.dataTables_paginate {text-align: center}
</style>

<div class="row" style="margin-top: 20px;">
    <div style="float:left; width: 10em;  margin-left: 20px;">
        <button style="width:100%;" type="button" class="btn OS-Button" data-toggle="modal" data-target="#addbranchmodal" data-function="add">
          Add Branch
        </button>
    </div> 
    
    <div style="float:left; width: 10em;  margin-left: 20px;">
        <button style="width:100%;" type="button" class="btn OS-Button" data-toggle="modal" data-target="#addbranchmodal" data-function="edit">
          Edit Branch
        </button>
    </div> 
    
    <div style="float:left; width: 18em;  margin-left: 20px;">
        <div class="input-group ">
            <span class="input-group-addon" for="search"><div style="width: 7em;">Search:</div></span>
            <input id="search" name="search" type="text" placeholder="" value="" class="form-control" data-validation-label="Search" data-validation-required="false" data-validation-type="">
        </div>
    </div> 
    
    <div style="float:left; width: 18em; margin-left: 20px;">
        <div class="input-group ">   
            <span class="input-group-addon" for="status"><div style="width: 7em;">Status:</div></span>
            <select id="status" name="status" type="text" placeholder="choice" class="form-control">
                <option value="all" selected>All</option>
                <option value="Main">Main</option>                
                <option value="Active">Active</option>
                <option value="Disabled">Disabled</option>
            </select>
        </div>
    </div> 
      
    <div style="float:left; width: 18em; margin-left: 20px;">
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
    <div style="float:left; width: 18em; margin-left: 20px;">
        <div id="newInfoBranch"></div>
    </div>
</div>


<div id="EnabledBranchesContainer">
    <div class="row">
        <div id="newPaginateBranch"></div> 
    </div>
    
    <table id="EnabledBranches" class="table">
        <thead>
            <tr id="head">
                <th>Address</th>
                <th>Phone Number</th>
                <th>Fax Number</th>
                <th>Status</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Number Of Employee's</th>
            </tr>
        </thead>
        <tfoot>
            <tr id="head">
                <th>Address</th>
                <th class="col-md-1">Phone Number</th>
                <th class="col-md-1">Fax Number</th>
                <th class="col-md-1">Status</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Number Of Employee's</th>                
        </tfoot>
        <tbody>
            @foreach($branches as $branch)
            <tr>
                <td>{{ $branch->number }} {{ $branch->address1 }} {{ $branch->address2 }} {{ $branch->city }} {{ $branch->region }} {{ $branch->state }} {{ $branch->zip }} </td>
                <td>{{ $branch->phonenumber }}</td>
                <td>{{ $branch->faxnumber }}</td>
                <td>{{ $branch->getStatus() }}</td>
                
                <td>{{ $branch->number }}</td><td>{{ $branch->address1 }}</td><td>{{ $branch->address2 }}</td><td>{{ $branch->city }}</td><td>{{ $branch->region }}</td><td>{{ $branch->state }}</td><td>{{ $branch->zip }}</td>
                <td>{{ $branch->id }}</td>
                <td>{{ $branch->numberOfUsers() }}</td>
                <td>{{ $branch->citytax }}</td>
                <td>
                    <a class="btn popovers" href="#" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="{!! $branch->ListOfUsers() !!}" data-html="true">{{ $branch->numberOfUsers() }}</a>
                </td>
            </tr>
            @endforeach
            @foreach($branchestrashed as $branch)
            <tr>
                <td>{{ $branch->number }} {{ $branch->address1 }} {{ $branch->address2 }} {{ $branch->city }} {{ $branch->region }} {{ $branch->state }} {{ $branch->zip }} </td>
                <td>{{ $branch->phonenumber }}</td>
                <td>{{ $branch->faxnumber }}</td>
                <td>{{ $branch->getStatus() }}</td>
                
                <td>{{ $branch->number }}</td><td>{{ $branch->address1 }}</td><td>{{ $branch->address2 }}</td><td>{{ $branch->city }}</td><td>{{ $branch->region }}</td><td>{{ $branch->state }}</td><td>{{ $branch->zip }}</td>
                <td>{{ $branch->id }}</td>
                <td>{{ $branch->numberOfUsers() }}</td>
                <td>{{ $branch->citytax }}</td>
                <td>
                    <a class="btn popovers" href="#" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="{!! $branch->ListOfUsers() !!}" data-html="true">{{ $branch->numberOfUsers() }}</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>    

<script>
$(document).ready(function() {
    $("[data-toggle=popover]").popover({html:true});
    
    
    // DataTable
    var Branches = $('#EnabledBranches').DataTable({
        "columnDefs": [
            { "targets": [4,5,6,7,8,9,10,11,12,14],"visible": false}
        ]
    });
    
    $('#EnabledBranches tbody').on( 'click', 'tr', function () {
        $row = $(this);
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            Branches.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });
    
    $('#status').on( 'keyup change', function () {
        switch(this.value) {
            case "all":
                Branches
                    .columns( 3 )
                    .search( "" , true)
                    .draw();
                break;
            case "Active":
                Branches
                    .columns( 3 )
                    .search( "^" + $(this).val() + "$|^Main$", true, false, true)
                    .draw();
                break;
            default:
                Branches
                    .columns( 3 )
                    .search( "^" + $(this).val() + "$", true, false, true)
                    .draw();
        }        
    });
    
    $('#search').on( 'keyup change', function () {
        Branches.search( this.value )
                .draw();
    });
    
    $('#length').on( 'change', function () {
        Branches.page.len( this.value )
                .draw();
    });    
    
    $( "#EnabledBranchesContainer" ).children().find(".dataTables_filter").css("display", "none");
    $( "#EnabledBranchesContainer" ).children().find(".dataTables_length").css("display", "none");
    
    $("#newPaginateBranch").html($( "#EnabledBranchesContainer" ).children().find(".dataTables_paginate"));
    $("#newInfoBranch").html($( "#EnabledBranchesContainer" ).children().find(".dataTables_info"));    
    
    $('#EnabledBranches').css("width" , "100%");
});

function AddressLookup($zip, $done) {

    $("body").addClass("loading");

    $data = {};
    $data['_token'] = "{{ csrf_token() }}";
    $data['zip'] = $zip;

    $post = $.post("/Address/Lookup", $data);

    $post.done(function ($addressdata) {
        $("body").removeClass("loading");
        switch($addressdata['status']) {
            case "OK":
                PopulateAddressFields($addressdata['data']);
                if($done != null){
                    $done();
                }
                break;
            case "error":
                $.dialog({
                    title: 'Oops..',
                    content: $addressdata['reason']
                });
                break;
            default:
                $.dialog({
                    title: 'Oops...',
                    content: 'Unknown Response from server. Please refresh the page and try again.'
                });
        }
    });

    $post.fail(function () {
        NoReplyFromServer();
    });

    function PopulateAddressFields($addressdata){
        $.each($addressdata, function (index, value) {
            $('#number').prop('disabled', false);
            switch (index) {
                case "postal_code":
                    $('#zip').val(value);
                case "state_province":
                    if (value == "") {
                        $('#state').prop('disabled', false);
                        $('#state').val(value);
                    } else {
                        $('#state').prop('disabled', true);
                        $('#state').val(value);
                    }
                    break;
                case "region":
                    if (value == "") {
                        $('#region').prop('disabled', false);
                        $('#region').val(value);
                    } else {
                        $('#region').prop('disabled', true);
                        $('#region').val(value);
                    }
                    break;
                case "city":
                    if (value == "") {
                        $('#city').prop('disabled', false);
                        $('#city').val(value);
                    } else {
                        $('#city').prop('disabled', true);
                        $('#city').val(value);
                    }
                    break;
                case "address2":
                    if (value == "") {
                        $('#address2').prop('disabled', false);
                        $('#address2').val(value);
                    } else {
                        $('#address2').prop('disabled', true);
                        $('#address2').val(value);
                    }
                    break;
                case "address1":
                    if (value == "") {
                        $('#address1').prop('disabled', false);
                        $('#address1').val(value);
                    } else {
                        $('#address1').prop('disabled', true);
                        $('#address1').val(value);
                    }
                    break;
            }
        });
    }
}

</script> 

@include('ACP.Tabs.CompanyInfo.addbranch')