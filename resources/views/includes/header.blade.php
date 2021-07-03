<header class="header-style-1">
    <div class="top-bar">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-md-6 col-9">
                    <div class="top-bar-media">
                        <span class="media-txt fs-14">@lang('header.Connect With')</span>
                        <ul class="custom-flex mb-0">
                            <li>
                                <a href="https://www.facebook.com/ultrraofficial" target="_blank" class="text-brown" style="height: 36px">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            </li>
                            <li>
                                <a href="https://twitter.com/ultrraofficial" target="_blank" class="text-brown" style="height: 36px">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            </li>
                            <li>
                                <a href="https://www.instagram.com/ultrraofficial" target="_blank" class="text-brown" style="height: 36px">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </li>
                            <li>
                                <a href="https://www.youtube.com/channel/UCB2RTpsT8zQCTVRrXXdMa7Q" target="_blank" class="text-brown" style="height: 36px">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-3 align-self-center">
                    <div class="top-bar-right">
                        <ul class="top-nav custom-flex">
                            <li class="language">
                                <div id="language-1" class="select" onclick="dropdown()">
                                    <a href="javascript:void(0)" class="text-brown">
                                        {{app()->getLocale() == 'es' ? 'Spanish' : 'English'}}
                                    </a>
                                </div>
                                <div id="language-1-drop" class="dropdown" style="display: none">
                                    <ul class="custom">
                                        <li>
                                            <a href="{{url('/lang/en')}}" class="text-brown">
                                                English
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{url('/lang/es')}}" class="text-brown">
                                                Spanish
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="menu-item">
                                @if(!auth()->check())
                                    <a href="https://office.ultrra.com/signin" target="_blank" class="text-brown">@lang('header.Login')</a>
                                @else
                                    <a href="javascript:void(0)" class="text-brown" onclick="localStorage.clear(); $('#logout-form').submit();">Logout</a>
                                    <form id="logout-form" action="{{url('www/logout')}}" class="d-none" method="post">
                                        @csrf
                                        <button type="submit"></button>
                                    </form>
                                @endif
                            </li>
                            {{--@if($show_enroll)--}}
                            <li class="menu-item">
                                @if(!auth()->check())
                                    <a href="{{url('/www/enrollment')}}" class="text-brown">@lang('header.Enroll')</a>
                                @elseif(count(auth()->user()->addresses) == 0)
                                    <a href="{{url('/www/create-account?usertype='.auth()->user()->usertype)}}" class="text-brown">@lang('header.Enroll')</a>
                                @endif
                            </li>
                            {{--@endif--}}
                            <li class="menu-item">
                                <a href="https://zoom.us/j/398309238" target="_blank" class="text-brown">Webinars</a>
                            </li>
                            <li class="menu-item shop-li">
                                @if(!auth()->user())
                                    <a href="{{url('/www/enrollment')}}" class="text-brown">@lang('header.Shop')</a>
                                @else
                                    <a href="{{url('/www/products')}}" class="text-brown">@lang('header.Shop')</a>
                                @endif
                            </li>
                            <li class="menu-item cart-box">
                                <a href="javascript:void(0)" class="cart-btn text-brown" style="font-size: 20px !important; max-height: 47px" onclick="cartIcon()">
                                    <i class="fal fa-shopping-bag fw-500"></i>
                                    <span class="cart-value">0</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="hamburger-menu top-hamburger" onclick="openSideMenuMini()">
                        <div class="menu-btn">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header-style-2">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="navigation-wrap">
                        <div class="menu-trigger" onclick="openSideMenu()">
                            <div class="menu-line">
                                <div class="menu-line-wrap">
                                    <span></span>
                                    <span></span>
                                </div>
                            </div>
                            <img src="{{asset('/images/navigation/nav.svg')}}" class="menu-svg change-svg" alt="svg" />
                        </div>
                        <a href="{{url('/')}}" class="logo-box">
                            <img src="{{asset('/images/logo.png')}}" class="image-fit" alt="img" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<nav class="navigation-main">
    <div class="navigation-body row no-gutters">
        <div class="col-xl-8 col-lg-7 left-side">
            <div class="nav-body">
                <p class="nav-title text-custom-white">@lang('header.Menu')</p>
                <ul class="custom menu-list">
                    <li>
                        <a href="{{url('/')}}">
                            <span class="text-first text-left">@lang('header.Home')</span>
                            <span class="text-second text-left">Ultrra</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{url('/www/about')}}">
                            <span class="text-first text-left">@lang('header.About Us')</span>
                            <span class="text-second text-left">@lang('header.Our Story')</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{url('/www/opportunity')}}">
                            <span class="text-first text-left">@lang('header.Opportunity')</span>
                            <span class="text-second text-left">@lang('header.Work from home')</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{url('/www/testimonial')}}">
                            <span class="text-first text-left">@lang('header.Testimonials')</span>
                            <span class="text-second text-left">@lang('header.Real People, Real Results')</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{url('/www/research')}}">
                            <span class="text-first text-left">@lang('header.Research')</span>
                            <span class="text-second text-left" style="white-space: normal">@lang('header.East Meets West')</span>
                        </a>
                    </li>
                    <li class="shop-li">
                        @if(!auth()->user())
                            <a href="{{url('/www/enrollment')}}" class="text-brown">
                                <span class="text-first text-left">@lang('header.Shop')</span>
                                <span class="text-second text-left">@lang('header.Try Ultrra Products')</span>
                            </a>
                        @else
                            <a href="{{url('/www/products')}}" class="text-brown">
                                <span class="text-first text-left">@lang('header.Shop')</span>
                                <span class="text-second text-left">@lang('header.Try Ultrra Products')</span>
                            </a>
                        @endif
                    </li>
                </ul>
                <ul class="custom-flex menu-apply">
                    <li>
                        <a href="{{url('/www/contact')}}">
                            <span class="text">@lang('header.Contact')</span>
                            <span class="arrow">
                                <span>
                                    <svg viewBox="0 0 16 7"><path d="M15.216.8L.812 6.2 3.206.825z" fillRule="evenodd"></path></svg>
                                </span>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="nav-footer">
                <a href="javascript:void(0)">@lang('header.Privacy Policy')</a>
                <a href="{{url('/files/terms.pdf')}}" target="_blank">@lang('header.Terms of use')</a>
                <a href="javascript:void(0)">@lang('header.Disclosure')</a>
            </div>
        </div>
        <div class="col-xl-4 col-lg-5 right-side">
            <div class="nav-img">
                <img src="{{asset('/images/menu-side.png')}}" alt="img" />
            </div>
            <div class="nav-body">
                <p class="nav-title text-custom-white">@lang('header.Category')</p>
                <ul class="custom menu-list">
                    <li>
                        <a href="{{url('/www/nutritional')}}">
                            <span class="text text-left">@lang('header.Nutritionals')</span>
                            <span class="arrow">
                                <span>
                                    <svg viewBox="0 0 16 7"><path d="M15.216.8L.812 6.2 3.206.825z" fillRule="evenodd"></path></svg>
                                </span>
                            </span>
                        </a>
                        <div class="image-wrap">
                            <img src="{{asset('/images/nav1.jpg')}}" class="image-fit" alt="img" />
                        </div>
                    </li>
                    <li>
                        <a href="{{url('/www/beverage')}}">
                            <span class="text text-left">@lang('header.Beverages')</span>
                            <span class="arrow">
                                <span>
                                    <svg viewBox="0 0 16 7"><path d="M15.216.8L.812 6.2 3.206.825z" fillRule="evenodd"></path></svg>
                                </span>
                            </span>
                        </a>
                        <div class="image-wrap">
                            <img src="{{asset('/images/nav2.jpg')}}" class="image-fit" alt="img" />
                        </div>
                    </li>
                    <li>
                        <a href="{{url('/www/rare-oil/15')}}">
                            <span class="text text-left">@lang('header.Rare Oils') @lang('header.Blends')</span>
                            <span class="arrow">
                                <span>
                                    <svg viewBox="0 0 16 7"><path d="M15.216.8L.812 6.2 3.206.825z" fillRule="evenodd"></path></svg>
                                </span>
                            </span>
                        </a>
                        <div class="image-wrap">
                            <img src="{{asset('/images/nav3.jpg')}}" class="image-fit" alt="img" />
                        </div>
                    </li>
                    <li>
                        <a href="{{url('/www/rare-oil/14')}}">
                            <span class="text text-left">@lang('header.Rare Oils') @lang('header.Singles')</span>
                            <span class="arrow">
                                <span>
                                    <svg viewBox="0 0 16 7"><path d="M15.216.8L.812 6.2 3.206.825z" fillRule="evenodd"></path></svg>
                                </span>
                            </span>
                        </a>
                        <div class="image-wrap">
                            <img src="{{asset('/images/nav3-rare.jpg')}}" class="image-fit" alt="img" />
                        </div>
                    </li>
                    <li>
                        <a href="{{url('/www/element')}}">
                            <span class="text text-left">@lang('header.Elements')</span>
                            <span class="arrow">
                                <span>
                                    <svg viewBox="0 0 16 7"><path d="M15.216.8L.812 6.2 3.206.825z" fillRule="evenodd"></path></svg>
                                </span>
                            </span>
                        </a>
                        <div class="image-wrap">
                            <img src="{{asset('/images/nav4.jpg')}}" class="image-fit" alt="img" />
                        </div>
                    </li>
                </ul>
                <ul class="custom-flex social-media">
                    <li>
                        <a href="https://www.facebook.com/ultrraofficial" target="_blank">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.instagram.com/ultrraofficial" target="_blank">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.twitter.com/ultrraofficial" target="_blank">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <i class="fab fa-vimeo"></i>
                        </a>
                    </li>
                </ul>
            </div>
            <span class="copyright">© 2011-{{date('Y')}} ULTRRA, LLC ™. @lang('header.ALL RIGHTS RESERVED').</span>
        </div>
    </div>
