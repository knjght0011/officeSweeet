<div class="modal fade" id="AddSelectModal" tabindex="-1" role="dialog" aria-labelledby="#AddSelectModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="exampleModalLabel">Add Dropdown:</h4>
            </div>
            <div id="AddSelectBody" class="modal-body">
                <div class="input-group ">
                    <span class="input-group-addon" for="select-label"><div style="width: 7em;">Label:</div></span>
                    <input id="select-label" name="select-label" type="text" placeholder="" value="" class="form-control">
                </div>
                <br>
                <div class="col-md-12">
                    <label class="checkbox-inline" for="checkboxes-0">
                        <input type="checkbox" name="select-all-option" id="select-all-option" value="1">
                        All Option
                    </label>
                    <label class="checkbox-inline" for="checkboxes-1">
                        <input type="checkbox" name="select-none-option" id="select-none-option" value="2">
                        None Option
                    </label>
                </div>
                <div class='input-group'>
                    <span class='input-group-addon'><div style='width: 7em;'>Option 1:</div></span>
                    <input class='form-control' id='option1'  name='option1' />
                </div>

            </div>
            <div class="modal-footer">
                <button id="DeleteOption" name='DeleteOption' type='button' class='btn OS-Button' >Delete Last Option</button>
                <button id="AddOption" name="AddOption" type="button" class="btn OS-Button" >Add Option</button>
                <button id="AddSelect" name="AddInput" type="button" class="btn OS-Button" value="">Add/Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="EditSelectModal" tabindex="-1" role="dialog" aria-labelledby="#EditSelectModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="exampleModalLabel">Edit Dropdown:</h4>
            </div>
            <div id="EditSelectBody" class="modal-body">
                <div class="input-group ">
                    <span class="input-group-addon" for="select-label-edit"><div style="width: 7em;">Label:</div></span>
                    <input id="select-label-edit" name="select-label-edit" type="text" placeholder="" value="" class="form-control">
                </div>

                <br>
                <div class="col-md-12">
                    <label class="checkbox-inline" for="checkboxes-0">
                        <input type="checkbox" name="select-all-option-edit" id="select-all-option-edit" value="1">
                        All Option
                    </label>
                    <label class="checkbox-inline" for="checkboxes-1">
                        <input type="checkbox" name="select-none-option-edit" id="select-none-option-edit" value="2">
                        None Option
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button id="DeleteOption-Edit" name='DeleteOption' type='button' class='btn OS-Button' >Delete Last Option</button>
                <button id="AddOption-Edit" name="AddOption-Edit" type="button" class="btn OS-Button" >Add Option</button>
                <button id="EditSelect" name="EditSelect" type="button" class="btn OS-Button" value="">Add/Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$( document ).ready(function() {

    $('#EditSelectModal').on('hidden.bs.modal', function () {
        //when model closes clear all options
        $( "#EditSelectBody" ).find('input').each(function() {
            $name = $(this).attr('name');
            if($name !== "select-label-edit"){
                if($name !== "select-none-option-edit"){
                    if($name !== "select-all-option-edit"){
                        $(this).parent().remove();
                    }
                }
            }
        });

        $('#select-none-option-edit').prop('checked', false);
        $('#select-all-option-edit').prop('checked', false);
    });


    //ADD Dropdown functions
    $( "#AddOption" ).click(function() {
        $number = $( "#AddSelectBody" ).children().length - 2;
        console.log($number);

        $element = "<div class='input-group'>\n\
                        <span class='input-group-addon'><div style='width: 7em;'>Option "+$number+":</div></span>\n\
                        <input class='form-control' id='option"+$number+"'  name='option"+$number+"' />\n\
                    </div>";

        $( "#AddSelectBody" ).append( $element );
    });

    $( "#DeleteOption" ).click(function() {
        var target = $( "#AddSelectBody" ).children().last();
        if ( target.is( "div" ) ) {
            target.remove();
        }
    });

    $( "#AddSelect" ).click(function() {
        var $inputs = $( "#AddSelectBody" ).find("input");

        $options = "";

        if($("#select-none-option").is(":checked")) {
            $options = $options + "<option value='None'>None</option>";
        }

        if($("#select-all-option").is(":checked")) {
            $options = $options + "<option value='All'>All</option>";
        }

        $inputs.each( function() {
            if ($(this).attr( "type" ) !== "checkbox"){

                $value = $(this).val();

                if($(this).attr('id') === "select-label"){
                    $label = $value;
                }else{
                    $optionvalid = ValidateOption($value);
                    if($optionvalid === true){
                        $options = $options + "<option value='"+$value+"'>"+$value+"</option>";
                    }else{
                        //error
                    }
                }
            }
        });

        $valid = ValidateLabel($label);
        $match = CheckExists($label);

        if($valid === true){
            if($match === false){
                $labelnospace = $label.replace(/ /g, '_');

                $element = "<div class='element input-group'><span class='input-group-addon' for='"+$labelnospace+"'><div style='min-width: 7em;'>"+$label+"</div></span><select id='"+$labelnospace+"' name='"+$labelnospace+"' class='form-control'>";

                $element = $element + $options;
                $element = $element + "</select></div>";

                switch($col) {
                    case 1:
                        $( "#col1" ).append( $element );
                        $col = 2;
                        break;
                    case 2:
                        $( "#col2" ).append( $element );
                        $col = 3;
                        break;
                    case 3:
                        $( "#col3" ).append( $element );
                        $col = 1;
                        break;
                }

            }else{
                alert("not unique");
            }
        }else{

        }

    });


    //Edit Dropdown functions
    $( "#AddOption-Edit" ).click(function() {
        $number = $( "#EditSelectBody" ).children().length - 2;
        console.log($number);

        $element = "<div class='input-group'>\n\
                        <span class='input-group-addon'><div style='width: 7em;'>Option "+$number+":</div></span>\n\
                        <input class='form-control' id='option"+$number+"'  name='option"+$number+"' />\n\
                    </div>";

        $( "#EditSelectBody" ).append( $element );
    });

    $( "#DeleteOption-Edit" ).click(function() {
        var target = $( "#EditSelectBody" ).children().last();
        if ( target.is( "div" ) ) {
            target.remove();
        }
    });

    $( "#EditSelect" ).click(function() {


        var $inputs = $( "#EditSelectBody" ).find("input");

        $options = "";

        if($("#select-none-option-edit").is(":checked")) {
            $options = $options + "<option value='None'>None</option>";
        }

        if($("#select-all-option-edit").is(":checked")) {
            $options = $options + "<option value='All'>All</option>";
        }

        $inputs.each( function() {
            if ($(this).attr( "type" ) !== "checkbox"){

                $value = $(this).val();

                if($(this).attr('id') === "select-label-edit"){
                    $label = $value;
                }else{
                    $optionvalid = ValidateOption($value);
                    if($optionvalid === true){
                        $options = $options + "<option value='"+$value+"'>"+$value+"</option>";
                    }else{
                        //error
                    }
                }
            }
        });

        $valid = ValidateLabel($label);
        //$match = CheckExists($label);

        $match = false;

        if($valid === true){
            if($match === false){
                $labelnospace = $label.replace(/ /g, '_');

                $element = "<span class='input-group-addon' for='"+$labelnospace+"'><div style='min-width: 7em;'>"+$label+"</div></span><select id='"+$labelnospace+"' name='"+$labelnospace+"' class='form-control'>";
                $element = $element + $options;
                $element = $element + "</select>";

                window.selectedelement.html($element);


            }else{
                alert("not unique");
            }
        }else{

        }

    });


    $( "#EditDropDown" ).click(function() {
        event.preventDefault();

        $label = window.selectedelement.find('select').attr('name');

        $( "#select-label-edit" ).val($label);


        var $number = 1;
        window.selectedelement.find('option').each(function() {

            $value = $(this).val();

            switch($value) {
                case "All":
                    $('#select-all-option-edit').prop('checked', true);
                    break;
                case "None":
                    $('#select-none-option-edit').prop('checked', true);
                    break;
                default:
                    $element = "<div class='input-group'>\n\
                                    <span class='input-group-addon'><div style='width: 7em;'>Option "+$number+":</div></span>\n\
                                    <input class='form-control' id='option"+$number+"'  name='option"+$number+"'  value='"+$value+"' />\n\
                                </div>";

                    $( "#EditSelectBody" ).append( $element );

                    $number++;
            }

        });

        $('#EditSelectModal').modal('show');


    });
});
</script>