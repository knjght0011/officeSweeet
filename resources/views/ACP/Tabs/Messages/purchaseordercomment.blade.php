<div class="row">    
    <div class="col-md-1">

    </div>
    <div class="form-group col-md-10">
        <label for="comment">Comment Content:</label>
        <textarea class="form-control" rows="15" id="purchaseordercomment">{{ SettingHelper::GetSetting('purchaseordercomment') }}</textarea>
        <button id="purchaseordercommentsave" name="purchaseordercommentsave" type="button" class="btn OS-Button" style="width: 100%;">Save</button>
    </div>
</div>

<script>
$(document).ready(function() {
    
    $("#purchaseordercommentsave").click(function()
    {
        $("body").addClass("loading");

        $purchaseordercomment = $("#purchaseordercomment").val();

        
        post = $.post("/ACP/General/Save",
        {
            _token: "{{ csrf_token() }}",
            purchaseordercomment: $purchaseordercomment,
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