</nav>
@push('js')
    <script>
        let search = window.location.search;
        let params = new URLSearchParams(search);
        let username = params.get('username');
        let suffix = '';
        let cart = JSON.parse(localStorage.getItem('cart'));

        if (username !== null && localStorage.getItem('user') === null && localStorage.getItem('access_token') === null)
        {
            suffix = '?username=' + username;
        }

        window.addEventListener('load', function() {
            $('a').each(function (index, element) {
                let href = $(element).attr('href');
                let excluded_links = [
                    'javascript:void(0)',
                    '#',
                    'https://office.ultrra.com/signin',
                    'https://zoom.us/j/398309238',
                    'tel:', 'mailto:cc@ultrra.com',
                    'http://www.dsa.org/consumerprotection/Code',
                    'https://office.ultrra.com',
                    'https://phplaravel-370200-1945145.cloudwaysapps.com/files/terms.pdf',
                    'https://www.facebook.com/ultrraofficial',
                    'https://twitter.com/ultrraofficial',
                    'https://www.instagram.com/ultrraofficial',
                    'https://www.instagram.com/ultrraofficial/',
                    'https://www.youtube.com/ultrra',
                    'https://www.youtube.com/channel/UCB2RTpsT8zQCTVRrXXdMa7Q'
                ];

                if (!excluded_links.includes(href))
                {
                    href = href.replace(window.location.search, '');
                    $(element).attr('href', href + window.location.search);
                }
            });

            if (cart !== null)
            {
                axios.get('/cart/' + cart.id).then((response) => {
                    $('.cart-value').text(response.data.data.products.length);
                });
            }

            if ('{{auth()->check()}}' == 1)
            {
                axios.get('/user/' + '{{auth()->id()}}').then(response => {
                    if (response.data.status_code === 200 && response.data.user !== null)
                    {
                        localStorage.setItem('user', JSON.stringify(response.data.user));
                        localStorage.setItem('country', JSON.stringify(response.data.user.country_id));
                        let sponsor_user = response.data.user.sponsor;
                        $('.img-1').attr('src', 'https://admin.ultrra.com/user_images/' + sponsor_user.image);
                        $('.img-2').attr('src', 'https://admin.ultrra.com/user_images/' + sponsor_user.image);
                        $('#recommended-by').text(sponsor_user.name);
                        $('#sponsor-phone').text(sponsor_user.phone);
                        $('#sponsor-email').text(sponsor_user.email);
                        axios.get('/get-placement-info/' + response.data.user.id).then(response => {
                            if (response.data.data !== null)
                            {
                                localStorage.setItem('placement_info', JSON.stringify(response.data.data));
                            }
                        });
                        axios.get('/user-addresses-by-id/' + '{{auth()->id()}}').then(response => {
                            if (response.data.data.length > 0)
                            {
                                localStorage.setItem('address', JSON.stringify(response.data.data[0]));
                            }
                        });
                        axios.get('/user-orders-by-id/' + '{{auth()->id()}}').then(response => {
                            if (response.data.data.total > 0)
                            {
                                let user_data = JSON.parse(localStorage.getItem('user'));
                                axios.get('/user-cart/' + user_data.id).then(response => {
                                    if (response.data.data !== null)
                                    {
                                        localStorage.setItem('cart', JSON.stringify(response.data.data));
                                    }
                                    axios.post('/cart', {user_id: user_data.id, create_cart: 1}).then(response => {
                                        axios.get('/user-cart/' + user_data.id).then(response => {
                                            if (response.data.data)
                                            {
                                                localStorage.setItem('cart', JSON.stringify(response.data.data));
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
                                }).catch((error) => {
                                    if (error.response.status === 401)
                                    {
                                        localStorage.clear();
                                        window.location.reload();
                                    }
                                });
                            }
                        });
                    }
                });
            }

            if (localStorage.getItem('user') !== null && localStorage.getItem('address') !== null && localStorage.getItem('shipping_address'))
            {
                $('.shop-li a').attr('href', '/www/products' + search);
            }
        });

        window.addEventListener('scroll', handleScroll, true);

        function dropdown()
        {
            console.log($('#language-1-drop'))
            if ($('#language-1-drop')[0].classList.length == 1)
            {
                $('#language-1-drop').addClass('open');
                $('#language-1-drop').css('display', 'block');
            }
            else
            {
                $('#language-1-drop').removeClass('open');
                $('#language-1-drop').css('display', 'none');
            }
        }

        function openSideMenuMini()
        {
            if ($('.top-bar-right')[0].classList.length == 1)
            {
                $('.top-bar-right').addClass('active');
                $('.hamburger-menu').addClass('active');
            }
            else
            {
                $('.top-bar-right').removeClass('active');
                $('.hamburger-menu').removeClass('active');
            }
        }

        function openSideMenu()
        {
            if ($('.header-style-2')[0].classList.length == 1)
            {
                $('.header-style-2').addClass('active');
                $('.navigation-main').addClass('active');
            }
            else
            {
                $('.header-style-2').removeClass('active');
                $('.navigation-main').removeClass('active');
            }
        }

        function  handleScroll(event)
        {
            // console.log(event)
            if ($('nav').length !== 0 && !document.querySelector('nav').classList.contains('active'))
            {
                let scrollTop = event.srcElement.scrollingElement ? event.srcElement.scrollingElement.scrollTop : event.srcElement.scrollTop;
                if (document.querySelector('.header-style-1') !== null && document.querySelector('.popup-wrap') !== null)
                {
                    if (scrollTop >= 100)
                    {
                        document.querySelector('.header-style-1').classList.add('sticky');
                        document.querySelector('.popup-wrap').classList.add('sticky');
                    }
                    else
                    {
                        document.querySelector('.header-style-1').classList.remove('sticky');
                        document.querySelector('.popup-wrap').classList.remove('sticky');
                    }
                }

                if (document.querySelector('.back-top') !== null)
                {
                    if (scrollTop > 200)
                    {
                        document.querySelector('.back-top').style.display = 'block'
                    }
                    else
                    {
                        document.querySelector('.back-top').style.display = 'none'
                    }
                }
            }
        }

        function cartIcon()
        {
            let current_route = '{{url()->current()}}';
            if (current_route.includes('/products'))
            {
                $('#cart-sidebar').show();
            }
            else
            {
                window.location.href = '/www/products' + search;
            }
        }

    </script>
@endpush