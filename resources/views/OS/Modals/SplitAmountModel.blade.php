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
                <h4 class="modal-title">Select One or More Categories</h4>
                
            </div>
            <div class="modal-body">
                <div style="width: 100%;" id="SplitAmountBody"></div>
                <div class='row'>
                    <div class='col-md-7'></div>
                    <div class='col-md-2'><label for='usr'>Total:</label></div>
                    <div class='col-md-2'><input class='splittotal form-control invalid' value='0.00' disabled/></div>
                </div>
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

        var button = $(event.relatedTarget); // Button that triggered the modal
        var amount = button.data('amount');

        $('#SplitAmountModal').data('type',  button.data('type'));
        $('#SplitAmountModal').data('output',  button.data('output'));

        $temp = $('#'+amount).val();
        $number = $temp.replace(',', '');
        $number = $number.replace('$', '');
        $amount = parseFloat($number);

        $('#'+amount).removeClass('invalid');
        if(isNaN($amount)){
            SavedSuccess($content = 'please enter amount', $title = "Oops!", "2000");
            $('#'+amount).addClass('invalid');
            $('#SplitAmountModal').modal('toggle');
        }else{
            $('#SplitAmountModal').data('amount',  $amount);

            $catagorys = $('#catagorys option');

            $catagorys.each(function(item) {

                $value = $(this).val();
                $catagry = $(this).text();

                $element = GetSelectHTML($('#SplitAmountModal').data('type'), $amount, $value);

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

            if($catagorys.length === 0){
                $amount = $('#SplitAmountModal').data('amount');

                $element = GetSelectHTML($('#SplitAmountModal').data('type'), $amount, $amount);

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
                SetupSlider($slider, $amount, $amount);
                SetupInput($input, $amount);
                SetupButton($button);
            }else{
                $('.splitselect').each(function ($key, $value) {
                    var hasOption = $('option[value="' + $catagorys[$key].text + '"]', this);
                    if (hasOption.length == 0) {
                        //alert('No such option');
                        $(this).append('<option value="' + $catagorys[$key].text + '">' + $catagorys[$key].text + '</option>');
                        $(this).val($catagorys[$key].text);
                    }
                });
            }

            SplitTotal($amount);
        }
    });

    $("#add-category").click(function()
    {

        $amount = $('#SplitAmountModal').data('amount');

        $element = GetSelectHTML($('#SplitAmountModal').data('type'), $amount, 0);
        
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

        $outputelement = $('#SplitAmountModal').data('output');
        $output = $('#' + $outputelement);
        $output.empty();
                
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

            $output
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

    //Fix for inaccuracy of floating point calculation - if problem persists investigating treating all amounts as whole integers of cents
    $total = parseFloat($total.toFixed(2));
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
    

        
    $(".splittotal").val(parseFloat($total).toFixed(2));

    if($total !== parseFloat($max)){
        $( ".splittotal" ).addClass('invalid');
        $('#split-save').prop('disabled', true);
    }else{
        $( ".splittotal" ).removeClass('invalid');
        $('#split-save').prop('disabled', false);
    }

}

function GetSelectHTML($type, $max, $value){//$('#SplitAmountModal').data('type')
    switch($type) {
        case "income":
            return ElementDeposit($max, $value);
        case "expense":
            return ElementExpense($max, $value);
        case "asset":
            return ElementAsset($max, $value);
        case "liability":
            return ElementLiability($max, $value);
        case "equity":
            return ElementEquity($max, $value);
    }
}


function ElementDeposit($max, $value) {

    $value = "<div class='row'>\n\
                <div class='col-md-3'>\n\
                    <select class='form-control input-md splitselect' >\n\
                        <option value='0'>Choose a category</option>\n\
                        <option value='Unknown'>Unknown</option>\n\
                        @foreach(CategoryHelper::GetAllIncomes() as $incomecategory)\n\
                             <option value='{{ $incomecategory->category }}'>{{ $incomecategory->category }}</option>\n\
                             @foreach($incomecategory->subcategories as $SubCategory)\n\
                                 <option value='{{ $incomecategory->category }} - {{ $SubCategory->subcategory }}'>&emsp;--{{ $SubCategory->subcategory }}</option>\n\
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
                        @foreach(CategoryHelper::GetAllExpenses() as $expensecategory)\n\
                             <option value='{{ $expensecategory->category }}'>{{ $expensecategory->category }}</option>\n\
                             @foreach($expensecategory->subcategories as $SubCategory)\n\
                                 <option value='{{ $expensecategory->category }} - {{ $SubCategory->subcategory }}'>&emsp;--{{ $SubCategory->subcategory }}</option>\n\
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

function ElementAsset($max, $value) {

    $value = "<div class='row'>\n\
                <div class='col-md-3'>\n\
                    <select class='form-control input-md splitselect' >\n\
                        <option value='0'>Choose a category</option>\n\
                        <option value='Unknown'>Unknown</option>\n\
                        @foreach(CategoryHelper::GetAllAssets() as $assetcatagory)\n\
                             <option value='{{ $assetcatagory->category }}'>{{ $assetcatagory->category }}</option>\n\
                             @foreach($assetcatagory->subcategories as $SubCategory)\n\
                                 <option value='{{ $assetcatagory->category }} - {{ $SubCategory->subcategory }}'>&emsp;--{{ $SubCategory->subcategory }}</option>\n\
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
function ElementLiability($max, $value) {

    $value = "<div class='row'>\n\
                <div class='col-md-3'>\n\
                    <select class='form-control input-md splitselect' >\n\
                        <option value='0'>Choose a catagory</option>\n\
                        @foreach(CategoryHelper::GetAllLiabilitys() as $liabilitycatagory)\n\
                             <option value='{{ $liabilitycatagory->category }}'>{{ $liabilitycatagory->category }}</option>\n\
                             @foreach($liabilitycatagory->subcategories as $SubCategory)\n\
                                 <option value='{{ $liabilitycatagory->category }} - {{ $SubCategory->subcategory }}'>&emsp;--{{ $SubCategory->subcategory }}</option>\n\
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
function ElementEquity($max, $value) {

    $value = "<div class='row'>\n\
                <div class='col-md-3'>\n\
                    <select class='form-control input-md splitselect' >\n\
                        <option value='0'>Choose a catagory</option>\n\
                        @foreach(CategoryHelper::GetAllEquitys() as $equitycatagory)\n\
                             <option value='{{ $equitycatagory->category }}'>{{ $equitycatagory->category }}</option>\n\
                             @foreach($equitycatagory->subcategories as $SubCategory)\n\
                                 <option value='{{ $equitycatagory->category }} - {{ $SubCategory->subcategory }}'>&emsp;--{{ $SubCategory->subcategory }}</option>\n\
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

</script>
