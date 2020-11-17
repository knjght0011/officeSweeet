@include('OS.Public.header')
<style>
    tr:nth-child(odd)		{ background-color:#eee; }
    tr:nth-child(even)		{ background-color:#fff; }
    th { background-color:lightblue; }
    
    body{
        margin-left: 10%;
        margin-right: 10%;
        margin-top: 50px;
        margin-bottom: 50px;
        height: 1vh;
    }

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
.invoice-status{
    margin: 20px 0 0 0;
    text-transform: uppercase;
    font-size: 24px;
    font-weight: bold;
}

.paid {
    color: #779500;
}
.overdue {
    color: red;
}
.due {
    color: #779500;
}

.button {
	display: inline-block;
	zoom: 1; /* zoom and *display = ie7 hack for display:inline-block */
	*display: inline;
	vertical-align: baseline;
	margin: 0 2px;
	outline: none;
	cursor: pointer;
	text-align: center;
	text-decoration: none;
	font: 14px/100% Arial, Helvetica, sans-serif;
	padding: .5em 2em .55em;
	text-shadow: 0 1px 1px rgba(0,0,0,.3);
	-webkit-border-radius: .5em; 
	-moz-border-radius: .5em;
	border-radius: .5em;
	-webkit-box-shadow: 0 1px 2px rgba(0,0,0,.2);
	-moz-box-shadow: 0 1px 2px rgba(0,0,0,.2);
	box-shadow: 0 1px 2px rgba(0,0,0,.2);
}
.button:hover {
	text-decoration: none;
}
.button:active {
	position: relative;
	top: 1px;
}

.red {
	color: #faddde;
	border: solid 1px #980c10;
	background: #d81b21;
	background: -webkit-gradient(linear, left top, left bottom, from(#ed1c24), to(#aa1317));
	background: -moz-linear-gradient(top,  #ed1c24,  #aa1317);
	filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#ed1c24', endColorstr='#aa1317');
}
.red:hover {
	background: #b61318;
	background: -webkit-gradient(linear, left top, left bottom, from(#c9151b), to(#a11115));
	background: -moz-linear-gradient(top,  #c9151b,  #a11115);
	filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#c9151b', endColorstr='#a11115');
}
.red:active {
	color: #de898c;
	background: -webkit-gradient(linear, left top, left bottom, from(#aa1317), to(#ed1c24));
	background: -moz-linear-gradient(top,  #aa1317,  #ed1c24);
	filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#aa1317', endColorstr='#ed1c24');
}
</style>

<div class="row" id="headerdiv">
    <div class="col-md-6" >   
        <table class="table">
            <tr>
                <td>Total Cost:</td><td>${{ $quote->getTotal() }}</td>
            </tr> 
            @if($quote->finalized === 1)
            <tr>
                <td>Total Payments:</td><td>${{ $quote->getTotalPayments() }}</td>
            </tr> 
            <tr>
                <td>Total Outstanding:</td><td>${{ $quote->getBallence() }}</td>
            </tr> 
            @endif
        </table>    
    </div>
    <div class="col-md-6 text-center" >
        @if($quote->finalized === 1)
        <div class="invoice-status">
            <span class="{{ $quote->getStatus() }}"> {{ $quote->getStatus() }} </span>
        </div>   
        @if(SettingHelper::GetSetting('transnational-username') != null)
            @if($quote->getStatus() === "overdue" || $quote->getStatus() === "due")
                <button type="button" class="button red" data-toggle="modal" data-target="#PaymentModel">Make Payment</button>
            @endif
        @endif
        @endif
    </div>
</div>


<div class="row">
<iframe style="width: 99%;;"id="PdfFrame" src="/Invoice/PDF/{{$token}}"></iframe>
</div>

@if($quote->finalized === 1)
@if(SettingHelper::GetSetting('transnational-username') != null)
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
                                    value="{{ $quote->contact->firstname }}"
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
                                    value="{{ $quote->contact->lastname }}"
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
                                    value="{{ $quote->contact->email }}"
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
                                    value="{{ $quote->contact->address->number }} {{ $quote->contact->address->address1 }}"
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
                                    value="{{ $quote->contact->address->address2 }}"
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
                                    value="{{ $quote->contact->address->city }}"
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
                                    value="{{ $quote->contact->address->state }}"
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
                                    value="{{ $quote->contact->address->zip }}"
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
                                <button id="submit" class="btn btn-success btn-lg btn-block" type="submit">Pay ${{ $quote->getBallence() }} Now</button>
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
@endif
@endif
<script>
$(document).ready(function() {
    $bodyheight = $(document).height();
    $headerdiveheight = $('#headerdiv').height();
    $math = $bodyheight - $headerdiveheight;
    $('#PdfFrame').css('height', $math - 101 + "px"); 

    $("#submit").click(function()
    {   
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
        values.quotetoken = "{{$token}}";
        values.amount = "{{ $quote->getBallence() }}";
                
        PostPayment(values);
    });
});
@if(SettingHelper::GetSetting('transnational-username') != null)
function PostPayment(values){
    $("body").addClass("loading");
    posting = $.post("/Payment",values);

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
@endif
</script>

@include('OS.Public.footer')