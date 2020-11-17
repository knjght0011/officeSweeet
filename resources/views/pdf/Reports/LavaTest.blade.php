@extends('pdf.Reports.master')

@section('content')

@include('pdf.Reports.Inserts.header')

<div id="pop-div" style="width:800px;border:1px solid black"></div>
<?= $lava->render('PieChart', 'Popularity', 'pop-div') ?>

@stop

