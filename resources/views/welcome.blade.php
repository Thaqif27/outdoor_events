@extends('layouts.app')

@section('content')
    <!-- Premium Hero Section -->
    <div class="hero-section text-center"
        style="background-image: url('{{ asset('images/hero-bg.jpg') }}');">
        <div class="hero-overlay"></div>
        <div class="container animate-fade-in py-5">
            <h1 class="display-3 fw-bold mb-4">Discover Your Next <span class="highlight">Adventure</span></h1>
            <p class="lead mb-5 fs-4">
                Join Malaysia's premier community for running, hiking, and cycling events.
                Experience nature like never before.
            </p>

            <div class="d-flex justify-content-center gap-3">
                @auth
                    <a href="{{ route('user.events.index') }}"
                        class="btn btn-warning btn-lg shadow-lg px-5 py-3 fw-bold rounded-pill">Browse Events</a>
                    <a href="{{ route('user.events.map') }}"
                        class="btn btn-outline-light btn-lg shadow-lg px-5 py-3 fw-bold rounded-pill">View Map</a>
                @else
                    <a href="{{ route('register') }}"
                        class="btn btn-warning btn-lg shadow-lg px-5 py-3 fw-bold rounded-pill">Get Started</a>
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-5 py-3 fw-bold rounded-pill">Login</a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="premium-section bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Choose Your Path</h2>
                <p class="text-muted mt-3">Find events tailored to your passion</p>
            </div>

            <div class="row g-4">
                <!-- Running Card -->
                <div class="col-md-4">
                    <div class="event-card text-center p-4">
                        <div class="mb-4 mt-3">
                            <span class="fa-stack fa-3x text-primary">
                                <i class="fas fa-circle fa-stack-2x opacity-10"></i>
                                <i class="fas fa-running fa-stack-1x"></i>
                            </span>
                        </div>
                        <h3 class="card-title fw-bold">Running</h3>
                        <p class="card-text text-muted mb-4">From fun runs to full marathons. Challenge your endurance and
                            speed.</p>
                        <a href="{{ route('user.events.index', ['category' => 'running']) }}"
                            class="btn btn-outline-success rounded-pill stretched-link">Explore Running</a>
                    </div>
                </div>

                <!-- Hiking Card -->
                <div class="col-md-4">
                    <div class="event-card text-center p-4">
                        <div class="mb-4 mt-3">
                            <span class="fa-stack fa-3x text-warning">
                                <i class="fas fa-circle fa-stack-2x opacity-10"></i>
                                <i class="fas fa-hiking fa-stack-1x"></i>
                            </span>
                        </div>
                        <h3 class="card-title fw-bold">Hiking</h3>
                        <p class="card-text text-muted mb-4">Explore scenic trails and conquer peaks. Connect with nature.
                        </p>
                        <a href="{{ route('user.events.index', ['category' => 'hiking']) }}"
                            class="btn btn-outline-warning rounded-pill stretched-link">Explore Hiking</a>
                    </div>
                </div>

                <!-- Cycling Card -->
                <div class="col-md-4">
                    <div class="event-card text-center p-4">
                        <div class="mb-4 mt-3">
                            <span class="fa-stack fa-3x text-info">
                                <i class="fas fa-circle fa-stack-2x opacity-10"></i>
                                <i class="fas fa-biking fa-stack-1x"></i>
                            </span>
                        </div>
                        <h3 class="card-title fw-bold">Cycling</h3>
                        <p class="card-text text-muted mb-4">Join group rides and races. Experience the thrill of the trail.
                        </p>
                        <a href="{{ route('user.events.index', ['category' => 'cycling']) }}"
                            class="btn btn-outline-info rounded-pill stretched-link">Explore Cycling</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection