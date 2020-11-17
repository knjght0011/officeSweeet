

    <div class="modal fade" id="new-entry-modal" tabindex="-1" role="dialog" aria-labelledby="new-entry-modal"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="">New Asset\Liability</h4>
                </div>
                <div class="modal-body">
                    <div class="input-group ">
                        <label class="input-group-addon" for="type">
                            <div style="width: 164px;">Type:</div>
                        </label>
                        <select id="new-type" class="form-control input-md">
                            <option value="a">Asset</option>
                            <option value="l">Liability</option>
                            <option value="e">Equity</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <span width="10em" class="input-group-addon" for="new-name"><div
                                    style="width: 164px;">Name/Description:</div></span>
                        <input id="new-name" name="new-name" type="text" class="form-control input-md" required="">
                    </div>

                    <div class="input-group">
                        <span class="input-group-addon" for="new-date"><div style="width: 164px;">Date Purchased/Acquired:</div></span>
                        <input style="z-index: 100000;" id="new-date" name="new-date" class="form-control input-md" value="{{ date('Y-m-d') }}" readonly>
                    </div>

                    <div class="input-group">
                        <span width="10em" class="input-group-addon" for="new-amount"><div
                                    style="width: 164px;">Amount/Value: $</div></span>
                        <input id="new-amount" name="new-amount" type="number" class="form-control input-md" required=""
                               value="0.00">
                    </div>

                    <div class="input-group ">
                        <label class="input-group-addon" for="catagorys">
                            <div style="width: 164px;">Categories:</div>
                        </label>
                        <select multiple id="new-catagorys" class="form-control input-md">
                        </select>
                        <span style="height: 100%; padding: 0px;" class="input-group-btn">
                            <button style="height: 82px;" id="new-SplitAmountModalButton" class="btn btn-default" type="button"
                                    data-toggle="modal" data-target="#SplitAmountModal" data-amount="new-amount" data-output="new-catagorys"
                                    data-type="asset">Select</button>
                        </span>
                    </div>

                    <div class="input-group">
                        <span width="10em" class="input-group-addon" for="new-comments"><div style="width: 164px;">Comments/Notes:</div></span>
                        <textarea style="resize: none" id="new-comments" name="new-comments" type="text"
                                  class="form-control input-md" rows="4"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="new-entry-save" name="new-entry-save" type="button" class="btn OS-Button" value="">
                        Save
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#new-type").change(function()
            {
                $('#new-catagorys').find('option').remove();
                if(this.value === "a"){
                    $('#new-SplitAmountModalButton').data('type', 'asset');
                }else{
                    $('#new-SplitAmountModalButton').data('type', 'liability');
                }

            });

            $('#new-entry-save').click(function(){
                if ($('#new-catagorys option').length === 0) {
                    $('#new-SplitAmountModalButton').click();
                } else {
                    $data = {};
                    $data['amount'] = $("#new-amount ").val();
                    $data['catagorys'] = BuildSplitArray($data['amount'], $('#new-catagorys option'));

                    if ($array === "error") {
                        $('#new-SplitAmountModalButton').click();
                    } else {

                        $data['_token'] = "{{ csrf_token() }}";
                        $data['id'] = 0;
                        $data['name'] = $("#new-name").val();
                        $data['date'] = $("#new-date").val();
                        $data['comments'] = $("#new-comments").val();
                        $data['type'] = $("#new-type").val();
                        $data['file_id'] = "";

                        SaveExpense($data, null, null, true);
                    }
                }
            });
            
            $('#new-date').datepicker({
                changeMonth: true,
                changeYear: true,
                inline: true,
                onSelect: function (dateText, inst) {
                    var d = new Date(dateText);
                    $("#new-date").val(d.getFullYear() + '-' + ("0" + (d.getMonth() + 1)).slice(-2) + '-' + d.getDate());

                }
            });
        });
    </script>

