@extends('layouts.app')

@section('content')
    <div class="container">
        <form id="review-order" method="post">
            <div class="col-md-12">
                <div class="stepwizard text-center">
                    <div class="stepwizard-row">
                        @include('includes.cart-stepper')
                    </div>
                </div>
                <br/>
                <br/>
            </div>
            <div id="review-rows" class="row">
                <div class="col-md-12 col-10 offset-1 offset-md-0">
                    <div class="row">
                        <div class="detailsfull text-left">
                            <h3>
                                SPONSOR <span class="details" style="font-weight: lighter">Details</span>
                            </h3>
                            <span class="editdetails">
                        <a href="{{url('/www/create-account')}}">
                            <i class="fa fa-pencil-alt" style="color: #0090cd; font-size: 18px"></i>&nbsp;
                            <span>Edit Details</span>
                        </a>
                        </span>
                            <br/>
                            <div class="col-md-12">
                                <div class="row">
                                    <div>
                                        <h6 class="mb-0">Sponsor Info</h6>
                                        <span id="sponsor-information"></span>
                                        <br/><br/>
                                    </div>
                                    <div class="ml-3">
                                        <h6 class="mb-0">Placement Info</h6>
                                        <span id="placement-information"></span><br/>
                                        leg-<span id="placement-leg"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="detailsfull text-left">
                            <h3>
                                ACTIVATION <span class="details" style="font-weight: lighter">Details</span>
                            </h3>
                            <span class="editdetails">
                            <a href="{{url('/www/products')}}">
                                <i class="fa fa-pencil-alt" style="color: #0090cd; font-size: 18px"></i>&nbsp;
                                <span>Edit Details</span>
                            </a>
                        </span>
                            <br/>
                            <div>
                                <h6>ORDER OPTIONS</h6>
                                <table id="order-details-table" class="table table-responsive-sm radio outline orderDetails-table">
                                    <thead class="gray">
                                    <tr>
                                        <th width="50%">Description</th>
                                        <th>QV</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                                <div class="row">
                                    <div class="col-md-8">

                                    </div>
                                    <div class="col-md-4">
                                        <div class="totals">
                                            <div class="totals-item">
                                                <label class="text-md-right text-left">Total QV</label>
                                                <div class="totals-value" id="cart-qvTotal"></div>
                                            </div>

                                            <div class="totals-item">
                                                <label class="text-md-right text-left">Subtotal</label>
                                                <div class="totals-value" id="cart-subtotal"></div>
                                            </div>
                                            <div class="totals-item">
                                                <label class="text-md-right text-left">Tax</label>
                                                <div class="totals-value" id="cart-tax"></div>
                                            </div>
                                            <div class="totals-item">
                                                <label class="text-md-right text-left">Shipping</label>
                                                <div class="totals-value" id="cart-shipping"></div>
                                            </div>
                                            <div class="totals-item">
                                                <label class="text-md-right text-left">Handling</label>
                                                <div class="totals-value" id="cart-handling"></div>
                                            </div>

                                            <div class="totals-item totals-item-total">
                                                <label class="text-md-right text-left">Grand Total</label>
                                                <div class="totals-value" id="cart-total"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="detailsfull payment-box text-left">
                            <h3 class="details-title text-uppercase">
                                Shipping <span class="details" style="font-weight: lighter">Details</span>
                            </h3>
                            <span class="editdetails">
                            <a href="javascript:void(0)">
                                <i class="fa fa-pencil-alt" style="color: #0090cd; font-size: 18px"></i>&nbsp;
                                <span>Edit Details</span>
                            </a>
                        </span>
                            <br/>
                            <div class="row">
                                <div class="col-md-3">
                                    <h6>SHIPPING ADDRESS</h6>
                                    <address id="shipping-address">
                                        <div>

                                        </div>
                                    </address>
                                </div>
                                <div class="col-md-4">
                                    <h6>SHIPPING METHOD</h6>
                                    <div id="shipping-methods-div">
                                        <div>
                                            <select class="form-control" name="shipping_method" id="shipping_method" onchange="shippingMethodChange(this);">

                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="detailsfull text-left">
                            <h3 class="details-title">
                                PAYMENT <span class="details">Details</span>
                            </h3>
                            <br/>
                            <div style="display: flex; box-sizing: border-box; flex-direction: column">
                                <div class="payment-box" style="box-sizing: border-box; flex-direction: row">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-12">
                                            <div class="form-group mb-2">
                                                <label for="payment_method">Payment Method <span>*</span></label>
                                                <select id="payment_method" name="payment_method" class="form-control" onchange="onPaymentMethodChange(this)">
                                                    <option value="credit_card">Credit Card (CC) #</option>
                                                    <option value="cod">Cash/Manual</option>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="hide-on-cod">
                                                <div class="form-group">
                                                    <img src="{{asset('/images/navigation/cards-removebg-preview.png')}}" alt="image" style="width: 190px"/>
                                                </div>
                                                <div class="form-group mb-2">
                                                    <input type="hidden" id="user_id" name="user_id"/>
                                                    <input type="text" class="form-control" id="card_name" name="card_name" placeholder="Name on Card"/>
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                                <div class="form-group mb-2">
                                                    <input type="text" class="form-control" id="card_number" name="card_number" placeholder="Credit Card #"/>
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                                <div class="form-group mb-2">
                                                    <input type="text" class="form-control" id="cvv" name="cvv" placeholder="Verification Code"/>
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                                <div class="form-group mb-2">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <select class="form-control" name="expiry_month" id="expiry_month" onchange="onCardDateChange(this)">
                                                                @for($i = 1; $i <= 12; $i++)
                                                                    <option value="{{$i < 10 ? '0'.$i : $i}}">{{date('F', strtotime('2021-'.($i < 10 ? '0'.$i : $i).'-01'))}}</option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                        <div class="col-6">
                                                            <select class="form-control" name="expiry_year" id="expiry_year" onchange="onCardDateChange(this)">
                                                                @foreach(range(date('Y'), (int)date('Y') + 10) as $year)
                                                                    <option value="{{$year}}">{{$year}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-12">
                                                            <input type="hidden" id="card_expiration" name="card_expiration"/>
                                                        </div>
                                                        <div id="invalid-date" class="col-12 invalid-feedback"></div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-lg-4 col-md-4 col-12 hide-on-cod">
                                            <div class="form-group form-check pl-3 mb-2">
                                                <span class="d-none d-md-none">&nbsp;&nbsp;&nbsp;&nbsp;</span><input class="form-check-input" type="checkbox" id="shipping_same_checkbox" name="billing_same" onchange="billingSameChecked(this)"/>
                                                <label class="form-check-label" for="shipping_same_checkbox">My billing address is the same as my shipping address</label>
                                            </div>
                                            <div class="form-group mb-2">
                                                <input type="hidden" name="billing_contact_name"/>
                                                <input type="hidden" name="billing_contact_number"/>
                                                <input class="form-control" type="text" name="billing_address_1" placeholder="Address 1"/>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="form-group mb-2">
                                                <input class="form-control" type="text" name="billing_address_2" placeholder="Address 2"/>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="form-group mb-2">
                                                <input class="form-control" type="text" name="billing_city" placeholder="City"/>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="form-group mb-2">
                                                <input class="form-control" type="text" name="billing_postcode" placeholder="ZIP Code"/>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="form-group mb-2">
                                                <select class="form-control" name="billing_country_id" onchange="changeCountry(event, 'billing')">

                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="form-group">
                                                <select class="form-control" name="billing_state_id">

                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-4 col-12">
                                            <div class="form-group mb-2">
                                                <textarea class="form-control" id="notes" name="notes" rows="4" maxlength="500" onkeyup="wordsCheck(this)"></textarea>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="col-12">
                                                <p style="color: #939391; line-height: 1.42857143; font-family: 'Montserrat-Regular'">
                                                    Your IP address has been logged as <span id="ip-address"></span> for
                                                    confirmation and security purposes. You are now authorized to place this
                                                    order using this payment method.
                                                </p>
                                            </div>
                                            <div class="form-group form-check">
                                                <input type="checkbox" class="form-check-input" id="termsCheck" onchange="termsChecked(this)"/>
                                                <label class="form-check-label" for="termsCheck" style="font-size: 14px">I authorize Ultrra to charge the amount shown for the order above to the payment information I have entered.</label>
                                            </div>
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
                <div class="text-center col-md-2 offset-md-5">
                    <div class="col-md-12">
                        <button id="submit-button" class="btn btn-dark btn-block" type="submit" disabled><b>PAY NOW</b></button>
                    </div>
                </div>
                <div class="col-md-12">
                    <div id="error-submit-button" class="text-danger text-center"></div>
                </div>
                <br>
                <br>
            </div>
            <div class="row">
                <div id="loader" class="text-center" style="display: none; position: relative; background: #ffffff91; padding-top: 25%">
                    <i class="fa fa-spinner fa-spin fa-4x"></i>
                </div>
            </div>
        </form>
    </div>
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

        p
        {
            margin-bottom: 0 !important;
        }

        body
        {
            font-size: 12px !important;
        }
        label.error
        {
            color: red !important;
        }
    </style>
@endsection
@push('js')
    <script>
        if (localStorage.getItem('user') === null || localStorage.getItem('address') === null || localStorage.getItem('shipping_address') === null || localStorage.getItem('cart') === null)
        {
            if ('{{auth()->check()}}' == 1)
            {
                window.location.href = '/www/shipping-address';
            }
            else
            {
                window.location.href = '/www/create-account';
            }
        }
        let user = JSON.parse(localStorage.getItem('user'));
        let address = JSON.parse(localStorage.getItem('address'));
        address['contact_name'] = user.firstname + ' ' + user.lastname;
        address['contact_number'] = user.phone;
        let shipping_address = JSON.parse(localStorage.getItem('shipping_address'));
        let placement_info = JSON.parse(localStorage.getItem('placement_info'));
        cart = JSON.parse(localStorage.getItem('cart'));
        let totalQV = 0;
        let shippingTotal = 0;
        let taxTotal = 0;
        let handlingCharges = 0;
        let subTotal = 0;
        let taxFreeSubTotal = 0;
        let fast_shipping_price = 0;
        let regular_shipping_price = 0;
        let shippingMethod = '';
        let is_membership_only = 0;
        let country_id = parseInt(localStorage.getItem('products_country'));
        let countries = [];
        window.addEventListener('load', function() {
            $('#loader').show();
            let review_rows_offset = $('#review-rows').offset();
            $('#loader').css({'width': $('#review-rows').width(), 'height': '1416px', 'margin-top': '-134.5%'});
            Payment.formatCardNumber($('[name="card_number"]'));
            Payment.formatCardCVC($('[name="cvv"]'));
            $('#card_expiration').val($('#expiry_month').val() + ' / ' + $('#expiry_year').val());
            $.validator.addMethod("alpha", function(value, element) {
                return this.optional(element) || value == value.match(/^[a-zA-Z\s]+$/);
            }, 'Should only contain letters and spaces');
            $.validator.addMethod("expiry_date", function(value, element) {
                let date = value.split(' / ');
                return Payment.fns.validateCardExpiry(date[0], date[1]);
            }, 'Card expiry is invalid');
            $('#review-order').validate({
                ignore: [],
                onfocusout: function(element) {
                    this.element(element);
                },
                rules: {
                    "card_name": {
                        required: true,
                        alpha: true,
                    },
                    "card_number": {
                        required: true,
                    },
                    "cvv": {
                        required: true,
                        digits: true,
                        maxlength: 4
                    },
                    "card_expiration": {
                        required: true,
                        expiry_date: true
                    },
                    "billing_address_1": {
                        required: true,
                    },
                    "billing_city": {
                        required: true,
                    },
                    "billing_postcode": {
                        required: true,
                    },
                    "billing_state_id": {
                        required: true,
                    },
                    "billing_country_id": {
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
                    let myform = $('#review-order');
                    let formData = myform.serializeArray();
                    let formObject = {};
                    $.each(formData, function(i, v) {
                        formObject[v.name] = v.value;
                    });

                    let billing_address = {};
                    Object.keys(formObject).map((key) => {
                        if (key.includes('billing_') && key !== 'billing_same')
                        {
                            billing_address[key.replace('billing_', '')] = formObject[key];
                            delete formObject[key];
                        }
                    });
                    formObject['billing_address'] = billing_address;
                    console.log(formObject);

                    let placement_info;
                    if (user.hasOwnProperty('id'))
                    {
                        placement_info =  JSON.parse(localStorage.getItem('placement_info'))
                    }
                    else
                    {
                        placement_info = {
                            placement_id: user.placement_id,
                            leg: user.leg,
                            placement_type: localStorage.getItem('placement_type')
                        };
                    }

                    let data = {
                        placement_info: placement_info,
                        address: address,
                        shipping_address: shipping_address,
                        user: user,
                        credit_card: formObject,
                        cart: cart,
                        cvv: formObject.payment_method == 'credit_card' ? formObject.cvv : null,
                        payment_method: formObject.payment_method,
                        notes: $('#notes').val()
                    };
                    $('#submit-button br').remove();
                    $('#submit-button small').remove();
                    $('#submit-button').attr('disabled', true);
                    $('#submit-button').append('&nbsp;<i class="fa fa-spinner fa-spin"></i>');
                    axios.post('/place-order-new', data).then((response) => {
                        if (response.data.status === 300)
                        {
                            $('#error-submit-button').text(response.data.message);
                            $('#submit-button').removeAttr('disabled');
                            $('#submit-button i').remove();
                        }
                        else if (response.data.status === 2)
                        {
                            $('#error-submit-button').text(response.data.message);
                            $('#submit-button').removeAttr('disabled');
                            $('#submit-button i').remove();
                        }
                        else if (response.data.status === 200)
                        {
                            localStorage.clear();
                            window.location.href = '/www/invoice/' + response.data.data.id;
                            // this.props.history.push('/www/invoice/' + response.data.data.id);
                        }
                    }).catch((error) => {
                        console.log(error);
                        $('#error-submit-button').text('Something went wrong');
                        $('#submit-button').removeAttr('disabled');
                        $('#submit-button i').remove();
                    });

                },
            });

            axios.get('all-countries').then((response) => {
                let country_options = '';
                countries = response.data.data;
                response.data.data.map((country) => {
                    country_options += '<option value="'+country.id+'">'+country.name+'</option>';
                });
                $('[name="billing_country_id"]').html(country_options);
                let shipping_country = response.data.data.find(country => country.id == shipping_address.country_id);

                axios.get('states-by-country/' + countries[0].id).then((response) => {
                    let state_options = '';
                    response.data.data.map((state) => {
                        state_options += '<option value="'+state.id+'">'+state.name+'</option>';
                    });
                    $('[name="billing_state_id"]').html(state_options);
                });

                axios.get('states-by-country/' + shipping_address.country_id).then((response) => {
                    let shipping_state = response.data.data.find(state => state.id == shipping_address.state_id);
                    let shipping_address_html = shipping_address.address_1 + '<br/>' +
                        shipping_address.address_2 + '<br/>' +
                        shipping_address.city + '<br/>' +
                        shipping_address.postcode + ', ' + shipping_state.name + '<br/>' +
                        shipping_country.name;

                    let state_options = '';

                    $('#shipping-address div').html('<address>' + shipping_address_html + '</address>');

                });
            });

            axios.get('/check-sponsor/' + user.sponsor_id).then((response) => {
                $('#sponsor-information').text(response.data.data.name+' ('+response.data.data.username+')');
            });

            if ('{{auth()->check()}}' != 1)
            {
                axios.get('/get-placement/' + user.placement_search_id).then((response) => {
                    let placement = response.data.data.find(item => item.id == user.placement_id);
                    $('#placement-information').text(placement.business_center);
                    $('#placement-leg').text(user.leg);
                });
            }
            else
            {
                let placement_id = placement_info.placement_id
                axios.get('/get-business-center/' + placement_info.placement_id).then(response => {
                    $('#placement-information').text(response.data.data.business_center);
                    $('#placement-leg').text(placement_info.leg);
                });
            }

            axios.get('/cart/' + cart.id).then(response => {
                cart = response.data.data;
                cart.products.forEach(product => {
                    let price = user.usertype === 'rc' ? product.product.retail_customer_price : user.usertype === 'pc' ? product.product.preferred_customer_price : product.product.distributor_price;
                    let single_qv = product.product.qv;

                    if (product.product.product_countries.length > 0 && typeof product.product.product_countries.find(product_country => product_country.country_id == country_id) !== 'undefined')
                    {
                        let product_country = product.product.product_countries.find(product_country => product_country.country_id == country_id);
                        price = user.usertype === 'rc' ? product_country.retail_customer_price : user.usertype === 'pc' ? product_country.preferred_customer_price : product_country.distributor_price;
                        single_qv = product_country.qv;
                    }

                    totalQV += (single_qv * product.quantity);
                    subTotal += parseFloat(price * product.quantity);
                    if (product.product.tax_category === 'tax_free')
                    {
                        taxFreeSubTotal += parseFloat(price * product.quantity);
                    }

                    if (response.data.data.products.length === 1 && [80, 83, 84].includes(response.data.data.products[0].product_id))
                    {
                        is_membership_only = 1;
                    }

                    if (product.product.shipping_type.includes('fast_shipping'))
                    {
                        fast_shipping_price += parseFloat(user.usertype == 'dc' ? product.product.distributor_price : user.usertype == 'pc' ? product.product.preferred_customer_price : product.product.retail_customer_price) * product.quantity;
                    }
                    if (product.product.shipping_type.includes('regular_shipping'))
                    {
                        regular_shipping_price += parseFloat(user.usertype == 'dc' ? product.product.distributor_price : user.usertype == 'pc' ? product.product.preferred_customer_price : product.product.retail_customer_price) * product.quantity;
                    }
                });

                if (typeof response.data.data.products.find(product => product.product.shipping_type === 'regular_shipping') !== 'undefined')
                {
                    fast_shipping_price = 0
                }

                getShippingRates(shipping_address.country_id, regular_shipping_price, fast_shipping_price, is_membership_only);

                let cart_tbody = '';
                cart.products.map((product) => {
                    let components_div = '';
                    console.log(product)
                    product.product.product_components.map(component => {
                        components_div += '<div style="padding-left: 20px; font-size: 12px"><i class="fa fa-level-up rotate90"></i>'+component.name+'</div>';
                    });
                    let price = user.usertype === 'rc' ? product.product.retail_customer_price : user.usertype === 'pc' ? product.product.preferred_customer_price : product.product.distributor_price;
                    let single_qv = product.product.qv;

                    if (product.product.product_countries.length > 0 && typeof product.product.product_countries.find(product_country => product_country.country_id == country_id) !== 'undefined')
                    {
                        let product_country = product.product.product_countries.find(product_country => product_country.country_id == country_id);
                        price = user.usertype === 'rc' ? product_country.retail_customer_price : user.usertype === 'pc' ? product_country.preferred_customer_price : product_country.distributor_price;
                        single_qv = product_country.qv;
                    }

                    cart_tbody += '<tr>' +
                        '<td><span>'+product.product.name+'</span>'+components_div+'</td>' +
                        '<td>'+single_qv+'</td>' +
                        '<td>'+product.quantity+'</td>' +
                        '<td>'+price+'</td>' +
                        '<td>'+(price * product.quantity)+'</td>' +
                        '</tr>';
                });

                $('#order-details-table tbody').html(cart_tbody);
            });

        });

        function getShippingRates(country_id, price, fast_shipping_price, is_membership_only)
        {
            let params = {
                country_id: country_id,
                price: price,
                fast_shipping_price: fast_shipping_price,
                is_membership_only: is_membership_only
            };
            // $('#shipping-rates-loader').show();
            axios.get('/get-shipping-rates', {params: params}).then(response => {
                let options = '';
                if (response.data.data.length === 0)
                {
                    let data = [{
                        id: 0,
                        shipping_service_setting: {
                            service_name: 'Unshippable Product'
                        },
                        range_amount: 0
                    }];
                    data.map((item) => {
                        options += '<option value='+item.id+'>'+item.shipping_service_setting.service_name+'($'+item.range_amount+')</option>';
                    });
                }
                else
                {
                    response.data.data.map((item) => {
                        options += '<option value='+item.range_amount+'>'+item.shipping_service_setting.service_name+'($'+item.range_amount+')</option>';
                    });

                }
                $('#shipping_method').html(options);

                getTotals();
            });
        }

        function billingSameChecked(element)
        {
            console.log($(element).is(':checked'));
            if ($(element).is(':checked'))
            {
                Object.keys(shipping_address).map((key) => {
                    if (['address_1', 'address_2', 'city', 'postcode', 'state_id', 'country_id'].includes(key))
                    {
                        if ($('[name="billing_' + key + '"]')[0].localName == 'input')
                        {
                            $('[name="billing_' + key + '"]').val(shipping_address[key]);
                        }
                        else
                        {
                            if (key == 'state_id')
                            {
                                axios.get('states-by-country/' + shipping_address.country_id).then((response) => {
                                    let state_options = '';
                                    response.data.data.map((state) => {
                                        state_options += '<option value="'+state.id+'">'+state.name+'</option>';
                                    });
                                    $('[name="billing_state_id"]').html(state_options);
                                    let option_elements = $('[name="billing_' + key + '"]').children('option');
                                    for (let option_element in option_elements)
                                    {
                                        if ($(option_element).attr('value') == shipping_address[key])
                                        {
                                            $(option_element).attr('selected', true);
                                        }
                                        else
                                        {
                                            $(option_element).removeAttr('selected');
                                        }
                                    }
                                });
                            }
                            else
                            {
                                let option_elements = $('[name="billing_' + key + '"] option');
                                for (let i = 0; i < option_elements.length; i++)
                                {
                                    if ($(option_elements[i]).attr('value') == shipping_address[key])
                                    {
                                        $(option_elements[i]).attr('selected', true);
                                    }
                                    else
                                    {
                                        $(option_elements[i]).removeAttr('selected');
                                    }
                                }
                            }

                        }
                    }

                });
            }
            else
            {
                axios.get('states-by-country/' + countries[0].id).then((response) => {
                    let state_options = '';
                    response.data.data.map((state) => {
                        state_options += '<option value="'+state.id+'">'+state.name+'</option>';
                    });
                    $('[name="billing_state_id"]').html(state_options);
                });

                Object.keys(shipping_address).map((key) => {
                    if ($('[name="billing_' + key + '"]').localName == 'input')
                    {
                        $('[name="billing_' + key + '"]').val('');
                    }
                });

                $('[name="billing_state_id"] option').map((index, option) => {
                    $(option).removeAttr('selected');
                });

                $('[name="billing_country_id"] option').map((index, option) => {
                    $(option).removeAttr('selected');
                });
            }
        }

        function onCardDateChange()
        {
            $('#card_expiration').val($('#expiry_month').val() + ' / ' + $('#expiry_year').val());
            $('#card_expiration').valid();
        }

        function termsChecked(element)
        {
            if ($(element).is(':checked'))
            {
                $('#submit-button').removeAttr('disabled');
            }
            else
            {
                $('#submit-button').attr('disabled', true);
            }
        }

        function getTotals()
        {
            let params = {
                country_id: shipping_address.country_id,
                state_id: shipping_address.state_id,
                total: subTotal - taxFreeSubTotal,
            };
            axios.get('/tax-and-charges', {params: params}).then((response) => {
                taxTotal = response.data.data.total_tax_amount;
                handlingCharges = response.data.data.total_handling_charges;
                let shippingRate = parseFloat($('#shipping_method').val());
                let shippingMethod = $('#shipping_method option:selected').text().split('($')[0];
                console.log(shippingRate);
                console.log($('#shipping_method').val());
                let grandTotal = taxTotal + handlingCharges + subTotal + shippingRate;
                cart['totalQV'] = totalQV;
                cart['shippingTotal'] = shippingRate;
                cart['shippingMethod'] = shippingMethod;
                cart['taxTotal'] = taxTotal.toFixed(2);
                cart['handlingCharges'] = handlingCharges;
                cart['subTotal'] = subTotal;
                cart['grandTotal'] = grandTotal.toFixed(2);
                $('#cart-qvTotal').html(cart.totalQV);
                $('#cart-subtotal').html('$'+cart.subTotal);
                $('#cart-tax').html('$'+cart.taxTotal);
                $('#cart-shipping').html('$'+cart.shippingTotal);
                $('#cart-handling').html('$'+cart.handlingCharges);
                $('#cart-total').html('$'+cart.grandTotal);
                $('#loader').hide();
            });
        }

        function shippingMethodChange(element)
        {
            getTotals();
        }

        function onPaymentMethodChange(element)
        {
            if ($(element).val() == 'cod')
            {
                $('.hide-on-cod').hide();
                $('.hide-on-cod input').attr('disabled', true);
                $('.hide-on-cod select').attr('disabled', true);
            }
            else
            {
                $('.hide-on-cod').show();
                $('.hide-on-cod input').removeAttr('disabled');
                $('.hide-on-cod select').removeAttr('disabled');
            }
        }
        function wordsCheck(event)
        {
            $('#notes').parent().find('.invalid-feedback').css('display', 'block');
            $('#notes').parent().find('.invalid-feedback').text($('#notes').val().length + '/500');
        }
    </script>
@endpush