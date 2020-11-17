<div class="modal fade" id="use-gmail">
    <div style="margin: 2.5vh auto; width: 95vw" class="modal-dialog" role="document">
        <div style="height: 95vh; width: 95vw;" class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Use G-Mail</h4>
            </div>
            <div style="height: calc(95vh - 120px);" class="modal-body">
                <img style="display: block; margin-left: auto; margin-right: auto; width: 40%;" src="{{ url('/images/gmail1.jpg') }}">
                
                <h2 style="text-align: center;">Did you know you can use GMail?</h2>
                <div class="col-md-12">
                    <p>Currently, if you have an email service set up on your machine/computer, OfficeSweeet will use that email client to send your emails.  Likewise, you would use your existing system to receive email from your clients. However, we are offering an alternate approach for those who wish to send mass emails to all of your clients and/or prospects.</p>
                    <p>We offer integration with Gmail whereby you can;</p>
                    <ol>
                        <li>link your personal/individual Gmail account to OfficeSweeet</li>
                        <li>link your company Gmail account to OfficeSweeet or</li>
                        <li>create a new Gmail account for your business through Google that is linked with OfficeSweeet ( for example; yourbusiness@gmail.com)</li>
                    </ol>
                    <p>The advantages to linking a Gmail account with OfficeSweeet will be;</p>
                    <ol>
                        <li>The ability to send mass/bulk email messages to all of your clients and/or prospects.</li>
                        <li>To include many of the other Goggle services offered through this interface.</li>
                    </ol>

                    <p>IMPORTANT: Should you decide to integrate Google services with OfficeSweeet, when asked to allow OfficeSweeet to manage your emails etc., they are merely using the name OfficeSweeet as the application you are using and not our company.  We cannot view your email messages nor would we want to.  We respect your privacy.  We are merely attempting to make it easier for you to use Google services/apps within your OfficeSweeet application.  If you have any questions, feel free to contact us directly at 813-444-5284.  </p>

                </div>
            </div>
            <div class="modal-footer">
                @if(Auth::user()->hasPermission('acp_permission'))
                    <button id="gmail-more" type="button" class="btn btn-secondary">Find Out More!.</button>
                @endif
                <button id="gmail-toggle-off" type="button" class="btn btn-secondary">No Thanks... I'll Use my Own E-Mail Client For Now. Don't Show This Again.</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {

    $('#use-gmail').on('show.bs.modal', function (event) {

    }); 

    $('#use-gmail').on('hide.bs.modal', function (event) {

    });

    $('#gmail-more').click(function(){
        $("body").addClass("loading");
        GoToPage('/ACP/Email')
    });

    $('#gmail-toggle-off').click(function(){
        $("body").addClass("loading");
        var get = $.get( "/Google/PromptOff", function(  ) { });

        get.done(function( data ) {
            $("body").removeClass("loading");
            $('#use-gmail').modal('hide');

            $('a').each(function() {

                if($(this).data('toaddress') !== undefined ){
                    $(this).attr("href", "mailto:" + $(this).data('toaddress'));
                    $(this).removeData('toggle');
                    $(this).removeAttr('data-toggle');
                    $(this).removeData('target');
                    $(this).removeAttr('data-target');
                }
            });
        });
    });
});
</script>