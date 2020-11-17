<div class="row" style="margin-top: 15px;">
    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="search"><div style="width: 7em;">Search:</div></span>
            <input id="patient-search" name="patient-search" type="text" placeholder="" value="" class="form-control">
        </div>
    </div>
    <div class="col-md-6">
        {!! PageElement::TableControl('patient') !!}
    </div>

    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="patient-status"><div style="width: 7em;">Action:</div></span>
            <select id="patient-action-select" name="invoice-status" type="text" placeholder="choice" class="form-control">
                <option value="add">Add New Patient</option>
                <option value="edit">Edit Patient</option>
                <option value="schedule" selected>Add To Schedule</option>
                <option value="send" selected>Send List</option>
            </select>
            <span class="input-group-btn">
                <button id="patient-action-button" class="btn btn-default" type="button">GO</button>
            </span>
        </div>
    </div>
</div>

<table id="patientsearch" class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Scheduled</th>
            <th>Mobile Number</th>
            <th>Home Number</th>
            <th>E-Mail</th>
            <th>Comments</th>
            <th>Address number</th>
            <th>Address address1</th>
            <th>Address address2</th>
            <th>Address city</th>
            <th>Address region</th>
            <th>Address state</th>
            <th>Address zip</th>
            <th>firstname</th>
            <th>lastname</th>
        </tr>
    </thead>
    <tfoot style="visibility: hidden;">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Scheduled</th>
            <th>Mobile Number</th>
            <th>Home Number</th>
            <th>E-Mail</th>
            <th>Comments</th>
            <th>Address number</th>
            <th>Address address1</th>
            <th>Address address2</th>
            <th>Address city</th>
            <th>Address region</th>
            <th>Address state</th>
            <th>Address zip</th>
            <th>firstname</th>
            <th>lastname</th>
        </tr>
    </tfoot>
    <tbody>
    @foreach($client->patient as $patient)
        <tr>
            <td>{{ $patient->id }}</td>
            <td>{{ $patient->firstname }} {{ $patient->lastname }}</td>
            <td>@if($patient->scheduled == 'YES') YES @else NO @endif</td>
            <td><a href="tel:{{ $patient->GetMobile() }}">{{ $patient->GetMobile() }}</a></td>
            <td><a href="tel:{{ $patient->GetHome() }}">{{ $patient->GetHome() }}</a></td>
            <td>{{ $patient->email }}</td>
            <td>{{ $patient->comments }}</td>
            @if($patient->address_id == NULL)
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            @else
            <td>{{ $patient->address->number }}</td>
            <td>{{ $patient->address->address1 }}</td>
            <td>{{ $patient->address->address2 }}</td>
            <td>{{ $patient->address->city }}</td>
            <td>{{ $patient->address->region }}</td>
            <td>{{ $patient->address->state }}</td>
            <td>{{ $patient->address->zip }}</td>
            @endif
            <td>{{ $patient->firstname }}</td>
            <td>{{ $patient->lastname }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="modal fade" id="patientModel" tabindex="-1" role="dialog" aria-labelledby="patientModel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="">Patient:</h4>
            </div>
            <div class="modal-body">
                <input id="patient-id" name="patient-id" type="text" placeholder="" value="" class="form-control" style="display: none;">
                <div class="input-group ">
                    <span class="input-group-addon" for="patient-firstname"><div class="inputdiv">Firstname:</div></span>
                    <input id="patient-firstname" name="patient-firstname" type="text" placeholder="" value="" class="form-control">
                </div>
                <div class="input-group ">
                    <span class="input-group-addon" for="patient-lastname"><div class="inputdiv">Lastname:</div></span>
                    <input id="patient-lastname" name="patient-lastname" type="text" placeholder="" value="" class="form-control">
                </div>
                <div class="input-group ">
                    <span class="input-group-addon" for="patient-scheduled"><div class="inputdiv">Scheduled:</div></span>
                    <input id="patient-scheduled" name="patient-scheduled" type="checkbox" placeholder="" value="" class="form-control">
                </div>
                <div class="input-group ">
                    <span class="input-group-addon" for="patient-mobilenumber"><div class="inputdiv">Mobile Number:</div></span>
                    <input id="patient-mobilenumber" name="patient-mobilenumber" type="text" placeholder="" value="" class="form-control">
                </div>
                <div class="input-group ">
                    <span class="input-group-addon" for="patient-homenumber"><div class="inputdiv">Home Number:</div></span>
                    <input id="patient-homenumber" name="patient-homenumber" type="text" placeholder="" value="" class="form-control">
                </div>
                <div class="input-group ">
                    <span class="input-group-addon" for="patient-email"><div class="inputdiv">E-Mail:</div></span>
                    <input id="patient-email" name="patient-email" type="text" placeholder="" value="" class="form-control">
                </div>

                <div class="input-group ">
                    <span class="input-group-addon" for="patient-comments"><div class="inputdiv">Comments:</div></span>
                    <textarea id="patient-comments" name="patient-comments" type="text" placeholder="" value="" class="form-control" ></textarea>
                </div>

                <div class="input-group ">
                    <span class="input-group-addon" for="patient-number"><div class="inputdiv">House Name\Number:</div></span>
                    <input id="patient-number" name="patient-number" type="text" placeholder="" class="form-control" required="" disabled>
                </div>

                <div class="input-group ">
                    <span class="input-group-addon" for="patient-address1"><div class="inputdiv">Street:</div></span>
                    <input id="patient-address1" name="patient-address1" type="text" placeholder="Address Line 1" class="form-control" required="" disabled>
                </div>

                <!-- Text input-->
                <div class="input-group ">
                    <span class="input-group-addon" for="patient-address2"><div class="inputdiv">Address Line 2:</div></span>
                    <input id="patient-address2" name="patient-address2" type="text" placeholder="Address Line 2" class="form-control" disabled>
                </div>

                <!-- Text input-->
                <div class="input-group ">
                    <span class="input-group-addon" for="patient-city"><div class="inputdiv">City:</div></span>
                    <input id="patient-city" name="patient-city" type="text" placeholder="City" class="form-control" required="" disabled>
                </div>

                <div class="input-group ">
                    <span class="input-group-addon" for="patient-region"><div class="inputdiv">Region:</div></span>
                    <input id="patient-region" name="patient-region" type="text" placeholder="Region" class="form-control" required="" disabled>
                </div>

                <!-- Text input-->
                <div class="input-group ">
                    <span class="input-group-addon" for="patient-state"><div class="inputdiv">State/Province:</div></span>
                    <input id="patient-state" name="patient-state" type="text" placeholder="State" class="form-control" required="" disabled>
                </div>

                <!-- Text input-->
                <div class="input-group ">
                    <span class="input-group-addon" for="patient-zip"><div class="inputdiv">Postal Code:</div></span>
                    <input id="patient-zip" name="patient-zip" type="text" placeholder="Zip" class="form-control" required="">
                    <span class="input-group-btn">
                    <button id="patient-lookup" name="patient-lookup" type="button" class="btn btn-default">Lookup Address</button>
                </span>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button id="patient-save" type="button" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="patientScheduleModel" tabindex="-1" role="dialog" aria-labelledby="patientScheduleModel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="">Schedule Patient:</h4>
            </div>
            <div class="modal-body">
                <input id="schedule-patient-id" name="schedule-patient-id" type="text" placeholder="" value="" class="form-control" style="display: none;">

                <div class="input-group ">
                    <span class="input-group-addon" for="schedule-patient-date"><div style="width: 15em;">Date:</div></span>
                    <input style="z-index: 100000;" id="schedule-patient-date" name="schedule-patient-date" type="text" placeholder="" class="form-control" readonly>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button id="schedule-patient-save" type="button" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {

        $('#schedule-patient-date').datepicker({
            changeMonth: true,
            changeYear: true,
            controlType: 'select',
            parse: "loose",
            dateFormat: "yy-mm-dd",
        });

        // DataTable
        var patientsearch = $('#patientsearch').DataTable(
            {
                "columns": [
                    {"data": "id"},
                    {"data": "name"},
                    {"data": "scheduled"},
                    {"data": "mobilenumber"},
                    {"data": "homenumber"},
                    {"data": "email"},
                    {"data": "comments"},
                    {"data": "number"},
                    {"data": "address1"},
                    {"data": "address2"},
                    {"data": "city"},
                    {"data": "region"},
                    {"data": "state"},
                    {"data": "zip"},
                    {"data": "firstname"},
                    {"data": "lastname"},
                ],
                "order": [[1, "desc"]],
                "columnDefs": [
                    {"targets": [0, 7, 5, 8, 9, 10, 11, 12, 13, 14,15], "visible": false}
                ]
            }
        );

        $( "#patient-previous-page" ).click(function() {
            patientsearch.page( "previous" ).draw('page');
            PageinateUpdate(patientsearch.page.info(), $('#patient-next-page'), $('#patient-previous-page'),$('#patient-tableInfo'));
        });

        $( "#patient-next-page" ).click(function() {
            patientsearch.page( "next" ).draw('page');
            PageinateUpdate(patientsearch.page.info(), $('#patient-next-page'), $('#patient-previous-page'),$('#patient-tableInfo'));
        });

        $('#patient-search').on( 'keyup change', function () {
            patientsearch.search( this.value ).draw();
            PageinateUpdate(patientsearch.page.info(), $('#patient-next-page'), $('#patient-previous-page'),$('#patient-tableInfo'));
        });

        PageinateUpdate(patientsearch.page.info(), $('#patient-next-page'), $('#patient-previous-page'),$('#patient-tableInfo'));

        $("#patientsearch").css("width", "100%");

        $('#patientsearch tbody').on( 'click', 'tr', function () {
            $row = $(this);
            if ( $(this).hasClass('selected') ) {
                $(this).removeClass('selected');
            }
            else {
                patientsearch.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }
        } );

        $('#patient-action-button').click(function () {

            switch ($('#patient-action-select').val()) {
                case "add":
                    $('#patient-id').val('0');
                    $('#patient-firstname').val('');
                    $('#patient-lastname').val('');
                    $('#patient-scheduled').val('0');
                    $('#patient-mobilenumber').val('');
                    $('#patient-homenumber').val('');
                    $('#patient-email').val('');
                    $('#patient-number').val('');
                    $('#patient-address1').val('');
                    $('#patient-address2').val('');
                    $('#patient-city').val('');
                    $('#patient-region').val('');
                    $('#patient-state').val('');
                    $('#patient-zip').val('');

                    $('#patient-comments').val('');

                    $('#patient-number').prop('disabled', false);
                    $('#patient-address1').prop('disabled', true);
                    $('#patient-address2').prop('disabled', true);
                    $('#patient-city').prop('disabled', true);
                    $('#patient-region').prop('disabled', true);
                    $('#patient-state').prop('disabled', true);
                    $('#patient-zip').prop('disabled', false);

                    $('#patientModel').modal('show');
                    break;
                case "edit":
                    $row = patientsearch.row('.selected').data();

                    if($row === undefined){
                        event.preventDefault();
                        $.dialog({
                            title: 'Oops...',
                            content: 'Please Select a Patient from the table.'
                        });
                    }else{

                        $('#patient-id').val($row['id']);
                        $('#patient-firstname').val($row['firstname']);
                        $('#patient-lastname').val($row['lastname']);
                        if ($row['scheduled'] == "YES")
                        {
                            $('#patient-scheduled').prop("checked", true);
                        }else{
                            $('#patient-scheduled').prop("checked", false);
                        }
                        $('#patient-mobilenumber').val($row['mobilenumber']);
                        $('#patient-homenumber').val($row['homenumber']);
                        $('#patient-email').val($row['email']);
                        $('#patient-number').val($row['number']);
                        $('#patient-address1').val($row['address1']);
                        $('#patient-address2').val($row['address2']);
                        $('#patient-city').val($row['city']);
                        $('#patient-region').val($row['region']);
                        $('#patient-state').val($row['state']);
                        $('#patient-zip').val($row['zip']);
                        $('#patient-comments').val($row['comments']);

                        $('#patient-number').prop('disabled', false);
                        $('#patient-address1').prop('disabled', false);
                        $('#patient-address2').prop('disabled', false);
                        $('#patient-city').prop('disabled', false);
                        $('#patient-region').prop('disabled', false);
                        $('#patient-state').prop('disabled', false);
                        $('#patient-zip').prop('disabled', false);

                        $('#patientModel').modal('show');
                    }

                    break;
                case "schedule":
                    $row = patientsearch.row('.selected').data();

                    if($row === undefined){
                        event.preventDefault();
                        $.dialog({
                            title: 'Oops...',
                            content: 'Please Select a Patient from the table.'
                        });
                    }else{
                        $('#schedule-patient-id').val($row['id']);

                        $('#patientScheduleModel').modal('show');

                    }

                    break;
                case "send":

                    $PatData = "<HTML>PatientData <br //><br //>";
                    $PatData = "<style>\n" +
                        "th, td {\n" +
                        "padding: 15px;\n" +
                        "border: 1px solid #ddd;\n" +
                        "text-align: left;\n" +
                        "}\n" +
                        "</style>"
                    $PatData += "<TABLE><tr >"
                    $PatData += "<td>Firstname<//td><td>LastName<//td><td>Scheduled<//td><td>Mobile<//td><td>Home<//td><td>Comments<//td><//tr>";
                    patientsearch.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
                        $row = this.data();
                        $PatData += "<tr><td>" + $row['firstname'] + "<//td><td>" + $row['lastname'] + "<//td><td>" + $row['scheduled'] + "<//td><td>" + $row['mobilenumber'] + "<//td><td>" + $row['homenumber'] + "<//td><td>" + $row['comments'] + "<//td><//tr>";
                    } );
                    $PatData += "<//table><//HTML>";
                    $email = "{{ $client->primarycontact->email }}";
                    EmailPatientList($PatData, $email);
                    break;
            }
        });

        $('#patient-save').click(function () {
            $("body").addClass("loading");
            ResetServerValidationErrors();
            alert('save');
            $data = {};
            $data['_token'] = "{{ csrf_token() }}";
            $data['id'] = $('#patient-id').val();
            $data['firstname'] = $('#patient-firstname').val();
            $data['lastname'] = $('#patient-lastname').val();
            if($('#patient-scheduled').is(':checked')) {
                $data['scheduled'] = 'YES';
            }else{
                $data['scheduled'] = 'NO';
            }

            $data['mobilenumber'] = $('#patient-mobilenumber').val();
            $data['homenumber'] = $('#patient-homenumber').val();
            $data['email'] = $('#patient-email').val();
            $data['number'] = $('#patient-number').val();
            $data['address1'] = $('#patient-address1').val();
            $data['address2'] = $('#patient-address2').val();
            $data['city'] = $('#patient-city').val();
            $data['region'] = $('#patient-region').val();
            $data['state'] = $('#patient-state').val();
            $data['zip'] = $('#patient-zip').val();
            $data['client_id'] = "{{ $client->id }}";
            $data['comments'] = $('#patient-comments').val();

            $post = $.post("/Patients/Save", $data);

            $post.done(function (data) {
                $("body").removeClass("loading");
                switch(data['status']) {
                    case "OK":
                        if(data['new'] ==  "true"){
                            $('#patientsearch').DataTable().row.add(data['data']).draw();
                        }else{
                            $('#patientsearch').DataTable().row('.selected').data(data['data']).draw();
                        }
                        $('#patientModel').modal('hide');
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

        $('#schedule-patient-save').click(function () {
            $("body").addClass("loading");
            ResetServerValidationErrors();

            $data = {};
            $data['_token'] = "{{ csrf_token() }}";
            $data['patient-id'] = $('#schedule-patient-id').val();
            $data['date'] = $('#schedule-patient-date').val();

            console.log($data);

            $post = $.post("/Patients/Schedule", $data);

            $post.done(function (data) {
                $("body").removeClass("loading");
                switch(data['status']) {
                    case "OK":
                        $('#patientScheduleModel').modal('hide');
                        $('#schedule-patient-date').val("");
                        $('#schedule-patient-id').val("");

                        var link= document.createElement("a");
                        link.id = 'patient-schedule-link'; //give it an ID!
                        link.href = "/Scheduling/View/agendaDay/" + data['time'];
                        link.target = "_blank";
                        link.click();

                        $('#patient-schedule-link').remove();

                        break;
                    case "notfound":
                        $.dialog({
                            title: 'Oops...',
                            content: 'Unknown Response from server. Please refresh the page and try again.'
                        });
                        break;
                    case "validation":
                        $.dialog({
                            title: 'Oops...',
                            content: 'Not a valid date.'
                        });
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

        $("#patient-lookup").click(function () {
            PatientAddressLookup($('#patient-zip').val());
        });

    });

    function PatientAddressLookup($zip) {

        $("body").addClass("loading");

        $data = {};
        $data['_token'] = "{{ csrf_token() }}";
        $data['zip'] = $zip;

        $post = $.post("/Address/Lookup", $data);

        $post.done(function ($addressdata) {
            $("body").removeClass("loading");
            switch($addressdata['status']) {
                case "OK":
                    PatientPopulateAddressFields($addressdata['data']);
                    break;
                case "error":
                    $.dialog({
                        title: 'Oops..',
                        content: $addressdata['reason']
                    });
                    break;
                default:
                    $.dialog({
                        title: 'Oops...',
                        content: 'Unknown Response from server. Please refresh the page and try again.'
                    });
            }
        });

        $post.fail(function () {
            NoReplyFromServer();
        });

    }

    function PatientPopulateAddressFields($addressdata){
        $.each($addressdata, function (index, value) {

            $('#patient-number').prop('disabled', false);
            switch (index) {
                case "postal_code":
                    $('#patient-zip').val(value);
                case "state_province":
                    if (value == "") {
                        $('#patient-state').prop('disabled', false);
                        $('#patient-state').val(value);
                    } else {
                        $('#patient-state').prop('disabled', true);
                        $('#patient-state').val(value);
                    }
                    break;
                case "region":
                    if (value == "") {
                        $('#patient-region').prop('disabled', false);
                        $('#patient-region').val(value);
                    } else {
                        $('#patient-region').prop('disabled', true);
                        $('#patient-region').val(value);
                    }
                    break;
                case "city":
                    if (value == "") {
                        $('#patient-city').prop('disabled', false);
                        $('#patient-city').val(value);
                    } else {
                        $('#patient-city').prop('disabled', true);
                        $('#patient-city').val(value);
                    }
                    break;
                case "address2":
                    if (value == "") {
                        $('#patient-address2').prop('disabled', false);
                        $('#patient-address2').val(value);
                    } else {
                        $('#patient-address2').prop('disabled', true);
                        $('#patient-address2').val(value);
                    }
                    break;
                case "address1":
                    if (value == "") {
                        $('#patient-address1').prop('disabled', false);
                        $('#patient-address1').val(value);
                    } else {
                        $('#patient-address1').prop('disabled', true);
                        $('#patient-address1').val(value);
                    }
                    break;
            }
        });
    }
</script>
