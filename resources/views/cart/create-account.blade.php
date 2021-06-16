@extends('layouts.app')

@section('content')
    <form id="create-account-form" method="post" onkeydown="return event.key != 'Enter';">
        <div class="container-fluid text-center">
            <div class="col-md-10 offset-md-1">
                <div class="stepwizard text-center">
                    <div class="stepwizard-row">
                        @include('includes.cart-stepper')
                    </div>
                </div>
                <br/>
                <br/>
            </div>
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="row">
                        <div class="col">
                            <h2 style="color: #0090cd; margin-bottom: 0; font-family: apex-sans-bold">
                                <span style="font-weight: normal; font-family: apex-sans-light">@lang('cart.WELCOME')</span> <span id="welcome-name"></span>
                            </h2>
                        </div>
                    </div>
                    <!-- <div class="row align-items-center">
                        <div class="col">
                            <div id="country-language" class="text-left">
                                <h2 class="subpage text-center">COUNTRY AND LANGUAGE INFORMATION</h2>
                                <div style="padding-bottom: 30px">
                                    <p style="font-size: 15px; font-weight: 500; color: #555555">Please select a country and a language for the new team member or customer that will be enrolled.</p>
                                </div>
                                <div>
                                    <div class="row form-group">
                                        <label class="text-md-right text-sm-left col-md-4 form-label" style="color: #3c763d">Country*</label>
                                        <div class="col-md-8">
                                            <select class="form-control" id="country" onchange="changeCountry(this)">
                                                
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="text-md-right text-sm-left col-md-4 form-label" style="color: #3c763d">Preferred Language*</label>
                                        <div class="col-md-8">
                                            <select class="form-control" id="language">
                                                <option value="en">English (en)</option>
                                                <option value="es">Espanol (es)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <div class="row">
                        <div class="col">
                            <div id="account-type" class="text-left">
                                <h2 class="subpage text-center">@lang('cart.SELECT AN ACCOUNT TYPE')</h2>
                                <div class="text-center">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="inlineCheckbox1" value="rc" name="user[usertype]" />
                                        <label class="form-check-label" for="inlineCheckbox1">Retail Customer</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="inlineCheckbox2" value="pc" name="user[usertype]" />
                                        <label class="form-check-label" for="inlineCheckbox2">Preferred Customer</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="inlineCheckbox3" value="dc" name="user[usertype]" />
                                        <label class="form-check-label" for="inlineCheckbox3">Distributor</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div id="sponsor-select" class="text-left">
                                <h2 class="subpage text-center"><b>@lang('cart.PERSONAL SPONSOR INFORMATION')</b></h2>
                                <div>
                                    <div class="row form-group">
                                        <label class="text-md-right text-sm-left col-md-4 form-label">@lang('cart.Sponsor Username')</label>
                                        <div class="col-md-6 col-9">
                                            <input class="form-control" type="text" name="user[sponsor_id]" onkeyup="sponsorIdKeyUp(this)" onkeydown="verifySponsor1(event)"/>
                                        </div>
                                        <div class="col-md-2 col-3">
                                            <button id="sponsor-search-button" type="button" class="btn btn-primary float-right float-md-none" disabled onclick="verifySponsor()"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <b><i>@lang('cart.Don\'t Have a Sponsor?')</i></b>
                                    <br/>
                                    <p>@lang('cart.Please contact the person who referred you to Ultrra for this information')?.</p>
                                </div>
                                <div id="selected-sponsor" style="display: none;">
                                    <div class="text-center">
                                        <h5><img src="" alt="user-image" style="width: 100px"/><br class="d-md-none"/><span></span></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="main-form" style="display: none;">
                        <div id="placement-sponsor-information" class="row">
                            <div class="col">
                                <div class="text-left">
                                    <h2 class="subpage text-center"><b>@lang('cart.PLACEMENT SPONSOR INFORMATION')</b></h2>
                                    <div style="padding-bottom: 30px">
                                        <p>@lang('cart.If you wish to select your position, click Manual and enter your placement ID number and proceed to select tracking center location Otherwise choose Automatic and Ultrra\'s system will make the assignment')</p>
                                    </div>
                                    <div class="text-center">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" id="Checkbox1" value="automatic" name="placement_type" checked onchange="handleOptionChange2(this)" />
                                            <label class="form-check-label" for="Checkbox1">@lang('cart.Automatic')</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" id="Checkbox2" value="manual" name="placement_type" onchange="handleOptionChange2(this)" />
                                            <label class="form-check-label" for="Checkbox2">@lang('cart.Manual')</label>
                                        </div>
                                    </div>
                                    <br/>
                                    <div id="placement-details" style="display: none;">
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label">@lang('cart.Placement ID')#</label>
                                            <div class="col-md-5 col-8">
                                                <input type="text" name="user[placement_search_id]" class="form-control" onkeyup="placementSearchKeyUp()"/>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="col-md-2 col-4">
                                                <button id="placement-search-button" type="button" class="btn btn-primary" disabled onclick="getPlacement()"><i class="fa fa-search"></i></button>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label">@lang('cart.Select Placement')</label>
                                            <div class="col-md-8">
                                                <select class="form-control" name="user[placement_id]" disabled onchange="selectPlacement()">

                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label">@lang('cart.Select Placement Side')</label>
                                            <div class="col-md-8">
                                                <select class="form-control" name="user[leg]" disabled onchange="selectPlacement()">
                                                    <option value="auto">Auto</option>
                                                    <option value="L">Left</option>
                                                    <option value="R">Right</option>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="placement-info-business-center-leg" class="text-center" style="padding-top: 30px">
                                        <b></b>
                                    </div>
                                    <div id="placement-info-business-center-leg1" class="text-center" style="padding-top: 30px; display:none;">
                                        <b></b>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div id="user-information" class="text-left">
                                    <h4 class="subpage text-center"><b>@lang('cart.Account Information')</b></h4>
                                    <br/>
                                    <div>
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label">@lang('cart.First Name') *</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="user[firstname]" required/>
                                                <input class="form-control" type="hidden" name="user[enrollment_type]" value="direct"/>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label">@lang('cart.Last Name') *</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="user[lastname]" required/>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label">@lang('cart.Joint') @lang('cart.First Name')</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="user[joint_firstname]" />
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label">@lang('cart.Joint') @lang('cart.Last Name')</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="user[joint_lastname]" />
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label">@lang('cart.Username') *</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="user[username]" required/>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label">@lang('cart.Date of Birth') *</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="date" name="user[dateofbirth]" max="{{date('Y-m-d', strtotime('-18 years'))}}" required>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div id="ssn_number" class="row form-group" style="display: none">
                                            <label class="text-md-right text-sm-left col-md-4 form-label">SSN/ITIN</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="user[ssn_number]" />
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label">@lang('cart.Mobile Phone') #*</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="user[phone]" required/>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                        <label class="text-md-right text-sm-left col-md-4 form-label">Preferred Language*</label>
                                        <div class="col-md-8">
                                            <select class="form-control" id="language">
                                                <option value="en">English (en)</option>
                                                <option value="es">Espanol (es)</option>
                                            </select>
                                        </div>
                                    </div>
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label">Email *</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="user[email]" required/>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div id="address-information" class="text-left">
                                    <h2 class="subpage text-center"><b>@lang('cart.MAIN ADDRESS')</b></h2>
                                    <br/>
                                    <div>
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label">@lang('cart.Address') 1 *</label>
                                            <div class="col-md-8">
                                                <input type="hidden" name="address[id]"/>
                                                <input class="form-control" type="text" name="address[address_1]" required/>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label">@lang('cart.Address') 2</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="address[address_2]"/>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label">@lang('cart.City') *</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="address[city]" required/>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label">@lang('cart.State') *</label>
                                            <div class="col-md-8">
                                                <select class="form-control" name="address[state_id]">

                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label">@lang('cart.Postal Code') *</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="address[postcode]" required/>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label" style="color: #3c763d">@lang('cart.Country') *</label>
                                            <div class="col-md-8">
                                                <select class="form-control" name="address[country_id]" disabled>
                                                     
                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div id="shipping-address-information" class="text-left">
                                    <h2 class="subpage text-center"><b>@lang('cart.SHIPPING ADDRESS')</b></h2>
                                    <div class="text-center">
                                        <input class="form-check-input" type="checkbox" id="shipping_same" onchange="shippingSame(this)"/>
                                        <label class="form-check-label" for="shipping_same">
                                            @lang('cart.Shipping address is the same as the main address').
                                        </label>
                                    </div>
                                    <br/>
                                    <div id="shipping-address-fields">
                                    <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label">@lang('cart.First Name') *</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="hidden" name="shipping_address[contact_name]" required/>
                                                <input class="form-control" type="text" name="shipping_address[firstname]" required/>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label">@lang('cart.Last Name') *</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="shipping_address[lastname]" required/>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label">@lang('cart.Address') 1 *</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="shipping_address[address_1]" required/>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label">@lang('cart.Address') 2</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="shipping_address[address_2]"/>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label">@lang('cart.City') *</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="shipping_address[city]" required/>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label">@lang('cart.State') *</label>
                                            <div class="col-md-8">
                                                <select class="form-control" name="shipping_address[state_id]">

                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label">@lang('cart.Mobile Phone') #*</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="shipping_address[phone]" required/>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label">@lang('cart.Postal Code') *</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="shipping_address[postcode]" required/>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="text-md-right text-sm-left col-md-4 form-label" style="color: #3c763d">@lang('cart.Country') *</label>
                                            <div class="col-md-8">
                                                <select class="form-control" name="shipping_address[country_id]" disabled>

                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="terms" style="display: none;">
                <div class="row">
                    <div class="col-md-10 offset-md-1 col-12">
                        <div class="">
                            <div class="detailsFull text-left">
                                <div><h5 style="margin-bottom: 20px"><b>@lang('cart.ACKNOWLEDGEMENT')</b></h5></div>
                                <div class="termsbox">
                                    <p class="heading"><strong>@lang('cart.TERMS & CONDITIONS')</strong></p>
                                    <p class="paragraph">The use of this site or any other site owned or maintained by Ultrra, a corporation organized and existing under the laws of
                                        the United States of America ("Company") and is governed by the policies, terms and conditions set forth below. Please
                                        read them carefully. Your use of this site signifies your acceptance of the terms and conditions set forth below. Your order
                                        placed on this site signifies your acceptance of the terms and conditions set forth below.
                                    </p>
                                    <br/>
                                    <p class="heading"><strong>1. Privacy & Security; Disclosure</strong></p>
                                    <p class="paragraph">
                                        Company's privacy policy may be viewed at http://www.ultrra.com
                                        <br/>
                                        Company reserves the right to modify its privacy policy in its reasonable discretion from time to time.
                                    </p>
                                    <br/>
                                    <p class="heading"><strong>2. Payment Methods</strong></p>
                                    <p class="paragraph">
                                        We accept U.S. issued credit and debit cards: <br/>
                                        Visa <br/>
                                        MasterCard <br/>
                                        Discover <br/>
                                        American Express <br/>
                                        <br/>
                                        When placing an order online, you will need: <br/>
                                        The address the card's statement is sent to (billing address).
                                        <br/>
                                        The card number and expiration date. <br/>
                                        The 3 or 4 digit code found only on the card (CVV2 code). <br/>
                                        Credit card orders can be placed online over our 128 bit Secure Socket layer encrypted connection.
                                        <br/>
                                        You are entering into a legally binding agreement with Ultrra. Ultrra.com is the official online store for Ultrra and its affiliates. Ultrra has the registered mailing address of:
                                        <br/>
                                        Ultrra <br/>
                                        10101 Southwest Freeway <br/>
                                        4th Floor <br/>
                                        Houston, TX 77074 USA <br/>
                                        Phone: 888.981.1711 <br/>
                                        Email: cc@ultrra.com <br/>
                                        Hours: 9am-5pm M-F <br/>

                                    </p>
                                    <br/>
                                    <p class="heading"><strong>3. Shipping Policies</strong></p>
                                    <p class="paragraph">
                                        Company ships orders via local courier and will call centers. Depending on product availability, orders are usually
                                        processed for shipment within 1 to 3 business days after custom manufacturing is completed. Custom manufacturing can
                                        take up to 10 business days. Accurate shipping address and phone number are required. Your signature may be required
                                        for delivery. Will call, hand deliveries and pickups are the responsibility of the customer.
                                    </p>
                                    <br/>
                                    <p class="heading"><strong>4. Delivery Confirmation</strong></p>
                                    <p class="paragraph">
                                        Because many instances may occur at your delivery address that are beyond our control, you agree that any delivery
                                        confirmation provided by the carrier is deemed sufficient proof of delivery to the card holder, even without a signature.
                                    </p>
                                    <br/>
                                    <p class="heading"><strong>5. Return and Cancellation Policy</strong></p>
                                    <p class="paragraph">
                                        All independent distributor package and product sales are final in signed agreement with section four (4) on your enrollment form. Any and all member purchases are non-refundable. The customer 30 day money back guarantee applies from the date of purchase. Customer must notify CustomerCare of order cancellation via email from the same email address on file for their account to cc@ultrra.com
                                        <br/>
                                        <br/>

                                        The following terms apply for all damaged items: <br/>
                                        You must notify Ultrra within 24 hours of package delivery of damages and obtain a Return Merchandize Authorization (RMA) number by contacting the customer support department at cc@Ultrra.com.<br/>
                                        <br/>
                                        An RMA number can ONLY be obtained by contacting the customer service department at cc@ultrra.com.
                                        <br/>
                                        Company cannot process packages marked "Return to Sender." <br/>
                                        Shipping charges and return shipping charges are not refundable in any case.
                                        <br/>
                                        Company is not responsible for lost or stolen items. Company is not responsible for returned items. We recommend all returned items to be sent using some type of delivery confirmation system to ensure proper delivery.
                                        <br/>

                                    </p>
                                    <br/>
                                    <p class="heading"><strong>6. Chargeback Policy</strong></p>
                                    <p class="paragraph">
                                        All references to a "chargeback" refer to a reversal of a credit/debit card charge placed on www.Ultrra.com. There is no
                                        reason for a chargeback to ever be filed. If a credit is due, simply contact us, and we will gladly issue it. Unnecessary
                                        chargebacks are theft and can be prosecuted, and will be prosecuted to the fullest extent of the law. If you feel that your
                                        credit/debit card was used fraudulently on www.ultrra.com, please contact us for immediate resolution.
                                        YOU AGREE THAT YOU WILL NOT CHARGEBACK ANY AMOUNTS CHARGED TO YOUR CREDIT/DEBIT CARD ON THIS
                                        SITE. IF YOU CHARGEBACK A CREDIT/DEBIT CARD CHARGE FOR A PAYMENT INITIATED BY YOU, YOU AGREE
                                        THAT THIS SITE MAY RECOVER THE AMOUNT OF THE CHARGEBACK IN ADDITION TO $200.00 USD BY ANY
                                        MEANS DEEMED NECESSARY, INCLUDING BUT NOT LIMITED TO RECHARGING YOUR CREDIT/DEBIT CARD OR
                                        HAVING THE AMOUNT RECOVERED THROUGH COLLECTIONS BY A COLLECTION AGENCY.

                                    </p>
                                    <br/>
                                    <p class="heading"><strong>7. Third Party Interactions</strong></p>
                                    <p class="paragraph">
                                        During use of Company Website, you may enter into correspondence with, purchase goods and/or services from, or
                                        participate in promotions of advertisers or sponsors showing their goods and/or services through the Website. Any such
                                        activity, and any terms, conditions, warranties or representations associated with such activity, are solely between you and
                                        the applicable third-party. Company shall have no liability, obligation or responsibility for any such correspondence,
                                        purchase or promotion between you and any such third party. Company does not endorse any sites on the Internet that are
                                        linked through its Website. Company provides these links to you only as a matter of convenience, and in no event shall
                                        Company be responsible for any content, products, or other materials on or available from such sites. Company provides
                                        products to you pursuant to the terms and conditions of this Agreement. You recognize, however, that certain third-party
                                        providers of ancillary software, hardware or services may require your agreement to additional or different license or other
                                        terms prior to your use of or access to such software, hardware or services.

                                    </p>
                                    <br/>
                                    <p class="heading"><strong>8. Ordering Disclaimer</strong></p>
                                    <p class="paragraph">
                                        Your electronic order confirmation, or any form of confirmation, does not signify our acceptance of your order. Company
                                        reserves the right to accept or deny shipment to anyone for any reason. Company reserves the right to require additional
                                        information before processing any order. If an order appears fraudulent in any way, Company reserves the right to cancel
                                        the order, notify the card holder and the proper authorities.

                                    </p>
                                    <br/>
                                    <p class="heading"><strong>9. Product Disclaimers; Disclaimers of Warranty</strong></p>
                                    <p class="paragraph">
                                        THE SERVICE AND ALL CONTENT IS PROVIDED TO YOU STRICTLY ON AN "AS IS" BASIS. ALL CONDITIONS,
                                        REPRESENTATIONS AND WARRANTIES, WHETHER EXPRESS, IMPLIED, STATUTORY OR OTHERWISE, INCLUDING,
                                        WITHOUT LIMITATION, ANY IMPLIED WARRANTY OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE,
                                        OR NONINFRINGEMENT OF THIRD PARTY RIGHTS, ARE HEREBY DISCLAIMED TO THE MAXIMUM EXTENT
                                        PERMITTED BY APPLICABLE LAW BY COMPANY.

                                    </p>
                                    <br/>
                                    <p class="heading"><strong>10. Limitation of Liability</strong></p>
                                    <p class="paragraph">
                                        IN NO EVENT SHALL EITHER PARTY BE LIABLE TO ANYONE FOR ANY INDIRECT, PUNITIVE, SPECIAL,
                                        EXEMPLARY, INCIDENTAL, CONSEQUENTIAL OR OTHER DAMAGES OF ANY TYPE OR KIND (INCLUDING LOSS OF
                                        DATA, REVENUE, PROFITS, USE OR OTHER ECONOMIC ADVANTAGE) ARISING OUT OF, OR IN ANY WAY
                                        CONNECTED WITH THIS SITE, INCLUDING BUT NOT LIMITED TO THE USE OR INABILITY TO USE THE SITE, OR
                                        FOR ANY CONTENT OBTAINED FROM OR THROUGH THE SITE, ANY INTERRUPTION, INACCURACY, ERROR OR
                                        OMISSION, REGARDLESS OF CAUSE IN ANY INFORMATION CONTAINED HEREIN, EVEN IF THE PARTY FROM WHICH
                                        DAMAGES ARE BEING SOUGHT HAS BEEN PREVIOUSLY ADVISED OF THE POSSIBILITY OF SUCH DAMAGES.
                                        Certain states and/or jurisdictions do not allow the exclusion of implied warranties or limitation of liability for incidental,
                                        consequential or certain other types of damages, so the exclusions set forth above may not apply to you.

                                    </p>
                                    <br/>
                                    <p class="heading"><strong>11. Notice</strong></p>
                                    <p class="paragraph">
                                        Company may give notice by means of a general notice on the www.ultrra.com Website, electronic mail to your e-mail
                                        address on record in Company's account information, or by written communication sent by first class mail or pre-paid post to
                                        your address on record in Company's account information. Such notice shall be deemed to have been given upon the
                                        expiration of 48 hours after mailing or posting (if sent by first class mail or pre-paid post) or 24 hours after sending (if sent by
                                        e-mail). You may give notice to Company (such notice shall be deemed given when received by Company) at any time by
                                        any of the following: letter delivered by nationally recognized overnight delivery service or first class postage prepaid mail
                                        to Company at the following address: <br/>
                                        <br/>
                                        Ultrra <br/>
                                        10101 Southwest Freeway <br/>
                                        4th Floor <br/>
                                        Houston, TX 77074 USA <br/>

                                    </p>
                                    <br/>
                                    <p class="heading"><strong>12. Modification to Terms</strong></p>
                                    <p class="paragraph">
                                        Company reserves the right to modify the terms and conditions of this Agreement or its policies relating to its products and
                                        services at any time, effective upon posting of an updated version of this Agreement on the www.ultrra.com Website. You
                                        are responsible for regularly reviewing this Agreement. Continued use of the Service after any such changes shall
                                        constitute your consent to such changes.
                                    </p>
                                    <br/>
                                    <p class="heading"><strong>13. General</strong></p>
                                    <p class="paragraph">
                                        With respect to U.S. Customers, this Agreement shall be governed by Texas law and controlling United States federal law,
                                        without regard to the choice or conflicts of law provisions of any jurisdiction, and any disputes, actions, claims or causes of
                                        action arising out of or in connection with this Agreement or the Service shall be subject to the exclusive jurisdiction of the
                                        state and federal courts located in Texas. If any provision of this Agreement is held by a court of competent jurisdiction to
                                        be invalid or unenforceable, then such provision(s) shall be construed, as nearly as possible, to reflect the intentions of the
                                        invalid or unenforceable provision(s), with all other provisions remaining in full force and effect. No joint venture,
                                        partnership, employment, or agency relationship exists between you and Company as a result of this agreement or use of
                                        this Website. The failure of Company to enforce any right or provision in this Agreement shall not constitute a waiver of
                                        such right or provision unless acknowledged and agreed to by Company in writing. This Agreement, together with any
                                        applicable Form and policies, comprises the entire agreement between you and Company and supersedes all prior or
                                        contemporaneous negotiations, discussions or agreements, whether written or oral, between the parties regarding the
                                        subject matter contained herein.

                                    </p>
                                    <br/>
                                    <p class="heading"><strong>14. Definitions</strong></p>
                                    <p class="paragraph">
                                        As used in this Agreement and in any Order Forms now or hereafter associated herewith: "Agreement" means these online
                                        terms of use, any Order Forms, whether written or submitted online via the www.ultrra.com Website(s), and any materials
                                        available on the Company Website(s) specifically incorporated by reference herein, as such materials, including the terms
                                        of this Agreement, may be updated by Company from time to time in its sole discretion; "Effective Date" means the earlier
                                        of either the date this Agreement is accepted by selecting the "I Accept" option presented on the screen after this
                                        Agreement is displayed, the Effective date on the subscription form or the date you begin purchasing products from this
                                        site; "Order Form(s)" means the form evidencing your purchase from this site and any subsequent order forms submitted
                                        online or in written form, each such Order Form to be incorporated into and to become a part of this Agreement (in the
                                        event of any conflict between the terms of this Agreement and the terms of any such Order Form, the terms of this
                                        Agreement shall prevail); "Company" means collectively Ultrra, a corporation organized and existing under the laws of the
                                        State of Texas doing business as "www.ultrra.com, together with its officers, directors, shareholders, employees, agents and
                                        affiliated companies.

                                    </p>
                                    <br/>
                                    <p class="heading"><strong>15. Questions or Additional Information</strong></p>
                                    <p class="paragraph">
                                        If you have questions regarding this Agreement or wish to obtain additional information, please send an e-mail to cc@Ultrra.com.
                                        <br/>
                                        <br/>

                                        © 2011-2020 Ultrra. All Rights Reserved.

                                    </p>

                                </div>
                                <br/>
                                <div class="text-center col-md-8 offset-md-2">
                                    <div>
                                        <div class="row form-check">
                                            <input class="form-check-input" type="checkbox" id="termsCheck" onchange="termsChecked(this)"/>
                                            <label class="form-check-label" for="termsCheck">
                                                @lang('cart.I agree to the terms and conditions of the Customer Agreement and the Policies and Procedures').
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br/>
            <br/>
            <div class="row">
                <div class="col-md-4 offset-md-4 text-center">
                    <div class="row">
                        <div class="col-md-6">
                            <button class="btn btn-outline-dark btn-block" onclick="previousPage();"><b>@lang('cart.BACK')</b></button>&nbsp;&nbsp;&nbsp;&nbsp;
                        </div>
                        <div class="col-md-6">
                            <button id="submit-button" class="btn btn-dark btn-block" type="submit" disabled><b>@lang('cart.CONTINUE')</b></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br/>
    </form>
    <style>
        h1, h2, h3, h4, h5, h6
        {
            margin: 0 0 20px !important;
            font-weight: 400 !important;
        }
        h2
        {
            font-size: 2rem !important;
        }
    </style>
