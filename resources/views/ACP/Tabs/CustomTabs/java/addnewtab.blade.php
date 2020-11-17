<div class="modal fade" id="AddNewTabModal" tabindex="-1" role="dialog" aria-labelledby="#AddSelectModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="exampleModalLabel">Add New Tab:</h4>
            </div>
            <div id="AddNewTabBody" class="modal-body">
                <div class="input-group ">
                    <span class="input-group-addon" for="designer-tab-name-new"><div style="width: 7em;">Name:</div></span>
                    <input id="designer-tab-name-new" name="designer-tab-name-new" type="text" class="form-control">
                </div>
                <div class="input-group ">
                    <span class="input-group-addon" for="tab-type-new"><div style="width: 7em;">Type:</div></span>
                    <select id="tab-type-new" name="selectbasic" class="form-control tab-type">
                        <option value="client">{{ TextHelper::GetText("Client") }}</option>
                        <option value="vendor">Vendor</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button id="AddNewTab" name="" type="button" class="btn OS-Button" value="">Add</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="EmptyDesign" style="display: none;">
    <div id="col1" class="col-md-4">

    </div>
    <div id="col2" class="col-md-4">

    </div>
    <div id="col3" class="col-md-4">

    </div>
</div>

<script>
$( document ).ready(function() {
    $("#AddNewTab").click(function()
    {
        $.confirm({
            title: "Are you sure you want to save?",
            buttons: {
                confirm: function() {

                    $("#AddNewTabModal .close").click();
                    $("body").addClass("loading");

                    //window.xhtmlFormatting = "formatted";

                    $name = $('#designer-tab-name-new').val();

                    $type = $('#tab-type-new').val();

                    post = PostTab(0, $name, "", $type);

                    post.done(function( data )
                    {
                        $("body").removeClass("loading");
                        if ($.isNumeric(data))
                        {
                            $("#edit-tabselect").append($('<option/>', {
                                value: data,
                                text : $name
                            }));

                            $("#edit-tabselect").val(data);
                            $('#edit-tabselect').change();
                        }else{
                            //server validation errors
                            ServerValidationErrors(data);
                        }
                    });

                    post.fail(function() {
                        $("body").removeClass("loading");
                        alert( "Failed to post" );
                    });
                },
                cancel: function() {
                    // nothing to do

                }
            }
        });
    });
});
</script>