<div class="row">    
    <div class="col-md-1">

    </div>
    <div class="form-group col-md-10">
        <label for="comment">Email Content:</label>
        <textarea class="form-control" rows="15" id="clientquotetemplate">{{ SettingHelper::GetSetting('clientquotetemplate') }}</textarea>
        <button id="clientquotetemplatesave" name="clientquotetemplatesave" type="button" class="btn OS-Button" style="width: 100%;">Save</button>
    </div>
</div>

<script>
$(document).ready(function() {
    
    $("#clientquotetemplatesave").click(function()
    {
        $("body").addClass("loading");

        $clientquotetemplate = $("#clientquotetemplate").val();

        
        post = $.post("/ACP/General/Save",
        {
            _token: "{{ csrf_token() }}",
            clientquotetemplate: $clientquotetemplate,
        });
        
        
        post.done(function( data ) {
             $("body").removeClass("loading");
             alert(data);
        });
        
        post.fail(function() {
             $("body").removeClass("loading");
             alert( "Failed to post settings" );
             //bootstrap_alert.warning("Unable to post data", 'danger', 4000);
        });  
    });

}); 
</script> 