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
                        @foreach ($client->contacts as $contact)
                        <option value="{{ $contact->email }}">{{ $contact->email }}</option>
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
    var reportid = button.data('reportid');
    $('#doc-email-modal').data('mode', button.data('mode'));

    $('#doc-email-id').val(reportid);
});

$("#doc-email-send").click(function()
{

    $('#doc-email-modal').modal('hide');
    $("body").addClass("loading");

    $data = {};
    $data['_token'] = "{{ csrf_token() }}";
    $data['reportid'] = $('#doc-email-id').val();
    $data['email'] = $('#doc-email-address').val();
    $data['subject'] = $('#doc-email-subject').val();
    $data['body'] = $('#doc-email-body').val();
    $data['mode'] = $('#doc-email-modal').data('mode');

    $post = $.post("/Signing/Email", $data);

    $post.done(function (data) {
        $("body").removeClass("loading");
        switch(data['status']) {
            case "OK":
                SavedSuccess("Email Sent");
                GoToPage('/Clients/View/{{ $client->id }}/file');
                break;
            case "reportnotfound":
            case "signnotfound":
            case "nomode":
                $.dialog({
                    title: 'Oops...',
                    content: 'Unknown Response from server. Please refresh the page and try again.'
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
        NoReplyFromServer();
    });
});
</script>