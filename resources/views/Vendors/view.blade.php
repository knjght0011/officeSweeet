@extends('master')

@section('content')
<div id="view-header">
    <div class="row">
        <div class="col-md-12" style="font-size: x-large !important;">{{ $vendor->getName() }}</div>
    </div>
    <div class="row">
        <div class="col-md-12" style="font-size: larger !important;">Tel: <a href="tel:{{ $vendor->phonenumber }}">{{ $vendor->phonenumber }}</a></div>
    </div>
    <div class="row">
        <div class="col-md-12" style="font-size: larger !important;">E-mail: <a href="mailto:{{ $vendor->email }}">{{ $vendor->email }}</a></div>
    </div>
    <div class="row">
        <div class="col-md-12" style="font-size: larger !important;">{{ $vendor->custom }}</div>
    </div>
    <div class="row">
        <div class="col-md-12" style="font-size: larger !important;">{!! PageElement::GoogleAddressLink($vendor->address) !!}</div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
             @include('Vendors.view.primarycontact')
        </div>
    </div>     
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation"><a href="#schedule" aria-controls="profile" role="tab" data-toggle="tab">Schedule</a></li>
        <li role="presentation"><a href="#notes" aria-controls="profile" role="tab" data-toggle="tab">Notes</a></li>
        <li role="presentation" class="active"><a href="#file" aria-controls="profile" role="tab" data-toggle="tab">Documents</a></li>
        @if(Auth::User()->hasPermissionMulti('multi_assets_permission', 1))
        <li role="presentation"><a href="#products" aria-controls="profile" role="tab" data-toggle="tab">Products</a></li>
        @endif
        <li role="presentation"><a href="#checks" aria-controls="profile" role="tab" data-toggle="tab">Checks</a></li>
        <li role="presentation"><a href="#receipts" aria-controls="profile" role="tab" data-toggle="tab">Expenses</a></li>
        <li role="presentation"><a href="#contacts" aria-controls="profile" role="tab" data-toggle="tab">Contacts</a></li>
        <li role="presentation"><a href="#tabs" aria-controls="profile" role="tab" data-toggle="tab">Tabs</a></li>
        @if(Auth::user()->os_support_permission === "1")
        <li role="presentation"><a href="#debug" aria-controls="profile" role="tab" data-toggle="tab">Debug</a></li>
        @endif
    </ul>
</div>
<div id="view-tabs">
    <div class="tab-content" style="height: calc(100% - 50px);">
        
        <div role="tabpanel" class="tab-pane " id="contacts">
            @include('Vendors.view.contacts')
        </div>

        @if(Auth::User()->hasPermissionMulti('multi_assets_permission', 1))
        <div role="tabpanel" class="tab-pane " id="products">
            @include('Vendors.view.products')
        </div>
        @endif

        <div role="tabpanel" class="tab-pane" id="schedule">
            @include('Vendors.view.schedule')
        </div>

        <div role="tabpanel" class="tab-pane active" id="file">
            <br>
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#docs" aria-controls="profile" role="tab" data-toggle="tab">Documents</a></li>
                <li role="presentation" ><a href="#email-docs" aria-controls="profile" role="tab" data-toggle="tab">Emailed Documents</a></li>
                <li role="presentation"><a href="#purchaseorders" aria-controls="profile" role="tab" data-toggle="tab">Purchase Orders</a></li>
                <li role="presentation" ><a href="#filestore" aria-controls="profile" role="tab" data-toggle="tab">Uploaded Files</a></li>
            </ul>
            
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="docs">
                    @include('Vendors.view.file')
                </div>

                <div role="tabpanel" class="tab-pane" id="email-docs">
                    @include('Vendors.view.signing')
                </div>

                <div role="tabpanel" class="tab-pane" id="purchaseorders">
                    @include('Vendors.view.purchaseorders')
                </div>

                <div role="tabpanel" class="tab-pane" id="filestore">
                    @include('Vendors.view.filestore')
                </div>
            </div>
            
        </div>

        <div role="tabpanel" class="tab-pane" id="checks">
            @include('Vendors.view.checks')
        </div>
        
        <div role="tabpanel" class="tab-pane" id="receipts">
            @include('Vendors.view.receipts')
        </div>
        
        <div role="tabpanel" class="tab-pane" id="tabs"  style="height: 100%;">
            @include('Vendors.view.tabs')
        </div>
        
        <div role="tabpanel" class="tab-pane" id="notes">
            @include('Vendors.view.notes')
        </div>
        @if(Auth::user()->os_support_permission === "1")
        <div role="tabpanel" class="tab-pane" id="debug">
            @include('Vendors.view.debug')
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
                <button id="EmailOnPDFModal" data-toggle="modal" data-target="#EmailQuoteModal" type="button" class="btn btn-secondary" data-dismiss="modal" style="display: none;">Email Invoice</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


@include('Vendors.view.modals.emaildocument')
@include('OS.FileStore.fileuploadmodel')
@include('OS.FileStore.displayfile')

<script>   
$(document).ready(function(){

    $('title').html('OS - Vendor - {{ $vendor->getName() }}');

    $('#ShowPdfModel').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal

        $('#ShowPdfFrame').attr("src", button.data('url'));
        $('#ShowPdfModelTitle').html(button.data('title'));

        var mode = button.data('mode');
        switch(mode) {
            case "PO":

                $table = $('#purchaseordersearch').DataTable();
                $row = $table.row('.selected').data();
                var url = "/PurchaseOrders/PDF/";
                var id = $row[4];

                $('#ShowPdfFrame').attr("src", url + id);
                break;
            case "details":


                break;
            case "overview":

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

    $("#AddNoteButton").click(function()
    {
        $("body").addClass("loading");
        $note = jQuery("textarea[name='note']").val();
        post = $.post("/Vendors/AddNote",
        {
            _token: "{{ csrf_token() }}",
            vendorid: "{{ $vendor->id }}",
            note: $note

        });
        
        post.done(function( data ) 
        {
            $("body").removeClass("loading");
            if(data === "success"){
                
                $date = GetMeTheDate();

                $.dialog({
                    title: 'Perfect!',
                    content: 'Your note was successfully added'
                });

                //bootstrap_alert.warning("Note Added", 'danger', 4000);
                window.notestable.row.add( [
                    $note,
                    "{{ Auth::user()->email }}",
                    $date
                ] ).draw( false );

                
                $('#addnote').modal('hide');
            }else{
                ServerValidationErrors(data);
            }
        });
        
        post.fail(function() {
            $("body").removeClass("loading");
            bootstrap_alert.warning("Failed to post", 'danger', 4000);
        });
            
        $("body").removeClass("loading");
    });

    @if($tab != null)
    $('a[href$="#{{ $tab }}"]').click();
    @endif

});
    
$('.nav-tabs a').click(function (e) {
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
</script>
@stop
