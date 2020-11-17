@include('OS.CustomTable.header')

<style>
    .element {
        margin-bottom: 10px;
    }
</style>

<div style="padding: 10px;">
    <button style="width: 100%;" id="savetabs" name="savetabs" type="button" class="btn OS-Button" >Save Before Leaving</button>
</div>

<div id="table-content" style="padding: 10px;">

{!! $table->HTML() !!}

</div>

<div class="modal fade" id="NoteModal" role="dialog" aria-labelledby="NoteModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: none;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="NoteModal-Title">Event:</h4>
            </div>
            <div class="modal-body" style="padding-top: 0px;">
                <div class="input-group " style="width: 100%; padding-bottom: 5px;">
                    <input style="z-index: 100000; width: 23%; float: left;" id="start-date" name="start-date" class="form-control" readonly>
                    <input style="z-index: 100000; width: 23%; float: left;" id="start-time" name="start-time" class="form-control" readonly data-autoclose="true">
                    <div style="width: 8%; float: left; text-align: center; padding-top: 7px;"> To </div>
                    <input style="z-index: 100000; width: 23%; float: left;" id="end-date" name="end-date" class="form-control" readonly>
                    <input style="z-index: 100000; width: 23%; float: left;" id="end-time" name="end-time" class="form-control" readonly data-autoclose="true">
                </div>

                {!! Form::OSinput("title", "Title", "", "", "true", "") !!}

                {!! Form::OStextarea("notes", "Note", "", "", "true", "") !!}

                <div class="input-group ">
                    <span class="input-group-addon" for="event-userid"><div style="width: 15em;">User:</div></span>
                    <select id="event-userid" name="event-userid" class="form-control">
                        <option value="0">None</option>
                        @foreach(UserHelper::GetAllUsers() as $user)
                            <option value="{{ $user->id }}">{{ $user->email }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer" style="border-top: none;">
                <button id="SaveEvent" name="SaveEvent" type="button" class="btn OS-Button" value="">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<div class="modalload"><!-- Place at bottom of page --></div>

<script>
$(document).ready(function() {

    @foreach($table->Data($dataid) as $key => $value)
    $input = document.getElementById('customtab-input-{{ $key }}');
    if($input != null){
        $input.value = '{{$value}}';
    }
    @endforeach

    $('.date').datepicker({
        changeMonth: true,
        changeYear: true,
        controlType: 'select',
        parse: "loose",
        dateFormat: "yy-mm-dd",
    });

    $("#savetabs").click(function()
    {
        $("body").addClass("loading");

        $thistabdata = {};

        $inputs = $('#table-content').find("input");
        $.each($inputs, function( index, value ) {
            $thistabdata[$(this).attr("name")] = $(this).val();
        });

        $textarea = $('#table-content').find("textarea");
        $.each($textarea, function( index, value ) {
            $thistabdata[$(this).attr("name")] = $(this).val();
        });

        $selects = $('#table-content').find("select");
        $.each($selects, function( index, value ) {
            $thistabdata[$(this).attr("name")] = $(this).val();
        });

        posting = PostTabData($thistabdata);

        posting.done(function( data ) {
            $("body").removeClass("loading");
        });

        posting.fail(function() {
            $("body").removeClass("loading");
            alert('fail');
        });
    });

    $('.datebutton').click(function () {

        $date = $(this).parent().parent().find('input').val();

        $("#notes").val("");
        $("#title").val("");

        $('#start-date').val($date);
        $('#start-time').val("12:00");
        $('#end-date').val($date);
        $('#end-time').val("14:00");

        $('#NoteModal').modal('show');

    });

    $('#SaveEvent').click(function () {

        $event = {};
        $event['id'] = 0;
        $event['title'] =  $("#title").val();
        $event['note'] =  $("#notes").val();
        $event['userid'] = parseInt($("#event-userid").val());

        $event['start'] =  moment($("#start-date").val() + " " + $('#start-time').val()).format('YYYY-MM-DD HH:mm:ss');
        $event['end'] =  moment($("#end-date").val() + " " + $('#end-time').val()).format('YYYY-MM-DD HH:mm:ss');

        $event['linkedid'] = "{{ $dataid }}";
        $event['linkedtype'] = "{{ $table->type }}";

        $event['reminderdate'] = "Click here to set date.";
        $event['reminderemails'] = [];

        $event['repeats'] = 0;
        $event['repeat_freq'] = 0;

        $("body").addClass("loading");

        posting = $.post("/Scheduling/Save",
            {
                _token: "{{ csrf_token() }}",
                event: $event
            });

        posting.done(function( data ) {

            $("body").removeClass("loading");
            switch(data['status']) {
                case "OK":
                    $('#NoteModal').modal('hide');
                    $.confirm({
                        autoClose: 'Close|1000',
                        title: "Success!",
                        content: 'Saved',
                        backgroundDismiss: true,
                        buttons: {
                            Close: function () {

                            }
                        }
                    });
                    break;
                case "error":
                    ServerValidationErrors(data);
                    break;
                default:
                    console.log(data);
                    $.dialog({
                        title: 'Oops...',
                        content: 'Unknown Response from server. Please refresh the page and try again.'
                    });
            }

        });

        posting.fail(function() {
            $("body").removeClass("loading");
            alert('failed try again');
        });
    });
});

function PostTabData($tabdata) {

    return $.post("/CustomTables/Contents/Save",
        {
            _token: "{{ csrf_token() }}",
            tableid: "{{ $table->id }}",
            dataid: "{{ $dataid }}",
            tabdata: $tabdata,
        });
}
</script>

@include('OS.CustomTable.footer')