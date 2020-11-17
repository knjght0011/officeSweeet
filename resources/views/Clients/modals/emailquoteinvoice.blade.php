<div class="modal fade EmailQuoteModal" id="EmailQuoteModal" tabindex="-1" role="dialog" aria-labelledby="EmailQuoteModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="exampleModalLabel">Send To:</h4>
            </div>
            <div class="modal-body">
                <label for="selectbasic">Select Email Address:</label>
                <select id="quoteemailaddressid" name="quoteemailaddressid" class="form-control">
                    @foreach ($client->contacts as $contact)
                    <option value="{{ $contact->id }}">{{ $contact->email }}</option>
                    @endforeach
                </select>
                <label for="quoteemailsubject">Subject:</label>
                <input type="text" class="form-control" id="quoteemailsubject" list="quoteemailsubjects-list">
                <datalist  id="quoteemailsubjects-list" name="quoteemailsubjects-list">
                    @foreach(PageElement::EmailSubjects() as $subject)
                        <option>{{ $subject->subject }}</option>
                    @endforeach
                </datalist>

                <label for="quoteemailbodytext">Body:</label>
                <textarea style="resize: none;" id="quoteemailbodytext"class="form-control" rows="15">
                @if(isset($settings['clientquotetemplate']))
                {{ $settings['clientquotetemplate'] }}
                @else
                Please see attached
                @endif
                </textarea>
                <input style="visibility: hidden;" type="text" class="form-control" id="quoteid">
            </div>
            <div class="modal-footer">
                <button id="SendQuote" name="SendQuote" type="button" class="btn OS-Button SendQuote" value="">Send</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script>
    
$('#EmailQuoteModal').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget); // Button that triggered the modal
    var quoteid = button.data('quoteid');
    var quotenumber = button.data('quotenumber');// Extract info from data-* attributes
    var type = button.data('type');
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    var modal = $(this);
    
    $quotetempate = "{{ SettingHelper::GetSetting('clientquotetemplate') }}";
    $invoicetempate = "{{ SettingHelper::GetSetting('clientinvoicetemplate') }}";
    
    //modal.find('.modal-body input[id="quoteemailsubject"]').val("Here is your " + type + " number: #" + quotenumber);
    //modal.find('.modal-body input[id="quoteid"]').val(quoteid);

    switch(type) {
        case "invoice":

            modal.find('.modal-body input[id="quoteemailsubject"]').val("Here is your " + type + " number: #" + quotenumber);
            modal.find('.modal-body input[id="quoteid"]').val(quoteid);
            modal.find('.modal-body textarea[id="quoteemailbodytext"]').val($invoicetempate);
            $('#EmailQuoteModal').data('mode', 'invoice');
            break;
        case "quote":

            modal.find('.modal-body input[id="quoteemailsubject"]').val("Here is your " + type + " number: #" + quotenumber);
            modal.find('.modal-body input[id="quoteid"]').val(quoteid);
            modal.find('.modal-body textarea[id="quoteemailbodytext"]').val($quotetempate);
            $('#EmailQuoteModal').data('mode', 'quote');
            break;
        case "overview":

            modal.find('.modal-body input[id="quoteemailsubject"]').val("Here is your Statement");
            modal.find('.modal-body textarea[id="quoteemailbodytext"]').val("overview");
            $('#EmailQuoteModal').data('mode', 'overview');
            break;
    }

});

$(".SendQuote").click(function()
{

    $('#EmailQuoteModal').modal('hide');
    $("body").addClass("loading");

    $data = {};
    $data['_token'] = "{{ csrf_token() }}";
    $data['ContactID'] = $('#quoteemailaddressid').val();
    $data['emailbody'] = $('#quoteemailbodytext').val();
    $data['emailsubject'] = $('#quoteemailsubject').val();

    if($('#EmailQuoteModal').data('mode') ===  'overview'){
        $data['mode'] = "overview";
        $data['ClientID'] = "{{ $client->id }}";
    }else{
        $data['mode'] = "quote/invoice";
        $data['QuoteID'] = $('#quoteid').val();
    }

    post = $.post("/Email/Quote", $data);


    post.done(function( data ) {
        $("body").removeClass("loading");
        if(data === "sent"){
            bootstrap_alert.warning("Email Sent", 'success', 4000);
        }else{
            if(data === "disabled"){
                $.dialog({
                    title: 'Oops...',
                    content: 'This has been disabled during the live demo.'
                });
            }else{
                ServerValidationErrors(data);
            }
        }
    });

    post.fail(function() {
        $("body").removeClass("loading");
        bootstrap_alert.warning("Unable to post data", 'danger', 4000);
    });
});
</script>