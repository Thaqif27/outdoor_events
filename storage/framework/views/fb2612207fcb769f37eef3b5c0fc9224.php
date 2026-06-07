

<?php $__env->startSection('title', 'My Schedule'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container py-5">
        <!-- Header -->
        <div class="mb-5">
            <h1 class="display-4 fw-bold mb-2">📅 My Event Schedule</h1>
            <p class="text-muted fs-5">Your upcoming events organized by category</p>
        </div>

        <?php
            $totalEvents = $runEvents->count() + $hikeEvents->count() + $cyclingEvents->count() + $otherEvents->count();
        ?>

        <!-- Stats Overview -->
        <?php if($totalEvents > 0): ?>
            <div class="row g-3 mb-5">
                <div class="col-6 col-md-3">
                    <div class="card border-0 bg-success bg-opacity-10 text-center h-100">
                        <div class="card-body">
                            <h5 class="text-success mb-2">🏃 Running</h5>
                            <h3 class="fw-bold text-success"><?php echo e($runEvents->count()); ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 bg-warning bg-opacity-10 text-center h-100">
                        <div class="card-body">
                            <h5 class="text-warning mb-2">🥾 Hiking</h5>
                            <h3 class="fw-bold text-warning"><?php echo e($hikeEvents->count()); ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 bg-info bg-opacity-10 text-center h-100">
                        <div class="card-body">
                            <h5 class="text-info mb-2">🚴 Cycling</h5>
                            <h3 class="fw-bold text-info"><?php echo e($cyclingEvents->count()); ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 bg-secondary bg-opacity-10 text-center h-100">
                        <div class="card-body">
                            <h5 class="text-secondary mb-2">📌 Other</h5>
                            <h3 class="fw-bold text-secondary"><?php echo e($otherEvents->count()); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center py-5">
                <i class="fas fa-calendar-plus fs-1 mb-3 d-block"></i>
                <h5>No Events Scheduled Yet</h5>
                <p class="mb-0">Join events from the Explore Events page to add them to your schedule!</p>
                <a href="<?php echo e(route('user.events.index')); ?>" class="btn btn-primary mt-3">
                    <i class="fas fa-search"></i> Explore Events
                </a>
            </div>
        <?php endif; ?>

        <!-- Running Events -->
        <?php if($runEvents->isNotEmpty()): ?>
            <div class="mb-5">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="fas fa-running fa-2x text-success"></i>
                    </div>
                    <h3 class="mb-0">Running Events</h3>
                </div>
                <div class="row g-3">
                    <?php $__currentLoopData = $runEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm border-0 transition" style="cursor: pointer; transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'" onclick="window.location='<?php echo e(route('user.events.show', $event)); ?>'">
                                <div class="card-body">
                                    <span class="badge bg-success mb-2">🏃 Running</span>
                                    <h5 class="card-title fw-bold mb-3"><?php echo e($event->name); ?></h5>
                                    
                                    <div class="mb-2">
                                        <small class="text-muted d-block mb-1">
                                            <i class="fas fa-calendar text-primary me-2"></i><?php echo e($event->event_date->format('M d, Y')); ?>

                                        </small>
                                        <small class="text-muted d-block mb-1">
                                            <i class="fas fa-clock text-warning me-2"></i><?php echo e(date('g:i A', strtotime($event->event_time))); ?>

                                        </small>
                                        <small class="text-muted d-block">
                                            <i class="fas fa-map-marker-alt text-danger me-2"></i><?php echo e($event->location); ?>

                                        </small>
                                    </div>
                                    
                                    <a href="<?php echo e(route('user.events.show', $event)); ?>" class="btn btn-sm btn-outline-primary w-100 mt-3">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Hiking Events -->
        <?php if($hikeEvents->isNotEmpty()): ?>
            <div class="mb-5">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="fas fa-mountain fa-2x text-warning"></i>
                    </div>
                    <h3 class="mb-0">Hiking Events</h3>
                </div>
                <div class="row g-3">
                    <?php $__currentLoopData = $hikeEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm border-0 transition" style="cursor: pointer; transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'" onclick="window.location='<?php echo e(route('user.events.show', $event)); ?>'">
                                <div class="card-body">
                                    <span class="badge bg-warning mb-2">🥾 Hiking</span>
                                    <h5 class="card-title fw-bold mb-3"><?php echo e($event->name); ?></h5>
                                    
                                    <div class="mb-2">
                                        <small class="text-muted d-block mb-1">
                                            <i class="fas fa-calendar text-primary me-2"></i><?php echo e($event->event_date->format('M d, Y')); ?>

                                        </small>
                                        <small class="text-muted d-block mb-1">
                                            <i class="fas fa-clock text-warning me-2"></i><?php echo e(date('g:i A', strtotime($event->event_time))); ?>

                                        </small>
                                        <small class="text-muted d-block">
                                            <i class="fas fa-map-marker-alt text-danger me-2"></i><?php echo e($event->location); ?>

                                        </small>
                                    </div>
                                    
                                    <a href="<?php echo e(route('user.events.show', $event)); ?>" class="btn btn-sm btn-outline-primary w-100 mt-3">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Cycling Events -->
        <?php if($cyclingEvents->isNotEmpty()): ?>
            <div class="mb-5">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-info bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="fas fa-biking fa-2x text-info"></i>
                    </div>
                    <h3 class="mb-0">Cycling Events</h3>
                </div>
                <div class="row g-3">
                    <?php $__currentLoopData = $cyclingEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm border-0 transition" style="cursor: pointer; transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'" onclick="window.location='<?php echo e(route('user.events.show', $event)); ?>'">
                                <div class="card-body">
                                    <span class="badge bg-info mb-2">🚴 Cycling</span>
                                    <h5 class="card-title fw-bold mb-3"><?php echo e($event->name); ?></h5>
                                    
                                    <div class="mb-2">
                                        <small class="text-muted d-block mb-1">
                                            <i class="fas fa-calendar text-primary me-2"></i><?php echo e($event->event_date->format('M d, Y')); ?>

                                        </small>
                                        <small class="text-muted d-block mb-1">
                                            <i class="fas fa-clock text-warning me-2"></i><?php echo e(date('g:i A', strtotime($event->event_time))); ?>

                                        </small>
                                        <small class="text-muted d-block">
                                            <i class="fas fa-map-marker-alt text-danger me-2"></i><?php echo e($event->location); ?>

                                        </small>
                                    </div>
                                    
                                    <a href="<?php echo e(route('user.events.show', $event)); ?>" class="btn btn-sm btn-outline-primary w-100 mt-3">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Other Events -->
        <?php if($otherEvents->isNotEmpty()): ?>
            <div class="mb-5">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-secondary bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="fas fa-calendar-alt fa-2x text-secondary"></i>
                    </div>
                    <h3 class="mb-0">Other Events</h3>
                </div>
                <div class="row g-3">
                    <?php $__currentLoopData = $otherEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm border-0 transition" style="cursor: pointer; transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'" onclick="window.location='<?php echo e(route('user.events.show', $event)); ?>'">
                                <div class="card-body">
                                    <span class="badge bg-secondary mb-2">📌 Other</span>
                                    <h5 class="card-title fw-bold mb-3"><?php echo e($event->name); ?></h5>
                                    
                                    <div class="mb-2">
                                        <small class="text-muted d-block mb-1">
                                            <i class="fas fa-calendar text-primary me-2"></i><?php echo e($event->event_date->format('M d, Y')); ?>

                                        </small>
                                        <small class="text-muted d-block mb-1">
                                            <i class="fas fa-clock text-warning me-2"></i><?php echo e(date('g:i A', strtotime($event->event_time))); ?>

                                        </small>
                                        <small class="text-muted d-block">
                                            <i class="fas fa-map-marker-alt text-danger me-2"></i><?php echo e($event->location); ?>

                                        </small>
                                    </div>
                                    
                                    <a href="<?php echo e(route('user.events.show', $event)); ?>" class="btn btn-sm btn-outline-primary w-100 mt-3">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Back Button -->
        <div class="mt-5 text-center">
            <a href="<?php echo e(route('user.events.index')); ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Explore Events
            </a>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\outdoor-events\resources\views/user/timetable/index.blade.php ENDPATH**/ ?>