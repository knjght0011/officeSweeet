<div class="modal fade" id="send-popup-compose-email-client-tab-modal" tabindex="-1" role="dialog" aria-labelledby="ShowEmailCompose"
     aria-hidden="true">
    <div class="modal-dialog small-custom-modal-dialog" role="document"
         style="
     position: fixed;
     bottom: 10px;
     right: 10px;
     margin: 10px;
}">
        <div class="modal-content small-custom-modal-content">
            <div class="modal-header">
                <h2 class="modal-title" style="float: left">New Email</h2>
                <button type="button" class="close" style="float: right" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div style="height: 99%;" class="modal-body">
                <div class="tab-content" style="height: 100%">
                    <div class="row">
                        <div class="col-md-12" id="form_container">
                            <form role="form" method="post" id="compose-mail-clients-tab">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group error" style="color: #8c001a;font-weight: 400">&nbsp;
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 form-group">
                                        <label for="recipient">
                                            Recipient:</label>
                                        <input id="send-popup-compose-email-recipient-client-tab" type="text" class="form-control" id="recipient" name="recipient" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 form-group">
                                        <label for="subject">
                                            Subject:</label>
                                        <input id="send-popup-compose-email-subject-client-tab" type="text" class="form-control"  name="subject" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 form-group">
                                        <label for="message">
                                            Message:</label>
                                        <textarea id="send-popup-compose-email-body-client-tab" class="form-control" type="textarea" name="message"  maxlength="6000" rows="7"></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 form-group">
                                        <button id="send-popup-compose-email-client-tab" type="button" class="btn btn-lg btn-default pull-right" >Send →</button>
                                    </div>
                                </div>

                            </form>
                            <div id="success_message" style="width:100%; height:100%; display:none; ">
                                <h3>Posted your message successfully!</h3>
                            </div>
                            <div id="error_message"
                                 style="width:100%; height:100%; display:none; ">
                                <h3>Error</h3>
                                Sorry there was an error sending your form.

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#send-popup-compose-email-client-tab-modal').on('show.bs.modal', function (event) {
            var button  = $(event.relatedTarget); // Button that triggered the modal
            var email = button.data('mail'); // Extract info from data-* attributes
            var client_contact_id = button.data('client-contact-id'); // Extract info from data-* attributes
            var recipient_id = button.data('recipient-id'); // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            $("#send-popup-compose-email-recipient-client-tab").val(email);
            $("#send-popup-compose-email-subject-client-tab").val('');
            $("#send-popup-compose-email-body-client-tab").val('');
            $('#send-popup-compose-email-client-tab-modal').data('link_id', client_contact_id);
            $('#send-popup-compose-email-client-tab-modal').data('contact_id', client_contact_id);
            $('#send-popup-compose-email-client-tab-modal').data('recipient_id', recipient_id);
            $('#send-popup-compose-email-client-tab-modal').data('type', 'EmailFromPopupModalToClient');
        });

        $('#send-popup-compose-email-client-tab-modal').on('hide.bs.modal', function (event) {
        });

        $("#send-popup-compose-email-client-tab").unbind().click(function()
        {
            $("body").addClass("loading");
            $data = {};
            $data['_token'] = "{{ csrf_token() }}";

            $data['contact_id'] = $('#send-popup-compose-email-client-tab-modal').data('contact_id');
            $data['recipient_id'] = $('#send-popup-compose-email-client-tab-modal').data('recipient_id');
            $data['contact_type'] = "Client";
            $data['email'] = $('#send-popup-compose-email-recipient-client-tab').val();
            $data['subject'] = $('#send-popup-compose-email-subject-client-tab').val();
            $data['body'] = $('#send-popup-compose-email-body-client-tab').val();
            $data['link_id'] = $('#send-popup-compose-email-client-tab-modal').data('link_id');
            $data['type'] = $('#send-popup-compose-email-client-tab-modal').data('type');

            post = $.post("/Email/SendFromPopupCompose", $data);

            post.done(function( data ) {
                $("body").removeClass("loading");
                switch(data['status']) {
                    case "OK":
                        $('#send-popup-compose-email-client-tab-modal').modal('hide');
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
    });
</script>