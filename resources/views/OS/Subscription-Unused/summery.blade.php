@extends('master')

@section('content')

    <div class="row" style="margin-top: 50px;">
        <div class="col-md-3">

        </div>

        <div class="col-md-3">
            <table class="table">
                <tr>
                    <td>
                        Plan Name
                    </td>
                    <td>
                        {{ $account->plan_name }}
                    </td>
                </tr>
                <tr>
                    <td>
                        Licensed Users
                    </td>
                    <td>
                        {{ $account->licensedusers }}
                    </td>
                </tr>
                <tr>
                    <td>
                        Current Number of Users
                    </td>
                    <td>
                        {{ $numofusers }}
                    </td>
                </tr>
            </table>
            <div>
                {{ var_dump($tansnationaldata) }}
            </div>

        </div>

        <div class="col-md-3">
            <div class="list-group">
                <a href="/Subscription/Update" class="list-group-item">Update Subscription</a>
                <a href="/Subscription/Cancel" class="list-group-item">Cancel Subscription</a>
                <a href="#" class="list-group-item">Option 3</a>
                <a href="#" class="list-group-item">Option 4</a>
                <a href="#" class="list-group-item">Option 5</a>
                <a href="#" class="list-group-item">Option 6</a>
                <a href="#" class="list-group-item">Option 7</a>
                <!--<a href="#" class="list-group-item youtubebutton" data-url=""></a>-->
            </div>
        </div>
    </div>
@stop
