@extends('master')

@section('content')  
<div class="row">
    <div style="float:left; width: 15em;  margin-left: 20px;">
        <button style="width: 100%;" id="save" name="save" type="button" class="btn OS-Button">Save</button>
    </div>

    <div style="float:left; width: 15em;  margin-left: 20px;">
        <button style="width: 100%;" id="backbutton" name="backbutton" type="button" class="btn OS-Button">Back to Accounts</button>
    </div>
</div>
<br>
{!! Form::OSinput("subdomain", "Subdomain", "", "", "true", "") !!}

{!! Form::OSinput("database", "DB Name", "", "", "true", "") !!}

{!! Form::OSinput("username", "DB Username", "", "", "true", "") !!}

{!! Form::OSinput("password", "DB Password", "", "", "true", "") !!}

{!! Form::OSselect("status", "Status", ["1" => "Active", "0" => "Disabled"], "", 1, "false", "") !!}

{!! Form::OStextarea("disabledmessage", "Disabled Message", "", "", "false", "") !!}

{!! Form::OSinput("client_id", "LLS Client ID", "", "0", "true", "") !!}

{!! Form::OSinput("licenseduseres", "Licensed Users", "", "5", "true", "") !!}

<script>    
$(document).ready(function() {
    $('#backbutton').click(function(e) {
        var link = document.createElement('a');
        link.href = "/Accounts/";
        link.id = "link";
        document.body.appendChild(link);
        link.click(); 
    });
    
});
</script>

@stop