<style>
.dataTables_filter {
    display: none; 
}
.dataTables_length {
    display: none; 
}
.dataTables_info {
    display: none; 
}
.dataTables_paginate {
    display: none; 
}
</style>

<div class="modal fade" id="HoursModal">
    <div class="modal-dialog modal-lg" role="document" data-backdrop="">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Add Billable Hours</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="input-group ">   
                            <span class="input-group-addon" for="hours-search"><div style="width: 7em;">Search:</div></span>
                            <input id="hours-search" name="hours-search" type="text" placeholder="" value="" class="form-control" data-validation-label="Search" data-validation-required="false" data-validation-type="">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <button style="width: 100%;" id="addhours" type="button" class="btn OS-Button">Select Billable Hours</button>
                    </div>    

                    <div class="col-md-4">
                        <div class="input-group ">   
                            <span class="input-group-addon" for="hours-length"><div style="width: 7em;">Show:</div></span>
                            <select id="hours-length" name="hours-length" type="text" placeholder="choice" class="form-control">
                                <option value="10">10 entries</option>
                                <option value="25">25 entries</option>
                                <option value="50">50 entries</option>
                                <option value="100">100 entries</option>
                            </select>
                        </div>
                    </div>
                </div>                

                <div class="row" style="margin-top: 15px;">
                    <div class="col-md-12">
                        {!! PageElement::TableControl('hours') !!}
                    </div>
                </div>                
                
                
                <table id="hours-table" class="table">
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>rate</th>
                            <th>User</th>
                            <th>Rate</th>
                            <th>Hours</th>
                            <th>Total</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>id</th>
                            <th>rate</th>
                            <th>User</th>
                            <th>Rate</th>
                            <th>Hours</th>
                            <th>Total</th>
                            <th>Date</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach($hours as $hour)
                            <tr>
                                <td>{{ $hour->id }}</td>
                                <td>{{ number_format($hour->rate , 2, '.', '') }}</td>
                                <td>{{ $hour->user->getName() }}</td>
                                <td>${{ number_format($hour->rate , 2, '.', '') }}</td>
                                <td>{{ floatval($hour->hours) }}</td>
                                <td>${{ number_format($hour->Total() , 2, '.', '') }}</td>
                                <td>{{ $hour->created_at }}</td>
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

    // DataTable
    var hourstable = $('#hours-table').DataTable({
        "columnDefs": [
            { "targets": [0, 1],"visible": false}
        ],
        "language": {
            "emptyTable": "No Data"
        },
        "order": [[ 0, "desc" ]]
    });

    $('#hours-search').on( 'keyup change', function (e) {
        hourstable.search( this.value )
                .draw();
        
        UpdatePageinateHours(hourstable.page.info());
        
        if (e.which == 13){

            $row = hourstable.row( {search:'applied'} ).data();
            UpdateQuoute($row);
            
            $("#hours-search").val("");
            hourstable.search( '' );
            hourstable.draw();
        } 
    });

    $('#hours-length').on( 'change', function () {
        hourstable.page.len( this.value )
                .draw();
        UpdatePageinateHours(hourstable.page.info());
    });

    $( "#hours-previous-page" ).click(function() {
        hourstable.page( "previous" ).draw('page');
        UpdatePageinateHours(hourstable.page.info());
    });
    
    $( "#hours-next-page" ).click(function() {
        hourstable.page( "next" ).draw('page');
        UpdatePageinateHours(hourstable.page.info());
    });          
        
    $('#hours-table tbody').on( 'click', 'tr', function () {
        $row = $(this);
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
            $('#addhours').html('Select Billable Hours');
        }
        else {
            //hourstable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            $('#addhours').html('Add Billable Hours');
        }
    });

    $("#addhours").click(function(){
        $row = hourstable.rows('.selected').data();
        $.each($row, function ($index, $value){
            UpdateQuoteHours($value);
        });
    });
    
    $("#hours-table").css("width" , "100%");

    UpdatePageinateHours(hourstable.page.info());
}); 

function UpdateQuoteHours($info){

    $description = $info[4] + " Hours worked @$" + $info[1] + " per hour by " + $info[2];

    AddTableRow("Hours-" + $info[0], $description, $info[1], $info[4], "0");

    update_total();
}
function UpdatePageinateHours(info){

    $( "#hours-previous-page" ).prop('disabled', false);
    $( "#hours-next-page" ).prop('disabled', false);
    
    $('#hours-tableInfo').html(
        'Currently showing page '+(info.page+1)+' of '+info.pages+' pages.'
    );
    
    if(info.page === 0){
        $( "#hours-previous-page" ).prop('disabled', true);
    }
    
    if((info.page+1) === info.pages){
        $( "#hours-next-page" ).prop('disabled', true);
    }
}
</script>