@extends('master')

@section('content')

    <h3 style="margin-top: 10px;">Payroll Processing</h3>


    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#runpayroll" aria-controls="profile" role="tab" data-toggle="tab">Run Payroll</a></li>
        <li role="presentation"><a href="#payrollreports" aria-controls="profile" role="tab" data-toggle="tab">Payroll Reports</a></li>
    </ul>

    <div class="tab-content">
        
        <div role="tabpanel" class="tab-pane active" id="runpayroll">
            @include('OS.Payroll.view.current')
        </div>
        
        <div role="tabpanel" class="tab-pane" id="payrollreports">
            @include('OS.Payroll.view.old')
        </div>
    </div>    

@stop