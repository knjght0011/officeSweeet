<div class="" ><h4 style="display: inline; margin-right: 10px;">Primary Contact</h4></div>
@if (is_null($client->primarycontact_id))
    <br><br>No Primary Contact Set
@else
    @desktop
    <table class="table">
        <tr>
            <td>Name:</td>
            <td>{{ $client->primarycontact->firstname }} {{ $client->primarycontact->lastname }}</td>
            <td>Office Phone Number:</td>
            <td><a href="tel:{{ $client->primarycontact->officenumberRAW() }}">{{ $client->primarycontact->officenumber }}</a></td>
        </tr>
        <tr>
            <td>Address:</td>
            <td>{!! PageElement::GoogleAddressLink($client->primarycontact->address) !!}</td>
            <td>Mobile Phone Number:</td>
            <td><a href="tel:{{ $client->primarycontact->mobilenumberRAW() }}">{{ $client->primarycontact->mobilenumber }}</a></td>
        </tr>
        <tr>
            <td>Email:</td>
            <td><a data-toggle='modal' href="#client-view-primary-contact-compose-mail-choose-modal">{{ $client->primarycontact->email }}</a></td>
            <td>Home Phone Number:</td>
            <td><a href="tel:{{ $client->primarycontact->homenumberRAW() }}">{{ $client->primarycontact->homenumber }}</a></td>
        </tr>
    </table>
    @elsedesktop
    <table class="table">
        <tr>
            <td>Name:</td>
            <td>{{ $client->primarycontact->firstname }} {{ $client->primarycontact->lastname }}</td>
        </tr>
        <tr>
            <td>Address:</td>
            <td>{!! PageElement::GoogleAddressLink($client->primarycontact->address) !!}</td>
        </tr>
        <tr>
            <td>Email:</td>
            <td>{!! PageElement::EmailLink($client->primarycontact->email) !!}</td>
        </tr>
        <tr>
            <td>Office Phone Number:</td>
            <td><a href="tel:{{ $client->primarycontact->officenumberRAW() }}">{{ $client->primarycontact->officenumber }}</a></td>
        </tr>
        <tr>
            <td>Mobile Phone Number:</td>
            <td><a href="tel:{{ $client->primarycontact->mobilenumberRAW() }}">{{ $client->primarycontact->mobilenumber }}</a></td>
        </tr>
        <tr>
            <td>Home Phone Number:</td>
            <td><a href="tel:{{ $client->primarycontact->homenumberRAW() }}">{{ $client->primarycontact->homenumber }}</a></td>
        </tr>
    </table>
    @enddesktop
@endif

{{--send mail form--}}
<div class="modal fade" id="client-view-primary-contact-compose-mail" tabindex="-1" role="dialog" aria-labelledby="ShowClientViewComposeMail"
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
                                        <input disabled id="client-view-primary-contact-compose-mail-recipient" type="text" class="form-control" id="recipient" name="recipient" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 form-group">
                                        <label for="subject">
                                            Subject:</label>
                                        <input id="client-view-primary-contact-compose-mail-subject" type="text" class="form-control"  name="subject" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 form-group">
                                        <label for="message">
                                            Message:</label>
                                        <div name="client-view-primary-contact-compose-mail-body" id="client-view-primary-contact-compose-mail-body"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 form-group">
                                        <button id="client-view-primary-contact-compose-mail-button" type="button" class="btn btn-lg btn-default pull-right" >Send →</button>
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
<div class="modal fade bd-example-modal-sm" id="client-view-primary-contact-compose-mail-choose-modal" tabindex="-1" role="dialog" aria-labelledby="client-view-primary-contact-compose-mail-choose-modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel" style="float:left">Please choose an action:</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="float:right">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <button type="button" data-toggle='modal' data-dismiss="modal" href='#client-view-primary-contact-compose-mail' id="client-view-primary-contact-compose-mail-click" class="btn btn-outline-primary btn-lg btn-block">Send email from scratch</button>
                <button type="button" onclick="alert('this feature not available at the present')" class="btn btn-outline-primary btn-lg btn-block">Send email from template</button>
                <button type="button" onclick="alert('this feature not available at the present')" class="btn btn-outline-primary btn-lg btn-block">Send email campaign</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@include('Emails.ckEditorStyle')
@php
    $clours = array(    'Red',
                        'Orange',
                        'Yellow',
                        'Green',
                        'Blue',
                        'Purple',
                        'Brown',
                        'Magenta',
                        'Tan',
                        'Cyan',
                        'Olive',
                        'Maroon',
                        'Navy',
                        'Aquamarine',
                        'Turquoise',
                        'Silver',
                        'Lime',
                        'Teal',
                        'Indigo',
                        'Violet',
                        'Pink',
                        'Black',
                        'White',
                        'Gray');

