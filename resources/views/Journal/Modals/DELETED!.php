  <style>
  #custom-handle {
    width: 3em;
    height: 1.6em;
    top: 50%;
    margin-top: -.8em;
    text-align: center;
    line-height: 1.6em;
  }
  </style>

<div class="modal fade bs-example-modal-lg" id="SplitAmountModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Select Expense/Income Category</h4>
                
            </div>
            <div id="SplitAmountBody" class="modal-body">
                <div>Test</div>
                <div>Test2</div>
                <div>Test3</div>
            </div>
            <div class="modal-footer">
                <label id="split-unassigned">Currently unasigned: $0 </label>
                <button id="add-category" name="save" type="button" class="btn OS-Button" >Add/Split</button>
                <button id="split-save" name="save" type="button" class="btn OS-Button" disabled>Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>        
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->    

<script>
$(document).ready(function() {
    $('#SplitAmountModal').on('show.bs.modal', function (event) {
        
        var children = $( "#SplitAmountBody" ).children();
        children.each( function( index, element ){

                element.remove();
            
        });

        $temp = $('#amount').val();

        $number = $temp.substr(1).replace(',', '');
        $amount = parseFloat($number);
        $catagorys = $('#catagorys option');
       
        $catagorys.each(function(item) {

            $value = $(this).val();
            $catagry = $(this).text();

            if($('#typedata').val() === "deposit"){
                $element = ElementDeposit($amount, $value);
            }else{
                $element = ElementExpense($amount, $value);
            }


            $( "#SplitAmountBody" ).append( $element );

            $select = $("#SplitAmountBody").find( ".splitselect" ).last();
            $slider = $("#SplitAmountBody").find( ".slidertest" ).last();
            $input = $("#SplitAmountBody").find( ".sliderinput" ).last();
            $button = $("#SplitAmountBody").find( ".deletesplit" ).last();

            SetupSelect($select);
            SetupInput($input, $amount);
            SetupSlider($slider, $amount, $value);
            SetupButton($button);

            $select.val($catagry);

        });
        
        
        $element = "<div class='row'>\n\
                <div class='col-md-7'>\n\
                </div>\n\
                <div class='col-md-2'>\n\
                    <label for='usr'>Total:</label>\n\
                </div>\n\
                <div class='col-md-2'>\n\
                    <input class='splittotal form-control invalid' value='0.00' disabled/>\n\
                </div>\n\
            </div>";

        $( "#SplitAmountBody" ).append( $element );

        SplitTotal($amount);
    });

    $("#add-category").click(function()
    {

        $amount = $('#amountnumber-input').val();

        if($('#typedata').val() === "deposit"){
            $element = ElementDeposit($amount, 0);
        }else{
            $element = ElementExpense($amount, 0);
        }
        
        $slider = $("#SplitAmountBody").find( ".slidertest" ).last();
        $lastcontainer = $slider.parent().parent();
                            
        $( "#SplitAmountBody" ).append( $element );

        $slider = $("#SplitAmountBody").find( ".slidertest" ).last();
        $input = $("#SplitAmountBody").find( ".sliderinput" ).last();
        $button = $("#SplitAmountBody").find( ".deletesplit" ).last();
        $select = $("#SplitAmountBody").find( ".splitselect" ).last();
        
        $newcontainer = $slider.parent().parent();
        
        $lastcontainer.after($newcontainer);
        
        SetupSelect($select);
        SetupSlider($slider, $amount, 0);
        SetupInput($input, $amount);
        SetupButton($button);
        
    });

    $("#split-save").click(function()
    {
        $('#catagorys').empty();
                
        $inputs = $("#SplitAmountBody").find( ".sliderinput" );
        $inputs.each( function( index, element ){

            $parent = $(this).parent().parent();
            $select = $parent.find( ".splitselect" );
            
            $slidervalue = $(this).val();
            $slidercatagory = $select.val();
            
            if($slidercatagory ===  "0"){
                $.dialog({
                    title: 'Error!',
                    content: 'Please select at category'
                });
                $('#catagorys').empty();
                throw new Error("Validation Error");
            }
            
            $('#catagorys')
                .append($("<option></option>")
                    .attr("value",$slidervalue)
                    .text($slidercatagory));
            
        });
        $('#datachanged').val("1");
        $('#SplitAmountModal').modal('toggle');
    });  

});

function SetupButton($button) {

    $button.on( "click", function( event, ui ) {
        $numofbuttons = $("#SplitAmountBody").find( ".deletesplit" ).length;
        if($numofbuttons > 1){
            $parent = $(this).parent().parent();
            $parent.remove();
        }
        
    });
       
}

