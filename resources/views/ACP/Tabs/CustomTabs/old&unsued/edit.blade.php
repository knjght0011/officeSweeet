<br>
<div class="col-md-12">
    <select id="edit-tabselect" name="selectbasic" class="form-control tabselect" style="height: 100%;"></select>
</div>
<br><br><br>
<div class="row">
    <div class="form-group">
        <label class="col-md-1 control-label" for="tab-name-basic">Name:</label>  
        <div class="col-md-4">
            <input id="tab-name-edit" name="tab-name-basic" type="text" placeholder="Name" class="form-control input-md" required="">
        </div>

        <label class="col-md-1 control-label" for="tab-displayname-basic">Type:</label>  
        <div class="col-md-4">
            <input id="tab-type-edit" name="tab-type-edit" type="text" class="form-control input-md" disabled="true">
        </div>
    </div>
</div>

<br>
<div class="row">
    <!-- Textarea -->
    <div class="form-group">
        <label class="col-md-1 control-label" for="tab-content-basic">HTML</label>
        <div class="col-md-9">                     
            <textarea class="form-control" id="tab-content-edit" name="tab-content-basic"></textarea>
        </div>
    </div>
</div>    

<br>
<button id="tab-basic-edit" name="" class="btn OS-Button">Submit</button>


<script>
$( document ).ready(function() {
    
    GetAllTableData();
    
    $('#edit-tabselect').change(function() {

        
        $id = $('#edit-tabselect').val();
        GetTableData($id);
        
    });
    
    $("#tab-basic-edit").click(function()
    {
        $id = $('#edit-tabselect').val();
        $name = $('#tab-name-edit').val();
        $html = $('#tab-content-edit').val();
        
        
        
        post = PostTabEdit($id, $name, $html);

        post.done(function( data ) 
        {

            alert(data);
            if (data === "success"){
                alert("success text");
            }else{
                //server validation errors
                ServerValidationErrors(data);
            }
        });

        post.fail(function() {
            alert( "Failed to post" );
        }); 

    });

});

function PostTabEdit($id, $name, $html ) {
    return $.post("/CustomTables/Save",
    {
        _token: "{{ csrf_token() }}",
        id: $id,
        name: $name,
        html: $html,
    });
}

function GetAllTableData() {
    var get = $.get( "/CustomTables/Get/all", function(  ) { });

    get.done(function( data ) {
        first = true;
        $('.tabselect').empty();
        $.each(data, function( index, value ) {
            
            $('.tabselect')
                .append($("<option></option>")
                    .attr("value",value["id"])
                    .text(value["displayname"]));
            
            if(first === true)
            {
                $('#tab-name-edit').val(value["displayname"]);
                $('#tab-type-edit').val(value["type"]);
                $('#tab-content-edit').val(value["content"]);
                first = false;
            }
        });
    });   
}

function GetTableData($id) 
{    
    $("body").addClass("loading");

    var get = $.get( "/CustomTables/Get/" + $id, function(  ) { });

    get.done(function( data ) {

        $('#tab-name-edit').val(data["displayname"]);
        $('#tab-type-edit').val(data["type"]);
        $('#tab-content-edit').val(data["content"]);
        $("body").removeClass("loading");
    });   
}
</script>