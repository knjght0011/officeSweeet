<div class="row">    
    <div class="col-md-1">

    </div>
    <div class="form-group col-md-10">
        <label for="comment">Comment Content:</label>
        <textarea class="form-control" rows="15" id="quotecomment">{{ SettingHelper::GetSetting('quotecomment') }}</textarea>
        <button id="quotecommentsave" name="quotecommentsave" type="button" class="btn OS-Button" style="width: 100%;">Save</button>
    </div>
</div>

<script>
$(document).ready(function() {
    
    $("#quotecommentsave").click(function()
    {
        $("body").addClass("loading");

        $quotecomment = $("#quotecomment").val();

        
        post = $.post("/ACP/General/Save",
        {
            _token: "{{ csrf_token() }}",
            quotecomment: $quotecomment,
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