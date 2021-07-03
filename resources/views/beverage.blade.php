@extends('layouts.app')

@section('content')
    <div class="subheader normal-bg">
        <div class="container">
            <div class="row">
                <div class="col-xl-7 col-lg-6 align-self-center p-relative">
                    <div class="subheader-text">
                        <div class="page-title">
                            <h1 class="text-custom-white fw-600 text-left"> @lang('beverage.Beverages')</h1>
                            <ul class="custom-flex breadcrumb">
                                <li>
                                    <a href="{{url('/')}}" class="td-none text-custom-white"> @lang('beverage.Home')</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" class="td-none text-custom-white"> @lang('beverage.Products')</a>
                                </li>
                                <li class="text-custom-white active">
                                    @lang('beverage.Beverages')
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div id="product-listing"></div>
    <style>
        h1
        {
            font-size: 2.5rem !important;
        }
        h6
        {
            font-size: 1rem !important;
        }
        p
        {
            font-family: 'Montserrat-Light', sans-serif !important;
            margin-bottom: 1rem !important;
        }
        h1, h4, h6
        {
            margin-bottom: 20px !important;
        }
        ul
        {
            margin-bottom: 1rem !important;
        }
    </style>
@endsection
@push('js')
    <script>
        let language = '{{app()->getLocale()}}';
        window.addEventListener('load', function(){
            axios.get('/all-products', {params: {product_category_id: 16}}).then((response) => {
                let html = '';
                response.data.data.map((product) => {
                    let tag_images = '';
                    product.product_tag_images.map((product_tag_image) => {
                        tag_images += ('<li><img src="https://admin.ultrra.com/ultrra-aromatically/'+product_tag_image+'.png" style="width: 40px"/></li>')
                    });

                    let tick_points = '';
                    getTickPoints(product).map((tick_point) => {
                        tick_points += ('<li>'+tick_point+'</li>')
                    });

                    html += "<div class=\"ul-categories-wrap section-padding\">\n" +
                        "    <div class=\"container\">\n" +
                        "        <div class=\"row row-reverse\">\n" +
                        "            <div class=\"col-md-6 align-self-center text-left\">\n" +
                        "                <div class=\"category-text-box p-relative categories-right\">\n" +
                        "                    <h4>\n" +
                        "                        <a href=\"javascript:void(0)\" class=\"text-pink\">\n" +
                        "                            <span class=\"text-custom-black fw-700\">"+getName(product)+" </span>\n" +
                        "                        </a>\n" +
                        "                    </h4>\n" +
                        "                    <ul class=\"custom-flex pr-tags\">"+tag_images+"</ul>\n" +
                        "                    <h6 class=\"fw-600 text-green\">$"+parseFloat(product.retail_customer_price).toFixed(2)+" <span class=\"fs-18 fw-500 text-custom-black \">(QV "+product.qv+")</span></h6>\n" +
                        "                    <p class=\"text-custom-black product-list fs-18\">"+getDescription(product)+"</p>\n" +
                        "                    <ul class=\"tick-points\">"+tick_points+"</ul>\n" +
                        "                    <a href='{{url('www/supplements/')}}/"+product.id+window.location.search+"' class=\"theme-btn text-brown\"><div class=\"btn-text\">@lang("beverage.Learn More")</div></a>\n" +
                        "                </div>\n" +
                        "            </div>\n" +
                        "            <div class=\"col-md-6\">\n" +
                        "                <div class=\"categories-left mb-sm-80\">\n" +
                        "                    <div class=\"mask-warp\">\n" +
                        "                        <img src='https://admin.ultrra.com/product_images/"+product.image+"' alt=\"img\" />\n" +
                        "                    </div>\n" +
                        "                </div>\n" +
                        "            </div>\n" +
                        "        </div>\n" +
                        "    </div>\n" +
                        "    <hr/>\n" +
                        "    </div>";
                });

                $('#product-listing').html(html);
            });
        });

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
    </script>
@endpush