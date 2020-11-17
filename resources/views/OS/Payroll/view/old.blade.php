<table class="table">
    <thead>
        <tr>
            <td  style="visibility: collapse; display: none; background-color: #6374AB;">
                id
            </td>
            <td>
                Start
            </td>
            <td>
                End
            </td>
            <td>

            </td>           
         </tr>
    </thead>
    <tbody>        
@foreach($completepayrolls as $completepayroll)
        <tr>
            <td  style="visibility: collapse; display: none; background-color: #6374AB;">
                {{ $completepayroll->id }}
            </td>
            <td>
                {{ $completepayroll->start->toDateString() }}
            </td>
            <td>
                {{ $completepayroll->end->toDateString() }}
            </td>
            <td>
                <button class="btn btn-default" type="button" data-toggle="modal" data-target="#ShowPdfModel" data-id="{{ $completepayroll->id }}">Show Report</button>
            </td>                
        </tr>
@endforeach
    </tbody>
</table>

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
    var $id = button.data('id'); // Extract info from data-* attributes
    
    
    ///Report/{reporttype}/{date}/{timeframe}/{option}/{output}
    var url = "/Payroll/Report/" + $id;
    
    console.log(url);
    
    $('#ShowPdfFrame').attr("src", url);  
}); 

$('#ShowPdfModel').on('hide.bs.modal', function (event) {
    $('#ShowPdfFrame').attr("src", "{{ url('images/loading4.gif') }}");
}); 
</script>