@extends('layouts.app')

@section('title', 'Event Timetable & Participation')

@section('content')
@extends('layouts.app')

@section('title', 'Event Timetable & Participation')

    @section('content')
        <div class="container py-5">
            <div class="d-flex justify-content-between align-items-center mb-5 animate-fade-in">
                <div>
                    <h2 class="fw-bold text-success display-6"><i class="fas fa-calendar-alt me-2"></i> Event Timetable</h2>
                    <p class="text-muted">Monitor event schedules and participant capacity.</p>
                </div>

                <div class="glass p-1 rounded-pill d-inline-flex">
                    <a href="{{ route('admin.timetable.index', ['category' => 'all']) }}"
                        class="btn btn-{{ $category == 'all' ? 'primary' : 'light text-muted' }} rounded-pill px-4">All</a>
                    <a href="{{ route('admin.timetable.index', ['category' => 'run']) }}"
                        class="btn btn-{{ $category == 'run' ? 'primary' : 'light text-muted' }} rounded-pill px-4">Running</a>
                    <a href="{{ route('admin.timetable.index', ['category' => 'hike']) }}"
                        class="btn btn-{{ $category == 'hike' ? 'primary' : 'light text-muted' }} rounded-pill px-4">Hiking</a>
                    <a href="{{ route('admin.timetable.index', ['category' => 'cycling']) }}"
                        class="btn btn-{{ $category == 'cycling' ? 'primary' : 'light text-muted' }} rounded-pill px-4">Cycling</a>
                </div>
            </div>

            <div class="card border-0 shadow mb-4 animate-fade-in delay-1 overflow-hidden"
                style="background: linear-gradient(to right, #e8f5e9, #ffffff);">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="bg-white p-3 rounded-circle shadow-sm me-3 text-success">
                            <i class="fas fa-filter fa-lg"></i>
                        </div>
                        <div>
                            <small class="text-uppercase text-muted fw-bold">Currently Viewing</small>
                            <h4 class="mb-0 fw-bold text-dark">{{ $category == 'all' ? 'All Categories' : ucfirst($category) }}
                            </h4>
                        </div>
                    </div>
                    <div class="text-end">
                        <h2 class="display-5 fw-bold text-primary mb-0">{{ $events->count() }}</h2>
                        <small class="text-muted">Events Found</small>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-lg animate-fade-in delay-2">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3 ps-4 text-uppercase text-muted small fw-bold">Date & Time</th>
                                    <th class="py-3 text-uppercase text-muted small fw-bold">Event Info</th>
                                    <th class="py-3 text-uppercase text-muted small fw-bold">Category</th>
                                    <th class="py-3 text-uppercase text-muted small fw-bold" style="width: 25%;">Participation
                                    </th>
                                    <th class="py-3 pe-4 text-uppercase text-muted small fw-bold text-end">Status</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                @forelse($events as $event)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex flex-column">
                                                <span
                                                    class="fw-bold text-dark fs-5">{{ \Carbon\Carbon::parse($event->event_date)->format('d') }}</span>
                                                <span class="text-uppercase text-muted small fw-bold"
                                                    style="font-size: 0.7rem;">{{ \Carbon\Carbon::parse($event->event_date)->format('M Y') }}</span>
                                                <span class="text-muted small mt-1"><i class="far fa-clock me-1"></i>
                                                    {{ $event->event_time }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $event->image_url }}"
                                                    class="rounded-3 shadow-sm me-3" width="60" height="60"
                                                    style="object-fit: cover;"
                                                    onerror="this.onerror=null;this.src='{{ $event->fallback_image_url }}';">
                                                <div>
                                                    <h6 class="mb-1 fw-bold text-dark">{{ $event->name }}</h6>
                                                    <small class="text-muted"><i class="fas fa-map-marker-alt me-1 text-danger"></i>
                                                        {{ Str::limit($event->location, 30) }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span
                                                class="badge rounded-pill bg-{{ $event->category === 'running' ? 'danger' : ($event->category === 'hiking' ? 'success' : 'warning text-dark') }} px-3 py-2 shadow-sm">
                                                {{ ucfirst($event->category) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span class="small fw-bold text-dark">{{ $event->participants->count() }} <span
                                                            class="text-muted fw-normal">/
                                                            {{ $event->max_participants }}</span></span>
                                                    <span
                                                        class="small fw-bold {{ $event->isFull() ? 'text-danger' : 'text-success' }}">
                                                        {{ round(($event->participants->count() / $event->max_participants) * 100) }}%
                                                    </span>
                                                </div>
                                                <div class="progress rounded-pill bg-light" style="height: 6px;">
                                                    @php
                                                        $percentage = ($event->participants->count() / $event->max_participants) * 100;
                                                        $color = $percentage >= 90 ? 'danger' : ($percentage >= 50 ? 'warning' : 'success');
                                                    @endphp
                                                    <div class="progress-bar bg-{{ $color }} rounded-pill" role="progressbar"
                                                        style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}"
                                                        aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                @if($event->participants->count() > 0)
                                                    <div class="mt-2 text-muted small d-flex align-items-center">
                                                        <i class="fas fa-users me-1 opacity-50"></i>
                                                        <span class="text-truncate" style="max-width: 150px;">
                                                            {{ $event->participants->take(3)->pluck('name')->implode(', ') }}
                                                        </span>
                                                        @if($event->participants->count() > 3)
                                                            <span class="ms-1 fw-bold">+{{ $event->participants->count() - 3 }}</span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="pe-4 text-end">
                                            @php
                                                $statusClass = match ($event->status) {
                                                    'upcoming' => 'bg-primary',
                                                    'ongoing' => 'bg-success',
                                                    'completed' => 'bg-secondary',
                                                    'cancelled' => 'bg-danger',
                                                    default => 'bg-secondary'
                                                };
                                            @endphp
                                            <span class="badge {{ $statusClass }} rounded-pill text-uppercase"
                                                style="font-size: 0.7rem;">
                                                {{ $event->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="opacity-50 mb-3">
                                                <i class="fas fa-search-location fa-3x text-muted"></i>
                                            </div>
                                            <h5 class="fw-bold text-muted">No events found.</h5>
                                            <p class="text-muted small">Try selecting a different category.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endsection
@endsection