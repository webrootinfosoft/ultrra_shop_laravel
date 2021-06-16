@extends('layouts.app')

@section('content')
    <div class="ul-top-banner">
        <div class="no-padding">
            <div class="row no-gutters">
                <div class="col-lg-2" style="z-index: 99">
                    <div class="banner-image-box custom ul-testimonial-menu-slider" id="top-banner-first-div">
                        {{--<Slider ref={slick => (this.sliderSide = slick)} {...settingsSide}>--}}
                        <div class="image-wrap nav-item" onclick="slideTo(0)">
                            <a href="javascript:void(0)" class="nav-link">
                                <img src="{{url('/images/banner1.jpg')}}" class="image-fit" alt="img"/>
                            </a>
                        </div>
                        <div class="image-wrap nav-item" onclick="slideTo(1)">
                            <a href="javascript:void(0)" class="nav-link">
                                <img src="{{url('/images/banner2.jpg')}}" class="image-fit" alt="img"/>
                            </a>
                        </div>
                        <div class="image-wrap nav-item" onclick="slideTo(2)">
                            <a href="javascript:void(0)" class="nav-link">
                                <img src="{{url('/images/banner3.jpg')}}" class="image-fit" alt="img"/>
                            </a>
                        </div>
                        <div class="image-wrap nav-item" onclick="slideTo(3)">
                            <a href="javascript:void(0)" class="nav-link">
                                <img src="{{url('/images/banner4.jpg')}}" class="image-fit" alt="img"/>
                            </a>
                        </div>
                        {{--</Slider>--}}
                    </div>
                </div>
                <div class="col-lg-4 align-self-center">
                    <div class="ul-slider-testimonial-sec margin-top-100-minus">
                        <div class="ul-category-slider-wrap mb-xl-40">
                            <div class="menu-slider-wrap p-relative">
                                <div class="menu-slider">
                                    <div id="first-slider">
                                        <div class="slide-item">
                                            <a href="{{url('/www/supplements/103')}}">
                                                <img src="{{url('/images/bestseller5.png')}}" class="image-fit first-slider-image" alt="img"/>
                                            </a>
                                        </div>
                                        <div class="slide-item">
                                            <a href="{{url('/www/supplements/109')}}">
                                                <img src="{{url('/images/bestseller2.png')}}" class="image-fit first-slider-image" alt="img"/>
                                            </a>
                                        </div>
                                        <div class="slide-item">
                                            <a href="{{url('/www/supplements/106')}}">
                                                <img src="{{url('/images/bestseller3.png')}}" class="image-fit first-slider-image" alt="img"/>
                                            </a>
                                        </div>
                                        <div class="slide-item">
                                            <a href="{{url('/www/supplements/108')}}">
                                                <img src="{{url('/images/bestseller4.png')}}" class="image-fit first-slider-image" alt="img"/>
                                            </a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ul-testimonial-wrap mb-md-40">
                            <div class="ul-testimonial-slider">
                                <div id="second-slider">
                                    <div class="slide-item">
                                        <div class="ul-testimonial-item">
                                            <div class="ul-ratings mb-xl-20">
                                                <span class="text-light-yellow"><i class="fas fa-star"></i></span>
                                                <span class="text-light-yellow"><i class="fas fa-star"></i></span>
                                                <span class="text-light-yellow"><i class="fas fa-star"></i></span>
                                                <span class="text-light-yellow"><i class="fas fa-star"></i></span>
                                                <span class="text-light-yellow"><i class="fas fa-star"></i></span>
                                            </div>
                                            <h5 class="fs-20 fw-600 text-custom-white">{{app()->getLocale()}}I was pleasantly surprised at my results with the Detox. I feel lighter, more energized, and balanced. So happy!</h5>
                                            <div class="name-box">
                                                <a href="javascript:void(0)" class="fs-16 text-white td-none">- Chris Hilliard</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="slide-item">
                                        <div class="ul-testimonial-item">
                                            <div class="ul-ratings mb-xl-20">
                                                <span class="text-light-yellow"><i class="fas fa-star"></i></span>
                                                <span class="text-light-yellow"><i class="fas fa-star"></i></span>
                                                <span class="text-light-yellow"><i class="fas fa-star"></i></span>
                                                <span class="text-light-yellow"><i class="fas fa-star"></i></span>
                                                <span class="text-light-yellow"><i class="fas fa-star"></i></span>
                                            </div>
                                            <h5 class="fs-20 fw-600 text-custom-white">At my age I needed to supplement but didn’t want to swallow 25 pills per day. Finding the Genki made life easier.</h5>
                                            <div class="name-box">
                                                <a href="javascript:void(0)" class="fs-16 text-white td-none">- Raul Zuniga</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="slide-item">
                                        <div class="ul-testimonial-item">
                                            <div class="ul-ratings mb-xl-20">
                                                <span class="text-light-yellow"><i class="fas fa-star"></i></span>
                                                <span class="text-light-yellow"><i class="fas fa-star"></i></span>
                                                <span class="text-light-yellow"><i class="fas fa-star"></i></span>
                                                <span class="text-light-yellow"><i class="fas fa-star"></i></span>
                                                <span class="text-light-yellow"><i class="fas fa-star"></i></span>
                                            </div>
                                            <h5 class="fs-20 fw-600 text-custom-white">I am glad I was introduced to the Slim, it helps me maintain my weight without the caffeine jitters and heartrate.</h5>
                                            <div class="name-box">
                                                <a href="javascript:void(0)" class="fs-16 text-white td-none">- Sana Awada</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="slide-item">
                                        <div class="ul-testimonial-item">
                                            <div class="ul-ratings mb-xl-20">
                                                <span class="text-light-yellow"><i class="fas fa-star"></i></span>
                                                <span class="text-light-yellow"><i class="fas fa-star"></i></span>
                                                <span class="text-light-yellow"><i class="fas fa-star"></i></span>
                                                <span class="text-light-yellow"><i class="fas fa-star"></i></span>
                                                <span class="text-light-yellow"><i class="fas fa-star"></i></span>
                                            </div>
                                            <h5 class="fs-20 fw-600 text-custom-white">Ultrra Enzymes allow me to eat normally again, without bloating and indigestion. Thank you!</h5>
                                            <div class="name-box">
                                                <a href="javascript:void(0)" class="fs-16 text-white td-none">- Xiao Chen</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="full-video-sec">
                        <div class="video-box p-relative">
                            <video playsinline class="full-video" autoplay muted="muted" loop="loop">
                                <source src="/images/video-bg.mp4" type="video/mp4" />
                            </video>
                            <div class="overlay overlay-bg-black"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="bg-light-white p-relative about-products">
        <div id="about-products-slider" class="about-products-slider arrow-style-3 text-left">
            <div class="slide-item section-padding-top">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="about-product-wrap">
                                <img src="/images/chamomile.png" class="strawberry" alt="img" />
                                <img src="/images/featured-product2.png" class="lady" alt="img" />
                                <img src="/images/leaf.png" class="chilly" alt="img" />
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="about-product-wrap mb-xl-80">
                                <div class="about-products-text">
                                    <p>
                                        Now Available For
                                        <span class="text-light-blue"></span>
                                    </p>
                                    <h2>
                                        <span class="light">Ultrra</span>
                                        Rare Oils <br/>
                                        Overpower
                                    </h2>
                                    <p style="font-weight: bold">Essential Oil Protection Blend</p>
                                    <p>Overpower is an Ultrra Rare blend of oils that support a healthy environment by combining natural germ fighting essential oils to help support your body's natural immune response.</p>
                                    <a href="{{url('/www/oils/118')}}" class="theme-btn"><span class="btn-text">Buy Now</span></a>
                                </div>
                                <img src="/images/cookie.png" class="cookie" alt="img" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="slide-item section-padding-top">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="about-product-wrap">
                                <img src="/images/metal.png" class="strawberry" alt="img" />
                                <img src="/images/featured-product3.png" class="lady" alt="img" />
                                <img src="/images/lemon.png" class="chilly" alt="img" />
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="about-product-wrap mb-xl-80">
                                <div class="about-products-text">
                                    <p>
                                        Now Available For
                                    </p>
                                    <h2>
                                        <span class="light">Ultrra</span>
                                        Elements <br/>
                                        R5
                                    </h2>
                                    <p style="font-weight: bold">Joint Support Chews</p>
                                    <p>Ultrra's unique formulation helps support joint comfort after exercise, promoting cartilage regeneration, while providing the necessary building blocks for optimal joint lubrication. </p>
                                    <a href="{{url('/www/supplements/113')}}" class="theme-btn"><span class="btn-text">Buy Now</span></a>
                                </div>
                                <img src="/images/cookie.png" class="cookie" alt="img" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="slide-item section-padding-top">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="about-product-wrap">
                                <img src="/images/strawberry.png" class="strawberry" alt="img" />
                                <img src="/images/featured-product1.png" class="lady" alt="img" />
                                <img src="/images/olive.png" class="chilly" alt="img" />
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="about-product-wrap mb-xl-80">
                                <div class="about-products-text">
                                    <p>
                                        Now Available For
                                    </p>
                                    <h2>
                                        <span class="light">Ultrra</span>
                                        Elements <br/>
                                        APS
                                    </h2>
                                    <p style="font-weight: bold">Active Protection Serum</p>
                                    <p>Ultrra APS contains powerful nutrients and elements known to support a healthy immune system, enhanced with Monolaurin and Colloidal Silver.</p>
                                    <a href="{{url('/www/supplements/112')}}" class="theme-btn"><span class="btn-text">Buy Now</span></a>
                                </div>
                                <img src="/images/cookie.png" class="cookie" alt="img" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="seprator-sec section-padding bg-light-blue-gr">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="seprator-text text-center">
                        <div class="section-header">
                            <div class="section-heading">
                                <h5 class="watermark text-custom-white">Ultrra</h5>
                                <h3 class="text-custom-white fw-700"> Feel <span class="text-brown fw-100">the Difference</span></h3>
                            </div>
                        </div>
                        <p class="text-custom-white fs-16">Ultrra provides you with exclusive an product-line, expert training resources and pro marketing materials to build your business. Whether you’re looking to earn some part-time income or grow your operations ...</p>
                        <div class="img-box mb-xl-20">
                            <img src="/images/img-clg.png" class="img-fluid" alt="img" />
                        </div>
                        <a href="{{url('/www/research')}}" class="theme-btn bg-custom-black border-0"><span class="btn-text">Read More</span></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="ul-two-product-sec">
        <div class="no-padding">
            <div class="row no-gutters">
                <div class="col-lg-8">
                    <div class="ult-product-box normal-bg image-1">
                        <div class="overlay overlay-bg-black"></div>
                        <div class="text-wrap">
                            <div class="section-header left-side">
                                <div class="section-heading">
                                    <h3 class="text-custom-white fw-700"><span class="fs-20">THE ULTRRA DETOX</span> <br/> 24 Hours to a<br/> BETTER YOU</h3>
                                    <a href="{{url('/www/supplements/241')}}" class="theme-btn"><span class="btn-text">Learn More</span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="ult-product-box normal-bg image-2">
                        <div class="overlay overlay-bg-black"></div>
                        <div class="text-wrap">
                            <div class="section-header left-side">
                                <div class="section-heading">
                                    <h3 class="text-custom-white fw-700">
                                        <span class="fs-20">The 15/15 Challenge</span><br/>Lose up to 15 pounds <br/>in 15 Days</h3>
                                    <a href="javascript:void(0)" class="theme-btn"><span class="btn-text">Start Today</span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="ul-full-image parallax section-padding">
        <div class="overlay overlay-bg-pink-gr"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="ul-full-image-text text-left">
                        <div class="section-header left-side">
                            <div class="section-heading">
                                <h5 class="watermark text-custom-white">Ultrra</h5>
                                <h3 class="text-custom-white fw-700"><span class="text-brown fw-100">Unlease Your</span> Potential</h3>
                            </div>
                        </div>
                        <p class="text-custom-white fs-16">ULTRRA’S MISSION is to grow human potential, and advance society to achieve great things through scientifically documented products produced by the power of nature.</p>
                        <a href="{{url('/www/about')}}" class="theme-btn btn-style-2 border-0 bg-custom-black"><div class="btn-text">Learn More</div></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="ul-category-slider-sec section-padding" style="padding-bottom: 0;">
        <div class="container">
            <div class="section-header">
                <div class="section-heading">
                    <h5 class="watermark text-dark">Ultrra</h5>
                    <h3 class="text-brown fw-700"> Categories</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="ul-category-slider text-left row">

                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="container" style="padding-bottom: 80px">
        <div id="category-slider">
            <div class="slide-item col-12" onclick="gotoCat('nutritional')">
                <div class="ul-category-box p-relative">
                    <div class="ul-category-img animate-img">
                        <a href="{{url('/www/nutritional')}}">
                            <img src="https://via.placeholder.com/255x270" class="image-fit" alt="img"  style="visibility: hidden"/>
                        </a>
                        <div class="overlay overlay-bg-blue-gr"></div>
                    </div>
                    <div class="ul-category-title text-center">
                        <h4 class="fw-600 title"><a href="{{url('/www/nutritional')}}" class="text-custom-black">Nutritionals</a></h4>
                        <p class="no-margin text-custom-black description fs-16 text-capitalize">essences of nature</p>
                    </div>
                    <div class="ul-category-img-2">
                        <img src="/images/product2.png" class="image-fit" alt="img" />
                    </div>
                </div>
            </div>
            <div class="slide-item col-12" onclick="gotoCat('rare-oil/15')">
                <div class="ul-category-box p-relative">
                    <div class="ul-category-img animate-img">
                        <a href="{{url('/www/rare-oil/15')}}">
                            <img src="https://via.placeholder.com/255x270" class="image-fit" alt="img" style="visibility: hidden"/>
                        </a>
                        <div class="overlay overlay-bg-blue-gr"></div>
                    </div>
                    <div class="ul-category-title text-center">
                        <h4 class="fw-600 title"><a href="{{url('/www/rare-oil/15')}}" class="text-custom-black">Rare Oils</a></h4>
                        <p class="no-margin text-custom-black description fs-16 text-capitalize">better health and fitness</p>
                    </div>
                    <div class="ul-category-img-2">
                        <img src="/images/product5.png" class="image-fit" alt="img" />
                    </div>
                </div>
            </div>
            <div class="slide-item col-12" onclick="gotoCat('beverage')">
                <div class="ul-category-box p-relative">
                    <div class="ul-category-img animate-img">
                        <a href="{{url('/www/beverage')}}">
                            <img src="https://via.placeholder.com/255x270" class="image-fit" alt="img"  style="visibility: hidden"/>
                        </a>
                        <div class="overlay overlay-bg-pink-gr"></div>
                    </div>
                    <div class="ul-category-title text-center">
                        <h4 class="fw-600 title"><a href="{{url('/www/beverage')}}" class="text-custom-black">Beverages</a></h4>
                        <p class="no-margin text-custom-black description fs-16 text-capitalize">energy, balance, focus</p>
                    </div>
                    <div class="ul-category-img-2">
                        <img src="/images/product4.png" class="image-fit" alt="img" />
                    </div>
                </div>
            </div>
            <div class="slide-item col-12" onclick="gotoCat('element')">
                <div class="ul-category-box p-relative">
                    <div class="ul-category-img animate-img">
                        <a href="{{url('/www/element')}}">
                            <img src="https://via.placeholder.com/255x270" class="image-fit" alt="img" style="visibility: hidden"/>
                        </a>
                        <div class="overlay overlay-bg-pink-gr"></div>
                    </div>
                    <div class="ul-category-title text-center">
                        <h4 class="fw-600 title"><a href="{{url('/www/element')}}" class="text-custom-black">Elements</a></h4>
                        <p class="no-margin text-custom-black description fs-16 text-capitalize">immune support and joint health</p>
                    </div>
                    <div class="ul-category-img-2">
                        <img src="/images/featured-product3.png" class="image-fit" alt="img" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="ul-hack-time-sec section-padding normal-bg">
        <div class="overlay overlay-bg-black"></div>
        <div class="container">
            <div class="section-header">
                <div class="section-heading">
                    <h5 class="watermark text-custom-white">Ultrra</h5>
                    <h3 class="text-custom-white fw-700 no-margin">Hack Your <span class="text-light-blue">Free Time </span></h3>

                </div>
                <em class="text-custom-white fw-700 mb-xl-20"> & Work from home</em>
            </div>
            <div class="row justify-content-center mb-xl-40">
                <div class="col-lg-10 text-left">
                    <p class="text-custom-white">A decade ago we made a bold choice – not to put our amazing products on store shelves. </p>
                    <p class="text-custom-white fw-600">We knew our success wasn’t going to be based on fancy packaging or marketing. It depended on our customers’ success stories and product experience. So we set out to change the status quo, and created an opportunity for any customer to win through better health, and for all who told our story to win through better wealth. </p>
                    <p class="text-custom-white">We know what it takes to launch and run a successful business, so we made it easier and simpler to start a business with Ultrra. Whether you're just looking to earn some extra pocket money or want to build your own full-time business, Ultrra is the ideal side gig. Ultrra independent business owners are able to earn amazing rewards:</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-sm-6">
                    <div class="ul-hack-box p-relative text-center mb-xl-40">
                        <div class="image-wrap mb-xl-20 animate-img">
                            <img src="/images/rewards.jpg" class="rounded-circle image-fit" alt="img" />
                        </div>
                        <h6 class="text-custom-white">Profits & Rewards</h6>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="ul-hack-box p-relative text-center mb-xl-40">
                        <div class="image-wrap mb-xl-20 animate-img">
                            <img src="/images/lifestyle.jpg" class="rounded-circle image-fit" alt="img" />
                        </div>
                        <h6 class="text-custom-white">Lifestyle Gateways</h6>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="ul-hack-box p-relative text-center mb-xl-40">
                        <div class="image-wrap mb-xl-20 animate-img">
                            <img src="/images/bonouses.jpg" class="rounded-circle image-fit" alt="img" />
                        </div>
                        <h6 class="text-custom-white">Achievement Bonuses</h6>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="ul-hack-box p-relative text-center mb-xl-40">
                        <div class="image-wrap mb-xl-20 animate-img">
                            <img src="/images/cars.jpg" class="rounded-circle image-fit" alt="img" />
                        </div>
                        <h6 class="text-custom-white">Luxury Car Perks</h6>
                    </div>
                </div>
                <div class="col-12 text-center">
                    <a href="{{url('/www/opportunity')}}" class="theme-btn"><span class="btn-text">Read More</span></a>
                </div>
            </div>
        </div>
    </section>
    <section class="section-padding text-testimonials">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-header">
                        <div class="section-heading">
                            <h5 class="watermark text-dark">Ultrra</h5>
                            <h3 class="text-brown fw-700"><span class="fw-100 text-purple">What People</span> Are Saying</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-sm-6">
                            <div class="text-testimonials-box">
                                <div class="testimonial-img mb-xl-20">
                                </div>
                                <div class="ratings mb-xl-20">
                                    <span class="text-light-yellow fs-20"><i class="fas fa-star"></i></span>
                                    <span class="text-light-yellow fs-20"><i class="fas fa-star"></i></span>
                                    <span class="text-light-yellow fs-20"><i class="fas fa-star"></i></span>
                                    <span class="text-light-yellow fs-20"><i class="fas fa-star"></i></span>
                                    <span class="text-light-yellow fs-20"><i class="fas fa-star"></i></span>
                                </div>
                                <p class="fs-16">“I was pleasantly surprised at my results with the Detox. I feel lighter, more energized, and balanced. So happy!”</p>
                                <h6 class="no-margin fw-600 text-purple">Chris Hilliard</h6>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="text-testimonials-box">
                                <div class="testimonial-img mb-xl-20">
                                </div>
                                <div class="ratings mb-xl-20">
                                    <span class="text-light-yellow fs-20"><i class="fas fa-star"></i></span>
                                    <span class="text-light-yellow fs-20"><i class="fas fa-star"></i></span>
                                    <span class="text-light-yellow fs-20"><i class="fas fa-star"></i></span>
                                    <span class="text-light-yellow fs-20"><i class="fas fa-star"></i></span>
                                    <span class="text-light-yellow fs-20"><i class="fas fa-star"></i></span>
                                </div>
                                <p class="fs-16">“At my age I needed to supplement but didn’t want to swallow 25 pills per day. Finding the Genki made life easier, I mix one packet with my orange juice, and done. Best part is, it tastes amazing!”</p>
                                <h6 class="no-margin fw-600 text-purple">Raul Zuniga</h6>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="text-testimonials-box">
                                <div class="testimonial-img mb-xl-20">
                                </div>
                                <div class="ratings mb-xl-20">
                                    <span class="text-light-yellow fs-20"><i class="fas fa-star"></i></span>
                                    <span class="text-light-yellow fs-20"><i class="fas fa-star"></i></span>
                                    <span class="text-light-yellow fs-20"><i class="fas fa-star"></i></span>
                                    <span class="text-light-yellow fs-20"><i class="fas fa-star"></i></span>
                                    <span class="text-light-yellow fs-20"><i class="fas fa-star"></i></span>
                                </div>
                                <p class="fs-16">“I am glad I was introduced to the Slim, it helps me maintain my weight without the caffeine jitters and heartrate effects of other fat burners I’ve used, and I love that it’s natural.”</p>
                                <h6 class="no-margin fw-600 text-purple">Sana Awada</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section-padding awards-sec bg-img-black">
        <div class="container">
            <div class="section-header">
                <div class="section-heading">
                    <h5 class="watermark text-custom-white">Ultrra</h5>
                    <h3 class="text-custom-white fw-700"><span class="text-purple fw-100">Be Your</span> Best Today</h3>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="text-center ul-dist-shop-box mb-xs-40">
                        <label class="text-custom-white fs-16 mb-xl-20">As a Customer...</label>
                        <a href="{{url('/www/enrollment')}}" class="theme-btn full-width"><span class="btn-text">Shop Now</span></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="text-center ul-dist-shop-box style-2">
                        <label class="text-custom-white fs-16 mb-xl-20">As an Entrepreneur...</label>
                        <a href="{{url('/www/enrollment')}}" class="theme-btn full-width"><span class="btn-text">Earn Today</span></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('js')
    <script>
        let first_slider, second_slider;
        window.addEventListener('load', function(){
            $('#top-banner-first-div').slick({
                slidesToShow: 4,
                slidesToScroll: 1,
                arrows: false,
                dots: false,
                autoplay: false,
                infinite: false,
                // useTransform: false,
                vertical: true,
                focusOnSelect: true,
                asNavFor: '#first-slider,#second-slider',
                easing: 'linear',
                responsive: [{
                    breakpoint: 991,
                    settings: {
                        vertical: false,
                        useTransform: false,
                    }
                },{
                    breakpoint: 768,
                    settings: {
                        vertical: false,
                        useTransform: false,
                        autoplay: true,
                        speed: 500,
                        slidesToShow: 3,
                    }
                },{
                    breakpoint: 576,
                    settings: {
                        vertical: false,
                        useTransform: false,
                        slidesToShow: 2,
                    }
                }]
            });

            first_slider = $('#first-slider').slick({
                infinite: true,
                slidesToShow: 1,
                adaptiveHeight: true,
                speed: 500,
                prevArrow: '<i class="fas fa-chevron-left fa-4x" style="position: absolute; top: 50%; left: 0;"></i>',
                nextArrow: '<i class="fas fa-chevron-right fa-4x text-right" style="position: absolute; top: 50%; right: 0;"></i>',
                asNavFor: '#second-slider',
            });

            second_slider = $('#second-slider').slick({
                arrows: false,
                dots: false,
                infinite: true,
                speed: 500,
                slidesToShow: 1,
                slidesToScroll: 1
            });

            $('#first-slider').on('beforeChange', function(event, slick, current, next){
                $('#top-banner-first-div .slick-slide').each((function (i, element) {
                    if (i === next)
                    {
                        $(element).addClass('slick-current');
                    }
                    else
                    {
                        $(element).removeClass('slick-current');
                    }
                }));
            });

            $('#about-products-slider').slick({
                customPaging: function(slick,i) {
                    let image = i < 2 ? i + 2 : 1;
                    return '<a href="javascript:void(0)"><img src="/images/featured-product'+image+'.png"/></a>';
                },
                dots: true,
                dotsClass: "slick-dots slick-thumb second-slider-ul col-md-10 offset-md-1 col-lg-10 offset-lg-1 mb-0",
                infinite: true,
                speed: 500,
                slidesToShow: 1,
                slidesToScroll: 1
            });

            $('#category-slider').slick({
                slidesToShow: 4,
                slidesToScroll: 1,
                arrows: true,
                dots: false,
                infinite: true,
                autoplay: true,
                autoplaySpeed: 4000,
                speed: 2000,
                draggable: true,
                easing: 'linear',
                pauseOnHover: true,
                responsive: [{
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 3,
                    }
                },{
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 2,
                    }
                },{
                    breakpoint: 576,
                    settings: {
                        slidesToShow: 1,
                    }
                }]
            });
        });

        function slideTo(index)
        {
            $('#first-slider').slick('slickGoTo', index);
            $('#second-slider').slick('slickGoTo', index);
            $('#top-banner-first-div .slick-slide').each((function (i, element) {
                if (i === index)
                {
                    $(element).addClass('slick-current');
                }
                else
                {
                    $(element).removeClass('slick-current');
                }
            }));
        }

        function gotoCat(category)
        {

        }
    </script>
@endpush
