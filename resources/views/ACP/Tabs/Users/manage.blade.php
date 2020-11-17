<input type="text" style="display:none">
<input type="password" style="display:none">

<br>
<div class="col-md-12">
    <select id="manage-userselect" name="selectbasic" class="form-control userselect" style="height: 100%;"></select>
</div>
<br><br><br>
<div class="col-md-9">

    <div class="row">   
        <label class="col-md-3 control-label" style="margin-top: 5px; text-align: right;" for="email">Email</label>
        <div class="col-md-7" >
            <input id="email" name="email" type="text" value="" class="form-control input-md" required="" autocomplete="off" >
        </div>
        <div class="col-md-2" >
            <button id="saveemail" name="Submit" class="btn btn-default">Update</button>
        </div>
    </div>


    <div class="row">
        <label class="col-md-3 control-label" style="margin-top: 25px; text-align: right;" for="confirmpassword">Branch</label>
        <div class="col-md-7" style="margin-top: 20px">
            <select id="branchselect" name="branchselect" class="form-control">
                @foreach($branches as $branch)
                @if($branch->isDisabled() === false)
                    <option value="{{ $branch->id }}">{{ $branch->number }} - {{ $branch->address1 }} - {{ $branch->address2 }} - {{ $branch->city }} - {{ $branch->region }} - {{ $branch->state }} - {{ $branch->zip }}</option>
                @endif
                @endforeach
            </select>
        </div>
        <div class="col-md-2" style="margin-top: 20px">
            <button id="savebranch" name="Submit" class="btn btn-default">Update</button>
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label class="control-label" for="Delete Account">Delete Account</label>
        <div class="checkbox">
        <label for="checkboxes-0">
            <input type="checkbox" name="locked" id="locked" value="1" >
            Locked
        </label>
        </div>
        <div class="checkbox">
            <label for="checkboxes-1">
                <input type="checkbox" name="disabled" id="disabled" value="1" >
                Disabled
            </label>
        </div>
    </div>  

    <div class="form-group">
        <label class="control-label" for="Delete Account">Permissions</label>
        <div class="checkbox">
            <label for="checkboxes-0">
                <input type="checkbox" name="usermanagement" id="usermanagement" value="1" >
                usermanagement
            </label>
        </div>
        <div class="checkbox">
            <label for="checkboxes-0">
                <input type="checkbox" name="viewacp" id="viewacp" value="1" >
                View ACP
            </label>
        </div>
        <div class="checkbox">
            <label for="checkboxes-1">
                <input type="checkbox" name="checkboxes" id="checkboxes-1" value="1">
                View {{ TextHelper::GetText("Clients") }}
            </label>
        </div>
        <div class="checkbox">
            <label for="checkboxes-2">
                <input type="checkbox" name="checkboxes" id="checkboxes-2" value="1">
                View Vendors
            </label>
        </div>
        <div class="checkbox">
            <label for="checkboxes-3">
                <input type="checkbox" name="checkboxes" id="checkboxes-3" value="1">
                View Employees
            </label>
        </div>
        <div class="checkbox">
            <label for="checkboxes-4">
                <input type="checkbox" name="checkboxes" id="checkboxes-4" value="1">
                View Scheduler
            </label>
        </div>
        <div class="checkbox">
            <label for="checkboxes-5">
                <input type="checkbox" name="checkboxes" id="checkboxes-5" value="1">
                View Templates
            </label>
        </div>
        <div class="checkbox">
            <label for="checkboxes-6">
                <input type="checkbox" name="checkboxes" id="checkboxes-6" value="1">
                View Reporting
            </label>
        </div>


        <div class="pull-right">
            <label class="col-md-4 control-label" for="Submit"></label>
            <div class="col-md-4">
                <button id="savepermissions" name="Submit" class="btn btn-default">Update</button>
            </div>
        </div>
    </div>
</div>


