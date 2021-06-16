@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-md-12">
            <div class="stepwizard text-center">
                @include('includes.cart-stepper')
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="col-12">
                    <div class="row">
                        <div class="col-12">
                            <h3>@lang('cart.Products')</h3>
                        </div>
                        <div class="col-8"></div>
                        <div class="col-md-4">
                            <select class="form-control" id="products_country_id" onchange="onCountryChange()">

                            </select>
                            <br class="d-sm-block d-none"/>
                            <br class="d-sm-block d-none"/>
                            <br class="d-sm-block d-none"/>
                            <select class="form-control" id="category_id" onchange="onCategoryChange()">

                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <br/>
            <div class="col-12 product-listing-box-main">
                <div id="main-products-div" class="row no-magin">

                </div>
            </div>
            <div id="cart-sidebar" class="cartsummery" style="display: none">
                <div class="cart-sidebar-inner">
                    <div class="cart-sidebar-title">
                        <div class="cart-sidebar-title-inner text-left"><h2 style="font-size: 17px !important; color: #888888; margin-bottom: 0 !important;"><a href="javascript:void(0)" class="cart-sidebar-close" onclick="$('#cart-sidebar').hide()">X</a> YourCart</h2></div>
                    </div>
                    <div class="cart-sidebar-body">
                        <div class="sidebar-order-section">
                            <div class="cart-body-title text-left">
                                <p style="color: #fff; font-weight: bold; margin-bottom: 0 !important;">@lang('cart.Todays Order')</p>
                            </div>
                            <div id="main-cart-div" class="cart_section">

                            </div>
                        </div>
                        <div class="cart-sidebar-total-section">
                            <div class="cart-sidebar-total-inner">
                                <div class="cart-sidebar-totat-content text-right">
                                    <p class="cart-sidebar-total-price" style="margin-bottom: 0 !important; font-weight: bold;"> <span id="subTotal"></span></p>
                                    <p class="" style="margin-bottom: 0; font-weight: bold;">QV <span id="totalQV"></span></p>
                                    <p class="cart-sidebar-total-qty" style="margin-bottom: 0; font-weight: bold;">Total QTY <span id="totalQuantity"></span></p>
                                </div>
                                <div class="cart-sidebar-total-desc"></div>
                            </div>
                        </div>
                        <p class="text-center" style="font-weight: bold; margin-bottom: 0 !important; padding: 0 2px !important;">@lang('cart.Shipping and tax calculated at checkout')</p>
                        <div class="cart-sidebar-image-section">
                            <div class="cart-sidebar-image-section text-center d-block-center">
                                <img src="" alt="" class="img-responsive d-block-center"/>
                            </div>
                        </div>
                        <div class="cart-sidebar-button-section">
                            <div class="cart-sidebar-button-inner text-center">
                                <a id="continue-button" href="javascript:void(0)" class="btn btn-dark btn-block btn-round" disabled onclick="nextPage(this);" style="margin: 20px 0px;">@lang('cart.CHECKOUT')</a>
                                <button onclick="$('#cart-sidebar').hide()" class="btn btn-outline-dark btn-block btn-round" style="margin: 20px 0px;">@lang('cart.CONTINUE SHOPPING')</button>
                            </div>
                        </div>
                        <div class="row" style="margin: 0;">
                            <div class="mastercard pull-left col-6 col-sm-6 col-md-6 col-lg-6 text-center" style="display: grid; text-align: center;">
                                <i class="fa fa-credit-card" style="color: grey"></i>
                                <small class="gray block" style="margin-top: -85px"><b>@lang('cart.Mastercard and Visa Credit Cards accepted')</b></small>
                            </div>
                            <div class="orders pull-left col-6 col-sm-6 col-md-6 col-lg-6 text-center" style="display: grid; text-align: center">
                                <i class="fa fa-truck" style="color: grey"></i>
                                <small class="gray block">
                                    <b>
                                        @lang('cart.Orders typically ship with 2 - 4 days Shipping during peak times, however, will vary depending on product selection and shipping address')
                                    </b>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="cart-loader" class="text-center" style="position: absolute; height: 200vh; width: 100%; top: 0; background: #d8d8d87d; z-index: 9; display: none">
                    <i class="fa fa-spinner fa-spin fa-3x" style="margin-top: 50vh"></i>
                </div>
            </div>
            <br/>
            <br/>
        </div>
        <br/>
        <br/>
        <div class="row">
            <div class="col-md-2 offset-md-5 text-center">
                <button type="button" id="submit-button" onclick="nextPage(this)" class="btn btn-dark btn-block" disabled><b>@lang('cart.CONTINUE')</b></button>
            </div>
        </div>
        <br/>
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
    </style>
