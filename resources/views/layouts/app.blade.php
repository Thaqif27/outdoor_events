<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Outdoor Events')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Poppins:wght@500;600;700&display=swap"
        rel="stylesheet">

    <!-- Vite Assets (Modern Laravel Way) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="d-flex flex-column min-vh-100">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                <i class="fas fa-mountain text-warning"></i> Outdoor Events
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-1">
                    <ul class="navbar-nav ms-auto gap-1">
                        @auth
                            @if(auth()->user()->isAdmin())
                                <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('admin.events.index') }}">Events</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('admin.users.index') }}">Users</a></li>
                                <li class="nav-item"><a class="nav-link"
                                        href="{{ route('admin.timetable.index') }}">Timetable</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('admin.scraper.index') }}">Scraper</a>
                                </li>
                            @else
                                <li class="nav-item"><a class="nav-link" href="{{ route('user.dashboard') }}">Dashboard</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('user.events.index') }}">Explore
                                        Events</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('user.events.map') }}">Map</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('user.timetable.index') }}">My
                                        Schedule</a></li>
                            @endif

                            <!-- User Dropdown (For BOTH Admin and User) -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fas fa-user-circle me-1"></i> {{ auth()->user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-3 overflow-hidden">
                                    @if(auth()->user()->isAdmin())
                                        <li><a class="dropdown-item py-2" href="{{ route('admin.profile.show') }}"><i
                                                    class="fas fa-user text-primary me-2"></i> Profile</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                    @else
                                        <li><a class="dropdown-item py-2" href="{{ route('user.favourites.index') }}"><i
                                                    class="fas fa-heart text-danger me-2"></i> Favourites</a></li>
                                        <li><a class="dropdown-item py-2" href="{{ route('user.profile.show') }}"><i
                                                    class="fas fa-user text-primary me-2"></i> Profile</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                    @endif
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item py-2 text-danger">
                                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                            <li class="nav-item"><a class="nav-link btn btn-sm bg-white text-dark ms-2 px-3 fw-bold"
                                    href="{{ route('register') }}" style="color: var(--primary-color) !important;">Get
                                    Started</a></li>
                        @endauth
                    </ul>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Alert Messages -->
    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show rounded-3" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm alert-dismissible fade show rounded-3" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger border-0 shadow-sm alert-dismissible fade show rounded-3" role="alert">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="premium-footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <h5 class="mb-3"><i class="fas fa-mountain text-warning me-2"></i> Outdoor Events</h5>
                    <p class="small">Discover Malaysia's best outdoor adventures. From marathons to mountain
                        hikes, find your next challenge with us.</p>
                </div>
                <div class="col-md-4">
                    <h5 class="mb-3">Quick Links</h5>
                    <ul class="list-unstyled d-flex flex-column gap-2">
                        <li><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                        <li><a href="{{ route('user.events.index') }}" class="text-decoration-none">Events</a></li>
                        <li><a href="#" class="text-decoration-none">About Us</a></li>
                        <li><a href="#" class="text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 class="mb-3">Stay Updated</h5>
                    <p class="small">Join our newsletter for the latest event updates.</p>
                    <div class="input-group mt-3">
                        <input type="email" class="form-control" placeholder="Enter your email">
                        <button class="btn btn-subscribe" type="button">Subscribe</button>
                    </div>
                </div>
            </div>
            <div class="border-top mt-5 pt-4 text-center small">
                <p class="mb-0">&copy; {{ date('Y') }} Outdoor Events. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .hover-white:hover {
            color: white !important;
        }
    </style>
    @stack('scripts')
</body>

</html>