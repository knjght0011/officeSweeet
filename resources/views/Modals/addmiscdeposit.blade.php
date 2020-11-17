@if(Auth::user()->hasPermission('deposits_permission'))

{{--
Modal for adding misc deposits
required data attributes:

include with:
     @include('Modals.addmiscdeposit') 
--}}


<div class="modal fade" id="AddMiscDepositModel" tabindex="-1" role="dialog" aria-labelledby="AddPaymentModel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="exampleModalLabel">Add Misc Deposit:</h4>
            </div>
            <div class="modal-body">

                @if(Auth::user()->hasPermissionMulti('multi_assets_permission', 3))
                <div class="input-group ">
                    <label class="input-group-addon" for="type">
                        <div style="width: 10em;">Type:</div>
                    </label>
                    <select id="misc-deposit-type" class="form-control input-md">
                        <option value="income">Income</option>
                        <option value="equity">Equity</option>
                    </select>
                </div>
                @endif

                <div class="input-group">
                    <span class="input-group-addon" for="deposit-date"><div style="width: 10em;">Date:</div></span>  
                    <input id="deposit-date" type="text" name="deposit-date"  class="form-control input-md" value="{{ date('Y-m-d') }}"  readonly style="z-index: 10000;">
                </div>  
                
                <div class="input-group">
                    <span width="10em" class="input-group-addon" for="deposit-amount"><div style="width: 10em;">Amount:</div></span>
                    <input id="deposit-amount" name="deposit-amount" type="text" class="form-control input-md" value="0.00" data-validation-label="Amount" data-validation-required="true" data-validation-type="amount">
                </div>

                <div class="input-group">
                    <span width="10em" class="input-group-addon" for="misc-deposit-catagorys"><div style="width: 10em;">Categories:</div></span>
                    <select multiple id="misc-deposit-catagorys" class="form-control input-md">
                    </select>
                    <span style="height: 100%; padding: 0px;" class="input-group-btn">
                            <button style="height: 82px;" id="misc-deposit-SplitAmountModalButton" class="btn btn-default" type="button"
                                    data-toggle="modal" data-target="#SplitAmountModal" data-amount="deposit-amount" data-output="misc-deposit-catagorys"
                                    data-type="income">Select</button>
                        </span>
                </div>
                
                <div class="input-group">
                    <span width="10em" class="input-group-addon" for="deposit-method"><div style="width: 10em;">Method:</div></span>
                    <select type="text" class="form-control input-md" name="deposit-method" id="deposit-method">
                        <option value="Cash">Cash</option>
                        <option value="Check">Check</option>
                        <option value="Credit Card">Credit Card</option>
                        <option value="Debit Card">Debit Card</option>
                        <option value="ACH/ETF & Wire">ACH/ETF & Wire</option>
                    </select>    
                </div>
                
                <div class="input-group">
                    <span width="10em" class="input-group-addon" for="deposit-comments"><div style="width: 10em;">Comments:</div></span>  
                    <input id="deposit-comments" name="deposit-comments" type="text" class="form-control input-md">
                </div>

                <div class="input-group ">
                    <span class="input-group-addon" for="deposit-misc-fileupload-file"><div style="width: 10em;">Upload Image:</div></span>
                    <input id="deposit-misc-fileupload-file" name="deposit-misc-fileupload-file" type="file" class="form-control">
                </div>

                <div id="deposit-misc-fileupload-preview-container" style="height: 0px; width: 100%;">
                    <embed style="height: 420px; width: 100%;" id="deposit-misc-fileupload-preview" ></embed>
                </div>

                 
            </div>
            <div class="modal-footer">
                <button id="deposit-misc-save" name="save" type="button" class="btn OS-Button">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#AddMiscDepositModel').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal

        var quoteid = button.data('quoteid'); // Extract info from data-* attributes
        var clientid = button.data('clientid');
        
        var modal = $(this);
        modal.find('.modal-body input[name*="payment-quoteid"]').val(quoteid);
        modal.find('.modal-body input[name*="payment-clientid"]').val(clientid);
    });

    function BuildSplitArray($total, $catagorys) {
        $array = {};

        $runningtotal = parseFloat(0);
        $catagorys.each(function (index, element) {
            $array[$(this).text()] = $(this).val();
            $runningtotal = parseFloat($runningtotal) + parseFloat($(this).val());
        });

        $total = parseFloat($total).toFixed(2);
        $runningtotal = parseFloat($runningtotal).toFixed(2);

        if (parseFloat($total) === parseFloat($runningtotal)) {
            return $array;
        } else {
            return "error";
        }
    }

    $('#deposit-date').datepicker({
        changeMonth: true,
        changeYear: true,
        inline: true,
        dateFormat: "yy-mm-dd",
    });

    $("#misc-deposit-type").change(function()
    {
        $('#misc-deposit-catagorys').find('option').remove();
        $('#misc-deposit-SplitAmountModalButton').data('type', this.value);

        if(this.value === 'income'){
            $('#deposit-method').prop('disabled', false);
        }
        if(this.value === 'equity'){
            $('#deposit-method').prop('disabled', 'disabled');
        }
    });

    $("#deposit-misc-save").click(function()
    {
        $amount = $('#deposit-amount').val().replace(/[^\d.-]/g, '');
        $depositdata = {};
        $depositdata['_token'] = "{{ csrf_token() }}";
        $depositdata['id'] = '0';
        $depositdata['date'] = $('#deposit-date').val();
        $depositdata['amount'] = $amount;
        $depositdata['method'] = $('#deposit-method').val();
        $depositdata['comments'] = $('#deposit-comments').val();
        $depositdata['name'] = "Equity Deposit";

        $depositdata['catagorys'] = BuildSplitArray($depositdata['amount'], $('#misc-deposit-catagorys option'));

        if ($depositdata['catagorys'] === "error") {
            $('#misc-deposit-SplitAmountModalButton').click();
        } else {
            @if(Auth::user()->hasPermissionMulti('multi_assets_permission', 3))
            if($("#misc-deposit-type").val() === "income"){
                $depositdata['type'] = "";
                $depositdata['file'] = $('#deposit-misc-fileupload-preview').attr('src');
                PostMiscDeposit($depositdata);
            }
            if($("#misc-deposit-type").val() === "equity"){
                $depositdata['type'] = "e";
                $depositdata['journal'] = "1";
                //$depositdata['file_id'] = $("#file-id").val();
                PostEquity($depositdata);
            }
            @else
                $depositdata['type'] = "";
                $depositdata['file'] = $('#deposit-misc-fileupload-preview').attr('src');
                PostMiscDeposit($depositdata);
            @endif
        }
    });


    $("#deposit-misc-fileupload-file").change(function()
    {
        if (this.files[0].name != "") {
            $('#deposit-misc-fileupload-preview').remove();
            $('#deposit-misc-fileupload-preview-container').css('height', "420px");
            $('#deposit-misc-fileupload-preview-container').append('<embed style="height: 420px; width: 100%;" id="deposit-misc-fileupload-preview" ></embed>');
            DepositMiscReadURL(this);
        }
    });
});

    var srcContent;
    function DepositMiscReadURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                srcContent=  e.target.result;
                $('#deposit-misc-fileupload-preview').attr('src', srcContent);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function PostEquity($depositdata){

        $("body").addClass("loading");
        posting = $.post("/AssetLiability/Save", $depositdata);

        posting.done(function (data) {
            console.log(data);
            $("body").removeClass("loading");
            switch(data['status']) {
                case "OK":
                    $('#AddMiscDepositModel').modal('hide');
                    console.log(data);
                    $.confirm({
                        autoClose: 'Close|2000',
                        title: 'Success!',
                        content: 'Data Saved',
                        buttons: {
                            Close: function () {

                            }
                        }
                    });
                    break;
                case "monthend":
                    $.dialog({
                        title: 'Oops...',
                        content: 'Cannot create with given date as a month end has already been actioned after that date.'
                    });
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

        posting.fail(function (xhr, status, error) {

            debugger;
            $("body").removeClass("loading");
            $.dialog({
                title: 'Error!',
                content: "Failed to contact server | " + error + " | " + status
            });
        });

    }

    function PostMiscDeposit($depositdata) {

        $("body").addClass("loading");
        posting = $.post("/Deposit/Add/Misc", $depositdata);

        posting.done(function( data ) {
            $("body").removeClass("loading");
            switch(data['status']) {
                case "OK":
                    $('#AddMiscDepositModel').modal('hide');
                    location.reload();
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

        posting.fail(function() {
            $("body").removeClass("loading");
            bootstrap_alert.warning('Failed to post data', 'danger', 4000);
        });
    }



</script>

@endif