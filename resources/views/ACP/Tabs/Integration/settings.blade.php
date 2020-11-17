
<div class="col-md-4 col-md-offset-4" style="margin-top: 15px;">
{!! Form::OSinput("tn-username", "Gateway Username", "", SettingHelper::GetSetting('transnational-username'), "true", "") !!}
{!! Form::OSinput("tn-password", "Gateway Password", "", SettingHelper::GetSetting('transnational-password'), "true", "") !!}
<button id="tn-save" name="save" type="button" class="btn OS-Button">Save</button>
</div>

<script>
$(document).ready(function() {   
   
    $("#tn-save").click(function()
    {
        $username = {};        
        $username['_token'] = "{{ csrf_token() }}";
        $username['transnational-username'] = $('#tn-username').val();
        $username['transnational-password'] = $('#tn-password').val();
        
        $("body").addClass("loading");
                
        post = $.post("/ACP/General/Save",$username);
        
        post.done(function( data ) {   
            $("body").removeClass("loading");
        });
        
        post.fail(function() {
            $("body").removeClass("loading");
            alert( "Lost contact eith server" );
        });  
    });
});    
</script>