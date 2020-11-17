<div class="container">

    <div id="compensation-payroll">
        <legend>Payroll</legend>

        @if(SettingHelper::GetSetting('Payroll-Frequency') === null)
            <p>In order to use this section please head to the payroll section and set your payroll options</p>
        @endif

        <div class="input-group ">
            <span class="input-group-addon" for="type"><div style="width: 15em;">Type:</div></span>
            <select id="type" name="type" type="text" placeholder="" class="form-control">
                <option value="1">Owner</option>
                <option value="2" selected="selected">Employee - W2</option>
                <option value="3">Contractor - 1099</option>
                <option value="4">Agent</option>
            </select>
        </div>

        <div class="input-group ">
            <span class="input-group-addon" for="ssn"><div style="width: 15em;">Tax ID Number:</div></span>
            <input id="ssn" name="ssn" type="text" value="" class="form-control locked-payroll">
            <span class="input-group-btn">
                <button class="btn btn-default unlock" type="button">
                    <span class="glyphicon glyphicon-lock"></span> Unlock
                </button>
            </span>
        </div>

        <div class="input-group ">
            <span class="input-group-addon" for="rate"><div style="width: 15em;">Rate:</div></span>
            <input id="rate" name="rate" type="text" value="{{ number_format($employee->rate ,2, '.', '') }}" class="form-control locked-payroll">
            <span class="input-group-btn">
                <button class="btn btn-default unlock" type="button">
                    <span class="glyphicon glyphicon-lock"></span> Unlock
                </button>
            </span>
        </div>

        @if(SettingHelper::GetSetting('Payroll-Frequency') === "weekly")
            <div class="input-group ">
                <span class="input-group-addon" for="frequency"><div style="width: 15em;">Frequency :</div></span>
                <select id="frequency" name="frequency" type="text" placeholder="" class="form-control">
                    <option value="hour" selected="selected">Per Hour</option>
                    <option value="day">Per Day</option>
                    <option value="week">Per Week</option>
                </select>
            </div>
        @endif
        @if(SettingHelper::GetSetting('Payroll-Frequency') === "biweekly")
            <div class="input-group ">
                <span class="input-group-addon" for="frequency"><div style="width: 15em;">Frequency :</div></span>
                <select id="frequency" name="frequency" type="text" placeholder="" class="form-control">
                    <option value="hour" selected="selected">Per Hour</option>
                    <option value="day">Per Day</option>
                    <option value="week">Per Week</option>
                </select>
            </div>
        @endif
        @if(SettingHelper::GetSetting('Payroll-Frequency') === "semimonthly")
            <div class="input-group ">
                <span class="input-group-addon" for="frequency"><div style="width: 15em;">Frequency :</div></span>
                <select id="frequency" name="frequency" type="text" placeholder="" class="form-control">
                    <option value="hour" selected="selected">Per Hour</option>
                    <option value="day">Per Day</option>
                    <option value="month">Per Month</option>
                </select>
            </div>
        @endif
        @if(SettingHelper::GetSetting('Payroll-Frequency') === "monthly")
            <div class="input-group ">
                <span class="input-group-addon" for="frequency"><div style="width: 15em;">Frequency :</div></span>
                <select id="frequency" name="frequency" type="text" placeholder="" class="form-control">
                    <option value="hour" selected="selected">Per Hour</option>
                    <option value="day">Per Day</option>
                    <option value="month">Per Month</option>
                </select>
            </div>
        @endif

        <button id="save-compensation" name="save-compensation" type="button" class="btn OS-Button"
                style="width: 100%;">Save Payroll
        </button>
    </div>

    <div id="compensation-payroll">
        <legend>Commission</legend>

        <div class="input-group ">
            <span class="input-group-addon" for="product-commission"><div style="width: 15em;">Products: (%)</div></span>
            <input id="product-commission" name="product-commission" type="text"
                   value="{{ $employee->product_commission }}" class="form-control numonly locked">
            <span class="input-group-btn">
                <button class="btn btn-default unlock" type="button">
                    <span class="glyphicon glyphicon-lock"></span> Unlock
                </button>
            </span>
        </div>

        <div class="input-group ">
            <span class="input-group-addon" for="service-commission"><div style="width: 15em;">Services: (%)</div></span>
            <input id="service-commission" name="service-commission" type="text"
                   value="{{ $employee->service_commission }}" class="form-control numonly locked">
            <span class="input-group-btn">
                <button class="btn btn-default unlock" type="button">
                    <span class="glyphicon glyphicon-lock"></span> Unlock
                </button>
            </span>
        </div>

        <button id="save-commission" name="save-commission" type="button" class="btn OS-Button" style="width: 100%;">
            Save Commission
        </button>
    </div>

    <div class="modal fade" id="UnlockModal" tabindex="-1" role="dialog" aria-labelledby="UnlockModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Confirm Login Credentials</h4>
                </div>
                <div class="modal-body">
                    <div class="input-group">
                        <span class="input-group-addon" for="ssn"><div style="width: 15em;">Email:</div></span>
                        <input id="unlock-email" name="unlock-email" class="form-control" style="z-index: 100000;">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon" for="ssn"><div style="width: 15em;">Password:</div></span>
                        <input id="unlock-password" name="unlock-password" class="form-control" type="password" style="z-index: 100000;">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Save & Close</button>

                </div>
            </div>
        </div>
    </div>


</div>

<script>
    $(document).ready(function () {

        $('#frequency').val('{{ $employee->frequency }}');

        @if(SettingHelper::GetSetting('Payroll-Frequency') === null)
        $("#compensation-payroll *").prop('disabled', true);
        @endif

        $('.locked').prop('disabled', true);
        $('.locked-payroll').prop('disabled', true);

        $('.numonly').on('keypress', function (e) {
            keys = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.'];
            return keys.indexOf(event.key) > -1;
        });

        $('.unlock').click(function () {
            $('#UnlockModal').modal('show');
        });

        $('#submit-unlock').click(function () {
            $("body").addClass("loading");
            ResetServerValidationErrors();

            $data = {};
            $data['_token'] = "{{ csrf_token() }}";
            $data['id'] = "{{ $employee->id }}";
            $data['email'] = $('#unlock-email').val();
            $data['password'] = $('#unlock-password').val();

            $post = $.post("/Employees/Unlock", $data);

            $post.done(function (data) {
                $("body").removeClass("loading");
                switch (data['status']) {
                    case "OK":

                        $('.locked').prop('disabled', false);

                        @if(SettingHelper::GetSetting('Payroll-Frequency') != null)
                        $('.locked-payroll').prop('disabled', false);
                        @endif

                        $('#UnlockModal').modal('hide');

                        $('.unlock').prop('disabled', true);

                        $('#ssn').val(data['ssn']);

                        break;
                    case "failed":
                        $.dialog({
                            title: 'Oops...',
                            content: 'Authentification failed.'
                        });
                        break;
                    case "notfound":
                        $.dialog({
                            title: 'Oops...',
                            content: 'Unknown Response from server. Please refresh the page and try again.'
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

        $("#save-commission").click(function () {

            $("body").addClass("loading");
            ResetServerValidationErrors();

            $data = {};
            $data['_token'] = "{{ csrf_token() }}";
            $data['id'] = "{{ $employee->id }}";
            $data['product-commission'] = $('#product-commission').val();
            $data['service-commission'] = $('#service-commission').val();

            $post = $.post("/Employees/SaveCommission", $data);

            $post.done(function (data) {
                $("body").removeClass("loading");
                switch (data['status']) {
                    case "OK":
                        SavedSuccess();
                        break;
                    case "notfound":
                        $.dialog({
                            title: 'Oops...',
                            content: 'Unknown Response from server. Please refresh the page and try again.'
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


        $("#save-compensation").click(function () {

            $("body").addClass("loading");

            $data = {};
            $data['_token'] = "{{ csrf_token() }}";
            $data['id'] = "{{ $employee->id }}";
            $data['type'] = $('#type').val();

            if($('#ssn').prop('disabled')){
                $data['ssn'] = "LOCKED";
            }else{
                $data['ssn'] = $('#ssn').val();
            }

            if($('#rate').prop('disabled')){
                $data['rate'] = "LOCKED";
            }else{
                $data['rate'] = $('#rate').val();
            }

            $data['frequency'] = $('#frequency').val();

            $post = $.post("/Employees/SaveCompensation", $data);

            $post.done(function (data) {
                $("body").removeClass("loading");
                switch (data['status']) {
                    case "OK":
                        SavedSuccess();
                        break;
                    case "nopayroll":
                        $.dialog({
                            title: 'Oops...',
                            content: 'Unknown Response from server. Please refresh the page and try again.'
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
    });
</script> 