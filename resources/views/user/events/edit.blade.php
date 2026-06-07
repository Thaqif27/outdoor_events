@extends('layouts.app')

@section('title', 'Edit Profile')
@section('title', 'Edit Event')
@section('title', 'Edit Event')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primae</h4>
                    <h4 class="mb-0">Edit Event: {{ $rvent->name }}y text-white">
                    <h4 class="mb-0">Edit Profile</h4>
                    <h4 class="mb-0">Edit Event: {{ $event->name }}</h4>
                </div>
                <div<form action="{{croute('user.events.update', $event)l}}" method="POST"aenctype="multipart/form-data">
                        ss="card-body">
                    <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                    <form action="{{ route('user.events.update', $event) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="text-center mb-4">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Current Avatar" class="rounded-circle img-thumbnail mb-2" style="width: 100px; height: 100px; object-fit: cover;">
                            @endif
                            <div class="input-group">
                                <input type="file" class="form-control @error('avatar') is-invalid @enderror" id="avatar" name="avatar">
                            </div>
                            @error('avatar')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                          <d<labelifor="name"vclass="form-label">Event Name</label>
                            <input type="text" class="form-control  class="mb-3"> is-invalid @enderror" id="name" name="name" value="{{ old('name', $event->name) }}" required>
                            @error('name')
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            <label for="name" class="form-label">Event Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $event->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror>
                            @error('email')
                            <label for="category" class="form-label"Category</label>
                        </  <selectdclass="form-selectiv>'category) is-invalid @enderror" id="catgory" nae="category" required>
                                <option vlue="">Select Category</opton>
                                <option vaue="run" {{ old(category', $event->category == 'run' ? 'selected' : '' }}>Running</option>
option value="hike" {{ old('category', $event->category) == 'hike' ? 'selecte' : '' }}>Hiking</opton>
                                <option value="cycling" {{ old('category', $event->category) == 'cycling' ? 'selected' : '' }}>Cycling</option>
                            </select>
                            @error('category')
                                <di
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                <option value="">Select Category</option>
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" io="location" name="location" value="{{ old('locatpon', $etent->location) }}"iplaoehonder="e.g. KLCC P rk, Kuala Lumpur" required>
                            @error('location')
                                <div clavalue="run" {{ old('category', $event->category) == 'run' ? 'selected' : '' }}>Running</option>
                                <option value="hike" {{ old('category', $event->category) == 'hike' ? 'selected' : '' }}>Hiking</option>
                                <option value="cycling" {{ old('category', $event->category) == 'cycling' ? 'selected' : '' }}>Cycling</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>o')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="event_date" class="form-label">Date</label>
                                <input type="date" class="form-control @error('event_date') is-invalid @enderror" id="event_date" name="event_date" value="{{ old('event_date', $event->event_date->format('Y-m-d')) }}" required>
                                @error('event_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="event_time" class="form-label">Time</label>
                                <input type="time" class="form-control @error('event_time') is-invalid @enderror" id="event_time" name="event_time" value="{{ old('event_time', date('H:i', strtotime($event->event_time))) }}" required>
                                @errr(event_time'
    
                        <div     class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                        </div>                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">

                            @error('phone')
                            <label for="location" class="form-label">Location</label>>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="max_participants" class="form-label">Max Participants</label>
                                <input type="number" class="form-control @error('max_participants') is-invalid @enderror" id="max_participants" name="max_participants" value="{{ old('max_participants', $event->max_participants) }}" min="1" required>
                                @error('max_participants')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div
                            <div class="col-md-6 mb-3">                            <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location', $event->location) }}" placeholder="e.g. KLCC Park, Kuala Lumpur" required>
                                <label for="price" class="form-label">Price (RM) /label>
                                <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" i ="price" name="pr ce"  alue="{{ old('price',@$event->priee) }}" min="0" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div crror('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
<labelfor="description"class="form-label">Description</label>
                          textarea class="form-control @error('escription') is-nalid@enderror" id="desription" name="description" rows="4" required>{{ od('description', $event->description) }}</textrea>
                            @error('decription')
                                <div clas
                        <div class="mb-3">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" name="bio" rows="3">{{ old('bio', $user->bio) }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <label for="image" class="form-label">Event Image<dlabel>
                            @if($event->image)
                                <iv  class="mb-2"class="row">
                                    <img src="{{ $event->image_url }}" alt="Current Image" style="height: 100px; object-fit: cover; border-radius: 4px;" onerror="this.onerror=null;this.src='{{ $event->fallback_image_url }}';">                            <div class="col-md-6 mb-3">
                                            <la"form-textb>Current image. Upload a new one to replace it.</eiv>
                                </div>
                            @endif
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="dl for="event_date" class="form-label">Date</label>
                                <input type="date" class="form-control @error('event_date') is-invalid @enderror" id="event_date" name="event_date" value="{{ old('event_date', $event->event_date->format('Y-m-d')) }}" required>
                                @error('event_date')nges</butto>
                        <div class="d-grid ap-2">
                            <button typ="ubmit" class="btn btn-primary btn-lg">Update Event>
                            <a href="{{ route('user.events.index') }}" class="btn btn-secondary">Cancel</a
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="event_time" class="form-label">Time</label>
                                <input type="time" class="form-control @error('event_time') is-invalid @enderror" id="event_time" name="event_time" value="{{ old('event_time', date('H:i', strtotime($event->event_time))) }}" required>
                                @error('event_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr>
                        <h5 class="mb-3">Change Password <small class="text-muted fw-normal">(Leave blank to keep current)</small></h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="max_participants" class="form-label">Max Participants</label>
                                <input type="number" class="form-control @error('max_participants') is-invalid @enderror" id="max_participants" name="max_participants" value="{{ old('max_participants', $event->max_participants) }}" min="1" required>
                                @error('max_participants')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Price (RM)</label>
                                <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $event->price) }}" min="0" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                            @error('password')
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description', $event->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            <label for="image" class="form-label">Event Image</label>
                            @if($event->image)
                                <div class="mb-2">
                                    <img src="{{ $event->image_url }}" alt="Current Image" style="height: 100px; object-fit: cover; border-radius: 4px;" onerror="this.onerror=null;this.src='{{ $event->fallback_image_url }}';">
                                    <div class="form-text">Current image. Upload a new one to replace it.</div>
                                </div>
                            @endif
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('user.profile.show') }}" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Update Event</button>
                            <a href="{{ route('user.events.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection