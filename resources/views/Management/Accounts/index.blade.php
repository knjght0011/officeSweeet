@extends('master')

@section('content')  

<div class="row">
    <div style="float:left; width: 15em;  margin-left: 20px;">
        <button style="width: 100%;" id="new" name="new" type="button" class="btn OS-Button">Create New Account</button>
    </div>
    <!--
    <div style="float:left; width: 15em;  margin-left: 20px;">
        <button style="width: 100%;" id="backbutton" name="backbutton" type="button" class="btn OS-Button">Back to Client</button>
    </div>
    -->
</div>

<table id="search" class="table">
    <thead>
        <tr id="head">
            <th>ID</th>
            <th>Subdomain</th>
            <th>DB Name</th>
            <th>DB Username</th>
            <th>DB Password</th>
            <th>Status</th>
            <th>LLS Client ID</th>
            <th>Licensed Users</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>ID</th>
            <th>Subdomain</th>
            <th>DB Name</th>
            <th>DB Username</th>
            <th>DB Password</th>
            <th>Status</th>
            <th>LLS Client ID</th>
            <th>Licensed Users</th>
        </tr>
    </tfoot>
    <tbody>
        @foreach($accounts as $account)
        <tr>
            <td>
                <a href="/Accounts/{{ $account->subdomain }}">{{ $account->id }}</a>
            </td>
            <td>
                {{ $account->subdomain }}
            </td>
             <td>
                {{ $account->database }}
            </td>    
            <td>
                {{ $account->username }}
            </td>
            <td>
                {{ $account->password }}
            </td>
            <td>
                {{ $account->statusWords() }}
            </td>
            <td>
                {{ $account->client_id }}
            </td>
            <td>
                {{ $account->licenseduseres }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<script>    
$(document).ready(function() {
    $('#new').click(function(e) {
        var link = document.createElement('a');
        link.href = "/Accounts/create";
        link.id = "link";
        document.body.appendChild(link);
        link.click(); 
    });
    
});
</script>
@stop