@endsection
@push('js')
    <script>
        let user = localStorage.getItem('user');
        suffix = window.location.search;
        let enrollParams = new URLSearchParams(window.location.search);
       // console.log(suffix);
        if (localStorage.getItem('cart') === null)
        {
            window.location.href = '/www/products' + window.location.search;
        }
        window.addEventListener('load', function() {
            $.validator.addMethod("alpha", function(value, element) {
                return this.optional(element) || value == value.match(/^[a-zA-Z\s]+$/);
            }, 'Should only contain letters and spaces');
            $.validator.addMethod("alpha_numeric", function(value, element) {
                return this.optional(element) || value == value.match(/^[a-z0-9]*$/);
            }, 'Should only contain small letters and numbers');

            $('form').validate({
                onfocusout: function(element) {
                    this.element(element);
                },
                rules: {
                    "user[usertype]": {
                        required: true,
                    },
                    "user[placement_search_id]": {
                        required: true,
                    },
                    "user[placement_id]": {
                        required: true,
                    },
                    "user[firstname]": {
                        required: true,
                        alpha: true,
                    },
                    "user[lastname]": {
                        required: true,
                        alpha: true,
                    },
                    "user[joint_firstname]": {
                        alpha: true,
                    },
                    "user[joint_lastname]": {
                        alpha: true,
                    },
                    "user[username]": {
                        required: true,
                        alpha_numeric: true,
                        remote: '{{url("/www/check-username")}}'
                    },
                    "user[password]": {
                        required: true,
                        minlength: 6
                    },
                    "user[dateofbirth]": {
                        required: true,
                        date: true
                    },
                    "user[ssn_number]": {
                        digits: true
                    },
                    "user[phone]": {
                        required: true,
                        digits: true,
                        minlength: 10,
                        maxlength: 15
                    },
                    "user[email]": {
                        required: true,
                        email: true,
                        remote: '{{url("/www/check-email")}}'
                    },
                    "shipping_address[firstname]": {
                        required: true,
                        alpha: true,
                    },
                    "shipping_address[lastname]": {
                        required: true,
                        alpha: true,
                    },
                    "user[phone1]": {
                        required: true,
                        digits: true,
                        minlength: 10,
                        maxlength: 15
                    },
                    "address[address_1]": {
                        required: true,
                    },
                    "address[city]": {
                        required: true,
                    },
                    "address[postcode]": {
                        required: true,
                    },
                    "address[state_id]": {
                        required: true,
                    },
                    "address[country_id]": {
                        required: true,
                    },
                    "shipping_address[address_1]": {
                        required: true,
                    },
                    "shipping_address[city]": {
                        required: true,
                    },
                    "shipping_address[postcode]": {
                        required: true,
                    },
                    "shipping_address[state_id]": {
                        required: true,
                    },
                    "shipping_address[country_id]": {
                        required: true,
                    },
                },
                errorPlacement: function(error, element) {
                    if (element.attr("type") == "checkbox" || element.attr("type") == "radio")
                    {
                        if (element.parent().siblings().length > 0)
                        {
                            error.insertAfter(element.parent().siblings().last());
                        }
                        else
                        {
                            error.insertAfter(element.parent());
                        }
                    }
                    else
                    {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function(form, event) {
                    event.preventDefault();
                    let myform = $('#create-account-form');
                    let disabled = myform.find(':disabled').removeAttr('disabled');
                    let formData = myform.serializeArray();
                    disabled.attr('disabled','disabled');
                    let formObject = {};
                    $.each(formData, function(i, v) {
                        formObject[v.name] = v.value;
                    });
                    console.log(formObject);

                    let object = {user: {}, address: {}, shipping_address: {}};

                    formData.forEach((value) => {
                        let first_obj = {};
                        let key = value.name;
                        value = value.value;
                        if (key.includes('['))
                        {
                            let main_key = key.split('[');
                            let second_key = main_key[1].replace(']', '');
                            first_obj[second_key] = value;
                            object[main_key[0]][second_key] = value;
                        }
                        else
                        {
                            object[key] = value;
                        }
                    });
                    console.log(object);
                    console.log(object.user.placement_type);
                    object.shipping_address.contact_name = object.shipping_address.firstname + ' ' + object.shipping_address.lastname;
                    object.shipping_address.contact_number = object.shipping_address.phone;
                    object.user['enrollment_type'] = '';
                    object.user['user'] = 'avatar-big.png';
                    object.user['address1'] = object.address.address_1;
                    object.user['address2'] = object.address.address_2;
                    object.user['city'] = object.address.city;
                    object.user['postcode'] = object.address.postcode;
                    object.user['state_id'] = object.address.state_id;
                    object.user['country_id'] = object.address.country_id;
                    localStorage.setItem('user', JSON.stringify(object.user));
                    localStorage.setItem('address', JSON.stringify(object.address));
                    localStorage.setItem('shipping_address', JSON.stringify(object.shipping_address));
                    localStorage.setItem('placement_type', object.placement_type);
                    window.location.href = '{{url("/www/review")}}' + window.location.search;
                },
            });

            if (enrollParams.has('sponsor_id') && enrollParams.has('placement_user_id') && enrollParams.has('business_center_id') && enrollParams.has('leg'))
            {
                axios.get('/enroll/' + enrollParams.get('sponsor_id') + '/' + enrollParams.get('placement_user_id') + '/' + enrollParams.get('business_center_id') + '/' + enrollParams.get('leg')).then(response => {
                    $('[name="user[sponsor_id]"]').val(response.data.data.sponsor.username);
                    $('[value="manual"]').attr('checked', true);
                    $('[name="user[placement_search_id]"]').val(response.data.data.placement_info.username);
                    $('[name="user[leg]"] option').each(function (index, element) {
                        if ($(element).val() === enrollParams.get('leg'))
                        {
                            $(element).attr('selected', true);
                        }
                        else
                        {
                            $(element).removeAttr('selected');
                        }
                    });
                    $('#selected-sponsor img').attr('src', 'https://admin.ultrra.com/user_images/' + (response.data.data.sponsor.image !== null ? response.data.data.sponsor.image : 'avatar-big.png'));
                    $('#selected-sponsor span').text(response.data.data.sponsor.username + ', ' + response.data.data.sponsor.name);
                    $('#placement-info-business-center-leg b').text(response.data.data.business_center + ' - ' + enrollParams.get('leg'));
                    $('#placement-info-business-center-leg1 b').text(response.data.data.business_center + ' - ' + enrollParams.get('leg'));
                    $('#placement-details').show();
                    $('[name="user[sponsor_id]"]').attr('disabled', true);
                    $('[name="placement_type"]').attr('disabled', true);
                    $('[name="user[placement_search_id]"]').attr('disabled', true);
                    getPlacement();
                    $('[name="user[enrollment_type]"]').val('replicated');
                    $('#selected-sponsor').show();
                    $('#main-form').show();
                    $('#terms').show();
                });
            }

            if (localStorage.getItem('sponsor_input_value'))
            {
                $('[name="user[sponsor_id]"]').val(localStorage.getItem('sponsor_input_value'));
                verifySponsor();
            }

            if (enrollParams.has('username'))
            {
                $('[name="user[sponsor_id]"]').val(enrollParams.get('username'));
                $('[name="user[sponsor_id]"]').attr('disabled', true);
                console.log('gsd');
                $('[name="user[enrollment_type]"]').val('replicated');
                verifySponsor();
            }

            if (enrollParams.has('usertype'))
            {
                $('[value="'+enrollParams.get('usertype')+'"]').prop('checked', true);
                $('[name="user[usertype]"]').attr('disabled', true);
            }

            axios.get('/all-countries').then(response => {
                let options = '';
                response.data.data.map((country) => {
                    if (country.id == localStorage.getItem('products_country'))
                    {
                        options += '<option value="'+country.id+'" selected>'+country.name+'</option>';
                    }
                    else
                    {
                        options += '<option value="'+country.id+'">'+country.name+'</option>';
                    }
                });
                $('#country').html(options);
                $('[name="address[country_id]"]').html(options);
                $('[name="shipping_address[country_id]"]').html(options);
                axios.get('/states-by-country/' + localStorage.getItem('products_country')).then(response => {
                    let options = '';
                    response.data.data.map((state) => {
                        options += '<option value="'+state.id+'">'+state.name+'</option>';
                    });
                    $('[name="address[state_id]"]').html(options);
                    $('[name="shipping_address[state_id]"]').html(options);
                });
            });


            if (localStorage.getItem('usertype') !== null)
            {
                $('[name="user[usertype]"]').each(function (index, element) {
                    if ($(element).attr('value') == localStorage.getItem('usertype'))
                    {
                        $(element).prop('checked', true);
                    }
                });

                $('[name="user[usertype]"]').attr('disabled', true);

                if (localStorage.getItem('usertype') == 'rc')
                {
                    $('[name="placement_type"]').attr('disabled', true);
                }

            }

            if (localStorage.getItem('user') !== null)
            {
                Object.keys(JSON.parse(localStorage.getItem('user'))).forEach(function (value) {
                    $('[name="user['+value+']"]').val(JSON.parse(localStorage.getItem('user'))[value]);
                });
            }
            if (localStorage.getItem('address') !== null)
            {
                Object.keys(JSON.parse(localStorage.getItem('address'))).forEach(function (value) {
                    $('[name="address['+value+']"]').val(JSON.parse(localStorage.getItem('address'))[value]);
                });
            }
            if (localStorage.getItem('shipping_address') !== null)
            {
                Object.keys(JSON.parse(localStorage.getItem('shipping_address'))).forEach(function (value) {
                    $('[name="shipping_address['+value+']"]').val(JSON.parse(localStorage.getItem('shipping_address'))[value]);
                });
            }
            if (localStorage.getItem('placement_type') !== null)
            {
                $('[value="'+localStorage.getItem('placement_type')+'"]').prop('checked', true);
            }

            if (localStorage.getItem('shipping_same') == 'true')
            {
                $('#shipping_same').prop('checked', true);
                $('#shipping-address-fields').addClass('d-none');
                $('[name="shipping_address[contact_name]"]').val($('[name="shipping_address[firstname]"]').val() + ' ' + $('[name="shipping_address[lastname]"]').val());
            }
            else
            {
                $('#shipping_same').prop('checked', false);
                $('#shipping-address-fields').removeClass('d-none');
                $('[name="shipping_address[contact_name]"]').val('');
            }

            if ($('#termsCheck').is(':checked'))
            {
                $('#submit-button').removeAttr('disabled');
                $('#termsCheck').prop('checked', true);
            }
            else
            {
                $('#submit-button').attr('disabled', true);
            }
        });

        function changeCountry(element)
        {
            let country_id = $(element).find('option:selected');
            axios.get('/states-by-country/' + $(element).val()).then(response => {
                let options = '';
                response.data.data.map((state) => {
                    options += '<option id="'+state.id+'">'+state.name+'</option>';
                });
                $('[name="address[state_id]"]').html(options);
                $('[name="shipping_address[state_id]"]').html(options);
                $('[name="address[country_id]"] option').each(function (index, element) {
                    if ($(element).val() == $('#country').val())
                    {
                        $(element).attr('selected', true);
                    }
                    else
                    {
                        $(element).removeAttr('selected');
                    }
                });
                $('[name="shipping_address[country_id]"] option').each(function (index, element) {
                    if ($(element).val() == $('#country').val())
                    {
                        $(element).attr('selected', true);
                    }
                    else
                    {
                        $(element).removeAttr('selected');
                    }
                });
            });
        }

        function sponsorIdKeyUp(element)
        {
            if ($(element).val() != '')
            {
                $('#sponsor-search-button').removeAttr('disabled');
            }
            else
            {
                $('#sponsor-search-button').attr('disabled', true);
            }
        }

        function verifySponsor()
        {

            localStorage.setItem('sponsor_input_value', $('[name="user[sponsor_id]"]').val());
            $('#sponsor-search-button').attr('disabled', true);
            $('#sponsor-search-button i').removeClass('fa fa-search');
            $('#sponsor-search-button i').addClass('fa fa-spinner fa-spin');
            $('#sponsor-search-button').parent().siblings('.col-md-6').find('input').removeClass('is-invalid');
            $('#sponsor-search-button').parent().siblings('.col-md-6').find('input').removeClass('is-valid');
            $('#sponsor-search-button').parent().siblings('.col-md-6').find('div').remove();
            axios.get('/check-sponsor/' + $('[name="user[sponsor_id]"]').val()).then((response) => {
                if (response.data.data !== null)
                {
                    $('#selected-sponsor img').attr('src', 'https://admin.ultrra.com/user_images/' + response.data.data.image);
                    $('#selected-sponsor span').text(response.data.data.username + ', ' + response.data.data.name);
                    $('#placement-info-business-center-leg b').text(response.data.data.business_centers[0].business_center + ' - auto');
                    $('#placement-info-business-center-leg1 b').text(response.data.data.business_centers[0].business_center + ' - auto');
                    $('#selected-sponsor').show();
                    $('#main-form').show();
                    $('#terms').show();
                    $('#sponsor-search-button').parent().siblings('.col-md-6').find('input').addClass('is-valid');
                    $('#sponsor-search-button').parent().siblings('.col-md-6').find('input').removeClass('is-invalid');
                    $('#sponsor-search-button').parent().siblings('.col-md-6').find('div').remove();
                    $('#sponsor-search-button i').removeClass('fa fa-spinner fa-spin');
                    $('#sponsor-search-button i').addClass('fa fa-search');
                    $('#sponsor-search-button').removeAttr('disabled');
                    $('[name="user[placement_search_id]"]').val(response.data.data.username);
                    axios.get('/get-placement/' + $('[name="user[sponsor_id]"]').val()).then((response) => {
                        let options = '';
                        response.data.data.map((placement) => {
                            options += '<option value="'+placement.id+'">'+placement.business_center+'</option>';
                        });
                        $('[name="user[placement_id]"]').html(options);
                        $('[name="user[placement_id]"]').removeAttr('disabled');
                        $('[name="user[leg]"]').removeAttr('disabled');
                        $('#placement-search-button').removeAttr('disabled');
                        if (enrollParams.has('sponsor_id') && enrollParams.has('placement_user_id') && enrollParams.has('business_center_id') && enrollParams.has('leg'))
                        {
                            $('[name="user[placement_id]"]').attr('disabled', true);
                            $('[name="user[leg]"]').attr('disabled', true);
                        }
                    });
                }
                else
                {
                    $('#selected-sponsor').hide();
                    $('#main-form').hide();
                    $('#terms').hide();
                    $('#sponsor-search-button').parent().siblings('.col-md-6').find('input').removeClass('is-valid');
                    $('#sponsor-search-button').parent().siblings('.col-md-6').find('input').addClass('is-invalid');
                    $('#sponsor-search-button').parent().siblings('.col-md-6').append('<div class="invalid-feedback">Sponsor not found</div>');
                    $('#sponsor-search-button i').removeClass('fa fa-spinner fa-spin');
                    $('#sponsor-search-button i').addClass('fa fa-search');
                    $('#sponsor-search-button').removeAttr('disabled');
                }
                // if (localStorage.getItem('placement_info') !== null)
                // {
                //     let placement_info = JSON.parse(localStorage.getItem('placement_info'));
                //     $('[name="placement_type"]').prop('checked', false);
                //     this.setState({placementType: placement_info.placement_type});
                //     $('[value="'+placement_info.placement_type+'"]').prop('checked', true);
                //     this.setState({leg: placement_info.leg});
                //     axios.get('/get-business-center/' + placement_info.placement_id).then(response => {
                //         this.setState({placement_search_id: response.data.data.user.username});
                //         this.setState({placement_info: response.data.data});
                //         axios.get('/get-placement/' + response.data.data.user.username).then((response) => {
                //             this.setState({placements: response.data.data});
                //             $('#placement-search-button i').removeClass('fa fa-spinner fa-spin');
                //             $('#placement-search-button i').addClass('fa fa-search');
                //             $('#placement-search-button').removeAttr('disabled');
                //         });
                //     });
                // }
                // axios.get('/get-placement/' + response.data.data.business_centers[0].id + '/' + this.state.sponsor_id).then((response) => {
                //     this.setState({placement_info: response.data.data});
                //     this.setState({continue_button_disabled: false});
                // });
            });
        }

        function verifySponsor1(event)
        {
            console.log(event.keyCode);
            if (event.keyCode  == 13)
            {
                // $('#create-account-form').on('submit', function(event1){
                //     event1.preventDefault();
                // });
                localStorage.setItem('sponsor_input_value', $('[name="user[sponsor_id]"]').val());
                $('#sponsor-search-button').attr('disabled', true);
                $('#sponsor-search-button i').removeClass('fa fa-search');
                $('#sponsor-search-button i').addClass('fa fa-spinner fa-spin');
                $('#sponsor-search-button').parent().siblings('.col-md-6').find('input').removeClass('is-invalid');
                $('#sponsor-search-button').parent().siblings('.col-md-6').find('input').removeClass('is-valid');
                $('#sponsor-search-button').parent().siblings('.col-md-6').find('div').remove();
                axios.get('/check-sponsor/' + $('[name="user[sponsor_id]"]').val()).then((response) => {
                    if (response.data.data !== null)
                    {
                        $('#selected-sponsor img').attr('src', 'https://admin.ultrra.com/user_images/' + response.data.data.image);
                        $('#selected-sponsor span').text(response.data.data.username + ', ' + response.data.data.name);
                        $('#placement-info-business-center-leg b').text(response.data.data.business_centers[0].business_center + ' - auto');
                        $('#placement-info-business-center-leg1 b').text(response.data.data.business_centers[0].business_center + ' - auto');
                        $('#selected-sponsor').show();
                        $('#main-form').show();
                        $('#terms').show();
                        $('#sponsor-search-button').parent().siblings('.col-md-6').find('input').addClass('is-valid');
                        $('#sponsor-search-button').parent().siblings('.col-md-6').find('input').removeClass('is-invalid');
                        $('#sponsor-search-button').parent().siblings('.col-md-6').find('div').remove();
                        $('#sponsor-search-button i').removeClass('fa fa-spinner fa-spin');
                        $('#sponsor-search-button i').addClass('fa fa-search');
                        $('#sponsor-search-button').removeAttr('disabled');
                        $('[name="user[placement_search_id]"]').val(response.data.data.username);
                        axios.get('/get-placement/' + $('[name="user[sponsor_id]"]').val()).then((response) => {
                            let options = '';
                            response.data.data.map((placement) => {
                                options += '<option value="'+placement.id+'">'+placement.business_center+'</option>';
                            });
                            $('[name="user[placement_id]"]').html(options);
                            $('[name="user[placement_id]"]').removeAttr('disabled');
                            $('[name="user[leg]"]').removeAttr('disabled');
                            $('#placement-search-button').removeAttr('disabled');
                            if (enrollParams.has('sponsor_id') && enrollParams.has('placement_user_id') && enrollParams.has('business_center_id') && enrollParams.has('leg'))
                            {
                                $('[name="user[placement_id]"]').attr('disabled', true);
                                $('[name="user[leg]"]').attr('disabled', true);
                            }
                        });
                    }
                    else
                    {
                        $('#selected-sponsor').hide();
                        $('#main-form').hide();
                        $('#terms').hide();
                        $('#sponsor-search-button').parent().siblings('.col-md-6').find('input').removeClass('is-valid');
                        $('#sponsor-search-button').parent().siblings('.col-md-6').find('input').addClass('is-invalid');
                        $('#sponsor-search-button').parent().siblings('.col-md-6').append('<div class="invalid-feedback">Sponsor not found</div>');
                        $('#sponsor-search-button i').removeClass('fa fa-spinner fa-spin');
                        $('#sponsor-search-button i').addClass('fa fa-search');
                        $('#sponsor-search-button').removeAttr('disabled');
                    }
                    // if (localStorage.getItem('placement_info') !== null)
                    // {
                    //     let placement_info = JSON.parse(localStorage.getItem('placement_info'));
                    //     $('[name="placement_type"]').prop('checked', false);
                    //     this.setState({placementType: placement_info.placement_type});
                    //     $('[value="'+placement_info.placement_type+'"]').prop('checked', true);
                    //     this.setState({leg: placement_info.leg});
                    //     axios.get('/get-business-center/' + placement_info.placement_id).then(response => {
                    //         this.setState({placement_search_id: response.data.data.user.username});
                    //         this.setState({placement_info: response.data.data});
                    //         axios.get('/get-placement/' + response.data.data.user.username).then((response) => {
                    //             this.setState({placements: response.data.data});
                    //             $('#placement-search-button i').removeClass('fa fa-spinner fa-spin');
                    //             $('#placement-search-button i').addClass('fa fa-search');
                    //             $('#placement-search-button').removeAttr('disabled');
                    //         });
                    //     });
                    // }
                    // axios.get('/get-placement/' + response.data.data.business_centers[0].id + '/' + this.state.sponsor_id).then((response) => {
                    //     this.setState({placement_info: response.data.data});
                    //     this.setState({continue_button_disabled: false});
                    // });
                });
            }
        }

        function handleOptionChange2(element)
        {

            if ($('[name="placement_type"]:checked').val() == 'manual')
            {

                $('#placement-details').show();
                $('#placement-info-business-center-leg').hide();
                $('#placement-info-business-center-leg1').show();

            }
            else if ($('[name="placement_type"]:checked').val() == 'automatic')
            {

                $('#placement-details').hide();
                $('#placement-info-business-center-leg1').hide();
                $('#placement-info-business-center-leg').show();

            }
        }

        function placementSearchKeyUp()
        {
            if ($('[name="user[placement_search_id]"]').val() != '')
            {
                $('#placement-search-button').removeAttr('disabled');
            }
            else
            {
                $('#placement-search-button').attr('disabled', true);
            }
        }

        function getPlacement()
        {
            $('#placement-search-button').attr('disabled', true);
            $('#placement-search-button i').removeClass('fa fa-search');
            $('#placement-search-button i').addClass('fa fa-spinner fa-spin');
            axios.get('/get-placement/' + $('[name="user[placement_search_id]"]').val()).then((response) => {
                let options = '';
                response.data.data.map((placement) => {
                    options += '<option value="'+placement.id+'">'+placement.business_center+'</option>';
                });
                $('[name="user[placement_id]"]').html(options);
                $('[name="user[placement_id]"]').removeAttr('disabled');
                $('[name="user[leg]"]').removeAttr('disabled');
                $('#placement-search-button i').removeClass('fa fa-spinner fa-spin');
                $('#placement-search-button i').addClass('fa fa-search');
                $('#placement-search-button').removeAttr('disabled');
                $('#placement-info-business-center-leg b').html($('[name="user[placement_id]"] option:selected').text() + ' - ' + $('[name="user[leg]"]').val());
                $('#placement-info-business-center-leg1 b').html($('[name="user[placement_id]"] option:selected').text() + ' - ' + $('[name="user[leg]"]').val());
                if (enrollParams.has('sponsor_id') && enrollParams.has('placement_user_id') && enrollParams.has('business_center_id') && enrollParams.has('leg'))
                {
                    $('[name="user[placement_id]"]').attr('disabled', true);
                    $('[name="user[leg]"]').attr('disabled', true);
                }
            });
        }

        function selectPlacement()
        {
            $('#placement-info-business-center-leg b').html($('[name="user[placement_id]"] option:selected').text() + ' - ' + $('[name="user[leg]"]').val());
            $('#placement-info-business-center-leg1 b').html($('[name="user[placement_id]"] option:selected').text() + ' - ' + $('[name="user[leg]"]').val());
        }

        function termsChecked(element)
        {
            if($(element).is(':checked'))
            {
                $('#submit-button').removeAttr('disabled');
            }
            else
            {
                $('#submit-button').attr('disabled', true);
            }

        }

        function shippingSame(element)
        {
            $('#address-information input').each(function (index, input) {
                if ($(element).is(':checked'))
                {
                    $('[name="shipping_'+$(input).attr('name')+'"]').val($(input).val());
                }
                else
                {
                    $('[name="shipping_'+$(input).attr('name')+'"]').val('');
                }
            });

            $('[name="shipping_address[state_id]"] option').each(function (index, option) {
                if ($(element).is(':checked') && $(option).attr('value') == $('[name="address[state_id]"]').val())
                {
                    $(option).attr('selected', true);
                }
                else
                {
                    $(option).removeAttr('selected');
                    $('[name="shipping_address[state_id]"]').removeClass('d-none');
                }
            });

            if ($(element).is(':checked'))
            {
                $('[name="shipping_address[firstname]"]').val($('[name="user[firstname]"]').val());
                $('[name="shipping_address[lastname]"]').val($('[name="user[lastname]"]').val());
                $('#shipping-address-fields').addClass('d-none');
                $('[name="shipping_address[contact_name]"]').val($('[name="shipping_address[firstname]"]').val() + ' ' + $('[name="shipping_address[lastname]"]').val());
                localStorage.setItem('shipping_same', true);
            }
            else
            {
                $('[name="shipping_address[firstname]"]').val('');
                $('[name="shipping_address[lastname]"]').val('');
                $('#shipping-address-fields').removeClass('d-none');
                $('[name="shipping_address[contact_name]"]').val('');
                localStorage.setItem('shipping_same', false);
            }
        }

        function previousPage()
        {
            window.location.href = '/www/products' + window.location.search;
        }
    </script>
@endpush