@extends('layouts.app')

@section('content')
    <div class="subheader normal-bg">
        <div class="container">
            <div class="row">
                <div class="col-xl-7 col-lg-6 align-self-center p-relative">
                    <div class="subheader-text">
                        <div class="page-title">
                            <h1 class="text-custom-white fw-600 text-left">About Us</h1>
                            <ul class="custom-flex breadcrumb">
                                <li>
                                    <a href="{{url('/')}}" class="td-none text-custom-white">Home</a>
                                </li>
                                <li class="text-custom-white active">
                                    About Us
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="km-aboutus section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 align-self-center">
                    <div class="right-side mb-md-80">
                        <div class="section-header left-side">
                            <div class="section-heading">
                                <h3 class="text-brown fw-700"><span class="text-purple fw-100">Story About</span></h3>
                            </div>
                        </div>
                        <div class="about-desc text-left">
                            <p class="text-light-white fs-16">
                                With this idea the company set out to develop the world’s first high impact, immediately gratifying supplement line, a first class compensation plan, and complete cloud based platform with infrastructure to expand around the world. January 1, 2011 marked the beginnings of our journey as we pioneer and build the most admired company in the world.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="left-side p-relative full-height">
                        <div class="large-image">
                            <img src="{{asset('/images/aboutus.jpg')}}" class="image-fit layer" data-type='parallax' data-depth="0.01" alt="img" />
                        </div>
                        <div class="small-image">
                            <img src="{{asset('/images/aboutus2.jpg')}}" class="image-fit layer" data-type='parallax' data-depth="-0.01" alt="img" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="mission-vision section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-sm-6">
                    <div class="mission-vision-box mb-xs-40">
                        <h5 class="watermark text-dark text-center">Our</h5>
                        <h4 class="text-center text-light-blue">Mission</h4>
                        <p class="fs-16 text-brown no-margin">To grow human potential, and advance society to achieve great things through scientifically documented products produced through the power of nature</p>
                    </div>
                </div>
                <div class="col-lg-4 mb-md-40">
                    <div class="mission-vision-img full-height">
                        <img src="{{asset('/images/map.jpg')}}" class="image-fit" alt="img" />
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="mission-vision-box">
                        <h5 class="watermark text-dark text-center">Our</h5>
                        <h4 class="text-center text-purple">Vision</h4>
                        <p class="fs-16 text-brown no-margin">To inspire humanity is to conitnously give back and build a world in which we discover how great of a difference one person can make</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="product-formula-wrap p-relative">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="formula-top section-padding">
                        <div class="section-header left-side pb-0">
                            <div class="section-heading">
                                <h5 class="watermark text-custom-white">Ultrra</h5>
                                <h3 class="text-custom-white fw-100"><span class="text-light-blue fw-700">Powered by Nature</span></h3>
                            </div>
                        </div>
                        <p class="text-custom-white no-margin text-left">Ultrra searches the world and researches traditional health-care systems for life-giving ingredients in the form of plants, herbs and minerals that support the body’s natural cleansing and rejuvenating functions.</p>
                    </div>
                    <div class="formula-bottom section-padding">
                        <div class="row full-height">
                            <div class="col-md-3 col-6 mb-sm-30">
                                <div class="icon-box full-height">
                                    <img src="{{asset('/images/formula1.png')}}" class="image-fit" alt="icon" />
                                    <div class="hover-text">
                                        <h6 class="no-margin text-custom-white">Unani</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-sm-30">
                                <div class="icon-box full-height">
                                    <img src="{{asset('/images/formula2.png')}}" class="image-fit" alt="icon" />
                                    <div class="hover-text">
                                        <h6 class="no-margin text-custom-white">Tibetan</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="icon-box full-height">
                                    <img src="{{asset('/images/formula3.png')}}" class="image-fit" alt="icon" />
                                    <div class="hover-text">
                                        <h6 class="no-margin text-custom-white">Chinese Herbal</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="icon-box full-height">
                                    <img src="{{asset('/images/formula4.png')}}" class="image-fit" alt="icon" />
                                    <div class="hover-text">
                                        <h6 class="no-margin text-custom-white">Ayur-Veda</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="formula-img">
                        <img src="{{asset('/images/formula5.png')}}" class="image-fit" alt="img" />
                    </div>
                </div>
            </div>
        </div>
    </section>
    <style>
        h1
        {
            font-size: 2.5rem !important;
            margin: 0 0 20px !important;
            font-family: "Lato",sans-serif !important;
        }
    </style>
@endsection
