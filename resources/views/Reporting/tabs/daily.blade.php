List of Daily Reports here.

<label for="userid">Start:</label><input id="start" type="date" name="start" class="form-control" value="{{ date('Y-m-d') }}">
<label for="userid">End:</label><input id="end" type="date" name="end" class="form-control" value="<?php echo (new DateTime('+1 day'))->format('Y-m-d'); ?>">

<button type="button" class="btn OS-Button" data-toggle="modal" data-target="#ShowPdfModel" data-report="PaymentsAndAdjustments" data-option="all">Payments And Adjustments Report(P&A)</button>
<button type="button" class="btn OS-Button" data-toggle="modal" data-target="#ShowPdfModel" data-report="PaymentsAndAdjustments" data-option="onlycashandcheque">P&A(only cash and cheque)</button>
<button type="button" class="btn OS-Button" data-toggle="modal" data-target="#ShowPdfModel" data-report="PaymentsAndAdjustments" data-option="nocashandcheque">P&A(no cash and cheque)</button>
<br>
<button type="button" class="btn OS-Button" data-toggle="modal" data-target="#ShowPdfModel" data-report="Timesheets" data-option="all">Timesheets</button>
<button type="button" class="btn OS-Button" data-toggle="modal" data-target="#ShowPdfModel" data-report="Timesheets" data-option="6">Timesheets for Sam</button>
<br>
<button type="button" class="btn OS-Button" data-toggle="modal" data-target="#ShowPdfModel" data-report="Invoices" data-option="all">All Invoices</button>
<button type="button" class="btn OS-Button" data-toggle="modal" data-target="#ShowPdfModel" data-report="Invoices" data-option="6">Invoices by user</button>
<br>
<button type="button" class="btn OS-Button" data-toggle="modal" data-target="#ShowPdfModel" data-report="Ageing" data-option="all">Ageing Debt</button>

<div class="modal fade" id="ShowPdfModel" tabindex="-1" role="dialog" aria-labelledby="ShowPdfModel" aria-hidden="true">
    <div style="margin: 2.5vh auto; width: 95vw" class="modal-dialog" role="document">
        <div style="height: 95vh; width: 95vw;" class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="ShowPdfModel">View Report</h4>
            </div>
            <div style="height: calc(95vh - 120px);" class="modal-body">
                <iframe style="width: 100%; height: 100%;"id="ShowPdfFrame" src="{{ url('images/loading4.gif') }}"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$('#ShowPdfModel').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var report = button.data('report'); // Extract info from data-* attributes
    var option = button.data('option'); // Extract info from data-* attributes
    
    var $start = $('#start').val();
    var $end = $('#end').val();
    
    var url = "/Reporting/" + report + "/" + $start + "/" + $end + "/" + option;
    
    console.log(url);
    
    $('#ShowPdfFrame').attr("src", url);  
}); 

$('#ShowPdfModel').on('hide.bs.modal', function (event) {
    $('#ShowPdfFrame').attr("src", "{{ url('images/loading4.gif') }}");
}); 
</script>