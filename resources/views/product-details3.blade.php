@extends('layouts.app')

@section('content')
    <div id="product-details"></div>
    <section class="section-padding ul-product-text-wrap py-5 text-left">
        <div class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="row">
                        <div class="col-sm-6 content-collection">
                            <div id="country_of_origin"></div>
                            <div id="collection_method"></div>
                        </div>
                        <div class="col-sm-6 content-collection">
                            <div id="plant_part"></div>
                            <div id="main_constituents"></div>
                            <div id="aromatic_description"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="km-product-tabs tf-products-tabs style-2" style="display: none;">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="tabs">
                        <ul class="custom-flex nav nav-tabs no-gutters">

                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-content text-left">

        </div>
        <div class="container">
            <div id="disclaimer" class="col-md-6 offset-md-3 fs-14 text-center">

            </div>
        </div>
        <br>
    </section>
    <style>

    </style>
@endsection
@push('js')
    <script>
        cart = JSON.parse(localStorage.getItem('cart'));
        let user = JSON.parse(localStorage.getItem('user'));
        let image, secondary_image, product;
        let language = '{{app()->getLocale()}}';
        window.addEventListener('load', function(){
            axios.get('/product/' + '{{$product_id}}').then((response) => {
                image = response.data.data.image;
                product = response.data.data;
                secondary_image = response.data.data.secondary_image ? '<a class="pl-2 pr-2" href="javascript:void(0)" onclick="getImage(\'secondary\')"><img src="https://admin.ultrra.com/product_images/' + response.data.data.secondary_image +'" class="image-fit" alt="img" style="width: 64px" /></a>' : '<a></a>';
                let product_categories = '';
                response.data.data.product_categories.map((product_category) => {
                    product_categories += product_category.name;
                });

                let product_tag_images = '';
                response.data.data.product_tag_images.map((product_tag_image) => {
                    product_tag_images += '<span class="pr-tag p-0 my-2"><img src="https://admin.ultrra.com/ultrra-aromatically/'+product_tag_image+'.png" style="width: 40px"/></span>';
                });

                let tick_points = '';
                getTickPoints(response.data.data).map((tick_point) => {
                    tick_points += ('<li>'+tick_point+'</li>')
                });

                let html = '<div class="ul-product-detail section-padding">\n' +
                    '                            <div class="container">\n' +
                    '                                <div class="row row-reverse-991">\n' +
                    '                                    <div class="col-xl-5 col-lg-6">\n' +
                    '                                        <div class="pr-image-wrap mb-md-80">\n' +
                    '                                            <img id="main-image" src="https://admin.ultrra.com/product_images/' + image + '" class="image-fit" alt="img" />\n' +
                    '                                            <br/>\n' +
                    '                                            <nav class="text-center">\n' +
                    '                                                <a class="pl-2 pr-2" href="javascript:void(0)" onclick="getImage(\'primary\')"><img src="https://admin.ultrra.com/product_images/' + image + '" class="image-fit" alt="img" style="width: 64px" /></a>\n' +
                    '                                                ' + secondary_image + '\n' +
                    '                                            </nav>\n' +
                    '                                        </div>\n' +
                    '                                    </div>\n' +
                    '                                    <div class="col-xl-7 col-lg-6 text-left">\n' +
                    '                                        <div class="pr-detail-wrap">\n' +
                    '                                            <div style="font-size: 25px; font-weight: 500; color: #333333">\n' + product_categories + '</div>\n' +
                    '                                            <div style="font-size: 50px; font-weight: 800; color: #8ec41a">' + getName(response.data.data) + '</div>\n' +
                    '                                            <div style="font-size: 25px; font-weight: 500; color: #333333">' + getCategory(response.data.data) + '</div>\n' +
                    '                                            <span class="fs-18 text-brown fw-600 mr-3">\n' + product_tag_images + '</span>\n' +
                    '                                            <h6 class="text-custom-black fw-600">$' + getPrice(response.data.data) + ' <span class="fw-500" style="font-size: 14px">(QV ' + response.data.data.qv + ')</span></h6>\n' +
                    '                                            <p>' + getDescription(response.data.data) + '</p>\n' +
                    '                                            <ul>' + tick_points + '</ul>\n' +
                    '                                            <button onclick="addToCart('+response.data.data.id+')" class="theme-btn"><span class="btn-text">Add to Cart</span></button>\n' +
                    '                                        </div>\n' +
                    '                                    </div>\n' +
                    '                                </div>\n' +
                    '                            </div>\n' +
                    '                        </div>';

                $('#product-details').html(html);

                let additional_field_tabs = '';
                let additional_field_contents = '';

                getAdditionalFields(product.product_additional_fields).map((additional_field, index) => {
                    let class_list = index === 0 ? 'nav-link active text-capitalize' : 'nav-link text-capitalize';
                    additional_field_tabs += '<li class="nav-item col">' +
                        '<a id="'+additional_field.title+'-a" data-id="'+additional_field.title+'" class="'+class_list+'" data-toggle="tab" onclick="tabClick(\''+additional_field.title+'\')">'+additional_field.title+'</a>' +
                        '</li>';

                    let class_list2 = index === 0 ? 'tab-pane fade active show' : 'tab-pane fade';
                    additional_field_contents += '<div class="'+class_list2+'" id="'+additional_field.title+'">\n' +
                        '                                            <div class="container">\n' +
                        '                                                <div class="row">\n' +
                        '                                                    <div class="col-12">\n' +
                        '                                                        <div class="tab-inner">'+additional_field.description+'</div>\n' +
                        '                                                    </div>\n' +
                        '                                                </div>\n' +
                        '                                            </div>\n' +
                        '                                        </div>';
                });

                $('.tf-products-tabs ul.nav-tabs').html(additional_field_tabs);
                $('.tf-products-tabs .tab-content').html(additional_field_contents);
                $('.tf-products-tabs').show();

                $('#disclaimer').html(getDisclaimer(product));

                if (product.country_of_origin !== null && product.country_of_origin !== '')
                {
                    $('#country_of_origin').html('<p class="text-dark feature-origin fs-16 fw-700">\n' +
                        '                                Origin: <span class="country-origin fw-100">'+getCountryOfOrigin(product)+'</span>\n' +
                        '                            </p>');
                }
                if (product.collection_method !== null && product.collection_method !== '')
                {
                    $('#collection_method').html('<p class="text-dark feature-origin fs-16 fw-700">\n' +
                        '                                Collection Method: <span class="country-origin fw-100">'+getCollectionMethod(product)+'</span>\n' +
                        '                            </p>');
                }
                if (product.plant_part !== null && product.plant_part !== '')
                {
                    $('#plant_part').html('<p class="text-dark feature-origin fs-16 fw-700">\n' +
                        '                                Plant Part: <span class="country-origin fw-100">'+getPlantPart(product)+'</span>\n' +
                        '                            </p>');
                }
                if (product.main_constituents !== null && product.main_constituents !== '')
                {
                    $('#main_constituents').html('<p class="text-dark feature-origin fs-16 fw-700">\n' +
                        '                                Main Constituents: <span class="country-origin fw-100">'+getMainConstituents(product)+'</span>\n' +
                        '                            </p>');
                }
                if (product.aromatic_description !== null && product.aromatic_description !== '')
                {
                    $('#aromatic_description').html('<p class="text-dark feature-origin fs-16 fw-700">\n' +
                        '                                Aromatic Description: <span class="country-origin fw-100">'+getAromaticDescription(product)+'</span>\n' +
                        '                            </p>');
                }
            });
        });

        function getPrice(product)
        {
            if (user !== null)
            {
                return user.usertype === 'dc' ? product.distributor_price : user.usertype === 'pc' ? product.preferred_customer_price : product.retail_customer_price;
            }
            else
            {
                return product.retail_customer_price;
            }
        }

        function getName(product)
        {
            if (language != 'en')
            {
                if (language == 'es' && product.name_spanish !== null && product.name_spanish !== '')
                {
                    return product.name_spanish;
                }
                else if (language == 'ja' && product.name_japanese !== null && product.name_japanese !== '')
                {
                    return product.name_japanese;
                }
                else if (language == 'zh' && product.name_chinese !== null && product.name_spanish !== '')
                {
                    return product.name_chinese;
                }
                else
                {
                    return product.name;
                }
            }
            else
            {
                return product.name;
            }
        }

        function getCategory(product)
        {
            if (language != 'en')
            {
                if (language == 'es' && product.category_spanish !== null && product.category_spanish !== '')
                {
                    return product.category_spanish;
                }
                else if (language == 'ja' && product.category_japanese !== null && product.category_japanese !== '')
                {
                    return product.category_japanese;
                }
                else if (language == 'zh' && product.category_chinese !== null && product.category_spanish !== '')
                {
                    return product.category_chinese;
                }
                else
                {
                    return product.category;
                }
            }
            else
            {
                return product.category;
            }
        }

        function getDescription(product)
        {
            if (language != 'en')
            {
                if (language == 'es' && product.description_spanish !== null && product.description_spanish !== '')
                {
                    return product.description_spanish;
                }
                else if (language == 'ja' && product.description_japanese !== null && product.description_japanese !== '')
                {
                    return product.description_japanese;
                }
                else if (language == 'zh' && product.description_chinese !== null && product.description_spanish !== '')
                {
                    return product.description_chinese;
                }
                else
                {
                    return product.description;
                }
            }
            else
            {
                return product.description;
            }
        }

        function getTickPoints(product)
        {
            if (language != 'en')
            {
                if (language == 'es' && product.tick_points_spanish !== null && product.tick_points_spanish !== '')
                {
                    return product.tick_points_spanish;
                }
                else if (language == 'ja' && product.tick_points_japanese !== null && product.tick_points_japanese !== '')
                {
                    return product.tick_points_japanese;
                }
                else if (language == 'zh' && product.tick_points_chinese !== null && product.tick_points_spanish !== '')
                {
                    return product.tick_points_chinese;
                }
                else
                {
                    return product.tick_points;
                }
            }
            else
            {
                return product.tick_points;
            }
        }

        function getCountryOfOrigin(product)
        {
            if (language != 'en')
            {
                if (language == 'es' && product.country_of_origin_spanish !== null && product.country_of_origin_spanish !== '')
                {
                    return product.country_of_origin_spanish;
                }
                else if (language == 'ja' && product.country_of_origin_japanese !== null && product.country_of_origin_japanese !== '')
                {
                    return product.country_of_origin_japanese;
                }
                else if (language == 'zh' && product.country_of_origin_chinese !== null && product.country_of_origin_spanish !== '')
                {
                    return product.country_of_origin_chinese;
                }
                else
                {
                    return product.country_of_origin;
                }
            }
            else
            {
                return product.country_of_origin;
            }
        }

        function getCollectionMethod(product)
        {
            if (language != 'en')
            {
                if (language == 'es' && product.collection_method_spanish !== null && product.collection_method_spanish !== '')
                {
                    return product.collection_method_spanish;
                }
                else if (language == 'ja' && product.collection_method_japanese !== null && product.collection_method_japanese !== '')
                {
                    return product.collection_method_japanese;
                }
                else if (language == 'zh' && product.collection_method_chinese !== null && product.collection_method_spanish !== '')
                {
                    return product.collection_method_chinese;
                }
                else
                {
                    return product.collection_method;
                }
            }
            else
            {
                return product.collection_method;
            }
        }

        function getPlantPart(product)
        {
            if (language != 'en')
            {
                if (language == 'es' && product.plant_part_spanish !== null && product.plant_part_spanish !== '')
                {
                    return product.plant_part_spanish;
                }
                else if (language == 'ja' && product.plant_part_japanese !== null && product.plant_part_japanese !== '')
                {
                    return product.plant_part_japanese;
                }
                else if (language == 'zh' && product.plant_part_chinese !== null && product.plant_part_spanish !== '')
                {
                    return product.plant_part_chinese;
                }
                else
                {
                    return product.plant_part;
                }
            }
            else
            {
                return product.plant_part;
            }
        }

        function getMainConstituents(product)
        {
            if (language != 'en')
            {
                if (language == 'es' && product.main_constituents_spanish !== null && product.main_constituents_spanish !== '')
                {
                    return product.main_constituents_spanish;
                }
                else if (language == 'ja' && product.main_constituents_japanese !== null && product.main_constituents_japanese !== '')
                {
                    return product.main_constituents_japanese;
                }
                else if (language == 'zh' && product.main_constituents_chinese !== null && product.main_constituents_spanish !== '')
                {
                    return product.main_constituents_chinese;
                }
                else
                {
                    return product.main_constituents;
                }
            }
            else
            {
                return product.main_constituents;
            }
        }

        function getAromaticDescription(product)
        {
            if (language != 'en')
            {
                if (language == 'es' && product.aromatic_description_spanish !== null && product.aromatic_description_spanish !== '')
                {
                    return product.aromatic_description_spanish;
                }
                else if (language == 'ja' && product.aromatic_description_japanese !== null && product.aromatic_description_japanese !== '')
                {
                    return product.aromatic_description_japanese;
                }
                else if (language == 'zh' && product.aromatic_description_chinese !== null && product.aromatic_description_spanish !== '')
                {
                    return product.aromatic_description_chinese;
                }
                else
                {
                    return product.aromatic_description;
                }
            }
            else
            {
                return product.aromatic_description;
            }
        }

        function getAdditionalFields(additional_fields)
        {
            if (additional_fields.filter(additional_field => additional_field.language === language).length > 0)
            {
                return additional_fields.filter(additional_field => additional_field.language === language);
            }
            else
            {
                return additional_fields.filter(additional_field => additional_field.language === 'en');
            }
        }

        function getDisclaimer(product)
        {
            if (language != 'en')
            {
                if (language == 'es' && product.disclaimer_spanish !== null && product.disclaimer_spanish !== '')
                {
                    return product.disclaimer_spanish;
                }
                else if (language == 'ja' && product.disclaimer_japanese !== null && product.disclaimer_japanese !== '')
                {
                    return product.disclaimer_japanese;
                }
                else if (language == 'zh' && product.disclaimer_chinese !== null && product.disclaimer_spanish !== '')
                {
                    return product.disclaimer_chinese;
                }
                else
                {
                    return product.disclaimer;
                }
            }
            else
            {
                return product.disclaimer;
            }
        }

        function addToCart(product_id)
        {
            if ('{{auth()->check()}}' == 1 && user !== null && localStorage.getItem('address') !== null)
            {
                if (cart === null)
                {
                    axios.post('/cart', {user_id: user.id, create_cart: 1}).then(response => {
                        axios.get('/user-cart/' + user.id).then(response => {
                            if (response.data.data)
                            {
                                localStorage.setItem('cart', JSON.stringify(response.data.data));
                                cart = response.data.data;
                                addProduct(product_id);
                            }
                        }).catch((error) => {
                            if (error.response.status === 401)
                            {
                                localStorage.clear();
                                window.location.reload();
                            }
                        });
                    }).catch((error) => {
                        if (error.response.status === 401)
                        {
                            localStorage.clear();
                            window.location.reload();
                        }
                    });
                }
                else
                {
                    addProduct(product_id);
                }
            }
            else if(user !== null && localStorage.getItem('address') === null)
            {
                window.location.href = '/www/create-account?usertype=' + user.usertype;
            }
            else
            {
                window.location.href = '/www/enrollment';
            }
        }

        function addProduct(id)
        {
            axios.get('/product/'+id).then((response) => {
                if (response.data.data !== null && response.data.data.stock != 'out_of_stock')
                {
                    if (cart.products.findIndex(product => product.product.id === id) > -1)
                    {
                        let product = cart.products.find(product => product.product.id === id);
                        axios.put('/cart/' + product.id, {quantity: product.quantity + 1}).then((response) => {
                            getCart();
                        });
                    }
                    else
                    {
                        axios.post('/cart', {product_id: id, cart_id: cart.id}).then((response) => {
                            getCart();
                        });
                    }
                }
                else
                {
                    alert("Product out of stock");
                }
            });
        }

        function getCart()
        {
            axios.get('/cart/' + cart.id).then((response) => {
                localStorage.setItem('cart', JSON.stringify(response.data.data));
                cart = response.data.data;
                $('.cart-value').text(cart.products.length);
            });
        }

        function getImage(type)
        {
            if (type === 'primary')
            {
                $('#main-image').attr('src', 'https://admin.ultrra.com/product_images/'+product.image);
            }
            else
            {
                $('#main-image').attr('src', 'https://admin.ultrra.com/product_images/'+product.secondary_image);
            }
        }

        function tabClick(tab)
        {
            document.getElementById(tab+'-a').parentNode.parentNode.childNodes.forEach(element => {
                if (element.firstChild.getAttribute('data-id') === tab)
                {
                    element.firstChild.classList.add('active');
                }
                else
                {
                    element.firstChild.classList.remove('active');
                }
            });
            document.getElementById(tab).parentNode.childNodes.forEach(element => {
                console.log(element);
                console.log(tab);
                if (element.id === tab)
                {
                    element.classList.add('active');
                    element.classList.add('show');
                }
                else
                {
                    element.classList.remove('active');
                    element.classList.remove('show');
                }
            });
        }
    </script>
@endpush