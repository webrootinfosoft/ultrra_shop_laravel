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
        let cart = JSON.parse(localStorage.getItem('cart'));
        let image, secondary_image, product;
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
                response.data.data.tick_points.map((tick_point) => {
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
                    '                                            <div style="font-size: 50px; font-weight: 800; color: #8ec41a">' + response.data.data.name + '</div>\n' +
                    '                                            <div style="font-size: 25px; font-weight: 500; color: #333333">' + response.data.data.category + '</div>\n' +
                    '                                            <span class="fs-18 text-brown fw-600 mr-3">\n' + product_tag_images + '</span>\n' +
                    '                                            <h6 class="text-custom-black fw-600">$' + response.data.data.retail_customer_price + ' <span class="fw-500" style="font-size: 14px">(QV ' + response.data.data.qv + ')</span></h6>\n' +
                    '                                            <p>' + response.data.data.description + '</p>\n' +
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

                $('#disclaimer').html(product.disclaimer)

                if (product.country_of_origin !== null && product.country_of_origin !== '')
                {
                    $('#country_of_origin').html('<p class="text-dark feature-origin fs-16 fw-700">\n' +
                        '                                Origin: <span class="country-origin fw-100">'+product.country_of_origin+'</span>\n' +
                        '                            </p>');
                }
                if (product.collection_method !== null && product.collection_method !== '')
                {
                    $('#collection_method').html('<p class="text-dark feature-origin fs-16 fw-700">\n' +
                        '                                Collection Method: <span class="country-origin fw-100">'+product.collection_method+'</span>\n' +
                        '                            </p>');
                }
                if (product.plant_part !== null && product.plant_part !== '')
                {
                    $('#plant_part').html('<p class="text-dark feature-origin fs-16 fw-700">\n' +
                        '                                Plant Part: <span class="country-origin fw-100">'+product.plant_part+'</span>\n' +
                        '                            </p>');
                }
                if (product.main_constituents !== null && product.main_constituents !== '')
                {
                    $('#main_constituents').html('<p class="text-dark feature-origin fs-16 fw-700">\n' +
                        '                                Main Constituents: <span class="country-origin fw-100">'+product.main_constituents+'</span>\n' +
                        '                            </p>');
                }
                if (product.aromatic_description !== null && product.aromatic_description !== '')
                {
                    $('#aromatic_description').html('<p class="text-dark feature-origin fs-16 fw-700">\n' +
                        '                                Aromatic Description: <span class="country-origin fw-100">'+product.aromatic_description+'</span>\n' +
                        '                            </p>');
                }
            });
        });

        function addToCart(product_id)
        {

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

        function getAdditionalFields(additional_fields)
        {
            return additional_fields.filter(additional_field => additional_field.language === 'en');
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