@extends('layouts.app')

@section('title', 'Create Event')

@section('content')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-bold text-success display-6"><i class="fas fa-plus-circle me-2"></i> Create New Event</h2>
                <p class="text-muted">Add a new event to the system</p>
            </div>
            <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary rounded-pill">
                <i class="fas fa-arrow-left me-2"></i>Back to Events
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
                <i class="fas fa-exclamation-circle me-2"></i> <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card border-0 shadow-lg">
            <div class="card-body p-5">
                <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Event Basic Info -->
                    <h5 class="fw-bold mb-4"><i class="fas fa-info-circle text-primary me-2"></i> Event Information</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label fw-bold">Event Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name') }}" placeholder="Enter event name" required>
                            @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                            <select class="form-select @error('category') is-invalid @enderror" id="category"
                                name="category" required>
                                <option value="">Select Category</option>
                                <option value="running" {{ old('category') == 'running' ? 'selected' : '' }}>🏃 Running</option>
                                <option value="hiking" {{ old('category') == 'hiking' ? 'selected' : '' }}>🥾 Hiking</option>
                                <option value="cycling" {{ old('category') == 'cycling' ? 'selected' : '' }}>🚴 Cycling</option>
                            </select>
                            @error('category')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="description" class="form-label fw-bold">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                                name="description" rows="5" placeholder="Describe the event in detail" required>{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Date & Time -->
                    <h5 class="fw-bold mb-4"><i class="fas fa-calendar text-primary me-2"></i> Date & Time</h5>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="event_date" class="form-label fw-bold">Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('event_date') is-invalid @enderror"
                                id="event_date" name="event_date" value="{{ old('event_date') }}" required>
                            @error('event_date')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="event_time" class="form-label fw-bold">Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('event_time') is-invalid @enderror"
                                id="event_time" name="event_time" value="{{ old('event_time') }}" required>
                            @error('event_time')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="location" class="form-label fw-bold">Location <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" id="location"
                                name="location" value="{{ old('location') }}" placeholder="e.g., Kuala Lumpur" required>
                            @error('location')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Location Coordinates -->
                    <h5 class="fw-bold mb-4"><i class="fas fa-map-marked-alt text-primary me-2"></i> Location Coordinates</h5>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Location Picker</label>
                        <div id="map-picker"
                            style="height: 400px; width: 100%; border-radius: 8px; margin-bottom: 10px;" class="border shadow-sm">
                        </div>
                        <small class="text-muted d-block">Click on the map to set event coordinates</small>
                        <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
                        <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="latitude_display" class="form-label fw-bold">Latitude</label>
                            <input type="text" class="form-control" id="latitude_display" disabled value="Click on map">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="longitude_display" class="form-label fw-bold">Longitude</label>
                            <input type="text" class="form-control" id="longitude_display" disabled value="Click on map">
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Event Details -->
                    <h5 class="fw-bold mb-4"><i class="fas fa-details text-primary me-2"></i> Event Details</h5>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="max_participants" class="form-label fw-bold">Max Participants <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('max_participants') is-invalid @enderror"
                                id="max_participants" name="max_participants" value="{{ old('max_participants', 100) }}" min="1" required>
                            @error('max_participants')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="price" class="form-label fw-bold">Price (RM) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror"
                                id="price" name="price" value="{{ old('price', 0) }}" step="0.01" min="0" required>
                            @error('price')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="source_url" class="form-label fw-bold">External Link</label>
                            <input type="url" class="form-control @error('source_url') is-invalid @enderror"
                                id="source_url" name="source_url" value="{{ old('source_url') }}" 
                                placeholder="https://example.com/event">
                            @error('source_url')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            <small class="text-muted d-block mt-1">Link to original event website for registration</small>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Image Upload -->
                    <h5 class="fw-bold mb-4"><i class="fas fa-image text-primary me-2"></i> Event Image</h5>
                    
                    <div class="mb-3">
                        <label for="image" class="form-label fw-bold">Upload Image</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image"
                            name="image" accept="image/*">
                        <small class="text-muted d-block mt-1">Supported formats: JPEG, PNG, JPG (Max 2MB)</small>
                        @error('image')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <hr class="my-4">

                    <!-- Buttons -->
                    <div class="d-flex gap-3 justify-content-end">
                        <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-success rounded-pill px-4">
                            <i class="fas fa-check me-2"></i>Create Event
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
                                required>
                            @error('max_participants')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror"
                                id="price" name="price" value="{{ old('price', 0) }}" min="0" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status"
                                required>
                                <option value="upcoming" {{ old('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                <option value="ongoing" {{ old('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed
                                </option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="image" class="form-label">Event Image</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image"
                                name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Create Event</button>
                    </div>
                </form>
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
            const defaultLoc = { lat: 3.140853, lng: 101.693207 };
            map = new google.maps.Map(document.getElementById("map-picker"), {
                zoom: 13,
                center: defaultLoc,
                mapId: "ADMIN_EVENT_CREATE_MAP"
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

                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);
                }
                placeMarkerAndPanTo(place.geometry.location, map);
            });

            map.addListener("click", (e) => {
                placeMarkerAndPanTo(e.latLng, map);
            });

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
                marker = new google.maps.Marker({ position: latLng, map: map });
            }
            map.panTo(latLng);
            document.getElementById("latitude").value = latLng.lat();
            document.getElementById("longitude").value = latLng.lng();
        }
        window.onload = initMap;
    </script>
@endpush