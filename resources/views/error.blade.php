@extends('master')

@section('content')

    <legend>An Error Has Occured:</legend>
    
    @if (isset($error))
        <div class="alert alert-danger">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            {{ $error }}
        </div>
    @endif

    @foreach ($errors->all() as $error)
        <div class="alert alert-danger">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            {{ $error }}
        </div>
    @endforeach

    @if (App::isLocal())
    <legend>Debug information:</legend>
        <div class="alert alert-danger">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            {{ $debug }}
        </div>
    @endif

@stop
