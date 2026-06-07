

<?php $__env->startSection('title', 'Event Map'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid p-0" style="height: calc(100vh - 60px);">
        <?php if(empty($apiKey)): ?>
            <div class="d-flex justify-content-center align-items-center h-100 bg-light">
                <div class="text-center">
                    <h3>Map Configuration Required</h3>
                    <p>Please add your <code>GOOGLE_MAPS_API_KEY</code> to the <code>.env</code> file.</p>
                </div>
            </div>
        <?php else: ?>
            <div id="map" style="width: 100%; height: 100%;"></div>
            
            <!-- Info Panel -->
            <div id="info-panel" style="position: absolute; top: 20px; right: 20px; background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.2); z-index: 10; max-width: 300px; display: none;">
                <h6 class="mb-2"><i class="fas fa-map-pin"></i> Your Location</h6>
                <div id="location-info" style="font-size: 0.9rem; color: #666;">
                    Loading your location...
                </div>
                <hr class="my-2">
                <h6 class="mb-2"><i class="fas fa-search"></i> Nearby Events</h6>
                <div id="nearby-count" style="font-size: 0.9rem; color: #666;">
                    Searching...
                </div>
                <button class="btn btn-sm btn-primary w-100 mt-2" onclick="centerMapOnUser()">
                    <i class="fas fa-location-arrow"></i> Center on Me
                </button>
            </div>
        <?php endif; ?>
    </div>

    <?php if(!empty($apiKey)): ?>
        <?php $__env->startPush('scripts'); ?>
            <script>
                ((g) => { 
                    var h, a, k, p = "The Google Maps JavaScript API", c = "google", l = "importLibrary", q = "__ib__", m = document, b = window; 
                    b = b[c] || (b[c] = {}); 
                    var d = b.maps || (b.maps = {}), r = new Set, e = new URLSearchParams, u = () => h || (h = new Promise(async (f, n) => { 
                        await (a = m.createElement("script")); 
                        e.set("libraries", [...r] + ""); 
                        for (k in g) e.set(k.replace(/[A-Z]/g, t => "_" + t[0].toLowerCase()), g[k]); 
                        e.set("callback", c + ".maps." + q); 
                        a.src = `https://maps.googleapis.com/maps/api/js?` + e; 
                        d[q] = f; 
                        a.onerror = () => h = n(Error(p + " could not load.")); 
                        a.nonce = m.querySelector("script[nonce]")?.nonce || ""; 
                        m.head.append(a) 
                    })); 
                    d[l] ? console.warn(p + " only loads once. Ignoring:", g) : d[l] = (f, ...n) => r.add(f) && u().then(() => d[l](f, ...n)) 
                })({
                    key: "<?php echo e($apiKey); ?>",
                    v: "weekly",
                    libraries: ["maps", "marker"],
                });

                let map;
                let userMarker;
                let userLocation = null;
                const events = <?php echo json_encode($events, 15, 512) ?>;
                const NEARBY_RADIUS_KM = 50;

                async function initMap() {
                    try {
                        const { Map, InfoWindow } = await google.maps.importLibrary("maps");
                        const { AdvancedMarkerElement, PinElement } = await google.maps.importLibrary("marker");

                        const defaultCenter = { lat: 3.140853, lng: 101.693207 };

                        map = new Map(document.getElementById("map"), {
                            zoom: 12,
                            center: defaultCenter,
                            mapId: "DEMO_MAP_ID",
                            tilt: 0,
                            heading: 0,
                        });

                        const infoWindow = new InfoWindow();

                        // Show info panel immediately to inform user
                        document.getElementById('info-panel').style.display = 'block';

                        if (navigator.geolocation) {
                            console.log("Geolocation API available. Requesting location...");
                            navigator.geolocation.getCurrentPosition(
                                function(position) {
                                    console.log("✓ Location granted!", position.coords);
                                    userLocation = {
                                        lat: position.coords.latitude,
                                        lng: position.coords.longitude
                                    };

                                    const userPin = new PinElement({
                                        background: "#0d6efd",
                                        borderColor: "#ffffff",
                                        glyphColor: "#ffffff",
                                    });

                                    userMarker = new AdvancedMarkerElement({
                                        map: map,
                                        position: userLocation,
                                        content: userPin.element,
                                        title: "Your Location",
                                    });

                                    map.setCenter(userLocation);

                                    document.getElementById('location-info').innerHTML = 
                                        `<strong>✓ Location Found!</strong><br><strong>Lat:</strong> ${userLocation.lat.toFixed(4)}<br>
                                         <strong>Lng:</strong> ${userLocation.lng.toFixed(4)}`;

                                    addMarkers(events, userLocation, infoWindow, AdvancedMarkerElement, PinElement);
                                },
                                function(error) {
                                    console.warn("✗ Location error:", error.code, error.message);
                                    let errorMsg = "Location not available";
                                    
                                    switch(error.code) {
                                        case error.PERMISSION_DENIED:
                                            errorMsg = "📍 Permission Denied - You blocked location access";
                                            break;
                                        case error.POSITION_UNAVAILABLE:
                                            errorMsg = "📍 Position Unavailable";
                                            break;
                                        case error.TIMEOUT:
                                            errorMsg = "📍 Request Timeout";
                                            break;
                                    }
                                    
                                    document.getElementById('location-info').innerHTML = 
                                        `<span class="text-warning" style="font-size: 0.85rem;"><i class="fas fa-exclamation-triangle"></i> ${errorMsg}<br><small style="color: #999;">Showing all events within Malaysia</small></span>`;
                                    
                                    console.log("Showing all events (no location available)");
                                    addMarkers(events, null, infoWindow, AdvancedMarkerElement, PinElement);
                                },
                                {
                                    enableHighAccuracy: true,
                                    timeout: 10000,
                                    maximumAge: 0
                                }
                            );
                        } else {
                            console.error("Geolocation not supported by this browser");
                            document.getElementById('location-info').innerHTML = 
                                `<span class="text-danger"><i class="fas fa-times-circle"></i> Geolocation not supported</span>`;
                            addMarkers(events, null, infoWindow, AdvancedMarkerElement, PinElement);
                        }
                    } catch (error) {
                        console.error("Map initialization error:", error);
                        alert("Error loading map. Please check your Google Maps API key configuration.");
                    }
                }

                function addMarkers(filteredEvents, userLocation, infoWindow, AdvancedMarkerElement, PinElement) {
                    let eventsToShow = filteredEvents;
                    
                    // Filter events by distance if user location is available
                    if (userLocation) {
                        eventsToShow = filteredEvents.filter(event => {
                            const distance = getDistanceFromLatLonInKm(
                                userLocation.lat, 
                                userLocation.lng, 
                                parseFloat(event.latitude), 
                                parseFloat(event.longitude)
                            );
                            return distance <= NEARBY_RADIUS_KM;
                        });
                    }
                    
                    eventsToShow.forEach(event => {
                        const pinColor = event.category === 'running' ? '#28a745' : 
                                        (event.category === 'hiking' ? '#ffc107' : '#17a2b8');
                        
                        let distance = null;
                        
                        if (userLocation) {
                            distance = getDistanceFromLatLonInKm(
                                userLocation.lat, 
                                userLocation.lng, 
                                parseFloat(event.latitude), 
                                parseFloat(event.longitude)
                            );
                        }

                        const pin = new PinElement({
                            background: pinColor,
                            borderColor: "#ffffff",
                            glyphColor: "#ffffff",
                        });

                        const marker = new AdvancedMarkerElement({
                            map: map,
                            position: { lat: parseFloat(event.latitude), lng: parseFloat(event.longitude) },
                            content: pin.element,
                            title: event.name,
                        });

                        marker.addListener("click", () => {
                            infoWindow.close();
                            const distanceInfo = distance ? `<p class="text-muted small"><i class="fas fa-ruler"></i> ${distance.toFixed(2)} km away</p>` : '';
                            const categoryIcon = event.category === 'running' ? '🏃' : 
                                               (event.category === 'hiking' ? '🥾' : '🚴');
                            
                            infoWindow.setContent(`
                                <div style="min-width: 250px; padding: 10px;">
                                    <h6 class="mb-2">${categoryIcon} ${event.name}</h6>
                                    <span class="badge bg-${event.category === 'running' ? 'success' : (event.category === 'hiking' ? 'warning' : 'info')} mb-2">${event.category.toUpperCase()}</span>
                                    ${distanceInfo}
                                    <p class="mb-1"><small><i class="fas fa-calendar"></i> ${new Date(event.event_date).toLocaleDateString()}</small></p>
                                    <p class="mb-2 text-muted"><small><i class="fas fa-map-marker-alt"></i> ${event.location}</small></p>
                                    <a href="/user/events/${event.id}" class="btn btn-sm btn-outline-primary w-100">View Details</a>
                                </div>
                            `);
                            infoWindow.open(marker.map, marker);
                        });
                    });

                    if (userLocation) {
                        document.getElementById('nearby-count').innerHTML = 
                            `Found <strong>${eventsToShow.length}</strong> events within ${NEARBY_RADIUS_KM}km`;
                    } else {
                        document.getElementById('nearby-count').innerHTML = 
                            `Showing <strong>${eventsToShow.length}</strong> events`;
                    }
                }

                function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
                    const R = 6371;
                    const dLat = deg2rad(lat2 - lat1);
                    const dLon = deg2rad(lon2 - lon1);
                    const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                             Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
                             Math.sin(dLon/2) * Math.sin(dLon/2);
                    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
                    return R * c;
                }

                function deg2rad(deg) {
                    return deg * (Math.PI/180);
                }

                function centerMapOnUser() {
                    console.log("Center on Me clicked. userLocation:", userLocation, "map:", !!map);
                    
                    if (!map) {
                        console.error("Map not initialized yet");
                        alert("Map is still loading. Please try again.");
                        return;
                    }
                    
                    if (userLocation) {
                        console.log("Centering on user location:", userLocation);
                        map.setCenter(userLocation);
                        map.setZoom(13);
                        if (userMarker) {
                            userMarker.map = map;
                        }
                    } else {
                        console.warn("User location not available. Requesting geolocation again...");
                        
                        // Try to request location again
                        if (navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition(
                                function(position) {
                                    console.log("✓ Location granted on retry!", position.coords);
                                    userLocation = {
                                        lat: position.coords.latitude,
                                        lng: position.coords.longitude
                                    };
                                    
                                    // Create user pin
                                    google.maps.importLibrary("marker").then(({ PinElement, AdvancedMarkerElement }) => {
                                        const userPin = new PinElement({
                                            background: "#0d6efd",
                                            borderColor: "#ffffff",
                                            glyphColor: "#ffffff",
                                        });
                                        
                                        userMarker = new AdvancedMarkerElement({
                                            map: map,
                                            position: userLocation,
                                            content: userPin.element,
                                            title: "Your Location",
                                        });
                                        
                                        map.setCenter(userLocation);
                                        map.setZoom(13);
                                        
                                        document.getElementById('location-info').innerHTML = 
                                            `<strong>✓ Location Found!</strong><br><strong>Lat:</strong> ${userLocation.lat.toFixed(4)}<br>
                                             <strong>Lng:</strong> ${userLocation.lng.toFixed(4)}`;
                                        
                                        alert("Location found! Map centered on your location.");
                                    });
                                },
                                function(error) {
                                    console.error("Location request failed:", error);
                                    alert("❌ Location access denied or unavailable.\n\nTo enable location:\n1. Click the 🔒 icon in your address bar\n2. Click the X next to 'Location'\n3. Refresh the page\n4. Allow location access");
                                }
                            );
                        } else {
                            alert("⚠️ Geolocation is not supported by your browser");
                        }
                    }
                }

                initMap();
            </script>
        <?php $__env->stopPush(); ?>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\outdoor-events\resources\views/user/events/map.blade.php ENDPATH**/ ?>