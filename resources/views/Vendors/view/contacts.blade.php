<br/>
<a id="link" href="/Vendors/Contact/New/{{ $vendor->id }}"><button class="btn OS-Button btn-sm" type="button">Add New Contact</button></a>
<!--<a id="link" href="/Clients/Contact/New/{{ $vendor->id }}">Add New Contact</a> -->
<label class="radio-inline"><input type="radio" name="activecontactsradio" value="1" checked="checked">Active Contacts</label>
<label class="radio-inline"><input type="radio" name="activecontactsradio" value="0">Inactive Contacts</label>

<div id="activeContactsContainer">
    <table class="table">
        <thead>
            <tr>
                <th class="col-md-2">Contact Name</th>
                <th class="col-md-2">Contact Type</th>
                <th class="col-md-2">Email</th>
                <th class="col-md-4">Address</th>
                <th class="col-md-1"></th>
                <th class="col-md-1"></th>
            </tr>
        </thead>
        <tbody>
        @foreach ($vendor->contacts as $contact)
            <tr>
                <td>{{ $contact->firstname }} {{ $contact->lastname }}</td>
                <td>{{ $contact->contacttype }}</td>
                <td>{!! PageElement::EmailLink($contact->email) !!}</td>
                <td>{{ $contact->address->number }} {{ $contact->address->address1 }} {{ $contact->address->address2 }} {{ $contact->address->city }} {{ $contact->address->region }} {{ $contact->address->state }} {{ $contact->address->zip }}</td>
                <td><button style="width: 100%;" type="button" class="btn OS-Button btn-sm" data-toggle="modal" data-target="#{{ $contact->id }}Details">Details</button></td>
                <!--<td><button type="button" class="btn OS-Button btn-sm" data-toggle="modal" data-target="#{{ $contact->id }}Edit">Edit</button></td>-->
                <td><a id="link" href="/Vendors/Contact/{{$contact->id }}"><button style="width: 100%;" class="btn OS-Button btn-sm" type="button">Edit</button></a></td>
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
                <th class="col-md-4">Address</th>
                <th class="col-md-1"></th>
                <th class="col-md-1"></th>
            </tr>
        </thead>
        <tbody>
        @foreach ($vendor->trashedcontacts as $contact)
            <tr>
                <td>{{ $contact->firstname }} {{ $contact->lastname }}</td>
                <td>{{ $contact->contacttype }}</td>
                <td>{!! PageElement::EmailLink($contact->email) !!}</td>
                <td>{{ $contact->address->number }} {{ $contact->address->address1 }} {{ $contact->address->address2 }} {{ $contact->address->city }} {{ $contact->address->region }} {{ $contact->address->state }} {{ $contact->address->zip }}</td>
                <td><button style="width: 100%;" type="button" class="btn OS-Button btn-sm" data-toggle="modal" data-target="#{{ $contact->id }}Details">Details</button></td>
                <td><a id="link" href="/Clients/Contact/{{$contact->id }}"><button style="width: 100%;" class="btn OS-Button btn-sm" type="button">Edit</button></a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

@foreach ($vendor->contacts as $contact)
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

@foreach ($vendor->trashedcontacts as $contact)
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