

<?php $__env->startSection('title', 'Edit Event'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-bold text-success display-6"><i class="fas fa-edit me-2"></i> Edit Event</h2>
                <p class="text-muted"><?php echo e($event->name); ?></p>
            </div>
            <a href="<?php echo e(route('admin.events.index')); ?>" class="btn btn-outline-secondary rounded-pill">
                <i class="fas fa-arrow-left me-2"></i>Back to Events
            </a>
        </div>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
                <i class="fas fa-exclamation-circle me-2"></i> <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-lg">
            <div class="card-body p-5">
                <form action="<?php echo e(route('admin.events.update', $event)); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <!-- Event Basic Info -->
                    <h5 class="fw-bold mb-4"><i class="fas fa-info-circle text-primary me-2"></i> Event Information</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label fw-bold">Event Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="name"
                                name="name" value="<?php echo e(old('name', $event->name)); ?>" required>
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                            <select class="form-select <?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="category"
                                name="category" required>
                                <option value="">Select Category</option>
                                <option value="running" <?php echo e(old('category', $event->category) == 'running' ? 'selected' : ''); ?>>🏃 Running</option>
                                <option value="hiking" <?php echo e(old('category', $event->category) == 'hiking' ? 'selected' : ''); ?>>🥾 Hiking</option>
                                <option value="cycling" <?php echo e(old('category', $event->category) == 'cycling' ? 'selected' : ''); ?>>🚴 Cycling</option>
                            </select>
                            <?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="description" class="form-label fw-bold">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="description"
                                name="description" rows="5"
                                required><?php echo e(old('description', $event->description)); ?></textarea>
                            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Date & Time -->
                    <h5 class="fw-bold mb-4"><i class="fas fa-calendar text-primary me-2"></i> Date & Time</h5>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="event_date" class="form-label fw-bold">Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control <?php $__errorArgs = ['event_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="event_date" name="event_date" value="<?php echo e(old('event_date', $event->event_date->format('Y-m-d'))); ?>"
                                required>
                            <?php $__errorArgs = ['event_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="event_time" class="form-label fw-bold">Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control <?php $__errorArgs = ['event_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="event_time" name="event_time" value="<?php echo e(old('event_time', $event->event_time)); ?>"
                                required>
                            <?php $__errorArgs = ['event_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="location" class="form-label fw-bold">Location <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="location"
                                name="location" value="<?php echo e(old('location', $event->location)); ?>" required>
                            <?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                        <small class="text-muted d-block">Click on the map to update event coordinates</small>
                        <input type="hidden" id="latitude" name="latitude"
                            value="<?php echo e(old('latitude', $event->latitude)); ?>">
                        <input type="hidden" id="longitude" name="longitude"
                            value="<?php echo e(old('longitude', $event->longitude)); ?>">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="latitude_display" class="form-label fw-bold">Latitude</label>
                            <input type="text" class="form-control" id="latitude_display" disabled 
                                value="<?php echo e($event->latitude ?? 'Click on map'); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="longitude_display" class="form-label fw-bold">Longitude</label>
                            <input type="text" class="form-control" id="longitude_display" disabled 
                                value="<?php echo e($event->longitude ?? 'Click on map'); ?>">
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Event Details -->
                    <h5 class="fw-bold mb-4"><i class="fas fa-details text-primary me-2"></i> Event Details</h5>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="max_participants" class="form-label fw-bold">Max Participants <span class="text-danger">*</span></label>
                            <input type="number" class="form-control <?php $__errorArgs = ['max_participants'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="max_participants" name="max_participants"
                                value="<?php echo e(old('max_participants', $event->max_participants)); ?>" min="1" required>
                            <?php $__errorArgs = ['max_participants'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="price" class="form-label fw-bold">Price (RM) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="price" name="price" value="<?php echo e(old('price', $event->price)); ?>" min="0" required>
                            <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="status" class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                            <select class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="status" name="status" required>
                                <option value="upcoming" <?php echo e(old('status', $event->status) == 'upcoming' ? 'selected' : ''); ?>>📅 Upcoming</option>
                                <option value="ongoing" <?php echo e(old('status', $event->status) == 'ongoing' ? 'selected' : ''); ?>>🔴 Ongoing</option>
                                <option value="completed" <?php echo e(old('status', $event->status) == 'completed' ? 'selected' : ''); ?>>✅ Completed</option>
                                <option value="cancelled" <?php echo e(old('status', $event->status) == 'cancelled' ? 'selected' : ''); ?>>❌ Cancelled</option>
                            </select>
                            <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="source_url" class="form-label fw-bold">External Link</label>
                            <input type="url" class="form-control <?php $__errorArgs = ['source_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="source_url" name="source_url" value="<?php echo e(old('source_url', $event->source_url)); ?>"
                                placeholder="https://example.com/event">
                            <?php $__errorArgs = ['source_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="text-muted d-block mt-1">Link to original event website for user registration</small>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Image -->
                    <h5 class="fw-bold mb-4"><i class="fas fa-image text-primary me-2"></i> Event Image</h5>
                    
                    <div class="mb-3">
                        <?php if($event->image_url): ?>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Current Image</label>
                                <div>
                                    <img src="<?php echo e($event->image_url); ?>"
                                        alt="Current Image" class="rounded shadow-sm" width="150" height="150" style="object-fit: cover;"
                                        onerror="this.onerror=null;this.src='<?php echo e($event->fallback_image_url); ?>';">
                                </div>
                            </div>
                        <?php endif; ?>
                        <label for="image" class="form-label fw-bold">Upload New Image</label>
                        <input type="file" class="form-control <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="image"
                            name="image" accept="image/*">
                        <small class="text-muted d-block mt-1">Supported formats: JPEG, PNG, JPG (Max 2MB)</small>
                        <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <hr class="my-4">

                    <!-- Buttons -->
                    <div class="d-flex gap-3 justify-content-end">
                        <a href="<?php echo e(route('admin.events.index')); ?>" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            <i class="fas fa-check me-2"></i>Update Event
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=<?php echo e(config('services.google_maps.api_key')); ?>&libraries=places&v=weekly"></script>
    <script>
        let map, marker, autocomplete;
        function initMap() {
            // Default to event location or KL
            const lat = parseFloat(document.getElementById("latitude").value) || 3.140853;
            const lng = parseFloat(document.getElementById("longitude").value) || 101.693207;
            const eventLoc = { lat: lat, lng: lng };

            map = new google.maps.Map(document.getElementById("map-picker"), {
                zoom: 13,
                center: eventLoc,
                mapId: "ADMIN_EVENT_EDIT_MAP"
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

            // Place initial marker if we have coords
            if (document.getElementById("latitude").value) {
                placeMarkerAndPanTo(eventLoc, map);
            }

            map.addListener("click", (e) => {
                placeMarkerAndPanTo(e.latLng, map);
            });
        }

        function placeMarkerAndPanTo(latLng, map) {
            if (marker) {
                marker.setPosition(latLng);
            } else {
                marker = new google.maps.Marker({ position: latLng, map: map });
            }
            document.getElementById("latitude").value = typeof latLng.lat === 'function' ? latLng.lat() : latLng.lat;
            document.getElementById("longitude").value = typeof latLng.lng === 'function' ? latLng.lng() : latLng.lng;
        }
        window.onload = initMap;
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\outdoor-events\resources\views/admin/events/edit.blade.php ENDPATH**/ ?>