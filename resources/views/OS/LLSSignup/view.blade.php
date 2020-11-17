@extends('master')

@section('content')

    <h3 style="margin-top: 10px;">Signup Prospect</h3>

    <div class="container">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active" style="padding-top: 5px;"><a href="#signup" aria-controls="profile" role="tab" data-toggle="tab">Signup</a></li>
            <li role="presentation" style="padding-top: 5px;"><a href="#plans" aria-controls="profile" role="tab" data-toggle="tab">Plans</a></li>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="signup">
                @include('OS.LLSSignup.signup')
            </div>
            <div role="tabpanel" class="tab-pane " id="plans">
                @include('OS.LLSSignup.tnplan')
            </div>
        </div>
    </div>


@stop