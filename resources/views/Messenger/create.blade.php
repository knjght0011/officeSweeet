<div class="modal fade" id="createmocal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">        
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title">Create New Message</h4>
        </div>

        <div class="modal-body">

            {!! Form::OSinput("messenger-subject", "Subject", "", "", "true", "") !!}

            <div class="input-group ">   
                <span class="input-group-addon" for="message"><div style="width: 15em;">Message:</div></span>
                <textarea id="messenger-message" name="message" type="text" placeholder="" value="" class="form-control" data-validation-label="Subject" data-validation-required="true" data-validation-type=""></textarea>
            </div>
            @if($users->count() > 0)
            <div class="checkbox">
                @foreach($users as $user)
                    @if($user->id != Auth::user()->id)
                    <label title="{{ $user->email }}"><input class="recipients" type="checkbox" name="recipients[]" value="{{ $user->id }}">{!!$user->getShortName()!!}</label>
                    @endif
                @endforeach
            </div>
            @endif
        </div>   

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button id="send-message" name="send-message" type="button" class="btn OS-Button">Submit</button>
        </div>    <!-- Submit Form Input -->
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->  
<script>
$("#send-message").click(function(){

   $recipientsinput = $('.recipients:checkbox:checked');
   if($recipientsinput.length > 0){
        $subject = ValidateInput($('#messenger-subject'));
        $message = $("#messenger-message").val();
        $recipients = [];
        $recipientsinput.each(function(){
            $recipients.push(this.value);
        });
        $('#createmocal').modal('hide');
        $("body").addClass("loading");
        posting = $.post("/messages",
        {
            _token: "{{ csrf_token() }}",
            subject: $subject,
            message: $message,
            recipients: $recipients
        });

        posting.done(function( data ) {
            //$("body").removeClass("loading");
            location.reload();
        });

        posting.fail(function() {
            $("body").removeClass("loading");
            $.dialog({
                title: 'Error!',
                content: 'Failed to contact server, please try again later.'
            });
        });
        
    }else{
        $.dialog({
            title: 'Error!',
            content: 'Please select one or more recipients'
        });
    }
   
});   
</script>