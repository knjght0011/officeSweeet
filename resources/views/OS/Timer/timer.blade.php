@extends('windowmaster')

@section('content')
<style>
    .timerwindow{
        position: absolute;
        width: 400px;
        height: 236px;
        background-color: White;
        border: 2px;
        border-style: solid;
        border-color: black;
        z-index: 9999;

    }

    .timerbar{
        width: 100%;
        height: 50px;
        color: black;
        font-size: 20px;
        text-align: center;
        background-color: lightblue;
    }

    .timerbutton{
        width: 50px;
        height: 50px;
        padding: 0px;
        margin-left: 10px;
        margin-right: 10px;
        margin-top: 5px;
        margin-bottom: 5px;
        color: black;
    }
</style>

<div name="timerwindow" class="timerwindow">
    <div class="timerbar">
        @if(isset($client))
            Timer for: {{ $client->getName() }}
        @else
            Timer
        @endif
    </div>
    <div style="height: calc(100% - 50px); width: 70px; float: left;">
        <button id="timer-start" class="timerbutton">Start</button>
        <button id="timer-reset" class="timerbutton">Reset</button>
        @if(isset($client))
            <button href="#" id="timer-log" class="timerbutton" >Log</button>
        @endif
    </div>
    <div style="height: calc(100% - 50px); width: 70px; float: left;">
        <input style="width: 50px; height: 80px; margin-left: 10px; margin-right: 10px; margin-top: 35px; font-size: 53px; text-align: center;" id="timer-hours" value="1" readonly>
        <div style="width: 100%; text-align: center;">Hours</div>
    </div>

    <div style="height: calc(100% - 50px); width: 28px; float: left; text-align: center; font-size: 60px; padding-top: 20px;">:</div>

    <div style="height: calc(100% - 50px); width: 100px; float: left;">
        <input style="width: 70px; height: 80px; margin-left: 10px; margin-right: 10px; margin-top: 35px; font-size: 53px; text-align: center;" id="timer-minutes" value="23" readonly>
        <div style="width: 100%; text-align: center;">Minutes</div>
    </div>

    <div style="height: calc(100% - 50px); width: 28px; float: left; text-align: center; font-size: 60px; padding-top: 20px;">:</div>

    <div style="height: calc(100% - 50px); width: 100px; float: left;">
        <input style="width: 70px; height: 80px; margin-left: 10px; margin-right: 10px; margin-top: 35px;  font-size: 53px; text-align: center;" id="timer-seconds" value="45" readonly>
        <div style="width: 100%; text-align: center;">Seconds</div>
    </div>
</div>
@if(isset($client))
<div class="timerwindow" style="top:237px;">
    {!! Form::OSinput("billable-rate", "Hourly Rate ($)", "", 0, "true", "", "number") !!}
    {!! Form::OSinput("billable-hours", "Hours", "", 0, "true", "", "number") !!}
    {!! Form::OStextarea("billable-comment", "Comments") !!}
    <button id="billable-save" type="button" class="btn btn-primary" style="width: 100%; height: 50px;">Save To {{ TextHelper::GetText("Client") }}</button>
</div>
@endif
<script>
    $("body").css("overflow", "hidden");

$('#timer-log').click(function () {
    _StopWatch.stop();

    window.resizeTo("416", "540");
    $mins = _StopWatch.minutes();
    $hours = _StopWatch.hours();


    $m = parseInt(($mins / 60) * 100);

    if($m < 10){
        $m = "0" + $m;
    }

    $time = $hours + "." + $m;

    $('#billable-hours').val($time);

});
@if(isset($client))
$('#billable-save').click(function () {

    $("body").addClass("loading");

    $data = {};
    $data['_token'] = "{{ csrf_token() }}";
    $data['client_id'] = "{{ $client->id }}";
    $data['rate'] = $('#billable-rate').val();
    $data['hours'] = $('#billable-hours').val();
    $data['comment'] = $('#billable-comment').val();

    $post = $.post("/Clients/AddBillableHours", $data);

    $post.done(function (data) {


        $("body").removeClass("loading");
        $.confirm({
            autoClose: 'Close|2000',
            title: "Saved!",
            content: "Hours Saved.",
            buttons: {
                Close: function () {

                }
            }
        });

    });

    $post.fail(function () {

        $("body").removeClass("loading");
        $.dialog({
            title: 'Oops...',
            content: 'Failed to contact server. Please try again later.'
        });
    });
});
@endif

