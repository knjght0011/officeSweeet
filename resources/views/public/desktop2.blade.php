@extends('master')

@section('content')
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
        <select id="schedule-view" name="schedule-view" class="form-control" style="z-index: 0;">
            <option value="agendaDay">Team View</option>
            <option value="timelineDay">Day</option>
            <option value="listDay">List Day</option>
            <option value="timelineThreeDays">3 Days</option>
            <option value="agendaWeek">Week</option>
            <option value="listWeek">List Week</option>
            <option value="month">Month</option>
        </select>
    </div>

    <button class="btn OS-Button btn-sm" id="new-event" type="button" style="width: 100%; height: 100%;">New Event</button>

    <button class="btn OS-Button btn-sm" type="button" style="width: 100%; height: 100%;"  data-toggle="modal" data-target="#FilterModal" >Department Filter</button>

    <div id='external-events'>
        <h4>Draggable Events</h4>
        <div class='fc-event'>Custom Name</div>
        @foreach($generalevents as $generalevent)
            <div data-duration="{{ $generalevent->duration }}" class='fc-event'>{{ $generalevent->eventname }} {{--({{ $event->duration }})--}}</div>
        @endforeach

    </div>
</div>

<div id='calendar' style="position: relative;">
    <div class="calload"></div>
</div>

@include('public.common')

@include('public.CalendarDecelerationDesktop')

<div class="modal fade" id="FilterModal" tabindex="-1" role="dialog" aria-labelledby="FilterModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Filter Departments to show only those you wish to see</h4>
            </div>
            <div class="modal-body">
                @foreach(PageElement::Departments() as $department)
                    <div class="checkbox" style="width: 100%;">
                        <label style="width: 100%;">
                            <input type="checkbox" name="checkboxes" class="department" data-on="Show" data-off="Hide" data-toggle="toggle" data-width="50%" data-name="{{ $department }}">
                            {{ $department }}
                        </label>
                    </div>
                @endforeach
                    <div class="checkbox" style="width: 100%;">
                        <label style="width: 100%;">
                            <input type="checkbox" name="checkboxes" class="department" data-on="Show" data-off="Hide" data-toggle="toggle" data-width="50%" data-name="None" checked>
                            None
                        </label>
                    </div>
            </div>
            <div class="modal-footer">
                <button id="scheduler-reset-filter" name="scheduler-reset-filter" type="button" class="btn OS-Button" value="">Reset All</button>
                <button id="scheduler-save-filter" name="scheduler-save-filter" type="button" class="btn OS-Button" value="">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


@stop
