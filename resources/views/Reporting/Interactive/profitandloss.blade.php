@extends('master')

@section('content')
<style>
    .tableheading{
        background: #3498db;
        color: white;
        font-weight: bold;
    }
    .catagory{
        background-color: lightgrey;
        font-weight: bold;
        color: black;
    }
    .detail{
        display: none;
    }
</style>


<div class="row">
    <div class="col-md-4">
        {!! Form::OSdatepicker("start-date", "Start Date", $startdate->toDateString()) !!}
    </div>
    <div class="col-md-4">
        {!! Form::OSdatepicker("end-date", "End Date", $enddate->toDateString()) !!}
    </div>
    <div class="col-md-4" style="padding-right: 70px;">
        <button id="update" style="width: 100%;" type="button" class="btn OS-Button" >Update</button>
</div>
</div>

<div style="width: 100%; padding-top: 20px;">
    <table class="table">
        <thead>
        <tr class="tableheading">
            <th class="col-md-3">Income</th>
            <th class="col-md-2"></th>
            <th class="col-md-2"></th>
            <th class="col-md-3" style="text-align: center;">$</th>
            <th class="col-md-2"></th>
        </tr>
        </thead>
        <tbody>
        @foreach ($profitcatagorys as $catagory => $value)
            <tr class="catagory" data-catagory="income-{{$catagory}}">
                <td><span class="glyphicon glyphicon-chevron-down"></span> {{ $catagory }}</td>
                <td></td>
                <td></td>
                <td style="text-align: center;">${{ number_format($profitcatagorystotal[$catagory], 2) }}</td>
                <td></td>
            </tr>
            @foreach($value as $info)
                <tr class="detail" data-catagory="income-{{$catagory}}">
                    <td>Deposit</td>
                    <td>{{ $info['date'] }}</td>
                    <td><a href="/Deposit/Edit/{{ $info['id'] }}">{{ $info['linkedto'] }}</a></td>
                    <td style="text-align: center;">${{ number_format($info['amount'], 2) }}</td>
                    <td></td>
                </tr>
            @endforeach
        @endforeach
        <tr class="tableheading">
            <td>Total Income</td>
            <td></td>
            <td></td>
            <td style="text-align: center;">${{ number_format($incometotal, 2) }}</td>
            <td></td>
        </tr>
        </tbody>
    </table>
</div>

<div style="width: 100%;">
    <table class="table">
        <thead>
        <tr class="tableheading">
            <th class="col-md-3">Expenses</th>
            <th class="col-md-2"></th>
            <th class="col-md-2"></th>
            <th class="col-md-2"></th>
            <th class="col-md-3" style="text-align: center;">$</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($expencecatagorys as $catagory => $value)
            <tr class="catagory" data-catagory="expenses-{{$catagory}}">
                <td><span class="glyphicon glyphicon-chevron-down"></span> {{ $catagory }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td style="text-align: center;">${{ number_format($expencecatagorystotal[$catagory], 2) }}</td>
            </tr>
            @foreach($value as $info)
                <tr class="detail" data-catagory="expenses-{{$catagory}}">
                    <td>{{ $info['type'] }}</td>
                    <td>{{ $info['date'] }}</td>
                    <td><a href="{{ $info['link'] }}">{{ $info['linkedto'] }}</a></td>
                    <td></td>
                    <td style="text-align: center;">${{ number_format($info['amount'], 2) }}</td>
                </tr>
            @endforeach
        @endforeach

        <tr >
            <td></span>Depreciation</td>
            <td></td>
            <td></td>
            <td></td>
            <td style="text-align: center;">${{ number_format($depreciationtotal, 2) }}</td>
        </tr>

        <tr class="tableheading">
            <td>Total Expenses</td>
            <td></td>
            <td></td>
            <td></td>
            <td style="text-align: center;">${{ number_format($expencestotal, 2) }}</td>
        </tr>
        </tbody>
    </table>
</div>

<div style="width: 100%;">
    <table class="table">
        <thead>
            <tr class="tableheading">
                <th class="col-md-3">Profit/Loss</th>
                <th class="col-md-2"></th>
                <th class="col-md-2"></th>
                <th class="col-md-3" style="text-align: center;">${{ number_format($profitloss, 2) }}</th>
                <th class="col-md-2"></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<script>

    $('#update').click(function(){
        var $start = $('#start-date').val();
        var $end = $('#end-date').val();
        GoToPage('/Reporting/Interactive/ProfitAndLoss/' + $start + '/' + $end);
    });

    $('.catagory').click(function(){

        var $catagory = $(this).data('catagory');

        $('.detail').each(function( i ) {
            if ( $(this).data('catagory') === $catagory ) {

                if($(this).css("display") === "none"){
                    $(this).css('display','table-row');
                }else{
                    $(this).css('display','none');
                }
            }
        });

    });

</script>
@stop