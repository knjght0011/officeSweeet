@extends('master')

@section('content')

    <div id="header" class="row">
        <table class="table">
            <tr>
                <td>
                    From:
                </td>
                <td>
                    {{ $mail->sender }}
                </td>
            </tr>
            <tr>
                <td>
                    Subject:
                </td>
                <td>
                    {{ $mail->subject }}
                </td>
            </tr>
            <tr>
                <td>
                    To:
                </td>
                <td>
                    {{ $mail->email }}
                </td>
            </tr>
            <tr>
                <td>
                    Date:
                </td>
                <td>
                    {{ $mail->created_at }}
                </td>
            </tr>
            <tr>
                <td>
                    Body:
                </td>
                <td>
                    {{$mail->body}}
                </td>
            </tr>
        </table>
    </div>
    <div class="modal fade" id="inbox-reply-modal" tabindex="-1" role="dialog" aria-labelledby="inbox-reply"
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
                                <form role="form" method="post" id="compose-mail-inbox-reply">
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
                                            <input disabled id="inbox-reply-recipient" type="text" class="form-control"
                                                   id="recipient" name="recipient" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 form-group">
                                            <label for="subject">
                                                Subject:</label>
                                            <input id="inbox-reply-subject" type="text" class="form-control"
                                                   name="subject" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 form-group">
                                            <label for="message">
                                                Message:</label>
                                            <div name="inbox-reply-body" id="inbox-reply-body"></div>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 form-group">
                                            <button id="send-inbox-reply-button" type="button"
                                                    class="btn btn-lg btn-default pull-right">Send →
                                            </button>
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
    <button type="button" data-toggle='modal' data-dismiss="modal" href='#inbox-reply-modal'
            class="btn btn-outline-primary btn-lg btn-block">Reply
    </button>
    <button type="button" onclick="alert('this feature not available at the present')"
            class="btn btn-outline-primary btn-lg btn-block">Forward
    </button>
    {{--<iframe id="bodyframe" style="width: 100%; min-height: 100%;" src="{{ url("/Mail/Body/" . $mail->id) }}"></iframe>--}}
    <script>
        $(document).ready(function () {

            ClassicEditor.create( document.querySelector('#inbox-reply-body'))
                .then( editor => {
                    window.replyeditor = editor;

                    $height = $('#inbox-reply-body').height() + 200;
                    replyeditor.ui.view.editable.editableElement.style.height = $height + 'px';

                } )
                .catch( err => {
                    console.error( err.stack );
                } );

            $('#inbox-reply-modal').on('show.bs.modal', function (event) {
                var email = '<?php echo $mail->sender ?>';
                var subject = '<?php echo $mail->subject ?>';
                // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
                $("#inbox-reply-recipient").val(email);
                $("#inbox-reply-subject").val('Reply: ' + subject);
                $('#inbox-reply-modal').data('email', email);
                $('#inbox-reply-modal').data('type', 'ReplyEmail');
            });

            $("#send-inbox-reply-button").unbind().click(function()
            {
                $("body").addClass("loading");
                $data = {};
                $data['_token'] = "{{ csrf_token() }}";

                $data['email'] = $('#inbox-reply-modal').data('email');
                $data['contact_type'] = "Client";
                $data['subject'] = $('#inbox-reply-subject').val();
                $data['body'] = replyeditor.getData();
                $data['type'] = $('#inbox-reply-modal').data('type');
                post = $.post("/Email/SendFromPopupCompose", $data);

                post.done(function( data ) {
                    $("body").removeClass("loading");
                    switch(data['status']) {
                        case "OK":
                            $('#inbox-reply-modal').modal('hide');
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
        $windowheight = $('#content').height();
        $headerheight = $('#header').height();

        $('#bodyframe').height($windowheight - $headerheight - 20);

    </script>
@stop


