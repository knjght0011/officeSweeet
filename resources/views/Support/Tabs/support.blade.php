<div class="col-sm-12">
    <h3>Contact us by choosing from the options below</h3>
    <br>
    <form action="" method="post" accept-charset="utf-8" class="form-horizontal">
        <h4>Subject</h4><hr>
        <div class="form-group">
            <div class="col-sm-12">
                <select id="subject" name="subject" class="form-control"><!--Suggestion, Complaint, Technical Support, General Inquiry.-->
                    <!--<option value="0">Select</option>-->
                <!--<optgroup label="Accounts and tariff">-->
                        <option value="Suggestion">Suggestion</option>
                        <option value="Complaint">Complaint</option>
                    <!--</optgroup>-->
                    <!--<optgroup label="Sales and upgrades">-->
                        <option value="Technical Support">Technical Support</option>
                        <option value="General Inquiry">General Inquiry</option>
                        <!--<option value="Sales and upgrades:Where's my order?">Where's my order?</option>
                    </optgroup>
                    <optgroup label="Returns or disconnections">
                        <option value="Returns or disconnections:Returning a product">Returning a product</option>
                        <option value="Returns or disconnections:Cancelling my contract">Cancelling my contract</option>
                        <option value="Returns or disconnections:Cancelling my contract early">Cancelling my contract early</option>
                    </optgroup>
                    <optgroup label="Something else">
                        <option value="Something else:General enquiries">General enquiries</option>
                        <option value="Something else:Complaints">Complaints</option>
                    </optgroup>-->
                </select>
            </div>
        </div>
        <br>
        <br>
        <h4>Write Your Message</h4><hr>
        <p><small>To help us answer your email quickly, please give us as much information about your query as you can</small></p><br>
        <div class="form-group">
            <div class="col-sm-12">
                <textarea id="message" class="form-control" placeholder="Enter your message here" rows="8" name="message" cols="50" required=""></textarea>
            </div>
        </div>
         
        <button id="send-support-email" name="save" type="button" class="btn OS-Button">Send</button>
        <button name="save" type="button" class="btn OS-Button" onclick="GoToPage('/Tickets')">See Support Tickets</button>
    </form>
</div>

<script>
$(document).ready(function() {
    $("#send-support-email").click(function()
    {
        $("body").addClass("loading");

        $data = {};
        $data['_token'] = "{{ csrf_token() }}";
        $data['subject'] = $('#subject').val();
        $data['message'] = $('#message').val();

        $post = $.post("/Tickets/NewThread", $data);

        $post.done(function (data) {
            $("body").removeClass("loading");
            switch(data['status']) {
                case "OK":
                    $.dialog({
                        title: 'Success!',
                        content: 'Your support ticket has been created. you will hear from us soon.'
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

        $post.fail(function () {
            NoReplyFromServer();
        });
    });
    
});

function PostEmail($subject, $message) {
    return $.post("/Email/Support",
    {
        _token: "{{ csrf_token() }}",
        subject: $subject,
        message: $message
    });
}
</script>