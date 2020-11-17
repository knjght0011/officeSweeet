@extends('master')

@section('content')
<style>
    .sub-list-button{
        text-align: center;
        margin-bottom: 5px;
        border-color: red;
        border-width: 2px;
    }
</style>


    <h3 style="margin-top: 10px;">My Subscription</h3>


    @if(!$account->IsActive())
        @if($account->subscription_id === null)
            <div class="alert alert-danger">
                Your OfficeSweeet trial has ended. What would you like to do next?
            </div>
        @else
            <div class="alert alert-danger">
                It looks like your officesweeet account has been disabled.
            </div>
        @endif
    @endif

@if($account->plan_name === "SOLO")
    <div class="alert alert-danger" style="margin-top: 5px;">
        <p>You are using Office Sweeet SOLO.  As such, you only have access to the Accounting Suite.</p>
        <p>If you would like the benefit of using our CRM to to track of your prospects, the Scheduler for your staff and clients, the Payroll calculator for your employees, the Task Manager to assign and keep track of projects, the Inventory system to manage your stock, the Messaging Center to communicate with your staff and automatically notify others when there is a change in the schedule, a task assigned or when inventory runs low and most importantly, an Electronic Records manager to create, file, send, and maintain all of your documents electronically so that you can find them when you need them, then by all means, empower your staff and UPGRADE your subscription today.</p>
        <p>Office Sweeet EBM (Enterprise Business Management) is designed to create an atmosphere of teamwork allowing your business to run like “a well-oiled machine”. Subscribe today!</p>
    </div>
@endif


    <div class="row" style="margin-top: 20px;">
        <div class="col-md-4 col-md-offset-2">
            <table class="table">
                <tbody>
                    <tr>
                        <td>
                            Subscription Active Untill
                        </td>
                        <td>
                            @if($account->active != null)
                                {{ $account->active->toDateString() }}
                            @else
                                No Subscription
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Last time billed
                        </td>
                        <td>
                            {{ $summeryarray['Last time billed'] }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Last billed amount
                        </td>
                        <td>
                            {{ $summeryarray['Last billed amount'] }}
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

        <div class="col-md-4">
            <table class="table">
                <tbody>
                    <tr>
                        <td>
                            Licensed Users
                        </td>
                        <td>
                            {{ $summeryarray['Licensed Users'] }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Current Number of Users
                        </td>
                        <td>
                            {{ $summeryarray['Current Number of Users'] }}
                        </td>
                    </tr>
                </tbody>

            </table>
        </div>
    </div>

    <div class="col-md-6 col-lg-offset-3">
        <div class="list-group">
            @if(count(app()->make('account')->subscriptions) === 0)

                <a href="/Subscription/Signup" class="list-group-item sub-list-button" >Upgrade My Subscription</a>

            @else

                <a href="#" class="list-group-item sub-list-button" data-toggle="modal" data-target="#UpdateSubModel"  >Update Subscription</a>

                <a href="#" class="list-group-item sub-list-button" data-toggle="modal" data-target="#CancelSubModel" >Cancel Subscription</a>

            @endif


        </div>
    </div>

    <div id="CancelSubModel" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                @include('OS.Subscription.cancel')
                </div>
            </div>
        </div>
    </div>

    <div id="UpdateSubModel" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    @include('OS.Subscription.update')
                </div>
            </div>
        </div>
    </div>

<script>
    function ScriptionCancel($reason, $text){

        $("body").addClass("loading");
        posting = $.post("/Subscription/Cancel",
            {
                _token: "{{ csrf_token() }}",
                reason: $reason,
                text: $text
            });

        posting.done(function( data ) {

            $("body").removeClass("loading");
            //refresh to details page
            switch(data) {
                case "sucess":
                    //GoToPage("/ACP/Subscription/sub-summery")

                    $.dialog({
                        title: 'Thank you.',
                        content: 'Your cancellation will be processed within the next 3 working days.'
                    });

                    break;
                default:

                    $.dialog({
                        title: 'Oops...',
                        content: 'Unknown error! Please try again later.'
                    });
                    break;
            }
        });

        posting.fail(function() {
            $("body").removeClass("loading");
            $.dialog({
                title: 'Oops...',
                content: 'Lost contact witht her server, please try again later.'
            });
        });
    }

    function SubScriptionUpdate($numberofusers, $price, $plan, $timedowngrade) {

        $("body").addClass("loading");
        posting = $.post("/Subscription/Update",
            {
                _token: "{{ csrf_token() }}",
                numberofusers: $numberofusers,
                price: $price,
                plan: $plan,
                timedowngrade: $timedowngrade,
            });

        posting.done(function (data) {

            $("body").removeClass("loading");
            //refresh to details page
            switch (data['status']) {
                case "OK":
                    $.dialog({
                        title: 'Thank you.',
                        content: 'You will be contacted with regard to your update soon.'
                    });
                    break;
                default:

                    $.dialog({
                        title: 'Oops...',
                        content: 'Unknown error! Please try again later.'
                    });
                    break;
            }
        });

        posting.fail(function () {
            $("body").removeClass("loading");
            $.dialog({
                title: 'Oops...',
                content: 'Lost contact with the server, please try again later.'
            });
        });
    }
</script>

@stop