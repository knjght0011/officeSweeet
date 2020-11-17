@extends('pdf.Reports.master')

@section('content')

@include('pdf.Reports.Inserts.header')


<img src='data:image/png;base64,{{$b64}}'  />


@stop

