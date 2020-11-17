<script>
$(document).ready(function() {
    //$("#ActionListMenu").append('<li class="ActionListMenuItem"><a href="#" id="togglecalc"><span class="glyphicon glyphicon glyphicon-th"></span> Calculator</a></li>');
    //$("#ActionListMenu").append('<li class="ActionListMenuItem"><a href="#" id="toggletimer"><span class="glyphicon glyphicon glyphicon-th"></span> Timer</a></li>');
    $unread = 0;

    //setInterval(UpdateUnread, 20000);
    
    $('#togglecalc').click(function(){
        $(".calcwindow").css("display", "block");
    });

    $('.toggletimer').click(function(){

        $height = screen.height - 600;
        $width = screen.width - 500;

        $settings = "height= 236px, ";
        $settings += "width= 400px, ";
        $settings += "left=" + $width.toString()  + ",";
        $settings += "top=" + $height.toString()  + ",";
        $settings += "toolbar=no,";
        $settings += "titlebar=no,";
        $settings += "menubar=no,";
        $settings += "scrollbars=no,";
        $settings += "resizable=no";

        $id = $(this).data('clientid');
        if($id === undefined){
            window.open('/Timer/', '', $settings)
        }else{
            window.open('/Timer/' + $id, '', $settings)
        }
    });
});



bootstrap_alert = function () {}
bootstrap_alert.warning = function (message, alert, timeout) {

    if(alert === "danger"){
        alert = "Oops...";
    }
    
    $.dialog({
        title: alert,
        content: message
    });

};


bootstrap_alert_old = function () {}
bootstrap_alert_old.warning = function (message, alert, timeout) {
    $('<div id="floating_alert" class="alert alert-' + alert + ' fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' + message + '&nbsp;&nbsp;</div>').appendTo('body');

    setTimeout(function () {
        $(".alert").alert('close');
    }, timeout);

}

// request permission on page load
document.addEventListener('DOMContentLoaded', function () {
  if (!Notification) {
    alert('Desktop notifications not available in your browser. Try Chromium.'); 
    return;
  }

  if (Notification.permission !== "granted")
    Notification.requestPermission();
});


function notifyMe($title, $message, $url) {
  if (Notification.permission !== "granted")
    Notification.requestPermission();
  else {
    var notification = new Notification($title, {
      //icon: '{{ url("/favicon-32x32.png") }}',
      body: $message,
    });

    notification.onclick = function () {
      window.open($url);      
    };

    return true;
  }
}

function AddressLookup($zip, $done) {

    $("body").addClass("loading");

    $data = {};
    $data['_token'] = "{{ csrf_token() }}";
    $data['zip'] = $zip;

    $post = $.post("/Address/Lookup", $data);

    $post.done(function ($addressdata) {
        $("body").removeClass("loading");
        switch($addressdata['status']) {
            case "OK":
                PopulateAddressFields($addressdata['data']);
                if($done != null){
                    $done();
                }
                break;
            case "error":
                $.dialog({
                    title: 'Oops..',
                    content: $addressdata['reason']
                });
                break;
            default:
                $.dialog({
                    title: 'Oops...',
                    content: 'Unknown Response from server. Please refresh the page and try again.'
                });
        }
    });

    $post.fail(function () {
        NoReplyFromServer();
    });

    function PopulateAddressFields($addressdata){
        $.each($addressdata, function (index, value) {
            $('#number').prop('disabled', false);
            switch (index) {
                case "postal_code":
                    $('#zip').val(value);
                case "state_province":
                    if (value == "") {
                        $('#state').prop('disabled', false);
                        $('#state').val(value);
                    } else {
                        $('#state').prop('disabled', true);
                        $('#state').val(value);
                    }
                    break;
                case "region":
                    if (value == "") {
                        $('#region').prop('disabled', false);
                        $('#region').val(value);
                    } else {
                        $('#region').prop('disabled', true);
                        $('#region').val(value);
                    }
                    break;
                case "city":
                    if (value == "") {
                        $('#city').prop('disabled', false);
                        $('#city').val(value);
                    } else {
                        $('#city').prop('disabled', true);
                        $('#city').val(value);
                    }
                    break;
                case "address2":
                    if (value == "") {
                        $('#address2').prop('disabled', false);
                        $('#address2').val(value);
                    } else {
                        $('#address2').prop('disabled', true);
                        $('#address2').val(value);
                    }
                    break;
                case "address1":
                    if (value == "") {
                        $('#address1').prop('disabled', false);
                        $('#address1').val(value);
                    } else {
                        $('#address1').prop('disabled', true);
                        $('#address1').val(value);
                    }
                    break;
            }
        });
    }
}

