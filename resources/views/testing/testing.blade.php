@extends('master')

@section('content')
This is a testing page, you should likely not be here. If you accidentally found your way here please let support know how you got here. support@officesweeet.com

<button id="NotifyUser" name="NotifyUser" type="button" class="btn OS-Button" value="">Notify User</button>

<script>
    $( document ).ready(function() {

        $("#NotifyUser").click(function(e) {

            $("body").addClass("loading");
            posting = $.post("/test/SendEmail",
                {
                    _token: "{{ csrf_token() }}",
                    subject: 'test email',
                    body: 'test email',
                    email: 'movian@gmx.com'
                });


            posting.done(function (data) {
                $("body").removeClass("loading");
                switch (data['status']) {
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
                    default:
                        $.dialog({
                            title: 'Oops...',
                            content: 'Unknown Response from server. Please refresh the page and try again.' + data['status']
                        });
                }
            });

            posting.fail(function () {
                $("body").removeClass("loading");
                $.dialog({
                    title: 'Error!',
                    content: 'Failed to contact server, please try again later.'
                });
            });
        });
    });
</script>
@stop