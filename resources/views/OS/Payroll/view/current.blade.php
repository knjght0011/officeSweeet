<legend>Payroll for period {{$payroll->start->toDateString()}} to
    {{$payroll->end->toDateString()}}</legend>

<div class="row">

    <div style="float:left; width: 15em;  margin-left: 20px;">
        <button class="col-md-12 btn OS-Button" id="finalise" name="Finalise" type="button">Finalize Payroll for period</button>
    </div>

</div> 

<div class="panel-group" id="current-payroll-accordion" style="padding-top: 10px;">
    @foreach($employees as $employee)
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#current-payroll-accordion" href="#{{ $employee->HashEmail() }}">{{ $employee->getName() }} <span name="{{ $employee->HashEmail() }}" style="color:{{ $payroll->PayrollForUserGlyphiconColor($employee->id) }}" class="glyphicon {{ $payroll->PayrollForUserGlyphicon($employee->id) }} check" aria-hidden="true" data-id="{{ $employee->id }}" data-name="{{ $employee->name }}"></span></a>
        </h4>
      </div>
      <div id="{{ $employee->HashEmail() }}" class="panel-collapse collapse">
        <div class="panel-body">
            <div class="row">
                <div style="float:left; width: 15em;  margin-left: 20px;">
                    Rate: {{ $employee->RateFrequencyText() }}
                </div>  
                <div style="float:left; width: 15em;  margin-left: 20px;">
                    <button class="col-md-12 btn OS-Button AddItem"  name="AddItem" type="button">Add Line Item</button>
                </div>
                <div style="float:left; width: 15em;  margin-left: 20px;">
                    <button class="col-md-12 btn OS-Button SaveEmployee"  name="SaveEmployee" type="button" data-hash="{{ $employee->HashEmail() }}" data-userid="{{ $employee->id }}">Save Employee</button>
                </div>
            </div>  
            <table class="table" name="{{ $employee->HashEmail() }}"  data-userid="{{ $employee->id }}">
                <col span="1" style="visibility: collapse; display: none; background-color: #6374AB;" />
                <thead>
                    <tr>
                        <td style="visibility: collapse; display: none; background-color: #6374AB;">
                            id
                        </td>
                        <td class="col-md-1">
                            Description
                        </td>
                        <td>
                            Comment
                        </td>
                        <td class="col-md-1">
                            Taxable
                        </td>
                        <td class="col-md-1">
                             Pay/Rate ($)
                        </td>
                        <td class="col-md-1">
                            Units
                        </td>
                        <td class="col-md-1">
                            Total($)
                        </td>
                        <td style="width: 36px;">

                        </td>  
                    </tr>
                </thead>
                <tbody>
                    
                    @if($payroll->PayrollForUser($employee->id) === false)
                    <tr class="item-row">
                        <td style="visibility: collapse; display: none; background-color: #6374AB;">
                            <input class="noborder form-control input-md id" name="id" type="text" value="0">
                        </td>
                        <td>
                            <input class="noborder form-control input-md description" name="description" type="text" value="Wage">
                        </td>
                        <td>
                            <input class="noborder form-control input-md comment" name="comment" type="text" value="">
                        </td>
                        <td>
                            <select class="noborder form-control input-md tax" name="tax">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </td>
                        <td>
                            <input class="noborder form-control input-md amount" name="amount" type="text" value="{{ $employee->PayrollAmount() }}">
                        </td>
                        <td>
                            <input class="noborder form-control input-md qty" name="qty" type="text" value="{{ $employee->PayrollQuantity($payroll->start, $payroll->end, $payroll->daysInPeriod()) }}">
                        </td>
                        <td>
                            <input class="noborder form-control input-md total" name="total" type="text" value="0" readonly="true">
                        </td>
                        <td>
                            
                        </td>  
                    </tr>
                    @else
                    @foreach($payroll->PayrollForUser($employee->id) as $row)
                    <tr class="item-row">
                        <td style="visibility: collapse; display: none; background-color: #6374AB;">
                            <input class="noborder form-control input-md id" name="id" type="text" value="{{ $row->id }}">
                        </td>
                        <td>
                            <input class="noborder form-control input-md description" name="description" type="text" value="{{ $row->description }}">
                        </td>
                        <td>
                            <input class="noborder form-control input-md comment" name="comment" type="text" value="{{ $row->comment }}">
                        </td>
                        <td>
                            <select class="noborder form-control input-md tax" name="tax" value="">
                                @if($row->taxable === 1)
                                <option value="1" selected>Yes</option>
                                <option value="0">No</option>                                
                                @else
                                <option value="1">Yes</option>
                                <option value="0" selected>No</option>
                                @endif
                            </select>
                        </td>
                        <td>
                            <input class="noborder form-control input-md amount" name="amount" type="text" value="{{ number_format($row->netpay , 2, '.', '') }}">
                        </td>
                        <td>
                            <input class="noborder form-control input-md qty" name="qty" type="text" value="{{ $row->units }}">
                        </td>
                        <td>
                            <input class="noborder form-control input-md total" name="total" type="text" value="{{ $row->total }}" readonly="true">
                        </td>
                            <td style="width: 36px; border-right: none; border-top: none; border-bottom: none;"><button id="delete" name="delete" type="button" class="btn OS-Button btn-sm" value="0"><span class="glyphicon glyphicon-trash"></span></button></td> 
                    </tr>                        
                    @endforeach
                    @endif
                    <tr>
                        <td style="visibility: collapse; display: none; background-color: #6374AB;">
                            
                        </td> 
                        <td colspan="4">
                            
                        </td>    
                        <td>
                            Total
                        </td>  
                        <td>
                            <input class="noborder form-control input-md overalltotal" name="overalltotal" type="text" value="0" readonly="true">
                        </td>
                        <td>
                            
                        </td>                        
                    </tr>

                </tbody>
            </table>
        </div>
      </div>
    </div>
    @endforeach
