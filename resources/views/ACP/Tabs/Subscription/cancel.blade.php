<div class="row" style="margin-top: 50px">
    <div class="container">
        <p style="font-size: x-large;"><b>We’re sorry to see you go…</b></p>
        <p style="font-size: x-large;">Perhaps there was something we missed.</p>
    </div>
</div>
<div class="row" style="margin-top: 50px; padding-left: 100px;">
    <p style="font-size: large;">Please choose one:</p>
     <p style="font-size: x-large;"><input type="radio" name="cancel-reason" value="tools"> OfficeSweeet didn’t provide me the tools I needed to run my business</p>
     <p style="font-size: x-large;"><input type="radio" name="cancel-reason" value="learn"> It was too hard to learn</p>
     <p style="font-size: x-large;"><input type="radio" name="cancel-reason" value="value"> I didn’t feel that it was a good value</p>
        <p style="font-size: x-large;"><input type="radio" name="cancel-reason" value="time"> I needed more time to evaluate Office Sweeet</p>
     <p style="font-size: x-large;"><input type="radio" name="cancel-reason" value="other"> Other:</p>

</div>

<div class="row" id="cancel-reason-other" style="padding-left: 100px; padding-right: 100px;">
    <textarea id="sub-other-text" class="form-control" style="width: 100%; resize: none;"></textarea>
</div>

<div class="row" id="cancel-reason-time">
    <p style="font-size: x-large;text-align: center;"><b>$10 trial for an additional 30 days</b></p>
    <p style="font-size: large;text-align: center;">Your account will remain active for 30 more days; one user for $10.</p>
</div>

<div class="row" style="margin-top: 50px; padding-left: 300px; padding-right: 300px;">
    <input id="sub-cancel-submit" class="btn btn-primary btn-block active" type="submit" value="Submit"></div>
</div>
<script>
    $('#cancel-reason-time').hide();
    $('#cancel-reason-other').hide();

    $('#sub-cancel-submit').click(function(){

        
        $radio = $('input[name=cancel-reason]:checked').val();
        
        switch($radio) {
            case "tools":
                ScriptionCancel("didn’t provide me the tools I needed to run my business", "");

                break;
            case "learn":
                ScriptionCancel("It was too hard to learn", "");

                break;
            case "value":
                ScriptionCancel("I didn’t feel that it was a good value", "");

                break;
            case "other":
                $text = $('#sub-other-text').val();
                if($text === ""){
                    $.dialog({
                        title: 'Oops...',
                        content: 'Please tell us what we could have done better to serve you.'
                    });
                }else{
                     ScriptionCancel("Other", $text);
                }
                break;
            case "time":
                SubScriptionUpdate("1", "10.00", true);

                break;
        }        
        
        
    });

    $( 'input[name="cancel-reason"]:radio' ).change(function () {
        switch($(this).val()) {
            case "tools":
                if($('#cancel-reason-other').is(":visible")){
                    $('#cancel-reason-other').fadeOut("slow");
                }
                if($('#cancel-reason-time').is(":visible")){
                    $('#cancel-reason-time').fadeOut("slow");
                }
                break;
            case "learn":
                if($('#cancel-reason-other').is(":visible")){
                    $('#cancel-reason-other').fadeOut("slow");
                }
                if($('#cancel-reason-time').is(":visible")){
                    $('#cancel-reason-time').fadeOut("slow");
                }
                break;
            case "value":
                if($('#cancel-reason-other').is(":visible")){
                    $('#cancel-reason-other').fadeOut("slow");
                }
                if($('#cancel-reason-time').is(":visible")){
                    $('#cancel-reason-time').fadeOut("slow");
                }
                break;
            case "other":
                if($('#cancel-reason-time').is(":visible")){
                    $('#cancel-reason-time').fadeOut("slow");
                }
                $('#cancel-reason-other').fadeIn("slow");
                break;
            case "time":
                if($('#cancel-reason-other').is(":visible")){
                    $('#cancel-reason-other').fadeOut("slow");
                }
                $('#cancel-reason-time').fadeIn("slow");
                break;
        }
        
    });
    
    function ScriptionCancel($reason, $text){

        $("body").addClass("loading");
        posting = $.post("/Subscription/Cancel",
        {
            _token: "{{ csrf_token() }}",
            reason: $reason,
            text: $text
        });

        posting.done(function( data ) {

            //refresh to details page
            switch(data) {
                case "sucess":
                    GoToPage("/ACP/Subscription/sub-summery")
                    break;
                default:
                    $("body").removeClass("loading");
                    $.dialog({
                        title: 'Oops...',
                        content: 'Unknown error! Please try again later.'
                    });
                    break;
            }
        });

        posting.fail(function() {
            $("body").removeClass("loading");
            $.dialog({
                title: 'Oops...',
                content: 'Lost contact witht her server, please try again later.'
            });
        });          
    }
    
    function SubScriptionUpdate($numberofusers, $price, $timedowngrade){
        
        $("body").addClass("loading");
        posting = $.post("/Subscription/Update",
        {
            _token: "{{ csrf_token() }}",
            numberofusers: $numberofusers,
            price: $price,
            timedowngrade: $timedowngrade,
        });

        posting.done(function( data ) {
            
            //refresh to details page
            switch(data) {
                case "sucess":
                    GoToPage("/ACP/Subscription/sub-summery")
                    break;
                default:
                    $("body").removeClass("loading");
                    $.dialog({
                        title: 'Oops...',
                        content: 'Unknown error! Please try again later.'
                    });
                    break;
            }
        });

        posting.fail(function() {
            $("body").removeClass("loading");
            $.dialog({
                title: 'Oops...',
                content: 'Lost contact witht her server, please try again later.'
            });
        });        
    }
</script>

