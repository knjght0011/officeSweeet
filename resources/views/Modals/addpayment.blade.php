{{--
Modal for adding payments or adjustments to invoices
required data attributes:
    data-quoteid="" 
    data-clientid=""

include with:
     @include('Modals.addpayment') 
--}}


<div class="modal fade" id="AddPaymentModel" tabindex="-1" role="dialog" aria-labelledby="AddPaymentModel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="exampleModalLabel">Add Payment or Adjustment:</h4>
            </div>
            <div class="modal-body">
                
                <input style="display: none;" type="text" class="form-control" name="payment-quoteid" id="payment-quoteid" disabled>
                <input style="display: none;" type="text" class="form-control" name="payment-clientid" id="payment-clientid" disabled>
                
                <div class="form-group">
                    <label for="pwd">Amount:</label>
                    <input type="text" class="form-control" name="payment-amount" id="payment-amount">
                </div>
                <div class="form-group">
                    <label for="pwd">Type:</label>
                    <select type="text" class="form-control" name="payment-type" id="payment-type">
                        <option value="Payment">Payment</option>
                        <option value="Adjustment">Adjustment</option>
                    </select> 
                </div>
                <div class="form-group">
                    <label for="pwd">Method:</label>
                    <select type="text" class="form-control" name="payment-method" id="payment-method">
                        <option value="Cash">Cash</option>
                        <option value="Check">Check</option>
                        <option value="Credit Card">Credit Card</option>
                        <option value="Debit Card">Debit Card</option>
                    </select>    
                </div>
                <div class="form-group">
                    <label for="pwd">Comments:</label>
                    <input type="text" class="form-control" name="payment-comments" id="payment-comments">
                </div>                
            </div>
            <div class="modal-footer">
                <button id="payment-save" name="save" type="button" class="btn OS-Button">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
        $('#AddPaymentModel').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal

        var quoteid = button.data('quoteid'); // Extract info from data-* attributes
        var clientid = button.data('clientid');
        
        var modal = $(this);
        modal.find('.modal-body input[name*="payment-quoteid"]').val(quoteid);
        modal.find('.modal-body input[name*="payment-clientid"]').val(clientid);
    });     

    $("#payment-save").click(function()
    {
        $("body").addClass("loading");
        $client_id = $('#payment-clientid').val();
        $quote_id = $('#payment-quoteid').val();
        $amount = $('#payment-amount').val();
        $type = $('#payment-type').val();
        $method = $('#payment-method').val();
        $comments = $('#payment-comments').val();
                
        posting = PostPaymentData($client_id, $quote_id, $amount, $type, $method, $comments);
            
        posting.done(function( data ) {
            $("body").removeClass("loading");
            bootstrap_alert.warning('Data Saved', 'success', 4000);
        });
        
        posting.fail(function() {
            $("body").removeClass("loading");
            bootstrap_alert.warning('Failed to post data', 'danger', 4000);
        });
        
    });
});

function PostPaymentData($client_id, $quote_id, $amount, $type, $method, $comments) {

    return $.post("/Clients/Invoice/Payment",
    {
        _token: "{{ csrf_token() }}",
        client_id: $client_id,
        quote_id: $quote_id,
        amount: $amount,
        type: $type,
        method: $method,
        comments: $comments
    });
}
</script>