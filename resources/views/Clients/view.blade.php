@extends('master')

@section('content')  

<div id="view-header">
    <div class="row">
        <div class="col-md-4" style="font-size: x-large !important;">
            {{ $client->getName() }}

        <div class="row">
            <div class="col-md-12" style="font-size: large !important;">Tel: <a href="tel:{{ $client->phonenumber }}">{{ $client->phonenumber }}</a></div>
        </div>
        <div class="row">
            <div class="col-md-12" style="font-size: large !important;">E-mail: <a href="mailto:{{ $client->email }}">{{ $client->email }}</a></div>
        </div>
        </div>
        <div class="col-md-4">
            <button data-toggle="modal" data-target="#ProductModal" type="button" class="btn OS-Button" style="width: 100%;">Quick Sale</button>
        </div>
        <div class="col-md-4">
            <button id="NextClient" type="button" class="btn OS-Button" style="float: right; width: 200px; height: 100%;">Next</button>
            <button id="PreviousClient" type="button" class="btn OS-Button" style="float: right;  width: 200px; height: 100%; margin-right: 10px;">Previous</button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12" style="font-size: larger !important;">{!! PageElement::GoogleAddressLink($client->address) !!}</div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            @include('Clients.view.primarycontact')
        </div>
    </div>
</div>
<div id="view-tabs">
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active" style="padding-top: 5px;"><a href="#details" aria-controls="profile" role="tab" data-toggle="tab">Details</a></li>
        @if(app()->make('account')->HasFeature("patient-list"))
            <li role="presentation" style="padding-top: 5px;"><a href="#patient-list" aria-controls="profile" role="tab" data-toggle="tab">Patients</a></li>
        @endif
        <li role="presentation" style="padding-top: 5px;"><a href="#schedule" aria-controls="profile" role="tab" data-toggle="tab">Schedule</a></li>
        @if(app()->make('account')->subdomain === "lls")
        <li role="presentation" style="padding-top: 5px;"><a href="#osinfo" aria-controls="profile" role="tab" data-toggle="tab">OS Info</a></li>
        @endif       
        <li role="presentation" style="padding-top: 5px;"><a href="#notes" aria-controls="profile" role="tab" data-toggle="tab">Notes</a></li>
        <li role="presentation" style="padding-top: 5px;"><a class="file-click" href="#file" aria-controls="profile" role="tab" data-toggle="tab">Documents</a></li>
        <li role="presentation" style="padding-top: 5px;"><a href="#transactions" aria-controls="profile" role="tab" data-toggle="tab">Transactions</a></li>
        <li role="presentation" style="padding-top: 5px;"><a href="#contacts" aria-controls="profile" role="tab" data-toggle="tab">Contacts</a></li>



        <li role="presentation" style="padding-top: 5px;"><a href="#tabs" aria-controls="profile" role="tab" data-toggle="tab">Tabs</a></li>
        @if(Auth::user()->os_support_permission === "1")
        <li role="presentation" style="padding-top: 5px;"><a href="#debug" aria-controls="profile" role="tab" data-toggle="tab">Debug</a></li>
        @endif
    </ul>
    
    <div class="tab-content" style="height: calc(100% - 50px);">
        
        <div role="tabpanel" class="tab-pane active" id="details">
            @include('Clients.view.details')
        </div>

        <div role="tabpanel" class="tab-pane" id="schedule">
            @include('Clients.view.schedule')
        </div>

        <div role="tabpanel" class="tab-pane" id="contacts">
            @include('Clients.view.contacts')
        </div>
        
        <div role="tabpanel" class="tab-pane" id="file">
            <br>
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#docs" aria-controls="profile" role="tab" data-toggle="tab">Documents</a></li>
                <li role="presentation" ><a class="mail-click" href="#emails" aria-controls="profile" role="tab" data-toggle="tab">Emails Sent</a></li>
                <li role="presentation" ><a class="inbox-mail-click" href="#inbox-emails" aria-controls="profile" role="tab" data-toggle="tab">Emails Received</a></li>
                <li role="presentation" ><a href="#signing" aria-controls="profile" role="tab" data-toggle="tab">Signing Requests</a></li>
                <li role="presentation" ><a href="#quotes" aria-controls="profile" role="tab" data-toggle="tab">{{ TextHelper::GetText("Quotes") }}</a></li>
                <li role="presentation" ><a href="#fileupload" aria-controls="profile" role="tab" data-toggle="tab">Uploaded Files</a></li>
            </ul>
            
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane " id="quotes">
                    @include('Clients.view.quotes')
                </div>
                
                <div role="tabpanel" class="tab-pane active" id="docs">
                    @include('Clients.view.file')
                </div>

                <div role="tabpanel" class="tab-pane" id="emails">
                    @include('Clients.view.emails')
                </div>

                <div role="tabpanel" class="tab-pane" id="inbox-emails">
                    @include('Clients.view.inbox-emails')
                </div>

                <div role="tabpanel" class="tab-pane" id="signing">
                    @include('Clients.view.signing')
                </div>

                <div role="tabpanel" class="tab-pane " id="fileupload">
                    @include('Clients.view.filestore')
                </div>
            </div>
            
        </div>

        <div role="tabpanel" class="tab-pane" id="transactions">
            <br>
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active" style="padding-top: 5px;"><a href="#transactions-overview" aria-controls="profile" role="tab" data-toggle="tab">Overview</a></li>
                <li role="presentation" style="padding-top: 5px;"><a href="#invoices" aria-controls="profile" role="tab" data-toggle="tab">Invoices</a></li>
                <li role="presentation" style="padding-top: 5px;"><a href="#recurringinvoices" aria-controls="profile" role="tab" data-toggle="tab">Recurring Invoices</a></li>
                <li role="presentation" style="padding-top: 5px;"><a href="#checks" aria-controls="profile" role="tab" data-toggle="tab">Checks</a></li>
                <li role="presentation" style="padding-top: 5px;"><a href="#receipts" aria-controls="profile" role="tab" data-toggle="tab">Expenses</a></li>
                <li role="presentation" style="padding-top: 5px;"><a href="#billablehours-tab" aria-controls="profile" role="tab" data-toggle="tab">Billable Hours</a></li>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="transactions-overview">
                    @include('Clients.view.transactions-overview')
                </div>
                <div role="tabpanel" class="tab-pane " id="invoices">
                    @include('Clients.view.invoices')
                </div>
                <div role="tabpanel" class="tab-pane " id="recurringinvoices">
                    @include('Clients.view.recurringinvoices')
                </div>
                <div role="tabpanel" class="tab-pane" id="checks">
                    @include('Clients.view.checks')
                </div>
                <div role="tabpanel" class="tab-pane" id="receipts">
                    @include('Clients.view.receipts')
                </div>
                <div role="tabpanel" class="tab-pane" id="billablehours-tab">
                    @include('Clients.view.billablehours')
                </div>
            </div>
        </div>
        
        <div role="tabpanel" class="tab-pane" id="tabs" style="height: 100%; padding-top: 5px;">
            @include('Clients.view.tabs')
        </div>
        
        <div role="tabpanel" class="tab-pane" id="notes">
            @include('Clients.view.notes')
        </div>

        @if(Auth::user()->os_support_permission === "1")
        <div role="tabpanel" class="tab-pane" id="debug">
            @include('Clients.view.debug')
        </div>
        @endif

        @if(app()->make('account')->HasFeature("patient-list"))
            <div role="tabpanel" class="tab-pane" id="patient-list">
                @include('Clients.view.patient-list')
            </div>
        @endif

        @if(app()->make('account')->subdomain === "lls")
        <div role="tabpanel" class="tab-pane" id="osinfo">
            @include('Clients.view.osinfo')
        </div>
        @endif



    </div>
