@extends('layouts.app')

@section('title', 'System Automation Status')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="d-flex align-items-center mb-4">
                    <div class="me-3 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 60px; height: 60px;">
                        <i class="fas fa-robot fa-2x"></i>
                    </div>
                    <div>
                        <h2 class="fw-bold mb-0">Automation Status</h2>
                        <p class="text-muted mb-0">System Scraper & Health Monitor</p>
                    </div>
                </div>

                <div class="row g-4 mb-5">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-3 text-success"><i class="fas fa-check-circle me-2"></i> Active
                                    Scrapers</h5>
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-ticket-alt text-muted me-2"></i> Eventbrite</span>
                                        <span class="badge bg-success-subtle text-success">Active</span>
                                    </div>
                                    <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-running text-muted me-2"></i> JomRun</span>
                                        <span class="badge bg-success-subtle text-success">Active</span>
                                    </div>
                                    <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-mountain text-muted me-2"></i> Ticket2U</span>
                                        <span class="badge bg-success-subtle text-success">Active</span>
                                    </div>
                                    <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-flag-checkered text-muted me-2"></i> CheckpointSpot</span>
                                        <span class="badge bg-success-subtle text-success">Active</span>
                                    </div>
                                    <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-medal text-muted me-2"></i> Finishers</span>
                                        <span class="badge bg-success-subtle text-success">Active</span>
                                    </div>
                                    <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-users text-muted me-2"></i> Meetup</span>
                                        <span class="badge bg-success-subtle text-success">Active</span>
                                    </div>
                                    <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-hiking text-muted me-2"></i> SGTrek</span>
                                        <span class="badge bg-success-subtle text-success">Active</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100 bg-light">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-3 text-dark"><i class="fas fa-database me-2"></i> Database Stats</h5>
                                <div class="row text-center mt-4">
                                    <div class="col-6 border-end">
                                        <h2 class="display-4 fw-bold text-primary mb-0">{{ \App\Models\Event::count() }}
                                        </h2>
                                        <small class="text-muted text-uppercase fw-bold">Total Events</small>
                                    </div>
                                    <div class="col-6">
                                        <h2 class="display-4 fw-bold text-success mb-0">
                                            {{ \App\Models\Event::where('status', 'upcoming')->count() }}
                                        </h2>
                                        <small class="text-muted text-uppercase fw-bold">Upcoming</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow bg-primary text-white overflow-hidden">
                    <div class="card-body p-5 position-relative">
                        <div class="position-relative z-1">
                            <h4 class="fw-bold"><i class="fas fa-clock me-2"></i> Schedule Information</h4>
                            <p class="lead opacity-75 mb-0">
                                The automated crawler is scheduled to run daily at <strong>00:00 (Midnight)</strong>.
                                It automatically validates event locations (Malaysia only) and filters out irrelevant
                                content.
                            </p>
                        </div>
                        <i
                            class="fas fa-sync-alt fa-10x position-absolute top-50 end-0 translate-middle-y opacity-25 me-n5"></i>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection