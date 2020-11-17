<div class="modal fade" id="fileupload-modal" tabindex="-1" role="dialog" aria-labelledby="FileUpload" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="">Upload Image/PDF</h4>
            </div>
            <div class="modal-body" style="height: 450px;">
                <div class="col-md-12">
                    <div class="input-group ">
                        <span class="input-group-addon" for="fileupload-file"><div>Upload Image:</div></span>
                        <input id="fileupload-file" name="fileupload-file" type="file" placeholder="" value="" class="form-control" data-validation-label="Company Logo" data-validation-required="true" data-validation-type="">
                    </div>
                </div>
                <div class="col-md-6" style="height: 100%;">

                    <div style="width: 100%; height: 40px; float: left; text-align: center; background-color: #eee; padding-top: 7px; border-style:solid; border-width:1px; border-radius:4px; border-color:rgb(204, 204, 204); ">
                        <label style="font-weight: normal;" for="fileupload-description">Description</label>
                    </div>
                    <textarea id="fileupload-description" class="form-control input-md"  style="height: calc(100% - 80px); width: 100%; resize: none;"></textarea>
                </div>
                <div class="col-md-6" id="fileupload-preview-container">
                    <embed style="height: 420px; width: 100%;" id="fileupload-preview" ></embed>
                </div>
            </div>
            <div class="modal-footer">
                <button id="fileupload-save" name="fileupload-save" type="button" class="btn OS-Button" value="">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<input id="UploadIDField" type="text" hidden />

<script>
$(document).ready(function() {
    $('#fileupload-modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var updatetype = button.data('updatetype');

        if(updatetype !== undefined){
            $('#fileupload-modal').data('updatetype', updatetype );
        }

        var updateid = button.data('updateid');
        if(updateid !== undefined){
            $('#fileupload-modal').data('updateid', updateid );
        }

        var outputid = button.data('outputid');
        if(outputid !== undefined){
            $('#fileupload-modal').data('outputid', outputid );
        }

    });

    $("#fileupload-save").click(function(){

        $button = $(this);

        $("body").addClass("loading");

        $data = {};
        $data['_token'] = "{{ csrf_token() }}";

        @if(isset($client))
            $data['client_id'] = "{{ $client->id }}";
        @endif

        @if(isset($vendor))
            $data['vendor_id'] = "{{ $vendor->id }}";
        @endif

        @if(isset($employee))
            $data['user_id'] = "{{ $employee->id }}";
        @endif

        $data['file'] = $('#fileupload-preview').attr('src');
        $data['description'] = $('#fileupload-description').val();

        var updatetype = $('#fileupload-modal').data('updatetype');
        if(updatetype !== undefined){
            $data['updatetype'] = updatetype;
        }

        var updateid = $('#fileupload-modal').data('updateid');
        if(updateid !== undefined){
            $data['updateid'] = updateid;
        }

        $post = $.post("/FileStore/Upload", $data);

        $post.done(function (data) {
            $("body").removeClass("loading");
            switch(data['status']) {
                case "OK":
                    SavedSuccess("File Saved");
                    $('#fileupload-modal').modal('hide');

                    var outputid = $button.data('outputid');
                    if(outputid !== undefined){
                        $('#'+outputid).val(data['data']);
                    }else{
                        if($('#fileupload-modal').data('updatetype') === "trainingrequest"){
                            @if(isset($employee))
                                GoToPage('/Employees/View/{{ $employee->id }}/training-tab');
                            @else
                                //location.reload();
                            @endif
                        }else{
                            //location.reload();
                        }
                    }

                    if ($("#filestore-show-file-button").length ) {
                        $('#filestore-show-file-button').removeProp('disabled');
                        $("#filestore-show-file-button").data('fileid', data['data']);
                    }

                    $('#file-id').val(data['data']);

                    break;
                case "unsuportedfiletype":
                    $.dialog({
                        title: 'Oops...',
                        content: "This filetype is unsupported, Please upload PDF's and Images only."
                    });
                    break;
                case "validation":
                    ServerValidationErrors(data['errors']);
                    break;
                default:
                    console.log(data);
                    $.dialog({
                        title: 'Oops...',
                        content: 'Unknown Response from server. Please refresh the page and try again.'
                    });
            }
        });

        $post.fail(function () {

            $("body").removeClass("loading");
            $.dialog({
                title: 'Oops...',
                content: 'Failed to contact server. Please try again later.'
            });
        });


    });


    $("#fileupload-file").change(function()
    {
        if (this.files[0].name != "") {
            $('#fileupload-preview').remove();
            $('#fileupload-preview-container').append('<embed style="height: 396px; width: 100%;" id="fileupload-preview" ></embed>');
            readURL(this);
        }
    });
});

var srcContent;
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            srcContent=  e.target.result;
            $('#fileupload-preview').attr('src', srcContent);
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>