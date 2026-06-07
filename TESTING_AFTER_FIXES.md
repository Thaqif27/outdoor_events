# 🧪 TESTING GUIDE - After Fixes

## What Was Wrong
You were getting **500 SERVER ERROR** on two pages:
1. **View Details** (clicking event details)
2. **Map Tab** (clicking map tab)

## Why It Was Happening
✗ **Event details page** had corrupted HTML/Blade code mixed with profile page content
✗ **Map route** was being caught by the resource route catch-all (`{id}`)

## What's Fixed
✅ Event details page completely rewritten and cleaned
✅ Map route moved BEFORE resource declaration (fixed route order)
✅ All views and routes recached

---

## 🎯 TEST STEPS

### **Step 1: Test Event Details Page**
```
1. Visit: http://outdoor-events.test/user/events
2. Click "View Details" button on any event
3. Expected: Shows event name, image, details, buttons
4. Problem solved? ✅ YES (if no 500 error)
```

### **Step 2: Test Map Page**
```
1. Visit: http://outdoor-events.test/user/events/map
2. Expected: Map loads with your location (blue pin)
3. Problem solved? ✅ YES (if no 500 error and map shows)
```

### **Step 3: Test Map Geolocation**
```
1. On map page, browser may ask for location permission
2. Click "Allow"
3. Expected: Blue marker appears at your location
4. Expected: Colored pins show nearby events
5. Problem solved? ✅ YES (if markers appear)
```

### **Step 4: Test Event Details from Map**
```
1. On map page, click any event pin
2. In popup, click "View Details" button
3. Expected: Goes to event details page
4. Expected: Shows event information
5. Problem solved? ✅ YES (if page loads without error)
```

---

## ⚠️ Troubleshooting

### **Still Getting 500 Error on Details Page?**
- Clear cache: `php artisan view:clear`
- Refresh page: `Ctrl+Shift+Del` (hard refresh)
- Check logs: `Get-Content storage/logs/laravel.log -Tail 50`

### **Map Page Still 500?**
- Clear routes cache: `php artisan route:clear`
- Restart Laravel: `php artisan serve`
- Check API key: `php artisan tinker` → `config('services.google_maps.api_key')`

### **Map Loads But No Markers?**
- Browser console (F12) for JavaScript errors
- Allow location permission when prompted
- Check API key is set in `.env`: `GOOGLE_MAPS_API_KEY=...`

### **Markers Showing But No Location?**
- Browser location permissions settings might be blocking it
- Try different browser
- Check if running on HTTPS/localhost (required for geolocation)

---

## 📊 What Each Page Should Show

### **Events List Page** (`/user/events`)
```
✓ List of all events
✓ Search box (top)
✓ Category filters (Running/Hiking/Cycling)
✓ "View Details" button on each event
✓ "Map" button/tab in header
```

### **Event Details Page** (`/user/events/{id}`)
```
✓ Event name and image
✓ Category badge (green/yellow/cyan)
✓ Status badge (upcoming/completed)
✓ Date and location
✓ Description and details
✓ Participants count
✓ Price
✓ Favourite button
✓ Edit/Delete buttons (if you own the event)
✓ "Back to Events" button
```

### **Map Page** (`/user/events/map`)
```
✓ Full-screen map
✓ Blue marker (your location)
✓ Colored pins for events:
  - Green = Running
  - Yellow = Hiking  
  - Cyan = Cycling
✓ Info panel (top-right):
  - Your location coordinates
  - Nearby events count
  - "Center on Me" button
✓ Click pins to see event popup
✓ Popup has "View Details" link
```

---

## 🔧 Quick Command Reference

```bash
# If something still doesn't work, try these:

# Clear all caches
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Rebuild caches
php artisan route:cache
php artisan config:cache

# Check for errors
php artisan config
php artisan list

# Restart the development server
# (Stop with Ctrl+C, then restart)
php artisan serve
```

---

## ✅ Success Indicators

You'll know everything is working when:
1. ✅ Visiting `/user/events` shows list without error
2. ✅ Clicking "View Details" loads event page without 500 error
3. ✅ Clicking "Map" loads map without 500 error
4. ✅ Map shows blue marker when location allowed
5. ✅ Event pins appear on map with correct colors
6. ✅ Clicking pins shows event info with distance
7. ✅ "View Details" link in map popup works

---

**System Status: ✅ FIXED AND READY**

If any of these steps fail, check the browser console (F12) and server logs for specific error messages.
