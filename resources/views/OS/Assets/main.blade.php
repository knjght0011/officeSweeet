@extends('master')
@section('content')

    @if(Auth::user()->hasPermissionMulti('multi_assets_permission', 3))
        <h3 style="margin-top: 10px;">Manage Stock, Inventory, Products/Services & Assets</h3>
    @else
        @if(Auth::user()->hasPermissionMulti('multi_assets_permission', 2))
            <h3 style="margin-top: 10px;">Manage Stock, Inventory & Products/Services</h3>
        @else
            @if(Auth::user()->hasPermissionMulti('multi_assets_permission', 1))
                <h3 style="margin-top: 10px;">Manage Stock</h3>
            @endif
        @endif
    @endif

    <div role="tabpanel" class="tab-pane" id="transactions">
        <ul class="nav nav-tabs" role="tablist">
            @if(Auth::User()->hasPermission('multi_assets_permission'))
            <li role="presentation" class="active" style="padding-top: 5px;"><a href="#transactions-overview" aria-controls="profile" role="tab" data-toggle="tab">Mark item taken from stock</a></li>
            @endif
            @if(Auth::User()->hasPermissionMulti('multi_assets_permission', 1))
            <li role="presentation" style="padding-top: 5px;"><a href="#products" aria-controls="profile" role="tab" data-toggle="tab">Add/View Inventory/stock</a></li>
            <li role="presentation" style="padding-top: 5px;"><a href="#services" aria-controls="profile" role="tab" data-toggle="tab">Add/View Services</a></li>
            @endif
            @if(Auth::User()->hasPermissionMulti('multi_assets_permission', 2))
            <li role="presentation" style="padding-top: 5px;"><a href="#AssetLiabilityOverview" aria-controls="profile" role="tab" data-toggle="tab">Asset & Liability Overview</a></li>
            @endif
        </ul>

        <div class="tab-content" style="width: 100%;">
            @if(Auth::User()->hasPermission('multi_assets_permission'))
            <div role="tabpanel" class="tab-pane active" id="transactions-overview">
                @include('OS.Assets.takestock')
            </div>
            @endif
            @if(Auth::User()->hasPermissionMulti('multi_assets_permission', 1))
            <div role="tabpanel" class="tab-pane " id="products">
                @include('OS.Assets.products')
            </div>
            @endif
            @if(Auth::User()->hasPermissionMulti('multi_assets_permission', 1))
                <div role="tabpanel" class="tab-pane " id="services">
                    @include('OS.Assets.services')
                </div>
            @endif
            @if(Auth::User()->hasPermissionMulti('multi_assets_permission', 2))
            <div role="tabpanel" class="tab-pane " id="AssetLiabilityOverview" style="width: 100%;">
                @include('OS.Assets.overviewtest')
            </div>
            @endif
        </div>
    </div>

@stop
