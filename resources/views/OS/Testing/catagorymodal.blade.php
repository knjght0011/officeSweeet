@extends('master')

@section('content')

    <div class="input-group">
        <span class="input-group-addon" for="amount"><div style="width: 15em;">Amount:</div></span>
        <input id="amount" type="text" name="amount" class="form-control" value="10">
    </div>

    <div id="cat-grp-div" class="input-group">
        <label class="input-group-addon" for="name"><div style="width: 15em;">Expense Category:</div></label>
        <select multiple id="catagorys"  class="form-control input-md" >
        </select>
        <span style="height: 100%;" class="input-group-btn">
                <button style="height: 82px;" class="btn btn-default" type="button" data-toggle="modal" data-target="#SplitAmountModal" data-amount="amount" data-type="liability" data-output="catagorys">Select</button>
        </span>
    </div>

    <button style="height: 82px;" class="btn btn-default" type="button" data-toggle="modal" data-target="#SplitAmountModal" data-amount="amount" data-output="catagorys" data-type="expense" >expence</button>
    <button style="height: 82px;" class="btn btn-default" type="button" data-toggle="modal" data-target="#SplitAmountModal" data-amount="amount" data-output="catagorys" data-type="income" >income</button>
    <button style="height: 82px;" class="btn btn-default" type="button" data-toggle="modal" data-target="#SplitAmountModal" data-amount="amount" data-output="catagorys" data-type="asset" >asset</button>
    <button style="height: 82px;" class="btn btn-default" type="button" data-toggle="modal" data-target="#SplitAmountModal" data-amount="amount" data-output="catagorys" data-type="liability" >liability</button>

@stop
