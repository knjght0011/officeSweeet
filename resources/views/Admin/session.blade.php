@extends('master')

@section('content')

<?php 

$data = Session::all(); 

var_dump($data);

?>


@stop