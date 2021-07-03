@extends('layouts.app')

@section('content')
    <div class="subheader normal-bg">
        <div class="container">
            <div class="row">
                <div class="col-xl-7 col-lg-6 align-self-center p-relative">
                    <div class="subheader-text">
                        <div class="page-title">
                            <h1 class="text-custom-white fw-600 text-left">@lang('oppurtunity.Opportunity')</h1>
                            <ul class="custom-flex breadcrumb">
                                <li>
                                    <a href="{{url('/')}}" class="td-none text-custom-white">@lang('oppurtunity.Home')</a>
                                </li>
                                <li class="text-custom-white active">
                                    @lang('oppurtunity.Opportunity')
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="section-padding opportunity-sec-1 bg-light-white">
        <div class="container">
            <div class="row text-left">
                <div class="col-lg-6">
                    <div class="left-side mb-md-40">
                        <div class="section-header left-side pb-0">
                            <div class="section-heading">
                                <h3 class="text-brown fw-700"><span class="text-purple fw-100"> Ultrra @lang('oppurtunity.Rewards')</span> Plan</h3>
                            </div>
                        </div>
                        <p class="fs-16">@lang('oppurtunity.The Ultrra Rewards Plan is one of the most aggressive compensation plans in the industry. Our Distributors are able to generate income in six different ways, qualify for All-Expenses Paid luxury vacations and climb 10 prestigious achievement ranks').</p>
                        <ul class="custom-flex icon-text row">
                            <li class="col-auto text-center">
                                <div class="icon-box text-purple">
                                    <i class="fal fa-mobile-android"></i>
                                </div>
                                <span>@lang('oppurtunity.Business App')</span>
                            </li>
                            <li class="col-auto text-center">
                                <div class="icon-box text-purple">
                                    <i class="fal fa-tools"></i>
                                </div>
                                <span>@lang('oppurtunity.Tracking Backoffice')</span>
                            </li>
                            <li class="col-auto text-center">
                                <div class="icon-box text-purple">
                                    <i class="fal fa-book-open"></i>
                                </div>
                                <span>@lang('oppurtunity.Free Training')</span>
                            </li>
                            <li class="col-auto text-center">
                                <div class="icon-box text-purple">
                                    <i class="fal fa-tv"></i>
                                </div>
                                <span>@lang('oppurtunity.Personalized Website')</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="right-side">
                        <div class="section-header left-side pb-0">
                            <div class="section-heading">
                                <h3 class="text-brown fw-700"><span class="text-purple fw-100">@lang('oppurtunity.A Rewarding Lifestyle')</span> @lang('oppurtunity.Perks')</h3>
                            </div>
                        </div>
                        <p class="fs-16">@lang('oppurtunity.I deserve more, and will have more if you have ever felt an inner desire to do more, then you\'re in the right place At Ultrra, the lifestyle goes beyond just monetary perks, experience more lifestyle by partnering with our team!')</p>
                        <ul class="custom list-text">
                            <li>@lang('oppurtunity.Time, freedom & financial security')</li>
                            <li>@lang('oppurtunity.Exotic travel destinations and bonuses')</li>
                            <li>@lang('oppurtunity.The chance to give back')</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section-padding accordion-2 opportunity-sec-1 Product-cloud">
        <div class="container">
            <div class="row text-left">
                <div class="col-lg-6">
                    <div class="left-side accordion-2-collapse mb-md-40">
                        <div class="section-header left-side pb-0">
                            <div class="section-heading">
                                <h3 class="text-brown fw-700"><span class="text-purple fw-100">@lang('oppurtunity.Life-Enhancement')</span> @lang('oppurtunity.Products')</h3>
                            </div>
                        </div>
                        <div id="product-accordion">
                            <div class="card">
                                <div class="card-header">
                                    <a class="card-link collapsed" data-toggle="collapse" href="javascript:void(0)" id="management-a" onclick="toggleAccordian('management')">
                                        @lang('oppurtunity.Weight Management')
                                    </a>
                                </div>
                                <div id="management" class="collapse" data-parent="#product-accordion">
                                    <div class="card-body">
                                        <p class="text-brown fs-16 no-margin">@lang('oppurtunity.The global weight loss and weight management market is expected to reach $206.4 billion by 2019 from $148.1 billion in 2014, growing at a CAGR of 6.9% from 2014 to 2019').</p>
                                        <p class="text-brown fs-16 no-margin">Source :&nbsp;
                                            <a href="http://www.researchandmarkets.com/research/xvpflx/weight_loss_and" target="_blank">http://www.researchandmarkets.com/research</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <a class="collapsed card-link" data-toggle="collapse" href="javascript:void(0)" id="nutritio-a" onclick="toggleAccordian('nutritio')">
                                        @lang('oppurtunity.Nutrition')
                                    </a>
                                </div>
                                <div id="nutritio" class="collapse" data-parent="#product-accordion">
                                    <div class="card-body">
                                        <p class="text-brown fs-16 no-margin">@lang('oppurtunity.According to the estimates of the Nutrition Business Journal report, the global nutrition and supplements market stood at US$96 billion as of 2012. A year later, it was approximately US$104 billion globally. Going forward, the market is expected to show a CAGR between 6% and 7%')'.</p>
                                        <p class="text-brown fs-16 no-margin">Source :&nbsp;
                                            <a href="https://globenewswire.com/news-release/2015/01/27/700276/10117198/en/Global-Nutrition-and-Supplements-Market-History-Industry-Growth-and-Future-Trends-by-PMR.html" target="_blank">https://globenewswire.com/news-release/2015/01/27/700276/10117198/en/Global-Nutrition-and-Supplements-Market-History-Industry-Growth-and-Future-Trends-by-PMR.html</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <a class="collapsed card-link" data-toggle="collapse" href="javascript:void(0)" id="energy-drinks-a" onClick="toggleAccordian('energy-drinks')">
                                        @lang('oppurtunity.Energy Drinks')
                                    </a>
                                </div>
                                <div id="energy-drinks" class="collapse" data-parent="#product-accordion">
                                    <div class="card-body">
                                        <p class="text-brown fs-16 no-margin">@lang('oppurtunity.Global energy drinks market is expected to witness a high growth on account of growing consumer’s health consciousness hectic lifestyle. The overall market is projected to grow at an approximate CAGR of 10% from 2016 to 2024').</p>
                                        <p class="text-brown fs-16 no-margin">Source :&nbsp;
                                            <a href="http://www.grandviewresearch.com/industry-analysis/energy-drinks-market" target="_blank">http://www.grandviewresearch.com/industry-analysis/energy-drinks-market</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="right-side">
                        <div class="section-header left-side pb-0">
                            <div class="section-heading">
                                <h3 class="text-brown fw-700"><span class="text-purple fw-100">@lang('oppurtunity.Cloud')</span> @lang('oppurtunity.Technology')</h3>
                            </div>
                        </div>
                        <p class="fs-18 fw-700 mb-1">@lang('oppurtunity.The ultimate work from home business model')</p>
                        <p class="fs-16">@lang('oppurtunity.The Ultrra Cloud business approach is the best in class, most efficient, and the most lucrative operations foundation framework ever It allows Ultrra to operate with the lowest corporate overhead and yet have one of the fastest response and problem solving operating systems in the market It allows for efficient manageability with fast pace developments').</p>
                        <p class="fs-18 fw-700 mb-1">@lang('oppurtunity.The winning formula')</p>
                        <p class="fs-16">@lang('oppurtunity.Many brick and mortar Direct Sales companies are bloated with staff and employees that come with huge costs buildings and structures, W-2s, supervisors, hiring, firing, staffing, secretaries, middle managers, utility bills This adds up to high overhead, high cost of business and results in low distributor commissions The Ultrra business is the reverse, streamlining the whole process to help our distributor team earn more profits for their efforts').</p>
                        <p class="fs-18 fw-700 mb-1">@lang('oppurtunity.Business on the go')&nbsp; - <span class="fs-16 fw-400">@lang('oppurtunity.Your Ultrra office has all the tools to help you grow'):</span></p>
                        <ul class="custom list-text">
                            <li>@lang('oppurtunity.Order Tracking')</li>
                            <li>@lang('oppurtunity.Shipping')</li>
                            <li>@lang('oppurtunity.Commissions')</li>
                            <li>@lang('oppurtunity.Customer & Promoter Support')</li>
                            <li>@lang('oppurtunity.Team Sales & Calculations')</li>
                            <li>@lang('oppurtunity.Team Genealogy')</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section-padding bg-light-white p-relative opportunity-sec-2 opp-img-2">
        <div class="container">
            <div class="row text-left">
                <div class="col-lg-6 align-self-center mb-md-80">
                    <div class="text-section padding-20">
                        <div class="section-header left-side pb-0">
                            <div class="section-heading">
                                <h5 class="watermark text-dark">Ultrra</h5>
                                <h3 class="text-brown fw-700"><span class="text-purple fw-100">@lang('oppurtunity.Rewards')</span> Plan</h3>
                            </div>
                        </div>
                        <p class="fs-16">@lang('oppurtunity.Download the details to the Ultrra Rewards Plan for the steps you can take to build an online, homebased business').</p>
                        <a href="{{app()->getLocale() == 'en' ? 'https://drive.google.com/file/d/1-9xqJocW9_lPzwuIWSJGfDBLtvzyinGM/view?usp=sharing' : 'https://drive.google.com/file/d/1sUwNltzuxuH5DAryFtSS8zuDM3Vp4gHN/view?usp=sharing'}}" target="_blank" class="theme-btn btn-style-3 mb-2"><div class="btn-text">@lang('oppurtunity.Download Summary')</div></a>
                        &nbsp;&nbsp;<a href="{{app()->getLocale() == 'en' ? 'https://drive.google.com/file/d/1CdjCBSn1XaZQbPwZdcbxfye9DhYdvAdM/view?usp=sharing' : 'https://drive.google.com/file/d/1jndVncgH_WRTxi-t4NFTMhe_f16GW6Wq/view?usp=sharing'}}" target="_blank" class="theme-btn btn-style-3"><div class="btn-text">@lang('oppurtunity.Download Plan')</div></a>
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
                    <h3 class="text-custom-white fw-700"><span class="text-purple fw-100">@lang('oppurtunity.Be Your')</span> @lang('oppurtunity.Best Today')</h3>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="text-center ul-dist-shop-box mb-xs-40">
                        <label class="text-custom-white fs-16 mb-xl-20">@lang('oppurtunity.As a Customer')...</label>
                        <a href="{{url('/www/enrollment')}}" class="theme-btn full-width"><span class="btn-text">@lang('oppurtunity.Shop Now')</span></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="text-center ul-dist-shop-box style-2">
                        <label class="text-custom-white fs-16 mb-xl-20">@lang('oppurtunity.As an Entrepreneur')...</label>
                        <a href="{{url('/www/enrollment')}}" class="theme-btn full-width"><span class="btn-text">@lang('oppurtunity.Earn Today')</span></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section-padding bg-light-white opportunity-sec-3">
        <div class="container">
            <div class="section-header">
                <div class="section-heading">
                    <h5 class="watermark text-dark">@lang('translations.Ultrra')</h5>
                    <h3 class="text-brown fw-700"><span class="text-purple fw-100">@lang('oppurtunity.Build Your')</span> @lang('oppurtunity.Business')</h3>
                </div>
            </div>
            <div class="row text-left">
                <div class="col-lg-4 col-md-6">
                    <div class="build-business-box mb-xl-30">
                        <div class="build-business-img animate-img">
                            <a href="javascript:void(0)">
                                <img src="{{asset('/images/opportunity3.png')}}" class="image-fit" alt="img" />
                            </a>
                        </div>
                        <div class="build-business-meta padding-20 bg-custom-white">
                            <h5 class="fw-600"><a href="javascript:void(0)">@lang('oppurtunity.Work from Your Phone')</a></h5>
                            <p class="no-margin">@lang('oppurtunity.Attend trainings, engage with customers, organize with team members all while on the go').</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="build-business-box mb-xl-30">
                        <div class="build-business-img animate-img">
                            <a href="javascript:void(0)">
                                <img src="{{asset('/images/opportunity4.png')}}" class="image-fit" alt="img" />
                            </a>
                        </div>
                        <div class="build-business-meta padding-20 bg-custom-white">
                            <h5 class="fw-600"><a href="javascript:void(0)">@lang('oppurtunity.Share and Earn')</a></h5>
                            <p class="no-margin">Your business launch is ready the moment you enroll. Start earning immediately by sharing your own custom link with customers around the world. </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="build-business-box mb-xl-30">
                        <div class="build-business-img animate-img">
                            <a href="javascript:void(0)">
                                <img src="{{asset('/images/opportunity1.png')}}" class="image-fit" alt="img" />
                            </a>
                        </div>
                        <div class="build-business-meta padding-20 bg-custom-white">
                            <h5 class="fw-600"><a href="javascript:void(0)">@lang('oppurtunity.No Experience Needed')</a></h5>
                            <p class="no-margin">@lang('oppurtunity.From personalized trainings to live team events, we provide all the tools and support to help you progress from your first customers to a worldwide business'). </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="build-business-box mb-md-30">
                        <div class="build-business-img animate-img">
                            <a href="javascript:void(0)">
                                <img src="{{asset('/images/opportunity6.png')}}" class="image-fit" alt="img" />
                            </a>
                        </div>
                        <div class="build-business-meta padding-20 bg-custom-white">
                            <h5 class="fw-600"><a href="javascript:void(0)">@lang('oppurtunity.Happy customers')</a></h5>
                            <p class="no-margin">@lang('oppurtunity.With our 48 hour guarantee, your customers can be confident with the results they can expect when trying Ultrra products A happy customer is a lifelong customer').</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="build-business-box mb-sm-30">
                        <div class="build-business-img animate-img">
                            <a href="javascript:void(0)">
                                <img src="{{asset('/images/opportunity2.png')}}" class="image-fit" alt="img" />
                            </a>
                        </div>
                        <div class="build-business-meta padding-20 bg-custom-white">
                            <h5 class="fw-600"><a href="javascript:void(0)">@lang('oppurtunity.Unlimited Potential')</a></h5>
                            <p class="no-margin">@lang('oppurtunity.Set your own hours, speed, and progress at your own pace while maximizing the Ultrra Rewards Plan').</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="build-business-box">
                        <div class="build-business-img animate-img">
                            <a href="javascript:void(0)">
                                <img src="{{asset('/images/opportunity5.png')}}" class="image-fit" alt="img" />
                            </a>
                        </div>
                        <div class="build-business-meta padding-20 bg-custom-white">
                            <h5 class="fw-600"><a href="javascript:void(0)">@lang('oppurtunity.Social to Global')</a></h5>
                            <p class="no-margin">@lang('oppurtunity.Chances are you have heard of social media Get paid to socialize by making new connections on social media platforms, with potential to work from anywhere around the world')!! </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section-padding request-quote-sec">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 text-left">
                    <div class="section-header left-side pt-5">
                        <div class="section-heading">
                            <h5 class="watermark text-dark">Ultrra</h5>
                            <h3 class="text-brown fw-700"> @lang('oppurtunity.Win with Us')</h3>
                        </div>
                        <p>@lang('oppurtunity.We know first-hand how much effort and commitment must be put forth to achieve success, and we want others who work hard to obtain this as well Contact our team if you have questions!')</p>
                    </div>
                    <div class="request-catgory mb-md-80">
                        <div class="row">

                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="bg-light-white request-form-box full-height">
                        <div class="section-header">
                            <div class="section-heading">
                                <h5 class="watermark text-dark">@lang('translations.Ultrra')</h5>
                                <h3 class="text-brown fw-700"><span class="text-purple fw-100">@lang('oppurtunity.Have a')</span> @lang('oppurtunity.Question')?</h3>
                            </div>
                        </div>
                        <form class="form-style-2 style-2">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" name="#" class="form-control form-control-custom" placeholder="@lang('oppurtunity.Name') *" required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="email" name="#" class="form-control form-control-custom" placeholder="Email *" required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" name="#" class="form-control form-control-custom" placeholder="@lang('oppurtunity.Phone') *" required />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <textarea rows="5" name="#" class="form-control form-control-custom" placeholder="@lang('oppurtunity.Message') *" required></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12 text-center">
                                    <button class="theme-btn btn-style-3"><span class="btn-text">@lang('oppurtunity.Submit')</span></button>
                                </div>
                            </div>
                        </form>
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
@push('js')
    <script>
        function toggleAccordian(tab)
        {
            if (Array.from($('#'+tab+'-a')[0].classList).includes('collapsed'))
            {
                $('#'+tab+'-a')[0].classList.remove('collapsed');
                document.getElementById(tab).classList.remove('collapse');
                document.getElementById(tab).classList.add('collapsing');
                document.getElementById(tab).classList.remove('collapsing');
                document.getElementById(tab).classList.add('collapse');
                document.getElementById(tab).classList.add('show');
            }
            else
            {
                document.getElementById(tab).classList.remove('show');
                document.getElementById(tab).classList.remove('collapse');
                document.getElementById(tab).classList.add('collapsing');
                document.getElementById(tab).classList.remove('collapsing');
                document.getElementById(tab).classList.add('collapse');
                $('#'+tab+'-a')[0].classList.add('collapsed');
            }
        }
    </script>
@endpush