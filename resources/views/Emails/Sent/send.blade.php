@extends('master')

@section('content') 
<div class="row">
    <div class="col-md-6">
        {!! Form::OSinput("from", "From", "", Auth::user()->email, "true", "") !!}
    </div>
    <div class="col-md-6">
        <button style="width: 100%;" id="send" name="send" type="button" class="btn OS-Button">Send</button>
    </div>
    <div class="col-md-12">
        {!! Form::OSinput("to", "To", "", "", "true", "") !!}
    </div>
    <div class="col-md-12">
        {!! Form::OSinput("subject", "Subject", "", "", "true", "") !!}
    </div>
    <div class="col-md-12">
        <form>
            <textarea name="body" id="body" style="resize: none; width: 100%; height: 100%; ">

            </textarea>
        </form>
    </div>

</div>
<script>
$(document).ready(function() {
    $('#from').attr("readonly" , true);
    var ckEditor = new CKEDITOR.replace( 'body',
    {
        docType:  '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">',
        enterMode : CKEDITOR.ENTER_P,
        shiftEnterMode: CKEDITOR.ENTER_BR,
        entities: 'false',
        entities_additional : '',
        entities_greek : 'false',
        entities_latin : 'false',
        height: '600',
        resize_enabled : false,
        toolbar:  'Basic'
    });   
    
    $("#send").click(function()
    {
        $from = $('#from').val();
        $to = $('#to').val();
        $subject = $('#subject').val();
        $body = ckEditor.getData();
        SendMail($from, $to, $subject, $body);
    }); 
});

function SendMail($from, $to, $subject, $body) {

    $("body").addClass("loading");
    posting = $.post("/Mail/Send",
    {
        _token: "{{ csrf_token() }}",
        from: $from,
        to: $to,
        subject: $subject,
        body: $body

    });

    posting.done(function( data ) {

        $("body").removeClass("loading");
        $.dialog({
            title: 'Sent!',
            content: data
        });

        
            //server validation errors
            //ServerValidationErrors(data);
        
    });

    posting.fail(function() {

        $("body").removeClass("loading");
        $.dialog({
            title: 'Oops!',
            content: "Failed to contact server"
        });
    });
}
</script>
@stop