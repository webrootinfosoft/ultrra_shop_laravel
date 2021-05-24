<header class="header-style-1">
    <div class="top-bar">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-md-6 col-9">
                    <div class="top-bar-media">
                        <span class="media-txt fs-14">Connect With</span>
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
                                    <a class="text-brown">
                                        English
                                    </a>
                                </div>
                                <div id="language-1-drop" class="dropdown" style="display: none">
                                    <ul class="custom">
                                        <li>
                                            <a href="javascript:void(0)" class="text-brown">
                                            English
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)" class="text-brown">
                                            Spanish
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="menu-item">
                                @if(!auth()->check())
                                    <a href="https://office.ultrra.com/signin" target="_blank" class="text-brown">Login</a>
                                @else
                                    <a href="javascript:void(0)" class="text-brown">Logout</a>
                                @endif
                            </li>
                            {{--@if($show_enroll)--}}
                            <li class="menu-item">
                                @if(!auth()->check())
                                    <a href="{{url('/enrollment')}}" class="text-brown">Enroll</a>
                                @elseif(!auth()->user()->address)
                                    <a href="{{url('/create-account?usertype='.auth()->user()->usertype)}}" class="text-brown">Enroll</a>
                                @endif
                            </li>
                            {{--@endif--}}
                            <li class="menu-item">
                                <a href="https://zoom.us/j/398309238" target="_blank" class="text-brown">Webinars</a>
                            </li>
                            <li class="menu-item">
                                @if(!auth()->user())
                                    <a href="{{url('/signup?usertype=rc')}}" class="text-brown">Shop</a>
                                @else
                                    <a href="{{url('/cart')}}" class="text-brown">Shop</a>
                                @endif
                            </li>
                            <li class="menu-item cart-box">
                                <a href="{{url('/cart')}}" class="cart-btn text-brown" style="font-size: 20px !important; max-height: 47px">
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
                <p class="nav-title text-custom-white">Menu</p>
                <ul class="custom menu-list">
                    <li>
                        <a href="{{url('/')}}">
                            <span class="text-first text-left">Home</span>
                            <span class="text-second text-left">Ultrra</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{url('/about')}}">
                            <span class="text-first text-left">About Us</span>
                            <span class="text-second text-left">Our Story</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{url('/opportunity')}}">
                            <span class="text-first text-left">Opportunity</span>
                            <span class="text-second text-left">Work from home</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{url('/testimonial')}}">
                            <span class="text-first text-left">Testimonials</span>
                            <span class="text-second text-left">Real People, Real Results</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{url('/research')}}">
                            <span class="text-first text-left">Research</span>
                            <span class="text-second text-left" style="white-space: normal">East Meets West</span>
                        </a>
                    </li>
                    <li>
                        @if(!auth()->user())
                            <a href="{{url('/signup?usertype=rc')}}" class="text-brown">
                                <span class="text-first text-left">Shop</span>
                                <span class="text-second text-left">Try Ultrra Products</span>
                            </a>
                        @else
                            <a href="{{url('cart')}}" class="text-brown">
                                <span class="text-first text-left">Shop</span>
                                <span class="text-second text-left">Try Ultrra Products</span>
                            </a>
                        @endif
                    </li>
                </ul>
                <ul class="custom-flex menu-apply">
                    <li>
                        <a href="{{url('/contact')}}">
                            <span class="text">Contact</span>
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
                <a href="javascript:void(0)">Privacy Policy</a>
                <a href="{{url('/files/terms.pdf')}}" target="_blank">Terms of use</a>
                <a href="javascript:void(0)">Disclosure</a>
            </div>
        </div>
        <div class="col-xl-4 col-lg-5 right-side">
            <div class="nav-img">
                <img src="{{asset('/images/menu-side.png')}}" alt="img" />
            </div>
            <div class="nav-body">
                <p class="nav-title text-custom-white">Category</p>
                <ul class="custom menu-list">
                    <li>
                        <a href="{{url('/nutritional')}}">
                            <span class="text text-left">Nutritionals</span>
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
                        <a href="{{url('/beverage')}}">
                            <span class="text text-left">Beverages</span>
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
                        <a href="{{url('/rare-oil/15')}}">
                            <span class="text text-left">Rare Oils Blends</span>
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
                        <a href="{{url('/rare-oil/14')}}">
                            <span class="text text-left">Rare Oils Singles</span>
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
                        <a href="{{url('/element')}}">
                            <span class="text text-left">Elements</span>
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
            <span class="copyright">© 2011-{{date('Y')}} ULTRRA, LLC ™. ALL RIGHTS RESERVED.</span>
        </div>
    </div>
</nav>
@push('js')
    <script>
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
    </script>
@endpush