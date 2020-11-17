@extends('Emails.Templates.master')

@section('content')

<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach(UserHelper::GetAllUsers()->reverse() as $user)
        <tr>
            <td>{{ $user->firstname }} {{ $user->lastname }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->created_at }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@stop