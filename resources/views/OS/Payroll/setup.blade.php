@extends('master')

@section('content')
<div class="col-md-3" style="height: 200px;"></div>

<div class="col-md-6" style="margin-top: 20px;">
    As this is your first time running payroll with OfficeSweeet we need to know how and when you run payroll. please make sure this information is correct 

    {!! Form::OSselect("frequency", "Payroll Frequency ", ["1" => "Weekly", "2" => "Biweekly", "3" => "Semimonthly", "4" => "Monthly"], "", "1", "false", "") !!}

</div>

<div id="1" class="col-md-6" style="margin-top: 20px;">
    <legend>Weekly</legend>
    Your pay period is processed on a weekly basis, Please select the day of the week your period ends.
    {!! Form::OSselect("weeekly", "Day of the Week (Inclusive)", ["Monday" => "Monday", "Tuesday" => "Tuesday", "Wednesday" => "Wednesday", "Thursday" => "Thursday", "Friday" => "Friday", "Saturday" => "Saturday", "Sunday" => "Sunday"], "", "Friday", "false", "") !!}

    <div id="weeekly-text">

    </div>
    
    <button style="margin-top: 15px; float: right;" id="next" name="next" type="button" class="btn OS-Button next">Next</button>
</div>

<div id="2" class="col-md-6" style="margin-top: 20px;">
    <legend>Biweekly</legend>
    Your pay period is processed every other week, Please select the day of the week your period ends.
    {!! Form::OSselect("biweekly", "Day of the Week (Inclusive)", ["Monday" => "Monday", "Tuesday" => "Tuesday", "Wednesday" => "Wednesday", "Thursday" => "Thursday", "Friday" => "Friday", "Saturday" => "Saturday", "Sunday" => "Sunday"], "", "Friday", "false", "") !!}

    <div id="biweekly-text">

    </div>
    
    <button style="margin-top: 15px; float: right;" id="next" name="next" type="button" class="btn OS-Button next">Next</button>
</div>

<div id="3" class="col-md-6" style="margin-top: 20px;">
    <legend>Semimonthly</legend>
    Your pay period is broken down into two periods a month. Please select the day last day of the first period below. 
    {!! Form::OSselect("semimonthly", "Day of the Month (Inclusive)", ["1st/2nd" => "1st", "2nd/3rd" => "2nd", "3rd/3rd" => "3rd", "3rd/4th" => "3rd", "4th/5th" => "4th", "5th/6th" => "5th", "6th/7th" => "6th", "7th/8th" => "7th", "8th/9th" => "8th", "9th/10th" => "9th", "10th/11th" => "10th", "11th/12th" => "11th", "12th/13th" => "12th", "13th/14th" => "13th", "14th/15th" => "14th", "15th/16th" => "15th", "16th/17th" => "16th", "17th/18th" => "17th", "18th/19th" => "18th", "19th/20th" => "19th", "20th/21st" => "20th", "21st/22nd" => "21st", "22nd/23rd" => "22nd", "23rd/24th" => "23rd", "24th/25th" => "24th", "25th/26th" => "25th", "26th/27th" => "26th", "27th/28th" => "27th"], "", "14th/15th", "false", "") !!}
    <div id="semimonthly-text">

    </div>
    
    <button style="margin-top: 15px; float: right;" id="next" name="next" type="button" class="btn OS-Button next">Next</button>
</div>

<div id="4" class="col-md-6" style="margin-top: 20px;">
    <legend>Monthly</legend>
    Your pay period is once a month. Please select the day last day of the period below. 
    {!! Form::OSselect("monthly", "Day of the Month (Inclusive)", ["1st/2nd" => "1st", "2nd/3rd" => "2nd", "3rd/3rd" => "3rd", "3rd/4th" => "3rd", "4th/5th" => "4th", "5th/6th" => "5th", "6th/7th" => "6th", "7th/8th" => "7th", "8th/9th" => "8th", "9th/10th" => "9th", "10th/11th" => "10th", "11th/12th" => "11th", "12th/13th" => "12th", "13th/14th" => "13th", "14th/15th" => "14th", "15th/16th" => "15th", "16th/17th" => "16th", "17th/18th" => "17th", "18th/19th" => "18th", "19th/20th" => "19th", "20th/21st" => "20th", "21st/22nd" => "21st", "22nd/23rd" => "22nd", "23rd/24th" => "23rd", "24th/25th" => "24th", "25th/26th" => "25th", "26th/27th" => "26th", "27th/28th" => "27th",  "Last" => "Last"], "", "Last", "false", "") !!}

    <div id="monthly-text">

    </div>
    
    <button style="margin-top: 15px; float: right;" id="next" name="next" type="button" class="btn OS-Button next">Next</button>
