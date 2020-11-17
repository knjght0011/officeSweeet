<div class="modal fade" id="AddInputModal" tabindex="-1" role="dialog" aria-labelledby="#AddInputModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="exampleModalLabel">Add Text Input:</h4>
            </div>
            <div class="modal-body">
                <div class='input-group'>
                    <span class='input-group-addon' for='input-label'><div style='min-width: 7em;'>Label:</div></span>
                    <input id="input-label"  name="input-label" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button id="AddInput" name="AddInput" type="button" class="btn OS-Button SendQuote" value="">Add</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$( document ).ready(function() {
    //Add input funuctions
    $( "#AddInput" ).click(function() {

        $label = $( "#input-label" ).val();

        $labelnospace = $label.replace(/ /g, '_');

        $valid = ValidateLabel($label);//VALIDATE LABEL

        $match = CheckExists($label);

        if($valid === true){
            if($match === false){
                $element = "<div class='element input-group'> \n\
                                <span class='input-group-addon' for='"+$labelnospace+"'><div style='min-width: 7em;'>"+$label+"</div></span> \n\
                                <input class='form-control' id='"+$labelnospace+"'  name='"+$labelnospace+"' readonly>\n\
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