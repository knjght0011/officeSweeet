<div class="row"><div class="col-md-12"><br></div></div>

<div class="form-group row"> 
    <label class="col-sm-3 form-control-label" for="Tax">Tax</label>
    <div class="col-sm-6">
        <input id="Tax" name="Tax" type="text" value="" class="form-control" required="">
    </div>
</div>

<div class="row">
    <div class="col-md-2">
        <button id="save" name="save" type="button" class="btn OS-Button">Save</button>
    </div>
</div> 
   
<script>
$(document).ready(function() { 
    
    @if(isset($settings['Tax']))
    $("#Tax").val("{{ $settings['Tax'] }}");
    @else
    $("#Tax").val("0.0");
    @endif

    $("#save").click(function()
    {
        settingspost = $.post("/ACP/General/Save",
        {
            _token: "{{ csrf_token() }}",
            Tax: $('#Tax').val(),

        });

        settingspost.done(function( data )
        {
            alert(data)
        });

        settingspost.fail(function() {
            alert( "Failed to post settings" );
        });
    });
});    
</script> 