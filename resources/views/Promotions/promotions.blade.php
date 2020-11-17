@include('Promotions.header')

<body style="background-image: url('{{ url('/images/signupbackground2.jpg') }}');
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-size: 105% ;">

<style>
    .credit-card-box .panel-title {
        display: inline;
        font-weight: bold;
    }

    .credit-card-box .form-control.error {
        border-color: red;
        outline: 0;
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(255, 0, 0, 0.6);
    }

    .credit-card-box label.error {
        font-weight: bold;
        color: red;
        padding: 2px 8px;
        margin-top: 2px;
    }

    .credit-card-box .payment-errors {
        font-weight: bold;
        color: red;
        padding: 2px 8px;
        margin-top: 2px;
    }

    .credit-card-box label {
        display: block;
    }

    /* The old "center div vertically" hack */

    .credit-card-box .display-tr {
        display: table-row;
    }

    .credit-card-box .display-td {
        display: table-cell;
        vertical-align: middle;
        width: 50%;
    }

    /* Just looks nicer */
    .credit-card-box .panel-heading img {
        min-width: 180px;
    }

</style>


<div class="container" style="background: white; margin-top: 50px; border-radius: 20px; padding: 10px;">
    <div class="row">
        <div class="col-md-4 col-md-offset-4"><img width="100%" src="/images/oslogo.png"></div>
    </div>
    <div class="row">
        <div class="col-md-6 col-md-offset-3" style="font-size: x-large;">
            @if(count($promotions) === 0)
                Sorry there are no promotions running at the moment

            @else
                @if(count($promotions) === 1)
                    @if($promotions->first()->isActive())
                        {{ $promotions->first()->DealSummery() }} ({{ $promotions->first()->name }})
                        <br>
                        {{ $promotions->first()->description }}

                        <button style="width: 100%;" type="button" class="button" data-toggle="modal" data-target="#PaymentModel" data-type="single" data-users="{{ $promotions->first()->numusers }}" data-cost="{{ $promotions->first()->costFormated() }}" data-description="{{ $promotions->first()->description }}" data-tn_plan_name="{{ $promotions->first()->tn_plan_name }}">Let's Get Started!</button>
                    @else
                        This promotion is not currently active, please click <a href="/">here</a> to see our current promotions.
                    @endif
                @else
                    @foreach($promotions as $promotion)
                        <div class="radio">
                            <label ><input style="width: 30px; height: 30px;" class="promoradio" type="radio" name="promoradio" data-users="{{ $promotion->numusers }}" data-cost="{{ $promotion->costFormated() }}" data-description="{{ $promotion->description }}" data-tn_plan_name="{{ $promotion->tn_plan_name }}">.     <b>{{ $promotion->name }}</b> - {{ $promotion->DealSummery() }}</label>
                        </div>
                    @endforeach
                    <h4>(Select one)</h4>
                    <div id="dscription" style="text-align: center;"></div>

                    <div class="row" style="text-align: center; margin-top: 50px;">
                        <button id="StartButton" type="button" class="btn btn-default btn-lg  active col-md-8 col-md-offset-2" data-toggle="modal" data-target="#PaymentModel" data-type="radio" disabled><b>Lets Get Started!</b></button>
                    </div>
                @endif
            @endif
        </div>
    </div>
    <div class="row" style="text-align: center; margin-top: 20px;">
        <p>You will be billed every 30 days from your start date.</p>
        <p>You can cancel anytime from within Office Sweeet or by emailing support@officesweeet.com or calling 813-444-5284.</p>
    </div>
</div>


