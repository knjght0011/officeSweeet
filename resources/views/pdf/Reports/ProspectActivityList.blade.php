@extends('pdf.Reports.landscapemaster')

@section('content')
    @include('pdf.Reports.Inserts.header')

    <table class="table">
        <thead>
            <tr> <!-- date aquired,  date of last activity with note, referral source and assigned staff -->
                <th>Name</th>
                <th>Date Aquired</th>
                <th>Last Activity</th>
                <th>Last Note</th>
                <th>Referral Source</th>
                <th>Assigned Staff</th>
            </tr>
        </thead>
        <tbody>      
            @foreach($items as $item)
            <tr>
                <td>{{ $item->getName() }}</td>
                <td>{{ $item->formatDate_created_at_no_time() }}</td>
                <td>{{ $item->LastNoteTime() }}</td>
                <td>{{ $item->LastNoteContent() }}</td>
                <td>{{ $item->referral_source }}</td>
                <td>{{ $item->assigned_to_user_name() }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

@stop