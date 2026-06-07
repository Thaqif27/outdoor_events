@extends('layouts.app')

@section('title', 'Create Event')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Create New Event</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('user.events.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Event Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                    name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="category" class="form-label">Category</label>
                                    <select class="form-select @error('category') is-invalid @enderror" id="category"
                                        name="category" required>
                                        <option value="">Select Category</option>
                                        <option value="running" {{ old('category') == 'running' ? 'selected' : '' }}>Running
                                        </option>
                                        <option value="hiking" {{ old('category') == 'hiking' ? 'selected' : '' }}>Hiking
                                        </option>
                                        <option value="cycling" {{ old('category') == 'cycling' ? 'selected' : '' }}>Cycling
                                        </option>
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status"
                                        name="status" required>
                                        <option value="upcoming" {{ old('status') == 'upcoming' ? 'selected' : '' }}>Upcoming
                                        </option>
                                        <option value="ongoing" {{ old('status') == 'ongoing' ? 'selected' : '' }}>Ongoing
                                        </option>
                                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>
                                            Completed</option>
                                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>
                                            Cancelled</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Location</label>
                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control @error('location') is-invalid @enderror"
                                            id="location" name="location" value="{{ old('location') }}"
                                            placeholder="e.g. KLCC Park, Kuala Lumpur" required>
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="useCurrentLocation()">
                                            <i class="fas fa-location-arrow"></i> My Location
                                        </button>
                                    </div>
                                    <div id="map-picker"
                                        style="height: 300px; width: 100%; border-radius: 8px; margin-bottom: 10px;"
                                        class="border"></div>
                                    <small class="text-muted">Click on the map to set the exact location.</small>
                                    @error('location')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror

                                    <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
                                    <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="event_date" class="form-label">Date</label>
                                    <input type="date" class="form-control @error('event_date') is-invalid @enderror"
                                        id="event_date" name="event_date" value="{{ old('event_date') }}" required>
                                    @error('event_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="event_time" class="form-label">Time</label>
                                    <input type="time" class="form-control @error('event_time') is-invalid @enderror"
                                        id="event_time" name="event_time" value="{{ old('event_time') }}" required>
                                    @error('event_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="max_participants" class="form-label">Max Participants</label>
                                    <input type="number"
                                        class="form-control @error('max_participants') is-invalid @enderror"
                                        id="max_participants" name="max_participants" value="{{ old('max_participants') }}"
                                        min="1" required>
                                    @error('max_participants')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label">Price (RM)</label>
                                    <input type="number" step="0.01"
                                        class="form-control @error('price') is-invalid @enderror" id="price" name="price"
                                        value="{{ old('price') }}" min="0" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                                    name="description" rows="4" required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Event Image</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image"
                                    name="image">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">Create Event</button>
                                <a href="{{ route('user.events.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&libraries=places&v=weekly"></script>
    <script>
        let map, marker, autocomplete;

        function initMap() {
            const defaultLoc = { lat: 3.140853, lng: 101.693207 }; // KL
            const mapOptions = {
                zoom: 13,
                center: defaultLoc,
                mapId: "EVENT_CREATE_MAP"
            };

            map = new google.maps.Map(document.getElementById("map-picker"), mapOptions);

            map.addListener("click", (e) => {
                placeMarkerAndPanTo(e.latLng, map);
            });

            // Initialize Autocomplete
            const input = document.getElementById("location");
            autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.bindTo("bounds", map);

            autocomplete.addListener("place_changed", () => {
                const place = autocomplete.getPlace();

                if (!place.geometry || !place.geometry.location) {
                    window.alert("No details available for input: '" + place.name + "'");
                    return;
                }

                // If the place has a geometry, present it on a map.
                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);
                }
                placeMarkerAndPanTo(place.geometry.location, map);
            });

            // Initialize marker if old values exist
            const oldLat = document.getElementById("latitude").value;
            const oldLng = document.getElementById("longitude").value;
            if (oldLat && oldLng) {
                const pos = { lat: parseFloat(oldLat), lng: parseFloat(oldLng) };
                placeMarkerAndPanTo(pos, map);
            }
        }

        function placeMarkerAndPanTo(latLng, map) {
            if (marker) {
                marker.setPosition(latLng);
            } else {
                marker = new google.maps.Marker({
                    position: latLng,
                    map: map,
                });
            }
            map.panTo(latLng);

            document.getElementById("latitude").value = latLng.lat();
            document.getElementById("longitude").value = latLng.lng();
        }

        function useCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const pos = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude,
                        };
                        placeMarkerAndPanTo(new google.maps.LatLng(pos.lat, pos.lng), map);
                    },
                    () => {
                        alert("Error: The Geolocation service failed.");
                    }
                );
            } else {
                alert("Error: Your browser doesn't support geolocation.");
            }
        }

        window.onload = initMap;
    </script>
@endpush