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
    <!-- <link rel="stylesheet" type="text/css" href="{{ url('css/app.css') }}"> -->
</head>
<body>
    <div id="app">
        <header>
            <nav class="">
                <div class="nav d-flex justify-content-between p-4">
                    <a class="navbar-logo" href="{{ url('/') }}">
                        <img src="{{url('/images/stargaze-logo.png')}}" class="img-responsive stargaze-logo">
                    </a>
                    <ul id='navbar' class="d-flex justify-between gap-4 p-2 list-unstyled">
                        <li id="navbar-home" class="nav-item">
                            <a class="sg-nav-link" href="/">{{ __('Начало') }}</a>
                        </li>
                        <li id="navbar-artists" class="nav-item">
                            <a class="sg-nav-link" href="/artists">{{ __('Изпълнители') }}</a>
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
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle link-primary" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </nav>
        </header>

        <main class="py-4">
            @yield('content')
        </main>

        <footer class="text-center text-white">
            <div id="footer" class="footer py-4">
                <p id="footer_for-more" class="footer_for-more">{{ __('Следи за изяви и тук') }} </p>
                <div id="footer_social-media" class="d-flex footer_social-media">
                    <a href="#"><img src="{{url('/images/fb_icon.png')}}" class="social-media-icon"></a>
                    <a href="#"><img src="{{url('/images/ig_icon.png')}}" class="social-media-icon"></a>
                    <a href="#"><img src="{{url('/images/tw_icon.png')}}" class="social-media-icon"></a>
                </div>
                <p id="footer_copyright" class="footer_copyright"> © Copyright Stargaze. All Rights Reserved </p>
            </div>
        </footer>
    </div>
</body>
</html>