function StopWatch($timerhours = null, $timerminutes = null, $timerseconds = null){

    var startTime = new Array();
    var stopTime = new Array();
    var running = false;
    var timerhours = $timerhours;
    var timerminutes = $timerminutes;
    var timerseconds = $timerseconds;

    this.debug = function(){

        var day = new Date(2017, 11, 8, 14, 0, 0);
        startTime[0] = day.getTime();
    };

    this.start = function(){

        if (running == true) {
            return;
        }else if (startTime.length === 0){
            startTime[0] = getTime();
            stopTime[0] = null;
        }else{
            next = startTime.length;

            startTime[next] = getTime();
            stopTime[next] = null;
        }

        running = true;
    };

    this.stop = function(){

        if (running == false)
            return;

        stopTime[startTime.length - 1] = getTime();
        running = false;

    };

    this.reset = function(){

        if (running == true)
            return;

        startTime = new Array();
        stopTime = new Array();

    };

    this.checkRunning = function(){
        return running;
    };

    this.duration = function(){

        if(startTime == null || stopTime == null){
            return 'Undefined';
        }else{
            return (stopTime - startTime) / 1000;
        }
    };

    this.split = function(){

        time = 0;

        $.each( startTime, function( key, value ) {
            if(stopTime[key] === null){
                time = time + ((getTime() - value) / 1000);
            }else{
                time = time + ((stopTime[key] - value) / 1000);
            }
        });

        return time;
    };

    this.seconds = function() {
        $seconds = parseInt(this.split() - ((this.minutes() * 60) + (((this.hours() * 60) * 60))) ).toString();
        if($seconds.length === 1){
            $seconds = "0" + $seconds;
        }
        return $seconds;
    };

    this.minutes = function() {
        $minutes = parseInt((this.split() / 60) - (this.hours() * 60)).toString();
        if($minutes.length === 1){
            $minutes = "0" + $minutes;
        }
        return $minutes;
    };

    this.hours = function() {
        $hours = parseInt((this.split() / 60) / 60).toString();
        return $hours;
    };

    this.update = function() {

        seconds = this.seconds();
        minutes = this.minutes();
        hours = this.hours();

        if(timerhours != null){
            timerhours.val(hours);
        }
        if(timerminutes != null){
            timerminutes.val(minutes);
        }
        if(timerseconds != null){
            timerseconds.val(seconds);
        }

        return hours + ":" + minutes + ":" + seconds;

    };

    this.time = function() {

        seconds = this.seconds();
        minutes = this.minutes();
        hours = this.hours();

        return hours + ":" + minutes + ":" + seconds;
    };

    this.init = function($object){
        window.setInterval(function() { $object.update(); }, 100, $object);
    };

    this.init(this);
}

function getTime(){
    var day = new Date();
    return day.getTime();
}


var _StopWatch = new StopWatch($('#timer-hours'), $('#timer-minutes'), $('#timer-seconds'));


$( "#timer-start" ).click(function() {
    if(_StopWatch.checkRunning()){
        _StopWatch.stop();
        $(this).html('Start');
        //$( "#timer-reset" ).prop('disabled', 'true');
    }else{
        _StopWatch.start();
        $(this).html('Stop');
        //$( "#timer-reset" ).prop('disabled', 'false');
    }
});

$( "#timer-reset" ).click(function() {
    _StopWatch.reset();
});
</script>

<div class="modalload"><!-- Place at bottom of page --></div>
@stop