</div>
<!--Add Note modal-->
<div class="modal fade" id="addnote" tabindex="-1" role="dialog" aria-labelledby="addnoteLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Add Note:</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    
                    <textarea id="AddNoteContent" class="form-control" rows="5" name="note"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button id="AddNoteButton" type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

@include('Clients.modals.sendemail')

@include('Clients.modals.emaildocument')

@include('OS.FileStore.fileuploadmodel')
@include('OS.FileStore.displayfile')

@include('Modals.addinvoicedeposit')

@include('Clients.modals.addproduct')


<div class="modal fade" id="ShowPdfModel" tabindex="-1" role="dialog" aria-labelledby="ShowPdfModel" aria-hidden="true">
    <div style="margin: 2.5vh auto; width: 95vw" class="modal-dialog" role="document">
        <div style="height: 95vh; width: 95vw;" class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="ShowPdfModelTitle">View Invoice</h4>
            </div>
            <div style="height: calc(95vh - 120px);" class="modal-body">
                <iframe style="width: 100%; height: 100%;" id="ShowPdfFrame" src="{{ url('images/loading4.gif') }}"></iframe>
            </div>
            <div class="modal-footer">

                <button id="EmailOnPDFModal"
                        data-toggle="modal"
                        data-target="#send-client-email-modal"
                        data-mode="button"
                        data-type=""
                        data-link_id=""
                        data-subject=""
                        type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal"
                        style="display: none;">Email Invoice</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {

    $('title').html('OS - {{ TextHelper::GetText("Client") }} - {{ $client->getName() }}');

    $('#ShowPdfModel').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal

        $('#ShowPdfFrame').attr("src", button.data('url'));
        $('#ShowPdfModelTitle').html(button.data('title'));

        var mode = button.data('mode');
        switch(mode) {
            case "pdf":
                $('#EmailOnPDFModal').data( "link_id",  button.data('id') );
                $('#EmailOnPDFModal').data( "subject", button.data('quotenumber') );
                $('#EmailOnPDFModal').data('type', 'Invoice');
                $('#EmailOnPDFModal').css('display', 'inline-block');

                break;
            case "details":
                $('#EmailOnPDFModal').data( "link_id",  button.data('id') );
                $('#EmailOnPDFModal').data( "subject", button.data('quotenumber') );
                $('#EmailOnPDFModal').data('type', 'Invoice');
                $('#EmailOnPDFModal').css('display', 'inline-block');

                break;
            case "overview":
                $('#EmailOnPDFModal').data( "link_id",  "{{ $client->id }}" );
                $('#EmailOnPDFModal').data( "subject", "" );
                $('#EmailOnPDFModal').data('type', 'Overview');
                $('#EmailOnPDFModal').html('Email Statement');

                $('#EmailOnPDFModal').css('display', 'inline-block');
                break;
            case "url":

                break;
            default:

        }
    });

    $('#ShowPdfModel').on('hide.bs.modal', function (event) {
        $('#ShowPdfFrame').attr("src", "{{ url('images/loading4.gif') }}");

        $('#EmailOnPDFModal').css('display', 'inline-block');

        $('#EmailOnPDFModal').html('Email Invoice');
        $('#EmailOnPDFModal').css('display', 'none');
    });


    $contentheight = $('#content').css('height');
    $headerheight = $('#view-header').css('height');
    $tapheight = parseInt($contentheight) - parseInt($headerheight) - 20;
    $('#view-tabs').css('height', $tapheight);

    @if($tab != null)
    $('a[href$="#{{ $tab }}"]').click();
    @endif
    
    $("#AddNoteButton").click(function()
    {
        $("body").addClass("loading");
        $note = jQuery("textarea[name='note']").val();
        post = $.post("/Clients/AddNote",
        {
            _token: "{{ csrf_token() }}",
            clientid: "{{ $client->id }}",
            note: $note

        });
        
        post.done(function( data ) 
        {
            $("body").removeClass("loading");
            switch(data['status']) {
                case "OK":
                    $date = GetMeTheDate();

                    SavedSuccess('Your note was saved successfully.');

                    window.notestable.row.add( [
                        $note,
                        "{{ Auth::user()->email }}",
                        $date
                    ] ).draw( false );

                    $('#addnote').modal('hide');

                    break;
                case "validation":
                    ServerValidationErrors(data['errors']);
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
        
        post.fail(function() {
            NoReplyFromServer();
        });

    });

    $('#NextClient').click(function () {
        $("body").addClass("loading");
        GoToPage('/Clients/View/{{ $client->getNextID($client->id) }}')
    });

    $('#PreviousClient').click(function () {
        $("body").addClass("loading");
        GoToPage('/Clients/View/{{ $client->getPreviousID($client->id) }}')
    });

});
    
$('.nav-tabs a').click(function (e) {
    console.log($(this));
  e.preventDefault();
  $(this).tab('show');
});

function load_primay_contact_modal(){
event.preventDefault();
$('#primarycontact-modal').modal('show');
$("#ActionList").hide();
}

function load_add_note_modal(){
event.preventDefault();  
$('#addnote').modal('show');
$("#ActionList").hide();
}

function GetMeTheDate(){
    var d = new Date();
    var yyyy = d.getFullYear().toString();
    var TempMM = d.getMonth()+1;
    var MM = TempMM.toString();
    var DD = d.getDate().toString();

    var HOUR = d.getHours().toString();
    var MIN = d.getMinutes().toString();
    var SECOND = d.getSeconds().toString();

    if(MM.length === 1){
        MM = '0' + MM;
    }
    if(DD.length === 1){
        DD = '0' + DD;
    }
    
    if(HOUR.length === 1){
        HOUR = '0' + HOUR;
    }
    
    if(MIN.length === 1){
        MIN = '0' + MIN;
    }
    
    if(SECOND.length === 1){
        SECOND = '0' + SECOND;
    }
    
    $date = yyyy+'-'+MM+'-'+DD+' '+HOUR+':'+MIN+':'+SECOND;
    
    return $date;
}

$( document ).ready(function() {
    @if(@$type != '')
        $('.file-click').click();
    @endif
    @if(@$child != '')
        $('.mail-click').click();
    @endif

});
</script>
@stop
