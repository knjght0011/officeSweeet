@include('header')

<body>
<Div style="text-align: center"><B>Public View</B></Div>
<style>
#calendar {
    padding: 10px;
    float: left;
    min-height: 100%;
    width: calc(100% - 200px);
    height: 100%;
    display: inline-block;
}
</style>

<div style="width:200px; padding-left: 5px;  float:left;">
    <input class="btn btn-default" type="text" id="datepicker" style=" width:100%; margin-top: 10px; z-index: 1000;" readonly>

    <div class="input-group">
        <span class="input-group-addon" for="schedule-view">View</span>
        <select id="schedule-view" name="schedule-view" class="form-control"  style="z-index: 3;">
            <option value="timelineDay">Day</option>
            <option value="listDay">List Day</option>
            <option value="timelineThreeDays">3 Days</option>
            <option value="agendaWeek" selected>Week</option>
            <option value="listWeek">List Week</option>
            <option value="month">Month</option>
        </select>
    </div>

</div>

<div id='calendar' style="position: relative;height: 80%;" >
    <div class="calload"></div>
</div>

@include('public.common')

@include('public.CalendarDecelerationDesktop')

@include('public.Modals.ViewDetailsModel')

</body>