</div>




<script>
$(document).ready(function() {
    $('input[name=total]').each(function(){
        findrowandtotal($(this));
    });


    $("#finalise").click(function()
    {
        $checks = $('body').find('.check');
        
        $yes = {};
        $no = {};
        
        $checks.each(function(){
            if($(this).hasClass( "glyphicon-ok" )){
                $yes[$(this).data('id')] = $(this).data('name');
            }else{
                $no[$(this).data('id')] = $(this).data('name');
            }
        });
        console.log($yes);
        console.log($no);
        
        $yestext = "";
        $.each($yes, function( key, value ) {
            $yestext = $yestext + value + ", ";
        });
        
        $notext = "";
        $.each($no, function( key, value ) {
            $notext = $notext + value + ", ";
        });

        $.confirm({
            title: 'Confirm!',
            content: 'Are you sure you want to finalize payroll for the period.\nThis will include the following employees: <br>' + $yestext + "\n And exclude the following employees:\n" + $notext + "\n If this is correct click confirm. Otherwise hit 'GO BACK' and make any corrections", 
            buttons: {
                confirm: function () {

                    $("body").addClass("loading");
                    posting = $.post("Payroll/Finalise",
                    {
                        _token: "{{ csrf_token() }}",
                        id: "{{ $payroll->id }}",
                        yes: $yes
                    });

                    posting.done(function( data ) {
                        $("body").removeClass("loading");
                        console.log(data);
                        //location.reload;
                    });

                    posting.fail(function() {
                        $("body").removeClass("loading");
                        bootstrap_alert.warning("Failed to contact server", 'Oops...', 4000);
                    });
                },
                "GO BACK": function () {

                }
            }
        });
    });
    
    $(".AddItem").click(function()
    {
        $table = $(this).parent().parent().parent().find(".table");
        $table.find(".item-row:last").after('<tr class="item-row">\n\
                        <td style="visibility: collapse; display: none; background-color: #6374AB;">\n\
                            <input class="noborder form-control input-md id" name="id" type="text" value="0">\n\
                        </td>\n\
                        <td>\n\
                            <input class="noborder form-control input-md description" name="description" type="text" value="Adjustment">\n\
                        </td>\n\
                        <td>\n\
                            <input class="noborder form-control input-md comment" name="comment" type="text" value="">\n\
                        </td>\n\
                        <td>\n\
                            <select class="noborder form-control input-md tax" name="tax">\n\
                                <option value="1">Yes</option>\n\
                                <option value="0" selected>No</option>\n\
                            </select>\n\
                        </td>\n\
                        <td>\n\
                            <input class="noborder form-control input-md amount" name="amount" type="text" value="0">\n\
                        </td>\n\
                        <td>\n\
                            <input class="noborder form-control input-md qty" name="qty" type="text" value="1">\n\
                        </td>\n\
                        <td>\n\
                            <input class="noborder form-control input-md total" name="total" type="text" value="0" readonly="true">\n\
                        </td>\n\
                        <td style="width: 36px; border-right: none; border-top: none; border-bottom: none;"><button id="delete" name="delete" type="button" class="btn OS-Button btn-sm" value="0"><span class="glyphicon glyphicon-trash"></span></button></td>\n\
                    </tr>');

        $span = $("#current-payroll-accordion").find( "span[name="+ $table.attr('name') +"]" );
        TickToCross($span);
        
    });
    
    
    $(".SaveEmployee").click(function()
    {

        $span = $("#current-payroll-accordion").find( "span[name="+ $(this).data('hash') +"]" );
        $table = $("#current-payroll-accordion").find( "table[name="+ $(this).data('hash') +"]" );
        $userid = $(this).data('userid');
        
        $.confirm({
            title: 'Confirm!',
            content: 'Are you sure you want to finalise the payroll for this Employee?',
            buttons: {
                confirm: function () {
                    
                    SendData($userid, "{{ $payroll->id }}", ExtractData($table), $table);
                    console.log();
                    
                    CrossToTick($span);
                },
                "GO BACK": function () {

                }
            }
        });


    });
});

