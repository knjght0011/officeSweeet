<div class="modal fade" id="AddDateInputModal" tabindex="-1" role="dialog" aria-labelledby="#AddDateInputModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="exampleModalLabel">Add Date Input:</h4>
            </div>
            <div class="modal-body">
                <div class='input-group'>
                    <span class='input-group-addon' for='date-label'><div style='min-width: 7em;'>Label:</div></span>
                    <input id="date-label"  name="date-label" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button id="AddDateInput" name="AddDateInput" type="button" class="btn OS-Button" value="">Add</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$( document ).ready(function() {
    //Add input funuctions
    $( "#AddDateInput" ).click(function() {

        $label = $( "#date-label" ).val();

        $labelnospace = $label.replace(/ /g, '_');

        $valid = ValidateLabel($label);//VALIDATE LABEL

        $match = CheckExists($label);

        if($valid === true){
            if($match === false){
                $element = "<div class='element input-group'> \n\
                                <span class='input-group-addon date' for='"+$labelnospace+"'><div style='min-width: 7em;'>"+$label+"</div></span> \n\
                                <input class='form-control date'  id='"+$labelnospace+"'  name='"+$labelnospace+"' readonly>\n\
                                <span class='input-group-btn'>\n\
                                    <button class='btn btn-default datebutton' type='button' disabled>Add To Schedule</button>\n\
                                </span>\n\
                            </div>";
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
});
</script>