function SavedSuccess($content = 'Data Saved', $title = "Success!", $timeout = "1000"){
    $.confirm({
        autoClose: 'Close|' + $timeout,
        title: $title,
        content: $content,
        backgroundDismiss: true,
        buttons: {
            Close: function () {

            }
        }
    });
}

function NoReplyFromServer() {
    $("body").removeClass("loading");
    $.dialog({
        title: 'Oops...',
        content: 'Failed to contact server. Please try again later.'
    });
}


function ValidateInput($input) {
   
    $input.removeClass('invalid');
    
    $error = false;

    $value = $input.val();
    
    $type = $input.data('validation-type');
    if(typeof $type === 'undefined'){
        $type = "";
    }
    
    $label = $input.data('validation-label');
    if(typeof $label === 'undefined'){
        $label = "Highlighted input";
    }
    
    $required = $input.data('validation-required');
    
    
    switch($type) {
        case "string":
            
            break;
        case "date":
            if($value === ""){
                $.dialog({
                    title: 'Oops...',
                    content: $label + ' must be a selected'
                });
                $error = true;
            }
            break;            
        case "amount":
            if(!$.isNumeric($value)){
                $.dialog({
                    title: 'Oops...',
                    content: $label + ' must be a number'
                });
                $error = true;
            }
            break;
        default:
            
    }

    if($required === true){
        if($value === ""){
            $.dialog({
                title: 'Oops...',
                content: $label + ' is Required'
            });
            $error = true;
        }        
    }
    
    if($error === true){
        $input.addClass('invalid');
        throw new Error("Validation Error");
    }

    return $value;
}

function GoToPage($link) {
    var link = document.createElement('a');
    link.href = $link;
    link.id = "link";
    document.body.appendChild(link);
    link.click();    
}

function GoToPageNewTab($link) {
    window.open("/Clients/View/"+ $link + '#file');
    window.open("/Clients/View/"+ $link + '#emails');}

function AddPopup($element, $position, $text) {
    $element.data( "toggle", "popover" );
    $element.data( "placement", $position );
    $element.data( "trigger", "hover" );
    $element.data( "content", $text );
    
    $element.popover();
}

function NotLogedIN() {

    $.confirm({
        title: 'Alert!',
        content: 'You have been logged out of Office Sweeet due to inactivity. Please log back in and try again.',
        buttons: {
            Login: function () {
                window.location.reload();
            },
        }
    });
}

function ResetServerValidationErrors(){
    $('.invalid').removeClass('invalid');
}

function ServerValidationErrors($array) {

    $.each($array, function (index, value) {
        $('#' + index).addClass('invalid');
    });

    $text = "";
    $.each($array, function( index, value ) {
        $text = $text + value + "<br>";
    });
    $.dialog({
        title: 'Validation Error',
        content: $text
    });
}
function SendNotification2($subject, $message, $email) {

    $subject = "ALERT: " + $subject;
    $recipient = [];
    $recipient.push($email);

    $("body").addClass("loading");
    posting = $.post("/messages",
        {
            _token: "{{ csrf_token() }}",
            subject: $subject,
            message: $message,
            recipients: $recipient
        });

    posting.done(function( data ) {
        $("body").removeClass("loading");
        $.dialog({
            title: 'Done',
            content: 'User Notified'
        });
    });

    posting.fail(function() {
        $("body").removeClass("loading");
        $.dialog({
            title: 'Error!',
            content: 'Failed to contact server, please try again later.'
        });
    });
}

function SendNotification($subject, $message, $email) {

    $("body").addClass("loading");
    posting = $.post("/Notifications/New",
    {
        _token: "{{ csrf_token() }}",
        subject: $subject,
        message: $message,
        recipients: $email
    });

    posting.done(function( data ) {
        $("body").removeClass("loading");
        switch(data['status']) {
            case 'OK':
                $.dialog({
                    title: 'Done',
                    content: 'User Notified'
                });
                break;
            case 'USERNOTFOUND':
                $.dialog({
                    title: 'Oops...',
                    content: 'Unable to find User.'
                });
                break;
            case "notlogedin":
                NotLogedIN();
                break;
            default:
                $.dialog({
                    title: 'Oops...',
                    content: 'Unknown Response from server. Please refresh the page and try again.'
                });
        }
    });

    posting.fail(function() {
        $("body").removeClass("loading");
        $.dialog({
            title: 'Error!',
            content: 'Failed to contact server, please try again later.'
        });
    });
}

