<div class="modal fade" id="send-popup-compose-email-employee-tab-modal" tabindex="-1" role="dialog" aria-labelledby="ShowEmailCompose"
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
                            <form role="form" method="post" id="compose-mail-employees-tab">
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
                                        <input disabled id="send-popup-compose-email-recipient-employee-tab" type="text" class="form-control" id="recipient" name="recipient" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 form-group">
                                        <label for="subject">
                                            Subject:</label>
                                        <input id="send-popup-compose-email-subject-employee-tab" type="text" class="form-control"  name="subject" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 form-group">
                                        <label for="message">
                                            Message:</label>
                                        <div name="send-popup-compose-email-body-employee-tab" id="send-popup-compose-email-body-employee-tab"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 form-group">
                                        <button id="send-popup-compose-email-employee-tab-button" type="button" class="btn btn-lg btn-default pull-right" >Send →</button>
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

<!-- Choose send mail method -->
<div class="modal fade bd-example-modal-sm" id="send-popup-compose-email-employee-tab-choose-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel" style="float:left">Please choose an action:</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="float:right">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <button type="button" data-toggle='modal' data-dismiss="modal" href='#send-popup-compose-email-employee-tab-modal' class="btn btn-outline-primary btn-lg btn-block">Send email from scratch</button>
                <button type="button" onclick="alert('this feature not available at the present')" class="btn btn-outline-primary btn-lg btn-block">Send email from template</button>
                <button type="button" onclick="alert('this feature not available at the present')" class="btn btn-outline-primary btn-lg btn-block">Send email campaign</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        ClassicEditor.create( document.querySelector('#send-popup-compose-email-body-employee-tab'))
            .then( editor => {
                window.employeeeditor = editor;

                $height = $('#send-popup-compose-email-body-employee-tab').height() + 200;
                employeeeditor.ui.view.editable.editableElement.style.height = $height + 'px';

            } )
            .catch( err => {
                console.error( err.stack );
            } );

        $('#send-popup-compose-email-employee-tab-choose-modal').on('show.bs.modal', function (event) {
            var button  = $(event.relatedTarget); // Button that triggered the modal
            var email = button.data('mail'); // Extract info from data-* attributes
            var client_contact_id = button.data('client-contact-id'); // Extract info from data-* attributes
            var recipient_id = button.data('recipient-id'); // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            $('#send-popup-compose-email-employee-tab-choose-modal').data('email', email);
            $('#send-popup-compose-email-employee-tab-choose-modal').data('link_id', client_contact_id);
            $('#send-popup-compose-email-employee-tab-choose-modal').data('contact_id', client_contact_id);
            $('#send-popup-compose-email-employee-tab-choose-modal').data('recipient_id', recipient_id);
        });

        $('#send-popup-compose-email-employee-tab-modal').on('show.bs.modal', function (event) {
            var email =  $('#send-popup-compose-email-employee-tab-choose-modal').data('email');
            var client_contact_id = $('#send-popup-compose-email-employee-tab-choose-modal').data('contact_id');
            var recipient_id = $('#send-popup-compose-email-employee-tab-choose-modal').data('recipient_id');
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            $("#send-popup-compose-email-recipient-employee-tab").val(email);
            $("#send-popup-compose-email-subject-employee-tab").val('');
            $("#send-popup-compose-email-body-employee-tab").val('');
            $('#send-popup-compose-email-employee-tab-modal').data('link_id', client_contact_id);
            //Set contact_id= null because employee don't have contact_id
            $('#send-popup-compose-email-employee-tab-modal').data('contact_id', null);
            $('#send-popup-compose-email-employee-tab-modal').data('recipient_id', recipient_id);
            $('#send-popup-compose-email-employee-tab-modal').data('type', 'EmailFromPopupModalToEmployee');
        });

        $('#send-popup-compose-email-employee-tab-modal').on('hide.bs.modal', function (event) {
        });

        $("#send-popup-compose-email-employee-tab-button").unbind().click(function()
        {
            $("body").addClass("loading");
            $data = {};
            $data['_token'] = "{{ csrf_token() }}";

            $data['contact_id'] = $('#send-popup-compose-email-employee-tab-modal').data('contact_id');
            $data['recipient_id'] = $('#send-popup-compose-email-employee-tab-modal').data('recipient_id');
            $data['contact_type'] = "Client";
            $data['email'] = $('#send-popup-compose-email-recipient-employee-tab').val();
            $data['subject'] = $('#send-popup-compose-email-subject-employee-tab').val();
            $data['body'] = employeeeditor.getData();
            $data['link_id'] = $('#send-popup-compose-email-employee-tab-modal').data('link_id');
            $data['type'] = $('#send-popup-compose-email-employee-tab-modal').data('type');
            post = $.post("/Email/SendFromPopupCompose", $data);

            post.done(function( data ) {
                $("body").removeClass("loading");
                switch(data['status']) {
                    case "OK":
                        $('#send-popup-compose-email-employee-tab-modal').modal('hide');
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