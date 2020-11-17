@extends('master')

@section('content')

    @if(!$account->IsActive())
        @if($account->subscription_id === null)
            <div class="alert alert-danger">
                Your OfficeSweeet trial has ended.
            </div>
        @else
            <div class="alert alert-danger">
                It looks like your officesweeet account has been disabled but your do not have permission to manage the subscription.
            </div>
        @endif
    @endif

@stop