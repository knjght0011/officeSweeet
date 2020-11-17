@extends('master')

@section('content')

<div class="row">
    <div class="col-md-12" style="font-size: x-large !important;">{{ $employee->firstname }} {{ $employee->middlename }} {{ $employee->lastname }} - {{ $employee->employeeid }}</div>
</div>
<div class="row">
    <div class="col-md-12" style="font-size: larger !important;">{{ $employee->address->number }} {{ $employee->address->address1 }} {{ $employee->address->address2 }} {{ $employee->address->city }} {{ $employee->address->state }} {{ $employee->address->zip }}</div>
</div>

<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#details" aria-controls="home" role="tab" data-toggle="tab">Details</a></li>
    <li role="presentation"><a href="#file" aria-controls="profile" role="tab" data-toggle="tab">Documents</a></li>
    <li role="presentation"><a href="#notes-tab" aria-controls="profile" role="tab" data-toggle="tab">Notes</a></li>
    @if(Auth::user()->hasPermission('login_management_permission'))
    <li role="presentation"><a href="#login" aria-controls="home" role="tab" data-toggle="tab">Login Management</a></li>
    @endif
    <li role="presentation"><a href="#checks" aria-controls="profile" role="tab" data-toggle="tab">Checks</a></li>
    <li role="presentation"><a href="#clocks" aria-controls="profile" role="tab" data-toggle="tab">Timesheet</a></li>
    <li role="presentation"><a href="#receipts" aria-controls="profile" role="tab" data-toggle="tab">Receipts</a></li>
    <li role="presentation"><a href="#compensation" aria-controls="profile" role="tab" data-toggle="tab">Compensation</a></li>
    <li role="presentation"><a href="#billablehours-tab" aria-controls="profile" role="tab" data-toggle="tab">Billable Hours</a></li>
    <li role="presentation"><a href="#training-tab" aria-controls="profile" role="tab" data-toggle="tab">Training</a></li>
    @if(Auth::user()->os_support_permission === "1")
    <li role="presentation"><a href="#debug" aria-controls="profile" role="tab" data-toggle="tab">Debug</a></li>
    @endif
</ul>

<div class="tab-content">

    <div role="tabpanel" class="tab-pane active" id="details">
        @include('Employees.view.details')
    </div>

    <div role="tabpanel" class="tab-pane" id="file">
        <br>
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#docs" aria-controls="profile" role="tab" data-toggle="tab">Documents</a></li>
            <li role="presentation" ><a href="#email-docs" aria-controls="profile" role="tab" data-toggle="tab">Emailed Documents</a></li>
            <li role="presentation" ><a href="#fileupload" aria-controls="profile" role="tab" data-toggle="tab">Uploaded Files</a></li>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="docs">
                @include('Employees.view.file')
            </div>

            <div role="tabpanel" class="tab-pane" id="email-docs">
                @include('Employees.view.signing')
            </div>

            <div role="tabpanel" class="tab-pane " id="fileupload">
                @include('Employees.view.filestore')
            </div>
        </div>
    </div>


    @if(Auth::user()->hasPermission('login_management_permission'))
    <div role="tabpanel" class="tab-pane" id="login">
        @if( $employee->canlogin == 1)
            @include('Employees.view.login')
        @else
            @include('Employees.view.loginsetup')
        @endif
    </div>
    @endif
    
    <div role="tabpanel" class="tab-pane " id="checks">
        @include('Employees.view.checks')
    </div>    
    
    <div role="tabpanel" class="tab-pane " id="clocks">
        @include('Employees.view.clocks')
    </div>
    
    <div role="tabpanel" class="tab-pane" id="receipts">
        @include('Employees.view.receipts')
    </div>
    
    <div role="tabpanel" class="tab-pane" id="compensation">
        @include('Employees.view.compensation')
    </div>

    <div role="tabpanel" class="tab-pane" id="notes-tab">
        @include('Employees.view.notes')
    </div>

    <div role="tabpanel" class="tab-pane" id="training-tab">
        @include('Employees.view.training')
    </div>

    @if(Auth::user()->os_support_permission === "1")
    <div role="tabpanel" class="tab-pane" id="debug">
        @include('Employees.view.debug')
    </div>
    @endif

    <div role="tabpanel" class="tab-pane" id="billablehours-tab">
        @include('Employees.view.billablehours')
    </div>
</div>

@include('OS.FileStore.fileuploadmodel')
@include('OS.FileStore.displayfile')
@include('Employees.modals.emaildocument')

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
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){

    $('#ShowPdfModel').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal

        $('#ShowPdfFrame').attr("src", button.data('url'));
        $('#ShowPdfModelTitle').html(button.data('title'));

        var mode = button.data('mode');
        switch(mode) {
            case "pdf":

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
    });

    @if($page != null)
    $('a[href$="#{{ $page }}"]').click();
    @endif

    $("#AddNoteButton").click(function()
    {

        $("body").addClass("loading");

        $note = jQuery("textarea[name='note']").val();


        post = $.post("/Employees/AddNote",
            {
                _token: "{{ csrf_token() }}",
                user_id: "{{ $employee->id }}",
                note: $note

            });

        post.done(function( data )
        {

            $("body").removeClass("loading");
            if(data === "success"){

                $date = GetMeTheDate();

                SavedSuccess('Your note was saved successfully.');

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

    });
});

$('#myTabs a[href="#details"]').tab('show')
$('#myTabs a[href="#debug"]').tab('show')


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