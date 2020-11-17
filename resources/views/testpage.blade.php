@extends('master')

@section('content')

@foreach($palattes as $palatte)
    <div class="row">
    <legend>{{ count($palatte) }}</legend>
    @foreach ($palatte as $colour)
        <div style="height: 10px; width: 10px; float: left; background-color: rgb({{ $colour["R"] }}, {{ $colour["G"] }}, {{ $colour["B"] }});"></div>
    @endforeach
    </div>
@endforeach

@stop

