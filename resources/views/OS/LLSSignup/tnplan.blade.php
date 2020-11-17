<div class="input-group">
    <span width="10em" class="input-group-addon" for="plan-name"><div style="width: 10em;">Name:</div></span>
    <input id="plan-name" name="plan-name" type="text" class="form-control input-md planinput">
</div>

<div class="input-group">
    <span width="10em" class="input-group-addon" for="plan-description"><div style="width: 10em;">Description:</div></span>
    <input id="plan-description" name="plan-description" type="text" class="form-control input-md planinput">
</div>

<div class="input-group">
    <span width="10em" class="input-group-addon" for="plan-numusers"><div style="width: 10em;">Num Users:</div></span>
    <input id="plan-numusers" name="plan-numusers" type="text" class="form-control input-md planinput">
</div>

<div class="input-group">
    <span width="10em" class="input-group-addon" for="plan-cost"><div style="width: 10em;">Cost:</div></span>
    <input id="plan-cost" name="plan-cost" type="text" class="form-control input-md planinput">
</div>

<div class="input-group">
    <span width="10em" class="input-group-addon" for="plan-tn_plan_name"><div style="width: 10em;">TN Plan Name:</div></span>
    <input id="plan-tn_plan_name" name="plan-tn_plan_name" type="text" class="form-control input-md" readonly>
</div>

<button id="plan-submit" class="OS-Button btn" style="width: 100%;">Submit</button>

<script>
    $(document).ready(function() {
        $(".planinput").focusout(function(e) {
            $plandata = {};
            $plandata['plan-name'] = $('#plan-name').val();
            $plandata['plan-description'] = $('#plan-description').val();
            $plandata['plan-numusers'] = $('#plan-numusers').val();
            $plandata['plan-cost'] = $('#plan-cost').val();

            $tnplanname = $plandata['plan-numusers'] + "user" + $plandata['plan-cost'] +  moment().format('MMMYYYY')

            $('#plan-tn_plan_name').val($tnplanname);
        });

        $('#plan-submit').click(function () {


            $("body").addClass("loading");
            ResetServerValidationErrors();

            $plandata = {};
            $plandata['_token'] = "{{ csrf_token() }}";

            $plandata['name'] = $('#plan-name').val();
            $plandata['description'] = $('#plan-description').val();
            $plandata['numusers'] = $('#plan-numusers').val();
            $plandata['cost'] = $('#plan-cost').val();
            $plandata['tn_plan_name'] = $('#plan-tn_plan_name').val();

            $post = $.post("/Promotions/Add", $plandata);

            $post.done(function (data) {
                $("body").removeClass("loading");
                switch(data['status']) {
                    case "OK":
                        location.reload();
                        break;
                    case "plannameallreadyexists":
                        $.dialog({
                            title: 'Oops...',
                            content: 'Plan name already exists in TN.'
                        });
                    case "unknowntnresponce":
                        $.dialog({
                            title: 'Unknown TN Response',
                            content: data['responce']
                        });
                        break;
                    case "validation":
                        ServerValidationErrors(data['errors']);
                        break;
                    default:
                        console.log(data);
                        $.dialog({
                            title: 'Oops...',
                            content: 'Unknown Response from server. Please refresh the page and try again.'
                        });
                }
            });

            $post.fail(function () {
                NoReplyFromServer();
            });

        });

    });
</script>