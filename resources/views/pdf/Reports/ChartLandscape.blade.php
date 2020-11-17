@extends('pdf.Reports.landscapemaster')

@section('content')

<style>

</style>

@include('pdf.Reports.Inserts.header')

<img src='data:image/png;base64,{{$chart}}' style="width: 100%;" />

@stop

