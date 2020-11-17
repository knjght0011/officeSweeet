<br/>
<a id="link" href="/Clients/Contact/New/{{ $client->id }}"><button class="btn OS-Button btn-sm" type="button">Add New Contact</button></a>
<!--<a id="link" href="/Clients/Contact/New/{{ $client->id }}">Add New Contact</a> -->

<label class="radio-inline"><input type="radio" name="activecontactsradio" value="1" checked="checked">Active Contacts</label>
<label class="radio-inline"><input type="radio" name="activecontactsradio" value="0">Inactive Contacts</label>

<div id="activeContactsContainer">
    <table class="table">
        <thead>
            <tr>
                <th class="col-md-2">Contact Name</th>
                <th class="col-md-2">Contact Type</th>
                <th class="col-md-2">Email</th>
                <th class="col-md-2">Primary Phone Number</th>
                <th class="col-md-4">Address</th>
                <th class="col-md-1"></th>
                <th class="col-md-1"></th>
            </tr>
        </thead>
        <tbody>
        @foreach ($client->contacts as $contact)
            <tr>
                <td>{{ $contact->firstname }} {{ $contact->lastname }}</td>
                <td>{{ $contact->contacttype }}</td>
                <td>{!! PageElement::EmailLink($contact->email) !!}</td>
                <td><a href="tel:{{ $contact->GetprimaryphonenumberRAW() }}">{{ $contact->Getprimaryphonenumber() }}</a></td>
                <td>{{ $contact->address->number }} {{ $contact->address->address1 }} {{ $contact->address->address2 }} {{ $contact->address->city }} {{ $contact->address->region }} {{ $contact->address->state }} {{ $contact->address->zip }}</td>
                <td><button style="width: 100%;" type="button" class="btn OS-Button btn-sm" data-toggle="modal" data-target="#{{ $contact->id }}Details">Details</button></td>
                <!--<td><button type="button" class="btn OS-Button btn-sm" data-toggle="modal" data-target="#{{ $contact->id }}Edit">Edit</button></td>-->
                <td><a id="link" href="/Clients/Contact/{{$contact->id }}"><button style="width: 100%;" class="btn OS-Button btn-sm" type="button">Edit</button></a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div id="trashedContactsContainer">
    <table class="table">
        <thead>
            <tr>
                <th class="col-md-2">Contact Name</th>
                <th class="col-md-2">Contact Type</th>
                <th class="col-md-2">Email</th>
                <th class="col-md-2">Primary Phone Number</th>
                <th class="col-md-4">Address</th>
                <th class="col-md-1"></th>
                <th class="col-md-1"></th>
            </tr>
        </thead>
        <tbody>
        @foreach ($client->trashedcontacts as $contact)
            <tr>
                <td>{{ $contact->firstname }} {{ $contact->lastname }}</td>
                <td>{{ $contact->contacttype }}</td>
                <td>{!! PageElement::EmailLink($contact->email) !!}</td>
                <td><a href="tel:{{ $contact->GetprimaryphonenumberRAW() }}">{{ $contact->Getprimaryphonenumber() }}</a></td>
                <td>{{ $contact->address->number }} {{ $contact->address->address1 }} {{ $contact->address->address2 }} {{ $contact->address->city }} {{ $contact->address->region }} {{ $contact->address->state }} {{ $contact->address->zip }}</td>
                <td><button style="width: 100%;" type="button" class="btn OS-Button btn-sm" data-toggle="modal" data-target="#{{ $contact->id }}Details">Details</button></td>
                <td><a id="link" href="/Clients/Contact/{{$contact->id }}"><button style="width: 100%;" class="btn OS-Button btn-sm" type="button">Edit</button></a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@foreach ($client->contacts as $contact)
{{-- T
<div id="{{ $contact->id }}Edit" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Contact</h4>
            </div>

            <div class="modal-body">

                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#profile" aria-controls="home" role="tab" data-toggle="tab">Profile</a></li>
                    <li role="presentation"><a href="#address" aria-controls="profile" role="tab" data-toggle="tab">Address</a></li>
                 </ul>


                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="profile">
                        <form method="POST" action="/Contacts/Save" class="form-horizontal">
                        <fieldset>

                        <input type="hidden" name="clients_id" value="{{ $client->id }}">    
                        <input type="hidden" name="id" value="{{ $contact->id }}">
                        <input type="hidden" name="address_id" value="{{ $contact->address_id }}">

                        <div class="row">
                                <label class="col-md-4 control-label" for="firstname">First Name</label>
                                <label class="col-md-4 control-label" for="middlename">Middle Name</label>                                    
                                <label class="col-md-4 control-label" for="lastname">Last Name</label>                                    
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <input id="firstname" name="firstname" type="text" value="{{ $contact->firstname }}" class="form-control input-md" required="">
                            </div>
                            <div class="col-md-4">
                                <input id="middlename" name="middlename" type="text" value="{{ $contact->middlename }}" class="form-control input-md">
                            </div>
                            <div class="col-md-4">
                                <input id="lastname" name="lastname" type="text" value="{{ $contact->lastname }}" class="form-control input-md" required="">
                            </div>
                        </div>

                        <div class="row">
                            <label class="col-md-4 control-label" for="ssn">SSN</label>
                            <label class="col-md-4 control-label" for="driverslicense">Drivers License No.</label>
                            <label class="col-md-4 control-label" for="email">E-Mail</label>  
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <input id="ssn" name="ssn" type="text" value="{{ $contact->ssn }}" class="form-control input-md">
                            </div>
                            <div class="col-md-4">
                                <input id="driverslicense" name="driverslicense" type="text" value="{{ $contact->driverslicense }}" class="form-control input-md">
                            </div>
                            <div class="col-md-4">
                                <input id="email" name="email" type="text" value="{{ $contact->email }}" class="form-control input-md" required="">
                            </div>
                        </div>                                

                        <div class="row">
                            <label class="col-md-4 control-label" for="contacttype">Contact Type</label>
                            <!--<label class="col-md-4 control-label" for="ref2">Ref2</label>
                            <label class="col-md-4 control-label" for="ref3">Ref3</label>-->
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <input id="ref1" name="contacttype" type="text" value="{{ $contact->contacttype }}" class="form-control input-md">
                            </div>
                            <div class="col-md-4">

                            </div>
                            <div class="col-md-4">

                            </div>
                        </div>                                

                        <div class="row">
                            <label class="col-md-4 control-label" for="comments">Comments</label>
                        </div>       

                        <div class="row">
                            <div class="col-md-12">                     
                                <textarea class="form-control vresize" id="comments" value="{{ $contact->comments }}" name="comments"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button id="submit" name="submit" class="btn OS-Button">Save</button>
                            </div>
                        </div>
                        </fieldset>
                        </form>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="address">
                        <div class="panel-group" id="accordion">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Edit Existing Address</a>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                        Edit Existing Address
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">Add New Address</a>
                                    </h4>
                                </div>
                                <div id="collapseTwo" class="panel-collapse collapse">
                                    <div class="panel-body">
                                            Add New Address
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">Change to Different Existing Address</a>
                                    </h4>
                                </div>
                                <div id="collapseThree" class="panel-collapse collapse">
                                    <div class="panel-body">
                                            Change to Different Existing Address
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</div>
--}}