$(document).on('click', '#delete', function() { 

    $row = $(this).parents('.item-row');
    
    $table = $(this).parents('.table');
    
    $payrollid = $row.find('.id').val();

    if($payrollid === "0"){
        
        $(this).parents('.item-row').remove();
        overalltotal($table); 
        
    }else{
        $("body").addClass("loading");
        posting = $.post("Payroll/DeleteRow",
        {
            _token: "{{ csrf_token() }}",
            payrollid: $payrollid
        });

        posting.done(function( data ) {
            $("body").removeClass("loading");
            if(data === "done"){

                $row.remove();
                overalltotal($table);
            }
            if(data === "finalised"){
                $.dialog({
                    title: 'Oops...',
                    content: 'This record cannot be deleted as it is allread finalised'
                });
            }
        });

        posting.fail(function() {
            $("body").removeClass("loading");
            bootstrap_alert.warning("Failed to contact server", 'Oops...', 4000);
        });
    }
    
});

$(document).on('change', ".qty", function() { 
    findrowandtotal($(this));
    
    
    $table = $(this).parent().parent().parent().parent();
    $span = $("#current-payroll-accordion").find( "span[name="+ $table.attr('name') +"]" );
    TickToCross($span);
});

$(document).on('change', ".amount", function() {
    findrowandtotal($(this));
        
    $table = $(this).parent().parent().parent().parent();
    $span = $("#current-payroll-accordion").find( "span[name="+ $table.attr('name') +"]" );
    TickToCross($span);
});

$(document).on('change', ".description", function() {        
    $table = $(this).parent().parent().parent().parent();
    $span = $("#current-payroll-accordion").find( "span[name="+ $table.attr('name') +"]" );
    TickToCross($span);
});

$(document).on('change', ".comment", function() {        
    $table = $(this).parent().parent().parent().parent();
    $span = $("#current-payroll-accordion").find( "span[name="+ $table.attr('name') +"]" );
    TickToCross($span);
});

$(document).on('change', ".tax", function() {        
    $table = $(this).parent().parent().parent().parent();
    $span = $("#current-payroll-accordion").find( "span[name="+ $table.attr('name') +"]" );
    TickToCross($span);
});

function SendData($userid, $payrollid, $data, $table){
    console.log($userid);
    console.log($payrollid);
    console.log($data);

    $("body").addClass("loading");
    posting = $.post("Payroll/SaveEmployeePayroll",
    {
        _token: "{{ csrf_token() }}",
        userid: $userid,
        payrollid: $payrollid,
        data: $data
    });

    posting.done(function( data ) {
        $("body").removeClass("loading");
        var rows = data.split("/");
        var tablerows = $table.find(".item-row");

        for (i = 0; i < tablerows.length; i++) { 
            $(tablerows[i]).find('.id').val(rows[i]);
        }
            
    });

    posting.fail(function() {
        $("body").removeClass("loading");
        bootstrap_alert.warning("Failed to contact server", 'Oops...', 4000);
    });
}


function ExtractData($table){


    $data = [];
    
    $table.find(".item-row").each(function(){ //description comment tax amount qty total
        $row = {};
        $row['id'] = $(this).find('.id').val();
        $row['description'] = $(this).find('.description').val();
        $row['comment'] = $(this).find('.comment').val();
        $row['tax'] = $(this).find('.tax').val();
        $row['amount'] = $(this).find('.amount').val();
        $row['qty'] = $(this).find('.qty').val();
        $row['total'] = $(this).find('.total').val();
        
        $data.push($row);
    });
    
    return $data;
}

function findrowandtotal($input){
    total($input.parent().parent());
}

function total($row){

    $qty = parseFloat($row.find('.qty').val().replace(',', ''));
    $amount = parseFloat($row.find('.amount').val().replace(',', ''));
    $totalinput = $row.find('.total');
    
    $total = $qty * $amount;
    
    $totalinput.val($total.toFixed(2));
    
    overalltotal($row.parent());
}

function overalltotal($table){

    $total = 0;
    $table.find('.total').each(function(){
        $total = $total + parseFloat($(this).val().replace(',', ''));
    });
    
    $table.find('.overalltotal').val($total.toFixed(2));
    
}

function CrossToTick($span){
    $span.removeClass("glyphicon-remove");
    $span.addClass("glyphicon-ok");
    $span.css("color", "green");
}

function TickToCross($span){
    $span.addClass("glyphicon-remove");
    $span.removeClass("glyphicon-ok");
    $span.css("color", "red");
}
</script>
