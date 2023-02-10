<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">

    <title>PHAPCares Donation</title>
 
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/global.css') }}" rel="stylesheet">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    @yield('custom-css')

</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-light" style="background-color:white;">
            <a class="navbar-brand px-3" href="/home">
                <img src="/images/phapcares_logo.png" class="img-fluid" alt="" width="100">
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item tt nav-bg" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Home">
                        <a class="nav-link {{ (request()->is('home*')) ? 'active' : '' }}" aria-current="page" href="/home">
                            <i class="fa-solid fa-house navbar-font-size nav-buttons"></i>
                        </a>
                    </li>
                    <li class="nav-item tt nav-bg" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Call for Support">
                        <a class="nav-link {{ (request()->is('call-for-support*')) ? 'active' : '' }}" href="/call-for-support">
                            <i class="fa-solid fa-hand-holding-medical nav-buttons navbar-font-size"></i>
                        </a>
                    </li>

                    <li class="nav-item tt nav-bg" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Users">
                        <a class="nav-link {{ (request()->is('users*')) ? 'active' : '' }}" href="/users">
                            <i class="fa-solid fa-users  navbar-font-size nav-buttons"></i>
                        </a>
                    </li>
                    <li class="nav-item nav-bg tt" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Product Donation">
                        <a class="nav-link {{ (request()->is('product-donation*')) ? 'active' : '' }}" href="/product-donation/initial-details">
                            <i class="fas fa-pen-square navbar-font-size nav-buttons"></i>
                        </a>
                    </li>
                    <li class="nav-item text-center">
                        <div class="row">
                            <a class="nav-link {{ (request()->is('product-donation*')) ? 'active' : '' }}" href="/product-donation/initial-details">
                                <div>
                                    <i class="fas fa-pen-square navbar-font-size nav-buttons"></i>
                                </div>
                                Product Donation
                            </a>
                        </div>
                    </li>
                    <li class="nav-item text-center">
                        <div class="row">
                            <a class="nav-link {{ (request()->is('product-donation*')) ? 'active' : '' }}" href="/product-donation/initial-details">
                                <div>
                                    <i class="fas fa-box navbar-font-size nav-buttons"></i>
                                </div>
                                Contribution
                            </a>
                        </div>
                    </li>
                </ul>
                <ul class="navbar-nav px-2">
                    <div class="menu-bg">
                        <li class="nav-item dropdown tt">
                            <a class="nav-link " href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-list-ul navbar-font-settings"></i>
                            </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                            <li>
                                <a class="dropdown-item" href="{{route('contribution-list')}}">
                                    <i class="fas fa-box text-secondary"></i>
                                    Contribution
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{route('allocation-list')}}">
                                <i class="fa-solid fa-location-arrow text-secondary"></i>
                                Allocations
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{route('product-destruction-list')}}">
                                    <i class="fa-solid fa-trash text-secondary"></i>
                                    Product Destruction
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{route('inventory-list')}}">
                                    <i class="fa-solid fa-boxes-stacked text-secondary"></i>
                                    Inventory
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{route('reports-list')}}">
                                    <i class="fa-solid fa-boxes-stacked text-secondary"></i>
                                    Reports
                                </a>
                            </li>
                        </ul>
                        </li>
                    </div>

                    <div class="menu-bg">
                        <li class="nav-item dropdown tt">
                            <a class="nav-link " href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-gear navbar-font-settings"></i>
                            </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>

                            </li>
                        </ul>
                        </li>
                    </div>
                </ul>
            </div>
        </nav>

        <main class="py-4">
            <div class="container-fluid">
            @yield('content')
            </div>
        </main>

        <footer class="footer">
            <div class="d-flex p-2 justify-content-center">
                © Copyright 2022. PHAPCares Foundation. All Rights Reserved
            </div>
        </footer>
    </div>

    <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->

    
    <script src="{{ mix('js/app.js') }}"></script>

    <script>
        $(document).ready(function () {
            const tooltips = document.querySelectorAll('.tt')
            tooltips.forEach(t => {
                new bootstrap.Tooltip(t)
            })
        });
    </script>

    @yield('custom-js')


</body>
</html>
