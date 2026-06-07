

<?php $__env->startSection('title', 'Admin Dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-5 animate-fade-in">
            <div>
                <h2 class="fw-bold text-success display-6">Admin Dashboard</h2>
                <p class="text-muted">Overview of platform performance</p>
            </div>
            <div class="glass px-4 py-2 rounded-pill">
                <i class="fas fa-user-shield text-primary me-2"></i> <?php echo e(auth()->user()->name); ?>

            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-3 animate-fade-in delay-1">
                <div class="card border-0 shadow-sm h-100 overflow-hidden text-white"
                    style="background: linear-gradient(135deg, #0288D1, #26C6DA);">
                    <div class="card-body p-4 position-relative">
                        <h5 class="card-title fw-bold opacity-75">📅 Total Events</h5>
                        <h2 class="display-4 fw-bold mt-2 mb-0"><?php echo e($totalEvents); ?></h2>
                        <i class="fas fa-calendar-alt fa-4x position-absolute bottom-0 end-0 mb-n2 me-3 opacity-25"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 animate-fade-in delay-2">
                <div class="card border-0 shadow-sm h-100 overflow-hidden text-white"
                    style="background: linear-gradient(135deg, #F57C00, #FFB74D);">
                    <div class="card-body p-4 position-relative">
                        <h5 class="card-title fw-bold opacity-75">👥 Total Users</h5>
                        <h2 class="display-4 fw-bold mt-2 mb-0"><?php echo e($totalUsers); ?></h2>
                        <i class="fas fa-users fa-4x position-absolute bottom-0 end-0 mb-n2 me-3 opacity-25"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 animate-fade-in delay-3">
                <div class="card border-0 shadow-sm h-100 overflow-hidden text-white"
                    style="background: linear-gradient(135deg, #2E7D32, #66BB6A);">
                    <div class="card-body p-4 position-relative">
                        <h5 class="card-title fw-bold opacity-75">⏰ Upcoming Events</h5>
                        <h2 class="display-4 fw-bold mt-2 mb-0"><?php echo e($upcomingEvents); ?></h2>
                        <i class="fas fa-stopwatch fa-4x position-absolute bottom-0 end-0 mb-n2 me-3 opacity-25"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 animate-fade-in delay-4">
                <div class="card border-0 shadow-sm h-100 overflow-hidden text-white"
                    style="background: linear-gradient(135deg, #6A1B9A, #AB47BC);">
                    <div class="card-body p-4 position-relative">
                        <h5 class="card-title fw-bold opacity-75">✅ Total Registrations</h5>
                        <h2 class="display-4 fw-bold mt-2 mb-0">
                            <?php echo e(\App\Models\Event::join('event_participants', 'events.id', '=', 'event_participants.event_id')->count()); ?>

                        </h2>
                        <i class="fas fa-check-circle fa-4x position-absolute bottom-0 end-0 mb-n2 me-3 opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions (Optional placeholder for future) -->
        <div class="row animate-fade-in delay-3">
            <div class="col-12">
                <div class="glass p-4 rounded-4">
                    <h5 class="fw-bold mb-3"><i class="fas fa-bolt text-warning me-2"></i> Quick Actions</h5>
                    <div class="d-flex gap-3">
                        <a href="<?php echo e(route('admin.events.create')); ?>" class="btn btn-outline-primary rounded-pill">
                            <i class="fas fa-plus me-1"></i> New Event
                        </a>
                        <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-outline-secondary rounded-pill">
                            <i class="fas fa-users-cog me-1"></i> Manage Users
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\outdoor-events\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>