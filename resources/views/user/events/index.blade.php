@extends('layouts.app')

@section('title', 'Events')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Discover Events</h2>
        <a href="{{ route('user.events.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create Event
        </a>
    </div>

    <!-- Filter Section -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-3">
                <i class="fas fa-filter"></i> Filter Events
            </h5>
            
            <form method="GET" action="{{ route('user.events.index') }}" class="row g-3">
                <!-- Search Filter -->
                <div class="col-md-4">
                    <label for="search" class="form-label">Search Events</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           placeholder="Event name or location..." 
                           value="{{ request('search') }}">
                </div>

                <!-- Category Filter -->
                <div class="col-md-4">
                    <label for="category" class="form-label">Activity Type</label>
                    <select class="form-select" id="category" name="category" onchange="this.form.submit()">
                        <option value="">All Activities</option>
                        <option value="running" {{ request('category') == 'running' ? 'selected' : '' }}>
                            <i class="fas fa-person-running"></i> Running
                        </option>
                        <option value="hiking" {{ request('category') == 'hiking' ? 'selected' : '' }}>
                            <i class="fas fa-person-hiking"></i> Hiking
                        </option>
                        <option value="cycling" {{ request('category') == 'cycling' ? 'selected' : '' }}>
                            <i class="fas fa-person-biking"></i> Cycling
                        </option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="col-md-4">
                    <label class="form-label d-block">&nbsp;</label>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Search
                        </button>
                        @if(request('search') || request('category'))
                            <a href="{{ route('user.events.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Clear Filters
                            </a>
                        @endif
                    </div>
                </div>
            </form>

            <!-- Quick Category Buttons -->
            <div class="mt-3 pt-3 border-top">
                <p class="text-muted small mb-2">Quick filters:</p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('user.events.index') }}" class="btn btn-sm {{ !request('category') ? 'btn-info' : 'btn-outline-info' }}">
                        <i class="fas fa-list"></i> All Events
                    </a>
                    <a href="{{ route('user.events.index', ['category' => 'running']) }}" class="btn btn-sm {{ request('category') == 'running' ? 'btn-success' : 'btn-outline-success' }}">
                        <i class="fas fa-person-running"></i> Running
                    </a>
                    <a href="{{ route('user.events.index', ['category' => 'hiking']) }}" class="btn btn-sm {{ request('category') == 'hiking' ? 'btn-warning' : 'btn-outline-warning' }}">
                        <i class="fas fa-person-hiking"></i> Hiking
                    </a>
                    <a href="{{ route('user.events.index', ['category' => 'cycling']) }}" class="btn btn-sm {{ request('category') == 'cycling' ? 'btn-info' : 'btn-outline-info' }}">
                        <i class="fas fa-person-biking"></i> Cycling
                    </a>
                    <a href="{{ route('user.events.map') }}" class="btn btn-sm btn-outline-secondary ms-auto">
                        <i class="fas fa-map"></i> View Map
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if($events->isEmpty())
        <div class="alert alert-info text-center py-5">
            <h4>No events found.</h4>
            <p>Try adjusting your filters or create your own event!</p>
            <a href="{{ route('user.events.create') }}" class="btn btn-primary mt-3">Create Event</a>
        </div>
    @else
        <div class="row">
            @forelse($events as $event)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm hover-shadow" style="transition: transform 0.2s;">
                        <img src="{{ $event->image_url }}" class="card-img-top" alt="{{ $event->name }}" style="height: 200px; object-fit: cover;"
                            onerror="this.onerror=null;this.src='{{ $event->fallback_image_url }}';">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0">{{ $event->name }}</h5>
                                <span class="badge bg-{{ $event->category == 'running' ? 'success' : ($event->category == 'hiking' ? 'warning' : 'info') }} text-white">
                                    @if($event->category == 'running')
                                        <i class="fas fa-person-running"></i> Running
                                    @elseif($event->category == 'hiking')
                                        <i class="fas fa-person-hiking"></i> Hiking
                                    @else
                                        <i class="fas fa-person-biking"></i> Cycling
                                    @endif
                                </span>
                            </div>
                            
                            @if($event->source_url)
                                <div class="mb-2">
                                    <span class="badge bg-dark text-white px-2 py-1 small">
                                        <i class="fas fa-link me-1"></i> {{ strtoupper($event->source ?? 'external') }}
                                    </span>
                                </div>
                            @endif
                            
                            <p class="card-text text-muted small mb-2">
                                <i class="fas fa-calendar"></i> {{ $event->event_date->format('d M Y') }} &bull; {{ date('H:i', strtotime($event->event_time)) }}
                            </p>
                            <p class="card-text text-muted small mb-3">
                                <i class="fas fa-map-marker-alt"></i> {{ \Illuminate\Support\Str::limit($event->location, 30) }}
                            </p>
                            <p class="card-text">{{ \Illuminate\Support\Str::limit($event->description, 80) }}</p>
                            
                            @if($event->price == 0)
                                <span class="badge bg-success text-white mb-2">
                                    <i class="fas fa-gift me-1"></i> FREE
                                </span>
                            @else
                                <p class="text-success fw-bold mb-2">RM {{ number_format($event->price, 2) }}</p>
                            @endif
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <div class="d-grid gap-2">
                                @if($event->source_url)
                                    @php
                                        $isJoined = auth()->check() && $event->participants->contains(auth()->id());
                                    @endphp
                                    @if($isJoined)
                                        <a href="{{ $event->source_url }}" target="_blank" class="btn btn-success btn-sm" rel="noopener noreferrer">
                                            <i class="fas fa-check-circle me-1"></i> Joined - View Event
                                        </a>
                                    @else
                                        <a href="{{ $event->source_url }}" target="_blank" class="btn btn-primary btn-sm fw-bold" rel="noopener noreferrer">
                                            <i class="fas fa-external-link-alt me-1"></i> REGISTER NOW
                                        </a>
                                    @endif
                                    <a href="{{ route('user.events.show', $event) }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-info-circle me-1"></i> Details
                                    </a>
                                @else
                                    <a href="{{ route('user.events.show', $event) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-arrow-right me-1"></i> View & Join
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        No upcoming events found. Why not create one?
                    </div>
                </div>
            @endforelse
        </div>
        
        <div class="mt-4">
            {{ $events->links() }}
        </div>
    @endif
</div>

<style>
    .hover-shadow:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        transform: translateY(-2px);
    }
</style>
@endsection