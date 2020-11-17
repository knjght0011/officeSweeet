@extends('master')

@section('content')


<a href="/Accounts/migrate/{{ $osaccount->subdomain }}">Click to run migrations</a>


<table id="search" class="table">
    <tbody>
        <tr>
            <td>
                Install Progress
            </td>
            <td>
                {{ $osaccount->installstage }}
                {{ $osaccount->InstallStageWord() }}
            </td>
        </tr>
        <tr>
            <td>subdomain</td><td>{{ $osaccount->subdomain }}</td>
        </tr>  
        <tr>    
            <td>port</td><td>{{ $osaccount->port }}</td>
        </tr>  
        <tr>            
            <td>database</td><td>{{ $osaccount->database }}</td>
        </tr>  
        <tr>            
            <td>username</td><td>{{ $osaccount->username }}</td>
        </tr>  
        <tr>
            <td>password</td><td>{{ $osaccount->password }}</td>
        </tr>  
        <tr>
            <td>active untill</td><td>{{ $osaccount->active }}</td>
        </tr>  
        <tr>            
            <td>disabledmessage</td><td>{{ $osaccount->disabledmessage }}</td>
        </tr>  
        <tr>
            <td>licensedusers</td><td>{{ $osaccount->licensedusers }}</td>
        </tr>  
        <tr>            
            <td>client_id</td><td>{{ $osaccount->client_id }}</td>
        </tr>  
        <tr>            
            <td>created_at</td><td>{{ $osaccount->created_at }}</td>
        </tr>  
        <tr>
            <td>updated_at</td><td>{{ $osaccount->updated_at }}</td>
        </tr>  
        <tr>            
            <td>plan_name</td><td>{{ $osaccount->plan_name }}</td>
        </tr>  
        <tr>            
            <td>subscription_id</td><td>{{ $osaccount->subscription_id }}</td>
        </tr>  
        <tr>            
            <td>transection_id</td><td>{{ $osaccount->transection_id }}</td>
        </tr>  
        <tr>            
            <td>company_name</td><td>{{ $osaccount->company_name }}</td>
        </tr>  
        <tr>            
            <td>business_type</td><td>{{ $osaccount->business_type }}</td>
        </tr>  
        <tr>            
            <td>name</td><td>{{ $osaccount->name }}</td>
        </tr>  
        <tr>            
            <td>email</td><td>{{ $osaccount->email }}</td>
        </tr>  
        <tr>            
            <td>tel_no</td><td>{{ $osaccount->tel_no }}</td>
        </tr>  
        <tr>            
            <td>address</td><td>{{ $osaccount->address }}, {{ $osaccount->city }}, {{ $osaccount->state }}, {{ $osaccount->zip }},</td>
        </tr>  
        <tr>            
            <td>buisness_address</td><td>{{ $osaccount->buisness_address }}, {{ $osaccount->buisness_city }}, {{ $osaccount->buisness_state }}, {{ $osaccount->buisness_zip }}</td>
        </tr>
    </tbody>
</table>
@stop