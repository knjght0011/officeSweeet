@include('header')

<body style="background-image: url('{{ url('/images/signupbackground2.jpg') }}'); background-repeat: no-repeat; background-attachment: fixed; background-size: 105% ;">

<style>

    .credit-card-box .panel-title {
        display: inline;
        font-weight: bold;
    }
    .credit-card-box .form-control.error {
        border-color: red;
        outline: 0;
        box-shadow: inset 0 1px 1px rgba(0,0,0,0.075),0 0 8px rgba(255,0,0,0.6);
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

    .offer_img{position:absolute;right:-4px;top:-7px}

    .price_tab{margin-top:50px;padding:0 0px}
    .price_tab>label{margin-bottom:0;border:solid 3px #ef5952;padding:2px;border-radius:22px;display:inline-block;background:#fff}
    .price_tab label>span{display:block;padding:8px 40px;background:#ef5952;color:#fff;border-radius:20px}
    .price_tab #tab_4+label{margin-right:0}

    .tab_box{padding:15px;text-align:left;border:solid 1px #ccc;position:relative;margin-top:-2px;background-color: white;}
    .tab_box:before{content:"";position:absolute;top:-1px;left:1.5%;min-width:97%;height:2px;background:#ef5952}

    .tab_form h3{margin:0 0 10px;font-size:18px;color:#222;text-transform:uppercase}

    button{margin:5px 0 15px;padding:8px 25px;border:none;box-shadow:none;display:inline-block;border-radius:20px;background:#ef5952;color:#fff;outline:none;text-transform:uppercase}

</style>

<div class="container">
    <div class="price_tab">
        <div class="tab_box">
            <div class="tab_form">
                @if($account->subscription_id === null)
                    <h2 class="heading" style="text-align: center;">Your OfficeSweeet trial has ended. What would you like to do next?</h2>
                    <!--<h4 style="text-align: center;">We're saving your data so you can choose to subscribe now. Otherwise do nothing and your data will be deleted after 90 days.</h4>-->
                @else
                    <h3 style="text-align: center;">It looks like your officesweeet account has been disabled</h3>
                @endif
            </div>
        </div>
    </div>


    <div class="price_tab">
        <div class="tab_box">
            <div class="tab_form">
                <p style="font-size: x-large;"><input type="radio" name="cancel-reason" value="subscribe" checked="checked"> I like it. I would like to continue with a monthly subscription.</p>
                @if($account->transaction_id === "")<p style="font-size: x-large;"><input type="radio" name="cancel-reason" value="time"> I needed more time to evaluate Office Sweeet.</p>@endif
                <p style="font-size: x-large;"><input type="radio" name="cancel-reason" value="done"> Thank you but, I dont feel that Office Sweeet is a good fit for us.</p>
            </div>
        </div>
    </div>

    <div class="price_tab" id="sub-option">
        <div class="tab_box">
            <div class="tab_form">

                <h3>How many people in your office need access to OfficeSweeet? </h3>
                <p>(You can change this later)</p>


                <div class="form-group row">
                    <label for="number_of_users" class="col-sm-2 col-form-label">Number of Users *</label>
                    <div class="col-sm-3">
                        <input type="number" min="1" step="1" onkeypress="return event.charCode >= 48 && event.charCode <= 57" name="number_of_users" id="number_of_users" class="form-control"/>
                    </div>
                    <label for="price" class="col-sm-2 col-form-label">Total monthly cost *</label>
                    <div class="col-sm-3">
                        <input type="text" name="price" id="price" class="form-control"  readonly/>
                    </div>
                    <label for="price" class="col-sm-2 col-form-label">FOR THE ENTIRE SYSTEM</label>
                </div>


                <div class="wrap row" style="padding-left: 10px; padding-right: 10px;">
                    <div class="cost_sec">
                        <h3>SOLO OFFICE <br>(1)</h3>
                        <h5>COST PER USER : $29</h5>
                        <p>USERS : 1</p>
                    </div>
                    <div class="cost_sec">
                        <h3>SMALL BUSINESS <br>(2-3)</h3>
                        <h5>COST PER USER : $19</h5>
                        <p>USERS : 2, 3</p>
                    </div>
                    <div class="cost_sec">
                        <h3>MEDIUM BUSINESS <br>(4-6)</h3>
                        <h5>COST PER USER : $16</h5>
                        <p>USERS : 4, 5, 6</p>
                    </div>
                    <div class="cost_sec">
                        <h3>LARGE BUSINESS <br>(7-9)</h3>
                        <h5>COST PER USER : $14</h5>
                        <p>USERS : 7, 8, 9</p>
                    </div>
                    <div class="cost_sec">
                        <h3>ENTERPRISE <br>(10 or more)</h3>
                        <h5>COST PER USER : $9.95</h5>
                        <p>USERS : 10 or more</p>
                        <img src="{{ url('/images/offer-tag.png') }}" alt="" class="offer_img" />
                    </div>
                </div>

                <div style="text-align: center; margin-top: 10px;">
                    <h3>Subscription Agreement</h3>
                    <input type="checkbox" id="agree">
                    <label for="agree">I Agree to the <a href="#" data-toggle="modal" data-target="#model"> Subscription Agreement and Terms and Conditions.</a></label>
                </div>

                <div class="text-center">
                    <button type="submit"  type="button" value="Submit" id="subscribe-submit">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="price_tab" id="time-option">
        <div class="tab_box">
            <div class="tab_form">
                <p style="font-size: x-large;text-align: center;"><b>Pay only $10 for an additional 30 days</b></p>
                <p style="font-size: large;text-align: center;">Your account will remain active for 30 more days; one user for $10.</p>
                <div class="text-center">
                    <button type="submit" data-toggle="modal" data-target="#PaymentModel" type="button" value="Submit" id="trial-submit">I Accept</button>
                </div>
            </div>
        </div>
    </div>

    <div class="price_tab" id="done-option">
        <div class="tab_box">
            <div class="tab_form">
                <p>Some sort of text from John Explaining that you dont need to do anything and your data will be removed in 90 days, but feel free to come back if you change your mind.</p>
            </div>
        </div>
    </div>
</div>


<div id="PaymentModel" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <div class="panel panel-default credit-card-box" style="margin-bottom: 0px;">
                    <div class="panel-heading display-table" >
                        <div class="row display-tr" >
                            <h3 class="panel-title display-td" >Payment Details</h3>
                            <div class="display-td" >
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
                                    <label for="cardExpiry"><span class="hidden-xs">EXPIRATION</span><span class="visible-xs-inline">EXP</span> DATE</label>
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

                        <hr />
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="firstName">FIRST NAME</label>
                                    <input
                                            id="cc-firstname"
                                            type="tel"
                                            class="form-control"
                                            name="firstName"
                                            placeholder="First Name"
                                            required autofocus
                                            value=""
                                    />
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="lastName">LAST NAME</label>
                                    <input
                                            id="cc-lastname"
                                            type="tel"
                                            class="form-control"
                                            name="lastName"
                                            placeholder="Last Name"
                                            required autofocus
                                            value=""
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="email">EMAIL</label>
                                    <input
                                            id="cc-email"
                                            type="tel"
                                            class="form-control"
                                            name="email"
                                            placeholder="E-Mail"
                                            required autofocus
                                            value=""
                                    />
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="address1">ADDRESS LINE 1</label>
                                    <input
                                            id="cc-address1"
                                            type="tel"
                                            class="form-control"
                                            name="address1"
                                            placeholder="Address 1"
                                            required autofocus
                                            value=""
                                    />
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="address2">ADDRESS LINE 2</label>
                                    <input
                                            id="cc-address2"
                                            type="tel"
                                            class="form-control"
                                            name="address2"
                                            placeholder="Address 2"
                                            required autofocus
                                            value=""
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
                                            value=""
                                    />
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group">
                                    <label for="state">STATE</label>
                                    <input
                                            id="cc-state"
                                            type="tel"
                                            class="form-control"
                                            name="state"
                                            placeholder="State"
                                            required autofocus
                                            value=""
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
                                            value=""
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">

                                <label for="country">COUNTRY</label>
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
                        <hr />
                        <div class="row">
                            <div class="col-xs-12">
                                <button id="payment-submit" class="btn btn-success btn-lg btn-block" type="payment-submit">Pay $ Now</button>
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

<div class="modalload"><!-- Place at bottom of page --></div>

<div class="modal fade" id="model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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

<script>
$(document).ready(function() {
    $('#PaymentModel').on('show.bs.modal', function (e) {

        switch($("input[name='cancel-reason']:checked").val()) {
            case "time":
                $('#payment-submit').html('Pay $10 Now');
                break;
            case "subscribe":
                $cost = $('#price').val();
                $('#payment-submit').html('Pay $'+$cost+' Now');
                break;
            case "done":


                break;
        }


    });

    $('#payment-submit').click(function () {
        $('#cc-cardExpiry-month').removeClass('invalid');
        $('#cc-cardExpiry-year').removeClass('invalid');
        $('#cc-cardCVC').removeClass('invalid');
        $('#cc-cardNumber').removeClass('invalid');

        var values = {};
        values["_token"] = "{{ csrf_token() }}";
        $( "input[id^='cc-']" ).each(function(){
            values[$(this).attr('id').slice(3)] = $(this).val();
        });

        values.country = $('#cc-country').val();
        values.cardExpiryMonth = $('#cc-cardExpiry-month').val();
        values.cardExpiryYear = $('#cc-cardExpiry-year').val();
        values.mode = $("input[name='cancel-reason']:checked").val();

        values.numusers = $("#number_of_users").val();

        PostPayment(values);

    });

    $('#subscribe-submit').click(function () {
        if($('#agree').is(':checked')){
            if(parseInt($('#number_of_users').val()) > 0){
                $('#PaymentModel').modal('show');
            }else{
                $.dialog({
                    title: 'Oops!',
                    content: 'Please select a number of users greater than 0.'
                });
            }
        }else{
            $.dialog({
                title: 'Oops!',
                content: 'Please Read and Agree to the Subscription Agreement and Terms and Conditions'
            });
            var button = e.relatedTarget;
            e.stopPropagation();
        }
    });

    $('#done-option').hide();
    $('#time-option').hide();

    $( 'input[name="cancel-reason"]:radio' ).change(function () {
        switch($(this).val()) {
            case "time":
                if($('#sub-option').is(":visible")){
                    $('#sub-option').fadeOut("slow", function() {
                        $('#time-option').fadeIn("slow");
                    });
                }
                if($('#done-option').is(":visible")){
                    $('#done-option').fadeOut("slow", function() {
                        $('#time-option').fadeIn("slow");
                    });
                }
                break;
            case "subscribe":
                if($('#done-option').is(":visible")){
                    $('#done-option').fadeOut("slow", function() {
                        $('#sub-option').fadeIn("slow");
                    });
                }
                if($('#time-option').is(":visible")){
                    $('#time-option').fadeOut("slow", function() {
                        $('#sub-option').fadeIn("slow");
                    });
                }

                break;
            case "done":
                if($('#sub-option').is(":visible")){
                    $('#sub-option').fadeOut("slow", function() {
                        $('#done-option').fadeIn("slow");
                    });
                }
                if($('#time-option').is(":visible")){
                    $('#time-option').fadeOut("slow", function() {
                        $('#done-option').fadeIn("slow");
                    });
                }

                break;
        }

    });

    $('#number_of_users').keyup(function() {

        $numberofusers = $("#number_of_users").val();

        if ($numberofusers == 1) {
            $costperuser = 29;
        }

        if ($numberofusers == 2) {
            $costperuser = 19;
        }
        if ($numberofusers == 3) {
            $costperuser = 19;
        }

        if ($numberofusers == 4) {
            $costperuser = 16;
        }
        if ($numberofusers == 5) {
            $costperuser = 16;
        }
        if ($numberofusers == 6) {
            $costperuser = 16;
        }

        if ($numberofusers == 7) {
            $costperuser = 14;
        }
        if ($numberofusers == 8) {
            $costperuser = 14;
        }
        if ($numberofusers == 9) {
            $costperuser = 14;
        }
        if ($numberofusers >= 10) {
            $costperuser = 9.95;
        }

        $totalcost = $costperuser * $numberofusers;

        $('#price').val($totalcost.toFixed(2));

    });
});

function PostPayment(values){
    $("body").addClass("loading");
    posting = $.post("/Subscribe",values);

    posting.done(function( data ) {
        $("body").removeClass("loading");
        console.log(data);
        PostDone(data);
    });

    posting.fail(function() {
        $("body").removeClass("loading");
        alert('Lost contact with server');
    });
}



function PostDone(data){
    var res = data.split(":");

    switch(res[0]) {
        case "1":
            Approved(res[1]);
            break;
        case "2":
            Declined(res[1]);
            break;
        case "3":
            Error(res[1]);
            break;
        case "4":
            Invalid(res[1]);
            break;
        default:
            $.dialog({
                title: 'Oops...',
                content: 'Unknown Response'
            });
            console.log(data);
    }
}

function Approved(text){
    location.reload;
}

function Declined(text){
    $.dialog({
        title: 'Oops...',
        content: 'Transaction declined, please check with your card issuer.'
    });
}

function Error(text){
    $.dialog({
        title: 'Oops...',
        content: 'Error: ' + text
    });
}

function Invalid(text){
    switch(text) {
        case "invaliddate":
            $('#cc-cardExpiry-month').addClass('invalid');
            $('#cc-cardExpiry-year').addClass('invalid');
            $.dialog({
                title: 'Oops...',
                content: 'Please check your cards expiry date.'
            });
            break;
        case "invalidcvc":
            $('#cc-cardCVC').addClass('invalid');
            $.dialog({
                title: 'Oops...',
                content: 'Please check your cards cvv.'
            });
            break;
        case "invalidcardnumber":
            $('#cc-cardNumber').addClass('invalid');
            $.dialog({
                title: 'Oops...',
                content: 'Please check your card number.'
            });
            break;
        case "notninfo":
            $.dialog({
                title: 'Oops...',
                content: 'Unable to take payment at this time.'
            });
            break;
        default:
            $.dialog({
                title: 'Oops...',
                content: 'Unknown error, please refresh the page and try again.'
            });
    }
}
</script>
@include('javafunctions')

@include('footer')
