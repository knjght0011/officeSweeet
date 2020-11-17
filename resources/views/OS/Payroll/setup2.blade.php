@extends('master')

@section('content')
<div class="col-md-3"></div>

<div class="col-md-6" style="margin-top: 20px;">

    @if($freqencysetting->value === "weekly")
    <div id="1" class="row" style="margin-top: 20px;">
        <legend>Weekly</legend>
        <input id="period-start" name="period-start" type="text" placeholder="" class="form-control" readonly="true">
        <script>
            $("#period-start").val("Please Select the date you would like your first payroll period in OfficeSweeet to end.");
            $("#period-start").datepicker({
                changeMonth: true,
                changeYear: true,
                inline: true,
                onSelect: function(dateText, inst) {
                    $val = moment(dateText).format("YYYY-MM-DD");
                    $("#period-start").val($val);
                },
                beforeShowDay: highlightDays
                });

                function highlightDays(date) {

                    $moment = moment(date);
                    var dow = $moment.day();
                    
                    var arr;
                    if (dow === {{ $option }}) {
                        arr = [true, ''];
                    }else {
                        arr = [false, ''];
                    }
                    return arr;
                    
                }
            
        </script>

        
    </div>
    @endif
    
    @if($freqencysetting->value === "biweekly")
    <div id="2" class="row" style="margin-top: 20px;">
        <legend>Biweekly</legend>
        <input id="period-start" name="period-start" type="text" placeholder="" class="form-control" readonly="true">
        <script>
    $("#period-start").val("Please Select the date you would like your first payroll period in OfficeSweeet to end.");
            $("#period-start").datepicker({
                changeMonth: true,
                changeYear: true,
                inline: true,
                onSelect: function(dateText, inst) {
                    $val = moment(dateText).format("YYYY-MM-DD");
                    $("#period-start").val($val);
                },
                beforeShowDay: highlightDays
                });

                function highlightDays(date) {

                    $moment = moment(date);
                    var dow = $moment.day();
                    
                    var arr;
                    if (dow === {{ $option }}) {
                        arr = [true, ''];
                    }else {
                        arr = [false, ''];
                    }
                    return arr;
                    
                }
            
        </script>

        
    </div>
    </div>
    @endif
    
    @if($freqencysetting->value === "semimonthly")
    <div id="3" class="row" style="margin-top: 20px;">
        <legend>Semimonthly</legend>

        <input id="period-start" name="period-start" type="text" placeholder="" class="form-control" readonly="true">
        <script>
            $("#period-start").val("Please Select the date you would like your first payroll period in OfficeSweeet to end.");
            $("#period-start").datepicker({
                changeMonth: true,
                changeYear: true,
                inline: true,
                onSelect: function(dateText, inst) {
                    $val = moment(dateText).format("YYYY-MM-DD");
                    $("#period-start").val($val);
                },
                beforeShowDay: highlightDays
                });

                function highlightDays(date) {

                    $moment = moment(date);
                    var dom = $moment.date();
                    $month = $moment.format('M');
                    $month2 = $moment.add(1, 'days').format("M");
                    
                    var arr;
                    if (dom === {{ $option }} || $month !== $month2) {
                        arr = [true, ''];
                    }else {
                        arr = [false, ''];
                    }
                    return arr;
                    
                }
            
        </script>

        
    </div>
    </div>
    @endif
    
    @if($freqencysetting->value === "monthly")
    <div id="4" class="row" style="margin-top: 20px;">
        <legend>Monthly</legend>

        <input id="period-start" name="period-start" type="text" placeholder="" class="form-control" readonly="true">
        <script>
            $("#period-start").val("Please Select the date you would like your first payroll period in OfficeSweeet to end.");
            $("#period-start").datepicker({
                changeMonth: true,
                changeYear: true,
                inline: true,
                onSelect: function(dateText, inst) {
                    $val = moment(dateText).format("YYYY-MM-DD");
                    $("#period-start").val($val);
                },
                beforeShowDay: highlightDays
                });

                function highlightDays(date) {
                    @if($option === "Last")
                    $moment = moment(date);
                    $month = $moment.format('M');
                    $month2 = $moment.add(1, 'days').format("M");
                    
                    var arr;
                    if ($month === $month2) {
                        arr = [false, ''];
                    }else {
                        arr = [true, ''];
                    }
                    return arr;


                    @else
                    $moment = moment(date);
                    var dom = $moment.date();
                    
                    var arr;
                    if (dom === {{ $option }}) {
                        arr = [true, ''];
                    }else {
                        arr = [false, ''];
                    }
                    return arr;
                    @endif
                }
            
        </script>        
    </div>
    @endif
    <button id="next" name="next" type="button" class="btn OS-Button">Next</button>
</div>
<script>    
$(document).ready(function() {

    
    $("#next").click(function()
    {
        $start = $('#period-start').val();

        if($start === null){
            $.dialog({
                title: 'Oops...',
                content: 'Please select an option.'
            });
            
            $input.addClass('invalid');
        
            throw new Error("Validation Error");
        
        }

        $("body").addClass("loading");
        $post = $.post("/Payroll/Setup2",
        {
            _token: "{{ csrf_token() }}",
            start: $start
        });
        
        $post.done(function( data ) 
        {
            $("body").removeClass("loading");
            switch(data['status']) {
                case "OK":
                    location.reload();
                    break;
                case "validation":
                    ServerValidationErrors(data['errors']);
                    break;
                default:
                    console.log(data);
                    $.dialog({
                        title: 'Oops...',
                        content: 'Unknown Response from server. Please refresh the page and try again.'
                    });
            }

        });

        $post.fail(function() {
            NoReplyFromServer();
        });
    });
    
});
</script>
@stop