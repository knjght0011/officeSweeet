@include('SignUp.header')

<body style="background-image: url('{{ url('/images/signupbackground2.jpg') }}');
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-size: 105% ;
        background-position: 0px 99px;">

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
.home-video{max-width:100%;height:auto}

.price_tab{margin-top:50px;padding:0 0px}
.price_tab>label{margin-bottom:0;border:solid 3px #ef5952;padding:2px;border-radius:22px;display:inline-block;background:#fff}
.price_tab label>span{display:block;padding:8px 40px;background:#ef5952;color:#fff;border-radius:20px}
.price_tab #tab_4+label{margin-right:0}

.tab_box{padding:15px;text-align:left;border:solid 1px #ccc;position:relative;margin-top:-2px;background-color: white;}
.tab_box:before{content:"";position:absolute;top:-1px;left:1.5%;min-width:97%;height:2px;background:#ef5952}

.tab_form h3{margin:0 0 10px;font-size:18px;color:#222;text-transform:uppercase}

button{margin:5px 0 15px;padding:8px 25px;border:none;box-shadow:none;display:inline-block;border-radius:20px;background:#ef5952;color:#fff;outline:none;text-transform:uppercase}

.modal-dialog {

    top: 150px;

}

</style>
<header>
    <div class="banner">
        </div><nav class="navbar navbar-inverse" id="my_nav"><div class="container"><div class="navbar-header"> <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button><div class="logo"> <a href="https://www.officesweeet.com"><img src="https://www.officesweeet.com/wp-content/themes/office-sweet/images/logo.png" alt="Logo" title="Logo"> </a></div></div><div class="collapse navbar-collapse" id="myNavbar"><ul class="nav navbar-nav"><li id="menu-item-19" class="drop_menu menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-19"><a href="https://www.officesweeet.com/system-features-details/">Features</a><ul class=""><li id="menu-item-123" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-123"><a href="https://www.officesweeet.com/system-features-details/">System Features Details</a></li><li id="menu-item-260" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-260"><a href="https://www.officesweeet.com/business-solution/">Business Solution</a></li></ul></li><li id="menu-item-18" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-18"><a href="https://www.officesweeet.com/about-us/">About Us</a></li><li id="menu-item-347" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-347"><a href="https://signup.officesweeet.com">Pricing</a></li><li id="menu-item-16" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-16"><a href="https://www.officesweeet.com/partners/">Partners</a></li><li id="menu-item-15" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-15"><a href="https://www.officesweeet.com/contact-us/">Contact Us</a></li><li id="menu-item-185" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-185"><a href="https://www.officesweeet.com/sanity-blog/">Sanity Blog</a></li></ul><ul class="nav navbar-nav navbar-right"><li><a href="callto:+1-813-444-5284">Call Us: +1-813-444-5284</a></li></ul></div></div></nav></header>

<secton >
<div class="col-xs-1">
</div>

<div class="col-xs-7" style=" margin-top: 150px;">
    <div class="row">



        <div class="col-xs-12 tab_box" style="text-align: center;">
            <h2 class="heading" style="text-align: center;">Try Office Sweeet for FREE…</h2>
            <h2 class="heading" style="text-align: center;">No obligation, No contract, No limits.</h2>
            <h3>(No credit card required. Cancel anytime.)</h3>
            <p>You will be granted access for 8 days to decide if OfficeSweeet is right for you. At the end of 8 days, you can decide to;</p>
            <p><b>1.</b> Subscribe to OfficeSweeet - <b>2.</b> Continue your trial for another 30 days - <b>OR 3.</b> Allow your trial to expire.</p>
            <button type="button" data-toggle="modal" data-target="#price-modal">
                <b>Pricing Information</b>
            </button>
        </div>

        <div class="col-md-12">
            <div>
                <iframe style="width: 100%; height: 500px; padding-left: 50px; padding-right: 50px;"  allowfullscreen="" frameborder="0" scrolling="no" src="https://www.youtube.com/embed/eH2A-M4G1jM?iv_load_policy=3&amp;modestbranding=1&amp;rel=0&amp;autohide=1&amp;playsinline=1&amp;autoplay=0" sandbox="allow-scripts allow-same-origin allow-presentation allow-popups"></iframe>
            </div>
        </div>

        {{--
        <div class="price_tab">
            <label for="tab_1"><span>Step 1 of 3</span></label>
            <div class="tab_box">
                <div class="tab_form" id="tab_1">
                    <h3>How many people in your office need access to OfficeSweeet? </h3>
                    <p>(You can change this later)</p>


                    <div class="form-group row">
                        <label for="number_of_users" class="col-sm-2 col-form-label">Number of Users *</label>
                        <div class="col-sm-3">
                            <input type="text" name="number_of_users" id="number_of_users" class="form-control"/>
                        </div>
                        <label for="price" class="col-sm-2 col-form-label">Total monthly cost *</label>
                        <div class="col-sm-3">
                            <input type="text" name="price" id="price" class="form-control"  readonly/>
                        </div>
                        <label for="price" class="col-sm-2 col-form-label">FOR THE ENTIRE SYSTEM</label>
                    </div>

                </div>
            </div>
        </div>


        <div class="wrap">
            <div class="cost_sec">
                <h3>SOLO OFFICE (1)</h3>
                <h5>COST PER USER : $29.95</h5>
                <p>USERS : 1</p>
            </div>
            <div class="cost_sec">
                <h3>SMALL BUSINESS (2-3)</h3>
                <h5>COST PER USER : $22.95</h5>
                <p>USERS : 2, 3</p>
            </div>
            <div class="cost_sec">
                <h3>MEDIUM BUSINESS (4-6)</h3>
                <h5>COST PER USER : $20.95</h5>
                <p>USERS : 4, 5, 6</p>
            </div>
            <div class="cost_sec">
                <h3>LARGE BUSINESS (7-9)</h3>
                <h5>COST PER USER : $17.95</h5>
                <p>USERS : 7, 8, 9</p>
            </div>
            <div class="cost_sec">
                <h3>ENTERPRISE <br>(10 or more)</h3>
                <h5>COST PER USER : $9.95</h5>
                <p>USERS : 10 or more</p>
                <img src="{{ url('/images/offer-tag.png') }}" alt="" class="offer_img" />
            </div>
        </div>

        <div class="clearfix"></div>
        --}}
        <div class="price_tab">
            <label for="tab_1"><span>Step 1 of 3</span></label>
            <div class="tab_box">
                <div class="tab_form" id="tab_1">
                    <h3>Let's get to know you</h3>

                    <div class="form-group row">
                        <label for="firstname" class="col-sm-2 col-form-label">First Name *</label>
                        <div class="col-sm-4">
                            <input type="text" name="firstname" id="firstname" class="form-control"/>
                        </div>
                        <label for="lastname" class="col-sm-2 col-form-label">Last Name *</label>
                        <div class="col-sm-4">
                            <input type="text" name="lastname" id="lastname" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-sm-2 col-form-label">E-Mail *</label>
                        <div class="col-sm-4">
                            <input type="text" name="email" id="email" class="form-control"/>
                        </div>
                        <label for="businessrole" class="col-sm-2 col-form-label">Business Role *</label>
                        <div class="col-sm-4">
                            <input type="text" name="businessrole" id="businessrole" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="phonenumber" class="col-sm-2 col-form-label">Telephone Number *</label>
                        <div class="col-sm-4">
                            <input type="text" name="phonenumber" id="phonenumber" class="form-control"/>
                        </div>

                    </div>


                    <div class="form-group row">
                        <label for="referred_by" class="col-sm-2 col-form-label">Referred By</label>
                        <div class="col-sm-4">
                            <select type="text" name="referred_by" id="referred_by" class="form-control">
                                <option value="internet-search">Internet Search</option>
                                <option value="social-media">Social Media</option>
                                <option value="print-advertisement">Print Advertisement</option>
                                <option value="officesweeet-user">OfficeSweeet User</option>
                            </select>
                        </div>
                        <label for="referred_name" class="col-sm-2 col-form-label">Referral Name</label>
                        <div class="col-sm-4">
                            <input type="text" name="referred_name" id="referred_name" class="form-control" />
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="price_tab">
            <label for="tab_2"><span>Step 2 of 3</span></label>
            <div class="tab_box">
                <div class="tab_form" id="tab_2">
                    <h3>Tell us about your company</h3>

                    <div class="form-group row">
                        <label for="company" class="col-sm-2 col-form-label">Company name *</label>
                        <div class="col-sm-4">
                            <input type="text" name="company" id="company" class="form-control"/>
                        </div>
                        <label for="type_business" class="col-sm-2 col-form-label">Type of Business *</label>
                        <div class="col-sm-4">
                            <input type="text" name="type_business" id="type_business" class="form-control"/>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="address1" class="col-sm-2 col-form-label">Address1 *</label>
                        <div class="col-sm-10">
                            <input type="text" name="address1" id="address1" class="form-control"/>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="city" class="col-sm-2 col-form-label">City *</label>
                        <div class="col-sm-4">
                            <input type="text" name="city" id="city" class="form-control"/>
                        </div>
                        <label for="state" class="col-sm-2 col-form-label">State *</label>
                        <div class="col-sm-4">
                            <input type="text" name="state" id="state" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="zip" class="col-sm-2 col-form-label">Zip *</label>
                        <div class="col-sm-4">
                            <input type="text" name="zip" id="zip" class="form-control"/>
                        </div>
                        <label for="state" class="col-sm-2 col-form-label">Country</label>
                        <div class="col-sm-4">
                            <select id="country" class="form-control">
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

                </div>
            </div>
        </div>

        <div class="price_tab" style="margin-bottom: 50px;">
            <label for="tab_3"><span>Step 3 of 3</span></label>
            <div class="tab_box">
                <div class="tab_form" id="tab_3">
                    <h3 style="text-align: center;">Welcome to OfficeSweeet</h3>
                    <p style="text-align: center;">Please verify that the information you’ve entered is accurate.  We do not use or sell your information as we adhere to a strict <a href="{{ url('/images/PrivacyPolicy.pdf') }}" target="_blank">Privacy Policy</a>. The information above will be used to create a unique and secure database, sub-domain, user name and password for you. <b>Add as many clients as you like.</b></p>
                    <p style="text-align: center;">If you have any questions, you can call us at <a href="tel:813-444-5284">813-444-5284</a> or email us at {!! PageElement::EmailLink("support@officesweeet.com") !!}.  Click the button below to Start Your FREE Trial!</p>

                    <div class="text-center"> <button type="button" data-toggle="modal" data-target="#confirm-modal">Let’s Get Started</button></div>

                    <p style="text-align: center;">Please check your inbox within the next 15 minutes for a Welcome email from OfficeSweeet.</p>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirm-modal">
    <div class="modal-dialog" role="document" >
        <div class="modal-content" >
            <div class="modal-header">
                <h5 class="modal-title" style="width: 50%; float: left;">Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-bottom: 0px;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <tr>
                        <td>First Name</td>
                        <td id="confirm-firstname"></td>
                    </tr>
                    <tr>
                        <td>Last Name</td>
                        <td id="confirm-lastname"></td>
                    </tr>
                    <tr>
                        <td>E-Mail</td>
                        <td id="confirm-email"></td>
                    </tr>
                    <tr>
                        <td>Business Role</td>
                        <td id="confirm-businessrole"></td>
                    </tr>
                    <tr>
                        <td>Telephone Number</td>
                        <td id="confirm-phonenumber"></td>
                    </tr>
                    <tr>
                        <td>Referred By</td>
                        <td id="confirm-referred_by"></td>
                    </tr>
                    <tr>
                        <td>Referral Name</td>
                        <td id="confirm-referred_name"></td>
                    </tr>
                    <tr>
                        <td>Company name</td>
                        <td id="confirm-company"></td>
                    </tr>
                    <tr>
                        <td>Type of Business</td>
                        <td id="confirm-type_business"></td>
                    </tr>
                    <tr>
                        <td>Address1</td>
                        <td id="confirm-address1"></td>
                    </tr>
                    <tr>
                        <td>City</td>
                        <td id="confirm-city"></td>
                    </tr>
                    <tr>
                        <td>State</td>
                        <td id="confirm-state"></td>
                    </tr>
                    <tr>
                        <td>Zip</td>
                        <td id="confirm-zip"></td>
                    </tr>
                    <tr>
                        <td>Country</td>
                        <td id="confirm-country"></td>
                    </tr>

                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="submit">Submit</button>
            </div>
        </div>
    </div>
</div>

<!--<div class="fixed-img"> <img src="https://www.officesweeet.com/wp-content/themes/office-sweet/images/side_img.png" alt="" class="img-responsive"></div>-->


<div class="modal fade" id="price-modal">
    <div class="modal-dialog" role="document" style="width: 1000px;">
        <div class="modal-content" style="width: 1000px;">
            <div class="modal-header">
                <h5 class="modal-title" style="width: 50%; float: left;">Pricing Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-bottom: 0px;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" style="height: 240px; width: 1000px;">

                <div class="wrap">
                    <div class="cost_sec">
                        <h3>SOLO OFFICE (1)</h3>
                        <h5>COST PER USER : $29</h5>
                        <p>USERS : 1</p>
                    </div>
                    <div class="cost_sec">
                        <h3>SMALL BUSINESS (2-3)</h3>
                        <h5>COST PER USER : $19</h5>
                        <p>USERS : 2, 3</p>
                    </div>
                    <div class="cost_sec">
                        <h3>MEDIUM BUSINESS (4-6)</h3>
                        <h5>COST PER USER : $16</h5>
                        <p>USERS : 4, 5, 6</p>
                    </div>
                    <div class="cost_sec">
                        <h3>LARGE BUSINESS (7-9)</h3>
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

            </div>
            <div class="modal-footer">
                <p style="text-align: center; margin-top: 10px;">You may add or upload unlimited Prospects, Clients, Vendors and Employees.</p>
            </div>
        </div>
    </div>
</div>

    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
            var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
            s1.async=true;
            s1.src='https://embed.tawk.to/599308111b1bed47ceb04bd3/default';
            s1.charset='UTF-8';
            s1.setAttribute('crossorigin','*');
            s0.parentNode.insertBefore(s1,s0);
        })();
    </script>
    <!--End of Tawk.to Script-->

<div class="modalload"><!-- Place at bottom of page --></div>

<script type="text/javascript">
$(document).ready(function(){
    $('input').on('keyup', function(e){ $(this).removeClass('invalid');  });


    $('#confirm-modal').on('show.bs.modal', function (event) {

        $valid = true;

        var values = {};
        values["_token"] = "{{ csrf_token() }}";
        $( "input" ).each(function(){

            $(this).removeClass('invalid');

            if($(this).attr('id') != "referred_name"){
                if($(this).val() === ""){
                    $(this).addClass('invalid');
                    $valid = false;
                }
            }else{
                if($('#referred_by').val() === "officesweeet-user"){
                    if($(this).val() === ""){
                        $(this).addClass('invalid');
                        $valid = false;
                    }
                }
            }

            values[$(this).attr('id')] = $(this).val();
        });

        if($valid){
            $( "select" ).each(function(){
                values[$(this).attr('id')] = $(this).val();
            });

            $.each(values, function( index, value ) {
                $('#confirm-'+index).html(value);
            });
        }else{
            $.dialog({
                title: 'Oops...',
                content: 'It looks like you haven\'t filled in all the information, Please correct any boxes with a red outline.'
            });
            event.stopPropegation();
        }
    });

    $('#referred_name').attr('disabled', 'disabled');
    $('#referred_by').change(function(){
        if($(this).val() === "officesweeet-user"){
            $('#referred_name').removeAttr('disabled');
        }else{
            $('#referred_name').attr('disabled', 'disabled');
            $('#referred_name').val('');
        }
    });

    $('#submit').click(function(){
        var values = {};
        values["_token"] = "{{ csrf_token() }}";
        $( "input" ).each(function(){
            values[$(this).attr('id')] = $(this).val();
        });

        $( "select" ).each(function(){
            values[$(this).attr('id')] = $(this).val();
        });

        $("body").addClass("loading");
        posting = $.post("/", values);

        posting.done(function( data ) {

            $("body").removeClass("loading");
            if ($.isPlainObject(data))
            {
                $text = "";
                $.each(data, function( index, value ) {
                    if(index === "subdomain"){
                        $text = $text + "Your Company Name is not long enough<br>";
                    }else{
                        $text = $text + value + "<br>";
                    }
                });
                $.dialog({
                    title: 'Oops...',
                    content: $text
                });
            }
            else
            {
                $.dialog({
                    title: 'Success!',
                    content: 'Please check your inbox within the next 15 minutes for a Welcome email from OfficeSweeet.'
                });
                $(':input').val('');
            }
        });

        posting.fail(function() {
            $("body").removeClass("loading");
            $.dialog({
                title: 'Oops...',
                content: 'Lost contact with server'
            });
        });
    });

    {{--
    $('#number_of_users').change(function() {

        $numberofusers = $("#number_of_users").val();

        if ($numberofusers == 1) {
            $costperuser = 29.95;
        }

        if ($numberofusers == 2) {
            $costperuser = 22.95;
        }
        if ($numberofusers == 3) {
            $costperuser = 22.95;
        }

        if ($numberofusers == 4) {
            $costperuser = 20.95;
        }
        if ($numberofusers == 5) {
            $costperuser = 20.95;
        }
        if ($numberofusers == 6) {
            $costperuser = 20.95;
        }

        if ($numberofusers == 7) {
            $costperuser = 17.95;
        }
        if ($numberofusers == 8) {
            $costperuser = 17.95;
        }
        if ($numberofusers == 9) {
            $costperuser = 17.95;
        }
        if ($numberofusers >= 10) {
            $costperuser = 9.95;
        }

        $totalcost = $costperuser * $numberofusers;

        $('#price').val($totalcost.toFixed(2));

    });
    --}}
});

</script>
</secton>
@include('SignUp.footer')
