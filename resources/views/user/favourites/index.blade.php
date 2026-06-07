@extends('layouts.app')

@section('title', 'My Favourites')

@section('content')
    <div class="container">
        <h2 class="mb-4"><i class="fas fa-heart text-danger"></i> My Favourite Events</h2>

        @if($favourites->isEmpty())
            <div class="alert alert-info text-center py-5">
                <h4>You haven't favourited any events yet.</h4>
                <p>Browse events and click the heart icon to save them here!</p>
                <a href="{{ route('user.events.index') }}" class="btn btn-primary mt-3">Browse Events</a>
            </div>
        @else
            <div class="row">
                @foreach($favourites as $event)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <img src="{{ $event->image_url }}" class="card-img-top" alt="{{ $event->name }}"
                                style="height: 200px; object-fit: cover;"
                                onerror="this.onerror=null;this.src='{{ $event->fallback_image_url }}';">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title mb-0">{{ $event->name }}</h5>
                                    <span
                                        class="badge bg-{{ $event->category == 'running' ? 'success' : ($event->category == 'hiking' ? 'warning' : 'info') }}">
                                        {{ ucfirst($event->category) }}
                                    </span>
                                </div>
                                <p class="card-text text-muted small mb-2">
                                    <i class="fas fa-calendar"></i> {{ $event->event_date->format('d M Y') }}
                                </p>
                                <p class="card-text">{{ \Illuminate\Support\Str::limit($event->description, 80) }}</p>
                            </div>
                            <div class="card-footer bg-white border-top-0 d-flex justify-content-between">
                                <a href="{{ route('user.events.show', $event) }}" class="btn btn-outline-primary btn-sm">View</a>
                                <form action="{{ route('user.favourites.toggle', $event) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm" title="Remove from favourites">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4">
                {{ $favourites->links() }}
            </div>
        @endif
    </div>
@endsection