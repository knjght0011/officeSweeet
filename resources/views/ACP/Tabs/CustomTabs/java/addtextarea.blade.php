
<div class="modal fade" id="AddTextAreaModal" tabindex="-1" role="dialog" aria-labelledby="#AddInputModal" aria-hidden="true">
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
                    <input id="textarea-label"  name="textarea-label" class="form-control">
                </div>

                <div class='input-group'>
                    <span class='input-group-addon' for='input-label'><div style='min-width: 7em;'>Height:</div></span>
                    <select id="textarea-height" name="textarea-height" class="form-control">
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                    </select>
                </div>

                <div class='input-group'>
                    <span class='input-group-addon' for='input-label'><div style='min-width: 7em;'>Example:</div></span>
                    <textarea class="form-control" rows="2" id="textarea-example"  style="resize: none;" disabled></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button id="AddTextArea" name="AddInput" type="button" class="btn OS-Button" value="">Add</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$( document ).ready(function() {
    $( "#textarea-height" ).change(function() {

        $( "#textarea-example" ).prop('rows', $( "#textarea-height" ).val());

    });

    $( "#AddTextArea" ).click(function() {

        $label = $( "#textarea-label" ).val();

        $labelnospace = $label.replace(/ /g, '_');

        $rows = $( "#textarea-height" ).val();

        $valid = ValidateLabel($label);//VALIDATE LABEL

        $match = CheckExists($label);

        if($valid === true){
            if($match === false){

                $element = "<div class='element input-group'> \n\
                                <span class='input-group-addon' for='"+$labelnospace+"'><div style='min-width: 7em;'>"+$label+"</div></span> \n\
                                <textarea style='resize: none' class='form-control' id='"+$labelnospace+"'  name='"+$labelnospace+"'  rows='"+ $rows +"' readonly> </textarea>\n\
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