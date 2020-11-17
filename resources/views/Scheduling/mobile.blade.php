@extends('master')

@section('content')
<style>
    .fc-view-container{
        height: 100%;
    }
    .fc-view {
        height: 100%;
    }
    .fc-view > table{
        height: 100%;
    }
    .fc-time-grid-container{
        height: 100% !important;
    }
</style>

<div class="input-group ">
    <span class="input-group-addon" for="event-userid"><div >User(s):</div></span>
    <select id="display-userid" name="display-userid" class="form-control">
        @if($view === "agendaFourDay")
            <option value="0" >All</option>
        @else
            <option value="0" selected>All</option>
        @endif
        @foreach(UserHelper::GetAllUsers() as $user)
            @if($user->id === $mobileresourceid)
                @if($view === "agendaFourDay")
                    <option value="{{ $user->id }}" selected>{{ $user->getShortName() }}</option>
                @else
                    <option value="{{ $user->id }}">{{ $user->getShortName() }}</option>
                @endif
            @else
            <option value="{{ $user->id }}">{{ $user->getShortName() }}</option>
            @endif
        @endforeach
    </select>
</div>

<div class="input-group" style="width: 100%;">
    <button class="btn OS-Button btn-sm" id="new-event" type="button" style="width: 100%;">New Event</button>
</div>

<div class="input-group" style="width: 100%;">
    <input class="form-control" type="text" id="datepicker" >
</div>

<div id='calendar' style="position: relative; margin-top: 5px; height: 60vh;">
    <div class="calload"></div>
</div>

@include('Scheduling.common')

@include('Scheduling.CalendarDecelerationMobile')

@include('Scheduling.Modals.ViewDetailsModel')

@stop
