

<?php $__env->startSection('title', 'Manage Events'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-5 animate-fade-in">
             <div>
                <h2 class="fw-bold text-success display-6"><i class="fas fa-calendar-check me-2"></i> Manage Events</h2>
                <p class="text-muted">Create, edit, and oversee all system events.</p>
            </div>
            
            <div class="d-flex gap-3 align-items-center">
                 <form action="<?php echo e(route('admin.events.index')); ?>" method="GET" class="d-flex">
                    <div class="input-group glass rounded-pill p-1">
                        <span class="input-group-text border-0 bg-transparent text-muted ms-2"><i class="fas fa-filter"></i></span>
                        <select name="category" class="form-select border-0 bg-transparent focus-none" onchange="this.form.submit()" style="min-width: 150px; cursor: pointer;">
                            <option value="">All Categories</option>
                            <option value="running" <?php echo e(request('category') == 'running' ? 'selected' : ''); ?>>Running</option>
                            <option value="hiking" <?php echo e(request('category') == 'hiking' ? 'selected' : ''); ?>>Hiking</option>
                            <option value="cycling" <?php echo e(request('category') == 'cycling' ? 'selected' : ''); ?>>Cycling</option>
                        </select>
                    </div>
                </form>
                <a href="<?php echo e(route('admin.events.create')); ?>" class="btn btn-primary rounded-pill px-4 shadow-sm">
                    <i class="fas fa-plus me-2"></i> Create New Event
                </a>
            </div>
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 animate-fade-in">
                <i class="fas fa-check-circle me-2"></i> <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-lg animate-fade-in delay-1 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="py-3 ps-4 text-uppercase text-muted small fw-bold">Event Details</th>
                                <th class="py-3 text-uppercase text-muted small fw-bold">Date & Time</th>
                                <th class="py-3 text-uppercase text-muted small fw-bold">Category</th>
                                <th class="py-3 text-uppercase text-muted small fw-bold text-center">Status</th>
                                <th class="py-3 text-uppercase text-muted small fw-bold text-center">Price</th>
                                <th class="py-3 pe-4 text-uppercase text-muted small fw-bold text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            <?php $__empty_1 = true; $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="ps-4" style="min-width: 300px;">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 position-relative">
                                                <?php if($event->image_url): ?>
                                                      <img src="<?php echo e($event->image_url); ?>"
                                                          alt="<?php echo e($event->name); ?>"
                                                          class="rounded-3 shadow-sm object-fit-cover"
                                                          width="60" height="60"
                                                          onerror="this.onerror=null;this.src='<?php echo e($event->fallback_image_url); ?>';">
                                                <?php else: ?>
                                                     <?php
                                                        $grad = match($event->category) {
                                                            'running' => 'linear-gradient(135deg, #FF6B6B, #556270)',
                                                            'hiking' => 'linear-gradient(135deg, #11998e, #38ef7d)',
                                                            'cycling' => 'linear-gradient(135deg, #FDC830, #F37335)',
                                                            default => 'linear-gradient(135deg, #2E3192, #1BFFFF)'
                                                        };
                                                    ?>
                                                    <div class="rounded-3 shadow-sm d-flex align-items-center justify-content-center text-white"
                                                        style="width: 60px; height: 60px; background: <?php echo e($grad); ?>;">
                                                        <i class="fas fa-camera"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="ms-3">
                                                <h6 class="mb-1 fw-bold text-dark text-truncate" style="max-width: 250px;"><?php echo e($event->name); ?></h6>
                                                <small class="text-muted d-block">
                                                    <i class="fas fa-map-marker-alt me-1 text-danger"></i> <?php echo e(Str::limit($event->location, 25)); ?>

                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-dark"><?php echo e(\Carbon\Carbon::parse($event->event_date)->format('M d, Y')); ?></span>
                                            <span class="text-muted small"><i class="far fa-clock me-1"></i> <?php echo e($event->event_time); ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill bg-<?php echo e($event->category === 'running' ? 'danger' : ($event->category === 'hiking' ? 'success' : 'warning text-dark')); ?> px-3 py-2 shadow-sm">
                                            <?php echo e(ucfirst($event->category)); ?>

                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                            $statusColors = [
                                                'upcoming' => 'primary',
                                                'ongoing' => 'success',
                                                'completed' => 'secondary',
                                                'cancelled' => 'danger'
                                            ];
                                        ?>
                                        <span class="badge bg-<?php echo e($statusColors[$event->status] ?? 'secondary'); ?> rounded-pill text-uppercase" style="font-size: 0.7rem;">
                                            <?php echo e(ucfirst($event->status)); ?>

                                        </span>
                                        <div class="mt-1 small text-muted">
                                            <?php echo e($event->participants->count()); ?> / <?php echo e($event->max_participants); ?>

                                        </div>
                                    </td>
                                    <td class="text-center">
                                         <?php if($event->price > 0): ?>
                                            <span class="fw-bold text-dark">RM <?php echo e(number_format($event->price, 2)); ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-success-subtle text-success border border-success px-2">Free</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <div class="btn-group shadow-sm rounded-pill" role="group">
                                            <a href="<?php echo e(route('admin.events.edit', $event)); ?>"
                                                class="btn btn-sm btn-light text-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="<?php echo e(route('admin.events.destroy', $event)); ?>" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this event? This action cannot be undone.');">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-sm btn-light text-danger" title="Delete" style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="opacity-50 mb-3">
                                            <i class="fas fa-folder-open fa-3x text-muted"></i>
                                        </div>
                                        <h5 class="fw-bold text-muted">No events found.</h5>
                                        <p class="text-muted small">Create a new event to get started.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if($events->hasPages()): ?>
                <div class="p-4 border-top">
                    <?php echo e($events->links()); ?>

                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <style>
        .focus-none:focus {
            box-shadow: none;
            outline: none;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\outdoor-events\resources\views/admin/events/index.blade.php ENDPATH**/ ?>