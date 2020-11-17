<!--local.css-->
<style>
    .cost_sec{margin-top:30px;width:20%;float:left;text-align:center;padding:5px 10px 10px;border:solid 1px #ccc;background:#fff;transition:all 400ms ease;margin-left:-1px;position:relative}
    .cost_sec:hover{transform:scale(1.1);transition:all 400ms ease;z-index:999}
    .cost_sec h3,
    .cost_sec h5,
    .cost_sec p{margin:0;padding:10px 0;border-bottom:solid 1px #ccc}
    .cost_sec p{border:none;padding-bottom:0}
    .cost_sec h3{font-size:16px;color:#ef5952;min-height:60px}
    .cost_sec hh{font-size:17px}
    .cost_sec a{margin-top:10px;display:block;padding:7px;background:#ef5952;color:#fff}
    .cost_sec a:hover{background:#000}
    .cost_sec:nth-child(3){transform:scale(1.06);z-index:99}
    .offer_img{position:absolute;right:-4px;top:-7px}

    .sub-box{
        padding: 0px 15px 15px;
        background-color: white;
        box-shadow: 0 1px 3px rgba(34, 25, 25, 0.4);
        -moz-box-shadow: 0 1px 2px rgba(34,25,25,0.4);
        -webkit-box-shadow: 0 1px 3px rgba(34, 25, 25, 0.4);
        border-radius: 5px;
        float: left;
        text-align: center;
        font-size: x-large;
        font-weight: bold;
        margin: 5px;
    }

    .sub-selected{
        background-color: lightgrey;
    }
</style>

<h3>How many people in your office need access to OfficeSweeet? </h3>

<div style="width: 100%; display: flex; flex-direction: column;">
    @foreach($plans as $plan)
        <div class="sub-box" data-cost="{{ $plan->cost }}" data-users="{{ $plan->numusers }}" data-plan="{{ $plan->tn_plan_name }}">
            {{ $plan->name }} <br> {{ $plan->UserText() }} {{ $plan->CostText() }}
        </div>
    @endforeach
</div>
<div style="width: 100%;">
    <div style="width: 100%; text-align: center; margin-top: 10px;">
        <h3>Subscription Agreement</h3>
        <input type="checkbox" id="agree">
        <label for="agree">I Agree to the <a href="#" data-toggle="modal" data-target="#model"> Subscription Agreement and Terms and Conditions.</a></label>
    </div>

    <div class="text-center">
        <button id="sub-submit-1" type="button" value="Submit" id="submit">Submit</button>
    </div>
</div>

<div class="modal fade" id="terms-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body" style="text-align: left;">
                <h3>1. Terms</h3>
                <p>By accessing this web site, you are agreeing to be bound by these web site Terms and Conditions of Use, all applicable laws and regulations, and agree that you are responsible for compliance with any applicable local laws. If you do not agree with any of these terms, you are prohibited from using or accessing this site. The materials contained in this web site are protected by applicable copyright and trade mark law.</p>
                <h3>2. Use License</h3>
                <p>Permission is granted to temporarily download one copy of the materials (information or software) on Office Sweeet's web site for personal, non-commercial transitory viewing only. This is the grant of a license, not a transfer of title, and under this license you may not:</p>
                modify or copy the materials;
                <ul><li>use the materials for any commercial purpose, or for any public display (commercial or non-commercial);</li>
                <li>attempt to decompile or reverse engineer any software contained on Office Sweeet's web site;</li>
                <li>remove any copyright or other proprietary notations from the materials; or</li>
                <li>transfer the materials to another person or "mirror" the materials on any other server.</li></ul>
                <p>This license shall automatically terminate if you violate any of these restrictions and may be terminated by Office Sweeet at any time. Upon terminating your viewing of these materials or upon the termination of this license, you must destroy any downloaded materials in your possession whether in electronic or printed format.</p>
                <h3>3. Disclaimer</h3>
                <p>The materials on Office Sweeet's web site are provided "as is". Office Sweeet makes no warranties, expressed or implied, and hereby disclaims and negates all other warranties, including without limitation, implied warranties or conditions of merchantability, fitness for a particular purpose, or non-infringement of intellectual property or other violation of rights. Further, Office Sweeet does not warrant or make any representations concerning the accuracy, likely results, or reliability of the use of the materials on its Internet web site or otherwise relating to such materials or on any sites linked to this site.</p>
                <h3>4. Limitations</h3>
                <p>In no event shall Office Sweeet or its suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption,) arising out of the use or inability to use the materials on Office Sweeet's Internet site, even if Office Sweeet or an Office Sweeet authorized representative has been notified orally or in writing of the possibility of such damage. Because some jurisdictions do not allow limitations on implied warranties, or limitations of liability for consequential or incidental damages, these limitations may not apply to you.</p>
                <h3>5. Revisions and Errata</h3>
                <p>The materials appearing on Office Sweeet's web site could include technical, typographical, or photographic errors. Office Sweeet does not warrant that any of the materials on its web site are accurate, complete, or current. Office Sweeet may make changes to the materials contained on its web site at any time without notice. Office Sweeet does not, however, make any commitment to update the materials.</p>

                <h3>6. Links</h3>
                <p>Office Sweeet has not reviewed all of the sites linked to its Internet web site and is not responsible for the contents of any such linked site. The inclusion of any link does not imply endorsement by Office Sweeet of the site. Use of any such linked web site is at the user's own risk.</p>

                <h3>7. Site Terms of Use Modifications</h3>
                <p>Office Sweeet may revise these terms of use for its web site at any time without notice. By using this web site you are agreeing to be bound by the then current version of these Terms and Conditions of Use.</p>

                <h3>8. Governing Law</h3>
                <p>Any claim relating to Office Sweeet's web site shall be governed by the laws of the State of Florida without regard to its conflict of law provisions.</p>

                <p>General Terms and Conditions applicable to Use of a Web Site.</p>

                <h3>Privacy Policy</h3>
                <p>Your privacy is very important to us. Accordingly, we have developed this Policy in order for you to understand how we collect, use, communicate and disclose and make use of personal information. The following outlines our privacy policy.</p>
                <p>Before or at the time of collecting personal information, we will identify the purposes for which information is being collected.</p>
                <p>We will collect and use of personal information solely with the objective of fulfilling those purposes specified by us and for other compatible purposes, unless we obtain the consent of the individual concerned or as required by law.</p>
                <p>We will only retain personal information as long as necessary for the fulfillment of those purposes.</p>
                <p>We will collect personal information by lawful and fair means and, where appropriate, with the knowledge or consent of the individual concerned.</p>
                <p>Personal data should be relevant to the purposes for which it is to be used, and, to the extent necessary for those purposes, should be accurate, complete, and up-to-date.</p>
                <p>We will protect personal information by reasonable security safeguards against loss or theft, as well as unauthorized access, disclosure, copying, use or modification.</p>
                <p>We will make readily available to customers information about our policies and practices relating to the management of personal information.</p>
                <p>We are committed to conducting our business in accordance with these principles in order to ensure that the confidentiality of personal information is protected and maintained.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<input id="users" value="unlimited" style="display: none;">
<input id="cost" value="194" style="display: none;">
<input id="plan" value="Sept2018_Unlimited" style="display: none;">

<script>
$(document).ready(function() {

    $('.sub-box').click(function () {
        $('.sub-selected').removeClass('sub-selected');
        $(this).addClass('sub-selected');

        $('#users').val($(this).data('users'));
        $('#cost').val($(this).data('cost'));
        $('#plan').val($(this).data('plan'));

    });

    $('.sub-box').last().click();

    $('#sub-submit-1').on('click', function(e) {
        if($('#agree').is(':checked')){
            SubScriptionUpdate($('#users').val(), $('#cost').val(), $('#plan').val(), false);
        }else{
            $.dialog({
                title: 'Oops...',
                content: 'Please indicate that you have read and agreed to the Subscription Agreement and Terms and Conditions.'
            });
            $error = true;
        }
    });

});

</script>