<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;400;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <link href="{{ asset('css/bootstrap-icons.css') }}" rel="stylesheet">

    <link href="{{ asset('css/pp.css') }}" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>


    <!-- Scripts -->
    @vite(['resources/js/app.js'])
</head>

<body>

    <main>

        <nav class="navbar navbar-expand-lg">
            <div class="container">

                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('Big P gym', 'Big P gym') }}
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link click-scroll" href="#section_1">Home</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link click-scroll" href="#section_2">Trainings</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link click-scroll" href="#section_3">Monitoring</a>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                        @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @endif

                        @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                        @endif
                        @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">{{ Auth::user()->username }}</a>
                        </li>

                        </li>
                        @endguest
                    </ul>
                </div>

            </div>
        </nav>

        

        <section class="index-section" id="section_1">
            <div class="section-overlay"></div>

            <div class="container d-flex justify-content-center align-items-center">
                <div class="row">

                    <div class="col-12 mt-auto mb-5 text-center">
                        <small>Big P gym presents</small>

                        <h1 class="text-white mb-5">Training plan</h1>

                        <button class="fancy-btn smoothscroll" href="#section_2"> Let's begin </button>
                        <!-- <a class="btn custom-btn smoothscroll" href="#section_2">Let's begin</a> -->
                    </div>
                    <div class="col-lg-12 col-12 mt-auto d-flex flex-column flex-lg-row text-center">
                    </div>
                </div>
            </div>

            <div class="video-wrap">
                <video autoplay="" loop="" muted="" class="custom-video" poster="">
                    <source src="{{ asset('videos/cut.mp4') }}" type="video/mp4">

                    Sorry, your browser does not support the video tag.
                </video>
            </div>
        </section>

        <section class="trainings-section section-padding section-bg" id="section_2">
            <div class="container">
                <div class="flex">

                    <div class="col-lg-8 col-12 mx-auto">
                        <h2 class="text-center mb-4">Trainings</h2>
                    </div>

                    <div class="trainings-thumb">
                        <div class="d-flex">
                            <div>
                                <h3>What you need to know</h3>
                            </div>
                        </div>

                        <ul class="trainings-list mt-3">
                            <li class="trainings-list-item">Training sessions are generated based on your experience and
                                equipment</li>

                            <li class="trainings-list-item">You will be able to choose how many sets and repetitions you
                                have</li>

                            <li class="trainings-list-item">We will remember your sets, repetitions and weights for
                                every
                                exercise you trained</li>

                            <li class="trainings-list-item">Your progress as well as last training session will be shown
                                in home
                                tab, so you can see your progress in various graphs</li>
                        </ul>

                        <a class="link-fx-1 color-contrast-higher mt-4" href="{{ url('/login') }}">
                            <span>Log in</span>
                            <svg class="icon" viewBox="0 0 32 32" aria-hidden="true">
                                <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="16" cy="16" r="15.5"></circle>
                                    <line x1="10" y1="18" x2="16" y2="12"></line>
                                    <line x1="16" y1="12" x2="22" y2="18"></line>
                                </g>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section class="last-section">
            <div class="container">
                <h2 class="text-black mb-4">Your last training was Push</h2>

                <i class="btn collapsible">Incline Dumbbell Press</i>

                <div class="content">

                    <table class="table table-hover">
                        <tr>
                            <th>Set</th>
                            <th>Weight</th>
                            <th>Repetitions</th>
                        </tr>
                        @for($i = 1; $i < 5; $i++) <tr>
                            <td>{{$i}}</td>
                            <td>{{$i*20}}</td>
                            <td>{{17- $i *2}}</td>
                            </tr>
                            @endfor
                    </table>
                </div>

                <i class="btn collapsible">Bench Press</i>

                <div class="content">

                    <table class="table table-hover">
                        <tr>
                            <th>Set</th>
                            <th>Weight</th>
                            <th>Repetitions</th>
                        </tr>
                        @for($i = 1; $i < 5; $i++) <tr>
                            <td>{{$i}}</td>
                            <td>{{$i*20}}</td>
                            <td>{{17- $i *2}}</td>
                            </tr>
                            @endfor
                    </table>
                </div>

                <i class="btn collapsible">Decline Flyes</i>

                <div class="content">

                    <table class="table table-hover">
                        <tr>
                            <th>Set</th>
                            <th>Weight</th>
                            <th>Repetitions</th>
                        </tr>
                        @for($i = 1; $i < 5; $i++) <tr>
                            <td>{{$i}}</td>
                            <td>{{$i*20}}</td>
                            <td>{{17- $i *2}}</td>
                            </tr>
                            @endfor
                    </table>
                </div>

                <i class="btn collapsible">Shoudler Dumbbell Press</i>

                <div class="content">

                    <table class="table table-hover">
                        <tr>
                            <th>Set</th>
                            <th>Weight</th>
                            <th>Repetitions</th>
                        </tr>
                        @for($i = 1; $i < 5; $i++) <tr>
                            <td>{{$i}}</td>
                            <td>{{$i*20}}</td>
                            <td>{{17- $i *2}}</td>
                            </tr>
                            @endfor
                    </table>
                </div>

                <i class="btn collapsible">Dumbbell Lateral Raises</i>

                <div class="content">

                    <table class="table table-hover">
                        <tr>
                            <th>Set</th>
                            <th>Weight</th>
                            <th>Repetitions</th>
                        </tr>
                        @for($i = 1; $i < 5; $i++) <tr>
                            <td>{{$i}}</td>
                            <td>{{$i*20}}</td>
                            <td>{{17- $i *2}}</td>
                            </tr>
                            @endfor
                    </table>
                </div>

                <i class="btn collapsible">Bend Over Dumbbell Reverse Fly</i>

                <div class="content">

                    <table class="table table-hover">
                        <tr>
                            <th>Set</th>
                            <th>Weight</th>
                            <th>Repetitions</th>
                        </tr>
                        @for($i = 1; $i < 5; $i++) <tr>
                            <td>{{$i}}</td>
                            <td>{{$i*20}}</td>
                            <td>{{17- $i *2}}</td>
                            </tr>
                            @endfor
                    </table>
                </div>

                <i class="btn collapsible">EZ Bar Skullcrusher</i>

                <div class="content">

                    <table class="table table-hover">
                        <tr>
                            <th>Set</th>
                            <th>Weight</th>
                            <th>Repetitions</th>
                        </tr>
                        @for($i = 1; $i < 5; $i++) <tr>
                            <td>{{$i}}</td>
                            <td>{{$i*20}}</td>
                            <td>{{17- $i *2}}</td>
                            </tr>
                            @endfor
                    </table>
                </div>

                <i class="btn collapsible">Rope Triceps Extension</i>

                <div class="content">

                    <table class="table table-hover">
                        <tr>
                            <th>Set</th>
                            <th>Weight</th>
                            <th>Repetitions</th>
                        </tr>
                        @for($i = 1; $i < 5; $i++) <tr>
                            <td>{{$i}}</td>
                            <td>{{$i*20}}</td>
                            <td>{{17- $i *2}}</td>
                            </tr>
                            @endfor
                    </table>
                </div>


                <h3 class="text-black" style="margin-top: 20px">Cardio:</h3>
                <i class="btn collapsible">Treadmill 10 minutes</i>

            </div>
        </section>

        <section class="graph-section" id="section_3">
            <div class="container">
                <h3 class="text-black" style='margin: 20px'>Weight lifted per day in the month </h3>
                {!! $lifted_in_month->container() !!}
                {!! $lifted_in_month->script() !!}
            </div>

            <div class="container">
                <h3 class="text-black" style='margin: 20px'>Weight in months </h3>
                {!! $weight_year->container() !!}
                {!! $weight_year->script() !!}
            </div>
        </section>
    </main>


    <footer class="site-footer">
        <div class="site-footer-top">
            <div class="container">
                <div class="row">

                    <div class="col-lg-6 col-12">
                        <h4 class="text-white mb-lg-0">Big P gym</h2>
                    </div>

                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">

                <div class="col-lg-6 col-12 mb-4 pb-2">
                    <h5 class="site-footer-title mb-3">Links</h5>

                    <ul class="site-footer-links">
                        <li class="site-footer-link-item">
                            <a href="#section_1" class="site-footer-link">Home</a>
                        </li>

                        <li class="site-footer-link-item">
                            <a href="#section_2" class="site-footer-link">Trainings</a>
                        </li>

                        <li class="site-footer-link-item">
                            <a href="#section_3" class="site-footer-link">Monitoring</a>
                        </li>

                    </ul>
                </div>

            </div>
        </div>

    </footer>



    <!-- JAVASCRIPT FILES -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/jquery.sticky.js') }}"></script>
    <script src="{{ asset('js/click-scroll.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-multiselect.js"></script>
    <script src="js/main.js"></script>

    <script>
    var coll = document.getElementsByClassName("collapsible");
    var i;

    for (i = 0; i < coll.length; i++) {
        coll[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var content = this.nextElementSibling;
            if (content.style.maxHeight) {
                content.style.maxHeight = null;
            } else {
                content.style.maxHeight = content.scrollHeight + "px";
            }
        });
    }
    </script>

</body>

</html>