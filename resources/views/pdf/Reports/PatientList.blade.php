@extends('pdf.Reports.master')

@section('content')
    @include('pdf.Reports.Inserts.header')

    <table id="patientsearch" class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Scheduled</th>
            <th>Mobile Number</th>
            <th>Home Number</th>
            <th>E-Mail</th>
            <th>Comments</th>
            <th>Address number</th>
            <th>Address address1</th>
            <th>Address address2</th>
            <th>Address city</th>
            <th>Address region</th>
            <th>Address state</th>
            <th>Address zip</th>
            <th>firstname</th>
            <th>lastname</th>
        </tr>
        </thead>
        <tfoot style="visibility: hidden;">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Scheduled</th>
            <th>Mobile Number</th>
            <th>Home Number</th>
            <th>E-Mail</th>
            <th>Comments</th>
            <th>Address number</th>
            <th>Address address1</th>
            <th>Address address2</th>
            <th>Address city</th>
            <th>Address region</th>
            <th>Address state</th>
            <th>Address zip</th>
            <th>firstname</th>
            <th>lastname</th>
        </tr>
        </tfoot>
        <tbody>
        @foreach($client->patient as $patient)
            <tr>
                <td>{{ $patient->id }}</td>
                <td>{{ $patient->firstname }} {{ $patient->lastname }}</td>
                <td>@if($patient->scheduled == 'YES') YES @else NO @endif</td>
                <td>{{ $patient->mobilenumber }}</td>
                <td>{{ $patient->homenumber }}</td>
                <td>{{ $patient->email }}</td>
                <td>{{ $patient->comments }}</td>
                @if($patient->address_id == NULL)
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                @else
                    <td>{{ $patient->address->number }}</td>
                    <td>{{ $patient->address->address1 }}</td>
                    <td>{{ $patient->address->address2 }}</td>
                    <td>{{ $patient->address->city }}</td>
                    <td>{{ $patient->address->region }}</td>
                    <td>{{ $patient->address->state }}</td>
                    <td>{{ $patient->address->zip }}</td>
                @endif
                <td>{{ $patient->firstname }}</td>
                <td>{{ $patient->lastname }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@stop