@extends('Emails.Templates.system')

@section('content')
<p>Hello {{ $user->name }},</p>

<p>Here are your Events for the day</p>

<table class="table" style="width: 100%">
    <thead>
        <tr>
            <th>Title</th>
            <th>Note</th>
            <th>Start</th>
            <th>End</th>
            <th>With</th>
        </tr>
    </thead>
    <tbody>
    @foreach($events as $event)
        <tr>
            <td>{{ $event->title }}</td>
            <td>{{ $event->contents }}</td>
            <td>{{ $event->start->subMinutes($user->timezoneoffset)->toTimeString() }}</td>
            <td>{{ $event->end->subMinutes($user->timezoneoffset)->toTimeString() }}</td>
            <td>{{ $event->schedulerparent->getlinknameAttribute() }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<p>Regards<br>OfficeSweeet</p>

@stop