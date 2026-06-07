

<?php $__env->startSection('title', 'My Dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container py-5">

        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-5 animate-fade-in">
            <div>
                <h2 class="fw-bold text-success display-6">My Dashboard</h2>
                <p class="text-muted">Welcome back, <?php echo e(auth()->user()->name); ?>!</p>
            </div>
            <div class="glass px-4 py-2 rounded-pill">
                <i class="fas fa-user-circle text-primary me-2"></i> User Account
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-5">
            <div class="col-md-4 animate-fade-in delay-1">
                <div class="card border-0 shadow-sm h-100 overflow-hidden text-white"
                    style="background: linear-gradient(135deg, #0288D1, #26C6DA);">
                    <div class="card-body p-4 position-relative">
                        <h5 class="card-title fw-bold opacity-75">Events Joined</h5>
                        <h2 class="display-4 fw-bold mt-2 mb-0"><?php echo e($stats['joined']); ?></h2>
                        <i class="fas fa-running fa-4x position-absolute bottom-0 end-0 mb-n2 me-3 opacity-25"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4 animate-fade-in delay-2">
                <div class="card border-0 shadow-sm h-100 overflow-hidden text-white"
                    style="background: linear-gradient(135deg, #F57C00, #FFB74D);">
                    <div class="card-body p-4 position-relative">
                        <h5 class="card-title fw-bold opacity-75">My Favourites</h5>
                        <h2 class="display-4 fw-bold mt-2 mb-0"><?php echo e($stats['favourites']); ?></h2>
                        <i class="fas fa-heart fa-4x position-absolute bottom-0 end-0 mb-n2 me-3 opacity-25"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4 animate-fade-in delay-3">
                <div class="card border-0 shadow-sm h-100 overflow-hidden text-white"
                    style="background: linear-gradient(135deg, #2E7D32, #66BB6A);">
                    <div class="card-body p-4 position-relative">
                        <h5 class="card-title fw-bold opacity-75">Upcoming Events</h5>
                        <h2 class="display-4 fw-bold mt-2 mb-0"><?php echo e($stats['upcoming']); ?></h2>
                        <i class="fas fa-calendar-alt fa-4x position-absolute bottom-0 end-0 mb-n2 me-3 opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions / Find Adventures Link -->
        <div class="glass p-5 rounded-4 text-center animate-fade-in delay-2">
            <h3 class="fw-bold mb-3">Ready for your next adventure?</h3>
            <p class="text-muted mb-4 lead">Browse through hundreds of upcoming runs, hikes, and cycling events.</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="<?php echo e(route('user.events.index')); ?>" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm">
                    <i class="fas fa-search me-2"></i> Find Adventures
                </a>
                <a href="<?php echo e(route('user.events.create')); ?>" class="btn btn-success btn-lg rounded-pill px-5 shadow-sm">
                    <i class="fas fa-plus me-2"></i> Create Event
                </a>
                <a href="<?php echo e(route('user.events.map')); ?>" class="btn btn-outline-success btn-lg rounded-pill px-5">
                    <i class="fas fa-map-marked-alt me-2"></i> View Map
                </a>
            </div>
        </div>

    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\outdoor-events\resources\views/user/dashboard.blade.php ENDPATH**/ ?>