@endphp
<script>
    $(document).ready(function() {

        //body
        ClassicEditor.create( document.querySelector('#client-view-primary-contact-compose-mail-body'), {
            image: {
                // You need to configure the image toolbar, too, so it uses the new style buttons.
                toolbar: [ 'imageTextAlternative', '|', 'imageStyle:alignLeft', 'imageStyle:Full', 'imageStyle:alignRight' ],
                styles: [
                    // This option is equal to a situation where no style is applied.
                    'full',

                    // This represents an image aligned to the left.
                    'alignLeft',

                    // This represents an image aligned to the right.
                    'alignRight'
                ]
            },
            ckfinder: {
                uploadUrl: '/FileStore/CKEditor'
            },
            highlight: {
                options: [
                        @foreach($clours as $clour)
                    {
                        model: '{{ $clour }}Pen',
                        class: 'pen-{{ $clour }}',
                        title: '{{ $clour }} marker',
                        color: '{{ $clour }}',
                        type: 'pen'
                    },
                    @endforeach
                ]
            },
            fontFamily: {
                options: [
                    'default',
                    'Alegreya Sans',
                    'Alegreya',
                    'Arial',
                    'BioRhyme',
                    'Black Ops One',
                    'Bungee Shade',
                    'Bungee',
                    'Cabin',
                    'Calligraffitti',
                    'Charmonman',
                    'Courier New',
                    'Creepster',
                    'Dancing Script',
                    'Ewert',
                    'Fredericka the Great',
                    'Fruktur',
                    'Georgia',
                    'Gravitas One',
                    'Homemade Apple',
                    'IBM Plex Mono',
                    'IBM Plex Sans Condensed',
                    'IBM Plex Sans',
                    'IBM Plex Serif',
                    'Inconsolata',
                    'Indie Flower',
                    'Italianno',
                    'Loved by the King',
                    'Lucida Sans Unicode',
                    'Merriweather Sans',
                    'Merriweather',
                    'Monoton',
                    'Nanum Brush Script',
                    'Nanum Pen Script',
                    'Nunito Sans',
                    'Nunito',
                    'Pacifico',
                    'Quattrocento Sans',
                    'Quattrocento',
                    'Quicksand',
                    'Roboto Mono',
                    'Roboto Slab',
                    'Roboto',
                    'Rubik',
                    'Satisfy',
                    'Tahoma',
                    'Times New Roman',
                    'Trebuchet MS',
                    'Ubuntu',
                    'Verdana',
                    'VT323',
                ]
            },
            toolbar: [
                'heading',
                '|',
                'alignment',
                'fontSize',
                'fontFamily',
                'Highlight',
                'bold',
                'italic',
                'underline',
                'strikethrough',
                'code',
                'bulletedList',
                'numberedList',
                'imageUpload',
                'blockQuote',
                'insertTable',
                'undo',
                'redo'
            ],
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                    {
                        model: 'headingFancy',
                        view: {
                            name: 'h2',
                            classes: 'fancy'
                        },
                        title: 'Heading 2 (fancy)',
                        class: 'ck-heading_heading2_fancy',

                        // It needs to be converted before the standard 'heading2'.
                        converterPriority: 'high'
                    }
                ]
            }
        } )
            .then( editor => {
                window.clientviewprimarycontacteditor = editor;

                $height = $('#client-view-primary-contact-compose-mail-body').height() + 200;
                clientviewprimarycontacteditor.ui.view.editable.editableElement.style.height = $height + 'px';

            } )
            .catch( err => {
                console.error( err.stack );
            } );

        //choose modal
        $('#client-view-primary-contact-compose-mail-choose-modal').on('show.bs.modal', function (event) {
            var button  = $(event.relatedTarget); // Button that triggered the modal
            var email = button.data('mail'); // Extract info from data-* attributes
            var client_contact_id = button.data('client-contact-id'); // Extract info from data-* attributes
            var recipient_id = button.data('recipient-id'); // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            $('#client-view-primary-contact-compose-mail-choose-modal').data('email', email);
            $('#client-view-primary-contact-compose-mail-choose-modal').data('link_id', client_contact_id);
            $('#client-view-primary-contact-compose-mail-choose-modal').data('contact_id', client_contact_id);
            $('#client-view-primary-contact-compose-mail-choose-modal').data('recipient_id', recipient_id);
            $('#client-view-primary-contact-compose-mail-choose-modal').data('type', 'EmailFromPopupModalToClient');
        });
        $('#client-view-primary-contact-compose-mail').on('show.bs.modal', function (event) {
            var email = '<?php echo $client->primarycontact->email?>';
            var client_contact_id = '<?php echo $client->primarycontact->id?>';
            var recipient_id = '<?php echo $client->id?>';
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            $("#client-view-primary-contact-compose-mail-recipient").val(email);
            $("#client-view-primary-contact-compose-mail-subject").val('');
            clientviewprimarycontacteditor.setData('');
            $('#client-view-primary-contact-compose-mail').data('link_id', client_contact_id);
            $('#client-view-primary-contact-compose-mail').data('contact_id', client_contact_id);
            $('#client-view-primary-contact-compose-mail').data('recipient_id', recipient_id);
            $('#client-view-primary-contact-compose-mail').data('type', 'EmailFromPopupModalToClient');
        });

        $("#client-view-primary-contact-compose-mail-button").unbind().click(function()
        {
            $("body").addClass("loading");
            $data = {};
            $data['_token'] = "{{ csrf_token() }}";

            $data['contact_id'] = $('#client-view-primary-contact-compose-mail').data('contact_id');
            $data['recipient_id'] = $('#client-view-primary-contact-compose-mail').data('recipient_id');
            $data['contact_type'] = "Client";
            $data['email'] = $('#client-view-primary-contact-compose-mail-recipient').val();
            $data['subject'] = $('#client-view-primary-contact-compose-mail-subject').val();
            $data['body'] = clientviewprimarycontacteditor.getData();
            $data['link_id'] = $('#client-view-primary-contact-compose-mail').data('link_id');
            $data['type'] = $('#client-view-primary-contact-compose-mail').data('type');
            post = $.post("/Email/SendFromPopupCompose", $data);

            post.done(function( data ) {
                $("body").removeClass("loading");
                switch(data['status']) {
                    case "OK":
                        $('#client-view-primary-contact-compose-mail').modal('hide');
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
@include('Clients.view.modals.primarycontact')