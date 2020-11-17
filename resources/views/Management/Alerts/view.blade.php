@extends('master')

@section('content') 
<div class="row" style="margin-top: 20px;">
    <div style="float:left; width: 15em;  margin-left: 20px;">
        <button style="width: 100%;" id="backbutton" name="backbutton" type="button" class="btn OS-Button">Back</button>
    </div>
</div>


<table class="table" style="margin-top: 20px;">      
    <tbody>
        <tr>
            <td>created_at</td>
            <td colspan="2">
                {{ $alert->created_at }}
            </td> 
        </tr>    
        <tr>    
            <td>Account</td>
            <td colspan="2">
                <a id="link" href="/Accounts/{{ $alert->account_id }}">
                    {{ $alert->GetAccount()->subdomain }}
                </a>                
            </td>
        </tr>    
        <tr>    
            <td>LLS Client</td>
            <td colspan="2">
                {!! $alert->GetAccount()->LLSclientLink() !!}
            </td>
        </tr>
        <tr>  
            <td>Type</td>
            <td colspan="2">
                {{ $alert->type }}
            </td>
        </tr>  
        <tr>  
            <td>Description</td>
            <td colspan="2">
                {{ $alert->description }}
            </td>
        </tr>  
        <tr>   
            <td>Action</td>
            <td colspan="2">
                {{ $alert->ActionStageWord() }}
                @if($alert->action_stage === 1)
                    <button id="mark-complete" type="button" class="btn OS-Button">Mark Action Complete</button>
                @endif
            </td>
        </tr> 
        <tr>   
            <td>Variables</td>
            <td colspan="2">
                @if($alert->variables != null)
                @foreach($alert->variables as $key => $value)
                {{ $key }}: {{ $value }}<br>
                @endforeach
                @endif
            </td>
        </tr>   

    
    @if($alert->action_stage === 1)
        @if($alert->type === "Subscription Update")

            <tr>
                <td>Action needed:</td>
                <td>
                    1. Go here & login: <a id="link" target="_blank" href="https://secure.tnbcigateway.com/merchants/recurring.php?Action=Details&transaction={{ $alert->GetAccount()->subscription_id }}">
                        Here:{{ $alert->GetAccount()->subscription_id }}
                    </a><br>
                    2. If relevent plan exists in "Plan Name" drop down: Select it and save.<br>
                    3. Else Goto "Add Plans" in side bar<br>
                    4. Fill out acourding to image on the right.<br>
                    5. Change "Amount to charge each time" to the correct amount for the new plan.<br>
                    6. Change the leading X in "Plan Name / Description" & "Plan ID (For use with QuickClick and API)" to the number of users the new plan is for (i.e "30user_Jan2017")<br>
                    7. Click Save<br>
                    8. Repeat step 2. but now the plan should be there to select.<br>
                </td>
                <td>
                    <img src="{{ url('/images/TNaddplan.png') }}"><br>
                </td>
            </tr>
            {{--
            <tr>
            <td>Action needed: Old</td>
            <td colspan="2">
                1. Go here & login: <a id="link" target="_blank" href="https://secure.tnbcigateway.com/merchants/recurring.php?Action=Details&transaction={{ $alert->account->subscription_id }}">
                    {{ $alert->account->subscription_id }}
                </a><br>
                2. Take note of "Recurring SKU" example: "1_jfstagl@gmail.com_1509131230"<br>
                3. Goto "List Plans" in side bar<br>
                4. Find the plan with the corresponding "Recurring SKU" in the "Plan ID" column<br>
                5. Update "Amount to charge each time" to the "new_price" above and click "Save"
            </td>
            </tr>
            --}}
        @endif
    @endif
    </tbody>
</table>

<script>
$(document).ready(function() {
    $('#mark-complete').click(function(){
        GoToPage('/Alerts/Mark/' + {{ $alert->id }});
    });
    $('#backbutton').click(function(){
        GoToPage('/Alerts/');
    });
});    
</script>
@stop