function SendNotificationandEmail($subject, $message, $email) {
    $("body").addClass("loading");
    posting = $.post("/Notifications/NewAndEmail",
        {
            _token: "{{ csrf_token() }}",
            subject: $subject,
            message: $message,
            recipients: $email
        });

    posting.done(function( data ) {
        $("body").removeClass("loading");
        switch(data['status']) {
            case 'OK':
                $.dialog({
                    title: 'Done',
                    content: 'User Notified'
                });
                break;
            case 'USERNOTFOUND':
                $.dialog({
                    title: 'Oops...',
                    content: 'Unable to find User.'
                });
                break;
            case "notlogedin":
                NotLogedIN();
                break;
            default:
                $.dialog({
                    title: 'Oops...',
                    content: 'Unknown Response from server. Please refresh the page and try again.'
                });
        }
    });

    posting.fail(function() {
        $("body").removeClass("loading");
        $.dialog({
            title: 'Error!',
            content: 'Failed to contact server, please try again later.'
        });
    });
}

function EmailPatientList($message, $email) {
    $("body").addClass("loading");
    posting = $.post("/Notifications/PatList",
        {
            _token: "{{ csrf_token() }}",
            message: $message,
            email: $email
        });

    posting.done(function( data ) {
        $("body").removeClass("loading");
        switch(data['status']) {
            case 'OK':
                $.dialog({
                    title: 'Done',
                    content: 'Patient List Sent'
                });
                break;
            case 'USERNOTFOUND':
                $.dialog({
                    title: 'Oops...',
                    content: 'Unable to find User.'
                });
                break;
            case "notlogedin":
                NotLogedIN();
                break;
            default:
                $.dialog({
                    title: 'Oops...',
                    content: 'Unknown Response from server. Please refresh the page and try again.'
                });
        }
    });

    posting.fail(function() {
        $("body").removeClass("loading");
        $.dialog({
            title: 'Error!',
            content: 'Failed to contact server, please try again later.'
        });
    });
}

function SendEmail($subject, $message, $email) {

    $("body").addClass("loading");
    posting = $.post("/Email/SendNotification",
        {
            _token: "{{ csrf_token() }}",
            subject: $subject,
            body: $message,
            email: $email
        });

    posting.done(function( data ) {
        $("body").removeClass("loading");
        switch(data['status']) {
            case 'OK':
                $.dialog({
                    title: 'Done',
                    content: 'User Notified'
                });
                break;
            case 'USERNOTFOUND':
                $.dialog({
                    title: 'Oops...',
                    content: 'Unable to find User.'
                });
                break;
            case "notlogedin":
                NotLogedIN();
                break;
            default:
                $.dialog({
                    title: 'Oops...',
                    content: 'Unknown Response from server. Please refresh the page and try again.'
                });
        }
    });

    posting.fail(function() {
        $("body").removeClass("loading");
        $.dialog({
            title: 'Error!',
            content: 'Failed to contact server, please try again later.'
        });
    });
}

function PageinateUpdate(info, $nextbutton, $prevbutton, $textlocation){

    $prevbutton.prop('disabled', false);
    $nextbutton.prop('disabled', false);

    $textlocation.html(
        'Currently showing page '+(info.page+1)+' of '+info.pages+' pages.'
    );

    if(info.page === 0){
        $prevbutton.prop('disabled', true);
    }

    if((info.page+1) === info.pages){
        $nextbutton.prop('disabled', true);
    }
}

function htmlEncode(value){
    //create a in-memory div, set it's inner text(which jQuery automatically encodes)
    //then grab the encoded contents back out.  The div never exists on the page.
    return $('<div/>').text(value).html();
}


@if(app()->make('account')->plan_name === "SOLO")

@else
    @if(Session::exists('unread'))
        $.confirm({
            title: 'New Message!',
            content: 'You have {{ Session::pull("unread") }} unread message(s). Would you like to go to your messages?',
            buttons: {
                "READ NOW": function () {
                    GoToPage("/messages");
                },
                "READ LATER": function () {

                }
            }
        });
    @endif
@endif
</script>