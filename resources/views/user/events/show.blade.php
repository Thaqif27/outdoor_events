@extends('layouts.app')

@section('title', $event->name)

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle"></i> {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Back Button -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="{{ route('user.events.index') }}" class="btn btn-outline-secondary btn-sm px-3 rounded-pill shadow-sm bg-white">
                    <i class="fas fa-arrow-left me-1"></i> Back to Explore
                </a>
                @if($event->event_date)
                    <span class="text-muted small fw-medium">
                        <i class="far fa-clock me-1"></i> Added {{ $event->created_at->diffForHumans() }}
                    </span>
                @endif
            </div>

            <!-- Event Card -->
            <div class="card border-0 shadow-lg overflow-hidden" style="border-radius: 20px;">
                <!-- Event Image -->
                <div class="position-relative">
                    <img src="{{ $event->image_url }}" class="card-img-top" alt="{{ $event->name }}" style="height: 450px; object-fit: cover;"
                        onerror="this.onerror=null;this.src='{{ $event->fallback_image_url }}';">
                    <div class="position-absolute bottom-0 start-0 w-100 p-4 bg-gradient-to-t from-dark to-transparent text-white" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                        @if($event->price == 0)
                            <span class="badge bg-success px-3 py-2 rounded-pill shadow-sm">
                                <i class="fas fa-gift me-1"></i> FREE EVENT
                            </span>
                        @endif
                    </div>
                </div>

                <div class="card-body p-xl-5 p-4">
                    @php
                        $categoryMap = [
                            'running' => ['success', 'Running', '🏃'],
                            'hiking' => ['warning', 'Hiking', '🥾'],
                            'cycling' => ['info', 'Cycling', '🚴'],
                            'run' => ['success', 'Running', '🏃'],
                            'hike' => ['warning', 'Hiking', '🥾'],
                            'cycle' => ['info', 'Cycling', '🚴']
                        ];
                        $category = strtolower($event->category);
                        $badgeInfo = $categoryMap[$category] ?? ['secondary', ucfirst($category), '🎯'];
                        $isJoined = auth()->check() && $event->participants && $event->participants->contains(auth()->id());
                    @endphp

                    <!-- Title & Badges -->
                    <div class="mb-5">
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                            <span class="badge bg-{{ $badgeInfo[0] }} px-3 py-2 rounded-pill shadow-sm" style="font-size: 0.9rem;">
                                {{ $badgeInfo[2] }} {{ $badgeInfo[1] }}
                            </span>
                            <span class="badge bg-{{ $event->status == 'upcoming' ? 'primary' : 'secondary' }} px-3 py-2 rounded-pill shadow-sm" style="font-size: 0.9rem;">
                                {{ ucfirst($event->status) }}
                            </span>
                            @if($isJoined)
                                <span class="badge bg-success px-3 py-2 rounded-pill shadow-sm" style="font-size: 0.9rem;">
                                    <i class="fas fa-check-circle"></i> Joined
                                </span>
                            @endif
                            <span class="badge bg-dark px-3 py-2 rounded-pill shadow-sm text-white" style="font-size: 0.9rem;">
                                <i class="fas fa-database me-1"></i> {{ strtoupper($event->source ?? 'internal') }}
                            </span>
                        </div>
                        <h1 class="display-4 fw-bold text-dark mb-0">{{ $event->name }}</h1>
                    </div>

                    <!-- Quick Info Cards -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="card border-0 bg-light h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                                                <i class="fas fa-calendar-alt fa-2x text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="text-muted mb-1">Date & Time</h6>
                                            <p class="mb-0 fw-bold">{{ $event->event_date ? $event->event_date->format('l, d F Y') : 'Date TBA' }}</p>
                                            <small class="text-muted">{{ $event->event_time ?? 'Time TBA' }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 bg-light h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                                                <i class="fas fa-map-marker-alt fa-2x text-danger"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="text-muted mb-1">Location</h6>
                                            <p class="mb-0 fw-bold">{{ $event->location }}</p>
                                            @if($event->latitude && $event->longitude)
                                                <small class="text-muted">{{ number_format($event->latitude, 4) }}, {{ number_format($event->longitude, 4) }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Event Information Grid -->
                    <div class="row g-4 mb-5">
                        <div class="col-6 col-md-3">
                            <div class="h-100 p-3 bg-white border rounded-4 shadow-sm text-center transition-hover">
                                <i class="fas fa-tag text-success fs-2 mb-2"></i>
                                <h6 class="text-muted small fw-bold mb-1">PRICE</h6>
                                <p class="mb-0 fw-bold fs-5 text-success">
                                    {{ $event->price > 0 ? 'RM ' . number_format($event->price, 2) : 'FREE' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="h-100 p-3 bg-white border rounded-4 shadow-sm text-center transition-hover">
                                <i class="fas fa-users text-primary fs-2 mb-2"></i>
                                <h6 class="text-muted small fw-bold mb-1">CAPACITY</h6>
                                <p class="mb-0 fw-bold fs-5">
                                    {{ $event->participants ? $event->participants->count() : 0 }} / {{ $event->max_participants ?? '∞' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="h-100 p-3 bg-white border rounded-4 shadow-sm text-center transition-hover">
                                <i class="fas fa-user-circle text-warning fs-2 mb-2"></i>
                                <h6 class="text-muted small fw-bold mb-1">ORGANIZER</h6>
                                <p class="mb-0 fw-bold text-truncate px-1" title="{{ $event->creator->name ?? 'System Scraper' }}">
                                    {{ $event->creator->name ?? 'Verified' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="h-100 p-3 bg-white border rounded-4 shadow-sm text-center transition-hover">
                                <i class="fas fa-link text-info fs-2 mb-2"></i>
                                <h6 class="text-muted small fw-bold mb-1">OFFICIAL</h6>
                                <p class="mb-0">
                                    <span class="badge bg-light text-dark border">{{ strtoupper($event->source ?? 'local') }}</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Event Details Row -->
                    <div class="row mb-4">
                        <div class="col-md-12 mb-4">
                            <h5 class="mb-4 text-dark fw-bold">
                                <i class="fas fa-info-circle text-primary me-2"></i>About this Event
                            </h5>
                            <div class="card border-0 shadow-sm" style="border-radius: 15px; background: #fafafa;">
                                <div class="card-body p-4">
                                    @php
                                        $cleanDescription = $event->description;
                                        // Highlight key phrases (Running, Hiking, etc.)
                                        $keywords = ['Running', 'Marathon', 'Half Marathon', 'Hiking', 'Cycling', 'Checkpoint', 'JomRun', 'Ticket2U', 'Register'];
                                        foreach($keywords as $word) {
                                            $cleanDescription = str_ireplace($word, "<span class='text-primary fw-bold'>$word</span>", $cleanDescription);
                                        }
                                        // Ensure line breaks are preserved with a clean spacing
                                        $cleanDescription = nl2br($cleanDescription);
                                    @endphp
                                    <div class="description-content" style="line-height: 1.8; color: #444; font-size: 1.05rem;">
                                        {!! $cleanDescription !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>

                        <!-- Event Location Map -->
                        <div class="col-md-12">
                            @if($event->latitude && $event->longitude)
                                <h5 class="mb-4 text-dark fw-bold">
                                    <i class="fas fa-map-marked-alt text-primary me-2"></i>Map Location
                                </h5>
                                <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 15px;">
                                    <div id="event-map" style="width: 100%; height: 450px;"></div>
                                </div>
                            @else
                                <h5 class="mb-4 text-dark fw-bold">
                                    <i class="fas fa-map-marker-alt text-warning me-2"></i>Location Details
                                </h5>
                                <div class="alert alert-light border shadow-sm p-4 d-flex align-items-center" style="border-radius: 15px;">
                                    <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                                        <i class="fas fa-location-arrow text-warning fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-bold text-dark">{{ $event->location }}</h6>
                                        <p class="mb-0 text-muted small">Exact map coordinates are not available, but you can find it at the address above.</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <hr class="my-5 opacity-10">

                    <!-- Action Buttons -->
                    <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center">
                        <a href="{{ route('user.events.index') }}" class="btn btn-light border px-4 py-2 rounded-pill transition-hover">
                            <i class="fas fa-arrow-left me-2"></i> Back to Listing
                        </a>
                        
                        <div class="d-flex flex-wrap gap-3">
                            @if($event->source_url)
                                @if($isJoined)
                                    <!-- Already Joined - Show link -->
                                    <a href="{{ $event->source_url }}" target="_blank" class="btn btn-success px-4 py-2 rounded-pill shadow-sm transition-hover" rel="noopener noreferrer">
                                        <i class="fas fa-external-link-alt me-2"></i> Official Event Page
                                    </a>
                                @else
                                    <!-- External Event - Track Join & Redirect -->
                                    <form action="{{ route('user.events.join', $event) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill shadow-lg transition-hover">
                                            <i class="fas fa-ticket-alt me-2"></i> REGISTER ON {{ strtoupper($event->source ?? 'SOURCE') }}
                                        </button>
                                    </form>
                                @endif
                            @else
                                <!-- Internal Event - Direct Join -->
                                @if(!$isJoined)
                                    <form action="{{ route('user.events.join', $event) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill shadow-lg transition-hover">
                                            <i class="fas fa-calendar-plus me-2"></i> JOIN THIS EVENT
                                        </button>
                                    </form>
                                @endif
                            @endif

                            <!-- Favourite Button -->
                            <form action="{{ route('user.favourites.toggle', $event) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger px-4 py-2 rounded-pill transition-hover">
                                    <i class="fas fa-heart{{ auth()->check() && auth()->user()->favourites && auth()->user()->favourites->contains($event->id) ? '' : '-o' }} me-2"></i> 
                                    {{ auth()->check() && auth()->user()->favourites && auth()->user()->favourites->contains($event->id) ? 'Saved' : 'Save' }}
                                </button>
                            </form>
                        </div>
                    </div>

                    <style>
                        .transition-hover { transition: all 0.3s ease; }
                        .transition-hover:hover { transform: translateY(-3px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }
                        .description-content strong { color: #000; }
                    </style>

                            <!-- Edit & Delete (Owner Only) -->
                            @if(auth()->check() && auth()->id() == $event->created_by)
                                <a href="{{ route('user.events.edit', $event) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('user.events.destroy', $event) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this event?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($event->latitude && $event->longitude)
    @push('scripts')
        <script>
            ((g) => { 
                var h, a, k, p = "The Google Maps JavaScript API", c = "google", l = "importLibrary", q = "__ib__", m = document, b = window; 
                b = b[c] || (b[c] = {}); 
                var d = b.maps || (b.maps = {}), r = new Set, e = new URLSearchParams, u = () => h || (h = new Promise(async (f, n) => { 
                    await (a = m.createElement("script")); 
                    e.set("libraries", [...r] + ""); 
                    for (k in g) e.set(k.replace(/[A-Z]/g, t => "_" + t[0].toLowerCase()), g[k]); 
                    e.set("callback", c + ".maps." + q); 
                    a.src = `https://maps.googleapis.com/maps/api/js?` + e; 
                    d[q] = f; 
                    a.onerror = () => h = n(Error(p + " could not load.")); 
                    a.nonce = m.querySelector("script[nonce]")?.nonce || ""; 
                    m.head.append(a) 
                })); 
                d[l] ? console.warn(p + " only loads once. Ignoring:", g) : d[l] = (f, ...n) => r.add(f) && u().then(() => d[l](f, ...n)) 
            })({
                key: "{{ config('services.google_maps.api_key') }}",
                v: "weekly",
                libraries: ["maps", "marker"],
            });

            async function initEventMap() {
                try {
                    const { Map, InfoWindow } = await google.maps.importLibrary("maps");
                    const { AdvancedMarkerElement, PinElement } = await google.maps.importLibrary("marker");

                    const eventLocation = { 
                        lat: parseFloat({{ $event->latitude }}), 
                        lng: parseFloat({{ $event->longitude }}) 
                    };

                    // Create 3D map with tilt and heading
                    const map = new Map(document.getElementById("event-map"), {
                        center: eventLocation,
                        zoom: 17,
                        mapId: "EVENT_DETAIL_MAP",
                        tilt: 45,  // 3D view
                        heading: 0,
                        mapTypeId: 'satellite',
                    });

                    // Determine pin color based on category
                    const category = "{{ strtolower($event->category) }}";
                    const pinColor = category === 'running' ? '#28a745' : 
                                    (category === 'hiking' ? '#ffc107' : '#17a2b8');

                    // Create colored marker
                    const pin = new PinElement({
                        background: pinColor,
                        borderColor: "#ffffff",
                        glyphColor: "#ffffff",
                        scale: 1.5,
                    });

                    const marker = new AdvancedMarkerElement({
                        map: map,
                        position: eventLocation,
                        content: pin.element,
                        title: "{{ addslashes($event->name) }}",
                    });

                    // Add info window
                    const infoWindow = new InfoWindow({
                        content: `
                            <div style="padding: 10px; min-width: 250px;">
                                <h6 class="mb-2"><strong>{{ addslashes($event->name) }}</strong></h6>
                                <p class="mb-1 small text-muted">
                                    <i class="fas fa-map-marker-alt"></i> {{ addslashes($event->location) }}
                                </p>
                                <p class="mb-1 small">
                                    <i class="fas fa-calendar"></i> {{ $event->event_date ? $event->event_date->format('M d, Y') : 'TBA' }}
                                </p>
                                <span class="badge bg-{{ $badgeInfo[0] }}">{{ $badgeInfo[1] }}</span>
                            </div>
                        `,
                    });

                    // Auto-open info window
                    infoWindow.open(map, marker);

                    // Click marker to toggle info window
                    marker.addListener("click", () => {
                        infoWindow.open(map, marker);
                    });

                    // Add map controls
                    const toggleViewBtn = document.createElement('button');
                    toggleViewBtn.textContent = 'Toggle 2D/3D';
                    toggleViewBtn.classList.add('btn', 'btn-sm', 'btn-primary', 'm-2');
                    toggleViewBtn.style.cssText = 'position: absolute; top: 10px; left: 10px; z-index: 5;';
                    
                    let is3D = true;
                    toggleViewBtn.onclick = () => {
                        if (is3D) {
                            map.setTilt(0);
                            map.setMapTypeId('roadmap');
                            toggleViewBtn.textContent = 'Show 3D View';
                        } else {
                            map.setTilt(45);
                            map.setMapTypeId('satellite');
                            toggleViewBtn.textContent = 'Show 2D View';
                        }
                        is3D = !is3D;
                    };
                    
                    document.getElementById('event-map').appendChild(toggleViewBtn);

                } catch (error) {
                    console.error("Error loading event map:", error);
                    document.getElementById('event-map').innerHTML = `
                        <div class="d-flex align-items-center justify-content-center h-100 bg-light">
                            <div class="text-center p-4">
                                <i class="fas fa-exclamation-triangle text-warning fs-3 mb-2"></i>
                                <p class="mb-0">Unable to load map. Please check your connection.</p>
                            </div>
                        </div>
                    `;
                }
            }

            // Initialize map when page loads
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initEventMap);
            } else {
                initEventMap();
            }
        </script>
    @endpush
@endif
@endsection