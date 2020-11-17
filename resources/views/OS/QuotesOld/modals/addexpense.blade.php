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

<div class="modal fade" id="ExpenseModal">
    <div class="modal-dialog modal-lg" role="document" data-backdrop="">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Add Expense</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="input-group ">   
                            <span class="input-group-addon" for="expense-search"><div style="width: 7em;">Search:</div></span>
                            <input id="expense-search" name="expense-search" type="text" placeholder="" value="" class="form-control" data-validation-label="Search" data-validation-required="false" data-validation-type="">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <button style="width: 100%;" id="addexpense" type="button" class="btn OS-Button">Select a Expense</button>
                    </div>    

                    <div class="col-md-4">
                        <div class="input-group ">   
                            <span class="input-group-addon" for="expense-length"><div style="width: 7em;">Show:</div></span>
                            <select id="expense-length" name="expense-length" type="text" placeholder="choice" class="form-control">
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
                        {!! PageElement::TableControl('expense') !!}
                    </div>
                </div>                
                
                
                <table id="expense-table" class="table">
                    <thead>
                        <tr id="head">
                            <th>id</th>
                            <th>amount</th>
                            <th>Description</th>
                            <th>Catagorys</th>  
                            <th>Date</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>id</th>
                            <th>amount</th>
                            <th>Description</th>
                            <th>Catagorys</th>  
                            <th>Date</th>
                            <th>Amount</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach($expenses as $expense)
                        <tr>
                            <td>{{ $expense->id }}</td>
                            <td>{{ round($expense->amount, 2) }}</td>
                            <td>{{ $expense->description }}</td>
                            <td>{{ $expense->CatagoryString() }}</td>
                            <td>{{ $expense->DateString() }}</td>
                            <td>${{ number_format($expense->amount, 2) }}</td>
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
    var expensetable = $('#expense-table').DataTable({
        "columnDefs": [
            { "targets": [0,1],"visible": false}
        ]
    });

    $('#expense-search').on( 'keyup change', function (e) {
        expensetable.search( this.value )
                .draw();
        
        UpdatePageinate(expensetable.page.info());
        
        if (e.which == 13){

            $row = expensetable.row( {search:'applied'} ).data();
            UpdateQuoute($row);
            
            $("#expense-search").val("");
            expensetable.search( '' );
            expensetable.draw();
        } 
    });

    $('#expense-length').on( 'change', function () {
        expensetable.page.len( this.value )
                .draw();
        UpdatePageinate(expensetable.page.info());
    });

    $( "#expense-previous-page" ).click(function() { 
        expensetable.page( "previous" ).draw('page');
        UpdatePageinate(expensetable.page.info());
    });
    
    $( "#expense-next-page" ).click(function() { 
        expensetable.page( "next" ).draw('page');
        UpdatePageinate(expensetable.page.info());
    });          
        
    $('#expense-table tbody').on( 'click', 'tr', function () {
        $row = $(this);
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
            $('#addproduct').html('Select a Expense');
        }
        else {
            //expensetable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            $('#addproduct').html('Add Expense');
        }
    });

    $("#addexpense").click(function(){
        $row = expensetable.rows('.selected').data();
        $.each($row, function ($index, $value){
            UpdateQuoteExpense($value);
        });
    });
    
    $("#expense-table").css("width" , "100%");      

    UpdatePageinate(expensetable.page.info());
}); 

function UpdateQuoteExpense($info){

    AddTableRow("Expense-" + $info[0], $info[2], $info[1], "1", "0");

    /*
    $(".item-row:last").after('<tr class="item-row">\n\
                            <td name="sku" class="item-name"><input class="noborder form-control input-md sku" name="sku" type="text" disabled="true" value="Expense-'+ $info[0] +'"></td>\n\
                            <td name="description" class="description"><input class="noborder form-control input-md description" name="description" type="text" value="'+ $info[2] +'"></td>\n\
                            <td name="costperunit" ><input class="noborder form-control input-md cost" name="cost" type="number" value="'+ $info[1] +'" disabled="true"></td>\n\
                            <td id="qty" name="units" ><input style="width: 100%;" class="noborder form-control input-md qty" name="qty" type="number" value="1" disabled="true"></td>\n\
                            <td name="total"><input class="noborder form-control input-md total" name="total" type="number" value="" disabled="true"></td>\n\
                            <td name="id" hidden="true"><input class="form-control input-md id" name="id" type="number" value="0" disabled="true"></td>\n\
                            <td id="tax-percent" ><input class="noborder form-control input-md tax-percent" name="tax-percent" type="number" value="0"></td>\n\
                            <td id="tax-total" ><input class="noborder form-control input-md tax-total" name="tax-total" type="number" value="0" disabled="true"></td>\n\
                            <td name="delete" style="border-right: none; border-top: none; border-bottom: none;"><button id="delete" name="delete" type="button" class="btn OS-Button btn-sm" value="0"><span class="glyphicon glyphicon-trash"></span></button></td>\n\
                        </tr>');

        if ($(".delete").length > 0) $(".delete").show();

        $lastrow = $(".item-row:last");
        update_price($lastrow);
        $lastrow.find('td[name=units]').css("width" , "10px");
    */

    update_total();
}
function UpdatePageinate(info){

    $( "#expense-previous-page" ).prop('disabled', false);
    $( "#expense-next-page" ).prop('disabled', false);
    
    $('#expense-tableInfo').html(
        'Currently showing page '+(info.page+1)+' of '+info.pages+' pages.'
    );
    
    if(info.page === 0){
        $( "#expense-previous-page" ).prop('disabled', true);
    }
    
    if((info.page+1) === info.pages){
        $( "#expense-next-page" ).prop('disabled', true);
    }
}
</script>