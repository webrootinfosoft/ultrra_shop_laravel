@extends('layouts.app')

@section('content')
    <div class="subheader normal-bg">
        <div class="container">
            <div class="row">
                <div class="col-xl-7 col-lg-6 align-self-center p-relative">
                    <div class="subheader-text">
                        <div class="page-title">
                            <h1 class="text-custom-white fw-600 text-left">Rare Oils</h1>
                            <ul class="custom-flex breadcrumb">
                                <li>
                                    <a href="{{url('/')}}" class="td-none text-custom-white">Home</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" class="td-none text-custom-white">Products</a>
                                </li>
                                <li class="text-custom-white active">
                                    Rare Oils
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
        window.addEventListener('load', function(){
            axios.get('/all-products', {params: {product_category_id: '{{$id}}'}}).then((response) => {
                let html = '';
                response.data.data.map((product) => {
                    let tag_images = '';
                    product.product_tag_images.map((product_tag_image) => {
                        tag_images += ('<li><img src="https://admin.ultrra.com/ultrra-aromatically/'+product_tag_image+'.png" style="width: 40px"/></li>')
                    });

                    let tick_points = '';
                    product.tick_points.map((tick_point) => {
                        tick_points += ('<li>'+tick_point+'</li>')
                    });

                    html += "<div class=\"ul-categories-wrap section-padding\">\n" +
                        "    <div class=\"container\">\n" +
                        "        <div class=\"row row-reverse\">\n" +
                        "            <div class=\"col-md-6 align-self-center text-left\">\n" +
                        "                <div class=\"category-text-box p-relative categories-right\">\n" +
                        "                    <h4>\n" +
                        "                        <a href=\"javascript:void(0)\" class=\"text-pink\">\n" +
                        "                            <span class=\"text-custom-black fw-700\">"+product.name+" </span>\n" +
                        "                        </a>\n" +
                        "                    </h4>\n" +
                        "                    <ul class=\"custom-flex pr-tags\">"+tag_images+"</ul>\n" +
                        "                    <h6 class=\"fw-600 text-green\">$"+parseFloat(product.retail_customer_price).toFixed(2)+" <span class=\"fs-18 fw-500 text-custom-black \">(QV "+product.qv+")</span></h6>\n" +
                        "                    <p class=\"text-custom-black product-list fs-18\">"+product.description+"</p>\n" +
                        "                    <ul class=\"tick-points\">"+tick_points+"</ul>\n" +
                        "                    <a href='{{url('www/oils/')}}/"+product.id+"' class=\"theme-btn text-brown\"><div class=\"btn-text\">Learn More</div></a>\n" +
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
    </script>
@endpush