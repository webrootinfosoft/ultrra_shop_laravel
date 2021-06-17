<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" translate="no">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google" content="notranslate">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Ultrra</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        @include('includes.header')
        @yield('content')
        @include('includes.footer')
    </div>
</body>
@stack('js')
<script>
    var btnHover = document.querySelectorAll('.theme-btn');
    btnHover.forEach(function (btnHover) {
        if (typeof btnHover.firstChild.classList !== 'undefined' && Array.from(btnHover.firstChild.classList).includes('btn-text'))
        {
            btnHover.firstChild.innerHTML = btnHover.firstChild.textContent.replace(/([^\x00-\x80]|\w)/g, "<span class='btn_letters'>$&</span>");
            console.log(btnHover.firstChild.textContent)
        }
    });
    btnHover.forEach(function (btnHover) {
        btnHover.addEventListener('mouseenter', function () {
            var letter = btnHover.querySelectorAll('.btn_letters');
            // anime.timeline({}).add({
            //     targets: letter,
            //     translateY: ["1.1em", 0],
            //     translateZ: 0,
            //     duration: 750,
            //     delay: (el, i) => 50 * i
            // });
            anime({
                targets: letter,
                translateY: ["1.1em", 0],
                translateZ: 0,
                duration: 750,
                delay: (el, i) => 50 * i
            })
        });
    });
</script>
</html>
