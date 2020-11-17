<div class="modal fade send-client-email-modal" id="send-client-email-modal" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="exampleModalLabel">Send To:</h4>
            </div>
            <div class="modal-body">
                <label for="send-client-email-email">Select Email Address:</label>
                <select id="send-client-email-email" name="send-client-email-email" class="form-control">
                    @foreach ($client->contacts as $contact)
                    <option value="{{ $contact->id }}">{{ $contact->email }}</option>
                    @endforeach
                </select>
                <label for="send-client-email-subject">Subject:</label>
                <input type="text" class="form-control" id="send-client-email-subject" list="send-client-email-subject-list">
                <datalist  id="send-client-email-subject-list" name="send-client-email-subject-list">
                    @foreach(PageElement::EmailSubjects() as $subject)
                        <option>{{ $subject->subject }}</option>
                    @endforeach
                </datalist>

                <label for="send-client-email-body">Body:</label>
                <textarea style="resize: none;" id="send-client-email-body"class="form-control" rows="15"></textarea>
            </div>
            <div class="modal-footer">
                <button id="send-client-email-send" name="send-client-email-send" type="button" class="btn OS-Button">Send</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    
$('#send-client-email-modal').on('show.bs.modal', function (event) {


    var button = $(event.relatedTarget); // Button that triggered the modal

    if(button.data('mode') === "button"){

        // data-toggle="modal" data-target="#send-client-email-modal" data-mode="button" data-type="Quote" data-link_id="" data-subject=""

        $('#send-client-email-modal').data('link_id', button.data('link_id'));
        $('#send-client-email-modal').data('type', button.data('type'));
        $('#send-client-email-modal').data('subject', button.data('subject'));

    }

    switch($('#send-client-email-modal').data('type')) { //"Here is your " + type + " number: #" + quotenumber
        case "Invoice":
            $('#send-client-email-body').val("{{ SettingHelper::GetSetting('clientinvoicetemplate') }}");
            $('#send-client-email-subject').val("Here is your Invoice number: #" + $('#send-client-email-modal').data('subject'));
            break;
        case "Quote":
            $('#send-client-email-body').val("{{ SettingHelper::GetSetting('clientquotetemplate') }}");
            $('#send-client-email-subject').val("Here is your Quote number: #" + $('#send-client-email-modal').data('subject'));
            break;
        case "Overview":
            $('#send-client-email-body').val("Here is your Statement.");
            $('#send-client-email-subject').val("Here is your Statement");
            break;
        case "Document":
            $('#send-client-email-body').val("Here is your Document.");
            $('#send-client-email-subject').val("Please see attached");
            break;
    }

});

$("#send-client-email-send").click(function()
{

    $("body").addClass("loading");
    $data = {};
    $data['_token'] = "{{ csrf_token() }}";

    $data['contact_id'] = $('#send-client-email-email').val();
    $data['contact_type'] = "Client";
    $data['subject'] = $('#send-client-email-subject').val();
    $data['body'] = $('#send-client-email-body').val();
    $data['link_id'] = $('#send-client-email-modal').data('link_id');
    $data['type'] = $('#send-client-email-modal').data('type');

    post = $.post("/Email/Send", $data);

    post.done(function( data ) {
        $("body").removeClass("loading");
        switch(data['status']) {
            case "OK":
                $('#send-client-email-modal').modal('hide');
                SavedSuccess('Email Sent');
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
</script>