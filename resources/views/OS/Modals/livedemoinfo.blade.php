@if(Session::has('demopopup'))
<script>
$(document).ready(function() {

    $.alert({
        title: 'Welcome To OfficeSweeet Live Demo!',
        columnClass: 'col-md-12',
        content:    '<p>Please read…</p>\n' +
                    '<p>Feel free to click around to get the feel of OfficeSweeet.  Since this is merely a sample system, we cannot have all the functionality that you normal would have if it was your own.</p>\n' +
                    '<p>Later, you will be given an opportunity to have your own unique database to try for a while.  For now, this little “live demo” will give you a bit of the look and feel of this comprehensive business management solution.</p>\n' +
                    '<p>Feel free to watch the training videos as they only take a few minutes.</p>\n' +
                    '<p>To locate these videos, notice the navigation bar on the left. Towards the bottom you will see the Support link. This will take you to a few tabs related to support. Open the Video Tutorials tab and click the link to Introduction to OfficeSweeet to get started.</p>\n' +
                    '<p>Any information you add here in this system will be deleted ay the end of the day.</p>\n' +
                    '<p>For your privacy, some features have been disabled in this “live demo”.</p>\n' +
                    '<p>This has been created for you specifically to get a quick feel of the system. Please do not add any personal information.</p>\n' +
                    '<p>Questions? You can send us any messages using the Contact Support tab in the Support section (bottom left). We always want to hear your feedback.</p>\n' +
                    '<p>If you like what you see, click the button below to receive a completely free trial with your own database.  If you would like to continue using OfficeSweeet, you may elect to do so in the Admin Control Panel within your system.  No up sell either. What you see is what you get.</p>\n' +
                    '<p>Enjoy!</p>',
        buttons: {
            "Sign Up Now!": function(){
                window.open('https://www.officesweeet.com/free-trial', 'Sign Up');
                return false; // you shall not pass
            },
            close: function(){
            }
        }
    });

});
</script>
@php
session::forget('demopopup');
@endphp
@endif