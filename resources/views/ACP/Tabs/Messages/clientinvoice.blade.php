<div class="row">    
    <div class="col-md-1">

    </div>
    <div class="form-group col-md-10">
        <label for="comment">Email Content:</label>
        <textarea class="form-control" rows="14" id="clientinvoicetemplate">{{ SettingHelper::GetSetting('clientinvoicetemplate') }}</textarea>
        <button id="clientinvoicetemplatesave" name="clientinvoicetemplatesave" type="button" class="btn OS-Button" style="width: 100%;">Save</button>
    </div>
</div>

<script>
$(document).ready(function() {

    $("#clientinvoicetemplatesave").click(function()
    {
        $("body").addClass("loading");

        $clientinvoicetemplate = $("#clientinvoicetemplate").val();

        
        post = $.post("/ACP/General/Save",
        {
            _token: "{{ csrf_token() }}",
            clientinvoicetemplate: $clientinvoicetemplate,
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