@extends('layouts.app')

@section('content')
    <div onscroll="scrollResearch()">
        <div class="subheader normal-bg">
            <div class="container">
                <div class="row">
                    <div class="col-xl-7 col-lg-6 align-self-center p-relative">
                        <div class="subheader-text">
                            <div class="page-title">
                                <h1 class="text-custom-white fw-600 text-left text-capitalize">@lang('research.Research')</h1>
                                <ul class="custom-flex breadcrumb">
                                    <li>
                                        <a href="{{url('/')}}" class="td-none text-custom-white">@lang('research.Home')</a>
                                    </li>
                                    <li class="text-custom-white active">
                                    @lang('research.Research')
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section class="horizontal-slider section-padding">
            <div class="container-fluid" style="width: 100%">
                <div class="row">
                    <div class="col-12">
                        <div class="section-header">
                            <div class="section-heading">
                                <h5 class="watermark text-dark">@lang('translations.Ultrra')</h5>
                                <h3 class="text-purple fw-700">@lang('research.All Natural Ingredients')</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="row" style="max-height: 316px; max-width: 100%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div id="continuous-slider">
            <div class="slide-item col-12">
                <div class="ingredients-wrap">
                    <div class="title">
                        <p class="bebas no-margin text-custom-white fw-500">@lang('research.Black Pepper')</p>
                    </div>
                    <div class="img-box">
                        <img src="{{asset('/images/engredients1.png')}}" class="image-fit" alt="img" />
                    </div>
                </div>
            </div>
            <div class="slide-item col-12">
                <div class="ingredients-wrap">
                    <div class="title">
                        <p class="bebas no-margin text-custom-white fw-500">@lang('research.Ginger')</p>
                    </div>
                    <div class="img-box">
                        <img src="{{asset('/images/engredients2.png')}}" class="image-fit" alt="img" />
                    </div>
                </div>
            </div>
            <div class="slide-item col-12">
                <div class="ingredients-wrap">
                    <div class="title">
                        <p class="bebas no-margin text-custom-white fw-500">@lang('research.Rosemarie')</p>
                    </div>
                    <div class="img-box">
                        <img src="{{asset('/images/engredients3.png')}}" class="image-fit" alt="img" />
                    </div>
                </div>
            </div>
            <div class="slide-item col-12">
                <div class="ingredients-wrap">
                    <div class="title">
                        <p class="bebas no-margin text-custom-white fw-500">@lang('research.Coconut')</p>
                    </div>
                    <div class="img-box">
                        <img src="{{asset('/images/engredients4.png')}}" class="image-fit" alt="img" />
                    </div>
                </div>
            </div>
            <div class="slide-item col-12">
                <div class="ingredients-wrap">
                    <div class="title">
                        <p class="bebas no-margin text-custom-white fw-500">@lang('research.Turmeric')</p>
                    </div>
                    <div class="img-box">
                        <img src="{{asset('/images/engredients5.png')}}" class="image-fit" alt="img" />
                    </div>
                </div>
            </div>
            <div class="slide-item col-12">
                <div class="ingredients-wrap">
                    <div class="title">
                        <p class="bebas no-margin text-custom-white fw-500">@lang('research.Mint')</p>
                    </div>
                    <div class="img-box">
                        <img src="{{asset('/images/engredients6.png')}}" class="image-fit" alt="img" />
                    </div>
                </div>
            </div>
        </div>
        <section>
            <div class="full-video-sec style-2">
                <div class="container-fluid">
                    <div class="row bg-custom-white">
                        <div class="col-12 no-padding">
                            <div class="video-box p-relative">
                                <iframe class="full-video" src="https://www.youtube.com/embed/SWD8EJgGJeg?autoplay=1&showinfo=0&controls=0&rel=0&mute=1&modestbranding=1"allow="autoplay"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="accordion-2 style-3 section-padding">
            <div class="container">
                <div class="row text-left">
                    <div class="col-lg-12 what-you-get">
                        <div class="what-you-get-text accordion-2-collapse mb-md-80">
                            <div class="section-header left-side">
                                <div class="section-heading">
                                    <h3 class="text-brown fw-100"><span class="text-purple fw-700">@lang('research.Product')</span> Principles</h3>
                                </div>
                            </div>
                            <div id="accordion">
                                <div class="card">
                                    <div class="card-header">
                                        <a class="card-link collapsed" data-toggle="collapse" href="javascript:void(0)" id="integrity-a" onclick="toggleAccordian('integrity')">
                                        @lang('research.Integrity')
                                        </a>
                                    </div>
                                    <div id="integrity" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                            <p class="text-brown fs-16 no-margin pb-16">
                                                <b><strong>✓ &nbsp;</strong></b>
                                                @lang('research.Ultrra relies on the expertise of our international product development team of Ph.Ds, M.D.s and industry product development veterans and scientists who follow Naturopathic principles and perform modern scientific research').                                            </p>
                                            <p class="text-brown fs-16 no-margin pb-16">
                                                <b><strong>✓ &nbsp;</strong></b>
                                                @lang('research.The optimal dosage amounts of these clinically-studied ingredients are determined and verified by Ultrra’s product development team').
                                            </p>
                                            <p class="text-brown fs-16 no-margin pb-16">
                                                <b><strong>✓ &nbsp;</strong></b>
                                                @lang('research.At Ultrra, we are vertically-integrated, partnering with suppliers of raw materials that offer complete traceability of ingredients from seed to bottle').
                                            </p>
                                            <p class="text-brown fs-16 no-margin pb-16">
                                                <b><strong>✓ &nbsp;</strong></b>
                                                @lang('research.Ultrra chooses raw material suppliers who grow these health-supporting herbs sustainably, using optimal growing conditions, non-GMO (Genetically Modified Organisms) materials, and who treat the farmers and soil responsibly, with high levels of environmental and social stewardship').
                                            </p>
                                            <p class="text-brown fs-16 no-margin pb-16">
                                                <b><strong>✓ &nbsp;</strong></b>
                                                @lang('research.The compounds that are identified in the plants as beneficial for the human consumption are extracted in a standardized way to achieve consistent herbal extract ratios and percentages of the particular compound').
                                            </p>
                                            <p class="text-brown fs-16 no-margin pb-16">
                                                <b><strong>✓ &nbsp;</strong></b>
                                                @lang('research.The plant compounds are extracted where they are grown locally in FDA-inspected and ISO (International Organization for Standardization) facilities').
                                            </p>
                                            <p class="text-brown fs-16 no-margin">
                                                <b><strong>✓ &nbsp;</strong></b>
                                                @lang('research.These compounds are compliant with California Proposition 65 with no detectable levels of pesticides, lead or other heavy metals etc. are present').
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <a class="card-link collapsed" data-toggle="collapse" href="javascript:void(0)" id="ingredients-a" onclick="toggleAccordian('ingredients')">
                                        @lang('research.Ingredients')
                                        </a>
                                    </div>
                                    <div id="ingredients" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                            <p class="text-brown fs-16 no-margin pb-16">
                                                <b><strong>✓ &nbsp;</strong></b>
                                                @lang('research.Many of these ingredients are foods and spices used traditionally and continually by cultures and peoples for centuries').
                                            </p>
                                            <p class="text-brown fs-16 no-margin pb-16">
                                                <b><strong>✓ &nbsp;</strong></b>
                                                @lang('research.These traditionally-used ingredients are clinically-studied, -tested and -proven using modern scientific research, and published in peer-reviewed medical and scientific journals around the world').
                                            </p>
                                            <p class="text-brown fs-16 no-margin pb-16">
                                                <b><strong>✓ &nbsp;</strong></b>
                                                @lang('research.Some of Ultrra’s ingredients are patented').
                                            </p>
                                            <p class="text-brown fs-16 no-margin pb-16">
                                                <b><strong>✓ &nbsp;</strong></b>
                                                @lang('research.Ultrra uses only the highest quality and purest form of every ingredient, and all ingredients are Kosher- and Halal- certified').
                                            </p>
                                            <p class="text-brown fs-16 no-margin">
                                                <b><strong>✓ &nbsp;</strong></b>
                                                @lang('research.Ultrra strives for complete traceability of ingredients (as evidenced by Specification sheets and Certificates of Analysis of finished goods)').
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <a class="card-link collapsed" data-toggle="collapse" href="javascript:void(0)" id="manufacture-a" onclick="toggleAccordian('manufacture')">
                                        @lang('research.Manufacturing')
                                        </a>
                                    </div>
                                    <div id="manufacture" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                            <p class="text-brown fs-16 no-margin pb-16">
                                                <b><strong>✓ &nbsp;</strong></b>
                                                @lang('research.Ultrra uses the finest manufacturing facilities in the USA (where food manufacturing processes are tightly regulated for your safety), which follow strict cGMPs (current Good Manufacturing Practices) with Standard Operating Procedures (SOPs) that strictly comply with US FDA (Food and Drug Administration) regulations. Quality Control and Quality Assurance ensures a safe product').
                                            </p>
                                            <p class="text-brown fs-16 no-margin pb-16">
                                                <b><strong>✓ &nbsp;</strong></b>
                                                @lang('research.Ultrra’s products are manufactured (blended, encapsulated, bottled and labeled) in cGMP-compliant facilities regulated by the FDA').
                                            </p>
                                            <p class="text-brown fs-16 no-margin">
                                                <b><strong>✓ &nbsp;</strong></b>
                                                @lang('research.Ultrra’s products are not, and do not need to be, FDA-approved. Only pharmaceutical drugs are FDA approved. Ultrra’s products are Foods and Dietary Supplements, and are compliant with the Act that regulates the Food and Dietary supplement Industry').
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br/>
                    <div class="col-lg-12">
                        <div class="accordion-2-collapse">
                            <div class="section-header left-side">
                                <div class="section-heading">
                                    <h3 class="text-brown fw-100 mt-3"><span class="text-light-blue fw-700"> Highlights</span> & More</h3>
                                </div>
                            </div>
                            <div id="accordion1">
                                <div class="card">
                                    <div class="card-header">
                                        <a class="card-link collapsed" data-toggle="collapse" href="javascript:void(0)" id="ingredients-1-a" onclick="toggleAccordian('ingredients-1')">
                                            Phyllanthus amarus (@lang('research.Detox') 1)
                                        </a>
                                    </div>
                                    <div id="ingredients-1" class="collapse" data-parent="#accordion1">
                                        <div class="card-body">
                                            <p class="text-brown fs-16 no-margin">@lang('research.Supportive studies on liver protective features of the plant were carried by independent researchers; Kumaran and Karunakaran (2007) detected antioxidant activities of Phyllanthus amarus ; Jeena and Kuttan (1999), Kumar and Kuttan (2005) and Naaz et al (2007) examined liver protective potential of Phyllanthus amarus in laboratory animals. The efficacy of Phyllanthus amarus was tested by liver injury markers including morphology of liver tissue and levels of enzymes. Independent studies showed the same results; Phyllanthus amarus showed a capacity to protect the liver').</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <a class="collapsed card-link" data-toggle="collapse" href="javascript:void(0)" id="ingredients-2-a" onclick="toggleAccordian('ingredients-2')">
                                            Multi-enzyme complex (@lang('research.Detox') 1)
                                        </a>
                                    </div>
                                    <div id="ingredients-2" class="collapse" data-parent="#accordion1">
                                        <div class="card-body">
                                            <p class="text-brown fs-16 no-margin pb-16">@lang('research.National Digestive Diseases Information Clearinghouse (NDDIC). National Institute of Health. Your digestive system and how it works').</p>
                                            <p class="text-brown fs-16 no-margin"><a href="https://www.niddk.nih.gov/health-information/digestive-diseases" target="_blank">https://www.niddk.nih.gov/health-information/digestive-diseases</a></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <a class="collapsed card-link" data-toggle="collapse" href="javascript:void(0)" id="ingredients-3-a" onclick="toggleAccordian('ingredients-3')">
                                            Triphala (@lang('research.Detox') 1)
                                        </a>
                                    </div>
                                    <div id="ingredients-3" class="collapse" data-parent="#accordion1">
                                        <div class="card-body">
                                            <p class="text-brown fs-16 no-margin"> @lang('research.Studies conducted by Rafatullah (2002), Suryanaraya et al (2004), Rao et al (2005) and Yokozawa (2007) demonstrated Amla’s healing efficacy in cases of stomach ulcer and carcinogen-induced liver ailments').<br/>
                                            @lang('research.One of most studies was conducted by Shirish S. Pingale: Hepatoprotective action of Terminalia bellerica on CCl4 induced hepatic disorders. Der Pharma Chemica. 2011; 3(1):42-48').
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <a class="collapsed card-link" data-toggle="collapse" href="javascript:void(0)" id="ingredients-4-a" onclick="toggleAccordian('ingredients-4')">
                                            Citrus Bioflavonoid Research (GenKi)
                                        </a>
                                    </div>
                                    <div id="ingredients-4" class="collapse" data-parent="#accordion1">
                                        <div class="card-body">
                                            <p class="text-brown fs-16 no-margin pb-16">@lang('research.Hesperidin, a citrus bioflavonoid, inhibits bone loss and decreases serum and hepatic lipids in ovariectomized mice. J Nutr. 2003').</p>
                                            <p class="text-brown fs-16 no-margin pb-16">@lang('research.The purpose of this study was to examine whether hesperidin inhibits bone loss in ovariectomized mice (OVX), an animal model of postmenopausal osteoporosis. Hesperidin administration did not affect the uterine weight. These results suggest a possible role for citrus bioflavonoids in the prevention of lifestyle-related diseases because of their beneficial effects on bone and lipids').</p>
                                            <p class="text-brown fs-16 no-margin pb-16">@lang('research.Biological properties of citrus bioflavonoids pertaining to cancer and inflammation. Curr Med Chem. 2001. US Citrus and Subtropical Products Laboratory, USDA, ARS, SAA, Winter Haven, FL').</p>
                                            <p class="text-brown fs-16 no-margin">@lang('research.Antiproliferative activities of citrus bioflavonoids against six human cancer cell lines. J Agric Food Chem. 2002. U.S. Citrus and Subtropical Products Laboratory, South Atlantic Area, Agricultural Research Service, U.S. Department of Agriculture, Winter Haven, Florida Study conducted for the quantitative assessment of carotenoid antioxidants in human skin in vivo. Study was published in the December 2010 issue of the journal Archives of Biochemistry and BioPhysics').</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <a class="collapsed card-link" data-toggle="collapse" href="javascript:void(0)" id="ingredients-5-a" onclick="toggleAccordian('ingredients-5')">
                                            @lang('research.Slim')
                                        </a>
                                    </div>
                                    <div id="ingredients-5" class="collapse" data-parent="#accordion1">
                                        <div class="card-body">
                                            <p class="text-brown fs-16 no-margin pb-16">@lang('research.Forskohlin clinical studies have been conducted in India, Japan and US. For clinical studies, visit') <a href="https://www.forslean.com/" target="_blank">http://www.forslean.com/clinical.htm</a></p>
                                            <p class="text-brown fs-16 no-margin pb-16">@lang('research.GarCitrin clinical studies are available at') <a href="http://www.garcitrin.com/clinical/" target="_blank">http://www.garcitrin.com/clinical/</a></p>
                                            <p class="text-brown fs-16 no-margin">@lang('research.Clinical studies on BioPerine are available at') <a href="http://www.bioperine.com/clinical.html" target="_blank">http://www.bioperine.com/clinical.html</a></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <a class="collapsed card-link" data-toggle="collapse" href="javascript:void(0)" id="ingredients-6-a" onclick="toggleAccordian('ingredients-6')">
                                            Pikroliv (@lang('research.Detox') 1)
                                        </a>
                                    </div>
                                    <div id="ingredients-6" class="collapse" data-parent="#accordion1">
                                        <div class="card-body">
                                            <p class="text-brown fs-16 no-margin">@lang('research.Picroliv prevents the biochemical changes triggered by aflatoxin B1. Rastogi et al. Pharmacol Toxicol. 2001; 88(2)')}:53-8').<br/>
                                                @lang('research.Picroliv possesses activity against E. hystolica induced hepatic damage. Singh M et al. Indian J Med Res. 2005; 121(5)')}:676-82').
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                    <h3 class="text-custom-white fw-100"><span class="text-light-blue fw-700">@lang('research.Powered by nature')</span></h3>
                                </div>
                            </div>
                            <p class="text-custom-white no-margin text-left">@lang('research.Ultrra searches the world and researches traditional health-care systems for life-giving ingredients in the form of plants, herbs and minerals that support the body’s natural cleansing and rejuvenating functions')</p>
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
                                            <h6 class="no-margin text-custom-white">@lang('research.Tibetan')</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="icon-box full-height">
                                        <img src="{{asset('/images/formula3.png')}}" class="image-fit" alt="icon" />
                                        <div class="hover-text">
                                            <h6 class="no-margin text-custom-white">@lang('research.Chinese Herbal')</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="icon-box full-height">
                                        <img src="{{asset('/images/formula4.png')}}" class="image-fit" alt="icon" />
                                        <div class="hover-text">
                                            <h6 class="no-margin text-custom-white">Ayur-veda</h6>
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
    </div>

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
        window.addEventListener('load', function() {
            $('#continuous-slider').slick({
                slidesToShow: 5,
                slidesToScroll: 1,
                arrows: false,
                dots: false,
                infinite: true,
                draggable: false,
                cssEase: 'linear',
                pauseOnHover: true,
                responsive: [{
                    breakpoint: 1200,
                    settings: {
                        autoplay: true,
                        autoplaySpeed: 1000,
                        speed: 3000,
                        slidesToShow: 3,
                    }
                },{
                    breakpoint: 768,
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

        function scrollResearch()
        {
            console.log('yufhg');
            if (!document.querySelector('nav').classList.contains('active'))
            {
                let scrollTop = event.srcElement.scrollingElement ? event.srcElement.scrollingElement.scrollTop : event.srcElement.scrollTop;
                if (document.querySelector('.slick-track') !== null)
                {
                    document.querySelector('.slick-track').style.left = 0 + scrollTop * -1 + 'px';
                }
            }
        }
    </script>
@endpush