<!-- Details Modal -->
<div id="{{ $contact->id }}Details" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                @if (isset($contact->firstname))
                    {{ $contact->firstname }} 
                @endif
                @if (isset($contact->middlename))
                    {{ $contact->middlename }} 
                @endif
                @if (isset($contact->lastname))
                    {{ $contact->lastname }} 
                @endif
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <label class="col-md-4 text-right">Address:</label>
                    <div class="col-md-8">{{ $contact->address->number }} {{ $contact->address->address1 }} {{ $contact->address->address2 }} {{ $contact->address->city }} {{ $contact->address->region }} {{ $contact->address->state }} {{ $contact->address->zip }} </div>
                </div>
                <div class="row">
                    <label class="col-md-4 text-right">SSN:</label>
                    <div class="col-md-8">{{ $contact->ssn }}</div>
                </div>

                <div class="row">
                    <label class="col-md-4 text-right">Drivers License No:</label>
                    <div class="col-md-8">{{ $contact->driverslicense }}</div>
                </div>

                <div class="row">
                    <label class="col-md-4 text-right">E-Mail:</label>
                    <div class="col-md-8">{{ $contact->email }}</div>
                </div>


                <div class="row">
                    <label class="col-md-4 text-right" for="contacttype">Contact Type:</label>
                    <div class="col-md-8">{{ $contact->contacttype }}</div>
                </div>                               

                <div class="row">
                    <label class="col-md-4 text-right" for="comments">Comments:</label>
                    <div class="col-md-8">                     
                        {{ $contact->comments }}
                    </div>
                </div>             
            </div>                           
        </div>
    </div>
</div> 
@endforeach

@foreach ($client->trashedcontacts as $contact)
<!-- Details Modal -->
<div id="{{ $contact->id }}Details" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                @if (isset($contact->firstname))
                    {{ $contact->firstname }} 
                @endif
                @if (isset($contact->middlename))
                    {{ $contact->middlename }} 
                @endif
                @if (isset($contact->lastname))
                    {{ $contact->lastname }} 
                @endif
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <label class="col-md-4 text-right">Address:</label>
                    <div class="col-md-8">{{ $contact->address->number }} {{ $contact->address->address1 }} {{ $contact->address->address2 }} {{ $contact->address->city }} {{ $contact->address->region }} {{ $contact->address->state }} {{ $contact->address->zip }} </div>
                </div>
                <div class="row">
                    <label class="col-md-4 text-right">SSN:</label>
                    <div class="col-md-8">{{ $contact->ssn }}</div>
                </div>

                <div class="row">
                    <label class="col-md-4 text-right">Drivers License No:</label>
                    <div class="col-md-8">{{ $contact->driverslicense }}</div>
                </div>

                <div class="row">
                    <label class="col-md-4 text-right">E-Mail:</label>
                    <div class="col-md-8">{{ $contact->email }}</div>
                </div>


                <div class="row">
                    <label class="col-md-4 text-right" for="contacttype">Contact Type:</label>
                    <div class="col-md-8">{{ $contact->contacttype }}</div>
                </div>                               

                <div class="row">
                    <label class="col-md-4 text-right" for="comments">Comments:</label>
                    <div class="col-md-8">                     
                        {{ $contact->comments }}
                    </div>
                </div>             
            </div>                           
        </div>
    </div>
</div> 
@endforeach

<script>
$(document).ready(function() {
    $("#trashedContactsContainer").css("display","none");
    
    $( 'input[name="activecontactsradio"]:radio' ).change(function() {
        if($('input[name=activecontactsradio]:checked').val() == 1){
            $("#trashedContactsContainer").css("display","none");
            $("#activeContactsContainer").css("display","Inline");
        }
        if($('input[name=activecontactsradio]:checked').val() == 0){
            $("#activeContactsContainer").css("display","none");
            $("#trashedContactsContainer").css("display","Inline");
        }
        
    });
} );
</script> 