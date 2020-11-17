    <div class="row"><div class="col-md-12"><br></div></div>
    @foreach($settings as $setting)
            <div class="form-group row"> 
                <label class="col-sm-3 form-control-label" for="{{ $setting->name }}">{{ $setting->name }}</label>
                <div class="col-sm-6">
                    <input id="{{ $setting->name }}" name="{{ $setting->name }}" type="text" value="{{ $setting->value }}" class="form-control" required="">
                </div>
            </div>
    @endforeach
    
    <div class="row">
        <div class="col-md-2">
            <button id="save" name="save" type="button" class="btn OS-Button">Save</button>
        </div>
    </div> 
   


<script>
$(document).ready(function() { 
    var $settings = [];
    @foreach($settings as $setting)
    $settings["{{ $setting->name }}"] = $('#{{ $setting->name }}').val()
    @endforeach
    
    $("#save").click(function()
    {
        settingspost = $.post("/Admin/Settings/General/Save",
        {
            _token: "{{ csrf_token() }}",
            @foreach($settings as $setting)
            {{ $setting->name }}: $('#{{ $setting->name }}').val(),
            @endforeach
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