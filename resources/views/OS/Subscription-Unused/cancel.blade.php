@extends('master')

@section('content')

<div class="row" style="margin-top: 50px">
    <div class="container">
        All good things must come to an end, Before we cancel your subscription, can you please let us know a few things about your stay with us?
    </div>
</div>


<div class="row" style="margin-top: 50px">
    <div class="container">
        <form method="POST" action="{{ url('/Subscription/Cancel') }}" accept-charset="UTF-8">
            {{ csrf_field() }}
            <div class="form-group input-group">
                <span class="input-group-addon">Question</span>
                <input class="form-control" placeholder="" name="email" type="text">
            </div>

            <div class="form-group">
                <input class="btn btn-primary btn-block active" type="submit" value="Send"></div>
            </div>
        </form>
    </div>
</div>
@stop