@endsection
@push('js')
    <script>
        let user = JSON.parse(localStorage.getItem('user'));
        let usertype = localStorage.getItem('usertype') !== null ? localStorage.getItem('usertype') : user ? user.usertype : 'rc';
        let cartProducts = [];
        suffix = window.location.search;

        localStorage.setItem('usertype', usertype);

        window.addEventListener('load', function() {
            axios.get('/product-categories').then((response) => {
                let options = '';
                response.data.data.map((product_category) => {
                    options += '<option value="'+product_category.id+'">'+product_category.name+'</option>';
                });
                $('#category_id').html(options);
            });

            axios.get('/all-countries').then(response => {
                let options = '';
                response.data.data.map((country) => {
                    if (localStorage.getItem('products_country') !== null && country.id == localStorage.getItem('products_country'))
                    {
                        options += '<option value="'+country.id+'" selected>'+country.name+'</option>';
                    }
                    else if (localStorage.getItem('products_country') === null && country.id == 233)
                    {
                        options += '<option value="'+country.id+'" selected>'+country.name+'</option>';
                    }
                    else
                    {
                        options += '<option value="'+country.id+'">'+country.name+'</option>';
                    }
                });
                $('#products_country_id').html(options);
                localStorage.setItem('products_country', $('#products_country_id').val());
            });

            let params = {
                country_id: $('#products_country_id').val(),
                usertype: usertype,
                product_category_id: 8
            };
            axios.get('/all-products', {params: params}).then((response) => {
                let products = response.data.data;
                productsDiv(products);

            });

            $('#cart-loader').show();

            $('.removecartitem').on('click', function () {

                let id = $(this).attr('id');
                let product_id = id.split('-')[1];
                updateCartProduct(product_id, 0);
            });

            $('.transparentButton').on('click', function () {
                console.log($(this).attr('id'));
                let id = $(this).attr('id');
                let product_id = id.split('-')[1];
                let quantity = id.split('-')[2];
                updateCartProduct(product_id, quantity);
            });

            if (localStorage.getItem('user') === null && localStorage.getItem('cart') === null)
            {
                axios.post('/cart', {new_cart: true}).then(response => {
                    localStorage.setItem('cart', JSON.stringify(response.data.data));
                    getCartProducts();
                }).catch((error) => {
                    if (error.response.status === 401)
                    {
                        localStorage.clear();
                        window.location.reload();
                    }
                });
            }
            else if ('{{auth()->check()}}' == 1 && localStorage.getItem('user') !== null && localStorage.getItem('address') !== null && localStorage.getItem('cart') === null)
            {
                axios.post('/cart', {create_cart: 1, user_id: user.id}).then((response) => {
                    localStorage.setItem('cart', JSON.stringify(response.data.data));
                    getCartProducts();
                });
            }
            else
            {
                getCartProducts();
            }

        });

        function productsDiv(products)
        {
            let html = '';

            products.map(product => {
                let product_details_href = product.product_layout === 'oil' ? "/www/oils/"+product.id : "/www/supplements/"+product.id;
                let product_stock = product.product_layout === 'out_of_stock' ? 'Out of Stock' : '<a href="javascript:void(0)" class="addtocart btn-round" id="add-to-cart'+product.id+'" onclick="addToCart('+product.id+')">@lang("cart.Add to cart")</button>';
                let product_price = 0;
                let product_qv = 0;
                if (product.product_countries.length > 0 && typeof product.product_countries.find(product_country => product_country.country_id == $('#product_country_div').val()) !== 'undefined')
                {
                    if (usertype === 'rc')
                    {
                        product_price = product.product_countries.find(product_country => product_country.country_id == $('#product_country_id').val()).retail_customer_price;
                    }
                    else if (usertype === 'pc')
                    {
                        product_price = product.product_countries.find(product_country => product_country.country_id == $('#product_country_id').val()).preferred_customer_price;
                    }
                    else
                    {
                        product_price = product.product_countries.find(product_country => product_country.country_id == $('#product_country_id').val()).distributor_price;
                    }
                    product_qv = product.product_countries.find(product_country => product_country.country_id == $('#product_country_id').val()).qv;
                }
                else
                {
                    if (usertype === 'rc')
                    {
                        product_price = product.retail_customer_price;
                    }
                    else if (usertype === 'pc')
                    {
                        product_price = product.preferred_customer_price;
                    }
                    else
                    {
                        product_price = product.distributor_price;
                    }
                    product_qv = product.qv;
                }

                html += '<div class="col-md-3 col-sm-6 col-xs-12 no-padding">\n' +
                    '    <div class="product-listing-box">\n' +
                    '        <div class="product-listing-box-inner">\n' +
                    '            <div class="product-listing-box-img text-center">\n' +
                    '                <a href="'+product_details_href+'" target="_blank">\n' +
                    '                    <img src="https://admin.ultrra.com/product_images/' + product.image + '" alt="Product-1" class="img-responsive d-block-center"/>\n' +
                    '                </a>\n' +
                    '            </div>\n' +
                    '            <div class="product-listing-box-title">\n' +
                    '                <p class="text-center">'+product.name+'</p>\n' +
                    '            </div>\n' +
                    '            <div class="product-listing-box-price-main d-block-center">\n' +
                    '                <div class="product-listing-box-price">\n' +
                    '                    <p>\n' +
                    '                        <b>\n' +
                    '                            $' + product_price +
                    '                        </b>\n' +
                    '                    </p>\n' +
                    '                </div>\n' +
                    '                <div class="product-listing-box-qty">\n' +
                    '                    <p>\n' +
                    '                        <b>\n' + product_qv + ' QV\n' +
                    '                        </b>\n' +
                    '                    </p>\n' +
                    '                </div>\n' +
                    '               <div class="float"></div>\n' +
                    '            </div>\n' +
                    '            <div class="product-listing-box-add-cart text-center">' + product_stock + '</div>\n' +
                    '            <div class="product-listing-learn-more text-center">\n' +
                    '                <a href="'+product_details_href+'" target="_blank" class="text-center">@lang("cart.Details")</a>\n' +
                    '            </div>\n' +
                    '        </div>\n' +
                    '    </div>\n' +
                    '</div>'
            });

            $('#main-products-div').html(html);
        }

        function getCartProducts()
        {
            $('#cart-loader').show();
            let cart = JSON.parse(localStorage.getItem('cart'));
            let total = 0;
            let qv = 0;
            let quantity = 0;
            let html = '';
            axios.get('/cart/' + cart.id).then((response) => {
                cartProducts = response.data.data.products;
                response.data.data.products.forEach((product) => {
                    let price = usertype === 'rc' ? product.product.retail_customer_price : usertype === 'pc' ? product.product.preferred_customer_price : product.product.distributor_price;
                    let single_qv = product.product.qv;

                    if (product.product.product_countries.length > 0 && typeof product.product.product_countries.find(product_country => product_country.country_id == $('#product_country_div').val()) !== 'undefined')
                    {
                        let product_country = product.product.product_countries.find(product_country => product_country.country_id == $('#product_country_div').val());
                        price = usertype === 'rc' ? product_country.retail_customer_price : usertype === 'pc' ? product_country.preferred_customer_price : product_country.distributor_price;
                        single_qv = product_country.qv;
                    }

                    total = total + parseFloat(price * product.quantity);
                    qv = qv + (single_qv * product.quantity);
                    quantity = quantity + product.quantity;
                    let removeCartItem = ![80, 83, 84].includes(product.product_id) ? '<a href="javascript:void(0)" class="removecartitem" id="delete_product-'+product.id+'"><i class="fa fa-times-circle"></i></a>' : '<a class="d-none"></a>';
                    let buttonClass = [80, 83, 84].includes(product.product_id) ? "d-none" : "transparentButton";
                    html += '<div class="sidebar-box">\n' +
                        '    <div id="sidebar-box-inner'+product.id+'" class="sidebar-box-inner">\n' +
                        '        <div class="sidebar-box-close">'+removeCartItem+'</div>\n' +
                        '        <div class="sidebar-box-img">\n' +
                        '            <a href="javascript:void(0)"><img src="https://admin.ultrra.com/product_images/' + product.product.image +'" class="img-responsive" alt=""/></a>\n' +
                        '        </div>\n' +
                        '        <div class="sidebar-box-content">\n' +
                        '            <div class="sidebar-box-content-top text-left">\n' +
                        '                <p style="color: #888 !important;"><b></b>'+product.product.name+'</p>\n' +
                        '            </div>\n' +
                        '            <div class="sidebar-box-content-bottom">\n' +
                        '                <div class="text-right">\n' +
                        '                    <p class="sidebar-box-price text-right" style="color: #888888 !important; width: 100%; margin-bottom: 0 !important;">\n' +
                        '                        $' + price +
                        '                    </p><br/>\n' +
                        '                    <p class="sidebar-box-price sidebar-box-qv float-right text-right" style="color: #888888 !important; width: 50%, margin-bottom: 0 !important;">\n' +
                        '                        QV' + qv +
                        '                    </p>\n' +
                        '                </div>\n' +
                        '                <div class="notranslate float-left">\n' +
                        '                    <button class="'+buttonClass+'" id="decrease_qty-'+product.id+'-'+(product.quantity - 1)+'">\n' +
                        '                        <i class="fa fa-minus-circle"></i>\n' +
                        '                    </button>\n' +
                        '                    <span class="product_qty" id="product_qty" style="font-size: 16px">&nbsp;'+product.quantity+'&nbsp;</span>\n' +
                        '                    <button class="'+buttonClass+'" id="increase_qty-'+product.id+'-'+(product.quantity + 1)+'">\n' +
                        '                        <i class="fa fa-plus-circle"></i>\n' +
                        '                    </button>\n' +
                        '                </div>\n' +
                        '                <div class="float"></div>\n' +
                        '            </div>\n' +
                        '            <div class="float"></div>\n' +
                        '        </div>\n' +
                        '        <div class="float"></div>\n' +
                        '    </div>\n' +
                        '</div>';
                });

                $('#main-cart-div').html(html);

                $('.sidebar-box').each(function (index, element) {
                    let removecartitem = $(element).find('.removecartitem');
                    let decrease_button = $(element).find('.fa-minus-circle').parent();
                    let increase_button = $(element).find('.fa-plus-circle').parent();

                    if (removecartitem.length > 0 && decrease_button.length > 0 && increase_button.length > 0)
                    {
                        let product_id = removecartitem.attr('id').split('-')[1];
                        let decrease_quantity = decrease_button.attr('id').split('-')[2];
                        let increase_quantity = increase_button.attr('id').split('-')[2];

                        removecartitem.attr('onclick', 'updateCartProduct('+product_id+',0)');
                        decrease_button.attr('onclick', 'updateCartProduct('+product_id+','+decrease_quantity+')');
                        increase_button.attr('onclick', 'updateCartProduct('+product_id+','+increase_quantity+')');
                    }

                });

                $('#subTotal').text('$ '+total);
                $('#totalQuantity').text(quantity);
                $('#totalQV').text(qv);

                $('#cart-loader').hide();

                $('.cart-value').text(response.data.data.products.length);

                if (total > 0)
                {
                    $('#continue-button').removeAttr('disabled');
                    $('#submit-button').removeAttr('disabled');
                }

                if (response.data.data.products.length === 1 && [83, 84].includes(response.data.data.products[0].product_id) && total === 0)
                {
                    $('#continue-button').removeAttr('disabled');
                    $('#submit-button').removeAttr('disabled');
                }

                if (response.data.data.products.length === 0)
                {
                    $('#continue-button').attr('disabled', true);
                    $('#submit-button').attr('disabled', true);
                }
            });
        }

        function updateCartProduct(product_id, quantity)
        {
            $('#cart-loader').show();
            if (quantity === 0)
            {
                console.log('deleted');
                axios.delete('/cart/' + product_id).then((response) => {
                    getCartProducts();
                    $('#cart-sidebar').show();
                });
            }
            else
            {
                axios.put('/cart/' + product_id, {quantity: quantity}).then((response) => {
                    $('#max-quantity-error-'+product_id).remove();
                    if (response.data.status === 2)
                    {
                        $('#sidebar-box-inner' + product_id).append('<span id="max-quantity-error-'+product_id+'" style="padding: 10px; color: red; float: left;">'+response.data.message+'</span>');
                        $('#cart-loader').hide();
                    }
                    else if (response.data.status === 200)
                    {
                        getCartProducts();
                        $('#cart-sidebar').show();
                    }

                });
            }
        }

        function addToCart(product_id)
        {
            let cart = JSON.parse(localStorage.getItem('cart'));
            let usertype = localStorage.getItem('usertype');
            if (!$('#add-to-cart' + product_id).attr("class").split(/\s+/).includes('disabled'))
            {
                $('#add-to-cart'+product_id).append('&nbsp;<i class="fa fa-spinner fa-spin"></i>');
                $('#add-to-cart'+product_id).addClass('disabled');
                axios.get('/product/'+product_id).then((response) => {
                    if (response.data.data !== null && response.data.data.stock != 'out_of_stock')
                    {
                        $('#cart-loader').show();
                        axios.post('/cart', {product_id: product_id, cart_id: cart.id,usertype:usertype}).then((response) => {
                            getCartProducts();
                            $('#cart-sidebar').show();
                            $('#add-to-cart'+product_id+' i').remove();
                            $('#add-to-cart'+product_id).removeClass('disabled');
                        });
                    }
                    else
                    {
                        Swal.fire({
                            icon: 'error',
                            text: 'Something went wrong!'
                        });
                        $('#add-to-cart'+product_id).parent().append('<p class="text-danger">Out of Stock</p>');
                        $('#add-to-cart'+product_id).remove();
                        $('#add-to-cart'+product_id+' i').remove();
                        $('#add-to-cart'+product_id).removeClass('disabled');
                    }
                });
            }
        }

        function onCountryChange()
        {
            let selected_country_id = $('#products_country_id').val();
            localStorage.setItem('products_country', $('#products_country_id').val());
            Swal.fire({
                icon: 'warning',
                text: 'Any products in your cart will be removed when changing the shipping country',
                showCancelButton: true,
                confirmButtonText: 'Proceed',
                cancelButtonText: 'Cancel',
                showLoaderOnConfirm: true,
                reverseButtons: true,
                customClass: {
                    confirmButton: 'btn-success',
                    cancelButton: 'btn-dark'
                }
            }).then((result) => {
                if (result.isConfirmed)
                {
                    localStorage.setItem('products_country', selected_country_id);
                    // this.setState({country_id: selected_country_id});
                    let params = {
                        country_id: selected_country_id,
                        usertype: usertype,
                        product_category_id: $('#category_id').val()
                    };
                    axios.get('/all-products', {params: params}).then((response) => {
                        productsDiv(response.data.data);
                    });

                    cartProducts.map((cartProduct) => {
                        if (![80, 83, 84].includes(cartProduct.product_id))
                        {
                            axios.delete('/cart/' + cartProduct.id).then((response) => {
                                getCartProducts();
                                $('#cart-sidebar').show();
                            });
                        }
                    });
                }
                else
                {
                    $('#products_country_id option').each((key, element) => {
                        if ($(element).attr('value') == localStorage.getItem('country'))
                        {
                            $(element).attr('selected', true);
                        }
                        else
                        {
                            $(element).removeAttr('selected');
                        }
                    });
                }
            });
        }

        function onCategoryChange()
        {
            let params = {
                country_id: $('#products_country_id').val(),
                usertype: usertype,
                product_category_id: $('#category_id').val()
            };
            axios.get('/all-products', {params: params}).then((response) => {
                productsDiv(response.data.data);
            });
        }

        function nextPage(element)
        {
            if (!($(element).attr("disabled")))
            {
                if (user !== null && user.hasOwnProperty('id') && '{{auth()->check()}}' == 1)
                {
                    window.location.href = '/www/shipping-address' + window.location.search;
                }
                else
                {
                    window.location.href = '/www/create-account' + window.location.search;
                }
            }
        }
    </script>
@endpush