function SetupSlider($slider, $max, $value) {
    
    var handle = $( "#custom-handle" );

    $slider.slider({
        range: "min",
        min: 0,
        step: 0.01,
        max: $max,
        value: $value
    });
    $slider.on( "slide", function( event, ui ) {

        $slidervalue = $(this).slider( "option", "value" );
        $parent1 = $(this).parent();
        $parent2 = $parent1.parent();
        $child = $parent2.find( ".sliderinput" );
        $child.val($slidervalue);

        SplitTotal($amount);
        
        //handle.text( $slidervalue );
    });
    
    $slider.on( "slidechange", function( event, ui ) {

        //handle.text( $slidervalue );
    });
    
}
function SetupInput($input, $amount) {

    $input.on( "keyup", function( event, ui ) {
        $slidervalue = $(this).val();
        $parent1 = $(this).parent();
        $parent2 = $parent1.parent();
        $child = $parent2.find( ".slidertest" );
        $child.slider( "option", "value", $slidervalue );
        
        SplitTotal($amount);    
    });

    $input.on( "change", function( event, ui ) {
        $(this).val( parseFloat($(this).val()).toFixed(2));
    });
}
function SetupSelect($select) {

    $select.data("prev",$select.val());

    $select.on( "change", function( event, ui ) {
        $error = false;
        
        $inputs = $("#SplitAmountBody").find( ".splitselect" );
        $inputs.each( function( index, element ){

            $target = $select[0];
            if($(this).is($target)){

            }else{
                if($select.val() === $(this).val()){
                    $error = true;
                }
            }
        });
        
        if($error === true){
            $.dialog({
                title: 'Error!',
                content: 'You have all ready selected that category'
            });
            $(this).val($(this).data("prev"));
            
        }else{
            $(this).data("prev",$select.val());
        }
        
    });
}

function ElementDeposit($max, $value) {

    $value = "<div class='row'>\n\
                <div class='col-md-3'>\n\
                    <select class='form-control input-md splitselect' >\n\
                        <option value='0'>Choose a category</option>\n\
                        <option value='Unknown'>Unknown</option>\n\
                        @foreach($ExpenseAccountCategorysDeposit as $ExpenseAccountCategory)\n\
                             <option value='{{ $ExpenseAccountCategory->category }}'>{{ $ExpenseAccountCategory->category }}</option>\n\
                             @foreach($ExpenseAccountCategory->subcategories as $SubCategory)\n\
                                 <option value='{{ $SubCategory->subcategory }}'>&emsp;--{{ $SubCategory->subcategory }}</option>\n\
                             @endforeach\n\
                        @endforeach\n\
                    </select>\n\
                </div>\n\
                <div class='col-md-6'>\n\
                    <div style='position: absolute; left: 0px; right: 0px'> 0 </div> <div class='slidertest' style='margin-top: 15px;'> <div id='custom-handle' class='ui-slider-handle'></div> </div> <div style='position: absolute; top: 0px; right: 0px'>"+ $max +"</div>\n\
                </div>\n\
                <div class='col-md-2'>\n\
                    <input class='sliderinput form-control' value='"+ $value +"' />\n\
                </div>\n\
                <div class='col-md-1'>\n\
                    <button type='button' class='btn OS-Button btn-sm deletesplit' value='0'><span class='glyphicon glyphicon-trash'></span></button>\n\
                </div>\n\
            </div>";

    return $value;
}
function ElementExpense($max, $value) {
    
    $value = "<div class='row'>\n\
                <div class='col-md-3'>\n\
                    <select class='form-control input-md splitselect' >\n\
                        <option value='0'>Choose a category</option>\n\
                        @foreach($ExpenseAccountCategorysExpence as $ExpenseAccountCategory)\n\
                             <option value='{{ $ExpenseAccountCategory->category }}'>{{ $ExpenseAccountCategory->category }}</option>\n\
                             @foreach($ExpenseAccountCategory->subcategories as $SubCategory)\n\
                                 <option value='{{ $SubCategory->subcategory }}'>&emsp;--{{ $SubCategory->subcategory }}</option>\n\
                             @endforeach\n\
                        @endforeach\n\
                    </select>\n\
                </div>\n\
                <div class='col-md-6'>\n\
                    <div style='position: absolute; left: 0px; right: 0px'> 0 </div> <div class='slidertest' style='margin-top: 15px;'> <div id='custom-handle' class='ui-slider-handle'></div> </div> <div style='position: absolute; top: 0px; right: 0px'>"+ $max +"</div>\n\
                </div>\n\
                <div class='col-md-2'>\n\
                    <input class='sliderinput form-control' value='"+ $value +"' />\n\
                </div>\n\
                <div class='col-md-1'>\n\
                    <button type='button' class='btn OS-Button btn-sm deletesplit' value='0'><span class='glyphicon glyphicon-trash'></span></button>\n\
                </div>\n\
            </div>";
                        
    return $value;                    
}

function SplitTotal($max) {
    
    $inputs = $("#SplitAmountBody").find( ".sliderinput" );

    $total = parseFloat(0);

    $inputs.each( function( index, element ){
        $thisvalue = parseFloat($(this).val());
        if(isNaN($thisvalue)) {
            var $thisvalue = 0;
        }
        $total = $total + $thisvalue;

    });
    
    $leftover = parseFloat($max) - $total;
    
    if ($leftover > 0) {
        $("#split-unassigned").css("color" , "red");
        $("#split-unassigned").html("$" + parseFloat($leftover).toFixed(2) + " unassigned" );
    }else if($leftover < 0){
        $leftover = $leftover * -1;
        $("#split-unassigned").css("color" , "red");
        $("#split-unassigned").html("$" + parseFloat($leftover).toFixed(2) + " over");
    }else{
        $("#split-unassigned").css("color" , "black");
        $("#split-unassigned").html("Total amount assigned");
    }
    
    $totalinput = $("#SplitAmountBody").find( ".splittotal" );
    
    $totalinput.each( function( index, element ){
        
        $(this).val(parseFloat($total).toFixed(2));
        
        if($total !== parseFloat($max)){
            $(this).addClass('invalid');
            $('#split-save').prop('disabled', true);
        }else{
            $(this).removeClass('invalid');
            $('#split-save').prop('disabled', false);
        }

    });
}
</script>
