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
        <nav class="navbar navbar-expand-md nav-wrap navbar-light" style="background-color:white;">
            <a class="navbar-brand ps-3" href="/home">
                <img src="/images/phapcares_logo.png" class="img-fluid p-1" alt="" width="100" >
            </a>
            <div class="container-fluid">
                <div class="d-none d-md-block d-xl-block">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('home')) ? 'active' : '' }}" aria-current="page" href="/home">
                                Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('call-for-support*')) ? 'active' : '' }}" href="/call-for-support">Call for Support</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('product-donation*')) ? 'active' : '' }}" href="{{ route('pd-initial-details') }}">Product Donation</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('contributions*')) ? 'active' : '' }}" href="{{ route('contribution-list') }}">Contributions</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('inventory*')) ? 'active' : '' }}" href="{{route('inventory-list')}}">Inventory</a>
                        </li>
                    </ul>
                </div>
                <div></div>
                <div class="d-flex">
                    <div class="dropdown m-1">
                        <button class="btn bg-circle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-grip"></i>
                        </button>

                        <div class="dropdown-menu dropdown-menu-end apps-menu p-3">
                            <div class="row">
                                <div class="col-6">
                                    <div class="text-center">
                                        <a type="button" class="btn apps-btn" href="{{ route('pd-initial-details') }}">
                                            <i class="fa-solid fa-hand-holding-heart apps-icon"></i>
                                            <p class="mb-0">Donation</p>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <a type="button" class="btn apps-btn" href="/call-for-support">
                                            <i class="fa-solid fa-headset apps-icon"></i>
                                            <p class="mb-0">Support</p>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <a type="button" class="btn apps-btn" href="{{ route('contribution-list') }}">
                                            <i class="fa-solid fa-hand-holding-medical apps-icon"></i>
                                            <p class="mb-0">Contributions</p>
                                        </a>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="text-center">
                                        <a type="button" class="btn apps-btn" href="{{ route('all-users') }}">
                                            <i class="fa-solid fa-user apps-icon"></i>
                                            <p class="mb-0">Users</p>
                                        </a>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="text-center">
                                        <a type="button" class="btn apps-btn" href="{{ route('all-companies') }}">
                                            <i class="fa-solid fa-users apps-icon"></i>
                                            <p class="mb-0">Members</p>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <a type="button" class="btn apps-btn" href="{{ route('beneficiary-list') }}">
                                            <i class="fa-solid fa-hand-holding-hand apps-icon"></i>
                                            <p class="mb-0">Beneficiaries</p>
                                        </a>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="text-center">
                                        <a type="button" class="btn apps-btn" href="{{ route('allocation-list') }}">
                                            <i class="fa-solid fa-box-open apps-icon"></i>
                                            <p class="mb-0">Allocations</p>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <a type="button" class="btn apps-btn" href="{{route('product-destruction-list')}}">
                                            <i class="fa-solid fa-square-xmark apps-icon "></i>
                                            <p class="mb-0">Destruction</p>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <a type="button" class="btn apps-btn" href="{{route('reports-list')}}">
                                            <i class="fa-solid fa-folder apps-icon"></i>
                                            <p class="mb-0">Reports</p>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <a type="button" class="btn apps-btn" href="{{route('inventory-list')}}">
                                            <i class="fa-solid fa-boxes-stacked apps-icon"></i>
                                            <p class="mb-0">Inventory</p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown m-1">
                        <button class="btn bg-circle position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-folder"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $contributions_notif->count() }}
                                <span class="visually-hidden">unread messages</span>
                            </span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-notifs" aria-labelledby="dropdownMenuButton1">
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
                    <div class="dropdown m-1">
                        <button class="btn bg-circle position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-box"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $allocations_notif->count() }}
                                <span class="visually-hidden">unread messages</span>
                            </span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-notifs" aria-labelledby="dropdownMenuButton1">
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
                    <button type="button" class="btn bg-circle m-1" data-bs-toggle="modal" data-bs-target="#logoutModal">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </div>
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
                © Copyright 2023. PHAPCares Foundation. All Rights Reserved
            </div>
        </footer>

    </div>
    
    <script src="{{ asset('js/app.js') }}"></script>

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
