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
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500&display=swap" rel="stylesheet">
    @yield('custom-css')

</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-light" style="background-color:white;">
            <a class="navbar-brand px-3" href="/home">
                <img src="/images/phapcares_logo.png" class="img-fluid p-1" alt="" width="85" >
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ (request()->is('home')) ? 'active' : '' }}" aria-current="page" href="/home">
                            Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ (request()->is('product-donation*')) ? 'active' : '' }}" href="{{ route('pd-initial-details') }}">Product Donation</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ (request()->is('call-for-support*')) ? 'active' : '' }}" href="/call-for-support">Call for Support</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link {{ (request()->is('users*')) ? 'active' : '' }} dropdown-toggle" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Users
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item nav-items-font" href="{{ route('all-users') }}" >Users</a></li>
                            <li><a class="dropdown-item nav-items-font" href="{{ route('all-companies') }}">Members</a></li>
                            <li><a class="dropdown-item nav-items-font" href="{{ route('beneficiary-list') }}">Beneficiaries</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ (request()->is('contributions*')) ? 'active' : '' }}" href="{{ route('contribution-list') }}">Contributions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ (request()->is('allocations*')) ? 'active' : '' }}" href="{{ route('allocation-list') }}">Allocations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ (request()->is('product-destruction*')) ? 'active' : '' }}" href="{{route('product-destruction-list')}}">Product Destruction</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ (request()->is('inventory*')) ? 'active' : '' }}" href="{{route('inventory-list')}}">Inventory</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ (request()->is('reports*')) ? 'active' : '' }}" href="{{route('reports-list')}}">Reports</a>
                    </li>
                </ul>
                <ul class="navbar-nav px-2">
                    <li class="nav-item">
                        <div class="dropdown">
                            <button class="btn bg-circle position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-folder"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $contributions_notif->count() }}
                                    <span class="visually-hidden">unread messages</span>
                                </span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                                <li class="notification-title fw-bold border-bottom pb-2">Pending Contribution Approvals</li>
                                @if($contributions_notif->count() > 0)
                                    @foreach($contributions_notif as $item)
                                        <li class="border-bottom">
                                            <a class="dropdown-item py-2" href="{{ route('contribution-details', ['contribution_id' => $item->id]) }}">
                                                <div class="notification-item">
                                                    <p class="mb-0"><span class="fw-bold">{{ $item->member_name }}</span></p>
                                                    <p class="mb-0">Contribution No. : <span class="fw-bold">{{ $item->contribution_no }}</span></p> 
                                                    <p class="mb-0">Date :<span class="fw-bold"> {{ $item->contribution_date }}</span></p>
                                                    <p class="mb-0">Donation :<span class="fw-bold text-green"> PHP {{ number_format($item->total_donation,2) }}</span></p>
                                                </div>
                                            </a>
                                        </li>
                                    @endforeach
                                    <li class="notification-title fw-bold pt-2">
                                        <a class="py-2" href="{{ route('contribution-list') }}">
                                        <p class="mb-0 fw-bold">See more</p>
                                        </a>
                                    </li>
                                @else
                                    <li class="notification-title pt-2 pb-1 text-secondary">No Pending Contribution Approvals</li>
                                @endif
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="dropdown">
                            <button class="btn bg-circle position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-box"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $allocations_notif->count() }}
                                    <span class="visually-hidden">unread messages</span>
                                </span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                                <li class="notification-title fw-bold border-bottom pb-2">Pending Allocation Approvals</li>
                                @if($allocations_notif->count() > 0)
                                    @foreach($allocations_notif as $item)
                                        <li class="border-bottom">
                                            <a class="dropdown-item py-2" href="{{ route('allocation-details', ['allocation_id' => $item->id]) }}">
                                                <div class="notification-item">
                                                    <p class="mb-0"><span class="fw-bold">{{ $item->name }}</span></p> 
                                                    <p class="mb-0">Allocation No. : <span class="fw-bold"> {{ $item->allocation_no }}</span></p>
                                                    <p class="mb-0">Product Amount : <span class="fw-bold text-green">PHP {{ number_format($item->total_allocated_products,2) }}</span></p>
                                                </div>
                                            </a>
                                        </li>
                                    @endforeach
                                    <li class="notification-title fw-bold pt-2">
                                        <a class="py-2" href="{{ route('allocation-list') }}">
                                        <p class="mb-0 fw-bold">See more</p>
                                        </a>
                                    </li>
                                @else
                                    <li class="notification-title pt-2 pb-1 text-secondary">No Pending Allocation Approvals</li>
                                @endif
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <button type="button" class="btn bg-circle" data-bs-toggle="modal" data-bs-target="#logoutModal">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- Modal -->
        <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Logging Out</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure to logout?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="{{ route('logout') }}" type="button" class="btn btn-danger" 
                        onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
                </div>
            </div>
        </div>

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
