@extends('pdf.Reports.master')

@section('content')

@include('pdf.Reports.Inserts.header')
<div id="title"> Prospect Conversions Report </div>

<table class="table">
    <thead>
    <tr>
        <th>User</th>
        <th>Conversions</th>
    </tr>
    </thead>
    <tbody>
        @foreach(UserHelper::GetAllUsers() as $user)
            <tr>
                <td>{{ $user->getName() }}</td>
                <td>{{ $user->ClientConversions($startdate, $enddate) }}</td>
            </tr>
        @endforeach

    </tbody>
</table>

@stop