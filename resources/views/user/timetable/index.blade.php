@extends('layouts.app')

@section('title', 'My Schedule')

@section('content')
    <div class="container py-5">
        <!-- Header -->
        <div class="mb-5">
            <h1 class="display-4 fw-bold mb-2">📅 My Event Schedule</h1>
            <p class="text-muted fs-5">Your upcoming events organized by category</p>
        </div>

        @php
            $totalEvents = $runEvents->count() + $hikeEvents->count() + $cyclingEvents->count() + $otherEvents->count();
        @endphp

        <!-- Stats Overview -->
        @if($totalEvents > 0)
            <div class="row g-3 mb-5">
                <div class="col-6 col-md-3">
                    <div class="card border-0 bg-success bg-opacity-10 text-center h-100">
                        <div class="card-body">
                            <h5 class="text-success mb-2">🏃 Running</h5>
                            <h3 class="fw-bold text-success">{{ $runEvents->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 bg-warning bg-opacity-10 text-center h-100">
                        <div class="card-body">
                            <h5 class="text-warning mb-2">🥾 Hiking</h5>
                            <h3 class="fw-bold text-warning">{{ $hikeEvents->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 bg-info bg-opacity-10 text-center h-100">
                        <div class="card-body">
                            <h5 class="text-info mb-2">🚴 Cycling</h5>
                            <h3 class="fw-bold text-info">{{ $cyclingEvents->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 bg-secondary bg-opacity-10 text-center h-100">
                        <div class="card-body">
                            <h5 class="text-secondary mb-2">📌 Other</h5>
                            <h3 class="fw-bold text-secondary">{{ $otherEvents->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-info text-center py-5">
                <i class="fas fa-calendar-plus fs-1 mb-3 d-block"></i>
                <h5>No Events Scheduled Yet</h5>
                <p class="mb-0">Join events from the Explore Events page to add them to your schedule!</p>
                <a href="{{ route('user.events.index') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-search"></i> Explore Events
                </a>
            </div>
        @endif

        <!-- Running Events -->
        @if($runEvents->isNotEmpty())
            <div class="mb-5">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="fas fa-running fa-2x text-success"></i>
                    </div>
                    <h3 class="mb-0">Running Events</h3>
                </div>
                <div class="row g-3">
                    @foreach($runEvents as $event)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm border-0 transition" style="cursor: pointer; transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'" onclick="window.location='{{ route('user.events.show', $event) }}'">
                                <div class="card-body">
                                    <span class="badge bg-success mb-2">🏃 Running</span>
                                    <h5 class="card-title fw-bold mb-3">{{ $event->name }}</h5>
                                    
                                    <div class="mb-2">
                                        <small class="text-muted d-block mb-1">
                                            <i class="fas fa-calendar text-primary me-2"></i>{{ $event->event_date->format('M d, Y') }}
                                        </small>
                                        <small class="text-muted d-block mb-1">
                                            <i class="fas fa-clock text-warning me-2"></i>{{ date('g:i A', strtotime($event->event_time)) }}
                                        </small>
                                        <small class="text-muted d-block">
                                            <i class="fas fa-map-marker-alt text-danger me-2"></i>{{ $event->location }}
                                        </small>
                                    </div>
                                    
                                    <a href="{{ route('user.events.show', $event) }}" class="btn btn-sm btn-outline-primary w-100 mt-3">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Hiking Events -->
        @if($hikeEvents->isNotEmpty())
            <div class="mb-5">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="fas fa-mountain fa-2x text-warning"></i>
                    </div>
                    <h3 class="mb-0">Hiking Events</h3>
                </div>
                <div class="row g-3">
                    @foreach($hikeEvents as $event)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm border-0 transition" style="cursor: pointer; transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'" onclick="window.location='{{ route('user.events.show', $event) }}'">
                                <div class="card-body">
                                    <span class="badge bg-warning mb-2">🥾 Hiking</span>
                                    <h5 class="card-title fw-bold mb-3">{{ $event->name }}</h5>
                                    
                                    <div class="mb-2">
                                        <small class="text-muted d-block mb-1">
                                            <i class="fas fa-calendar text-primary me-2"></i>{{ $event->event_date->format('M d, Y') }}
                                        </small>
                                        <small class="text-muted d-block mb-1">
                                            <i class="fas fa-clock text-warning me-2"></i>{{ date('g:i A', strtotime($event->event_time)) }}
                                        </small>
                                        <small class="text-muted d-block">
                                            <i class="fas fa-map-marker-alt text-danger me-2"></i>{{ $event->location }}
                                        </small>
                                    </div>
                                    
                                    <a href="{{ route('user.events.show', $event) }}" class="btn btn-sm btn-outline-primary w-100 mt-3">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Cycling Events -->
        @if($cyclingEvents->isNotEmpty())
            <div class="mb-5">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-info bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="fas fa-biking fa-2x text-info"></i>
                    </div>
                    <h3 class="mb-0">Cycling Events</h3>
                </div>
                <div class="row g-3">
                    @foreach($cyclingEvents as $event)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm border-0 transition" style="cursor: pointer; transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'" onclick="window.location='{{ route('user.events.show', $event) }}'">
                                <div class="card-body">
                                    <span class="badge bg-info mb-2">🚴 Cycling</span>
                                    <h5 class="card-title fw-bold mb-3">{{ $event->name }}</h5>
                                    
                                    <div class="mb-2">
                                        <small class="text-muted d-block mb-1">
                                            <i class="fas fa-calendar text-primary me-2"></i>{{ $event->event_date->format('M d, Y') }}
                                        </small>
                                        <small class="text-muted d-block mb-1">
                                            <i class="fas fa-clock text-warning me-2"></i>{{ date('g:i A', strtotime($event->event_time)) }}
                                        </small>
                                        <small class="text-muted d-block">
                                            <i class="fas fa-map-marker-alt text-danger me-2"></i>{{ $event->location }}
                                        </small>
                                    </div>
                                    
                                    <a href="{{ route('user.events.show', $event) }}" class="btn btn-sm btn-outline-primary w-100 mt-3">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Other Events -->
        @if($otherEvents->isNotEmpty())
            <div class="mb-5">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-secondary bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="fas fa-calendar-alt fa-2x text-secondary"></i>
                    </div>
                    <h3 class="mb-0">Other Events</h3>
                </div>
                <div class="row g-3">
                    @foreach($otherEvents as $event)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm border-0 transition" style="cursor: pointer; transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'" onclick="window.location='{{ route('user.events.show', $event) }}'">
                                <div class="card-body">
                                    <span class="badge bg-secondary mb-2">📌 Other</span>
                                    <h5 class="card-title fw-bold mb-3">{{ $event->name }}</h5>
                                    
                                    <div class="mb-2">
                                        <small class="text-muted d-block mb-1">
                                            <i class="fas fa-calendar text-primary me-2"></i>{{ $event->event_date->format('M d, Y') }}
                                        </small>
                                        <small class="text-muted d-block mb-1">
                                            <i class="fas fa-clock text-warning me-2"></i>{{ date('g:i A', strtotime($event->event_time)) }}
                                        </small>
                                        <small class="text-muted d-block">
                                            <i class="fas fa-map-marker-alt text-danger me-2"></i>{{ $event->location }}
                                        </small>
                                    </div>
                                    
                                    <a href="{{ route('user.events.show', $event) }}" class="btn btn-sm btn-outline-primary w-100 mt-3">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Back Button -->
        <div class="mt-5 text-center">
            <a href="{{ route('user.events.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Explore Events
            </a>
        </div>
    </div>
@endsection