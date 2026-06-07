

<?php $__env->startSection('title', 'Manage Users'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Manage Users</h2>
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

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
                            <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($user->id); ?></td>
                                    <td>
                                        <?php if($user->avatar): ?>
                                            <img src="<?php echo e(asset('storage/' . $user->avatar)); ?>" alt="<?php echo e($user->name); ?>" width="40"
                                                height="40" class="rounded-circle object-fit-cover">
                                        <?php else: ?>
                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white"
                                                style="width: 40px; height: 40px;">
                                                <?php echo e(substr($user->name, 0, 1)); ?>

                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo e($user->name); ?>

                                        <?php if($user->bio): ?>
                                            <br><small class="text-muted"
                                                title="<?php echo e($user->bio); ?>"><?php echo e(Str::limit($user->bio, 30)); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($user->email); ?></td>
                                    <td><?php echo e($user->created_at->format('M d, Y')); ?></td>
                                    <td><span class="badge bg-info"><?php echo e($user->created_events_count); ?></span></td>
                                    <td><span class="badge bg-primary"><?php echo e($user->participating_events_count); ?></span></td>
                                    <td>
                                        <form action="<?php echo e(route('admin.users.destroy', $user)); ?>" method="POST" class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="8" class="text-center">No users found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    <?php echo e($users->links()); ?>

                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\outdoor-events\resources\views/admin/users/index.blade.php ENDPATH**/ ?>