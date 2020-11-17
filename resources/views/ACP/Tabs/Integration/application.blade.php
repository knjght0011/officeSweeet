
<div class="col-lg-5" style="margin-top: 15px;">
{!! Form::OSinput("tna-corporate-name", "Corporate Name", "", "", "true", "") !!}
{!! Form::OSinput("tna-dba-name", "DBA Name (if different)", "", "", "true", "") !!}
{!! Form::OSinput("tna-physical-business-address", "Physical Business Address", "", "", "true", "") !!}
{!! Form::OSinput("tna-business-phone", "Business Phone", "", "", "true", "") !!}
{!! Form::OSinput("tna-owner-officer-name", "Owner/Officer Name", "", "", "true", "") !!}
{!! Form::OSinput("tna-owner-officer-email", "Owner/Officer Email", "", "", "true", "") !!}
</div>
<div class="col-lg-7" style="margin-top: 15px;">
{!! Form::OSinputWideLabel("tna-federal-tax-id-number", "Federal Tax ID (EIN) Number", "", "", "true", "") !!}
{!! Form::OSinputWideLabel("tna-business-type", "Business Type", "", "", "true", "") !!}
{!! Form::OSinputWideLabel("tna-bank-name", "Bank Name", "", "", "true", "") !!}
{!! Form::OSinputWideLabel("tna-bank-account-number", "Bank Account Number", "", "", "true", "") !!}
{!! Form::OSinputWideLabel("tna-bank-routing-number", "Bank Routing Number", "", "", "true", "") !!}
{!! Form::OSinputWideLabel("tna-owner-home-address", "Owner Home Address", "", "", "true", "") !!}
{!! Form::OSinputWideLabel("tna-owner-date-of-birth", "Owner Date of Birth", "", "", "true", "") !!}
{!! Form::OSinputWideLabel("tna-owner-last-4-of-social-security-number", "Owner Last 4 of Social Security Number", "", "", "true", "") !!}
{!! Form::OSinputWideLabel("tna-estimated-monthly-credit-card-sales", "Estimated Monthly Credit Card Sales ($)", "", "", "true", "") !!}
{!! Form::OSinputWideLabel("tna-typical-average-transaction-amount", "Typical Average Transaction Amount ($)", "", "", "true", "") !!}
{!! Form::OSinputWideLabel("tna-estimated-largest-single-transaction-amount​", "Estimated Largest Single Transaction Amount ($)​", "", "", "true", "") !!}
</div>
<button id="tna-send" name="save" type="button" class="btn OS-Button">Send to TransNational</button>
<script>
$(document).ready(function() {
    AddPopup($('#tna-business-type'), "left", "Corporation, LLC, Sole Proprietor, etc.");
    
    @if(SettingHelper::GetSetting('companyname') != null)
        $('#tna-corporate-name').val("{{ SettingHelper::GetSetting('companyname') }}");
        $('#tna-dba-name').val("{{ SettingHelper::GetSetting('companyname') }}");
    @endif

    @if(isset($physicalbusinessaddress))
    $('#tna-physical-business-address').val("{{ $physicalbusinessaddress }}");
    $('#tna-business-phone').val("{{ $businessphone }}");
    @endif
    
    @foreach(UserHelper::GetAllUsers() as $user)
        @if($user->type === 1)
            $('#tna-owner-officer-name').val("{{ $user->name }}");
            $('#tna-owner-officer-email').val("{{ $user->email }}");
            $('#tna-owner-home-address').val("{{ $user->address->number }} {{ $user->address->address1 }} {{ $user->address->address2 }} {{ $user->address->city }} {{ $user->address->region }} {{ $user->address->state }} {{ $user->address->zip }}");
        @endif
    @endforeach

    $('#tna-send').click(function(){
        var values = {};
        values["_token"] = "{{ csrf_token() }}";
        $( "input[id^='tna-']" ).each(function(){
            values[$(this).attr('id')] = $(this).val();
        });
        
        $("body").addClass("loading");
        posting = $.post("/ACP/Integration/TNApplication", values);

        posting.done(function( data ) {
            $("body").removeClass("loading");
            $.dialog({
                title: 'Success!',
                content: 'Your application has been submitted, Please wait for a reply from transnational.'
            });
        });

        posting.fail(function() {
            $("body").removeClass("loading");
            $.dialog({
                title: 'Oops...',
                content: 'Lost contact with server'
            });
        });
    });
});
</script>