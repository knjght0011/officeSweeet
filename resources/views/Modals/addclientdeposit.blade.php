@if(Auth::user()->hasPermission('deposits_permission'))

{{--
Modal for adding misc deposits
optional data attributes:
        clientid - required if $clients not present in view, otherwise optional.

include with:
     @include('Modals.addclientdeposit') 
--}}


<div class="modal fade" id="AddClientDepositModel" tabindex="-1" role="dialog" aria-labelledby="AddPaymentModel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="exampleModalLabel">Add Client Deposit:</h4>
            </div>
            <div class="modal-body">

                @if(isset($clients))
                <div class="input-group">
                    <span width="10em" class="input-group-addon" for="deposit-client-client"><div style="width: 10em;">Client:</div></span>
                    <select type="text" class="form-control input-md" name="deposit-client-client" id="deposit-client-client">
                        @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->getName() }}</option>
                        @endforeach
                    </select>    
                </div>
                @else
                <input style="display: none;" type="text" class="form-control" name="deposit-client-client" id="deposit-client-client" disabled>
                @endif                
                
                <div class="input-group">
                    <span class="input-group-addon" for="deposit-client-date"><div style="width: 10em;">Date:</div></span>  
                    <input id="deposit-client-date" name="deposit-client-date" type="date" class="form-control input-md" value="{{ date('Y-m-d') }}">
                </div>  
                
                <div class="input-group">
                    <span width="10em" class="input-group-addon" for="deposit-client-amount"><div style="width: 10em;">Amount:</div></span>
                    <input id="deposit-client-amount" name="deposit-client-amount" type="text" class="form-control input-md" value="0.00" data-validation-label="Amount" data-validation-required="true" data-validation-type="amount">
                </div>
                
                <div class="input-group">
                    <span width="10em" class="input-group-addon" for="deposit-client-method"><div style="width: 10em;">Method:</div></span>
                    <select type="text" class="form-control input-md" name="deposit-client-method" id="deposit-client-method">
                        <option value="Cash">Cash</option>
                        <option value="Check">Check</option>
                        <option value="Credit Card">Credit Card</option>
                        <option value="Debit Card">Debit Card</option>
                        <option value="ACH/ETF & Wire">ACH/ETF & Wire</option>
                    </select>    
                </div>
                
                <div class="input-group">
                    <span width="10em" class="input-group-addon" for="deposit-client-comments"><div style="width: 10em;">Comments:</div></span>  
                    <input id="deposit-client-comments" name="deposit-client-comments" type="text" class="form-control input-md">
                </div>

                <div class="input-group ">
                    <span class="input-group-addon" for="deposit-client-fileupload-file"><div style="width: 10em;">Upload Image:</div></span>
                    <input id="deposit-client-fileupload-file" name="deposit-client-fileupload-file" type="file" class="form-control">
                </div>

                <div id="deposit-client-fileupload-preview-container" style="height: 0px; width: 100%;">
                    <embed style="height: 420px; width: 100%;" id="deposit-client-fileupload-preview" ></embed>
                </div>
                 
            </div>
            <div class="modal-footer">
                <button id="deposit-client-save" name="save" type="button" class="btn OS-Button">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#AddClientDepositModel').on('show.bs.modal', function (event) {

        var button = $(event.relatedTarget); // Button that triggered the modal

        var clientid = button.data('clientid');
        $('#deposit-client-client').val(clientid);


    });     

    $("#deposit-client-save").click(function()
    {

        $client_id = $('#deposit-client-client').val();  
        
        $depositdate = new Date($('#deposit-client-date').val());
        $date = moment($depositdate).format('YYYY-MM-DD');
        
        $amount = ValidateInput($('#deposit-client-amount'));
        $type = "";
        $method = $('#deposit-client-method').val();
        $comments = $('#deposit-client-comments').val();
        $file = $('#deposit-client-fileupload-preview').attr('src');
        
        $("body").addClass("loading");
        ResetServerValidationErrors();

        posting = PostClientDepost($client_id, $date, $amount, $type, $method, $comments, $file);
            
        posting.done(function( data ) {
            console.log(data);
            $("body").removeClass("loading");
            
            if ($.isNumeric(data)) 
            {
                location.reload();
            }else{
                //server validation errors
                $("body").removeClass("loading");
                ServerValidationErrors(data);
            }
        });
        
        posting.fail(function() {
            $("body").removeClass("loading");
            bootstrap_alert.warning('Failed to post data', 'danger', 4000);
        });
        
    });

    $("#deposit-client-fileupload-file").change(function()
    {
        if (this.files[0].name != "") {
            $('#deposit-client-fileupload-preview').remove();
            $('#deposit-client-fileupload-preview-container').css('height', "420px");
            $('#deposit-client-fileupload-preview-container').append('<embed style="height: 420px; width: 100%;" id="deposit-client-fileupload-preview" ></embed>');
            DepositClientReadURL(this);
        }
    });
});

var srcContent;
function DepositClientReadURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            srcContent=  e.target.result;
            $('#deposit-client-fileupload-preview').attr('src', srcContent);
        };
        reader.readAsDataURL(input.files[0]);
    }
}
function PostClientDepost($client_id, $date, $amount, $type, $method, $comments, $file) {


    return $.post("/Deposit/Add/Client",
    {
        _token: "{{ csrf_token() }}",
        client_id: $client_id,
        date: $date,
        amount: $amount,
        type: $type,
        method: $method,
        comments: $comments,
        file: $file
    });
}
</script>

@endif