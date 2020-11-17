<style>
.dataTables_filter {
    display: none; 
}
.dataTables_length {
    display: none; 
}
</style>

<div class="modal fade bs-example-modal-lg" id="SwitchPayee" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Select New Payee</h4>
                
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="input-group ">
                            <span class="input-group-addon" for="search"><div style="width: 7em;">Search:</div></span>
                            <input id="search" name="search" type="text" placeholder="" value="" class="form-control" data-validation-label="Search" data-validation-required="false" data-validation-type="">
                        </div>
                    </div> 

                    <div class="col-md-4">
                        <div class="input-group ">   
                            <span class="input-group-addon" for="status"><div style="width: 7em;">Type:</div></span>
                            <select id="status" name="status" type="text" placeholder="choice" class="form-control">
                                <option value="all" selected>All</option>
                                <option value="Vendor">Vendors</option>                
                                <option value="Client">Clients</option>
                                <option value="Employee">Employees</option>
                            </select>
                        </div>
                    </div> 

                    <div class="col-md-4">
                        <div class="input-group ">   
                            <span class="input-group-addon" for="length"><div style="width: 7em;">Show:</div></span>
                            <select id="length" name="length" type="text" placeholder="choice" class="form-control">
                                <option value="10">10 entries</option>
                                <option value="25">25 entries</option>
                                <option value="50">50 entries</option>
                                <option value="100">100 entries</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                
                <table id="payeetable" class="table">
                    <thead id="head">
                        <tr>
                            <td>Name</td>
                            <td>Type</td>          
                            <td>ID</td>                             
                        </tr>                        
                    </thead>    
                    <tfoot>
                        <tr>
                            <td>Name</td>
                            <td>Type</td>   
                            <td>ID</td>  
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach($vendors as $vendor)
                        <tr>
                            <td>
                                {{ $vendor->GetName() }}
                            </td>
                            <td>
                                Vendor
                            </td>
                            <td>
                                {{ $vendor->id }}
                            </td>                            
                        </tr>
                        @endforeach
                        @foreach($clients as $client)
                        <tr>
                            <td>
                                {{ $client->GetName() }}
                            </td>
                            <td>
                                Client
                            </td>
                            <td>
                                {{ $client->id }}
                            </td>
                        </tr>
                        @endforeach
                        @foreach($employees as $employee)
                        <tr>
                            <td>
                                {{ $employee->GetName() }}
                            </td>
                            <td>
                                Employee
                            </td>
                            <td>
                                {{ $employee->id }}
                            </td>
                        </tr>
                        @endforeach   
                    </tbody>
                </table>
            </div>     
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal --> 

<script>
$(document).ready(function() {
    $('#SwitchPayee').on('show.bs.modal', function (event) {
        
    });
    
    $('#status').on( 'keyup change', function () {
        switch(this.value) {
            case "all":
                payeetable
                    .columns( 1 )
                    .search( "" , true)
                    .draw();
                break;
            default:
                payeetable
                    .columns( 1 )
                    .search( "^" + $(this).val() + "$", true, false, true)
                    .draw();
        }        
    });    
    
    $('#search').on( 'keyup change', function () {
        payeetable.search( this.value )
                .draw();
    });
    
    $('#length').on( 'change', function () {
        payeetable.page.len( this.value )
                .draw();
    }); 
    
    var payeetable = $('#payeetable').DataTable({
        "columnDefs": [
            { "targets": [2],"visible": false}
        ]
    });
    
    $('#payeetable tbody').on( 'click', 'tr', function () {
        $("body").addClass("loading");
        $row = $(this);
        payeetable.$('tr.selected').removeClass('selected');
        $(this).addClass('selected');
            
        $data = $('#payeetable').DataTable().row('.selected').data();
        
        GoToPage("/Checks/New/"+ $data[1].toLowerCase() +"/" + $data[2]);
    });
    
    $('#payeetable').css("width", "100%");
});
</script>