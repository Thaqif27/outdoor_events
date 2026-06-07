@extends('layouts.app')

@section('title', 'Admin Profile')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Admin Profile</h4>
                        <a href="{{ route('admin.profile.edit') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-edit"></i> Edit Profile
                        </a>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-4">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar"
                                    class="rounded-circle img-thumbnail"
                                    style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto"
                                    style="width: 150px; height: 150px; font-size: 4rem;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <h3>{{ $user->name }}</h3>
                        <p class="text-muted">{{ $user->email }}</p>

                        @if($user->bio)
                            <div class="mt-4 text-start px-md-5">
                                <h5>About</h5>
                                <p>{{ $user->bio }}</p>
                            </div>
                        @endif

                        <hr>

                        <div class="row mt-4">
                            <div class="col-6">
                                <h5>Role</h5>
                                <p class="fs-4">Admin</p>
                            </div>
                            <div class="col-6">
                                <h5>Created Events</h5>
                                <p class="fs-4">{{ $user->createdEvents->count() ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