<div id="PaymentModel" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <div class="panel panel-default credit-card-box" style="margin-bottom: 0px;">
                    <div class="panel-heading display-table">
                        <div class="row display-tr">
                            <h3 class="panel-title display-td">Payment Details</h3>
                            <div class="display-td">
                                <img class="img-responsive pull-right" src="{{ url('/images/accepted_cc.png') }}">
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">


                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="cardNumber">CARD NUMBER</label>
                                    <div class="input-group">
                                        <input
                                                id="cc-cardNumber"
                                                type="tel"
                                                class="form-control"
                                                name="cardNumber"
                                                placeholder="Valid Card Number"
                                                autocomplete="cc-number"
                                                required autofocus
                                        />
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-credit-card"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-7 col-md-7">
                                <div class="form-group">
                                    <label for="cardExpiry"><span class="hidden-xs">EXPIRATION</span><span
                                                class="visible-xs-inline">EXP</span> DATE</label>
                                    <select
                                            style="width: 49%; display: inline-block;"
                                            id="cc-cardExpiry-month"
                                            class="form-control"
                                    >
                                        <option value="01">01</option>
                                        <option value="02">02</option>
                                        <option value="03">03</option>
                                        <option value="04">04</option>
                                        <option value="05">05</option>
                                        <option value="06">06</option>
                                        <option value="07">07</option>
                                        <option value="08">08</option>
                                        <option value="09">09</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                    </select>

                                    <select
                                            style="width: 49%; display: inline-block;"
                                            id="cc-cardExpiry-year"
                                            class="form-control"
                                    >
                                        @foreach($years as $year)
                                            <option value="{{$year}}">{{$year}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-5 col-md-5 pull-right">
                                <div class="form-group">
                                    <label for="cardCVC">CV CODE</label>
                                    <input
                                            id="cc-cardCVC"
                                            type="tel"
                                            class="form-control"
                                            name="cardCVC"
                                            placeholder="CVC"
                                            autocomplete="cc-csc"
                                            required
                                    />
                                </div>
                            </div>
                        </div>

                        <hr/>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="firstName">FIRST NAME</label>
                                    <input
                                            id="cc-firstname"
                                            class="form-control"
                                            name="firstName"
                                            placeholder="First Name"
                                            required autofocus
                                    />
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="lastName">LAST NAME</label>
                                    <input
                                            id="cc-lastname"
                                            class="form-control"
                                            name="lastName"
                                            placeholder="Last Name"
                                            required autofocus
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="email">EMAIL</label>
                                    <input
                                            id="cc-email"
                                            type="tel"
                                            class="form-control"
                                            name="email"
                                            placeholder="E-Mail"
                                            required autofocus
                                    />
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="email">TELEPHONE NUMBER</label>
                                    <input
                                            id="cc-phone"
                                            type="tel"
                                            class="form-control"
                                            name="phone"
                                            placeholder="Telephone Number"
                                            required autofocus
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="email">COMPANY NAME *</label>
                                    <input
                                            id="cc-company"
                                            type="tel"
                                            class="form-control"
                                            name="company"
                                            placeholder="Company Name"
                                            required autofocus
                                    />
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="email">BUSINESS ROLE</label>
                                    <input
                                            id="cc-businessrole"
                                            type="tel"
                                            class="form-control"
                                            name="businessrole"
                                            placeholder="Business Role"
                                            required autofocus
                                    />
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="address1">ADDRESS LINE 1</label>
                                    <input
                                            id="cc-address1"
                                            type="tel"
                                            class="form-control"
                                            name="cc-address1"
                                            placeholder="Address 1"
                                            required autofocus

                                    />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4">
                                <div class="form-group">
                                    <label for="city">CITY</label>
                                    <input
                                            id="cc-city"
                                            type="tel"
                                            class="form-control"
                                            name="city"
                                            placeholder="City"
                                            required autofocus

                                    />
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group">
                                    <label for="cc-state">STATE</label>
                                    <input
                                            id="cc-state"
                                            type="tel"
                                            class="form-control"
                                            name="state"
                                            placeholder="State"
                                            required autofocus

                                    />
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group">
                                    <label for="zip">ZIP</label>
                                    <input
                                            id="cc-zip"
                                            type="tel"
                                            class="form-control"
                                            name="zip"
                                            placeholder="Zip"
                                            required autofocus

                                    />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">

                                <label for="cc-country">COUNTRY</label>
                                <select
                                        id="cc-country"
                                        class="form-control"
                                >
                                    <option value='AF'>Afghanistan</option>
                                    <option value='AX'>Åland Islands</option>
                                    <option value='AL'>Albania</option>
                                    <option value='DZ'>Algeria</option>
                                    <option value='AS'>American Samoa</option>
                                    <option value='AD'>Andorra</option>
                                    <option value='AO'>Angola</option>
                                    <option value='AI'>Anguilla</option>
                                    <option value='AQ'>Antarctica</option>
                                    <option value='AG'>Antigua and Barbuda</option>
                                    <option value='AR'>Argentina</option>
                                    <option value='AM'>Armenia</option>
                                    <option value='AW'>Aruba</option>
                                    <option value='AU'>Australia</option>
                                    <option value='AT'>Austria</option>
                                    <option value='AZ'>Azerbaijan</option>
                                    <option value='BS'>Bahamas</option>
                                    <option value='BH'>Bahrain</option>
                                    <option value='BD'>Bangladesh</option>
                                    <option value='BB'>Barbados</option>
                                    <option value='BY'>Belarus</option>
                                    <option value='BE'>Belgium</option>
                                    <option value='BZ'>Belize</option>
                                    <option value='BJ'>Benin</option>
                                    <option value='BM'>Bermuda</option>
                                    <option value='BT'>Bhutan</option>
                                    <option value='BO'>Bolivia (Plurinational State of)</option>
                                    <option value='BQ'>Bonaire, Sint Eustatius and Saba</option>
                                    <option value='BA'>Bosnia and Herzegovina</option>
                                    <option value='BW'>Botswana</option>
                                    <option value='BV'>Bouvet Island</option>
                                    <option value='BR'>Brazil</option>
                                    <option value='IO'>British Indian Ocean Territory</option>
                                    <option value='BN'>Brunei Darussalam</option>
                                    <option value='BG'>Bulgaria</option>
                                    <option value='BF'>Burkina Faso</option>
                                    <option value='BI'>Burundi</option>
                                    <option value='CV'>Cabo Verde</option>
                                    <option value='KH'>Cambodia</option>
                                    <option value='CM'>Cameroon</option>
                                    <option value='CA'>Canada</option>
                                    <option value='KY'>Cayman Islands</option>
                                    <option value='CF'>Central African Republic</option>
                                    <option value='TD'>Chad</option>
                                    <option value='CL'>Chile</option>
                                    <option value='CN'>China</option>
                                    <option value='CX'>Christmas Island</option>
                                    <option value='CC'>Cocos (Keeling) Islands</option>
                                    <option value='CO'>Colombia</option>
                                    <option value='KM'>Comoros</option>
                                    <option value='CG'>Congo</option>
                                    <option value='CD'>Congo (Democratic Republic of the)</option>
                                    <option value='CK'>Cook Islands</option>
                                    <option value='CR'>Costa Rica</option>
                                    <option value='CI'>Côte d'Ivoire</option>
                                    <option value='HR'>Croatia</option>
                                    <option value='CU'>Cuba</option>
                                    <option value='CW'>Curaçao</option>
                                    <option value='CY'>Cyprus</option>
                                    <option value='CZ'>Czechia</option>
                                    <option value='DK'>Denmark</option>
                                    <option value='DJ'>Djibouti</option>
                                    <option value='DM'>Dominica</option>
                                    <option value='DO'>Dominican Republic</option>
                                    <option value='EC'>Ecuador</option>
                                    <option value='EG'>Egypt</option>
                                    <option value='SV'>El Salvador</option>
                                    <option value='GQ'>Equatorial Guinea</option>
                                    <option value='ER'>Eritrea</option>
                                    <option value='EE'>Estonia</option>
                                    <option value='ET'>Ethiopia</option>
                                    <option value='FK'>Falkland Islands (Malvinas)</option>
                                    <option value='FO'>Faroe Islands</option>
                                    <option value='FJ'>Fiji</option>
                                    <option value='FI'>Finland</option>
                                    <option value='FR'>France</option>
                                    <option value='GF'>French Guiana</option>
                                    <option value='PF'>French Polynesia</option>
                                    <option value='TF'>French Southern Territories</option>
                                    <option value='GA'>Gabon</option>
                                    <option value='GM'>Gambia</option>
                                    <option value='GE'>Georgia</option>
                                    <option value='DE'>Germany</option>
                                    <option value='GH'>Ghana</option>
                                    <option value='GI'>Gibraltar</option>
                                    <option value='GR'>Greece</option>
                                    <option value='GL'>Greenland</option>
                                    <option value='GD'>Grenada</option>
                                    <option value='GP'>Guadeloupe</option>
                                    <option value='GU'>Guam</option>
                                    <option value='GT'>Guatemala</option>
                                    <option value='GG'>Guernsey</option>
                                    <option value='GN'>Guinea</option>
                                    <option value='GW'>Guinea-Bissau</option>
                                    <option value='GY'>Guyana</option>
                                    <option value='HT'>Haiti</option>
                                    <option value='HM'>Heard Island and McDonald Islands</option>
                                    <option value='VA'>Holy See</option>
                                    <option value='HN'>Honduras</option>
                                    <option value='HK'>Hong Kong</option>
                                    <option value='HU'>Hungary</option>
                                    <option value='IS'>Iceland</option>
                                    <option value='IN'>India</option>
                                    <option value='ID'>Indonesia</option>
                                    <option value='IR'>Iran (Islamic Republic of)</option>
                                    <option value='IQ'>Iraq</option>
                                    <option value='IE'>Ireland</option>
                                    <option value='IM'>Isle of Man</option>
                                    <option value='IL'>Israel</option>
                                    <option value='IT'>Italy</option>
                                    <option value='JM'>Jamaica</option>
                                    <option value='JP'>Japan</option>
                                    <option value='JE'>Jersey</option>
                                    <option value='JO'>Jordan</option>
                                    <option value='KZ'>Kazakhstan</option>
                                    <option value='KE'>Kenya</option>
                                    <option value='KI'>Kiribati</option>
                                    <option value='KP'>Korea (Democratic People's Republic of)</option>
                                    <option value='KR'>Korea (Republic of)</option>
                                    <option value='KW'>Kuwait</option>
                                    <option value='KG'>Kyrgyzstan</option>
                                    <option value='LA'>Lao People's Democratic Republic</option>
                                    <option value='LV'>Latvia</option>
                                    <option value='LB'>Lebanon</option>
                                    <option value='LS'>Lesotho</option>
                                    <option value='LR'>Liberia</option>
                                    <option value='LY'>Libya</option>
                                    <option value='LI'>Liechtenstein</option>
                                    <option value='LT'>Lithuania</option>
                                    <option value='LU'>Luxembourg</option>
                                    <option value='MO'>Macao</option>
                                    <option value='MK'>Macedonia (the former Yugoslav Republic of)</option>
                                    <option value='MG'>Madagascar</option>
                                    <option value='MW'>Malawi</option>
                                    <option value='MY'>Malaysia</option>
                                    <option value='MV'>Maldives</option>
                                    <option value='ML'>Mali</option>
                                    <option value='MT'>Malta</option>
                                    <option value='MH'>Marshall Islands</option>
                                    <option value='MQ'>Martinique</option>
                                    <option value='MR'>Mauritania</option>
                                    <option value='MU'>Mauritius</option>
                                    <option value='YT'>Mayotte</option>
                                    <option value='MX'>Mexico</option>
                                    <option value='FM'>Micronesia (Federated States of)</option>
                                    <option value='MD'>Moldova (Republic of)</option>
                                    <option value='MC'>Monaco</option>
                                    <option value='MN'>Mongolia</option>
                                    <option value='ME'>Montenegro</option>
                                    <option value='MS'>Montserrat</option>
                                    <option value='MA'>Morocco</option>
                                    <option value='MZ'>Mozambique</option>
                                    <option value='MM'>Myanmar</option>
                                    <option value='NA'>Namibia</option>
                                    <option value='NR'>Nauru</option>
                                    <option value='NP'>Nepal</option>
                                    <option value='NL'>Netherlands</option>
                                    <option value='NC'>New Caledonia</option>
                                    <option value='NZ'>New Zealand</option>
                                    <option value='NI'>Nicaragua</option>
                                    <option value='NE'>Niger</option>
                                    <option value='NG'>Nigeria</option>
                                    <option value='NU'>Niue</option>
                                    <option value='NF'>Norfolk Island</option>
                                    <option value='MP'>Northern Mariana Islands</option>
                                    <option value='NO'>Norway</option>
                                    <option value='OM'>Oman</option>
                                    <option value='PK'>Pakistan</option>
                                    <option value='PW'>Palau</option>
                                    <option value='PS'>Palestine, State of</option>
                                    <option value='PA'>Panama</option>
                                    <option value='PG'>Papua New Guinea</option>
                                    <option value='PY'>Paraguay</option>
                                    <option value='PE'>Peru</option>
                                    <option value='PH'>Philippines</option>
                                    <option value='PN'>Pitcairn</option>
                                    <option value='PL'>Poland</option>
                                    <option value='PT'>Portugal</option>
                                    <option value='PR'>Puerto Rico</option>
                                    <option value='QA'>Qatar</option>
                                    <option value='RE'>Réunion</option>
                                    <option value='RO'>Romania</option>
                                    <option value='RU'>Russian Federation</option>
                                    <option value='RW'>Rwanda</option>
                                    <option value='BL'>Saint Barthélemy</option>
                                    <option value='SH'>Saint Helena, Ascension and Tristan da Cunha</option>
                                    <option value='KN'>Saint Kitts and Nevis</option>
                                    <option value='LC'>Saint Lucia</option>
                                    <option value='MF'>Saint Martin (French part)</option>
                                    <option value='PM'>Saint Pierre and Miquelon</option>
                                    <option value='VC'>Saint Vincent and the Grenadines</option>
                                    <option value='WS'>Samoa</option>
                                    <option value='SM'>San Marino</option>
                                    <option value='ST'>Sao Tome and Principe</option>
                                    <option value='SA'>Saudi Arabia</option>
                                    <option value='SN'>Senegal</option>
                                    <option value='RS'>Serbia</option>
                                    <option value='SC'>Seychelles</option>
                                    <option value='SL'>Sierra Leone</option>
                                    <option value='SG'>Singapore</option>
                                    <option value='SX'>Sint Maarten (Dutch part)</option>
                                    <option value='SK'>Slovakia</option>
                                    <option value='SI'>Slovenia</option>
                                    <option value='SB'>Solomon Islands</option>
                                    <option value='SO'>Somalia</option>
                                    <option value='ZA'>South Africa</option>
                                    <option value='GS'>South Georgia and the South Sandwich Islands</option>
                                    <option value='SS'>South Sudan</option>
                                    <option value='ES'>Spain</option>
                                    <option value='LK'>Sri Lanka</option>
                                    <option value='SD'>Sudan</option>
                                    <option value='SR'>Suriname</option>
                                    <option value='SJ'>Svalbard and Jan Mayen</option>
                                    <option value='SZ'>Swaziland</option>
                                    <option value='SE'>Sweden</option>
                                    <option value='CH'>Switzerland</option>
                                    <option value='SY'>Syrian Arab Republic</option>
                                    <option value='TW'>Taiwan, Province of China[a]</option>
                                    <option value='TJ'>Tajikistan</option>
                                    <option value='TZ'>Tanzania, United Republic of</option>
                                    <option value='TH'>Thailand</option>
                                    <option value='TL'>Timor-Leste</option>
                                    <option value='TG'>Togo</option>
                                    <option value='TK'>Tokelau</option>
                                    <option value='TO'>Tonga</option>
                                    <option value='TT'>Trinidad and Tobago</option>
                                    <option value='TN'>Tunisia</option>
                                    <option value='TR'>Turkey</option>
                                    <option value='TM'>Turkmenistan</option>
                                    <option value='TC'>Turks and Caicos Islands</option>
                                    <option value='TV'>Tuvalu</option>
                                    <option value='UG'>Uganda</option>
                                    <option value='UA'>Ukraine</option>
                                    <option value='AE'>United Arab Emirates</option>
                                    <option value='GB'>United Kingdom of Great Britain and Northern Ireland</option>
                                    <option value='US' selected>United States of America</option>
                                    <option value='UM'>United States Minor Outlying Islands</option>
                                    <option value='UY'>Uruguay</option>
                                    <option value='UZ'>Uzbekistan</option>
                                    <option value='VU'>Vanuatu</option>
                                    <option value='VE'>Venezuela (Bolivarian Republic of)</option>
                                    <option value='VN'>Viet Nam</option>
                                    <option value='VG'>Virgin Islands (British)</option>
                                    <option value='VI'>Virgin Islands (U.S.)</option>
                                    <option value='WF'>Wallis and Futuna</option>
                                    <option value='EH'>Western Sahara</option>
                                    <option value='YE'>Yemen</option>
                                    <option value='ZM'>Zambia</option>
                                    <option value='ZW'>Zimbabwe</option>
                                </select>

                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-xs-12">
                                <button id="submit" class="btn btn-success btn-lg btn-block" type="submit">Pay $ Now
                                </button>
                            </div>
                        </div>

                        <div class="row" style="display:none;">
                            <div class="col-xs-12">
                                <p class="payment-errors"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#PaymentModel').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            if(button.data('type') === "single"){

                $cost = button.data('cost');

                $('#submit').html('Subscribe now for $' + $cost + " per month");

            }else{
                $radio = $('input[name=promoradio]:checked');
                console.log($radio);

                $cost = $radio.data('cost');
                $users = $radio.data('users');
                $planname = $radio.data('tn_plan_name');

                $('#PaymentModel').data('num_of_users', $users);
                $('#PaymentModel').data('cost', $cost);
                $('#PaymentModel').data('tn_plan_name', $planname);

                $('#submit').html('Subscribe now for $' + $cost + " per month");

            }
        });

        $('.promoradio').click(function () {
           $('#dscription').html($(this).data("description"));
           $('#StartButton').prop('disabled', false);
        });

        $('#submit').click(function () {

            $("body").addClass("loading");
            ResetServerValidationErrors();

            var values = {};
            values["_token"] = "{{ csrf_token() }}";
            $( "input[id^='cc-']" ).each(function(){
                values[$(this).attr('id').slice(3)] = $(this).val();
            });

            values['country'] = $('#cc-country').val();

            values['cardExpiryMonth'] = $('#cc-cardExpiry-month').val();
            values['cardExpiryYear'] = $('#cc-cardExpiry-year').val();
            values['number_of_users'] = $('#PaymentModel').data('num_of_users');
            values['referalcode'] = $('#PaymentModel').data('tn_plan_name');
            //values[''] = $('#PaymentModel').data('cost');

            $post = $.post("/", values);

            $post.done(function (data) {
                $("body").removeClass("loading");
                switch(data['status']) {
                    case "OK":
                        var link = document.createElement('a');
                        link.href = "https://www.officesweeet.com/solo-sign-up-thank-you";
                        link.id = "";
                        document.body.appendChild(link);
                        link.click();
                        break;
                    case "notfound":
                        $.dialog({
                            title: 'Oops...',
                            content: 'Unknown Response from server. Please refresh the page and try again.'
                        });
                        break;
                    case "validation":
                        ServerValidationErrors(data['errors']);
                        break;
                    case "cardvalidation":
                        CardValidationErrors(data['errors']);
                        break;
                    default:
                        console.log(data);
                        $.dialog({
                            title: 'Oops...',
                            content: 'Unknown Response from server. Please refresh the page and try again.'
                        });
                }

            /**
                console.log(data);
                if ($.isArray(data)) {
                    ServerValidationErrors(data);

                }else{
                    $split = data.split(':');


                    $.dialog({
                        title: ' ',
                        content: $split[1],
                    });


                    //SavedSuccess();
                }
             **/
            });

            $post.fail(function () {
                NoReplyFromServer();
            });

        });
    });

    function ResetServerValidationErrors(){
        $('.invalid').removeClass('invalid');
    }

    function CardValidationErrors($errors){
        switch($errors) {
            case "4:invaliddate":
                $.dialog({
                    title: 'Oops...',
                    content: 'Your Card Expiry Date is Invalid.'
                });
                break;
            case "4:invalidcvc":
                $.dialog({
                    title: 'Oops...',
                    content: 'Your Card CVV is Invalid.'
                });
                break;
            case "4:invalidcardnumber":
                $.dialog({
                    title: 'Oops...',
                    content: 'Your Card number is Invalid.'
                });
                break;
            default:
                console.log(data);
                $.dialog({
                    title: 'Oops...',
                    content: 'Unknown Response from server. Please refresh the page and try again.'
                });
        }
    }

    function ServerValidationErrors($array) {



        $.each($array, function (index, value) {
            $('#' + index).addClass('invalid');
        });

        $text = "";
        $.each($array, function( index, value ) {
            $text = $text + value + "<br>";
        });
        $.dialog({
            title: 'Oops...',
            content: $text
        });
    }
</script>


<!--Start of Tawk.to Script-->
<script type="text/javascript">
    var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
    (function () {
        var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
        s1.async = true;
        s1.src = 'https://embed.tawk.to/599308111b1bed47ceb04bd3/default';
        s1.charset = 'UTF-8';
        s1.setAttribute('crossorigin', '*');
        s0.parentNode.insertBefore(s1, s0);
    })();
</script>
<!--End of Tawk.to Script-->

<div class="modalload"><!-- Place at bottom of page --></div>

@include('Promotions.footer')
