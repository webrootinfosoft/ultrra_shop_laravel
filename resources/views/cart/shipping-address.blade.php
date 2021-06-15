@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="container-fluid">
            <div class="col-md-10 offset-md-1">
                <div class="stepwizard text-center">
                    <div class="stepwizard-row">
                        @include('includes.cart-stepper')
                    </div>
                </div>
                <br/>
                <br/>
            </div>
            <form id="shipping-address-form">
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <div class="row">
                            <div class="col">
                                <div id="user-information" class="text-left">
                                    <h2 class="subpage text-center notop"><b>SHIPPING DETAILS</b></h2>
                                    <br/>
                                    <div class="row" style="border: 1px solid #cccccc; border-radius: 5px">
                                        <div class="col-md-12">
                                            <div class="row" id="address-radios">

                                            </div>
                                        </div>
                                    </div>
                                    <div id="user-shipping-addresses-loader" style="display: none; text-align: center"><i class="fa fa-spinner fa-spin fa-3x"></i></div>
                                    <div id="shipping-inputs" style="display: none;">
                                        <br>
                                        <br>
                                        <div>
                                            <div class="row form-group">
                                                <label class="text-md-right text-sm-left col-md-4 form-label">Contact Name *</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" type="text" id="contact_name" name="contact_name" required/>
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <label class="text-md-right text-sm-left col-md-4 form-label">Contact Number *</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" type="text" id="contact_number" name="contact_number" required/>
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <label class="text-md-right text-sm-left col-md-4 form-label">Address 1 *</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" type="text" id="address_1" name="address_1" required/>
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <label class="text-md-right text-sm-left col-md-4 form-label">Address 2</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" type="text" id="address_2" name="address_2" />
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <label class="text-md-right text-sm-left col-md-4 form-label">City *</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" type="text" id="city" name="city" required/>
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <label class="text-md-right text-sm-left col-md-4 form-label">State *</label>
                                                <div class="col-md-8">
                                                    <select class="form-control" id="state_id" name="state_id">

                                                    </select>
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <label class="text-md-right text-sm-left col-md-4 form-label">Postal Code *</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" type="text" id="postcode" name="postcode" required/>
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <label class="text-md-right text-sm-left col-md-4 form-label" style="color: #3c763d">Country *</label>
                                                <div class="col-md-8">
                                                    <select class="form-control" id="country_id" name="country_id" onchange="changeShippingCountry();">

                                                    </select>
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br/>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br/>
                <br/>
                <div class="row">
                    <div class="text-center col-md-4 offset-md-4">
                        <div class="row">
                            <div class="col-md-6">
                                <button class="btn btn-outline-dark btn-block" onclick="previousPage()"><b>BACK</b></button>&nbsp;&nbsp;&nbsp;&nbsp;
                            </div>
                            <div class="col-md-6">
                                <button id="submit-button" class="btn btn-dark btn-block" type="submit" disabled><b>CONTINUE</b></button>
                            </div>
                        </div>
                    </div>
                </div>
                <br/>
            </form>
        </div>
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
        let user = JSON.parse(localStorage.getItem('user'));
        if (localStorage.getItem('cart') === null)
        {
            window.location.href = '/www/products' + window.location.search;
        }
        window.addEventListener('load', function() {
            axios.defaults.headers.common['authorization'] = "Bearer " + localStorage.getItem('access_token');
            $.validator.addMethod("alpha", function(value, element) {
                return this.optional(element) || value == value.match(/^[a-zA-Z\s]+$/);
            }, 'Should only contain letters and spaces');
            $('#shipping-address-form').validate({
                onfocusout: function(element) {
                    this.element(element);
                },
                rules: {
                    "contact_name": {
                        required: true,
                        alpha: true,
                    },
                    "contact_number": {
                        required: true,
                        digits: true,
                        minlength: 10,
                        maxlength: 15
                    },
                    "address_1": {
                        required: true,
                    },
                    "city": {
                        required: true,
                    },
                    "postcode": {
                        required: true,
                    },
                    "state_id": {
                        required: true,
                    },
                    "country_id": {
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
                    let myform = $('#shipping-address-form');
                    let formData = myform.serializeArray();
                    let formObject = {};
                    $.each(formData, function (i, v) {
                        formObject[v.name] = v.value;
                    });

                    let address = {};
                    Object.keys(formObject).map((key) => {
                        address[key] = formObject[key];
                        delete formObject[key];
                    });
                    address['user_id'] = user.id;
                    address['is_shipping'] = 1;
                    console.log(formObject);
                    $('#submit-button').attr('disabled', 'disabled');
                    $('#submit-button').append('<i class="fa fa-spinner fa-spin"></i>');
                    if ($('#inlineRadio2AddNew').is(':checked'))
                    {
                        axios.post('/address', address).then((response) => {
                            if (response.data.status == 200)
                            {
                                localStorage.setItem('shipping_address', JSON.stringify(response.data.data));
                                getAddresses();
                                $('#inlineRadio2AddNew').prop('checked', false);
                                $('#submit-button').attr('disabled', true);
                                $('#submit-button i').remove();
                                $('#shipping-inputs').hide();
                                $('#shipping-address-form input').val('');
                                // this.props.history.push('/www/payment' + suffix);
                            }
                        }).catch((error) => {
                            console.log(error);
                            if (error.response.status == 422)
                            {
                                let responseErrors = error.response.data.errors;
                                Object.keys(responseErrors).forEach(key => {
                                    this.refs[key].classList.add('is-invalid');
                                    let errors = [];
                                    responseErrors[key].forEach((error) => {
                                        errors.push(error);
                                    });
                                    this.refs[key].nextSibling.innerHTML = errors.join('<br/>');
                                });
                            }
                            $('#submit-button').removeAttr('disabled');
                            $('#submit-button i').remove();
                        });
                    }
                    else
                    {
                        // console.log($('[name="user_shipping_address_id"]').val());
                        axios.get('/address/' + $('[name="user_shipping_address_id"]:checked').val()).then((response) => {
                            if (response.data.status == 200)
                            {
                                localStorage.setItem('shipping_address', JSON.stringify(response.data.data));
                                window.location.href = '/www/review' + window.location.search;
                            }
                        }).catch((error) => {

                        });
                    }
                }
            });
            if (user.hasOwnProperty('id'))
            {
                getAddresses();
            }
            else
            {
                $('#shipping-inputs').show();
                $('#submit-button').removeAttr('disabled');
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
                $('#country_id').html(options);
                axios.get('/states-by-country/' + localStorage.getItem('products_country')).then(response => {
                    let options = '';
                    response.data.data.map((state) => {
                        options += '<option value="'+state.id+'">'+state.name+'</option>';
                    });
                    $('#state_id').html(options);
                });
            });

        });

        function addNewChecked()
        {
            if ($('#inlineRadio2AddNew').is(':checked'))
            {
                $('#shipping-inputs').show();
                $('#submit-button').removeAttr('disabled');
            }
            else
            {
                $('#shipping-inputs').hide();
            }
        }

        function changeShippingCountry()
        {
            let states = '';
            axios.get('/states-by-country/' + $('#country_id').val()).then(response => {
                response.data.data.map((item) => {
                    states += '<option id="'+item.id+'">'+item.name+'</option>';
                });
            });
        }

        function shippingAddressSelected(element)
        {
            if ($(element).is(':checked'))
            {
                $('#shipping-inputs').hide();
                $('#submit-button').removeAttr('disabled');

            }
            else if ($('#inlineRadio2AddNew').is(':checked'))
            {
                $('#shipping-inputs').hide();
                $('#submit-button').attr('disabled', true);
            }
        }

        function getAddresses()
        {
            $('#user-shipping-addresses-loader').show();
            axios.get('/user-addresses-by-id/' + '{{auth()->id()}}').then(response => {
                let shipping_address = JSON.parse(localStorage.getItem('shipping_address'));
                let user_shipping_addresses = response.data.data.filter((item) => item.is_shipping === 1);
                let address_inputs = '';
                if (user_shipping_addresses.length > 0)
                {
                    user_shipping_addresses.map((user_shipping_address) => {
                        if (shipping_address !== null && shipping_address.id === user_shipping_address.id)
                        {
                            address_inputs += '<label class="form-check-label col-md-11 mr-auto ml-auto" for="inlineRadio2'+user_shipping_address.id+'" style="border-bottom: 1px solid #cccccc; padding: 15px">\n' +
                                '                  <div class="ml-3">\n' +
                                '                      <span style="color: #808080, font-weight: 600">'+user_shipping_address.contact_name+'</span><br/>\n' +
                                '                      <input class="form-check-input" type="radio" name="user_shipping_address_id" id="inlineRadio2'+user_shipping_address.id+'" value="'+user_shipping_address.id+'" checked onchange="shippingAddressSelected(this)"/>\n' +
                                '                      '+user_shipping_address.address_1+', '+(user_shipping_address.address_2 !== null && user_shipping_address.address_2.length > 0 ? user_shipping_address.address_2 : "")+', '+user_shipping_address.city+', '+user_shipping_address.state.name+', '+user_shipping_address.postcode+', '+user_shipping_address.country.name +
                                '                  </div>\n' +
                                '              </label>';
                            $('#submit-button').removeAttr('disabled');
                        }
                        else
                        {
                            address_inputs += '<label class="form-check-label col-md-11 mr-auto ml-auto" for="inlineRadio2'+user_shipping_address.id+'" style="border-bottom: 1px solid #cccccc; padding: 15px">\n' +
                                '                  <div class="ml-3">\n' +
                                '                      <span style="color: #808080, font-weight: 600">'+user_shipping_address.contact_name+'</span><br/>\n' +
                                '                      <input class="form-check-input" type="radio" name="user_shipping_address_id" id="inlineRadio2'+user_shipping_address.id+'" value="'+user_shipping_address.id+'" onchange="shippingAddressSelected(this)"/>\n' +
                                '                      '+user_shipping_address.address_1+', '+(user_shipping_address.address_2 !== null && user_shipping_address.address_2.length > 0 ? user_shipping_address.address_2 : "")+', '+user_shipping_address.city+', '+user_shipping_address.state.name+', '+user_shipping_address.postcode+', '+user_shipping_address.country.name +
                                '                  </div>\n' +
                                '              </label>';
                        }
                    });

                    address_inputs += '<label class="form-check-label col-md-11 mr-auto ml-auto" for="inlineRadio2AddNew" style="padding: 15px">\n' +
                        '                  <div class="ml-3">\n' +
                        '                      <input class="form-check-input" type="radio" name="user_shipping_address_id" id="inlineRadio2AddNew" value="add_new" onchange="addNewChecked()"/>\n' +
                        '                      Add New\n' +
                        '                  </div>\n' +
                        '              </label>';

                    $('#address-radios').html(address_inputs);
                    $('#shipping-inputs').hide();
                }
                else
                {
                    $('#shipping-inputs').show();
                    $('#submit-button').removeAttr('disabled');
                }
                $('#user-shipping-addresses-loader').hide();
            });
        }
    </script>
@endpush