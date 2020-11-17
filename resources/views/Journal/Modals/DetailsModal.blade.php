<!-- Modal -->
<div class="modal fade" id="viewDetails" tabindex="-1" role="dialog" aria-labelledby="viewDetails">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Entry Details</h4>
            </div>
            <div class="modal-body">
                
                
                <div class="input-group">   
                    <span class="input-group-addon" for="date"><div style="width: 15em;">In:</div></span>
                    <input id="date" type="text" name="date" class="form-control" disabled>
                </div>
                <div class="input-group">   
                    <span class="input-group-addon" for="type"><div style="width: 15em;">Type:</div></span>
                    <input id="type" type="text" name="type" class="form-control" disabled>
                </div>
                <div class="input-group">   
                    <span class="input-group-addon" for="tofrom"><div style="width: 15em;">To/From:</div></span>
                    <input id="tofrom" type="text" name="tofrom" class="form-control" disabled>
                </div>
                <div class="input-group">   
                    <span class="input-group-addon" for="amount"><div style="width: 15em;">Amount:</div></span>
                    <input id="amount" type="text" name="amount" class="form-control" disabled>
                </div>
                
                <div id="cat-grp-div" class="input-group"> 
                    <label class="input-group-addon" for="name"><div style="width: 15em;">Category:</div></label>
                    <select multiple id="catagorys"  class="form-control input-md" >
                    </select>
                    <span style="height: 100%;" class="input-group-btn">
                        <button style="height: 82px;" id="split-amount-button" class="btn btn-default" type="button" data-toggle="modal" data-target="#SplitAmountModal" data-amount="amount" data-output="catagorys" >Select</button>
                    </span>
                </div>
                
                <input id="datachanged" style="display: none;" name="datachanged" type="text" value="" >
                
                <div class="input-group">   
                    <span class="input-group-addon" for="ssn"><div style="width: 15em;">Comment:</div></span>
                    <textarea style="resize:vertical" id="comment" name="comment" class="form-control" > </textarea>
                </div>

                <input style="display: none;" id="entryid" type="text" name="entryid" class="form-control" >
                <input style="display: none;" id="typedata" type="text" name="typedata" class="form-control" >

            </div>
            <div class="modal-footer">
                <button id="editcheck" type="button" class="btn btn-primary">Edit Check</button>
                <button id="deletecheck" type="button" class="btn btn-primary">Void Check</button>
                <button id="editdeposit" type="button" class="btn btn-primary">Edit Deposit</button>
                <button id="editexpense" type="button" class="btn btn-primary">Edit Expense</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button id="saveentry" type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<script>   
