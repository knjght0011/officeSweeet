#loading animation

$("body").addClass("loading");
$("body").removeClass("loading");

#alerts

bootstrap_alert.warning("message", 'success', 4000);

success - green
danger - red
info - blue
warning - yellow

#post

$("body").addClass("loading");
ResetServerValidationErrors();

$data = {};
$data['_token'] = "{{ csrf_token() }}";

$post = $.post("/URL", $data);

$post.done(function (data) {
    $("body").removeClass("loading");
    switch(data['status']) {
        case "OK":

            break;
        case "notfound":
            $.dialog({
                title: 'Oops...',
                content: 'Unknown Response from server. Please refresh the page and try again.'
            });
            break;
        case "validation":
            ServerValidationErrors(data['errors']);
            break;
        case "notlogedin":
            NotLogedIN();
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

$.confirm({
    title: 'Confirm!',
    content: 'Simple confirm!',
    buttons: {
        confirm: function () {
            $.alert('Confirmed!');
        },
        cancel: function () {
            $.alert('Canceled!');
        },
        somethingElse: {
            $.alert('somethingElse!');
        }
    }
});

$.dialog({
    title: ' ',
    content: ' '
});

$('#duedate').datepicker({
    changeMonth: true,
    changeYear: true,
    controlType: 'select',
    parse: "loose",
    dateFormat: "yy-mm-dd",
});

$('#out').datetimepicker({
    controlType: 'select',
    parse: "loose",
    dateFormat: "yy-mm-dd",
    timeFormat: "HH:mm",
});

//force 404
return Response::make(view('errors.404'), 404);
