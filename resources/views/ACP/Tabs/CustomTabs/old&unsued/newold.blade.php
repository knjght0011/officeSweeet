<!-- No Longer Used, Left for reference --> 


<br>
<div class="row">
    <div class="form-group">
        <label class="col-md-1 control-label" for="tab-name-basic">Name:</label>  
        <div class="col-md-4">
            <input id="tab-name-new" name="tab-name-basic" type="text" placeholder="Name" class="form-control input-md" required="">
        </div>

        <label class="col-md-1 control-label" for="tab-displayname-basic">Type:</label>  
        <div class="col-md-4">
            <select id="tab-type-new" name="selectbasic" class="form-control tab-type">  
                <option value="client">{{ TextHelper::GetText("Client") }}</option>
                <option value="vendor">Vendor</option>
            </select>
        </div>
    </div>
</div>

<button type="button" class="btn OS-Button" data-toggle="modal" data-target="#AddInputModal" >Add Input</button>

<br>
<div class="row">
    <!-- Textarea -->
    <div class="form-group">
        <label class="col-md-1 control-label" for="tab-content-basic">HTML:</label>
        <div class="col-md-9">                     
            <textarea class="form-control" id="tab-content-new" name="tab-content-basic"></textarea>
        </div>
    </div>
</div>    

<br>
<button id="tab-basic-save" name="" class="btn OS-Button">Submit</button>

<div class="modal fade" id="NoteModal" tabindex="-1" role="dialog" aria-labelledby="EmailQuoteModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="exampleModalLabel">Add Input:</h4>
            </div>
            <div class="modal-body">
 
            </div>
            <div class="modal-footer">
                <button id="SaveEvent" name="SaveEvent" type="button" class="btn OS-Button SendQuote" value="">Send</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>




<script>
$( document ).ready(function() {
    
    $("#tab-basic-save").click(function()
    {

        post = PostTab(0, $('#tab-name-new').val(), $('#tab-content-new').val(), $('#tab-type-new').val());

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

function PostTab($id, $name, $html, $type) {
    return $.post("/CustomTables/Save",
    {
        _token: "{{ csrf_token() }}",
        id: $id,
        name: $name,
        html: $html,
        type: $type
    });
}
</script>