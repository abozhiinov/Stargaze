<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900italic,900' rel='stylesheet' type='text/css'>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <!-- <link rel="stylesheet" type="text/css" href="{{ url('css/app.css') }}"> -->
</head>
<body>
    <div id="app">
        <header class="site-header">
            <div class="site-header-inner-wrapper">
                <a class="navbar-logo" href="{{ url('/') }}">
                    <img src="{{url('/images/stargaze-logo.png')}}" class="img-responsive stargaze-logo">
                </a>

                <button class="site-header" hidden></button>

                <ul id='navbar' class="primary-menu list-unstyled">
                    <li id="navbar-home" class="nav-item">
                        <a class="sg-nav-link" href="/">{{ __('Начало') }}</a>
                    </li>
                    <li id="navbar-artists" class="nav-item">
                        <a class="sg-nav-link" href="/artists">{{ __('Изпълнители') }}</a>
                    </li> 
                    <li id="navbar-places" class="nav-item">
                        <a class="sg-nav-link" href="/places">{{ __('Заведения') }}</a>
                    </li>
                    <li id="navbar-events" class="nav-item">
                        <a class="sg-nav-link" href="/events">{{ __('Събития') }}</a>
                    </li>
                    <!-- Authentication Links -->
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="sg-nav-link" href="{{ route('login') }}">{{ __('Вход') }}</a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="sg-nav-link" href="{{ route('register') }}">{{ __('Регистрация') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item">
                            <a class="sg-nav-link" href="/profile" role="button" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ __('Моят Профил') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="sg-nav-link" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                    {{ __('Изход') }}
                                </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </header>

        <main class="py-4">
            @yield('content')
        </main>

        <footer class="text-center text-white">
            <div id="footer" class="footer py-4">
                <p id="footer_for-more" class="footer_for-more">{{ __('Следи за изяви и тук') }} </p>
                <div id="footer_social-media" class="d-flex footer_social-media">
                    <a href="#"><img src="{{url('/images/facebook.svg')}}" class="social-media-icon"></a>
                    <a href="#"><img src="{{url('/images/instagram.svg')}}" class="social-media-icon"></a>
                    <a href="#"><img src="{{url('/images/twitter.svg')}}" class="social-media-icon"></a>
                </div>
                <p id="footer_copyright" class="footer_copyright"> © Copyright Stargaze. All Rights Reserved </p>
            </div>
        </footer>
    </div>
</body>
</html>
