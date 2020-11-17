
    <div class="container">

        <div class="input-group">
            <span class="input-group-addon" for="template"><div style="width: 12em;">Template:</div></span>
            <select id="template" name="template" class="form-control">
            @foreach($templates as $template)
                <option value="{{ $template->id }}">{{ $template->name }}</option>
            @endforeach
            </select>
        </div>

        <div class="input-group">
            <span class="input-group-addon" for="contact-type"><div style="width: 12em;">Contact Type:</div></span>
            <select id="contact-type" name="contact-type" class="form-control">
                <option value="All" selected>All</option>
                @if(Auth::user()->hasPermission("client_permission") )
                    <option value="Client">Client</option>

                    @if(app()->make('account')->plan_name !== "SOLO")
                        <option value="Prospect">Prospect</option>
                    @endif
                @endif
                @if(Auth::user()->hasPermission("vendor_permission") )
                    <option value="Vendor">Vendor</option>
                @endif
                @if(Auth::user()->hasPermission("employee_permission") )
                    <option value="Employee">Team/Staff</option>
                @endif
            </select>
        </div>

        <div class="input-group">
            <span class="input-group-addon" for="contact-category"><div style="width: 12em;">Category/Department:</div></span>
            <select id="contact-category" name="contact-category" class="form-control">
                <option value="all" selected>All</option>
                <option value="none">None</option>
                @foreach(\App\Helpers\OS\Client\ClientHelper::AllCategorys() as $department)
                    <option value="{{ $department }}">{{ $department }}</option>
                @endforeach
                @foreach(\App\Helpers\OS\Client\ClientHelper::AllVendorCategorys() as $department)
                    <option value="{{ $department }}">{{ $department }}</option>
                @endforeach
                @foreach(\App\Helpers\EmployeeHelper::AllDepartments() as $department)
                    <option value="{{ $department }}">{{ $department }}</option>
                @endforeach
            </select>
        </div>

        <div class="input-group">
            <span class="input-group-addon" for="email-subject"><div style="width: 12em;">Subject:</div></span>
            <input id="email-subject" name="email-subject" class="form-control" placeholder="Subject">
        </div>

        <button id="group-email-send" type="button" class="btn OS-Button" style="width: 100%;">Send</button>

        <table id="group-email-table" class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th><input id="email-toggle-master" type="checkbox" data-on="Send" data-off="Dont Send" data-toggle="toggle" data-width="100%"></th>
                </tr>
            </thead>
            <tbody>
            @foreach($clients as $client)
                <tr class="group-email-table-row" data-type="{{ $client->getStatus() }}" style="background-color: lightblue;" data-category="{{ $client->category }}">
                    <td colspan="3">{{ $client->getStatus() }} - {{ $client->getName() }} - {{ $client->category }}</td>
                </tr>
            @foreach($client->contacts as $contact)
                <tr class="group-email-table-row" data-type="{{ $client->getStatus() }}" data-category="{{ $client->category }}">
                    <td>{{ $contact->firstname }} {{ $contact->lastname }}</td>
                    <td>{{ $contact->email }}</td>
                    <td><input type="checkbox" class="email-toggle" data-email="{{ $contact->email }}" data-type="Client" data-id="{{ $contact->id }}" data-on="Send" data-off="Dont Send" data-toggle="toggle" data-width="100%"></td>
                </tr>
            @endforeach
            @endforeach
            @foreach($vendors as $vendor)
                <tr class="group-email-table-row" data-type="Vendor" style="background-color: lightblue;" data-category="{{ $vendor->category }}">
                    <td colspan="3">Vendor - {{ $vendor->getName() }} - {{ $vendor->category }}</td>
                </tr>
            @foreach($vendor->contacts as $contact)
                <tr class="group-email-table-row" data-type="Vendor" data-category="{{ $vendor->category }}">
                    <td>{{ $contact->firstname }} {{ $contact->lastname }}</td>
                    <td>{{ $contact->email }}</td>
                    <td><input type="checkbox" class="email-toggle" data-email="{{ $contact->email }}" data-type="Vendor" data-id="{{ $contact->id }}" data-on="Send" data-off="Dont Send" data-toggle="toggle" data-width="100%"></td>
                </tr>
            @endforeach
            @endforeach
            @foreach(\App\Helpers\EmployeeHelper::AllDepartments() as $department)
                <tr class="group-email-table-row" data-type="Employee" style="background-color: lightblue;">
                    <td colspan="3">Employee - {{ $department }}</td>
                </tr>
                @foreach($employees->where('department', $department) as $employee)
                <tr class="group-email-table-row" data-type="Employee" data-category="{{ $employee->department }}">
                    <td>{{ $employee->firstname }} {{ $employee->lastname }}</td>
                    <td>{{ $employee->email }}</td>
                    <td><input type="checkbox" class="email-toggle" data-email="{{ $employee->email }}" data-type="User" data-id="{{ $employee->id }}" data-on="Send" data-off="Dont Send" data-toggle="toggle" data-width="100%"></td>
                </tr>
                @endforeach
            @endforeach
            <tr class="group-email-table-row" data-type="Employee" style="background-color: lightblue;">
                <td colspan="3">Employee - None</td>
            </tr>
            @foreach($employees->where('department', "") as $employee)
                <tr class="group-email-table-row" data-type="Employee" data-category="{{ $employee->department }}">
                    <td>{{ $employee->firstname }} {{ $employee->lastname }}</td>
                    <td>{{ $employee->email }}</td>
                    <td><input type="checkbox" class="email-toggle" data-email="{{ $employee->email }}" data-type="User" data-id="{{ $employee->id }}" data-on="Send" data-off="Dont Send" data-toggle="toggle" data-width="100%"></td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>

    <script>
        $(document).ready(function() {

            $('#template').select2({
                theme: "bootstrap"
            });

            $('#email-toggle-master').change(function() {


                if($(this).prop('checked') === true){
                    $('.email-toggle').each(function () {
                        debugger;
                        if($(this).parent().parent().parent().css('display') === "none"){

                        }else {
                            $(this).bootstrapToggle('on');
                        }
                    });
                }else{
                    $('.email-toggle').each(function () {
                        debugger;
                        if($(this).parent().parent().parent().css('display') === "none"){

                        }else {
                            $(this).bootstrapToggle('off');
                        }
                    });
                }

            });

            $('#group-email-send').click(function () {


                $emails = [];
                $emailstring = "";
                $('.email-toggle').each(function () {
                    if($(this).prop('checked') === true){


                        var object = {email: $(this).data('email'), type: $(this).data('type'), id: $(this).data('id')};

                        $emailstring = $emailstring + $(this).data('email') + ", ";

                        $emails.push(object);
                    }
                });

                $.confirm({
                    title: 'Send to the following ' + $emails.length + " addresses?",
                    content: $emailstring,
                    columnClass: 'col-me-12',
                    buttons: {
                        cancel: function () {

                        },
                        confirm: {
                            text: 'Send',
                            btnClass: 'btn-blue',
                            action: function(){
                                MakePost($('#template').val(), $emails, $('#email-subject').val());
                            }
                        }
                    }
                });


            });

            $('#contact-type').change(function () {

                $('#email-toggle-master').bootstrapToggle('off');
                if($(this).val() === "All"){
                    $('.group-email-table-row').css('display', 'table-row');
                }else{

                    $('.group-email-table-row').each(function () {
                        if($(this).data('type') === $('#contact-type').val()){
                            $(this).css('display', 'table-row');
                        }else{
                            $(this).css('display', 'none');
                        }
                    });

                }

            });
            
            $('#contact-category').change(function () {

                $('#email-toggle-master').bootstrapToggle('off');
                if($(this).val() === "all"){

                    $('.group-email-table-row').css('display', 'table-row');

                }else if($(this).val() === "none") {

                    $('.group-email-table-row').each(function () {
                        if($(this).data('category') === ""){
                            $(this).css('display', 'table-row');
                        }else{
                            $(this).css('display', 'none');
                        }
                    });

                }else{

                    $('.group-email-table-row').each(function () {

                        if(isNaN($(this).data('category'))){
                            $category = $(this).data('category');
                        }else{
                            $category = $(this).data('category').toString();
                        }

                        if($category === $('#contact-category').val()){
                            $(this).css('display', 'table-row');
                        }else{
                            $(this).css('display', 'none');
                        }
                    });

                }
            });
        });

        function MakePost($templateid, $emails, $subject) {

            $("body").addClass("loading");
            ResetServerValidationErrors();

            $data = {};
            $data['_token'] = "{{ csrf_token() }}";
            $data['templateid'] = $templateid;//
            $data['recipients'] = $emails;
            $data['subject'] = $subject;//

            $post = $.post("/Email/BulkSend", $data);

            $post.done(function (data) {
                $("body").removeClass("loading");
                switch(data['status']) {
                    case "OK":
                        SavedSuccess("Emails Sent.", "Success!", 1000);
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
        }
    </script>