</div>


<script>    
$(document).ready(function() {
    
    $("#2").css("display","none");
    $("#3").css("display","none");
    $("#4").css("display","none");
    
    $("#semimonthly").val("14th/15th");

    $("#frequency").on( 'change', function () {
    
        switch(this.value) {
            case "1":
                $("#1").css("display","block");
                $("#2").css("display","none");
                $("#3").css("display","none");
                $("#4").css("display","none");
                break;
            case "2":
                $("#1").css("display","none");
                $("#2").css("display","block");
                $("#3").css("display","none");
                $("#4").css("display","none");
                break;
            case "3":
                $("#1").css("display","none");
                $("#2").css("display","none");
                $("#3").css("display","block");
                $("#4").css("display","none");
                break;
            case "4":
                $("#1").css("display","none");
                $("#2").css("display","none");
                $("#3").css("display","none");
                $("#4").css("display","block");
                break;                
        }

    });
    
    $("#weeekly").prop("selectedIndex", -1);
    $("#weeekly").on( 'change', function () {
                
        $.dialog({
            title: 'Great!',
            content: 'Your pay period runs every week and finshes on ' + this.value + '. If this is correct press next to continue.'
        }); 
    });
    
    $("#biweekly").prop("selectedIndex", -1);
    $("#biweekly").on( 'change', function () {
        
        $.dialog({
            title: 'Great!',
            content: 'Your pay period runs every other week and finshes on ' + this.value + ' that week.  If this is correct press next to continue.'
        });         
    });    
    
    $("#semimonthly").prop("selectedIndex", -1);
    $("#semimonthly").on( 'change', function () {
        $arr = this.value.split("/");
        
        $.dialog({
            title: 'Great!',
            content: 'Your pay period runs twice a month from the 1st to the ' + $arr[0] + ' and ' + $arr[1] + ' to the last day of the month.  If this is correct press next to continue.'
        });          
        
    });
    
    $("#monthly").prop("selectedIndex", -1);
    $("#monthly").on( 'change', function () {
        if(this.value === "Last"){
            $arr = [];
            $arr[0] = "Last day";
            $arr[1] = "First day";
        }else{
            $arr = this.value.split("/");
        }
        
        $.dialog({
            title: 'Great!',
            content: 'Your pay period runs once a month, Starts on the ' + $arr['1'] + ' and finishes on the '  + $arr['0'] + ' of the month. If this is correct press next to continue.'
        });        
    });    
    
    $(".next").click(function()
    {

        switch($("#frequency").val()) {
            case "1":
                $frequency = "weekly";
                $option = $("#weeekly").val();
                $input = $("#weeekly");
                
                break;
            case "2":
                $frequency = "biweekly";
                $option = $("#biweekly").val();
                $input = $("#biweekly");
                break;
            case "3":
                $frequency = "semimonthly";
                $option = $("#semimonthly").val();
                $input = $("#semimonthly");
                break;
            case "4":
                $frequency = "monthly";
                $option = $("#monthly").val();
                $input = $("#monthly");
                break;                
        }

        if($option === null){
            $.dialog({
                title: 'Oops...',
                content: 'Please select an option.'
            });
            
            $input.addClass('invalid');
        
            throw new Error("Validation Error");
        
        }
        
        
        $("body").addClass("loading");
        $post = $.post("/Payroll/Setup",
        {
            _token: "{{ csrf_token() }}",
            frequency: $frequency,
            option: $option
        });
        
        $post.done(function( data ) 
        {
            location.reload();
        });

        $post.fail(function() {
            $("body").removeClass("loading");
            $.dialog({
                title: 'Oops...',
                content: 'Lost Contact With Server'
            });
        });
    });
    
});
</script>
@stop