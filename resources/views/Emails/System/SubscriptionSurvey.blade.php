@extends('Emails.Templates.system')

@section('content')
<table class="table">
    <thead>
        @foreach($data as $field => $answer)
        <tr>
            <td>{{ $field }}</td><td>{{ $answer }}</td>
        </tr>
        @endforeach
    </thead>
</table>

@stop