$(document).ready(function(){
    $('#viewDetails').on('hide.bs.modal', function (event) {
        if($('#datachanged').val() === "1"){
            event.preventDefault();
            event.stopImmediatePropagation();
             
            $.confirm({
                title: 'Warning!',
                content: 'You have unsaved changes, are you sure you want to close? (changes will be lost)',
                buttons: {
                    no: function () {

                    },
                    "Close without saving": function () {
                        $('#datachanged').val("0");
                        $('#viewDetails').modal('toggle');
                    }
                }
            });
            return false;
        }
    });
    
    $('#viewDetails').on('show.bs.modal', function (event) {
        $('#datachanged').val("0");
        $row = $('#journal-table').DataTable().row('.selected').data();
        console.log($row);

        $('#date').val($row[1]);
        $('#type').val($row[2]);
        $('#tofrom').val($row[3]);
        $('#amount').val($row[10]);

        $('#comment').val($row[8]);
       
        $('#entryid').val($row[7]);
        $('#typedata').val($row[9]);
        
        //if ($row[8] === "deposit"){
        //    $('#cat-grp-div').attr("display", "none");
        //    $('#cat-grp-div').children().hide();
        //}else{
        //    $('#cat-grp-div').attr("display", "table");
        //   $('#cat-grp-div').children().show();
        //}
        
        if ($row[8] === "check"){

        }else{

        }
        
        $('#catagorys')
            .find('option')
            .remove();

        $catagorys = $row[11].split(",");
        $catagorys.splice(-1,1);
        
        $catagorys.forEach(function(item) {
            $split = item.split(":");
            $('#catagorys')
                .append($("<option></option>")
                           .attr("value",$split[1])
                           .text($split[0]));
            
        });
        
        switch ($row[9]) {
            case "receipt"://
                $('#split-amount-button').data('type','expense');
                $('#editexpense').css("display", "inline");
                $('#editdeposit').css("display", "none");
                $('#deletecheck').css("display", "none");
                $('#editcheck').css("display", "none");
                break;
            case "deposit":
                $('#split-amount-button').data('type','income');
                $('#editdeposit').css("display", "inline");
                $('#editexpense').css("display", "none");
                $('#deletecheck').css("display", "none");
                $('#editcheck').css("display", "none");
                break;
            case "check":
                $('#editcheck').css("display", "inline");
                $('#deletecheck').css("display", "inline");
                $('#split-amount-button').data('type','expense');
                $('#editdeposit').css("display", "none");
                $('#editexpense').css("display", "none");
                break;
            default:
                $('#editdeposit').css("display", "none");
                $('#editexpense').css("display", "none");
                $('#deletecheck').css("display", "none");
                $('#editcheck').css("display", "none");
                break;
        }

    });
    
    $('#deletecheck').click(function(e) {
        $row = $('#journal-table').DataTable().row('.selected').data();
        $("body").addClass("loading");
        
        posting = $.post("/Checks/Delete",
        {
            _token: "{{ csrf_token() }}",
            id: $row[7]

        });

        posting.done(function( data ) {
            $("body").removeClass("loading");
            console.log(data);
            if( data  === "saved")
            {
                $('#journal-table').DataTable().row('.selected').remove().draw();
                $('#viewDetails').modal('toggle');
            }else{
                $.dialog({
                    title: 'Oops...',
                    content: data,
                });
           }
        });

        posting.fail(function() {
            $("body").removeClass("loading");
            bootstrap_alert.warning("Failed to contact server", 'danger', 4000);
        });
    });
    
    $('#saveentry').click(function(e) {
        $("body").addClass("loading");
        
        $id = $('#entryid').val();
        $typedata = $('#typedata').val();
        $comment = $('#comment').val();
        
        $catagorys = $('#catagorys option');
        $temp = $('#amount').val();
        $amount = parseFloat($temp.substr(1));
        $array = BuildSplitArray($amount, $catagorys);
        
        
        posting = $.post("/Journal/SaveComment",
        {
            _token: "{{ csrf_token() }}",
            id: $id,
            typedata: $typedata,
            comment: $comment,
            catagorys: $array
        });

        posting.done(function( data ) {
            $("body").removeClass("loading");
            //if ($.isNumeric(data)) 
            if( data  === "saved")
            {
                $row = $('#journal-table').DataTable().row('.selected').data();
                $row[7] = $comment;
                $row[11] = BuildTextFromArray($array);
                $('#journal-table').DataTable().row('.selected').data($row).draw();
                
                $('#datachanged').val("0");
                $('#viewDetails').modal('toggle');
            }else{
                alert("error");
           }
        });

        posting.fail(function() {
            $("body").removeClass("loading");
            bootstrap_alert.warning("Failed to contact server", 'danger', 4000);
        });
    });

    $('#editcheck').click(function(e) {
        $identry = $('#entryid').val();

        GoToPage("/Checks/Edit/" + $identry);
    });

    $('#editdeposit').click(function(e) {
        $identry = $('#entryid').val();

        GoToPage("/Deposit/Edit/" + $identry);
    });

    $('#editexpense').click(function(e) {
        $identry = $('#entryid').val();

        GoToPage("/Reciepts/Edit/" + $identry);
    });
});

function BuildTextFromArray($array) {
    $text = "";
    for (var key in $array) {
        $text = $text + key + ":" + $array[key] + ",";
    }
    return $text;
}

function BuildSplitArray($total, $catagorys) {

    $array = {};
    
    $runningtotal = parseFloat(0);
    $catagorys.each( function( index, element ){
        $array[$(this).text()] = $(this).val();
        $runningtotal = parseFloat($runningtotal) + parseFloat($(this).val());
    });

    $total = parseFloat($total).toFixed(2);
    if(parseFloat($total) === parseFloat($runningtotal)){
        return $array;
    }else{
        return "error";
    }
}
</script>   