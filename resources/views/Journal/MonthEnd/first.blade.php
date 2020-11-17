@extends('master')

@section('content')  

    <div class="container" style="padding-top: 50px;">
        <h3>Please watch the Getting started with the Journal video before continuing.</h3>

        <div class="input-group ">
            <span class="input-group-addon" for="start-date"><div style="width: 50em;">Select the date you wish to start your accounting in Office Sweeet:</div></span>
            <input id="start-date" name="start-date" type="text" placeholder="" value="{{ $date->toDateString() }}" class="form-control" readonly>
        </div>
        <div class="input-group ">
            <span class="input-group-addon" for="start-amount"><div style="width: 50em;">Now enter your beginning balance ($):</div></span>
            <input id="start-amount" name="start-amount" type="number" placeholder="" value="0.00" class="form-control numonly" >
        </div>
        <button id="save" class="OS-Button btn" style="width: 100%; margin-top: 5px;">Save</button>
    </div>

<script>   
$(document).ready(function(){

    $('.numonly').on('keypress', function(e) {
        keys = ['0','1','2','3','4','5','6','7','8','9','.'];
        return keys.indexOf(event.key) > -1;
    });

    $('#start-date').datepicker({
        changeMonth: true,
        changeYear: true,
        inline: true,
        dateFormat: "yy-mm-dd",
        maxDate: "{{ $date->toDateString() }}",
    });

    $('#save').click(function () {

        $("body").addClass("loading");
        $data = {};
        $data['_token'] = "{{ csrf_token() }}";
        $data['date'] = $('#start-date').val();
        $data['amount'] = $('#start-amount').val();

        $post = $.post("/Journal/MonthEnd/First", $data);

        $post.done(function (data) {

            switch(data['status']) {
                case "OK":
                    GoToPage('/Journal/View');
                    break;
                case "notfound":
                    $("body").removeClass("loading");
                    $.dialog({
                        title: 'Oops...',
                        content: 'Unknown Response from server. Please refresh the page and try again.'
                    });
                    break;
                case "validation":
                    $("body").removeClass("loading");
                    ServerValidationErrors(data['errors']);
                    break;
                default:
                    $("body").removeClass("loading");
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
</script>
@stop
