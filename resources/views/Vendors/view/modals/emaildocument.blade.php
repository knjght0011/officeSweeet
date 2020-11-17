<div class="modal fade doc-email-modal" id="doc-email-modal" tabindex="-1" role="dialog" aria-labelledby="doc-email-modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Send To:</h4>
            </div>
            <div class="modal-body">

                <div class="input-group">
                    <span class="input-group-addon" for="doc-email-address"><div style="width: 7em;">Email Address:</div></span>
                    <select id="doc-email-address" name="doc-email-address" class="form-control">
                        @foreach ($vendor->contacts as $contact)
                        <option value="{{ $contact->id }}">{{ $contact->email }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="input-group">
                    <span class="input-group-addon" for="doc-email-subject"><div style="width: 7em;">Subject:</div></span>
                    <input type="text" class="form-control" id="doc-email-subject" list="document-emailsubjects-list">
                    <datalist  id="document-emailsubjects-list" name="document-emailsubjects-list">
                        @foreach(PageElement::EmailSubjects() as $subject)
                            <option>{{ $subject->subject }}</option>
                        @endforeach
                    </datalist>
                </div>

                <div class="input-group">
                    <span class="input-group-addon" for="doc-email-body"><div style="width: 7em;">Body:</div></span>
                    <textarea style="resize: none;" id="doc-email-body" class="form-control" rows="15">Please see attached</textarea>
                </div>

                <input style="display: none;" type="text" class="form-control" id="doc-email-id">
                <input style="display: none;" type="text" class="form-control" id="doc-email-status">
            </div>
            <div class="modal-footer">
                <button id="doc-email-send" name="doc-email-send" type="button" class="btn OS-Button" value="">Send</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script>
    
$('#doc-email-modal').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget); // Button that triggered the modal
    var mode = button.data('mode');
    $('#doc-email-modal').data('mode', mode);
    if(mode === "sendpo"){
        $row = window.purchaseordersearch.row('.selected').data();
        var reportid = $row[4];
        $('#doc-email-status').val($row[3]);
    }else{
        var reportid = button.data('reportid');
        $('#doc-email-status').val(0);
    }


    $('#doc-email-id').val(reportid);
});

$("#doc-email-send").click(function()
{

    $('#doc-email-modal').modal('hide');
    $("body").addClass("loading");
    $data = {};
    $data['_token'] = "{{ csrf_token() }}";

    $data['contact_id'] = $('#doc-email-address').val();
    $data['contact_type'] = "Vendor";
    $data['subject'] = $('#doc-email-subject').val();
    $data['body'] = $('#doc-email-body').val();
    $data['link_id'] = $('#doc-email-id').val();
    $data['type'] = "PurchaseOrder";

    post = $.post("/Email/Send", $data);

    post.done(function( data ) {
        $("body").removeClass("loading");
        switch(data['status']) {
            case "OK":
                $('#send-client-email-modal').modal('hide');
                if($('#doc-email-status').val() === "Created"){
                    $.confirm({
                        title: 'Email Sent!',
                        content: 'Would you like to update the Purchase Orders Status to Ordered?',
                        buttons: {
                            Yes: function () {
                                UpdateStatus($data['link_id'])
                            },
                            No: function () {

                            },
                        }
                    });
                }else{
                    SavedSuccess("Email Sent");
                }
                break;
            case "linknotfound":
                console.log("Link Not Found");
                $.dialog({
                    title: 'Oops...',
                    content: 'Unknown Response from server. Please refresh the page and try again.'
                });
                break;
            case "disabled":
                $.dialog({
                    title: 'Oops...',
                    content: 'This has been disabled during the live demo.'
                });
                break;
            default:
                console.log(data);
                $.dialog({
                    title: 'Oops...',
                    content: 'Unknown Response from server. Please refresh the page and try again.'
                });
        }
    });

    post.fail(function() {
        NoReplyFromServer();
    });
});

function UpdateStatus($id) {

    $data = {};
    $data['_token'] = "{{ csrf_token() }}";
    $data['id'] = $id;
    $data['status'] = 2;

    $("body").addClass("loading");
    $post = $.post("/PurchaseOrders/UpdateStatus", $data);

    $post.done(function (data) {
        $("body").removeClass("loading");
        switch(data['status']) {
            case "OK":

                $row = window.purchaseordersearch.row('.selected').data();
                $row[3] = data['newstatus'];
                window.purchaseordersearch.row('.selected').data($row).draw( false );

                break;
            case "notfound":
                $.dialog({
                    title: 'Oops...',
                    content: 'Unknown Response from server. Please refresh the page and try again.'
                });
                break;
            case "notlogedin":
                NotLogedIN();
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
        NoReplyFromServer();
    });

}
</script>