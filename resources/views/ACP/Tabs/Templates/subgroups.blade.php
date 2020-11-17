    <div class="row"><div class="col-md-12"><br></div></div>
    <div class="col-md-6">
        <label class="col-md-4 control-label" for="selectbasic">Group</label>
        <select id="type" name="type" class="form-control">
            <option value="client">Client</option>
            <option value="vendor">Vendor</option>
            <option value="employee">Employee</option>
            <option value="general">General</option>
        </select>
        <div class="row"><div class="col-md-12"><br></div></div>
        <select id="subgroups" name="subgroups" class="form-control" multiple="multiple" size="30">

        </select>
    </div>
    

    <div class="col-md-6">
        <div class="row"><div class="col-md-12"><br></div></div>
        <div class="row"><div class="col-md-12"><br></div></div>
        <div class="row"><div class="col-md-12"><br></div></div>
        <div class="row">
            <label class="col-md-12 control-label">Add Item:</label>
        </div>
        <div class="row">
            <button id="savesubgroup" name="save" type="button" class="btn OS-Button col-md-2">Add</button>
            <div class="col-md-10">
                <input type="text" class="form-control" id="new"></input>
            </div>

        </div>
        <div class="row">
            <button id="deletesubgroup" name="save" type="button" class="btn OS-Button col-md-2">Delete Selected</button>
        </div>
    </div>



<script>
$(document).ready(function() { 
    var client = [];
    var vendor = [];
    var employee = [];
    var general = [];
    

    
    $('#type').on('change', function() {
        $('#subgroups').empty();
        switch(this.value) {
            case 'client':
                $('#subgroups').empty();
                    $.each( client, function( key, value ) {
                        $('#subgroups').append($("<option></option>").attr("value", key).text(value));
                    });
                break;
            case 'vendor': 
                $('#subgroups').empty();
                    $.each( vendor, function( key, value ) {
                        $('#subgroups').append($("<option></option>").attr("value", key).text(value));
                    });
                break;
            case 'employee':
                $('#subgroups').empty();
                    $.each( employee, function( key, value ) {
                        $('#subgroups').append($("<option></option>").attr("value", key).text(value));
                    });
                break;
            case 'general':
                $('#subgroups').empty();
                    $.each( general, function( key, value ) {
                        $('#subgroups').append($("<option></option>").attr("value", key).text(value));
                    });         
                break;
            default:
        }        
    });
    
    $("#deletesubgroup").click(function()
    {

        $post = $.post("/Admin/Settings/Templates/Delete",
        {
            _token: "{{ csrf_token() }}",
            group: $('#type').val(),
            subgroup: $("#subgroups option:selected").text(),
        });
        
        $post.done(function( data ) 
        {   
            if(data == "fail"){
                alert("Failed to delete item")
            }else{
                switch($('#type').val()) {
                    case 'client':
                        client.splice($.inArray(data ,client),1);
                        break;
                    case 'vendor':
                        vendor.splice($.inArray(data ,vendor),1)
                        break;
                    case 'employee':
                        employee.splice($.inArray(data ,employee),1)
                        break;
                    case 'general':
                        general.splice($.inArray(data ,general),1)
                        break;
                    default:

                }
                $("#type").change();
            }
        });
        
        $post.fail(function() 
        {   
            alert("Error");
        });        
    });
    
    $("#savesubgroup").click(function()
    {
        $post = $.post("/Admin/Settings/Templates/Save",
        {
            _token: "{{ csrf_token() }}",
            group: $('#type').val(),
            subgroup: $('#new').val(),
        });
        
        $post.done(function( data ) 
        {   
            switch($('#type').val()) {
                case 'client':
                    client.push($('#new').val()); 
                    break;
                case 'vendor':
                    vendor.push($('#new').val()); 
                    break;
                case 'employee':
                    employee.push($('#new').val());        
                    break;
                case 'general':
                    general.push($('#new').val());    
                    break;
                default:

            }
            $('#subgroups').append($("<option></option>").attr("value",data).text($('#new').val()));
            alert("Saved");
        });
        
        $post.fail(function() 
        {   
            alert("Error");
        });        
    });
});
</script>