<script>
$( document ).ready(function() {
    var $users;
    var $user;
    
    //GetBranchData();

    $('#manage-userselect').change(function() {       
        $.each($users, function( index, value ) {
            if(value["id"].toString() === $('#manage-userselect').val().toString()) {
                $user = value;
                UsersUpdateForm($user);
            }
        });
    });
    
    window.GetUsersData = function() {
        var get = $.get( "/Users/All", function(  ) { });

        get.done(function( data ) {
            $users = data;
            UpdateUserSelect(data);
        })   
    }
    window.GetUsersData();
    
    $("#saveemail").click(function()
    {
        
        PostEmail($('#manage-userselect').val(), $('#email').val());

    });
    
    $("#savebranch").click(function()
    {
        
        PostBranch($('#manage-userselect').val(), $('#branchselect').val());

    });
        
    $("#savepermissions").click(function()
    {

        if($("#locked").is(":checked")) {
            $locked = 1;
        } else {
            $locked = 0;
        }
        
        if($("#disabled").is(":checked")) {
            $disabled = 1;
        } else {
            $disabled = 0;
        }
        
        if($("#usermanagement").is(":checked")) {
            $usermanagement = 1;
        } else {
            $usermanagement = 0;
        }
        
        PostPermissions($('#manage-userselect').val(), $locked, $disabled, $usermanagement);

    });
});

function GetBranchData() {
    var get = $.get( "/Branches/All", function(  ) { });

    get.done(function( data ) {
        $('#branchselect').empty()
        $.each(data, function( index, value ) {
            var $address = value["number"] + " " + value["address1"] + " " + value["address2"] + " " + value["city"] + " " + value["region"] + " " + value["state"] + " " + value["zip"];
            $('#branchselect')
                .append($("<option></option>")
                    .attr("value",value["id"])
                    .text($address));

        });
    })   
}

function UpdateUserSelect($users) {
    $('.userselect').empty()
    $.each($users, function( index, value ) {
        $('.userselect')
            .append($("<option></option>")
                .attr("value",value["id"])
                .text(value["email"]));

    });

    $( "#manage-userselect" ).trigger( "change" );
}

function UsersUpdateForm($user) {

    $('#email').val($user["email"]);
    if($user["locked"] === 1){
        $('#locked').prop('checked', true);
    }else{
        $('#locked').prop('checked', false);
    }
    if($user["disabled"] === 1){
        $('#disabled').prop('checked', true);
    }else{
        $('#disabled').prop('checked', false);
    }
    if($user["usermanagement"] === 1){
        $('#usermanagement').prop('checked', true);
    }else{
        $('#usermanagement').prop('checked', false);
    }

    $('#branchselect').val($user["branch_id"]);
}

function PostEmail($id, $email) {
    ResetServerValidationErrors();

    post = $.post("/Users/Save/Email",
    {

        _token: "{{ csrf_token() }}",
        id: $id ,
        email: $email,
    });
    
    post.done(function( data ) 
    {
        if (data === "success"){
            alert( "Email address saved" );

            $id = $("#manage-userselect").val();
            window.GetUsersData();
            $("#manage-userselect").val($id.toString()).change(); //should change the select back to the previously selected user(cant seem to make it work for now)

        }else{
            //server validation errors
            ServerValidationErrors(data);
        }
    });

    post.fail(function() {
        alert( "Failed to post email details" );
    }); 
}

function PostBranch($id, $branch) {
    ResetServerValidationErrors();

    post = $.post("/Users/Save/Branch",
    {

        _token: "{{ csrf_token() }}",
        id: $id ,
        branch: $branch,
    });
    
    post.done(function( data ) 
    {
        if (data === "success"){
            alert( "Branch address saved" );                
        }else{
            //server validation errors
            ServerValidationErrors(data);
        }
    });

    post.fail(function() {
        alert( "Failed to post Branch details" );
    });
}

function PostPermissions($id, $locked, $disabled, $usermanagement) {

    ResetServerValidationErrors();

    post = $.post("/Users/Save/Permissions",
    {

        _token: "{{ csrf_token() }}",
        id: $id,
        locked: $locked,
        disabled: $disabled,
        usermanagement: $usermanagement,
    });
    
    post.done(function( data ) 
    {
        if (data === "success"){
            alert( "Permissions saved" );                
        }else{
            //server validation errors
            ServerValidationErrors(data);
        }
    });

    post.fail(function() {
        alert( "Failed to post permission details" );
    });
}
</script>