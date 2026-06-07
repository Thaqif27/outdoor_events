@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Manage Users</h2>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Avatar</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Joined</th>
                                <th>Events Created</th>
                                <th>Events Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>
                                        @if($user->avatar)
                                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" width="40"
                                                height="40" class="rounded-circle object-fit-cover">
                                        @else
                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white"
                                                style="width: 40px; height: 40px;">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $user->name }}
                                        @if($user->bio)
                                            <br><small class="text-muted"
                                                title="{{ $user->bio }}">{{ Str::limit($user->bio, 30) }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                                    <td><span class="badge bg-info">{{ $user->created_events_count }}</span></td>
                                    <td><span class="badge bg-primary">{{ $user->participating_events_count }}</span></td>
                                    <td>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No users found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection