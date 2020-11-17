@extends('master')

@section('content')
    <div class="row">
        @desktop
        <div style="float:left; width: 10em;  margin-left: 20px;">
        @elsedesktop
        <div class="col-md-12">
        @enddesktop
            <button style="width: 100%;" id="save" name="save" type="button" class="btn OS-Button">Save</button>
        </div>

        @desktop
        <div style="float:left; width: 15em;  margin-left: 20px;">
        @elsedesktop
        <div class="col-md-12">
        @enddesktop
            <button style="width: 100%;" id="backbutton" name="backbutton" type="button" class="btn OS-Button">
                @if(isset($employee))
                    Back to Team/Staff
                @else
                    Back to Team/Staff Search
                @endif
            </button>
        </div>

        @desktop
        <div style="float:left; width: 15em;  margin-left: 20px;">
        @elsedesktop
        <div class="col-md-12">
        @enddesktop
            @if(isset($employee))
                <input type="checkbox" name="checkboxes" id="active"  data-on="Active" data-off="Inactive" data-toggle="toggle" data-width="100%"
                @if($employee->deleted_at === null)
                   checked
                @endif
                >
            @endif
        </div>

                <!--
                <div style="float:left; width: 20em;  margin-left: 20px;">
                    <label class="col-md-4 control-label tablabel" for="inactive" style="padding-top: 10px">Inactive:</label>
                    <div class="col-md-5 " style="padding-top: 10px">
                        <input type="checkbox" name="inactive" id="inactive">
                    </div>
                </div>
                -->
            </div>
            <div class="col-md-12">

                <legend>Name</legend>

                @desktop
                <div class="input-group">
                    <span class="input-group-addon" for="firstname"><div class="inputdiv">First Name*</div></span>
                    <input id="firstname" name="firstname" type="text" value="" class="form-control" required="">

                    <span class="input-group-addon" for="middlename"><div class="inputdiv">Middle Name</div></span>
                    <input id="middlename" name="middlename" type="text" value="" class="form-control">

                    <span class="input-group-addon" for="lastname"><div class="inputdiv">Last Name*</div></span>
                    <input id="lastname" name="lastname" type="text" value="" class="form-control" required="">
                </div>
                @elsedesktop
                <div class="input-group">
                    <span class="input-group-addon" for="firstname"><div class="inputdiv">First Name*</div></span>
                    <input id="firstname" name="firstname" type="text" value="" class="form-control" required="">
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="middlename"><div class="inputdiv">Middle Name</div></span>
                    <input id="middlename" name="middlename" type="text" value="" class="form-control">
                </div>
                <div class="input-group">
                    <span class="input-group-addon" for="lastname"><div class="inputdiv">Last Name*</div></span>
                    <input id="lastname" name="lastname" type="text" value="" class="form-control" required="">
                </div>
                @enddesktop
            </div>


            <div class="col-md-6">

                <legend>Details</legend>

                <div class="input-group">
                    <span class="input-group-addon" for="type"><div class="inputdiv">Type</div></span>
                    <select id="type" name="type" class="form-control">
                        <option value="1">Owner</option>
                        <option value="2" selected="selected">Employee - W2</option>
                        <option value="3">Contractor - 1099</option>
                        <option value="4">Agent</option>
                    </select>
                </div>

                <div class="input-group">
                    <span class="input-group-addon" for="employeeid"><div class="inputdiv">ID:</div></span>
                    <input id="employeeid" name="employeeid" type="text" value="" class="form-control">
                </div>

                <div class="input-group">
                    <span class="input-group-addon" for="email"><div class="inputdiv">E-Mail:*</div></span>
                    <input id="email" name="email" type="text" value="" class="form-control">
                </div>

                <div class="input-group">
                    <span class="input-group-addon" for="phonenumber"><div class="inputdiv">Phone Number:</div></span>
                    <input id="phonenumber" name="phonenumber" type="text" value="" class="form-control">
                </div>

                <div class="input-group">
                    <span class="input-group-addon" for="ssn"><div class="inputdiv">Department:</div></span>
                    <input id="department" name="department" type="text" value="" class="form-control" list="department-list" >
                    <datalist  id="department-list" name="department-list" >
                    @foreach(PageElement::Departments() as $depatment)
                        <option value="{{ $depatment }}">{{ $depatment }}</option>
                    @endforeach
                    </datalist>
                </div>

                <div class="input-group">
                    <span class="input-group-addon" for="ssn"><div class="inputdiv">Tax Id Number:</div></span>
                    <input id="ssn" name="ssn" type="text" value="" class="form-control">
                </div>

                <div class="input-group">
                    <span class="input-group-addon" for="ssn"><div class="inputdiv">Drivers License No.:</div></span>
                    <input id="driverslicense" name="driverslicense" type="text" value="" class="form-control">
                </div>

                <div class="input-group">
                    <span class="input-group-addon" for="ssn"><div class="inputdiv">Branch:</div></span>
                    <select id="branch_id" name="branch_id" class="form-control">
                        <option value="none">None</td></option>
                        @foreach($branches as $branch)
                            @if($branch->isDisabled() === false)
                                <option value="{{ $branch->id }}">{{ $branch->number }} {{ $branch->address1 }} {{ $branch->address2 }} {{ $branch->city }} {{ $branch->region }} {{ $branch->state }} {{ $branch->zip }}</td></option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="input-group">
                    <span class="input-group-addon" for="start-date"><div class="inputdiv">Start Date:</div></span>
                    <input id="start-date" name="start-date" type="text" value="" class="form-control" readonly>
                </div>

                <div class="input-group">
                    <span class="input-group-addon" for="end-date"><div class="inputdiv">End Date:</div></span>
                    <input id="end-date" name="end-date" type="text" value="" class="form-control" readonly>
                </div>

                <legend>Emergency Contact</legend>

                <div class="input-group">
                    <span class="input-group-addon" for="emergency_contact_name"><div class="inputdiv">Name:</div></span>
                    <input id="emergency_contact_name" name="emergency_contact_name" type="text" value="" class="form-control">
                </div>

                <div class="input-group">
                    <span class="input-group-addon" for="emergency_contact_relationship"><div class="inputdiv">Relationship:</div></span>
                    <input id="emergency_contact_relationship" name="emergency_contact_relationship" type="text" value="" class="form-control">
                </div>

                <div class="input-group">
                    <span class="input-group-addon" for="emergency_contact_phone_number"><div class="inputdiv">Phone Number:</div></span>
                    <input id="emergency_contact_phone_number" name="emergency_contact_phone_number" type="text" value="" class="form-control">
                </div>
            </div>


            <div class="col-md-6">

                <legend>Address
                    <button id="addressunlock" name="addressunlock" type="button" class="btn btn-warning btn-sm"><span
                                class="glyphicon glyphicon-lock"></span> Unlock Address
                    </button>
                </legend>

                <div class="input-group">
                    <span class="input-group-addon" for="number"><div class="inputdiv">House Name\Number:*</div></span>
                    <input id="number" name="number" type="text" placeholder="" class="form-control" required=""
                           disabled>
                </div>

                <div class="input-group">
                    <span class="input-group-addon" for="address1"><div class="inputdiv">Street:*</div></span>
                    <input id="address1" name="address1" type="text" placeholder="Address Line 1" class="form-control"
                           required="" disabled>
                </div>

                <!-- Text input-->
                <div class="input-group">
                    <span class="input-group-addon" for="address2"><div class="inputdiv">Address Line 2:</div></span>
                    <input id="address2" name="address2" type="text" placeholder="Address Line 2" class="form-control"
                           disabled>
                </div>

                <!-- Text input-->
                <div class="input-group">
                    <span class="input-group-addon" for="city"><div class="inputdiv">City:*</div></span>
                    <input id="city" name="city" type="text" placeholder="City" class="form-control" required=""
                           disabled>
                </div>

                <div class="input-group">
                    <span class="input-group-addon" for="region"><div class="inputdiv">Region:*</div></span>
                    <input id="region" name="region" type="text" placeholder="Region" class="form-control" required=""
                           disabled>
                </div>

                <!-- Text input-->
                <div class="input-group">
                    <span class="input-group-addon" for="state"><div class="inputdiv">State/Province:*</div></span>
                    <input id="state" name="state" type="text" placeholder="State" class="form-control" required=""
                           disabled>
                </div>

                <!-- Text input-->
                <div class="input-group">
                    <span class="input-group-addon" for="zip"><div class="inputdiv">Postal Code:*</div></span>
                    <input id="zip" name="zip" type="text" placeholder="Zip" class="form-control" required="" disabled>
                    <span class="input-group-btn">
                        <button id="lookup" name="lookup" type="button" class="btn btn-default" disabled>Lookup Address</button>
                    </span>
                </div>

            </div>

            <div class="modalload"><!-- Place at bottom of page --></div>



            <script>
                $(document).ready(function () {
                    setupPage();

                    $('#start-date').datepicker({
                        changeMonth: true,
                        changeYear: true,
                        controlType: 'select',
                        parse: "loose",
                        dateFormat: "yy-mm-dd",
                    });

                    $('#end-date').datepicker({
                        changeMonth: true,
                        changeYear: true,
                        controlType: 'select',
                        parse: "loose",
                        dateFormat: "yy-mm-dd",
                    });

                    $('#backbutton').click(function (e) {
                        var link = document.createElement('a');

                        @if(isset($employee))
                            link.href = "/Employees/View/" + $id;
                        @else
                            link.href = "/";
                        @endif

                            link.id = "link";
                        document.body.appendChild(link);
                        link.click();
                    });

                    $("#addressunlock").click(function () {
                        $('#zip').prop('disabled', false);
                        $('#lookup').prop('disabled', false);

                        $('#addressunlock').prop('disabled', true);
                        $saveaddress = true;

                    });

                    $("#save").click(function () {
                        $("body").addClass("loading");
                        ResetServerValidationErrors();

                        $postdata = {};
                        $postdata['_token'] = "{{ csrf_token() }}";

                        $postdata['id'] = $id;
                        $postdata['firstname'] = $('#firstname').val();
                        $postdata['middlename'] = $('#middlename').val();
                        $postdata['lastname'] = $('#lastname').val();
                        $postdata['ssn'] = $('#ssn').val();
                        $postdata['driverslicense'] = $('#driverslicense').val();
                        $postdata['email'] = $('#email').val();
                        $postdata['phonenumber'] = $('#phonenumber').val();
                        $postdata['department'] = $('#department').val();
                        $postdata['employeeid'] = $('#employeeid').val();
                        $postdata['branch_id'] = $('#branch_id').val();
                        $postdata['type'] = $('#type').val();

                        if($('#start-date').val() === "None Set, Click to select."){
                            $postdata['start-date'] = "";
                        }else{
                            $postdata['start-date'] = $('#start-date').val();
                        }

                        if($('#end-date').val() === "None Set, Click to select."){
                            $postdata['end-date'] = "";
                        }else{
                            $postdata['end-date'] = $('#end-date').val();
                        }

                        $postdata['emergency_contact_name'] = $('#emergency_contact_name').val();
                        $postdata['emergency_contact_relationship'] = $('#emergency_contact_relationship').val();
                        $postdata['emergency_contact_phone_number'] = $('#emergency_contact_phone_number').val();

                        $postdata['number'] = $('#number').val();
                        $postdata['address1'] = $('#address1').val();
                        $postdata['address2'] = $('#address2').val();
                        $postdata['city'] = $('#city').val();
                        $postdata['region'] = $('#region').val();
                        $postdata['state'] = $('#state').val();
                        $postdata['zip'] = $('#zip').val();


                        $post = $.post("/Employees/Save", $postdata);

                        $post.done(function (data) {
                            $("body").removeClass("loading");
                            switch(data['status']) {
                                case "OK":
                                    GoToPage("/Employees/View/" + data['id']);
                                    break;
                                case "notfound":
                                    $.dialog({
                                        title: 'Oops...',
                                        content: 'Unknown Response from server. Please refresh the page and try again.'
                                    });
                                    break;
                                case "validation":
                                    ServerValidationErrors(data['errors']);
                                    break;
                                case "notlogedin":
                                    NotLogedIN();
                                    break;
                                default:
                                    console.log(data);
                                    $.dialog({
                                        title: 'Oops...',
                                        content: 'Unknown Response from server. Please refresh the page and try again.'
                                    });
                            }
                        });

                        $post.fail(function () {
                            NoReplyFromServer();
                        });

                    });

                    $("#lookup").click(function () {
                        $done = function () {
                            $('#save').prop('disabled', false);
                        };

                        AddressLookup($('#zip').val());
                    });

                    @if(isset($employee))
                    $('#active').change(function () {
                        $("body").addClass("loading");

                        $data = {};
                        $data['_token'] = "{{ csrf_token() }}";
                        $data['id'] = "{{ $employee->id }}";

                        if($(this).prop('checked') === true){
                            $data['action'] = 1;
                        }else{
                            $data['action'] = 0;
                        }

                        $post = $.post("/Employees/Status", $data);

                        $post.done(function (data) {
                            $("body").removeClass("loading");
                            switch(data['status']) {
                                case "OK":
                                    switch(data['action']) {
                                        case "enabled":
                                            SavedSuccess('Employee Enabled.', "Success!", "2000");
                                            break;
                                        case "disabled":
                                            SavedSuccess('Employee Disabled.', "Success!", "2000");
                                            break;
                                        default:
                                            console.log(data);
                                            $.dialog({
                                                title: 'Oops...',
                                                content: 'Unknown Response from server. Please refresh the page and try again.'
                                            });
                                    }
                                    break;
                                case "notfound":
                                    $.dialog({
                                        title: 'Oops...',
                                        content: 'Unknown Response from server. Please refresh the page and try again.'
                                    });
                                    break;
                                default:
                                    console.log(data);
                                    $.dialog({
                                        title: 'Oops...',
                                        content: 'Unknown Response from server. Please refresh the page and try again.'
                                    });
                            }
                        });

                        $post.fail(function () {
                            NoReplyFromServer();
                        });
                    });
                    @endif
                });

                function PostAddress($number, $address1, $address2, $city, $region, $state, $zip) {
                    return $.post("/Address/Add",
                        {
                            _token: "{{ csrf_token() }}",
                            number: $number,
                            address1: $address1,
                            address2: $address2,
                            city: $city,
                            region: $region,
                            state: $state,
                            zip: $zip
                        });
                }

                function setupPage(){
                    @if(isset($employee))
                        $('#firstname').val("{{ $employee->firstname }}");
                        $('#middlename').val("{{ $employee->middlename }}");
                        $('#lastname').val("{{ $employee->lastname }}");


                        $('#ssn').val("{{ $employee->ssn }}");
                        $('#driverslicense').val("{{ $employee->driverslicense }}");
                        $('#email').val("{{ $employee->email }}");
                        $('#department').val("{{ $employee->department }}");
                        $('#employeeid').val("{{ $employee->employeeid }}");
                        $('#type').val(["{{ $employee->type }}"]);

                        $('#phonenumber').val("{{ $employee->phonenumber }}");

                        @if($employee->branch_id === null)
                        $('#branch_id').val("none");
                        @else
                        $('#branch_id').val("{{ $employee->branch_id }}");
                        @endif

                        $('#number').val("{{ $employee->address->number }}");
                        $('#address1').val("{{ $employee->address->address1 }}");
                        $('#address2').val("{{ $employee->address->address2 }}");
                        $('#city').val("{{ $employee->address->city }}");
                        $('#region').val("{{ $employee->address->region }}");
                        $('#state').val("{{ $employee->address->state }}");
                        $('#zip').val("{{ $employee->address->zip }}");


                        $address_id = {{ $employee->address_id }};
                        $id = {{ $employee->id }};
                        $saveaddress = false;

                        @if($employee->start_date === null)
                            $('#start-date').val("None Set, Click to select.");
                        @else
                            $('#start-date').val("{{ $employee->start_date }}");
                        @endif

                        @if($employee->end_date === null)
                            $('#end-date').val("None Set, Click to select.");
                        @else
                            $('#end-date').val("{{ $employee->end_date }}");
                        @endif

                        $('#emergency_contact_name').val("{{ $employee->emergency_contact_name }}");
                        $('#emergency_contact_relationship').val("{{ $employee->emergency_contact_relationship }}");
                        $('#emergency_contact_phone_number').val("{{ $employee->emergency_contact_phone_number }}");

                    @else
                        $id = 0;
                        @foreach($branches as $branch)
                        @if($branch->default === "1")
                        $('#branch_id').val("{{ $branch->id }}");
                        @endif
                        @endforeach

                        $('#zip').prop('disabled', false);
                        $('#lookup').prop('disabled', false);
                        $('#employeeid').prop('disabled', true);
                        $("#addressunlock").hide();
                        $saveaddress = true;
                    @endif
                }
            </script>

@stop
